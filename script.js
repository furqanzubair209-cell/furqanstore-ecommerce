// ============== THEME TOGGLE ==============
function toggleTheme() {
  const body = document.body;
  const floatingIcon = document.getElementById('themeIconFloating');

  if (body.classList.contains('dark-mode')) {
    body.classList.remove('dark-mode');
    body.classList.add('light-mode');
    if (floatingIcon) {
      floatingIcon.classList.remove('fa-moon');
      floatingIcon.classList.add('fa-sun');
    }
    localStorage.setItem('furqan_theme', 'light');
    showToast('Light mode activated', 'info');
  } else {
    body.classList.remove('light-mode');
    body.classList.add('dark-mode');
    if (floatingIcon) {
      floatingIcon.classList.remove('fa-sun');
      floatingIcon.classList.add('fa-moon');
    }
    localStorage.setItem('furqan_theme', 'dark');
    showToast('Dark mode activated', 'info');
  }
}

function loadTheme() {
  const savedTheme = localStorage.getItem('furqan_theme') || 'dark';
  const body = document.body;
  const floatingIcon = document.getElementById('themeIconFloating');

  if (savedTheme === 'light') {
    body.classList.remove('dark-mode');
    body.classList.add('light-mode');
    if (floatingIcon) {
      floatingIcon.classList.remove('fa-moon');
      floatingIcon.classList.add('fa-sun');
    }
  } else {
    body.classList.remove('light-mode');
    body.classList.add('dark-mode');
    if (floatingIcon) {
      floatingIcon.classList.remove('fa-sun');
      floatingIcon.classList.add('fa-moon');
    }
  }
}

// ============== API CONFIGURATION ==============
const BASE_URL = 'http://localhost/furqanstore';

const API_URLS = {
  auth: `${BASE_URL}/api/auth`,
  cart: `${BASE_URL}/api/cart`,
  products: `${BASE_URL}/api/products`,
  orders: `${BASE_URL}/api/orders`,
  contact: `${BASE_URL}/api/contact`
};

async function apiCall(type, endpoint, method = 'GET', data = null) {
  const url = `${API_URLS[type]}/${endpoint}`;
  console.log('Calling:', url);

  const options = {
    method: method,
    headers: {
      'Content-Type': 'application/json',
    },
    credentials: 'include'
  };

  if (data && method === 'POST') {
    options.body = JSON.stringify(data);
  }

  try {
    const response = await fetch(url, options);
    const result = await response.json();
    console.log('Response:', result);
    return result;
  } catch (error) {
    console.error('API Error:', error);
    return { success: false, message: 'Network error: ' + error.message };
  }
}

// ============== PRODUCT DATABASE (Fallback) ==============
const products = [
  { id: 1, name: "Premium Sneakers", price: 7999, category: "footwear", vendor: "Nike Store", vendorId: 2, rating: 4.5, reviews: 128, image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400", badge: "hot" },
  { id: 2, name: "Wireless Headphones", price: 12499, category: "audio", vendor: "Sony Official", vendorId: 2, rating: 4.8, reviews: 256, image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400", badge: "best" },
  { id: 3, name: "Smart Watch Pro", price: 15999, category: "electronics", vendor: "Apple Store", vendorId: 3, rating: 4.6, reviews: 89, image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400", badge: "new" },
  { id: 4, name: "Bluetooth Speaker", price: 6999, category: "audio", vendor: "JBL Official", vendorId: 2, rating: 4.3, reviews: 67, image: "https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=400" },
  { id: 5, name: "Leather Backpack", price: 8999, category: "fashion", vendor: "Fashion Hub", vendorId: 4, rating: 4.7, reviews: 45, image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400", badge: "sale" },
  { id: 6, name: "Mechanical Keyboard", price: 10999, category: "electronics", vendor: "Gaming Gear", vendorId: 3, rating: 4.9, reviews: 312, image: "https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400", badge: "hot" }
];

// ============== GLOBAL VARIABLES ==============
let currentUser = JSON.parse(localStorage.getItem('furqan_current_user')) || null;
let cart = [];
let currentPage = 1;
let itemsPerPage = 12;
let filteredProducts = [...products];
let currentSlide = 0;
let slideInterval;

// ============== CURSOR EFFECT ==============
const cursor = document.querySelector('.cursor');
const cursorFollower = document.querySelector('.cursor-follower');

if (cursor && cursorFollower) {
  document.addEventListener('mousemove', (e) => {
    cursor.style.transform = `translate(${e.clientX - 4}px, ${e.clientY - 4}px)`;
    cursorFollower.style.transform = `translate(${e.clientX - 20}px, ${e.clientY - 20}px)`;
  });

  // Select all interactive elements for cursor interaction
  const interactives = document.querySelectorAll(`
    a, button, .product-premium-card, .category-premium-card, 
    .theme-toggle-floating, .theme-toggle-header, .nav-link, 
    .stat-card-premium, .card-premium, .badge-premium, .user-card,
    .search-bar-premium, .notification-bell, .user-menu-premium,
    .cart-preview, .btn-premium, .input-premium, .textarea-premium, .select-premium
  `);

  interactives.forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursorFollower.classList.add('active');
      if (el.classList.contains('product-premium-card')) {
        cursorFollower.innerHTML = '<span style="font-size: 8px; color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">View</span>';
      } else if (el.classList.contains('add-to-cart-premium')) {
        cursorFollower.innerHTML = '<span style="font-size: 8px; color: white; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Buy</span>';
      }
    });
    el.addEventListener('mouseleave', () => {
      cursorFollower.classList.remove('active');
      cursorFollower.innerHTML = '';
    });
  });
}

// ============== SESSION VERIFICATION ==============
async function verifySession() {
  const result = await apiCall('auth', 'verify.php', 'GET');
  if (result.success) {
    currentUser = result.data;
    localStorage.setItem('furqan_current_user', JSON.stringify(currentUser));
    updateUIForUser();
    await loadCart();
    showToast(`Welcome back, ${currentUser.full_name}!`, 'success');
  } else {
    currentUser = null;
    localStorage.removeItem('furqan_current_user');
    updateUIForUser();
  }
}

// ============== LOAD CART FROM API ==============
async function loadCart() {
  if (!currentUser) {
    const savedCart = localStorage.getItem('furqan_cart');
    cart = savedCart ? JSON.parse(savedCart) : [];
    updateCartCount();
    return;
  }

  const result = await apiCall('cart', 'get_cart.php', 'GET');
  if (result.success) {
    cart = result.data.items || [];
    updateCartCount();
  } else {
    cart = [];
  }
}

// ============== INITIALIZATION ==============
document.addEventListener('DOMContentLoaded', async () => {
  loadTheme();
  await verifySession();
  loadCategories();
  loadVendors();
  loadFeaturedProducts();
  renderHomeCategories();
  updateCartCount();
  updateUIForUser();
  startSlider();
  await loadProductsToGrid();
  initHeroDots();
  initAnimations();

  const menuTrigger = document.getElementById('menuTrigger');
  if (menuTrigger) {
    menuTrigger.addEventListener('click', () => {
      document.querySelector('.premium-sidebar').classList.toggle('open');
    });
  }
});

// ============== HERO SLIDER ==============
function startSlider() { slideInterval = setInterval(nextSlide, 5000); initHeroDots(); }
function initHeroDots() {
  const slides = document.querySelectorAll('.hero-slide');
  const dotsContainer = document.querySelector('.hero-dots');
  if (!dotsContainer) return;
  dotsContainer.innerHTML = '';
  slides.forEach((_, i) => {
    const dot = document.createElement('div');
    dot.classList.add('hero-dot');
    if (i === 0) dot.classList.add('active');
    dot.onclick = () => goToSlide(i);
    dotsContainer.appendChild(dot);
  });
}
function goToSlide(index) {
  const slides = document.querySelectorAll('.hero-slide');
  slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
  currentSlide = index;
  const dots = document.querySelectorAll('.hero-dot');
  dots.forEach((dot, i) => dot.classList.toggle('active', i === index));
}
function nextSlide() { const slides = document.querySelectorAll('.hero-slide'); currentSlide = (currentSlide + 1) % slides.length; goToSlide(currentSlide); }
function prevSlide() { const slides = document.querySelectorAll('.hero-slide'); currentSlide = (currentSlide - 1 + slides.length) % slides.length; goToSlide(currentSlide); }

// ============== CATEGORIES & VENDORS ==============
function loadCategories() {
  const categories = [...new Set(products.map(p => p.category))];
  const categorySelect = document.getElementById('categoryFilter');
  const sidebarCategories = document.getElementById('premiumCategories');
  if (categorySelect) {
    categorySelect.innerHTML = '<option value="all">All Categories</option>';
    categories.forEach(cat => { const option = document.createElement('option'); option.value = cat; option.textContent = cat.charAt(0).toUpperCase() + cat.slice(1); categorySelect.appendChild(option); });
  }
  if (sidebarCategories) sidebarCategories.innerHTML = categories.map(cat => `<a onclick="filterByCategory('${cat}')">${cat.charAt(0).toUpperCase() + cat.slice(1)}</a>`).join('');
}
function loadVendors() {
  const vendors = [...new Set(products.map(p => p.vendor))];
  const vendorSelect = document.getElementById('vendorFilter');
  if (vendorSelect) {
    vendorSelect.innerHTML = '<option value="all">All Vendors</option>';
    vendors.forEach(vendor => { const option = document.createElement('option'); option.value = vendor; option.textContent = vendor; vendorSelect.appendChild(option); });
  }
}
function filterByCategory(category) { const categoryFilter = document.getElementById('categoryFilter'); if (categoryFilter) categoryFilter.value = category; filterProducts(); showPage('products'); }
function showPage(pageName) {
  const main = document.querySelector('.premium-main');
  if (main) {
    main.style.opacity = '0';
    main.style.transform = 'translateY(10px)';
    main.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
  }

  setTimeout(() => {
    document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
    const targetPage = document.getElementById(`${pageName}Page`);
    if (targetPage) targetPage.classList.add('active');

    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
    if (window.event && window.event.target) {
      const clickedLink = window.event.target.closest('.nav-link');
      if (clickedLink) clickedLink.classList.add('active');
    }

    if (pageName === 'products') loadProductsToGrid();
    if (window.innerWidth <= 1024) document.querySelector('.premium-sidebar')?.classList.remove('open');

    if (main) {
      main.style.opacity = '1';
      main.style.transform = 'translateY(0)';
    }

    // Re-trigger animations for the new page
    initAnimations();
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }, 300);
}

// ============== PRODUCTS ==============
function filterProducts() {
  const search = document.getElementById('globalSearch')?.value.toLowerCase() || '';
  const category = document.getElementById('categoryFilter')?.value || 'all';
  const vendor = document.getElementById('vendorFilter')?.value || 'all';
  const sort = document.getElementById('sortBy')?.value || 'default';
  filteredProducts = products.filter(p => { const matchSearch = p.name.toLowerCase().includes(search); const matchCategory = category === 'all' || p.category === category; const matchVendor = vendor === 'all' || p.vendor === vendor; return matchSearch && matchCategory && matchVendor; });
  if (sort === 'price_asc') filteredProducts.sort((a, b) => a.price - b.price);
  else if (sort === 'price_desc') filteredProducts.sort((a, b) => b.price - a.price);
  else if (sort === 'rating') filteredProducts.sort((a, b) => a.rating - b.rating);
  currentPage = 1;
  loadProductsToGrid();
}
async function loadProductsToGrid() {
  const grid = document.getElementById('productsGrid');
  if (!grid) return;

  // Show Skeleton Loaders
  grid.innerHTML = Array(8).fill(0).map(() => `
    <div class="product-premium-card skeleton product-skeleton"></div>
  `).join('');

  const search = document.getElementById('globalSearch')?.value || '';
  const category = document.getElementById('categoryFilter')?.value || 'all';
  const vendor = document.getElementById('vendorFilter')?.value || 'all';
  const sort = document.getElementById('sortBy')?.value || 'default';

  const result = await apiCall('products', `get_products.php?search=${encodeURIComponent(search)}&category=${category}&vendor=${encodeURIComponent(vendor)}&sort=${sort}&page=${currentPage}`, 'GET');

  let productsToShow = [], totalPages = 1;
  if (result.success && result.data && result.data.products && result.data.products.length > 0) {
    productsToShow = result.data.products;
    totalPages = result.data.total_pages || 1;
  } else {
    const start = (currentPage - 1) * itemsPerPage;
    productsToShow = filteredProducts.slice(start, start + itemsPerPage);
    totalPages = Math.ceil(filteredProducts.length / itemsPerPage);
  }

  if (productsToShow.length === 0) {
    grid.innerHTML = '<div class="empty-cart-premium" style="grid-column:1/-1; color: var(--text-secondary);">No products found</div>';
    return;
  }

  grid.innerHTML = productsToShow.map((p, i) => `
    <div class="product-premium-card tilt-card reveal stagger-${(i % 4) + 1}">
      ${p.badge ? `<div class="product-badge ${p.badge}">${p.badge === 'hot' ? '🔥 Hot' : p.badge === 'new' ? '✨ New' : p.badge === 'sale' ? '💰 Sale' : '⭐ Best'}</div>` : ''}
      <img src="${p.image_url || p.image}" class="product-image" alt="${p.name}" loading="lazy" onerror="this.src='https://via.placeholder.com/400?text=No+Image'">
      <div class="product-info">
        <h3 class="product-title">${escapeHtml(p.name)}</h3>
        <p class="product-vendor"><i class="fas fa-store"></i> ${escapeHtml(p.vendor_name || p.vendor)}</p>
        <div class="product-rating">${generateStars(p.rating || 4.5)} (${p.reviews || 0})</div>
        <p class="product-price">PKR ${Number(p.price).toLocaleString()}</p>
        <button class="add-to-cart-premium" onclick="addToCart(${p.id})" ${cart.some(i => (i.product_id === p.id || i.id === p.id)) ? 'disabled' : ''}>
          ${cart.some(i => (i.product_id === p.id || i.id === p.id)) ? '✓ In Cart' : '🛒 Add to Cart'}
        </button>
      </div>
    </div>
  `).join('');

  renderPagination(totalPages);
  initAnimations();
  initTiltEffect();
}

function initTiltEffect() {
  document.querySelectorAll('.tilt-card').forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * 10;
      const rotateY = ((centerX - x) / centerX) * 10;
      card.style.transform = `translateY(-10px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = `translateY(0) rotateX(0) rotateY(0)`;
    });
  });
}

function loadFeaturedProducts() {
  const grid = document.getElementById('featuredProducts');
  if (!grid) return;
  const featured = products.filter(p => p.badge).slice(0, 8);
  grid.innerHTML = featured.map((p, i) => `<div class="product-premium-card reveal stagger-${(i % 4) + 1}">${p.badge ? `<div class="product-badge ${p.badge}">${p.badge === 'hot' ? '🔥 Hot' : p.badge === 'new' ? '✨ New' : p.badge === 'sale' ? '💰 Sale' : '⭐ Best'}</div>` : ''}<img src="${p.image}" class="product-image" alt="${p.name}" onerror="this.src='https://via.placeholder.com/400?text=No+Image'"><div class="product-info"><h3 class="product-title">${escapeHtml(p.name)}</h3><p class="product-price">PKR ${p.price.toLocaleString()}</p><button class="add-to-cart-premium" onclick="addToCart(${p.id})">Add to Cart</button></div></div>`).join('');
  initAnimations();
}
function renderHomeCategories() {
  const grid = document.getElementById('homeCategories');
  if (!grid) return;
  const categories = [...new Set(products.map(p => p.category))];
  const icons = { electronics: "fas fa-laptop", fashion: "fas fa-tshirt", footwear: "fas fa-shoe-prints", audio: "fas fa-headphones", appliances: "fas fa-blender", sports: "fas fa-bicycle" };
  grid.innerHTML = categories.map((cat, i) => `<div class="category-premium-card reveal stagger-${(i % 4) + 1}" onclick="filterByCategory('${cat}')"><i class="${icons[cat] || 'fas fa-tag'}"></i><h3>${cat.charAt(0).toUpperCase() + cat.slice(1)}</h3><p>${products.filter(p => p.category === cat).length} products</p></div>`).join('');
}
function generateStars(rating) { let stars = ''; for (let i = 1; i <= 5; i++) stars += i <= rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'; return stars; }
function renderPagination(totalPages) {
  const container = document.getElementById('pagination');
  if (!container) return;
  if (totalPages <= 1) { container.innerHTML = ''; return; }
  let html = `<button class="page-btn-premium" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>«</button>`;
  for (let i = 1; i <= totalPages; i++) { if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) html += `<button class="page-btn-premium ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`; else if (i === currentPage - 3 || i === currentPage + 3) html += `<span style="color: var(--text-muted);">...</span>`; }
  html += `<button class="page-btn-premium" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>»</button>`;
  container.innerHTML = html;
}
function changePage(page) { if (page < 1) return; currentPage = page; loadProductsToGrid(); window.scrollTo({ top: 0, behavior: 'smooth' }); }
function escapeHtml(text) { if (!text) return ''; const div = document.createElement('div'); div.textContent = text; return div.innerHTML; }

// ============== CART ==============
async function addToCart(productId) {
  if (!currentUser) { showToast('Please login first', 'error'); openAuthModal('login'); return; }
  const result = await apiCall('cart', 'add_to_cart.php', 'POST', { product_id: productId });
  if (result.success) { await loadCart(); showToast('Added to cart!', 'success'); loadProductsToGrid(); } else { showToast(result.message, 'error'); }
}
function updateCartCount() { const total = cart.reduce((sum, i) => sum + (i.quantity || 1), 0); const cartCountElem = document.getElementById('cartCount'); if (cartCountElem) cartCountElem.innerText = total; }
function openCartModal() { renderCartModal(); const modal = document.getElementById('cartModal'); if (modal) modal.classList.add('active'); }
function closeCartModal() { const modal = document.getElementById('cartModal'); if (modal) modal.classList.remove('active'); }
function renderCartModal() {
  const container = document.getElementById('cartItemsList'); const emptyDiv = document.getElementById('emptyCart'); const footer = document.getElementById('cartFooterPremium');
  if (!cart || cart.length === 0) { if (emptyDiv) emptyDiv.classList.remove('hidden'); if (footer) footer.classList.add('hidden'); if (container) container.innerHTML = ''; return; }
  if (emptyDiv) emptyDiv.classList.add('hidden'); if (footer) footer.classList.remove('hidden');
  let subtotal = 0;
  container.innerHTML = cart.map(item => { const itemPrice = item.price || 0; const itemQuantity = item.quantity || 1; subtotal += itemPrice * itemQuantity; return `<div class="cart-item-premium"><img src="${item.image_url || item.image}" class="cart-item-img" onerror="this.src='https://via.placeholder.com/70?text=No+Image'"><div class="cart-item-details"><div class="cart-item-title">${escapeHtml(item.name)}</div><div class="cart-item-price">PKR ${itemPrice.toLocaleString()}</div><div class="cart-item-quantity"><button class="qty-btn" onclick="updateQuantity(${item.product_id || item.id}, ${itemQuantity - 1})">-</button><span>${itemQuantity}</span><button class="qty-btn" onclick="updateQuantity(${item.product_id || item.id}, ${itemQuantity + 1})">+</button></div></div><button onclick="removeFromCart(${item.product_id || item.id})" style="background:none; border:none; color:#ef4444; cursor:pointer;"><i class="fas fa-trash"></i></button></div>`; }).join('');
  const shipping = 200; const total = subtotal + shipping;
  const cartSubtotalElem = document.getElementById('cartSubtotal'); const cartTotalElem = document.getElementById('cartTotal');
  if (cartSubtotalElem) cartSubtotalElem.innerText = `PKR ${subtotal.toLocaleString()}`;
  if (cartTotalElem) cartTotalElem.innerText = `PKR ${total.toLocaleString()}`;
}
async function updateQuantity(productId, quantity) {
  if (!currentUser) { showToast('Please login first', 'error'); return; }
  if (quantity <= 0) await removeFromCart(productId);
  else { const result = await apiCall('cart', 'update_cart.php', 'POST', { product_id: productId, quantity: quantity }); if (result.success) { await loadCart(); renderCartModal(); } else showToast(result.message, 'error'); }
}
async function removeFromCart(productId) {
  if (!currentUser) { showToast('Please login first', 'error'); return; }
  const result = await apiCall('cart', 'remove_from_cart.php', 'POST', { product_id: productId });
  if (result.success) { await loadCart(); renderCartModal(); loadProductsToGrid(); showToast('Item removed', 'success'); } else showToast(result.message, 'error');
}
async function checkout() {
  if (!currentUser) { showToast('Please login first', 'error'); openAuthModal('login'); return; }
  if (cart.length === 0) { showToast('Cart is empty', 'error'); return; }
  const shipping_address = prompt('Enter your shipping address:'); if (!shipping_address) return;
  const payment_method = prompt('Payment method (cod/card):', 'cod');
  const result = await apiCall('orders', 'place_order.php', 'POST', { shipping_address, payment_method });
  if (result.success) { showToast('Order placed successfully! 🎉', 'success'); await loadCart(); closeCartModal(); loadProductsToGrid(); } else showToast(result.message, 'error');
}

// ============== AUTHENTICATION ==============
function openAuthModal(type) { const modal = document.getElementById('authModal'); if (modal) modal.classList.add('active'); if (type === 'signup') showSignupForm(); else showLoginForm(); }
function closeAuthModal() { const modal = document.getElementById('authModal'); if (modal) modal.classList.remove('active'); }
function showLoginForm() { const loginForm = document.getElementById('loginForm'); const signupForm = document.getElementById('signupForm'); const title = document.getElementById('authModalTitle'); if (loginForm) loginForm.classList.remove('hidden'); if (signupForm) signupForm.classList.add('hidden'); if (title) title.innerText = 'Welcome Back'; }
function showSignupForm() { const loginForm = document.getElementById('loginForm'); const signupForm = document.getElementById('signupForm'); const title = document.getElementById('authModalTitle'); if (loginForm) loginForm.classList.add('hidden'); if (signupForm) signupForm.classList.remove('hidden'); if (title) title.innerText = 'Create Account'; }

async function loginUser() {
  const email = document.getElementById('loginEmail')?.value.trim();
  const password = document.getElementById('loginPassword')?.value;

  if (!email || !password) {
    showToast('Please enter email and password', 'error');
    return;
  }

  const result = await apiCall('auth', 'login.php', 'POST', { email, password });

  if (result.success) {
    currentUser = result.data;
    localStorage.setItem('furqan_current_user', JSON.stringify(currentUser));
    updateUIForUser();
    closeAuthModal();
    await loadCart();
    showToast(`Welcome ${currentUser.full_name}!`, 'success');

    document.getElementById('loginEmail').value = '';
    document.getElementById('loginPassword').value = '';

    if ((currentUser.role === 'super_admin' || currentUser.role === 'admin') && confirm('Redirect to Admin Dashboard?')) {
      window.location.href = 'admin/dashboard.php';
    } else if (currentUser.role === 'vendor' && confirm('Redirect to Vendor Dashboard?')) {
      window.location.href = 'vendor/dashboard.php';
    }
    loadProductsToGrid();
  } else {
    showToast(result.message, 'error');
  }
}

async function signupUser() {
  const full_name = document.getElementById('signupName')?.value.trim();
  const email = document.getElementById('signupEmail')?.value.trim();
  const phone = document.getElementById('signupPhone')?.value.trim();
  const password = document.getElementById('signupPassword')?.value;
  const confirm = document.getElementById('signupConfirm')?.value;
  const role = document.getElementById('signupRole')?.value;

  if (!full_name || !email || !phone || !password) {
    showToast('Fill all fields', 'error');
    return;
  }

  if (password.length < 6) {
    showToast('Password must be at least 6 characters', 'error');
    return;
  }

  if (password !== confirm) {
    showToast('Passwords do not match', 'error');
    return;
  }

  const result = await apiCall('auth', 'signup.php', 'POST', {
    full_name: full_name,
    email: email,
    phone: phone,
    password: password,
    role: role
  });

  if (result.success) {
    showToast(result.message, 'success');
    showLoginForm();
    document.getElementById('signupName').value = '';
    document.getElementById('signupEmail').value = '';
    document.getElementById('signupPhone').value = '';
    document.getElementById('signupPassword').value = '';
    document.getElementById('signupConfirm').value = '';
  } else {
    showToast(result.message, 'error');
  }
}

async function logout() {
  await apiCall('auth', 'logout.php', 'POST');
  currentUser = null;
  localStorage.removeItem('furqan_current_user');
  cart = [];
  localStorage.removeItem('furqan_cart');
  updateUIForUser();
  updateCartCount();
  loadProductsToGrid();
  showToast('Logged out successfully!', 'info');
}

function updateUIForUser() {
  const sidebarUserName = document.getElementById('sidebarUserName');
  const sidebarUserRole = document.getElementById('sidebarUserRole');
  const dropdownUserName = document.getElementById('dropdownUserName');
  const dropdownUserRole = document.getElementById('dropdownUserRole');
  const sidebarAuthBtn = document.getElementById('sidebarAuthBtn');

  if (currentUser) {
    if (sidebarUserName) sidebarUserName.innerText = currentUser.full_name;
    if (sidebarUserRole) sidebarUserRole.innerText = currentUser.role === 'super_admin' ? '👑 Super Admin' : currentUser.role === 'vendor' ? '🏪 Vendor' : '👤 Customer';
    if (dropdownUserName) dropdownUserName.innerText = currentUser.full_name;
    if (dropdownUserRole) dropdownUserRole.innerText = currentUser.role === 'super_admin' ? 'Super Admin' : currentUser.role === 'vendor' ? 'Vendor Account' : 'Customer Account';
    if (sidebarAuthBtn) { sidebarAuthBtn.innerHTML = `<i class="fas fa-sign-out-alt"></i><span>Logout</span>`; sidebarAuthBtn.onclick = logout; }
  } else {
    if (sidebarUserName) sidebarUserName.innerText = 'Guest User';
    if (sidebarUserRole) sidebarUserRole.innerText = 'Welcome! Login to shop';
    if (dropdownUserName) dropdownUserName.innerText = 'Guest';
    if (dropdownUserRole) dropdownUserRole.innerText = 'Not logged in';
    if (sidebarAuthBtn) { sidebarAuthBtn.innerHTML = `<i class="fas fa-user-astronaut"></i><span>Login / Register</span>`; sidebarAuthBtn.onclick = () => openAuthModal('login'); }
  }
}

function toggleUserMenu() { const dropdown = document.getElementById('userMenuDropdown'); if (dropdown) dropdown.classList.toggle('show'); }
document.addEventListener('click', (e) => { if (!e.target.closest('.user-menu-premium')) { const dropdown = document.getElementById('userMenuDropdown'); if (dropdown) dropdown.classList.remove('show'); } });

// ============== CONTACT FORM ==============
async function sendMessage(event) {
  event.preventDefault();
  const name = document.getElementById('contactName')?.value;
  const email = document.getElementById('contactEmail')?.value;
  const subject = document.getElementById('contactSubject')?.value;
  const message = document.getElementById('contactMessage')?.value;

  if (!name || !email || !message) {
    showToast('Please fill required fields', 'error');
    return;
  }

  const result = await apiCall('contact', 'send_message.php', 'POST', { name, email, subject, message });

  if (result.success) {
    const successDiv = document.getElementById('contactSuccess');
    if (successDiv) successDiv.classList.remove('hidden');
    const form = document.getElementById('contactForm');
    if (form) form.reset();
    setTimeout(() => { if (successDiv) successDiv.classList.add('hidden'); }, 5000);
    showToast('Message sent successfully!', 'success');
  } else {
    showToast(result.message, 'error');
  }
}

// ============== TOAST NOTIFICATION ==============
function showToast(message, type = 'success') {
  const toast = document.getElementById('toast');
  if (!toast) return;
  const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle';
  toast.innerHTML = `<i class="fas ${icon}"></i> ${message}`;
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

// ============== SCROLL ANIMATIONS ==============
function initAnimations() {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('active');
      }
    });
  }, observerOptions);

  document.querySelectorAll('.reveal, .reveal-left').forEach(el => {
    observer.observe(el);
  });
}

// ============== SUPPORT ARTICLES ==============
const supportArticles = {
  tracking: {
    title: "Order Tracking",
    icon: "fas fa-box-open",
    content: `
      <h3>How to track your order?</h3>
      <p>Once your order is shipped, you will receive an email with a tracking number and a link to the courier's website. You can also track your order directly from your dashboard.</p>
      <div class="article-steps" style="margin-top: 1.5rem;">
        <div style="margin-bottom: 1rem;"><strong>Step 1:</strong> Log in to your FurqanStore account.</div>
        <div style="margin-bottom: 1rem;"><strong>Step 2:</strong> Go to 'My Orders' in your profile menu.</div>
        <div style="margin-bottom: 1rem;"><strong>Step 3:</strong> Click on the 'Track' button next to your active order.</div>
      </div>
      <p style="margin-top: 1rem;">Standard delivery usually takes 2-4 business days for domestic orders and 7-14 days for international orders.</p>
    `
  },
  shipping: {
    title: "Shipping & Delivery",
    icon: "fas fa-truck",
    content: `
      <h3>Global Luxury Shipping</h3>
      <p>We partner with premium couriers like DHL, FedEx, and UPS to ensure your luxury items reach you safely and promptly.</p>
      <h4 style="margin-top: 1.5rem; color: var(--primary);">Shipping Rates</h4>
      <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
        <li><strong>Standard:</strong> PKR 200 (Free for orders over PKR 5,000)</li>
        <li><strong>Express:</strong> PKR 500 (1-2 business days)</li>
        <li><strong>International:</strong> Calculated at checkout based on weight and destination.</li>
      </ul>
      <p style="margin-top: 1rem;">All shipments are fully insured against loss or damage during transit.</p>
    `
  },
  returns: {
    title: "Returns & Refunds",
    icon: "fas fa-undo",
    content: `
      <h3>Complimentary Return Policy</h3>
      <p>We want you to be completely satisfied with your purchase. If for any reason you are not happy, you may return the item within 30 days of delivery.</p>
      <h4 style="margin-top: 1.5rem; color: var(--primary);">Eligibility Criteria</h4>
      <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
        <li>Items must be in original condition with all tags attached.</li>
        <li>Packaging must be intact for electronics and beauty products.</li>
        <li>Proof of purchase is required for all returns.</li>
      </ul>
      <p style="margin-top: 1rem;">Refunds will be processed back to your original payment method within 5-7 business days after we receive the returned item.</p>
    `
  },
  security: {
    title: "Payment Security",
    icon: "fas fa-shield-alt",
    content: `
      <h3>Secure Premium Transactions</h3>
      <p>Your security is our top priority. FurqanStore uses industry-standard 256-bit SSL encryption to protect your data during every transaction.</p>
      <h4 style="margin-top: 1.5rem; color: var(--primary);">Accepted Payment Methods</h4>
      <p>We accept all major credit/debit cards (Visa, Mastercard, Amex), JazzCash, EasyPaisa, and Cash on Delivery for domestic orders.</p>
      <p style="margin-top: 1rem;"><strong>Fraud Protection:</strong> Our systems monitor every transaction for suspicious activity to ensure your account remains safe.</p>
    `
  }
};

function openSupportArticle(type) {
  const article = supportArticles[type];
  if (!article) return;

  const modal = document.getElementById('supportArticleModal');
  const title = document.getElementById('supportArticleTitle');
  const content = document.getElementById('supportArticleContent');

  if (modal && title && content) {
    title.innerHTML = `<i class="${article.icon}"></i> ${article.title}`;
    content.innerHTML = article.content;
    modal.classList.add('active');
  }
}

function closeSupportArticleModal() {
  const modal = document.getElementById('supportArticleModal');
  if (modal) modal.classList.remove('active');
}

// ============== EXPORT FUNCTIONS ==============
window.toggleTheme = toggleTheme;
window.showPage = showPage;
window.filterProducts = filterProducts;
window.filterByCategory = filterByCategory;
window.addToCart = addToCart;
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;
window.openCartModal = openCartModal;
window.closeCartModal = closeCartModal;
window.openAuthModal = openAuthModal;
window.closeAuthModal = closeAuthModal;
window.loginUser = loginUser;
window.signupUser = signupUser;
window.logout = logout;
window.showLoginForm = showLoginForm;
window.showSignupForm = showSignupForm;
window.toggleUserMenu = toggleUserMenu;
window.sendMessage = sendMessage;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.checkout = checkout;
window.changePage = changePage;
window.loadProductsToGrid = loadProductsToGrid;
window.openSupportArticle = openSupportArticle;
window.closeSupportArticleModal = closeSupportArticleModal;
