// assets/js/main.js
const API_BASE = window.location.origin + '/furqan-store/api/';
let currentPage = 1;
let productsPerPage = 12;
let totalPages = 1;
let currentUser = null;
let currentSlide = 0;
let slideInterval;

document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    loadProducts();
    loadCart();
    startSlideShow();
    
    document.getElementById('menuBtn').addEventListener('click', () => {
        document.getElementById('navLinks').classList.toggle('show');
    });
    
    document.getElementById('searchInput').addEventListener('input', () => {
        currentPage = 1;
        loadProducts();
    });
    
    document.getElementById('categoryFilter').addEventListener('change', () => {
        currentPage = 1;
        loadProducts();
    });
});

// Check authentication
async function checkAuth() {
    try {
        const response = await fetch(`${API_BASE}auth.php?action=check`);
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            updateUIForLoggedInUser();
            showToast(`Welcome back, ${currentUser.full_name}! 🎉`, 'success');
        }
    } catch (error) {
        console.error('Auth check failed:', error);
    }
}

function updateUIForLoggedInUser() {
    const authLinks = document.getElementById('authLinks');
    authLinks.innerHTML = `
        <div class="user-menu">
            <i class="fas fa-user-circle"></i>
            <span>${currentUser.full_name}</span>
            <a href="#" onclick="logout()">Logout</a>
            ${currentUser.role === 'admin' ? '<a href="/furqan-store/admin/" target="_blank">Admin Panel</a>' : ''}
        </div>
    `;
}

async function logout() {
    try {
        await fetch(`${API_BASE}auth.php?action=logout`);
        currentUser = null;
        showToast('Logged out successfully! 👋', 'success');
        setTimeout(() => location.reload(), 1000);
    } catch (error) {
        showToast('Logout failed', 'error');
    }
}

// Handle Login
async function handleLogin(event) {
    event.preventDefault();
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    
    try {
        const response = await fetch(`${API_BASE}auth.php?action=login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            closeLoginModal();
            updateUIForLoggedInUser();
            loadCart();
            showToast(`✅ Login successful! Welcome back, ${currentUser.full_name}!`, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Login failed. Please try again.', 'error');
    }
}

// Handle Signup
async function handleSignup(event) {
    event.preventDefault();
    const fullName = document.getElementById('signupName').value;
    const email = document.getElementById('signupEmail').value;
    const phone = document.getElementById('signupPhone').value;
    const password = document.getElementById('signupPassword').value;
    const confirmPassword = document.getElementById('signupConfirmPassword').value;
    
    if (password !== confirmPassword) {
        showToast('Passwords do not match!', 'error');
        return;
    }
    
    if (password.length < 6) {
        showToast('Password must be at least 6 characters!', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}auth.php?action=register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ full_name: fullName, email, phone, password })
        });
        
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            closeSignupModal();
            updateUIForLoggedInUser();
            showToast(`🎉 Registration successful! Welcome to Furqan Store, ${fullName}!`, 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Registration failed. Please try again.', 'error');
    }
}

// Load Products
async function loadProducts() {
    const searchTerm = document.getElementById('searchInput').value;
    const category = document.getElementById('categoryFilter').value;
    
    try {
        const response = await fetch(`${API_BASE}products.php?page=${currentPage}&search=${encodeURIComponent(searchTerm)}&category=${category}&limit=${productsPerPage}`);
        const data = await response.json();
        
        if (data.success) {
            renderProducts(data.data.products);
            totalPages = data.data.totalPages;
            renderPagination();
        }
    } catch (error) {
        showToast('Failed to load products', 'error');
    }
}

function renderProducts(products) {
    const grid = document.getElementById('productGrid');
    grid.innerHTML = '';
    
    if (products.length === 0) {
        grid.innerHTML = '<div class="empty-state"><i class="fas fa-box-open"></i><h3>No products found</h3></div>';
        return;
    }
    
    products.forEach(product => {
        const badgeHtml = product.badge ? `<span class="badge ${product.badge}">${getBadgeText(product.badge)}</span>` : '';
        const inCart = currentUser ? cartItems.some(item => item.product_id === product.id) : false;
        
        grid.innerHTML += `
            <div class="product-card">
                ${badgeHtml}
                <img src="${product.image}" alt="${product.name}" class="product-image">
                <div class="product-info">
                    <span class="product-category">${product.category}</span>
                    <h3 class="product-name">${product.name}</h3>
                    <div class="product-rating">${generateStars(product.rating)}<span>(${product.reviews})</span></div>
                    <p class="product-price">PKR ${Number(product.price).toLocaleString()}</p>
                    <button class="add-to-cart" onclick="addToCart(${product.id})" ${inCart ? 'disabled' : ''}>
                        <i class="fas fa-cart-plus"></i> ${inCart ? 'Added to Cart' : 'Add to Cart'}
                    </button>
                </div>
            </div>
        `;
    });
}

function getBadgeText(badge) {
    const badges = { hot: '🔥 Hot', new: '✨ New', sale: '💰 Sale', best: '⭐ Best' };
    return badges[badge] || badge;
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        stars += i <= Math.floor(rating) ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
    }
    return stars;
}

function renderPagination() {
    const pagination = document.getElementById('pagination');
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }
    
    let html = `<button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>❮</button>`;
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">${i}</button>`;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            html += `<span>...</span>`;
        }
    }
    html += `<button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>❯</button>`;
    pagination.innerHTML = html;
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Cart Management
let cartItems = [];

async function loadCart() {
    if (!currentUser) {
        document.getElementById('cartCount').innerText = '0';
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}cart.php`);
        const data = await response.json();
        if (data.success) {
            cartItems = data.data.items || [];
            const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').innerText = totalItems;
        }
    } catch (error) {
        console.error('Failed to load cart:', error);
    }
}

async function addToCart(productId) {
    if (!currentUser) {
        showToast('Please login to add items to cart', 'error');
        openAuthModal('login');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}cart.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        if (data.success) {
            await loadCart();
            await loadProducts();
            showToast('✨ Product added to cart!', 'success');
        }
    } catch (error) {
        showToast('Failed to add to cart', 'error');
    }
}

// Modal Functions
function openAuthModal(type) {
    if (type === 'login') openLoginModal();
    else openSignupModal();
}

function openLoginModal() {
    document.getElementById('loginModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeLoginModal() {
    document.getElementById('loginModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('loginForm').reset();
}

function openSignupModal() {
    document.getElementById('signupModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSignupModal() {
    document.getElementById('signupModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('signupForm').reset();
}

function switchToSignup() {
    closeLoginModal();
    openSignupModal();
}

function switchToLogin() {
    closeSignupModal();
    openLoginModal();
}

function openCartModal() {
    document.getElementById('cartModal').classList.add('active');
    renderCartItems();
    document.body.style.overflow = 'hidden';
}

function closeCartModal() {
    document.getElementById('cartModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function renderCartItems() {
    const container = document.getElementById('cartItems');
    const emptyDiv = document.getElementById('cartEmpty');
    const footer = document.getElementById('cartFooter');
    
    if (!cartItems.length) {
        container.innerHTML = '';
        emptyDiv.classList.remove('hidden');
        footer.classList.add('hidden');
        return;
    }
    
    emptyDiv.classList.add('hidden');
    footer.classList.remove('hidden');
    
    let total = 0;
    container.innerHTML = cartItems.map(item => {
        total += item.price * item.quantity;
        return `
            <div class="cart-item">
                <img src="${item.image}" class="cart-item-img">
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">PKR ${Number(item.price).toLocaleString()}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">+</button>
                </div>
                <button onclick="removeFromCart(${item.id})" style="background:none; border:none; color:var(--danger); cursor:pointer;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    }).join('');
    
    document.getElementById('cartTotalPrice').innerHTML = `PKR ${total.toLocaleString()}`;
}

async function updateQuantity(cartId, quantity) {
    if (quantity <= 0) {
        await removeFromCart(cartId);
        return;
    }
    
    try {
        await fetch(`${API_BASE}cart.php`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cart_id: cartId, quantity })
        });
        await loadCart();
        renderCartItems();
    } catch (error) {
        showToast('Failed to update cart', 'error');
    }
}

async function removeFromCart(cartId) {
    try {
        await fetch(`${API_BASE}cart.php?id=${cartId}`, { method: 'DELETE' });
        await loadCart();
        await loadProducts();
        renderCartItems();
        showToast('Item removed from cart', 'success');
    } catch (error) {
        showToast('Failed to remove item', 'error');
    }
}

async function checkout() {
    if (!currentUser) {
        showToast('Please login to checkout', 'error');
        openAuthModal('login');
        return;
    }
    
    const name = prompt('Enter your full name for delivery:');
    const address = prompt('Enter your shipping address:');
    const phone = prompt('Enter your phone number:');
    
    if (!name || !address || !phone) {
        showToast('Please provide all delivery details', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}orders.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, address, phone })
        });
        
        const data = await response.json();
        if (data.success) {
            showToast('🎉 Order placed successfully! Thank you for shopping!', 'success');
            closeCartModal();
            await loadCart();
            await loadProducts();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast('Checkout failed', 'error');
    }
}

// Slider Functions
function startSlideShow() {
    slideInterval = setInterval(nextSlide, 5000);
}

function nextSlide() {
    const slides = document.querySelectorAll('.slide');
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
}

function prevSlide() {
    const slides = document.querySelectorAll('.slide');
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
}

function showSlide(index) {
    document.querySelectorAll('.slide').forEach((slide, i) => {
        slide.classList.toggle('active', i === index);
    });
}

function showSection(section) {
    document.getElementById(section).scrollIntoView({ behavior: 'smooth' });
    document.getElementById('navLinks').classList.remove('show');
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const icon = toast.querySelector('i');
    const text = toast.querySelector('span');
    
    text.innerText = message;
    icon.className = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
    icon.style.color = type === 'success' ? 'var(--accent)' : 'var(--danger)';
    
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Export to window
window.showSection = showSection;
window.changePage = changePage;
window.addToCart = addToCart;
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;
window.openCartModal = openCartModal;
window.closeCartModal = closeCartModal;
window.openAuthModal = openAuthModal;
window.closeLoginModal = closeLoginModal;
window.closeSignupModal = closeSignupModal;
window.switchToSignup = switchToSignup;
window.switchToLogin = switchToLogin;
window.handleLogin = handleLogin;
window.handleSignup = handleSignup;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.checkout = checkout;
window.logout = logout;
