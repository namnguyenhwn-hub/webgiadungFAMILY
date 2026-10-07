/**
 * FAMILY LUXE - Live Chat Widget (Khách Hàng & Tư Vấn Viên)
 * Hỗ trợ giao diện Trắng Tinh Khiết & Champagne Gold, tự động đồng bộ thời gian thực
 */
(function () {
    // 1. Khởi tạo Session ID định danh khách hàng
    function getCurrentUser() {
        try {
            const raw = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
            if (raw) return JSON.parse(raw);
        } catch (e) {}
        return null;
    }

    let sessionId = localStorage.getItem('family_chat_session_id');
    const user = getCurrentUser();
    
    if (user && user.id) {
        sessionId = 'user_' + user.id;
        localStorage.setItem('family_chat_session_id', sessionId);
    } else if (!sessionId || sessionId.startsWith('user_')) {
        sessionId = 'cust_' + Math.random().toString(36).substring(2, 10) + Date.now().toString(36);
        localStorage.setItem('family_chat_session_id', sessionId);
    }

    // Xác định base API url
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    const apiBase = isWebgiadung ? '/webgiadung/api/chat' : '/api/chat';

    let isChatOpen = false;
    let pollInterval = null;
    let lastMessageCount = 0;
    let localMessages = [];


    // Tạo âm thanh thông báo nhẹ nhàng
    function playChatChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, ctx.currentTime); // D5
            osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.15); // A5
            gain.gain.setValueAtTime(0.08, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.3);
        } catch (e) {}
    }

    // 2. Chèn CSS cho Widget
    const styleEl = document.createElement('style');
    styleEl.innerHTML = `
        .family-chat-btn {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 99999;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            cursor: pointer;
            box-shadow: 0 8px 25px rgba(197, 160, 89, 0.45);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid #FFFFFF;
        }
        .family-chat-btn:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 12px 30px rgba(197, 160, 89, 0.6);
        }
        .family-chat-pulse {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: inherit;
            opacity: 0.5;
            animation: chatPulse 2.2s infinite;
            z-index: -1;
        }
        @keyframes chatPulse {
            0% { transform: scale(1); opacity: 0.6; }
            70% { transform: scale(1.4); opacity: 0; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .family-chat-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #EF4444;
            color: #FFFFFF;
            font-size: 11px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 9999px;
            border: 2px solid #FFFFFF;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
        }
        .family-chat-tooltip {
            position: absolute;
            right: 72px;
            white-space: nowrap;
            background: #111827;
            color: #FFFFFF;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            opacity: 0;
            transform: translateX(10px);
            transition: all 0.25s ease;
            pointer-events: none;
        }
        .family-chat-btn:hover .family-chat-tooltip {
            opacity: 1;
            transform: translateX(0);
        }

        /* Khung chat Window */
        .family-chat-box {
            position: fixed;
            bottom: 100px;
            right: 28px;
            width: 380px;
            height: 540px;
            max-width: calc(100vw - 40px);
            max-height: calc(100vh - 120px);
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            border: 1px solid #E5E7EB;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            pointer-events: none;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .family-chat-box.open {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: all;
        }
        .family-chat-header {
            background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
            color: #FFFFFF;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #C5A059;
        }
        .chat-agent-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .chat-agent-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DFC07A, #9B782F);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #FFFFFF;
            position: relative;
            box-shadow: 0 2px 8px rgba(197, 160, 89, 0.4);
        }
        .chat-online-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 10px;
            height: 10px;
            background: #10B981;
            border-radius: 50%;
            border: 2px solid #0F172A;
        }
        .chat-title-group h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 700;
            color: #FFFFFF;
        }
        .chat-title-group span {
            font-size: 12px;
            color: #94A3B8;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .chat-header-actions button {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: #E2E8F0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }
        .chat-header-actions button:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #FFFFFF;
        }

        /* Customer info bar */
        .chat-cust-bar {
            background: #F8FAFC;
            padding: 8px 16px;
            border-bottom: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #64748B;
        }
        .chat-cust-bar strong {
            color: #916F29;
        }

        /* Message body */
        .family-chat-body {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            background: #FAFBFD;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .chat-msg {
            max-width: 82%;
            display: flex;
            flex-direction: column;
            animation: fadeInMsg 0.2s ease;
        }
        @keyframes fadeInMsg {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .chat-msg.admin {
            align-self: flex-start;
        }
        .chat-msg.customer {
            align-self: flex-end;
        }
        .chat-sender-name {
            font-size: 11px;
            color: #94A3B8;
            margin-bottom: 3px;
            font-weight: 600;
        }
        .chat-msg.customer .chat-sender-name {
            text-align: right;
            color: #916F29;
        }
        .chat-bubble {
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13.5px;
            line-height: 1.45;
            word-break: break-word;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .chat-msg.admin .chat-bubble {
            background: #FFFFFF;
            color: #1E293B;
            border: 1px solid #E2E8F0;
            border-bottom-left-radius: 4px;
        }
        .chat-msg.customer .chat-bubble {
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 100%);
            color: #FFFFFF;
            border-bottom-right-radius: 4px;
            font-weight: 500;
        }
        .chat-time {
            font-size: 10px;
            color: #94A3B8;
            margin-top: 4px;
        }
        .chat-msg.customer .chat-time {
            text-align: right;
        }

        /* Quick chips */
        .chat-chips-container {
            padding: 8px 14px;
            background: #FFFFFF;
            border-top: 1px solid #F1F5F9;
            display: flex;
            gap: 6px;
            overflow-x: auto;
            white-space: nowrap;
            scrollbar-width: none;
        }
        .chat-chips-container::-webkit-scrollbar {
            display: none;
        }
        .chat-chip {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #E2E8F0;
            border-radius: 9999px;
            padding: 4px 10px;
            font-size: 11.5px;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .chat-chip:hover {
            background: rgba(197, 160, 89, 0.15);
            color: #916F29;
            border-color: #DFC07A;
        }

        /* Chat Footer input */
        .family-chat-footer {
            padding: 12px 14px;
            background: #FFFFFF;
            border-top: 1px solid #E2E8F0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .family-chat-input {
            flex: 1;
            padding: 10px 14px;
            border-radius: 9999px;
            border: 1px solid #CBD5E1;
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s;
            font-family: inherit;
        }
        .family-chat-input:focus {
            border-color: #C5A059;
            box-shadow: 0 0 0 3px rgba(197, 160, 89, 0.18);
        }
        .family-chat-send {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DFC07A 0%, #C5A059 100%);
            color: #FFFFFF;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            transition: all 0.2s;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(197, 160, 89, 0.35);
        }
        .family-chat-send:hover {
            transform: scale(1.05);
        }
        .family-chat-send:active {
            transform: scale(0.95);
        }
    `;
    document.head.appendChild(styleEl);

    // 3. Render HTML Widget
    const triggerBtn = document.createElement('div');
    triggerBtn.className = 'family-chat-btn';
    triggerBtn.id = 'familyChatBtn';
    triggerBtn.innerHTML = `
        <div class="family-chat-pulse"></div>
        <i class="fa-solid fa-comments"></i>
        <span class="family-chat-badge" id="familyChatBadge" style="display:none;">0</span>
        <span class="family-chat-tooltip">Chat trực tuyến với Shop</span>
    `;

    const chatBox = document.createElement('div');
    chatBox.className = 'family-chat-box';
    chatBox.id = 'familyChatBox';
    chatBox.innerHTML = `
        <div class="family-chat-header">
            <div class="chat-agent-info">
                <div class="chat-agent-avatar">
                    <i class="fa-solid fa-headset"></i>
                    <div class="chat-online-dot"></div>
                </div>
                <div class="chat-title-group">
                    <h4>FAMILY Luxury Care</h4>
                    <span><i class="fa-solid fa-circle" style="font-size: 6px; color: #10B981;"></i> Đang trực tuyến</span>
                </div>
            </div>
            <div class="chat-header-actions">
                <button type="button" id="closeChatBtn" title="Đóng khung chat"><i class="fa-solid fa-xmark"></i></button>
            </div>
        </div>
        <div class="chat-cust-bar" id="chatCustBar">
            <span>Đang chat với tư cách: <strong id="chatCustName">Khách quý</strong></span>
            <span style="font-size: 11px; color: #94A3B8;">Hỗ trợ 24/7</span>
        </div>
        <div class="family-chat-body" id="chatMessagesBody">
            <div class="chat-msg admin">
                <div class="chat-sender-name">Admin chăm sóc khách hàng</div>
                <div class="chat-bubble">
                    Dạ FAMILY xin kính chào Quý khách! 👋<br>
                    Em có thể hỗ trợ tư vấn thiết bị gia dụng hoặc giải đáp thắc mắc đơn hàng nào cho mình ạ?
                </div>
                <div class="chat-time">Vừa xong</div>
            </div>
        </div>
        <div class="chat-chips-container">
            <button type="button" class="chat-chip" onclick="window.sendChatChip('Tư vấn nồi chiên không dầu OLED')">🔥 Nồi chiên OLED</button>
            <button type="button" class="chat-chip" onclick="window.sendChatChip('Chính sách bảo hành 24 tháng')">🛡️ Bảo hành 24T</button>
            <button type="button" class="chat-chip" onclick="window.sendChatChip('Kiểm tra tiến độ đơn hàng')">📦 Kiểm tra đơn hàng</button>
            <button type="button" class="chat-chip" onclick="window.sendChatChip('Gặp nhân viên tư vấn trực tiếp')">💬 Gặp tư vấn viên</button>
        </div>
        <div class="family-chat-footer">
            <input type="text" id="chatInputMessage" class="family-chat-input" placeholder="Nhập tin nhắn tư vấn..." autocomplete="off">
            <button type="button" id="chatSendBtn" class="family-chat-send" title="Gửi tin nhắn">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
    `;

    document.body.appendChild(triggerBtn);
    document.body.appendChild(chatBox);

    // 4. Sự kiện đóng / mở
    function toggleChat() {
        isChatOpen = !isChatOpen;
        if (isChatOpen) {
            chatBox.classList.add('open');
            triggerBtn.querySelector('i').className = 'fa-solid fa-chevron-down';
            // Ẩn badge unread
            document.getElementById('familyChatBadge').style.display = 'none';
            document.getElementById('familyChatBadge').textContent = '0';
            updateCustomerInfoDisplay();
            fetchMessages();
            startPolling();
            setTimeout(() => {
                document.getElementById('chatInputMessage').focus();
                scrollToBottom();
            }, 100);
        } else {
            chatBox.classList.remove('open');
            triggerBtn.querySelector('i').className = 'fa-solid fa-comments';
            if (pollInterval) {
                clearInterval(pollInterval);
                pollInterval = null;
            }
        }
    }

    triggerBtn.addEventListener('click', toggleChat);
    document.getElementById('closeChatBtn').addEventListener('click', toggleChat);

    function updateCustomerInfoDisplay() {
        const u = getCurrentUser();
        const nameEl = document.getElementById('chatCustName');
        if (u && u.name) {
            nameEl.textContent = u.name;
        } else {
            nameEl.textContent = 'Khách Quý';
        }
    }

    function scrollToBottom() {
        const body = document.getElementById('chatMessagesBody');
        if (body) {
            body.scrollTop = body.scrollHeight;
        }
    }

    // 5. Render danh sách tin nhắn
    function renderMessages(messages) {
        const body = document.getElementById('chatMessagesBody');
        if (!body) return;

        // Message chào mặc định
        let html = `
            <div class="chat-msg admin">
                <div class="chat-sender-name">Admin chăm sóc khách hàng</div>
                <div class="chat-bubble">
                    Dạ FAMILY xin kính chào Quý khách! 👋<br>
                    Em có thể hỗ trợ tư vấn thiết bị gia dụng hoặc giải đáp thắc mắc đơn hàng nào cho mình ạ?
                </div>
                <div class="chat-time">Tin nhắn hệ thống</div>
            </div>
        `;

        let unreadCount = 0;
        messages.forEach(m => {
            const isAdmin = m.sender_type === 'admin';
            if (isAdmin && !m.is_read) {
                unreadCount++;
            }
            html += `
                <div class="chat-msg ${isAdmin ? 'admin' : 'customer'}">
                    <div class="chat-sender-name">${isAdmin ? 'Quản trị viên (Admin)' : (m.customer_name || 'Bạn')}</div>
                    <div class="chat-bubble">${escapeHtml(m.message)}</div>
                    <div class="chat-time">${m.time_short || m.created_at || 'Vừa xong'}</div>
                </div>
            `;
        });

        body.innerHTML = html;
        scrollToBottom();

        // Xử lý badge khi cửa sổ đang đóng
        if (!isChatOpen && unreadCount > 0) {
            const badge = document.getElementById('familyChatBadge');
            badge.style.display = 'block';
            badge.textContent = unreadCount;
        }

        // Phát chuông nếu có tin nhắn mới từ Admin
        if (messages.length > lastMessageCount) {
            const latest = messages[messages.length - 1];
            if (latest && latest.sender_type === 'admin' && lastMessageCount > 0) {
                playChatChime();
            }
        }
        lastMessageCount = messages.length;
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

    // 6. Gửi tin nhắn
    function sendMessage(text) {
        const msg = (text || '').trim();
        if (!msg) return;

        const u = getCurrentUser();
        const payload = {
            session_id: sessionId,
            message: msg,
            sender_type: 'customer',
            customer_name: u ? u.name : 'Khách Quý',
            customer_email: u ? u.email : '',
            customer_phone: u ? u.phone : '',
        };

        // Render tạm tin nhắn lên giao diện
        const tempMsg = {
            id: 'temp_' + Date.now(),
            sender_type: 'customer',
            customer_name: payload.customer_name,
            message: msg,
            time_short: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            created_at: 'Vừa xong'
        };
        localMessages.push(tempMsg);
        renderMessages(localMessages);

        // Lưu vào LocalStorage làm fallback
        saveToLocalStorage(sessionId, payload);

        // Gửi lên Backend API
        fetch(`${apiBase}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(payload)
        }).then(r => r.json()).then(res => {
            if (res && res.success && res.data) {
                fetchMessages();
                simulateAiReply(msg); // Kích hoạt AI trả lời tự động
            }
        }).catch(err => {
            console.log('Chat API offline, running in local sync mode');
            simulateAiReply(msg); // AI vẫn hoạt động ở chế độ Offline
        });
    }

    // Tích hợp riêng 1 AI trò chuyện tự động
    function simulateAiReply(userText) {
        setTimeout(() => {
            let aiResponse = "Dạ, hệ thống trợ lý ảo FAMILY đã ghi nhận thông tin. Nhân viên CSKH sẽ phản hồi chi tiết cho bạn ngay trong ít phút tới nhé!";
            const textLower = userText.toLowerCase();
            
            if (textLower.includes('giá') || textLower.includes('bao nhiêu')) {
                aiResponse = "Chào bạn! Đối với thắc mắc về giá cả, FAMILY đang có chương trình trợ giá rất tốt. AI xin phép gửi yêu cầu tới nhân viên kinh doanh để báo giá ưu đãi nhất cho mình nhé!";
            } else if (textLower.includes('bảo hành') || textLower.includes('lỗi') || textLower.includes('hỏng')) {
                aiResponse = "Về chính sách bảo hành, FAMILY hỗ trợ BẢO HÀNH 24 THÁNG TẠI NHÀ và 1 ĐỔI 1 TRONG 30 NGÀY ạ. Bạn vui lòng để lại mã đơn hàng (hoặc số điện thoại) để AI kiểm tra ngay nhé.";
            } else if (textLower.includes('chào') || textLower.includes('hi') || textLower.includes('hello')) {
                aiResponse = "Dạ xin chào! Trợ lý ảo AI FAMILY rất hân hạnh được phục vụ bạn. Bạn đang cần tìm hiểu về dòng sản phẩm Nồi chiên, Bếp từ hay Tủ lạnh ạ?";
            } else if (textLower.includes('tư vấn') || textLower.includes('mua')) {
                aiResponse = "Dạ, bạn có thể tham khảo các bộ sưu tập thiết bị mới nhất của FAMILY trên trang chủ. Bạn muốn AI tư vấn kỹ hơn về dòng sản phẩm nào?";
            }

            const aiPayload = {
                session_id: sessionId,
                message: aiResponse,
                sender_type: 'admin',
                customer_name: 'Trợ lý ảo AI FAMILY',
                customer_email: '',
                customer_phone: '',
            };

            // Lưu vào LocalStorage
            saveToLocalStorage(sessionId, aiPayload);

            // Gửi giả lập lên Backend để lưu vào DB Admin
            fetch(`${apiBase}/send`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(aiPayload)
            }).then(() => fetchMessages()).catch(() => fetchMessages());

        }, 1500); // Trì hoãn 1.5s tạo cảm giác AI đang gõ
    }

    // 7. Lấy danh sách tin nhắn từ Backend hoặc LocalStorage
    function fetchMessages() {
        fetch(`${apiBase}/messages?session_id=${sessionId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(r => r.json()).then(res => {
            if (res && res.success && Array.isArray(res.data)) {
                localMessages = res.data;
                // Đồng bộ ngược lại LocalStorage để Admin trên cùng máy có thể đọc
                syncLocalStoreFromApi(sessionId, res.data);
                renderMessages(localMessages);
            }
        }).catch(err => {
            // Fallback sang LocalStorage nếu chạy môi trường tĩnh không có Laravel API
            const stored = getFromLocalStorage(sessionId);
            if (stored && stored.length > 0) {
                localMessages = stored;
                renderMessages(localMessages);
            }
        });
    }

    // 8. Đồng bộ LocalStorage (Hỗ trợ Live Server 5500 và test trên cùng máy)
    function saveToLocalStorage(sId, payload) {
        try {
            const raw = localStorage.getItem('family_chat_store') || '{}';
            const store = JSON.parse(raw);
            if (!store[sId]) store[sId] = [];
            store[sId].push({
                id: Date.now(),
                session_id: sId,
                sender_type: payload.sender_type,
                customer_name: payload.customer_name,
                customer_email: payload.customer_email,
                customer_phone: payload.customer_phone,
                message: payload.message,
                is_read: false,
                created_at: new Date().toLocaleString(),
                time_short: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
            });
            localStorage.setItem('family_chat_store', JSON.stringify(store));
        } catch(e) {}
    }

    function getFromLocalStorage(sId) {
        try {
            const raw = localStorage.getItem('family_chat_store');
            if (raw) {
                const store = JSON.parse(raw);
                return store[sId] || [];
            }
        } catch(e) {}
        return [];
    }

    function syncLocalStoreFromApi(sId, data) {
        try {
            const raw = localStorage.getItem('family_chat_store') || '{}';
            const store = JSON.parse(raw);
            store[sId] = data;
            localStorage.setItem('family_chat_store', JSON.stringify(store));
        } catch(e) {}
    }

    // 9. Polling lặp lại định kỳ (Chỉ thăm dò khi người dùng đang mở chat & xem trang)
    function startPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        if (!isChatOpen) return;
        pollInterval = setInterval(() => {
            if (isChatOpen && !document.hidden) {
                fetchMessages();
            }
        }, 20000);
    }

    // 10. Sự kiện gửi tin nhắn
    document.getElementById('chatSendBtn').addEventListener('click', () => {
        const input = document.getElementById('chatInputMessage');
        const text = input.value;
        input.value = '';
        sendMessage(text);
        input.focus();
    });

    document.getElementById('chatInputMessage').addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const input = document.getElementById('chatInputMessage');
            const text = input.value;
            input.value = '';
            sendMessage(text);
        }
    });

    // Hàm cho quick chips
    window.sendChatChip = function (chipText) {
        sendMessage(chipText);
    };

    // Kiểm tra tin nhắn ban đầu 1 lần (không bật polling nền khi đóng)
    fetchMessages();
})();
