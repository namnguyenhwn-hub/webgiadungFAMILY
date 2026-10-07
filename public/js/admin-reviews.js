/**
 * FAMILY LUXE - Quản Lý Đánh Giá Khách Hàng (Admin Reviews Management)
 * Tích hợp duyệt, chỉnh sửa, xóa và thêm đánh giá VIP cho Quản Trị Viên
 */

(function () {
    'use strict';

    let adminReviewsList = [];
    let isEditingReview = false;

    /**
     * Lấy danh sách Candidate URLs cho API Reviews
     */
    function getReviewApiUrls() {
        const isWebgiadung = window.location.pathname.includes('/webgiadung');
        return [
            isWebgiadung ? '/webgiadung/api/reviews' : '/api/reviews',
            '/api/reviews',
            '/webgiadung/api/reviews',
            'http://localhost/webgiadung/api/reviews',
            'http://localhost:8000/api/reviews'
        ];
    }

    /**
     * Nạp toàn bộ danh sách đánh giá từ API
     */
    window.loadAdminReviews = async function () {
        const candidateUrls = getReviewApiUrls();
        let fetchedData = null;

        for (const baseUrl of candidateUrls) {
            try {
                const url = baseUrl + (baseUrl.includes('?') ? '&all=1' : '?all=1');
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 2500);
                const res = await fetch(url, { signal: controller.signal, headers: { 'Accept': 'application/json' } });
                clearTimeout(timeoutId);
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.success && Array.isArray(data.reviews)) {
                        fetchedData = data.reviews;
                        break;
                    }
                }
            } catch (e) { }
        }

        // Lấy thêm custom reviews từ localStorage nếu chưa có trong DB
        let localReviews = [];
        try {
            const raw = localStorage.getItem('auraluxe_custom_reviews');
            if (raw) {
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) {
                    localReviews = parsed.filter(r => r.comment && r.comment.trim().length >= 3 && r.comment.trim().toLowerCase() !== 'ok');
                }
            }
        } catch (e) { }

        if (fetchedData && fetchedData.length > 0) {
            adminReviewsList = fetchedData;
        } else if (localReviews.length > 0) {
            adminReviewsList = localReviews;
        } else {
            // Dữ liệu mẫu cơ bản nếu cả API và localStorage chưa sẵn sàng
            adminReviewsList = [
                {
                    id: 1,
                    user_name: 'Nguyễn Thu Huyền',
                    user_title: 'Chủ căn hộ Vinhomes Metropolis, Hà Nội',
                    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
                    product_id: 9,
                    product_name: 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L',
                    rating: 5,
                    comment: 'Thiết kế màu đen viền vàng sang trọng xuất sắc, đặt vào căn bếp phong cách tân cổ điển cực kỳ hợp. Nồi 12L nướng cả con gà chín vàng đều mà không bị khô. Rất hài lòng với dịch vụ!',
                    status: 'approved',
                    created_at_human: '3 ngày trước',
                    created_at: '23/09/2026'
                },
                {
                    id: 2,
                    user_name: 'Trần Quang Dũng',
                    user_title: 'Kiến trúc sư nội thất, TP. Hồ Chí Minh',
                    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
                    product_id: 11,
                    product_name: 'Bếp Từ Đôi Inverter Booster 4400W',
                    rating: 5,
                    comment: 'Mặt kính Schott Ceran vát cạnh kim loại vàng cực kỳ tinh tế. Khách hàng của mình ai ghé thăm cũng khen căn bếp sang trọng như resort. Đun sôi 1 lít nước chỉ mất chưa tới 2 phút.',
                    status: 'approved',
                    created_at_human: '1 tuần trước',
                    created_at: '19/09/2026'
                },
                {
                    id: 3,
                    user_name: 'Lê Mai Phương',
                    user_title: 'Blogger Ẩm Thực Gia Đình, Đà Nẵng',
                    avatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
                    product_id: 15,
                    product_name: 'Tủ Lạnh Smart French-Door 568L',
                    rating: 5,
                    comment: 'Ngăn đông mềm -3°C là cứu tinh cho mẹ bận rộn như mình, thịt cá lấy ra nấu ngay không cần rã đông. Cửa kính gõ 2 lần sáng đèn rất hiện đại. Đóng gói và giao hàng 10/10!',
                    status: 'approved',
                    created_at_human: '2 tuần trước',
                    created_at: '12/09/2026'
                }
            ];
        }

        renderAdminReviewsTable(adminReviewsList);
        updateReviewsBadge();
    };

    /**
     * Cập nhật số lượng đếm trên Tab Quản Lý Đánh Giá
     */
    function updateReviewsBadge() {
        const badge = document.getElementById('tabReviewsBadge');
        if (badge) {
            badge.innerText = adminReviewsList.length;
        }
    }

    /**
     * Vẽ bảng danh sách đánh giá
     */
    window.renderAdminReviewsTable = function (list) {
        const tbody = document.getElementById('reviewsTableBody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (!list || list.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" style="text-align: center; padding: 36px; color: #94A3B8;">
                        <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 8px; display: block; color: #CBD5E1;"></i>
                        Không tìm thấy đánh giá nào phù hợp.
                    </td>
                </tr>
            `;
            return;
        }

        list.forEach(r => {
            const tr = document.createElement('tr');

            // Stars string
            const rating = Math.max(1, Math.min(5, parseInt(r.rating || 5, 10)));
            let starsHtml = '';
            for (let i = 0; i < rating; i++) {
                starsHtml += '<i class="fa-solid fa-star" style="color: #F59E0B; font-size: 0.75rem;"></i>';
            }
            starsHtml += ` <span style="font-weight: 700; font-size: 0.8rem; color: #78350F; margin-left: 4px;">(${rating}/5)</span>`;

            // Status badge
            const isApproved = (r.status !== 'hidden');
            const statusBadge = isApproved
                ? `<span class="status-badge status-completed" style="cursor: pointer;" onclick="toggleReviewStatus(${r.id})" title="Nhấn để ẩn đánh giá"><i class="fa-solid fa-check"></i> Đã duyệt</span>`
                : `<span class="status-badge status-cancelled" style="cursor: pointer; background: #F1F5F9; color: #64748B; border: 1px solid #CBD5E1;" onclick="toggleReviewStatus(${r.id})" title="Nhấn để duyệt hiển thị"><i class="fa-solid fa-eye-slash"></i> Đang ẩn</span>`;

            const avatarUrl = r.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(r.user_name || 'Khách')}&background=C5A059&color=fff&size=150&bold=true`;
            const toggleBtnText = isApproved ? 'Ẩn' : 'Duyệt';
            const toggleBtnIcon = isApproved ? 'fa-eye-slash' : 'fa-check';
            const toggleBtnStyle = isApproved ? 'color: #64748B;' : 'color: #10B981; font-weight: 700;';

            tr.innerHTML = `
                <td><strong>#${r.id}</strong></td>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="${avatarUrl}" alt="${r.user_name}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 1px solid #DFC07A;">
                        <div>
                            <div style="font-weight: 700; color: #0F172A; font-size: 0.875rem;">${r.user_name}</div>
                            <small style="color: #64748B; font-size: 0.75rem;">${r.user_title || 'Khách hàng'}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-weight: 600; color: #916F29; font-size: 0.8125rem;">
                        <i class="fa-solid fa-tag" style="font-size: 0.7rem;"></i> ${r.product_name || 'Thiết bị cao cấp FAMILY'}
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center;">${starsHtml}</div>
                </td>
                <td style="max-width: 280px;">
                    <div style="font-size: 0.8125rem; color: #334155; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="${(r.comment || '').replace(/"/g, '&quot;')}">
                        "${r.comment}"
                    </div>
                </td>
                <td>
                    <small style="color: #64748B; font-size: 0.775rem;">${r.created_at_human || r.created_at || 'Vừa xong'}</small>
                </td>
                <td>${statusBadge}</td>
                <td>
                    <div style="display: flex; gap: 6px; align-items: center;">
                        <button type="button" class="btn-table-action" style="${toggleBtnStyle}" onclick="toggleReviewStatus(${r.id})" title="${isApproved ? 'Ẩn khỏi trang chủ' : 'Duyệt lên trang chủ'}">
                            <i class="fa-solid ${toggleBtnIcon}"></i> ${toggleBtnText}
                        </button>
                        <button type="button" class="btn-table-action btn-edit" onclick="openEditReviewModal(${r.id})" title="Chỉnh sửa nội dung">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </button>
                        <button type="button" class="btn-table-action btn-delete" onclick="deleteAdminReview(${r.id})" title="Xóa đánh giá">
                            <i class="fa-regular fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            `;

            tbody.appendChild(tr);
        });
    };

    /**
     * Lọc đánh giá theo ô tìm kiếm & bộ lọc trạng thái
     */
    window.filterAdminReviews = function () {
        const searchVal = (document.getElementById('adminReviewSearchInput')?.value || '').toLowerCase().trim();
        const statusVal = document.getElementById('adminReviewStatusFilter')?.value || 'all';

        const filtered = adminReviewsList.filter(r => {
            const matchesSearch = !searchVal ||
                (r.user_name && r.user_name.toLowerCase().includes(searchVal)) ||
                (r.product_name && r.product_name.toLowerCase().includes(searchVal)) ||
                (r.comment && r.comment.toLowerCase().includes(searchVal)) ||
                (r.user_title && r.user_title.toLowerCase().includes(searchVal));

            let matchesStatus = true;
            if (statusVal === 'approved') {
                matchesStatus = (r.status !== 'hidden');
            } else if (statusVal === 'hidden') {
                matchesStatus = (r.status === 'hidden');
            }

            return matchesSearch && matchesStatus;
        });

        renderAdminReviewsTable(filtered);
    };

    /**
     * Nạp danh sách sản phẩm vào Dropdown của Modal Admin
     */
    function populateAdminProductDropdown(selectedId) {
        const select = document.getElementById('adminReviewProductId');
        if (!select) return;

        select.innerHTML = '';

        // Sử dụng danh sách window.demoProducts nếu có
        const list = Array.isArray(window.demoProducts) && window.demoProducts.length > 0
            ? window.demoProducts
            : [
                { id: 9, name: 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L' },
                { id: 11, name: 'Bếp Từ Đôi Inverter Booster 4400W Gold' },
                { id: 15, name: 'Tủ Lạnh Smart French-Door 4 Cánh 568L' },
                { id: 12, name: 'Nồi Cơm Áp Suất Cao Tần IH FAMILY 1.8L' },
                { id: 13, name: 'Robot Hút Bụi Lau Nhà Tự Động FAMILY S9 Pro' }
            ];

        list.forEach(p => {
            const opt = document.createElement('option');
            opt.value = p.id;
            opt.textContent = `#${p.id} - ${p.name}`;
            if (String(p.id) === String(selectedId)) {
                opt.selected = true;
            }
            select.appendChild(opt);
        });
    }

    /**
     * Mở modal Thêm Đánh Giá VIP mới
     */
    window.openAddReviewModal = function () {
        isEditingReview = false;
        document.getElementById('adminReviewModalTitle').innerText = 'Thêm Đánh Giá VIP Mới Cho Trang Chủ';
        document.getElementById('adminReviewId').value = '';
        document.getElementById('adminReviewUserName').value = '';
        document.getElementById('adminReviewUserTitle').value = '';
        document.getElementById('adminReviewAvatar').value = '';
        document.getElementById('adminReviewComment').value = '';
        document.getElementById('adminReviewRating').value = '5';
        document.getElementById('adminReviewStatus').value = 'approved';

        populateAdminProductDropdown();

        const modal = document.getElementById('adminReviewModal');
        if (modal) modal.classList.add('active');
    };

    /**
     * Mở modal Chỉnh Sửa Đánh Giá
     */
    window.openEditReviewModal = function (id) {
        const review = adminReviewsList.find(r => String(r.id) === String(id));
        if (!review) return;

        isEditingReview = true;
        document.getElementById('adminReviewModalTitle').innerText = `Chỉnh Sửa Đánh Giá #${review.id}`;
        document.getElementById('adminReviewId').value = review.id;
        document.getElementById('adminReviewUserName').value = review.user_name || '';
        document.getElementById('adminReviewUserTitle').value = review.user_title || '';
        document.getElementById('adminReviewAvatar').value = review.avatar || '';
        document.getElementById('adminReviewComment').value = review.comment || '';
        document.getElementById('adminReviewRating').value = review.rating || 5;
        document.getElementById('adminReviewStatus').value = review.status || 'approved';

        populateAdminProductDropdown(review.product_id);

        const modal = document.getElementById('adminReviewModal');
        if (modal) modal.classList.add('active');
    };

    /**
     * Lưu (Thêm mới hoặc Cập nhật) Đánh Giá từ Admin
     */
    window.handleSaveAdminReview = async function (e) {
        e.preventDefault();

        const id = document.getElementById('adminReviewId')?.value;
        const userName = (document.getElementById('adminReviewUserName')?.value || '').trim();
        const userTitle = (document.getElementById('adminReviewUserTitle')?.value || '').trim() || 'Khách hàng VIP';
        const prodSelect = document.getElementById('adminReviewProductId');
        const productId = prodSelect?.value;
        const productName = prodSelect?.selectedOptions[0]?.textContent.replace(/^#\d+\s*-\s*/, '') || 'Thiết bị FAMILY';
        const rating = parseInt(document.getElementById('adminReviewRating')?.value || '5', 10);
        let avatar = (document.getElementById('adminReviewAvatar')?.value || '').trim();
        const comment = (document.getElementById('adminReviewComment')?.value || '').trim();
        const status = document.getElementById('adminReviewStatus')?.value || 'approved';

        if (comment.length < 3) {
            alert('Nội dung đánh giá phải có ít nhất 3 ký tự.');
            return;
        }

        if (!avatar) {
            avatar = `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=C5A059&color=fff&size=150&bold=true`;
        }

        const payload = {
            user_name: userName,
            user_title: userTitle,
            product_id: productId,
            product_name: productName,
            rating: rating,
            avatar: avatar,
            comment: comment,
            status: status
        };

        const candidateUrls = getReviewApiUrls();
        let apiSucceeded = false;

        if (id) {
            // CẬP NHẬT (PUT / PATCH)
            for (const baseUrl of candidateUrls) {
                try {
                    const res = await fetch(`${baseUrl}/${id}`, {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    if (res.ok) {
                        apiSucceeded = true;
                        break;
                    }
                } catch (err) { }
            }

            // Cập nhật trong mảng nội bộ
            const idx = adminReviewsList.findIndex(r => String(r.id) === String(id));
            if (idx !== -1) {
                adminReviewsList[idx] = {
                    ...adminReviewsList[idx],
                    ...payload
                };
            }
        } else {
            // TẠO MỚI (POST)
            let newId = Date.now();
            for (const baseUrl of candidateUrls) {
                try {
                    const res = await fetch(baseUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    if (res.ok) {
                        const data = await res.json();
                        if (data && data.review && data.review.id) {
                            newId = data.review.id;
                            apiSucceeded = true;
                            break;
                        }
                    }
                } catch (err) { }
            }

            const newReview = {
                id: newId,
                ...payload,
                created_at_human: 'Vừa xong',
                created_at: new Date().toLocaleDateString('vi-VN')
            };

            adminReviewsList.unshift(newReview);
        }

        // Lưu localStorage
        try {
            localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(adminReviewsList));
            localStorage.setItem('family_reviews_last_update', Date.now().toString());
        } catch (e) { }

        // Báo cho các tab khác (bao gồm index.html)
        broadcastReviewChange(id ? 'REVIEW_UPDATED' : 'REVIEW_ADDED');

        // Đóng modal và vẽ lại bảng
        if (typeof window.closeModal === 'function') {
            window.closeModal('adminReviewModal');
        } else {
            document.getElementById('adminReviewModal')?.classList.remove('active');
        }

        renderAdminReviewsTable(adminReviewsList);
        updateReviewsBadge();
        alert(id ? 'Đã cập nhật đánh giá thành công!' : 'Đã thêm đánh giá VIP thành công lên trang chủ!');
    };

    /**
     * Bật / Tắt trạng thái Duyệt hoặc Ẩn đánh giá
     */
    window.toggleReviewStatus = async function (id) {
        const review = adminReviewsList.find(r => String(r.id) === String(id));
        if (!review) return;

        const newStatus = (review.status === 'hidden') ? 'approved' : 'hidden';
        review.status = newStatus;

        // Gọi API cập nhật
        const candidateUrls = getReviewApiUrls();
        for (const baseUrl of candidateUrls) {
            try {
                await fetch(`${baseUrl}/${id}`, {
                    method: 'PATCH',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ status: newStatus })
                });
            } catch (err) { }
        }

        // Cập nhật localStorage
        try {
            localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(adminReviewsList));
            localStorage.setItem('family_reviews_last_update', Date.now().toString());
        } catch (e) { }

        broadcastReviewChange('REVIEW_STATUS_CHANGED');
        renderAdminReviewsTable(adminReviewsList);
    };

    /**
     * Xóa đánh giá
     */
    window.deleteAdminReview = async function (id) {
        const review = adminReviewsList.find(r => String(r.id) === String(id));
        const customerName = review ? review.user_name : `#${id}`;

        if (!confirm(`Bạn có chắc chắn muốn xóa đánh giá của "${customerName}"? Hành động này không thể hoàn tác.`)) {
            return;
        }

        // Gọi API Xóa
        const candidateUrls = getReviewApiUrls();
        for (const baseUrl of candidateUrls) {
            try {
                await fetch(`${baseUrl}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
            } catch (err) { }
        }

        // Loại bỏ khỏi mảng nội bộ
        adminReviewsList = adminReviewsList.filter(r => String(r.id) !== String(id));

        // Cập nhật localStorage
        try {
            localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(adminReviewsList));
            localStorage.setItem('family_reviews_last_update', Date.now().toString());
        } catch (e) { }

        broadcastReviewChange('REVIEW_DELETED');
        renderAdminReviewsTable(adminReviewsList);
        updateReviewsBadge();
    };

    /**
     * Phát sóng BroadcastChannel tới Trang Chủ (index.html)
     */
    function broadcastReviewChange(type) {
        try {
            if (window.BroadcastChannel) {
                const bc = new BroadcastChannel('family_reviews_channel');
                bc.postMessage({
                    type: type,
                    timestamp: Date.now()
                });
            }
        } catch (e) { }
    }

    /**
     * Lắng nghe BroadcastChannel từ Trang Chủ khi có khách hàng vừa đánh giá
     */
    function setupAdminReviewsListener() {
        if (!window.BroadcastChannel) return;
        try {
            const bc = new BroadcastChannel('family_reviews_channel');
            bc.onmessage = function (e) {
                if (e.data && e.data.type === 'REVIEW_ADDED') {
                    // Tự động nạp lại danh sách khi khách gửi review
                    loadAdminReviews();
                }
            };
        } catch (e) { }
    }

    // Khởi chạy khi tài liệu sẵn sàng
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            loadAdminReviews();
            setupAdminReviewsListener();
        });
    } else {
        loadAdminReviews();
        setupAdminReviewsListener();
    }

})();
