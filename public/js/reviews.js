/**
 * FAMILY LUXE - Hệ Thống Đánh Giá & Bình Luận Khách Hàng (Customer Reviews & Comments)
 * Hỗ trợ: Khung gửi bình luận trực tiếp trên trang chủ + Modal + Đồng bộ Admin thời gian thực
 */

(function () {
    'use strict';

    let currentInlineRating = 5;
    let currentModalRating = 5;

    const ratingDescriptions = {
        1: '1/5 Sao (Cần cải thiện)',
        2: '2/5 Sao (Tạm chấp nhận)',
        3: '3/5 Sao (Hài lòng)',
        4: '4/5 Sao (Rất tốt, hài lòng)',
        5: '5/5 Sao (Xuất sắc, cực kỳ ưng ý!)'
    };

    /**
     * Dọn dẹp rác cũ trong localStorage (nếu có bình luận test "ok", v.v.)
     */
    try {
        const stored = localStorage.getItem('auraluxe_custom_reviews');
        if (stored) {
            const parsed = JSON.parse(stored);
            if (Array.isArray(parsed)) {
                // Lọc bỏ các bình luận rác hoặc test
                const clean = parsed.filter(r => r.comment && r.comment.trim().length >= 3 && r.comment.trim().toLowerCase() !== 'ok');
                localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(clean));
            }
        }
    } catch (e) {
        localStorage.removeItem('auraluxe_custom_reviews');
    }

    /**
     * Lấy danh sách Candidate URLs cho API Reviews
     */
    function getReviewCandidateUrls() {
        const isWebgiadung = window.location.pathname.includes('/webgiadung');
        return [
            isWebgiadung ? '/webgiadung/api/reviews' : '/api/reviews'
        ];
    }

    /**
     * Chọn sao cho Khung Bình Luận Trực Tiếp
     */
    window.setInlineStars = function (rating) {
        currentInlineRating = Math.max(1, Math.min(5, parseInt(rating, 10) || 5));
        const stars = document.querySelectorAll('#inlineStarPicker i');
        stars.forEach(star => {
            const val = parseInt(star.getAttribute('data-val') || '0', 10);
            if (val <= currentInlineRating) {
                star.style.color = '#F59E0B';
            } else {
                star.style.color = '#D1D5DB';
            }
        });

        const label = document.getElementById('inlineStarLabel');
        if (label) {
            label.textContent = ratingDescriptions[currentInlineRating] || `${currentInlineRating}/5 Sao`;
        }
    };

    let currentCompactRating = 5;

    /**
     * Chọn sao cho Khung Bình Luận Tinh Gọn (Compact)
     */
    window.setCompactStars = function (rating) {
        currentCompactRating = Math.max(1, Math.min(5, parseInt(rating, 10) || 5));
        const stars = document.querySelectorAll('#compactStarPicker i');
        stars.forEach(star => {
            const val = parseInt(star.getAttribute('data-val') || '0', 10);
            if (val <= currentCompactRating) {
                star.style.color = '#F59E0B';
            } else {
                star.style.color = '#D1D5DB';
            }
        });

        const label = document.getElementById('compactStarLabel');
        if (label) {
            label.textContent = ratingDescriptions[currentCompactRating] || `${currentCompactRating}/5 Sao`;
        }
    };

    /**
     * Cuộn mượt và kích hoạt ô viết bình luận
     */
    window.focusInlineReviewForm = function () {
        const compactBox = document.getElementById('compactReviewBox');
        if (compactBox) {
            compactBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                const commentEl = document.getElementById('compactReviewComment');
                if (commentEl) commentEl.focus();
            }, 300);
            return;
        }
        const composer = document.getElementById('reviewComposerCard');
        if (composer) {
            composer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                const commentEl = document.getElementById('inlineReviewComment');
                if (commentEl) commentEl.focus();
            }, 300);
        } else if (typeof window.openCustomerReviewModal === 'function') {
            window.openCustomerReviewModal();
        }
    };

    /**
     * Nạp danh sách thiết bị vào Dropdown của Form
     */
    async function populateProductDropdowns() {
        const inlineSelect = document.getElementById('inlineReviewProduct');
        const modalSelect = document.getElementById('customerReviewProductId');
        if (!inlineSelect && !modalSelect) return;

        const productsMap = new Map();

        // 1. Quét từ DOM sản phẩm hiện có
        document.querySelectorAll('.product-card').forEach(card => {
            const id = card.getAttribute('data-product-id');
            const name = card.getAttribute('data-product-name');
            if (id && name && !productsMap.has(id)) {
                productsMap.set(id, name);
            }
        });

        // 2. Thử fetch từ API products nếu DOM chưa có đủ
        if (productsMap.size === 0) {
            const candidateUrls = [
                window.location.pathname.includes('/webgiadung') ? '/webgiadung/api/products' : '/api/products'
            ];
            for (const url of candidateUrls) {
                try {
                    const res = await fetch(url);
                    if (res.ok) {
                        const data = await res.json();
                        if (data && Array.isArray(data.products)) {
                            data.products.forEach(p => {
                                if (p.id && p.name) productsMap.set(String(p.id), p.name);
                            });
                            break;
                        }
                    }
                } catch (e) { }
            }
        }

        // 3. Fallback danh mục thiết bị cao cấp FAMILY LUXE
        if (productsMap.size === 0) {
            productsMap.set('9', 'Nồi Chiên Không Dầu FAMILY Pro OLED 12L');
            productsMap.set('11', 'Bếp Từ Đôi Inverter Booster 4400W Gold');
            productsMap.set('15', 'Tủ Lạnh Smart French-Door 4 Cánh 568L');
            productsMap.set('12', 'Nồi Cơm Áp Suất Cao Tần IH FAMILY 1.8L');
            productsMap.set('13', 'Robot Hút Bụi Lau Nhà Tự Động FAMILY S9 Pro');
        }

        // Điền vào select
        [inlineSelect, modalSelect].forEach(select => {
            if (!select) return;
            select.innerHTML = '<option value="">-- Vui lòng chọn thiết bị đã mua / trải nghiệm --</option>';
            productsMap.forEach((name, id) => {
                const opt = document.createElement('option');
                opt.value = id;
                opt.textContent = name;
                select.appendChild(opt);
            });
            // Tự động chọn sản phẩm đầu tiên
            if (select.options.length > 1) {
                select.selectedIndex = 1;
            }
        });
    }

    /**
     * Điền trước tên người dùng nếu đã đăng nhập
     */
    function prefillUserInfo() {
        try {
            const userStr = localStorage.getItem('auraluxe_user');
            if (userStr) {
                const u = JSON.parse(userStr);
                const inlineName = document.getElementById('inlineReviewName');
                if (inlineName && !inlineName.value && u.name) {
                    inlineName.value = u.name;
                }
                const modalName = document.getElementById('customerReviewName');
                if (modalName && !modalName.value && u.name) {
                    modalName.value = u.name;
                }
            }
        } catch (e) { }
    }

    /**
     * Tạo HTML Card đánh giá theo chuẩn phong cách sang trọng FAMILY LUXE
     */
    function createReviewCardHtml(r) {
        let starsHtml = '';
        const rating = Math.max(1, Math.min(5, parseInt(r.rating || 5, 10)));
        for (let i = 0; i < rating; i++) {
            starsHtml += '<i class="fa-solid fa-star"></i>';
        }

        const dateHuman = r.created_at_human || 'Vừa xong';
        const prodName = r.product_name || 'Thiết bị gia dụng cao cấp FAMILY';
        const commentEsc = (r.comment || '').replace(/"/g, '&quot;');
        const authorName = r.user_name || 'Khách Hàng Quý';
        const authorTitle = r.user_title || 'Khách hàng thân thiết';
        const avatar = r.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=C5A059&color=fff&size=150&bold=true`;

        return `
        <div class="review-card new-review-glow" data-review-id="${r.id || ''}">
            <div>
                <div class="review-top-meta">
                    <div class="review-stars">${starsHtml}</div>
                    <span class="review-date">${dateHuman}</span>
                </div>
                <div class="review-product-tag">
                    <i class="fa-solid fa-check-circle" style="color: #10B981;"></i>
                    Đã mua: ${prodName}
                </div>
                <p class="review-body">"${commentEsc}"</p>
            </div>
            <div class="review-author">
                <img src="${avatar}" alt="${authorName}" class="review-avatar" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(authorName)}&background=C5A059&color=fff&size=150&bold=true'">
                <div class="review-author-info">
                    <h5>${authorName}</h5>
                    <span>${authorTitle}</span>
                </div>
            </div>
        </div>
        `;
    }

    /**
     * Hiển thị Toast thông báo thành công
     */
    function showNotification(msg, isSuccess = true) {
        if (typeof window.showToast === 'function') {
            window.showToast(msg);
            return;
        }
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '28px';
        toast.style.right = '28px';
        toast.style.background = isSuccess ? '#10B981' : '#EF4444';
        toast.style.color = '#FFFFFF';
        toast.style.padding = '14px 22px';
        toast.style.borderRadius = '12px';
        toast.style.boxShadow = '0 10px 25px rgba(0,0,0,0.15)';
        toast.style.fontWeight = '700';
        toast.style.fontSize = '0.9rem';
        toast.style.zIndex = '999999';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '8px';
        toast.innerHTML = `<i class="fa-solid ${isSuccess ? 'fa-circle-check' : 'fa-triangle-exclamation'}"></i> ${msg}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    /**
     * XỬ LÝ GỬI BÌNH LUẬN TINH GỌN (CHỈ CẦN NỘI DUNG & ĐÁNH GIÁ SAO)
     */
    window.submitCompactReview = async function (e) {
        if (e) e.preventDefault();

        const commentInput = document.getElementById('compactReviewComment');
        const submitBtn = document.getElementById('btnSubmitCompactReview');
        const comment = (commentInput?.value || '').trim();
        const rating = currentCompactRating;

        if (comment.length < 3) {
            alert('Nội dung bình luận phải có ít nhất 3 ký tự.');
            commentInput?.focus();
            return;
        }

        // Tự động nhận diện tên người dùng nếu đã đăng nhập, hoặc để Khách hàng
        let userName = 'Khách hàng';
        try {
            const userStr = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
            if (userStr) {
                const u = JSON.parse(userStr);
                if (u && u.name) userName = u.name;
            }
        } catch (e) { }

        const userTitle = 'Khách hàng đã trải nghiệm sản phẩm';
        const productId = '9';
        const productName = 'Thiết bị gia dụng FAMILY';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang gửi...';
        }

        const payload = {
            user_name: userName,
            user_title: userTitle,
            product_id: productId,
            product_name: productName,
            rating: rating,
            comment: comment,
            status: 'approved'
        };

        let savedReview = null;
        const candidateUrls = getReviewCandidateUrls();

        for (const url of candidateUrls) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data && data.success && data.review) {
                        savedReview = data.review;
                        break;
                    }
                }
            } catch (err) { }
        }

        // Fallback local nếu mạng tạm ngắt
        if (!savedReview) {
            savedReview = {
                id: Date.now(),
                user_name: userName,
                user_title: userTitle,
                product_id: productId,
                product_name: productName,
                rating: rating,
                comment: comment,
                status: 'approved',
                created_at_human: 'Vừa xong',
                avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=C5A059&color=fff&size=150&bold=true`
            };
        }

        // Lưu vào localStorage
        try {
            const localReviews = JSON.parse(localStorage.getItem('auraluxe_custom_reviews') || '[]');
            localReviews.unshift(savedReview);
            localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(localReviews));
        } catch (e) { }

        // Chèn trực tiếp card mới lên đầu danh sách `#reviewsGrid`
        const grid = document.getElementById('reviewsGrid') || document.querySelector('.reviews-grid');
        if (grid) {
            const cardWrapper = document.createElement('div');
            cardWrapper.innerHTML = createReviewCardHtml(savedReview).trim();
            const newCardEl = cardWrapper.firstElementChild;
            grid.prepend(newCardEl);

            newCardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Phát sóng thời gian thực cho Admin Panel
        try {
            if (window.BroadcastChannel) {
                const bc = new BroadcastChannel('family_reviews_channel');
                bc.postMessage({
                    type: 'REVIEW_ADDED',
                    review: savedReview,
                    timestamp: Date.now()
                });
            }
            localStorage.setItem('family_reviews_last_update', Date.now().toString());
        } catch (e) { }

        // Reset form
        if (commentInput) commentInput.value = '';
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Gửi Bình Luận';
        }

        showNotification('Đã gửi bình luận thành công!', true);
    };

    /**
     * XỬ LÝ GỬI BÌNH LUẬN TRỰC TIẾP TỪ TRANG CHỦ
     */
    window.submitInlineReview = async function (e) {
        e.preventDefault();

        const nameInput = document.getElementById('inlineReviewName');
        const titleInput = document.getElementById('inlineReviewTitle');
        const prodSelect = document.getElementById('inlineReviewProduct');
        const commentInput = document.getElementById('inlineReviewComment');
        const submitBtn = document.getElementById('btnSubmitInlineReview');

        const userName = (nameInput?.value || '').trim();
        const userTitle = (titleInput?.value || '').trim() || 'Khách hàng thân thiết';
        const productId = prodSelect?.value || '9';
        const productName = prodSelect?.selectedOptions[0]?.textContent || 'Thiết bị gia dụng FAMILY';
        const comment = (commentInput?.value || '').trim();
        const rating = currentInlineRating;

        if (!userName) {
            alert('Vui lòng nhập họ và tên của bạn.');
            nameInput?.focus();
            return;
        }

        if (comment.length < 3) {
            alert('Nội dung bình luận phải có ít nhất 3 ký tự.');
            commentInput?.focus();
            return;
        }

        // Khóa nút gửi
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang đăng...';
        }

        const payload = {
            user_name: userName,
            user_title: userTitle,
            product_id: productId,
            product_name: productName,
            rating: rating,
            comment: comment,
            status: 'approved'
        };

        let savedReview = null;
        const candidateUrls = getReviewCandidateUrls();

        for (const url of candidateUrls) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    const data = await res.json();
                    if (data && data.success && data.review) {
                        savedReview = data.review;
                        break;
                    }
                }
            } catch (err) { }
        }

        // Fallback local nếu mạng tạm ngắt
        if (!savedReview) {
            savedReview = {
                id: Date.now(),
                user_name: userName,
                user_title: userTitle,
                product_id: productId,
                product_name: productName,
                rating: rating,
                comment: comment,
                status: 'approved',
                created_at_human: 'Vừa xong',
                avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(userName)}&background=C5A059&color=fff&size=150&bold=true`
            };
        }

        // Lưu vào localStorage
        try {
            const localReviews = JSON.parse(localStorage.getItem('auraluxe_custom_reviews') || '[]');
            localReviews.unshift(savedReview);
            localStorage.setItem('auraluxe_custom_reviews', JSON.stringify(localReviews));
        } catch (e) { }

        // Chèn trực tiếp card mới lên đầu danh sách `#reviewsGrid`
        const grid = document.getElementById('reviewsGrid') || document.querySelector('.reviews-grid');
        if (grid) {
            const cardWrapper = document.createElement('div');
            cardWrapper.innerHTML = createReviewCardHtml(savedReview).trim();
            const newCardEl = cardWrapper.firstElementChild;
            grid.prepend(newCardEl);

            // Cuộn mượt tới card mới
            newCardEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // Phát sóng thời gian thực cho Admin Panel
        try {
            if (window.BroadcastChannel) {
                const bc = new BroadcastChannel('family_reviews_channel');
                bc.postMessage({
                    type: 'REVIEW_ADDED',
                    review: savedReview,
                    timestamp: Date.now()
                });
            }
        } catch (e) { }

        // Reset form
        if (commentInput) commentInput.value = '';
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Gửi Bình Luận Ngay';
        }

        showNotification('Cảm ơn bạn! Bình luận của bạn đã được đăng công khai thành công.');
    };

    /**
     * Nạp toàn bộ danh sách Đánh Giá từ Server API lên Trang Chủ
     */
    window.loadHomepageReviews = async function () {
        const grid = document.getElementById('reviewsGrid') || document.querySelector('.reviews-grid');
        if (!grid) return;

        const candidateUrls = getReviewCandidateUrls();
        let fetchedReviews = null;

        for (const url of candidateUrls) {
            try {
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 1200);
                const res = await fetch(url, { signal: controller.signal, headers: { 'Accept': 'application/json' } });
                clearTimeout(timeoutId);
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.success && Array.isArray(data.reviews) && data.reviews.length > 0) {
                        fetchedReviews = data.reviews.filter(r => r.status !== 'hidden');
                        break;
                    }
                }
            } catch (e) { }
        }

        // Lấy thêm custom reviews từ localStorage
        let localReviews = [];
        try {
            localReviews = JSON.parse(localStorage.getItem('auraluxe_custom_reviews') || '[]');
        } catch (e) { }

        if (fetchedReviews && fetchedReviews.length > 0) {
            const apiIds = new Set(fetchedReviews.map(r => String(r.id)));
            const extraLocal = localReviews.filter(r => !apiIds.has(String(r.id)));
            const allToDisplay = [...extraLocal, ...fetchedReviews];

            grid.innerHTML = allToDisplay.map(r => createReviewCardHtml(r)).join('');
        }
    };

    /**
     * Modal Đánh Giá Khách Hàng (Dự phòng)
     */
    window.openCustomerReviewModal = function () {
        focusInlineReviewForm();
    };

    window.closeCustomerReviewModal = function () {
        const modal = document.getElementById('customerReviewModalBackdrop');
        if (modal) modal.classList.remove('active');
    };

    /**
     * Lắng nghe đồng bộ thời gian thực từ Admin
     */
    function setupSyncListener() {
        if (!window.BroadcastChannel) return;
        try {
            const bc = new BroadcastChannel('family_reviews_channel');
            bc.onmessage = function (e) {
                if (e.data && (e.data.type === 'REVIEW_ADDED' || e.data.type === 'REVIEW_UPDATED' || e.data.type === 'REVIEW_DELETED' || e.data.type === 'REVIEW_STATUS_CHANGED')) {
                    loadHomepageReviews();
                }
            };
        } catch (e) { }

        window.addEventListener('storage', function (e) {
            if (e.key === 'auraluxe_custom_reviews' || e.key === 'family_reviews_last_update') {
                loadHomepageReviews();
            }
        });
    }

    // Khởi chạy
    function init() {
        populateProductDropdowns();
        prefillUserInfo();
        setInlineStars(5);
        loadHomepageReviews();
        setupSyncListener();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
