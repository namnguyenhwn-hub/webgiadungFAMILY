/**
 * FAMILY LUXE APPLIANCES - Interactive Frontend Engine
 * Handles Cart Drawer, Bento Interactivity, Live Search & Category Filtering
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Header scroll effect (Tối ưu hóa requestAnimationFrame 60fps)
  const siteHeader = document.querySelector('.site-header');
  if (siteHeader) {
    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        window.requestAnimationFrame(() => {
          if (window.scrollY > 30) {
            siteHeader.classList.add('scrolled');
          } else {
            siteHeader.classList.remove('scrolled');
          }
          ticking = false;
        });
        ticking = true;
      }
    }, { passive: true });
  }

  // 2. State & Cart Management
  let cart = [];
  const cartCounter = document.querySelector('.cart-counter');
  const cartDrawer = document.getElementById('cartDrawer');
  const cartBackdrop = document.getElementById('cartBackdrop');
  const cartItemsContainer = document.getElementById('cartItemsContainer');
  const cartSubtotal = document.getElementById('cartSubtotal');
  const cartTriggers = document.querySelectorAll('.cart-trigger');
  const cartCloseBtn = document.getElementById('cartCloseBtn');

  // Toggle Cart Drawer
  function openCart() {
    cartDrawer.classList.add('active');
    cartBackdrop.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closeCart() {
    cartDrawer.classList.remove('active');
    cartBackdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  cartTriggers.forEach(btn => btn.addEventListener('click', (e) => {
    e.preventDefault();
    openCart();
  }));

  if (cartCloseBtn) cartCloseBtn.addEventListener('click', closeCart);
  if (cartBackdrop) cartBackdrop.addEventListener('click', closeCart);

  // Render Cart Items
  function renderCart() {
    const totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCounter) cartCounter.textContent = totalCount;

    if (!cartItemsContainer) return;

    if (cart.length === 0) {
      cartItemsContainer.innerHTML = `
        <div class="cart-empty-message">
          <i class="fa-solid fa-basket-shopping"></i>
          <p>Giỏ hàng của quý khách hiện đang trống.</p>
          <span style="font-size: 0.8rem; color: #9CA3AF; margin-top: 6px; display: block;">Hãy chọn những thiết bị gia dụng đẳng cấp để nhận ưu đãi!</span>
        </div>
      `;
      if (cartSubtotal) cartSubtotal.textContent = '0 ₫';
      return;
    }

    let subtotal = 0;
    cartItemsContainer.innerHTML = cart.map((item, index) => {
      subtotal += item.price * item.quantity;
      return `
        <div class="cart-item">
          <img src="${item.image}" alt="${item.name}" class="cart-item-img">
          <div class="cart-item-details">
            <h4 class="cart-item-title">${item.name}</h4>
            <div class="cart-item-price">${formatCurrency(item.price)} x ${item.quantity}</div>
            <span class="cart-item-remove" onclick="removeCartItem(${index})">
              <i class="fa-solid fa-trash-can"></i> Xóa
            </span>
          </div>
        </div>
      `;
    }).join('');

    if (cartSubtotal) cartSubtotal.textContent = formatCurrency(subtotal);
  }

  // Format Vietnamese Currency
  function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount).replace('₫', '₫');
  }

  // Load existing cart from localStorage
  try {
    const saved = localStorage.getItem('family_cart') || localStorage.getItem('aura_cart');
    if (saved) {
      const parsed = JSON.parse(saved);
      if (Array.isArray(parsed)) cart = parsed;
    }
  } catch(e) {}

  function getCartSyncUrl() {
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    return isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync';
  }

  // Add to cart functionality
  window.addToCart = function(id, name, price, image) {
    id = parseInt(id, 10);
    if (!id || isNaN(id) || id <= 0) return;

    const existing = cart.find(item => item.id == id);
    if (existing) {
      existing.quantity += 1;
    } else {
      cart.push({ id, name, price: Number(price), image, quantity: 1 });
    }
    
    try {
      localStorage.setItem('family_cart', JSON.stringify(cart));
      localStorage.setItem('aura_cart', JSON.stringify(cart));
    } catch(e) {}

    // Tự động đồng bộ toàn bộ giỏ hàng sang PHP Laravel Session
    fetch(getCartSyncUrl(), {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ items: cart, mode: 'full' })
    }).catch(err => console.log('Cart sync error:', err));

    renderCart();
    showToast(`Đã thêm "${name}" vào giỏ hàng!`);
  };

  // Mua hàng ngay lập tức (Buy Now)
  window.proceedToRealCheckout = function(id, name, price, image, event) {
    let btn = null;
    let originalContent = '';
    
    if (event) {
      try {
        event.stopPropagation();
        event.preventDefault();
        btn = event.currentTarget || event.target;
        if (btn && btn.tagName === 'BUTTON') {
          originalContent = btn.innerHTML;
          btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang xử lý...';
          btn.style.opacity = '0.7';
          btn.style.pointerEvents = 'none';
        } else {
          btn = null;
        }
      } catch(e) { btn = null; }
    }

    // Nếu không truyền ID hợp lệ, chuyển sang thanh toán toàn bộ giỏ hàng hiện tại
    if (!id || isNaN(parseInt(id, 10)) || parseInt(id, 10) <= 0) {
      if (typeof window.syncCartAndGoToCheckout === 'function') {
        return window.syncCartAndGoToCheckout();
      }
      window.location.href = (window.location.pathname.includes('/webgiadung') ? '/webgiadung' : '') + '/cart#checkoutOrderForm';
      return;
    }

    // Ép kiểu id về số nguyên để đảm bảo server tìm đúng sản phẩm trong DB
    const productId = parseInt(id, 10);
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    const isLiveServer = window.location.port === '5500';
    const backendBase = isLiveServer ? 'http://127.0.0.1:8000' : '';
    const cartUrl = backendBase + (isWebgiadung ? '/webgiadung/cart#checkoutOrderForm' : '/cart#checkoutOrderForm');

    // Kiểm tra đăng nhập qua Laravel session (gọi /api/auth/me)
    const syncUrl = backendBase + (isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync');
    const loginUrl = backendBase + (isWebgiadung ? '/webgiadung/login?redirect=' + encodeURIComponent('/webgiadung/cart#checkoutOrderForm') : '/login?redirect=' + encodeURIComponent('/cart#checkoutOrderForm'));

    // Kiểm tra xem đang đăng nhập qua Laravel session hay localStorage
    const meUrl = backendBase + (isWebgiadung ? '/webgiadung/api/auth/me' : '/api/auth/me');

    fetch(meUrl, { credentials: 'same-origin' })
      .then(res => res.json())
      .then(data => {
        const isLoggedIn = data && data.authenticated === true;
        const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
        if (!isLoggedIn && !localUser) {
          if (btn) { btn.innerHTML = originalContent; btn.style.opacity = '1'; btn.style.pointerEvents = 'auto'; }
          window.location.href = loginUrl;
          return;
        }

        // Cập nhật localStorage với sản phẩm đúng
        const singleItem = [{ id: productId, name: name, price: Number(price), image: image, quantity: 1 }];
        try {
          localStorage.setItem('family_cart', JSON.stringify(singleItem));
          localStorage.setItem('aura_cart', JSON.stringify(singleItem));
        } catch(e) {}

        // Đồng bộ giỏ hàng (chỉ sản phẩm này) lên server session
        return fetch(syncUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ items: singleItem, mode: 'full' })
        });
      })
      .then(res => {
        if (res) window.location.href = cartUrl;
      })
      .catch(() => {
        // Fallback: thử sync trực tiếp nếu /api/auth/me lỗi
        const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
        if (!localUser) { window.location.href = loginUrl; return; }
        const singleItem = [{ id: productId, name: name, price: Number(price), image: image, quantity: 1 }];
        try {
          localStorage.setItem('family_cart', JSON.stringify(singleItem));
          localStorage.setItem('aura_cart', JSON.stringify(singleItem));
        } catch(e) {}
        fetch(syncUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ items: singleItem, mode: 'full' })
        }).finally(() => { window.location.href = cartUrl; });
      });
  };

  // Đồng bộ toàn bộ giỏ hàng và chuyển sang trang thanh toán (dùng cho nút trong cart drawer)
  window.syncCartAndGoToCheckout = function() {
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    const isLiveServer = window.location.port === '5500';
    const backendBase = isLiveServer ? 'http://127.0.0.1:8000' : '';
    const syncUrl = backendBase + (isWebgiadung ? '/webgiadung/api/cart/sync' : '/api/cart/sync');
    const cartUrl = backendBase + (isWebgiadung ? '/webgiadung/cart#checkoutOrderForm' : '/cart#checkoutOrderForm');
    const loginUrl = backendBase + (isWebgiadung ? '/webgiadung/login?redirect=' + encodeURIComponent('/webgiadung/cart#checkoutOrderForm') : '/login?redirect=' + encodeURIComponent('/cart#checkoutOrderForm'));
    const meUrl = backendBase + (isWebgiadung ? '/webgiadung/api/auth/me' : '/api/auth/me');

    let currentCart = cart;
    if (!currentCart || currentCart.length === 0) {
      try {
        const raw = localStorage.getItem('family_cart') || localStorage.getItem('aura_cart');
        if (raw) {
          const parsed = JSON.parse(raw);
          if (Array.isArray(parsed)) currentCart = parsed;
        }
      } catch(e) {}
    }

    // Đảm bảo id luôn là integer dương
    const itemsToSync = (currentCart || [])
      .filter(item => item && item.id && !isNaN(parseInt(item.id, 10)) && parseInt(item.id, 10) > 0)
      .map(item => ({
        id: parseInt(item.id, 10),
        name: item.name || '',
        price: Number(item.price) || 0,
        image: item.image || item.img || '',
        quantity: Math.max(1, parseInt(item.quantity, 10) || 1)
      }));

    if (itemsToSync.length === 0) {
      window.location.href = cartUrl;
      return;
    }

    fetch(meUrl, { credentials: 'same-origin' })
      .then(res => res.json())
      .then(data => {
        const isLoggedIn = data && data.authenticated === true;
        const localUser = localStorage.getItem('family_user') || localStorage.getItem('auraluxe_user');
        if (!isLoggedIn && !localUser) {
          window.location.href = loginUrl;
          return null;
        }
        return fetch(syncUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ items: itemsToSync, mode: 'full' })
        });
      })
      .then(res => { if (res) window.location.href = cartUrl; })
      .catch(() => {
        fetch(syncUrl, {
          method: 'POST',
          credentials: 'same-origin',
          headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
          body: JSON.stringify({ items: itemsToSync, mode: 'full' })
        }).finally(() => { window.location.href = cartUrl; });
      });
  };

  // Chuyển hướng xem chi tiết sản phẩm
  window.goToProductDetail = function(id, event) {
    if (event) {
      // Ngăn chặn tuyệt đối việc chuyển hướng chi tiết khi người dùng ấn Mua Hàng hoặc Thêm Giỏ
      if (event.target && (event.target.closest('.btn-buy-now') || event.target.closest('.btn-add-cart') || event.target.closest('.quick-action-btn'))) {
        return;
      }
    }
    const isWebgiadung = window.location.pathname.includes('/webgiadung');
    window.location.href = isWebgiadung ? '/webgiadung/products/' + id : '/products/' + id;
  };

  // Remove from cart
  window.removeCartItem = function(index) {
    const removedItem = cart[index];
    cart.splice(index, 1);
    try {
      localStorage.setItem('family_cart', JSON.stringify(cart));
      localStorage.setItem('aura_cart', JSON.stringify(cart));
    } catch(e) {}

    // Đồng bộ lại giỏ hàng sau khi xóa
    fetch(getCartSyncUrl(), {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({ items: cart, mode: 'full' })
    }).catch(err => console.log('Cart sync error:', err));

    renderCart();
    if (removedItem) {
      showToast(`Đã xóa sản phẩm khỏi giỏ hàng.`);
    }
  };



  // 3. Toast Notifications
  const toastContainer = document.getElementById('toastContainer');
  window.showToast = function(message) {
    if (!toastContainer) return;
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `
      <i class="fa-solid fa-circle-check"></i>
      <span>${message}</span>
    `;
    toastContainer.appendChild(toast);
    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(10px)';
      toast.style.transition = 'all 0.3s ease';
      setTimeout(() => toast.remove(), 300);
    }, 3200);
  };

  // 4. Comprehensive Live Search System
  const searchInput = document.getElementById('mainSearchInput');
  const searchForm = document.getElementById('mainSearchForm');
  const liveDropdown = document.getElementById('liveSearchDropdown');
  const clearBtn = document.getElementById('clearSearchBtn');
  const activeSearchBanner = document.getElementById('activeSearchBanner');

  function getSearchableProducts() {
    const cards = document.querySelectorAll('.product-card');
    const items = [];
    cards.forEach((card, idx) => {
      const id = card.getAttribute('data-product-id') || (idx + 1);
      const name = card.getAttribute('data-product-name') || card.querySelector('.product-title')?.textContent?.trim() || '';
      const category = card.getAttribute('data-category') || '';
      const categoryName = card.querySelector('.product-category-name')?.textContent?.trim() || 'Thiết bị';
      const price = parseInt(card.getAttribute('data-product-price') || '0', 10);
      const priceFormatted = card.querySelector('.price-current')?.textContent?.trim() || (new Intl.NumberFormat('vi-VN').format(price) + ' ₫');
      const img = card.getAttribute('data-product-img') || card.querySelector('img')?.getAttribute('src') || '';
      const specs = card.querySelector('.product-specs')?.textContent?.trim() || '';
      items.push({ id, name, category, categoryName, price, priceFormatted, img, specs, card });
    });
    return items;
  }

  function escapeHtml(str) {
    return str.replace(/[&<>'"]/g, tag => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      "'": '&#39;',
      '"': '&quot;'
    }[tag] || tag));
  }

  function removeVietnameseTones(str) {
    if (!str) return '';
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    return str.toLowerCase().trim();
  }

  function highlightMatch(text, query) {
    if (!query) return escapeHtml(text);
    const clean = query.trim();
    const regex = new RegExp(`(${clean.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return escapeHtml(text).replace(regex, '<mark>$1</mark>');
  }

  function renderLiveSearchResults(query) {
    if (!liveDropdown) return;
    const cleanQuery = query.toLowerCase().trim();
    const normalizedQuery = removeVietnameseTones(query);

    if (!cleanQuery) {
      liveDropdown.style.display = 'none';
      liveDropdown.innerHTML = '';
      return;
    }

    const products = getSearchableProducts();
    const matches = products.filter(p => {
      const nameNorm = removeVietnameseTones(p.name);
      const catNorm = removeVietnameseTones(p.categoryName);
      const specsNorm = removeVietnameseTones(p.specs);

      return nameNorm.includes(normalizedQuery) ||
             catNorm.includes(normalizedQuery) ||
             specsNorm.includes(normalizedQuery) ||
             p.name.toLowerCase().includes(cleanQuery);
    });

    if (matches.length === 0) {
      liveDropdown.innerHTML = `
        <div class="search-no-results">
          <i class="fa-solid fa-magnifying-glass"></i>
          <p>Không tìm thấy sản phẩm phù hợp</p>
          <span>Thử tìm với "nồi chiên", "bếp từ", "tủ lạnh", "robot"...</span>
        </div>
      `;
    } else {
      let listHtml = matches.slice(0, 5).map(item => `
        <div class="search-result-item" onclick="selectLiveProduct('${item.id}')">
          <img src="${item.img}" alt="${escapeHtml(item.name)}" class="search-result-img" onerror="this.src='public/images/products/air_fryer.jpg'">
          <div class="search-result-info">
            <span class="search-result-cat">${escapeHtml(item.categoryName)}</span>
            <div class="search-result-title">${highlightMatch(item.name, cleanQuery)}</div>
            <div class="search-result-price">${item.priceFormatted}</div>
          </div>
          <i class="fa-solid fa-chevron-right" style="font-size: 0.75rem; color: #CBD5E1;"></i>
        </div>
      `).join('');

      liveDropdown.innerHTML = `
        <div class="search-dropdown-header">
          <span>Tìm thấy <strong>${matches.length}</strong> sản phẩm cho "<em>${escapeHtml(cleanQuery)}</em>"</span>
          <span style="font-size: 0.7rem; color: var(--gold-dark); cursor: pointer;" onclick="closeLiveDropdown()">Đóng</span>
        </div>
        <div class="search-dropdown-list">
          ${listHtml}
        </div>
        <div class="search-dropdown-footer" onclick="executeSearchGrid('${escapeHtml(cleanQuery)}')">
          <span>Xem tất cả ${matches.length} sản phẩm trên trang</span>
          <i class="fa-solid fa-arrow-down"></i>
        </div>
      `;
    }

    liveDropdown.style.display = 'block';
  }

  // 4. Products Pagination & Filtering System (8 items per page, 4 columns x 2 rows)
  const ITEMS_PER_PAGE = 8;
  let currentProductPage = 1;
  let currentFilterCategory = 'all';
  let currentSearchQuery = '';

  const catPills = document.querySelectorAll('.cat-pill');
  const filterTabBtns = document.querySelectorAll('.filter-tab-btn');

  function updateProductsDisplayAndPagination() {
    const productCards = document.querySelectorAll('.product-card');
    const container = document.querySelector('.products-grid');
    const paginationContainer = document.getElementById('productsPagination');
    if (!productCards.length) return;

    const cleanQuery = currentSearchQuery.toLowerCase().trim();
    const normalizedQuery = removeVietnameseTones(cleanQuery);

    // 1. Determine which cards match both category and search
    const matchedCards = [];
    productCards.forEach(card => {
      const cardCategory = card.getAttribute('data-category') || '';
      const categoryMatches = (currentFilterCategory === 'all' || cardCategory === currentFilterCategory);

      if (!categoryMatches) {
        card.style.setProperty('display', 'none', 'important');
        card.classList.add('card-hidden');
        return;
      }

      const name = (card.getAttribute('data-product-name') || card.querySelector('.product-title')?.textContent || '').toLowerCase();
      const specs = (card.querySelector('.product-specs')?.textContent || '').toLowerCase();
      const catName = (card.querySelector('.product-category-name')?.textContent || '').toLowerCase();

      const nameNorm = removeVietnameseTones(name);
      const specsNorm = removeVietnameseTones(specs);
      const catNorm = removeVietnameseTones(catName);

      const queryMatches = (!cleanQuery ||
        nameNorm.includes(normalizedQuery) ||
        specsNorm.includes(normalizedQuery) ||
        catNorm.includes(normalizedQuery) ||
        name.includes(cleanQuery) ||
        specs.includes(cleanQuery) ||
        catName.includes(cleanQuery)
      );

      if (queryMatches) {
        matchedCards.push(card);
      } else {
        card.style.setProperty('display', 'none', 'important');
        card.classList.add('card-hidden');
      }
    });

    const totalMatched = matchedCards.length;
    const totalPages = Math.max(1, Math.ceil(totalMatched / ITEMS_PER_PAGE));

    // Clamp current page
    if (currentProductPage > totalPages) currentProductPage = totalPages;
    if (currentProductPage < 1) currentProductPage = 1;

    // 2. Display only items for currentProductPage (exact 8 items per page)
    const startIndex = (currentProductPage - 1) * ITEMS_PER_PAGE;
    const endIndex = startIndex + ITEMS_PER_PAGE;

    matchedCards.forEach((card, index) => {
      if (index >= startIndex && index < endIndex) {
        card.style.setProperty('display', 'flex', 'important');
        card.classList.remove('card-hidden');
      } else {
        card.style.setProperty('display', 'none', 'important');
        card.classList.add('card-hidden');
      }
    });

    // 3. Search Banner & Dynamic Empty State
    const existingEmpty = document.getElementById('dynamicEmptySearchState');
    if (existingEmpty) existingEmpty.remove();

    if (cleanQuery) {
      if (activeSearchBanner) {
        activeSearchBanner.style.display = 'block';
        activeSearchBanner.innerHTML = `
          <div class="search-active-banner">
            <span><i class="fa-solid fa-magnifying-glass" style="color: var(--gold-dark); margin-right: 6px;"></i> Kết quả tìm kiếm cho: "<strong>${escapeHtml(cleanQuery)}</strong>" (${totalMatched} sản phẩm)</span>
            <button type="button" class="btn-clear-search" onclick="clearSearchFilter()">
              <i class="fa-solid fa-xmark"></i> Xóa tìm kiếm
            </button>
          </div>
        `;
      }

      if (totalMatched === 0 && container) {
        const emptyDiv = document.createElement('div');
        emptyDiv.id = 'dynamicEmptySearchState';
        emptyDiv.className = 'empty-search-state';
        emptyDiv.innerHTML = `
          <i class="fa-solid fa-box-open"></i>
          <h3>Không tìm thấy sản phẩm phù hợp</h3>
          <p>Không có sản phẩm nào khớp với từ khóa "<strong>${escapeHtml(cleanQuery)}</strong>".</p>
          <button type="button" class="btn-clear-search" onclick="clearSearchFilter()" style="font-size: 0.9rem; padding: 8px 20px;">
            Xem tất cả sản phẩm
          </button>
        `;
        container.appendChild(emptyDiv);
      }
    } else {
      if (activeSearchBanner) {
        activeSearchBanner.style.display = 'none';
        activeSearchBanner.innerHTML = '';
      }
    }

    // 4. Render Luxury Pagination Controls matching user design
    if (!paginationContainer) return;

    if (totalMatched === 0) {
      paginationContainer.innerHTML = '';
      return;
    }

    let pagesHtml = '';
    for (let p = 1; p <= totalPages; p++) {
      pagesHtml += `
        <button type="button" class="pagination-btn ${p === currentProductPage ? 'active' : ''}" 
                onclick="goToProductPage(${p})" 
                aria-label="Trang ${p}" 
                ${p === currentProductPage ? 'aria-current="page"' : ''}>
          ${p}
        </button>
      `;
    }

    const prevDisabled = currentProductPage <= 1 ? 'disabled' : '';
    const nextDisabled = currentProductPage >= totalPages ? 'disabled' : '';

    paginationContainer.innerHTML = `
      <nav class="products-pagination-nav" aria-label="Phân trang danh mục sản phẩm">
        <button type="button" class="pagination-btn pagination-prev ${prevDisabled}" 
                ${prevDisabled} 
                onclick="goToProductPage(${currentProductPage - 1})" 
                aria-label="Trang trước" 
                title="Trang trước">
          <i class="fa-solid fa-chevron-left"></i>
        </button>
        ${pagesHtml}
        <button type="button" class="pagination-btn pagination-next ${nextDisabled}" 
                ${nextDisabled} 
                onclick="goToProductPage(${currentProductPage + 1})" 
                aria-label="Trang tiếp theo" 
                title="Trang tiếp theo">
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </nav>
    `;
  }

  // Expose global pagination handlers
  window.updateProductsDisplayAndPagination = updateProductsDisplayAndPagination;
  window.goToProductPage = function(pageNumber) {
    currentProductPage = pageNumber;
    updateProductsDisplayAndPagination();
    const productSec = document.getElementById('san-pham');
    if (productSec) {
      productSec.scrollIntoView({ behavior: 'smooth' });
    }
  };

  // MutationObserver to enforce max 2 rows (8 products) per page dynamically
  const gridElem = document.querySelector('.products-grid');
  if (gridElem && window.MutationObserver) {
    let debounceTimer = null;
    const observer = new MutationObserver((mutations) => {
      let hasChildChanges = false;
      for (const m of mutations) {
        if (m.type === 'childList') {
          hasChildChanges = true;
          break;
        }
      }
      if (hasChildChanges) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          updateProductsDisplayAndPagination();
        }, 40);
      }
    });
    observer.observe(gridElem, { childList: true });
  }

  function filterGridByQuery(query) {
    currentSearchQuery = query || '';
    currentProductPage = 1;
    updateProductsDisplayAndPagination();
  }

  function filterByCategory(categoryId) {
    currentFilterCategory = categoryId;
    currentProductPage = 1;
    updateProductsDisplayAndPagination();

    // Sync active classes
    catPills.forEach(pill => {
      pill.classList.toggle('active', pill.getAttribute('data-category') === categoryId);
    });
    filterTabBtns.forEach(btn => {
      btn.classList.toggle('active', btn.getAttribute('data-category') === categoryId);
    });
  }

  window.filterByCategory = filterByCategory;

  // Helper cho Nút "Danh mục" trên Header Menu (Hiển thị danh mục & trỏ đến danh mục muốn chọn)
  window.selectNavCategory = function(catSlug, e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    // Đóng toàn bộ dropdown
    document.querySelectorAll('.luxury-nav-dropdown').forEach(d => d.classList.remove('show'));
    document.querySelectorAll('.nav-dropdown-wrapper').forEach(w => w.classList.remove('active'));

    // Đóng menu mobile nếu đang mở
    if (headerNav && window.innerWidth <= 880) {
      headerNav.style.display = 'none';
    }

    // Lọc danh mục tương ứng
    filterByCategory(catSlug);

    // Cuộn mượt đến phần sản phẩm
    const productSec = document.getElementById('san-pham') || document.getElementById('danh-muc');
    if (productSec) {
      const headerOffset = 85;
      const elementPosition = productSec.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  };

  // Helper cho Nút "Sản phẩm" trên Header Menu (Hiển thị các loại sản phẩm)
  window.selectNavProductType = function(catSlug, e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    window.selectNavCategory(catSlug, e);
  };

  window.handleNavCategoryClick = function(e) {
    if (window.innerWidth <= 880) {
      e.preventDefault();
      e.stopPropagation();
      const wrap = document.getElementById('navCategoryDropdownWrapper');
      if (wrap) wrap.classList.toggle('active');
    } else {
      // Khi click trực tiếp nút Danh mục trên desktop: cuộn mượt tới phần Danh mục / Sản phẩm
      const catSec = document.getElementById('danh-muc') || document.getElementById('san-pham');
      if (catSec) {
        e.preventDefault();
        const headerOffset = 85;
        const elementPosition = catSec.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
      }
    }
  };

  window.handleNavProductClick = function(e) {
    if (window.innerWidth <= 880) {
      e.preventDefault();
      e.stopPropagation();
      const wrap = document.getElementById('navProductDropdownWrapper');
      if (wrap) wrap.classList.toggle('active');
    } else {
      // Khi click trực tiếp nút Sản phẩm trên desktop: cuộn mượt tới phần Sản phẩm và hiển thị tất cả
      const prodSec = document.getElementById('san-pham');
      if (prodSec) {
        e.preventDefault();
        filterByCategory('all');
        const headerOffset = 85;
        const elementPosition = prodSec.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
      }
    }
  };

  // Đóng dropdown khi click ra ngoài màn hình
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.nav-dropdown-wrapper')) {
      document.querySelectorAll('.luxury-nav-dropdown').forEach(d => d.classList.remove('show'));
      document.querySelectorAll('.nav-dropdown-wrapper').forEach(w => w.classList.remove('active'));
    }
  });

  // Global search helpers
  window.closeLiveDropdown = function() {
    if (liveDropdown) liveDropdown.style.display = 'none';
  };

  window.selectLiveProduct = function(productId) {
    closeLiveDropdown();
    currentFilterCategory = 'all';
    currentSearchQuery = '';
    if (searchInput) searchInput.value = '';
    if (clearBtn) clearBtn.style.display = 'none';

    // Sync tabs back to 'all'
    catPills.forEach(pill => pill.classList.toggle('active', pill.getAttribute('data-category') === 'all'));
    filterTabBtns.forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-category') === 'all'));

    const allCards = Array.from(document.querySelectorAll('.product-card'));
    const targetCard = document.querySelector(`.product-card[data-product-id="${productId}"]`);
    if (targetCard) {
      const cardIndex = allCards.indexOf(targetCard);
      if (cardIndex !== -1) {
        currentProductPage = Math.floor(cardIndex / ITEMS_PER_PAGE) + 1;
      }
      updateProductsDisplayAndPagination();
      targetCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
      targetCard.style.transition = 'box-shadow 0.4s ease, outline 0.4s ease';
      targetCard.style.outline = '2px solid var(--gold-primary)';
      targetCard.style.boxShadow = '0 0 25px rgba(197, 160, 89, 0.4)';
      setTimeout(() => {
        targetCard.style.outline = '';
        targetCard.style.boxShadow = '';
      }, 2400);
    } else {
      const productSec = document.getElementById('san-pham');
      if (productSec) productSec.scrollIntoView({ behavior: 'smooth' });
    }
  };

  window.executeSearchGrid = function(query) {
    closeLiveDropdown();
    if (searchInput) searchInput.value = query;
    if (clearBtn) clearBtn.style.display = query ? 'flex' : 'none';
    filterGridByQuery(query);
    const productSec = document.getElementById('san-pham');
    if (productSec) productSec.scrollIntoView({ behavior: 'smooth' });
  };

  window.clearSearchInput = function() {
    if (searchInput) {
      searchInput.value = '';
      searchInput.focus();
    }
    if (clearBtn) clearBtn.style.display = 'none';
    closeLiveDropdown();
    filterGridByQuery('');
  };

  window.clearSearchFilter = function() {
    clearSearchInput();
  };

  window.handleSearchSubmit = function(e) {
    if (e) e.preventDefault();
    const query = searchInput ? searchInput.value.trim() : '';
    executeSearchGrid(query);
  };

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const query = e.target.value;
      if (clearBtn) clearBtn.style.display = query.trim() ? 'flex' : 'none';
      renderLiveSearchResults(query);
      filterGridByQuery(query);
    });

    searchInput.addEventListener('focus', (e) => {
      if (e.target.value.trim()) {
        renderLiveSearchResults(e.target.value);
      }
    });

    document.addEventListener('click', (e) => {
      if (searchInput && liveDropdown && !searchInput.contains(e.target) && !liveDropdown.contains(e.target)) {
        closeLiveDropdown();
      }
    });
  }

  // Hook Category Pills and Filter Tab Buttons
  catPills.forEach(pill => {
    pill.addEventListener('click', () => {
      const cat = pill.getAttribute('data-category');
      filterByCategory(cat);
      const productSec = document.getElementById('san-pham');
      if (productSec) productSec.scrollIntoView({ behavior: 'smooth' });
    });
  });

  filterTabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const cat = btn.getAttribute('data-category');
      filterByCategory(cat);
    });
  });

  // Initial call to set up 8-items-per-page pagination
  updateProductsDisplayAndPagination();

  // 6. VIP Voucher Newsletter Form Submission
  const newsletterForm = document.getElementById('newsletterForm');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const input = newsletterForm.querySelector('input');
      if (input && input.value.trim() !== '') {
        const voucherCode = 'FAMILY500K';
        alert(`Chúc mừng quý khách! Mã Voucher VIP của bạn là: [ ${voucherCode} ]\nĐã giảm ngay 500.000đ cho đơn hàng đầu tiên.`);
        input.value = '';
        showToast(`Đã đăng ký thành công! Nhận voucher 500.000đ.`);
      }
    });
  }

  // 7. Mobile Hamburger Menu Toggle
  const mobileMenuBtn = document.getElementById('mobileMenuBtn');
  const headerNav = document.querySelector('.header-nav');
  if (mobileMenuBtn && headerNav) {
    mobileMenuBtn.addEventListener('click', () => {
      const isVisible = headerNav.style.display === 'flex';
      headerNav.style.display = isVisible ? 'none' : 'flex';
      if (!isVisible) {
        headerNav.style.flexDirection = 'column';
        headerNav.style.position = 'absolute';
        headerNav.style.top = '100%';
        headerNav.style.left = '0';
        headerNav.style.right = '0';
        headerNav.style.background = '#FFFFFF';
        headerNav.style.padding = '20px';
        headerNav.style.boxShadow = '0 10px 30px rgba(0,0,0,0.1)';
        headerNav.style.zIndex = '99';
      }
    });
  }

  // 8. User Menu Dropdown Toggle
  const userMenuBtn = document.getElementById('userMenuToggleBtn');
  const userDropdownMenu = document.getElementById('userDropdownMenu');
  if (userMenuBtn && userDropdownMenu) {
    userMenuBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isShown = userDropdownMenu.style.display === 'block';
      userDropdownMenu.style.display = isShown ? 'none' : 'block';
    });

    document.addEventListener('click', (e) => {
      if (!userDropdownMenu.contains(e.target) && !userMenuBtn.contains(e.target)) {
        userDropdownMenu.style.display = 'none';
      }
    });
  }

  // 9. Client-side Real-time Database Synchronization for Homepage
  async function syncHomepageWithBackendApi() {
    try {
      const isWebgiadung = window.location.pathname.includes('/webgiadung');
      const apiUrl = isWebgiadung ? '/webgiadung/api/products' : '/api/products';
      const res = await fetch(apiUrl, { cache: 'no-store' });
      const data = await res.json();
      if (!data || !data.success || !Array.isArray(data.products) || data.products.length === 0) {
        return;
      }

      const products = data.products;
      const grid = document.querySelector('.products-grid');
      if (!grid) return;

      const basePath = isWebgiadung ? '/webgiadung/' : '/';
      const catCounts = {
        'all': products.length,
        'noi-chien': 0,
        'bep-dien-tu': 0,
        'tu-lanh': 0,
        'noi-com-ih': 0,
        'robot-hut-bui': 0
      };

      let newGridHtml = '';
      products.forEach((p, pIdx) => {
        const catSlug = p.category_slug || 'all';
        if (catCounts.hasOwnProperty(catSlug)) {
          catCounts[catSlug]++;
        }

        let img = p.image || 'images/products/air_fryer.jpg';
        if (!img.startsWith('http')) {
          if (img.startsWith('products/')) {
            img = 'storage/' + img;
          }
          img = img.replace(/^public\//, '');
          img = basePath + img.replace(/^\//, '');
        }

        const priceNum = parseFloat(p.price) || 0;
        const priceFmt = formatCurrency(priceNum);
        const oldPriceFmt = priceNum > 0 ? formatCurrency(Math.round(priceNum * 1.2 / 10000) * 10000) : '';
        const specs = p.description ? (p.description.length > 55 ? p.description.substring(0, 52) + '...' : p.description) : 'Bảo hành chính hãng 24T';
        const stock = parseInt(p.stock) || 10;
        const pNameEsc = escapeHtml(p.name);
        const pId = p.id;
        const detailUrl = `${basePath}products/${pId}`;
        const hideClass = pIdx >= ITEMS_PER_PAGE ? 'product-card card-hidden' : 'product-card';
        const hideAttr = pIdx >= ITEMS_PER_PAGE ? 'display: none !important; cursor: pointer;' : 'cursor: pointer;';

        newGridHtml += `
          <div class="${hideClass}" data-category="${catSlug}" data-product-id="${pId}" data-product-name="${pNameEsc}" data-product-price="${priceNum}" data-product-img="${img}" onclick="if(typeof goToProductDetail==='function'){goToProductDetail(${pId}, event);}" style="${hideAttr}">
              <span class="product-badge-flag badge-hot">CHÍNH HÃNG</span>
              <div class="product-img-box">
                  <a href="${detailUrl}" onclick="if(typeof goToProductDetail==='function'){goToProductDetail(${pId}, event);}" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                      <img src="${img}" alt="${pNameEsc}" loading="lazy" onerror="this.onerror=null; this.src='${basePath}images/products/air_fryer.jpg'">
                  </a>
                  <div class="card-quick-actions" onclick="event.stopPropagation()">
                      <button type="button" class="quick-action-btn" title="Xem thông số kỹ thuật" onclick="if(typeof openProductQuickSpecModal==='function'){openProductQuickSpecModal(${pId});}else{alert('${pNameEsc.replace(/'/g, "\\'")}\\n\\nGiá: ${priceFmt}\\nThông số: ${specs.replace(/'/g, "\\'")}\\nBảo hành chính hãng 24 tháng.');}"><i class="fa-regular fa-eye"></i></button>
                      <button type="button" class="quick-action-btn" title="Yêu thích" onclick="showToast('Đã lưu vào danh sách yêu thích!')"><i class="fa-regular fa-heart"></i></button>
                  </div>
              </div>
              <div class="product-category-name">${escapeHtml(p.category || 'Gia Dụng Thông Minh')}</div>
              <h4 class="product-title" title="${pNameEsc}">
                  <a href="${detailUrl}" onclick="if(typeof goToProductDetail==='function'){goToProductDetail(${pId}, event);}" style="color: inherit; text-decoration: none;">${pNameEsc}</a>
              </h4>
              <div class="product-specs">${escapeHtml(specs)}</div>
              <div class="product-rating-box">
                  <div class="rating-stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                  <span class="sold-count">| Còn lại ${stock} sp</span>
              </div>
              <div class="product-price-row">
                  <div>
                      <span class="price-current">${priceFmt}</span>
                      ${oldPriceFmt ? `<span class="price-old">${oldPriceFmt}</span>` : ''}
                  </div>
                  <span style="font-size: 0.75rem; color: #DC2626; font-weight: 700;">-18%</span>
              </div>
              <div style="display: flex; gap: 8px; margin-top: 12px;">
                  <button type="button" class="btn-buy-now" style="flex: 1.2; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 12px; background: linear-gradient(135deg, #DFC07A 0%, #C5A059 50%, #9B782F 100%); color: #FFFFFF; font-weight: 700; font-size: 0.85rem; border: none; border-radius: 9999px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(197, 160, 89, 0.25);" onclick="if(typeof proceedToRealCheckout === 'function'){proceedToRealCheckout(${pId}, '${pNameEsc.replace(/'/g, "\\'")}', ${priceNum}, '${img}')}else{addToCart(${pId}, '${pNameEsc.replace(/'/g, "\\'")}', ${priceNum}, '${img}')}">
                      <i class="fa-solid fa-bolt-lightning"></i> Mua Hàng
                  </button>
                  <button type="button" class="btn-add-cart" style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 9px 10px; background: #FFFFFF; color: #8C6A24; border: 1px solid #EAE2D5; font-weight: 600; font-size: 0.825rem; border-radius: 9999px; cursor: pointer; transition: all 0.2s;" onclick="addToCart(${pId}, '${pNameEsc.replace(/'/g, "\\'")}', ${priceNum}, '${img}')">
                      <i class="fa-solid fa-cart-plus"></i> Giỏ
                  </button>
              </div>
          </div>
        `;
      });

      grid.innerHTML = newGridHtml;

      // Update Category pills count in UI
      Object.keys(catCounts).forEach(cat => {
        const pill = document.querySelector(`.cat-pill[data-category="${cat}"] .count`);
        if (pill) pill.textContent = catCounts[cat];
      });

      // Kích hoạt lại phân trang tối đa 2 hàng (8 sản phẩm / trang)
      updateProductsDisplayAndPagination();

    } catch(err) {
      console.warn('Live product sync notice:', err);
    }
  }

  // Chỉ tự động fetch & re-render nếu trang chưa có thẻ sản phẩm nào từ máy chủ (tránh giật lag & reflow)
  if (document.querySelectorAll('.product-card').length === 0) {
    syncHomepageWithBackendApi();
  }
  window.syncHomepageWithBackendApi = syncHomepageWithBackendApi;
});

