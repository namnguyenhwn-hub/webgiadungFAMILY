/**
 * FAMILY LUXE - Admin Chat Support Engine
 * Trung tâm quản lý tin nhắn và trả lời trực tiếp cho khách hàng theo thời gian thực
 */
window.FamilyAdminChat = (function () {
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    const apiBase = isWebgiadung ? '/webgiadung/api/chat' : '/api/chat';

    let currentSessionId = null;
    let conversations = [];
    let currentMessages = [];
    let pollTimer = null;
    let lastTotalUnread = 0;

    // Âm thanh báo khi có khách gửi tin nhắn mới
    function playAdminNotifyChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
            osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
            osc.frequency.setValueAtTime(783.99, ctx.currentTime + 0.2); // G5
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.45);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.45);
        } catch (e) {}
    }

    // 1. Tải danh sách các cuộc hội thoại
    function fetchConversations() {
        fetch(`${apiBase}/conversations`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            if (res && res.success && Array.isArray(res.data)) {
                conversations = res.data;
                updateNavbarBadge(res.total_unread || 0);
                renderConversationList();
                if (currentSessionId) {
                    fetchCurrentMessages(false);
                }
            } else {
                fallbackFromLocalStorage();
            }
        })
        .catch(err => {
            fallbackFromLocalStorage();
        });
    }

    // Đọc từ LocalStorage nếu đang chạy môi trường tĩnh
    function fallbackFromLocalStorage() {
        try {
            const raw = localStorage.getItem('family_chat_store');
            if (raw) {
                const store = JSON.parse(raw);
                const list = [];
                let totalUnread = 0;

                Object.keys(store).forEach(sId => {
                    const msgs = store[sId] || [];
                    if (msgs.length === 0) return;
                    const last = msgs[msgs.length - 1];
                    const unread = msgs.filter(m => m.sender_type === 'customer' && !m.is_read).length;
                    totalUnread += unread;

                    list.push({
                        session_id: sId,
                        customer_name: last.customer_name || ('Khách #' + sId.substring(0, 5)),
                        customer_email: last.customer_email || 'Chưa cung cấp',
                        customer_phone: last.customer_phone || 'Chưa cung cấp',
                        last_message: last.message,
                        last_sender: last.sender_type,
                        unread_count: unread,
                        updated_at: last.created_at || 'Gần đây',
                        timestamp: last.id || Date.now()
                    });
                });

                list.sort((a, b) => b.timestamp - a.timestamp);
                conversations = list;
                updateNavbarBadge(totalUnread);
                renderConversationList();

                if (currentSessionId) {
                    fetchCurrentMessages(false);
                }
            }
        } catch (e) {}
    }

    // Cập nhật huy hiệu unread trên Header/Navbar
    function updateNavbarBadge(count) {
        const badges = [
            document.getElementById('navChatBadge'),
            document.getElementById('adminChatUnreadBadge')
        ];
        badges.forEach(b => {
            if (!b) return;
            if (count > 0) {
                b.style.display = 'inline-block';
                b.textContent = count;
            } else {
                b.style.display = 'none';
                b.textContent = '0';
            }
        });

        if (count > lastTotalUnread && lastTotalUnread >= 0) {
            playAdminNotifyChime();
        }
        lastTotalUnread = count;
    }

    // 2. Render danh sách hội thoại bên trái
    function renderConversationList(filterText = '') {
        const container = document.getElementById('adminChatConvList');
        if (!container) return;

        const term = (filterText || '').toLowerCase().trim();
        const filtered = conversations.filter(c => {
            if (!term) return true;
            return (c.customer_name && c.customer_name.toLowerCase().includes(term)) ||
                   (c.customer_phone && c.customer_phone.includes(term)) ||
                   (c.last_message && c.last_message.toLowerCase().includes(term));
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div style="padding: 32px 16px; text-align: center; color: #94A3B8;">
                    <i class="fa-regular fa-comments" style="font-size: 32px; margin-bottom: 8px; opacity: 0.5;"></i>
                    <p style="margin: 0; font-size: 13px;">Chưa có tin nhắn nào từ khách hàng</p>
                </div>
            `;
            return;
        }

        let html = '';
        filtered.forEach(c => {
            const isActive = c.session_id === currentSessionId;
            const hasUnread = c.unread_count > 0;
            const initial = (c.customer_name ? c.customer_name.charAt(0) : 'K').toUpperCase();

            html += `
                <div class="conv-item ${isActive ? 'active' : ''} ${hasUnread ? 'has-unread' : ''}" 
                     onclick="window.FamilyAdminChat.selectConversation('${c.session_id}')"
                     style="display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-bottom: 1px solid #F1F5F9; cursor: pointer; transition: all 0.15s; background: ${isActive ? 'rgba(197, 160, 89, 0.12)' : (hasUnread ? '#FFFBEB' : '#FFFFFF')}; border-left: 3px solid ${isActive ? '#C5A059' : (hasUnread ? '#F59E0B' : 'transparent')};">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #E2E8F0, #CBD5E1); color: #334155; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; flex-shrink: 0; position: relative;">
                        ${initial}
                        ${hasUnread ? '<span style="position: absolute; top: -2px; right: -2px; width: 10px; height: 10px; background: #EF4444; border-radius: 50%; border: 2px solid #fff;"></span>' : ''}
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <strong style="font-size: 13.5px; color: #0F172A; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; max-width: 130px;">
                                ${escapeHtml(c.customer_name)}
                            </strong>
                            <span style="font-size: 11px; color: #94A3B8;">${c.updated_at ? c.updated_at.split(' ')[0] : ''}</span>
                        </div>
                        <div style="font-size: 12.5px; color: ${hasUnread ? '#B45309' : '#64748B'}; text-overflow: ellipsis; overflow: hidden; white-space: nowrap; font-weight: ${hasUnread ? '700' : '400'};">
                            ${c.last_sender === 'admin' ? '<span style="color:#C5A059;">Bạn: </span>' : ''}${escapeHtml(c.last_message || '')}
                        </div>
                    </div>
                    ${hasUnread ? `<span style="background: #EF4444; color: #fff; font-size: 11px; font-weight: 700; padding: 2px 7px; border-radius: 9999px;">${c.unread_count}</span>` : ''}
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // 3. Chọn cuộc hội thoại
    function selectConversation(sessionId) {
        currentSessionId = sessionId;
        renderConversationList();
        fetchCurrentMessages(true);
        markAsRead(sessionId);
    }

    // 4. Lấy tin nhắn của cuộc hội thoại đang chọn
    function fetchCurrentMessages(shouldScroll = true) {
        if (!currentSessionId) return;

        fetch(`${apiBase}/messages?session_id=${currentSessionId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(res => {
            if (res && res.success && Array.isArray(res.data)) {
                currentMessages = res.data;
                renderChatThread(shouldScroll);
            } else {
                readMessagesFromLocalStorage(shouldScroll);
            }
        })
        .catch(err => {
            readMessagesFromLocalStorage(shouldScroll);
        });
    }

    function readMessagesFromLocalStorage(shouldScroll) {
        try {
            const raw = localStorage.getItem('family_chat_store');
            if (raw) {
                const store = JSON.parse(raw);
                currentMessages = store[currentSessionId] || [];
                renderChatThread(shouldScroll);
            }
        } catch(e) {}
    }

    // 5. Render nội dung tin nhắn cuộc trò chuyện bên phải
    function renderChatThread(shouldScroll = true) {
        const headerEl = document.getElementById('adminChatActiveHeader');
        const bodyEl = document.getElementById('adminChatActiveBody');
        const footerEl = document.getElementById('adminChatActiveFooter');

        if (!headerEl || !bodyEl || !footerEl) return;

        const currentConv = conversations.find(c => c.session_id === currentSessionId);
        const custName = currentConv ? currentConv.customer_name : 'Khách hàng';
        const custEmail = currentConv ? currentConv.customer_email : '';
        const custPhone = currentConv ? currentConv.customer_phone : '';

        // Render Header
        headerEl.innerHTML = `
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #DFC07A, #C5A059); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;">
                    ${(custName.charAt(0) || 'K').toUpperCase()}
                </div>
                <div>
                    <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #0F172A;">
                        ${escapeHtml(custName)}
                    </h4>
                    <div style="font-size: 12px; color: #64748B; display: flex; gap: 10px; margin-top: 2px;">
                        <span><i class="fa-regular fa-envelope me-1"></i> ${escapeHtml(custEmail || 'Chưa có email')}</span>
                        <span><i class="fa-solid fa-phone me-1"></i> ${escapeHtml(custPhone || 'Chưa có SĐT')}</span>
                    </div>
                </div>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.FamilyAdminChat.refreshActiveChat()" title="Làm mới cuộc trò chuyện" style="padding: 4px 10px; font-size: 12px;">
                    <i class="fa-solid fa-rotate"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.FamilyAdminChat.deleteActiveChat()" title="Xóa cuộc trò chuyện này" style="padding: 4px 10px; font-size: 12px;">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </div>
        `;

        // Render Messages Body
        let html = '';
        if (currentMessages.length === 0) {
            html = `
                <div style="text-align: center; color: #94A3B8; padding: 40px 20px;">
                    <p style="margin: 0;">Cuộc hội thoại chưa có tin nhắn nào.</p>
                </div>
            `;
        } else {
            currentMessages.forEach(m => {
                const isAdmin = m.sender_type === 'admin';
                html += `
                    <div style="display: flex; flex-direction: column; align-items: ${isAdmin ? 'flex-end' : 'flex-start'}; margin-bottom: 14px;">
                        <span style="font-size: 11px; color: #94A3B8; margin-bottom: 3px; font-weight: 600;">
                            ${isAdmin ? '👑 Quản Trị Viên (Bạn)' : escapeHtml(m.customer_name || 'Khách')}
                        </span>
                        <div style="max-width: 75%; padding: 10px 15px; border-radius: 14px; font-size: 13.5px; line-height: 1.45; word-break: break-word; box-shadow: 0 1px 3px rgba(0,0,0,0.05); ${isAdmin ? 'background: linear-gradient(135deg, #1E293B, #0F172A); color: #FFFFFF; border-bottom-right-radius: 3px;' : 'background: #FFFFFF; color: #1E293B; border: 1px solid #E2E8F0; border-bottom-left-radius: 3px;'}">
                            ${escapeHtml(m.message)}
                        </div>
                        <span style="font-size: 10px; color: #94A3B8; margin-top: 3px;">
                            ${m.time_short || m.created_at || ''}
                        </span>
                    </div>
                `;
            });
        }

        bodyEl.innerHTML = html;
        footerEl.style.display = 'block';

        if (shouldScroll) {
            bodyEl.scrollTop = bodyEl.scrollHeight;
        }
    }

    // 6. Đánh dấu đã đọc
    function markAsRead(sessionId) {
        fetch(`${apiBase}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ session_id: sessionId, reader: 'admin' })
        }).catch(() => {});

        // Cập nhật localStorage
        try {
            const raw = localStorage.getItem('family_chat_store');
            if (raw) {
                const store = JSON.parse(raw);
                if (store[sessionId]) {
                    store[sessionId].forEach(m => {
                        if (m.sender_type === 'customer') m.is_read = true;
                    });
                    localStorage.setItem('family_chat_store', JSON.stringify(store));
                }
            }
        } catch(e) {}
    }

    // 7. Gửi tin nhắn trả lời của Admin
    function sendAdminReply(text) {
        const msg = (text || '').trim();
        if (!msg || !currentSessionId) return;

        const currentConv = conversations.find(c => c.session_id === currentSessionId);
        const payload = {
            session_id: currentSessionId,
            message: msg,
            sender_type: 'admin',
            customer_name: currentConv ? currentConv.customer_name : 'Khách Quý',
            customer_email: currentConv ? currentConv.customer_email : '',
            customer_phone: currentConv ? currentConv.customer_phone : '',
        };

        // Render tạm tin nhắn
        currentMessages.push({
            id: 'temp_' + Date.now(),
            sender_type: 'admin',
            message: msg,
            time_short: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            created_at: 'Vừa xong'
        });
        renderChatThread(true);

        // Lưu vào LocalStorage
        try {
            const raw = localStorage.getItem('family_chat_store') || '{}';
            const store = JSON.parse(raw);
            if (!store[currentSessionId]) store[currentSessionId] = [];
            store[currentSessionId].push({
                id: Date.now(),
                session_id: currentSessionId,
                sender_type: 'admin',
                message: msg,
                is_read: false,
                created_at: new Date().toLocaleString(),
                time_short: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            localStorage.setItem('family_chat_store', JSON.stringify(store));
        } catch(e) {}

        // Gửi qua API
        fetch(`${apiBase}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(res => {
            fetchCurrentMessages(true);
            fetchConversations();
        }).catch(err => {
            fetchConversations();
        });
    }

    // 8. Xóa hội thoại
    function deleteActiveChat() {
        if (!currentSessionId) return;
        if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ lịch sử cuộc trò chuyện này?')) return;

        fetch(`${apiBase}/delete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ session_id: currentSessionId })
        }).catch(() => {});

        try {
            const raw = localStorage.getItem('family_chat_store');
            if (raw) {
                const store = JSON.parse(raw);
                delete store[currentSessionId];
                localStorage.setItem('family_chat_store', JSON.stringify(store));
            }
        } catch(e) {}

        currentSessionId = null;
        currentMessages = [];
        fetchConversations();
        const headerEl = document.getElementById('adminChatActiveHeader');
        const bodyEl = document.getElementById('adminChatActiveBody');
        const footerEl = document.getElementById('adminChatActiveFooter');
        if (headerEl) headerEl.innerHTML = '<span style="color: #94A3B8; font-size: 13px;">Chọn một cuộc hội thoại từ danh sách bên trái để phản hồi</span>';
        if (bodyEl) bodyEl.innerHTML = '';
        if (footerEl) footerEl.style.display = 'none';
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;')
            .replace(/\n/g, '<br>');
    }

    // 9. Khởi chạy
    function init() {
        fetchConversations();
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(() => {
            fetchConversations();
        }, 3000);

        // Lắng nghe tìm kiếm
        const searchInput = document.getElementById('adminChatSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                renderConversationList(e.target.value);
            });
        }

        // Lắng nghe gửi tin nhắn
        const sendBtn = document.getElementById('adminChatSendBtn');
        const inputMsg = document.getElementById('adminChatInputText');
        if (sendBtn && inputMsg) {
            sendBtn.addEventListener('click', () => {
                const text = inputMsg.value;
                inputMsg.value = '';
                sendAdminReply(text);
                inputMsg.focus();
            });

            inputMsg.addEventListener('keypress', (e) => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    const text = inputMsg.value;
                    inputMsg.value = '';
                    sendAdminReply(text);
                }
            });
        }
    }

    return {
        init: init,
        selectConversation: selectConversation,
        sendAdminReply: sendAdminReply,
        refreshActiveChat: () => fetchCurrentMessages(true),
        deleteActiveChat: deleteActiveChat,
        quickReply: (text) => sendAdminReply(text)
    };
})();

// Tự động khởi động khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('adminChatConvList')) {
        window.FamilyAdminChat.init();
    }
});
