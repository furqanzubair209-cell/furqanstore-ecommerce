// assets/js/main.js

// ============== CONFIGURATION ==============
const API_BASE = window.location.origin + '/furqan-store/api/';

// ============== APP STATE ==============
let currentPage = 1;
let productsPerPage = 12;
let currentUser = null;
let totalPages = 1;
let currentSlide = 0;
let slideInterval;

// ============== INITIALIZATION ==============
document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    loadProducts();
    loadCart();
    startSlideShow();
    
    // Mobile menu toggle
    document.getElementById('menuBtn').addEventListener('click', () => {
        document.getElementById('navLinks').classList.toggle('show');
    });

    // Close mobile menu on click outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.nav-links') && !e.target.closest('.menu-btn')) {
            document.getElementById('navLinks').classList.remove('show');
        }
    });
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('input', () => {
        currentPage = 1;
        loadProducts();
    });
    
    document.getElementById('categoryFilter').addEventListener('change', () => {
        currentPage = 1;
        loadProducts();
    });
});

// ============== AUTHENTICATION ==============
async function checkAuth() {
    try {
        const response = await fetch(`${API_BASE}auth.php?action=check`);
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            updateUIForLoggedInUser();
        }
    } catch (error) {
        console.error('Auth check failed:', error);
    }
}

function updateUIForLoggedInUser() {
    const navLinks = document.getElementById('navLinks');
    // Remove login/signup links
    const authLinks = navLinks.querySelectorAll('a[onclick*="openAuthModal"]');
    authLinks.forEach(link => {
        link.parentElement.remove();
    });
    
    // Add user menu
    const userMenuItem = document.createElement('li');
    userMenuItem.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user-circle"></i>
            <span style="color: white;">${currentUser.full_name}</span>
            <a href="#" onclick="logout()" style="color: var(--secondary);">Logout</a>
        </div>
    `;
    navLinks.appendChild(userMenuItem);
}

async function login(email, password) {
    try {
        const response = await fetch(`${API_BASE}auth.php?action=login`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password })
        });
        
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            updateUIForLoggedInUser();
            closeAuthModal();
            loadCart();
            showToast('Login successful!', 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error('Login failed:', error);
        showToast('Login failed', 'error');
    }
}

async function register(email, password, fullName, phone) {
    try {
        const response = await fetch(`${API_BASE}auth.php?action=register`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email, password, full_name: fullName, phone })
        });
        
        const data = await response.json();
        if (data.success) {
            currentUser = data.data;
            updateUIForLoggedInUser();
            closeAuthModal();
            showToast('Registration successful!', 'success');
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error('Registration failed:', error);
        showToast('Registration failed', 'error');
    }
}

async function logout() {
    try {
        const response = await fetch(`${API_BASE}auth.php?action=logout`);
        const data = await response.json();
        if (data.success) {
            currentUser = null;
            location.reload();
        }
    } catch (error) {
        console.error('Logout failed:', error);
    }
}

// ============== PRODUCTS ==============
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
        } else {
            showToast('Failed to load products', 'error');
        }
    } catch (error) {
        console.error('Failed to load products:', error);
        showToast('Failed to load products', 'error');
    }
}

function renderProducts(products) {
    const grid = document.getElementById('productGrid');
    grid.innerHTML = '';

    if (products.length === 0) {
        grid.innerHTML = `
            <div class="empty-state" style="grid-column: 1/-1;">
                <i class="fas fa-box-open"></i>
                <h3>No products found</h3>
                <p>Try adjusting your search or filter</p>
            </div>
        `;
        return;
    }

    products.forEach(product => {
        const badgeHtml = product.badge ? 
            `<span class="badge ${product.badge}">${product.badge === 'hot' ? '🔥 Hot' : product.badge === 'new' ? '✨ New' : product.badge === 'sale' ? '💰 Sale' : '⭐ Best'}</span>` : '';
        
        grid.innerHTML += `
            <div class="product-card">
                ${badgeHtml}
                <img src="${product.image}" alt="${product.name}" class="product-image" loading="lazy">
                <div class="product-info">
                    <span class="product-category">${product.category}</span>
                    <h3 class="product-name">${product.name}</h3>
                    <div class="product-rating">
                        ${generateStars(product.rating)}
                        <span>(${product.reviews})</span>
                    </div>
                    <p class="product-price">PKR ${Number(product.price).toLocaleString()}</p>
                    <button class="add-to-cart" onclick="addToCart(${product.id})">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
        `;
    });
}

function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
            stars += '<i class="fas fa-star"></i>';
        } else if (i === Math.ceil(rating) && !Number.isInteger(rating)) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        } else {
            stars += '<i class="far fa-star"></i>';
        }
    }
    return stars;
}

function renderPagination() {
    const pagination = document.getElementById('pagination');
    
    if (totalPages <= 1) {
        pagination.innerHTML = '';
        return;
    }

    let paginationHtml = `
        <button class="page-btn" onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''}>
            <i class="fas fa-chevron-left"></i>
        </button>
    `;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
            paginationHtml += `
                <button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        } else if (i === currentPage - 3 || i === currentPage + 3) {
            paginationHtml += `<span style="color: var(--gray);">...</span>`;
        }
    }

    paginationHtml += `
        <button class="page-btn" onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''}>
            <i class="fas fa-chevron-right"></i>
        </button>
    `;

    pagination.innerHTML = paginationHtml;
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadProducts();
    window.scrollTo({ top: document.getElementById('products').offsetTop - 100, behavior: 'smooth' });
}

// ============== CART MANAGEMENT ==============
async function addToCart(productId) {
    if (!currentUser) {
        showToast('Please login to add items to cart', 'error');
        openAuthModal('login');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}cart.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        if (data.success) {
            showToast('Added to cart!', 'success');
            loadCart();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error('Failed to add to cart:', error);
        showToast('Failed to add to cart', 'error');
    }
}

async function loadCart() {
    if (!currentUser) {
        document.getElementById('cartCount').innerText = '0';
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}cart.php`);
        const data = await response.json();
        if (data.success) {
            const totalItems = data.data.total_items || 0;
            document.getElementById('cartCount').innerText = totalItems;
            if (document.getElementById('cartModal').classList.contains('active')) {
                renderCartItems(data.data.items);
            }
        }
    } catch (error) {
        console.error('Failed to load cart:', error);
    }
}

async function updateCartQuantity(cartId, quantity) {
    try {
        const response = await fetch(`${API_BASE}cart.php`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cart_id: cartId, quantity: quantity })
        });
        
        const data = await response.json();
        if (data.success) {
            loadCart();
        }
    } catch (error) {
        console.error('Failed to update cart:', error);
    }
}

async function removeFromCart(cartId) {
    try {
        const response = await fetch(`${API_BASE}cart.php?id=${cartId}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        if (data.success) {
            loadCart();
            showToast('Item removed', 'success');
        }
    } catch (error) {
        console.error('Failed to remove from cart:', error);
    }
}

function renderCartItems(cartItems) {
    const cartItemsDiv = document.getElementById('cartItems');
    const cartEmptyDiv = document.getElementById('cartEmpty');
    const cartFooterDiv = document.getElementById('cartFooter');

    if (!cartItems || cartItems.length === 0) {
        cartItemsDiv.innerHTML = '';
        cartEmptyDiv.classList.remove('hidden');
        cartFooterDiv.classList.add('hidden');
        return;
    }
    
    cartEmptyDiv.classList.add('hidden');
    cartFooterDiv.classList.remove('hidden');
    
    let total = 0;
    cartItemsDiv.innerHTML = cartItems.map(item => {
        total += item.price * item.quantity;
        return `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}" class="cart-item-img">
                <div class="cart-item-info">
                    <div class="cart-item-title">${item.name}</div>
                    <div class="cart-item-price">PKR ${Number(item.price).toLocaleString()}</div>
                </div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity - 1})">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span style="font-weight: 600; min-width: 30px; text-align: center;">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, ${item.quantity + 1})">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <button onclick="removeFromCart(${item.id})" style="background: none; border: none; color: var(--danger); cursor: pointer;">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    }).join('');
    
    document.getElementById('cartTotalPrice').innerHTML = `PKR ${total.toLocaleString()}`;
}

async function checkout() {
    if (!currentUser) {
        showToast('Please login to checkout', 'error');
        openAuthModal('login');
        return;
    }
    
    const address = prompt('Enter shipping address:');
    const phone = prompt('Enter phone number:');
    
    if (!address || !phone) {
        showToast('Please provide shipping details', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE}orders.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ address, phone })
        });
        
        const data = await response.json();
        if (data.success) {
            showToast('Order placed successfully!', 'success');
            closeCartModal();
            loadCart();
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error('Checkout failed:', error);
        showToast('Checkout failed', 'error');
    }
}

// ============== MODAL FUNCTIONS ==============
function openCartModal() {
    const modal = document.getElementById('cartModal');
    modal.classList.add('active');
    loadCart();
    document.body.style.overflow = 'hidden';
}

function closeCartModal() {
    document.getElementById('cartModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openAuthModal(mode) {
    currentAuthMode = mode;
    const modal = document.getElementById('authModal');
    const title = document.getElementById('authTitle');
    const buttonText = document.getElementById('authButtonText');
    
    title.innerText = mode === 'login' ? 'Login' : 'Sign Up';
    buttonText.innerText = mode === 'login' ? 'Login' : 'Register';
    
    document.getElementById('toggleAuth').innerHTML = mode === 'login'
        ? `Don't have an account? <span onclick="toggleAuthMode()" style="color: var(--secondary); font-weight: 600; cursor: pointer;">Sign Up</span>`
        : `Already have an account? <span onclick="toggleAuthMode()" style="color: var(--secondary); font-weight: 600; cursor: pointer;">Login</span>`;
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeAuthModal() {
    document.getElementById('authModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('authEmail').value = '';
    document.getElementById('authPassword').value = '';
}

function toggleAuthMode() {
    currentAuthMode = currentAuthMode === 'login' ? 'signup' : 'login';
    openAuthModal(currentAuthMode);
}

function handleAuth() {
    const email = document.getElementById('authEmail').value.trim();
    const password = document.getElementById('authPassword').value.trim();

    if (!email || !password) {
        showToast('Please fill all fields', 'error');
        return;
    }

    if (!email.includes('@') || !email.includes('.')) {
        showToast('Please enter a valid email', 'error');
        return;
    }

    if (password.length < 6) {
        showToast('Password must be at least 6 characters', 'error');
        return;
    }

    if (currentAuthMode === 'login') {
        login(email, password);
    } else {
        const fullName = prompt('Enter your full name:');
        const phone = prompt('Enter your phone number:');
        if (!fullName || !phone) {
            showToast('Please provide all details', 'error');
            return;
        }
        register(email, password, fullName, phone);
    }
}

// ============== SLIDER ==============
function startSlideShow() {
    slideInterval = setInterval(nextSlide, 5000);
}

function stopSlideShow() {
    clearInterval(slideInterval);
}

function showSlide(index) {
    const slides = document.querySelectorAll('.slide');
    slides.forEach(slide => slide.classList.remove('active'));
    slides[index].classList.add('active');
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

// ============== TOAST NOTIFICATION ==============
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const icon = toast.querySelector('i');
    
    toastMessage.innerText = message;
    
    if (type === 'success') {
        icon.style.color = 'var(--accent)';
        icon.className = 'fas fa-check-circle';
    } else if (type === 'error') {
        icon.style.color = 'var(--danger)';
        icon.className = 'fas fa-exclamation-circle';
    } else {
        icon.style.color = 'var(--secondary)';
        icon.className = 'fas fa-info-circle';
    }
    
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// ============== SECTION NAVIGATION ==============
function showSection(section) {
    if (section === 'home') {
        document.getElementById('home').scrollIntoView({ behavior: 'smooth' });
    } else if (section === 'products') {
        document.getElementById('products').scrollIntoView({ behavior: 'smooth' });
    } else if (section === 'contact') {
        document.getElementById('contact').scrollIntoView({ behavior: 'smooth' });
    }
    document.getElementById('navLinks').classList.remove('show');
}

// ============== EXPORT FUNCTIONS FOR GLOBAL SCOPE ==============
window.showSection = showSection;
window.changePage = changePage;
window.addToCart = addToCart;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.openCartModal = openCartModal;
window.closeCartModal = closeCartModal;
window.openAuthModal = openAuthModal;
window.closeAuthModal = closeAuthModal;
window.toggleAuthMode = toggleAuthMode;
window.handleAuth = handleAuth;
window.nextSlide = nextSlide;
window.prevSlide = prevSlide;
window.checkout = checkout;
window.loadProducts = loadProducts;
