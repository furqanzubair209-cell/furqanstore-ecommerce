<?php
// ============================================
// INDEX: index.php (Landing Page - Loads Frontend)
// ============================================
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FurqanStore | Premium Luxury E-Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode">
  <!-- Theme Toggle - Floating Button -->
  <div class="theme-toggle-floating" id="themeToggleFloating" onclick="toggleTheme()">
    <i class="fas fa-moon" id="themeIconFloating"></i>
  </div>

<div class="app">
  <!-- Premium Cursor -->
  <div class="cursor"></div>
  <div class="cursor-follower"></div>

  <!-- Animated Background -->
  <div class="animated-bg">
    <div class="gradient-sphere"></div>
    <div class="gradient-sphere2"></div>
    <div class="gradient-sphere3"></div>
    <div class="gradient-sphere4"></div>
    <div class="glass-float shape-1"></div>
    <div class="glass-float shape-2"></div>
    <div class="glass-float shape-3"></div>
  </div>

  <!-- Premium Sidebar -->
  <aside class="premium-sidebar">
    <div class="sidebar-glow"></div>
    <div class="sidebar-content">
      <div class="logo-area">
        <div class="logo-icon">
          <i class="fas fa-crown"></i>
        </div>
        <div class="logo-text">
          <span class="logo-furqan">Furqan</span>
          <span class="logo-store">Store</span>
        </div>
      </div>

      <div class="user-card">
        <div class="user-avatar">
          <img src="https://ui-avatars.com/api/?background=3b82f6&color=fff&rounded=true&bold=true&size=80" alt="User">
          <div class="online-dot"></div>
        </div>
        <div class="user-info-panel">
          <h4 id="sidebarUserName">Guest User</h4>
          <p id="sidebarUserRole">Welcome! Login to shop</p>
        </div>
      </div>

      <nav class="premium-nav">
        <a href="#" onclick="showPage('home')" class="nav-link active">
          <div class="nav-icon"><i class="fas fa-home"></i></div>
          <span>Home</span>
          <div class="nav-indicator"></div>
        </a>
        <a href="#" onclick="showPage('products')" class="nav-link">
          <div class="nav-icon"><i class="fas fa-store"></i></div>
          <span>Shop</span>
          <div class="nav-indicator"></div>
        </a>
        <a href="#" onclick="showPage('contact')" class="nav-link">
          <div class="nav-icon"><i class="fas fa-headset"></i></div>
          <span>Support</span>
          <div class="nav-indicator"></div>
        </a>
      </nav>

      <div class="categories-widget">
        <div class="widget-header">
          <i class="fas fa-tags"></i>
          <span>Categories</span>
        </div>
        <div id="premiumCategories" class="categories-list-premium"></div>
      </div>

      <div class="sidebar-footer-premium">
        <div class="cart-preview" onclick="openCartModal()">
          <div class="cart-icon-premium">
            <i class="fas fa-shopping-bag"></i>
            <span id="cartCount">0</span>
          </div>
          <div class="cart-text">
            <span>My Cart</span>
            <small>View items</small>
          </div>
          <i class="fas fa-chevron-right"></i>
        </div>
        <div class="auth-button-premium" id="sidebarAuthBtn" onclick="openAuthModal('login')">
          <i class="fas fa-user-astronaut"></i>
          <span>Login / Register</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- Premium Main Content -->
  <main class="premium-main">
    <!-- Premium Header -->
    <header class="premium-header glass">
      <div class="menu-trigger" id="menuTrigger">
        <i class="fas fa-bars-staggered"></i>
      </div>
      
      <div class="search-bar-premium">
        <i class="fas fa-search"></i>
        <input type="text" id="globalSearch" placeholder="Search for products, brands..." onkeyup="filterProducts()">
        <div class="search-shortcut">⌘K</div>
      </div>
      
      <div class="header-actions-premium">
        <!-- Theme Toggle in Header -->
        <div class="theme-toggle-header" onclick="toggleTheme()">
          <i class="fas fa-sun"></i>
          <div class="toggle-switch">
            <span class="toggle-slider"></span>
          </div>
          <i class="fas fa-moon"></i>
        </div>
        
        <div class="notification-bell">
          <i class="fas fa-bell"></i>
          <span class="notification-dot"></span>
        </div>
        <div class="user-menu-premium" onclick="toggleUserMenu()">
          <img src="https://ui-avatars.com/api/?background=3b82f6&color=fff&bold=true&size=40" alt="Avatar">
          <div class="user-menu-dropdown" id="userMenuDropdown">
            <div class="dropdown-header">
              <strong id="dropdownUserName">Guest</strong>
              <span id="dropdownUserRole">Not logged in</span>
            </div>
            <div class="dropdown-divider"></div>
            <a href="#" onclick="openAuthModal('login')"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="#" onclick="openAuthModal('signup')"><i class="fas fa-user-plus"></i> Sign Up</a>
          </div>
        </div>
      </div>
    </header>

    <!-- Dynamic Pages -->
    <div id="homePage" class="page active">
      <!-- Premium Hero Slider -->
      <div class="premium-hero">
        <div class="hero-slider-premium">
          <div class="hero-slide active" data-slide="0">
            <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1556740714-a8395b3a74dd?w=1600')"></div>
            <div class="hero-content">
              <div class="hero-badge reveal stagger-1">Limited Edition</div>
              <h1 class="hero-title reveal stagger-2">Luxury <span>Collection</span> 2026</h1>
              <p class="hero-subtitle reveal stagger-3">Experience premium quality with exclusive discounts up to 50% off</p>
              <div class="hero-buttons reveal stagger-4">
                <button class="btn-primary-glow" onclick="showPage('products')">Shop Now →</button>
                <button class="btn-outline-glow" onclick="openAuthModal('signup')">Join VIP →</button>
              </div>
            </div>
          </div>
          <div class="hero-slide" data-slide="1">
            <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1515165562835-c4c9b3f5e8b8?w=1600')"></div>
            <div class="hero-content">
              <div class="hero-badge">Trending Now</div>
              <h1 class="hero-title">Tech <span>Revolution</span></h1>
              <p class="hero-subtitle">Latest gadgets with cutting-edge technology</p>
              <div class="hero-buttons">
                <button class="btn-primary-glow" onclick="showPage('products')">Explore →</button>
              </div>
            </div>
          </div>
          <div class="hero-slide" data-slide="2">
            <div class="hero-bg" style="background-image: url('https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=1600')"></div>
            <div class="hero-content">
              <div class="hero-badge">Fashion Week</div>
              <h1 class="hero-title">Style <span>Elevated</span></h1>
              <p class="hero-subtitle">Premium fashion for the modern lifestyle</p>
              <div class="hero-buttons">
                <button class="btn-primary-glow" onclick="showPage('products')">Shop Collection →</button>
              </div>
            </div>
          </div>
          <button class="hero-prev" onclick="prevSlide()"><i class="fas fa-chevron-left"></i></button>
          <button class="hero-next" onclick="nextSlide()"><i class="fas fa-chevron-right"></i></button>
          <div class="hero-dots"></div>
        </div>
      </div>

      <!-- Stats Section -->
      <div class="stats-section">
        <div class="stat-card reveal stagger-1">
          <i class="fas fa-truck-fast"></i>
          <div>
            <h3>Free Shipping</h3>
            <p>On orders over PKR 5,000</p>
          </div>
        </div>
        <div class="stat-card reveal stagger-2">
          <i class="fas fa-shield-alt"></i>
          <div>
            <h3>Secure Payment</h3>
            <p>100% protected</p>
          </div>
        </div>
        <div class="stat-card reveal stagger-3">
          <i class="fas fa-undo-alt"></i>
          <div>
            <h3>Easy Returns</h3>
            <p>30 days return policy</p>
          </div>
        </div>
        <div class="stat-card reveal stagger-4">
          <i class="fas fa-headset"></i>
          <div>
            <h3>24/7 Support</h3>
            <p>Dedicated assistance</p>
          </div>
        </div>
      </div>

      <!-- Premium Categories -->
      <div class="section-premium reveal">
        <div class="section-header-premium">
          <div>
            <span class="section-badge">Categories</span>
            <h2 class="section-title">Shop by <span>Category</span></h2>
          </div>
          <button class="view-all" onclick="showPage('products')">View All <i class="fas fa-arrow-right"></i></button>
        </div>
        <div class="categories-premium-grid" id="homeCategories"></div>
      </div>

      <!-- Featured Products -->
      <div class="section-premium reveal">
        <div class="section-header-premium">
          <div>
            <span class="section-badge">Best Sellers</span>
            <h2 class="section-title">Featured <span>Products</span></h2>
          </div>
        </div>
        <div class="products-premium-grid" id="featuredProducts"></div>
      </div>
    </div>

    <div id="productsPage" class="page">
      <div class="page-header-premium">
        <div>
          <h1>All Products</h1>
          <p>Discover our premium collection</p>
        </div>
        <div class="filter-group">
          <div class="filter-input">
            <i class="fas fa-filter"></i>
            <select id="categoryFilter" onchange="filterProducts()">
              <option value="all">All Categories</option>
            </select>
          </div>
          <div class="filter-input">
            <i class="fas fa-store"></i>
            <select id="vendorFilter" onchange="filterProducts()">
              <option value="all">All Vendors</option>
            </select>
          </div>
          <div class="filter-input">
            <i class="fas fa-arrow-down-wide-short"></i>
            <select id="sortBy" onchange="filterProducts()">
              <option value="default">Sort by: Featured</option>
              <option value="price_asc">Price: Low to High</option>
              <option value="price_desc">Price: High to Low</option>
              <option value="rating">Top Rated</option>
            </select>
          </div>
        </div>
      </div>
      <div id="productsGrid" class="products-premium-grid"></div>
      <div id="pagination" class="pagination-premium"></div>
    </div>

    <div id="contactPage" class="page">
      <div class="page-header-premium">
        <div>
          <span class="section-badge">Support Center</span>
          <h1>How can we <span>help you?</span></h1>
          <p>Search our help articles or get in touch with our experts</p>
        </div>
      </div>

      <!-- Support Categories -->
      <div class="support-grid reveal">
        <div class="support-category tilt-card" onclick="openSupportArticle('tracking')">
          <i class="fas fa-box-open"></i>
          <h3>Order Tracking</h3>
          <p>Check the status of your luxury purchases</p>
        </div>
        <div class="support-category tilt-card" onclick="openSupportArticle('shipping')">
          <i class="fas fa-truck"></i>
          <h3>Shipping & Delivery</h3>
          <p>Information about our worldwide shipping</p>
        </div>
        <div class="support-category tilt-card" onclick="openSupportArticle('returns')">
          <i class="fas fa-undo"></i>
          <h3>Returns & Refunds</h3>
          <p>Simple and easy return policy details</p>
        </div>
        <div class="support-category tilt-card" onclick="openSupportArticle('security')">
          <i class="fas fa-shield-alt"></i>
          <h3>Payment Security</h3>
          <p>How we protect your premium transactions</p>
        </div>
      </div>

      <!-- FAQ Section -->
      <div class="section-premium reveal">
        <div class="section-header-premium">
          <h2 class="section-title">Frequently Asked <span>Questions</span></h2>
        </div>
        <div class="faq-section">
          <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">
              <span>How long does international shipping take?</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              Our premium international shipping typically takes 3-7 business days depending on your location. All orders are fully insured and tracked.
            </div>
          </div>
          <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">
              <span>What is your return policy for luxury items?</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              We offer a 30-day complimentary return policy for all items in their original condition. Return shipping is on us for all VIP members.
            </div>
          </div>
          <div class="faq-item" onclick="this.classList.toggle('active')">
            <div class="faq-question">
              <span>Are the products on FurqanStore authentic?</span>
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              Every item sold on FurqanStore is guaranteed 100% authentic. We work directly with brands and authorized vendors to ensure the highest quality.
            </div>
          </div>
        </div>
      </div>

      <!-- Support Ticket Form -->
      <div class="section-premium reveal">
        <div class="support-ticket-area">
          <div class="contact-info-premium">
            <div class="contact-header">
              <span class="section-badge">Direct Contact</span>
              <h2>Still need <span>help?</span></h2>
              <p>Our dedicated support team is available 24/7 to assist you with any inquiries.</p>
            </div>
            <div class="contact-cards">
              <div class="contact-card-premium reveal stagger-1">
                <div class="contact-icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                  <h4>Priority Line</h4>
                  <p>+92 300 1234567</p>
                </div>
              </div>
              <div class="contact-card-premium reveal stagger-2">
                <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                <div>
                  <h4>Email Support</h4>
                  <p>support@furqanstore.com</p>
                </div>
              </div>
            </div>
          </div>

          <div class="contact-form-premium reveal">
            <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">Open a Support Ticket</h3>
            <form id="contactForm" onsubmit="sendMessage(event)">
              <div class="form-row">
                <div class="input-group-premium">
                  <i class="fas fa-user"></i>
                  <input type="text" id="contactName" placeholder="Your Name" required>
                </div>
                <div class="input-group-premium">
                  <i class="fas fa-envelope"></i>
                  <input type="email" id="contactEmail" placeholder="Email Address" required>
                </div>
              </div>
              <div class="input-group-premium">
                <i class="fas fa-tag"></i>
                <select id="contactSubject" class="select-premium" style="width: 100%; border: none; background: none; color: inherit; padding-left: 35px;">
                  <option value="order">Order Inquiry</option>
                  <option value="delivery">Delivery Issue</option>
                  <option value="payment">Payment Problem</option>
                  <option value="other">Other Inquiry</option>
                </select>
              </div>
              <div class="input-group-premium">
                <i class="fas fa-comment"></i>
                <textarea id="contactMessage" rows="4" placeholder="How can we help you?..." required></textarea>
              </div>
              <button type="submit" class="btn-submit">
                <span>Submit Ticket</span>
                <i class="fas fa-paper-plane"></i>
              </button>
            </form>
            <div id="contactSuccess" class="success-message hidden">
              <i class="fas fa-check-circle"></i>
              <p>Ticket submitted! We'll get back to you shortly.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Premium Footer -->
    <footer class="premium-footer">
      <div class="footer-content-premium">
        <div class="footer-brand">
          <div class="logo-icon-small"><i class="fas fa-crown"></i></div>
          <div>
            <h3>FurqanStore</h3>
            <p>Premium E-Commerce Experience</p>
          </div>
        </div>
        <div class="footer-links">
          <a href="#" onclick="showPage('home')">Home</a>
          <a href="#" onclick="showPage('products')">Shop</a>
          <a href="#" onclick="showPage('contact')">Support</a>
          <a href="#" onclick="openAuthModal('login')">Account</a>
        </div>
        <div class="footer-social">
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-copyright">
        <p>© 2026 FurqanStore — Where Luxury Meets Convenience</p>
      </div>
    </footer>
  </main>
</div>

<!-- Modals -->
<div id="cartModal" class="premium-modal">
  <div class="modal-glass">
    <div class="modal-header-premium">
      <h2><i class="fas fa-shopping-bag"></i> Your Cart</h2>
      <button class="modal-close" onclick="closeCartModal()"><i class="fas fa-times"></i></button>
    </div>
    <div id="cartItemsList" class="cart-items-premium"></div>
    <div id="emptyCart" class="empty-cart-premium">
      <i class="fas fa-shopping-bag"></i>
      <p>Your cart is empty</p>
      <button class="btn-primary-glow" onclick="closeCartModal();showPage('products')">Start Shopping</button>
    </div>
    <div id="cartFooterPremium" class="cart-footer-premium hidden">
      <div class="cart-summary-premium">
        <div class="summary-row"><span>Subtotal</span><span id="cartSubtotal">PKR 0</span></div>
        <div class="summary-row"><span>Shipping</span><span>PKR 200</span></div>
        <div class="summary-row total"><span>Total</span><span id="cartTotal">PKR 0</span></div>
      </div>
      <button class="checkout-premium" onclick="checkout()">Checkout <i class="fas fa-arrow-right"></i></button>
    </div>
  </div>
</div>

<div id="authModal" class="premium-modal">
  <div class="modal-glass auth-glass">
    <div class="auth-visual">
      <div class="auth-visual-content">
        <i class="fas fa-crown"></i>
        <h2>Elevate Your Experience</h2>
        <p>Join FurqanStore to discover exclusive luxury collections and personalized shopping.</p>
      </div>
    </div>
    
    <div class="auth-content">
      <button class="modal-close" onclick="closeAuthModal()" style="position: absolute; top: 20px; right: 20px;"><i class="fas fa-times"></i></button>
      
      <div class="auth-header">
        <h2 id="authModalTitle">Welcome Back</h2>
        <p id="authModalDesc">Enter your credentials to access your account</p>
      </div>
      
      <div id="loginForm" class="auth-form-premium">
        <div class="input-group-premium">
          <i class="fas fa-envelope"></i>
          <input type="email" id="loginEmail" placeholder="Email Address">
        </div>
        <div class="input-group-premium">
          <i class="fas fa-lock"></i>
          <input type="password" id="loginPassword" placeholder="Password">
        </div>
        <button class="btn-primary-glow" onclick="loginUser()">Sign In</button>
        <p class="auth-switch-premium">New here? <a onclick="showSignupForm()">Create Account</a></p>
      </div>
      
      <div id="signupForm" class="auth-form-premium hidden">
        <div class="input-group-premium">
          <i class="fas fa-user"></i>
          <input type="text" id="signupName" placeholder="Full Name">
        </div>
        <div class="form-row">
          <div class="input-group-premium">
            <i class="fas fa-envelope"></i>
            <input type="email" id="signupEmail" placeholder="Email Address">
          </div>
          <div class="input-group-premium">
            <i class="fas fa-phone"></i>
            <input type="tel" id="signupPhone" placeholder="Phone Number">
          </div>
        </div>
        <div class="form-row">
          <div class="input-group-premium">
            <i class="fas fa-lock"></i>
            <input type="password" id="signupPassword" placeholder="Password">
          </div>
          <div class="input-group-premium">
            <i class="fas fa-lock"></i>
            <input type="password" id="signupConfirm" placeholder="Confirm Password">
          </div>
        </div>
        <div class="input-group-premium">
          <i class="fas fa-user-tag"></i>
          <select id="signupRole">
            <option value="customer">Customer</option>
            <option value="vendor">Vendor (Sell Products)</option>
          </select>
        </div>
        <button class="btn-primary-glow" onclick="signupUser()">Create Account</button>
        <p class="auth-switch-premium">Already have an account? <a onclick="showLoginForm()">Sign In</a></p>
      </div>
      
      <div class="demo-accounts">
        <p><i class="fas fa-info-circle"></i> Demo Accounts:</p>
        <div class="demo-badges">
          <span>👑 superadmin@furqan.com / admin123</span>
          <span>🏪 vendor@furqan.com / vendor123</span>
          <span>👤 customer@furqan.com / customer123</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="supportArticleModal" class="premium-modal">
  <div class="modal-glass" style="max-width: 800px;">
    <div class="modal-header-premium">
      <h2 id="supportArticleTitle"><i class="fas fa-info-circle"></i> Support Article</h2>
      <button class="modal-close" onclick="closeSupportArticleModal()"><i class="fas fa-times"></i></button>
    </div>
    <div id="supportArticleContent" class="support-article-body" style="padding: 2rem; color: var(--text-primary);">
      <!-- Content will be injected here -->
    </div>
    <div class="modal-footer-premium" style="padding: 1.5rem; border-top: 1px solid var(--border-light); text-align: right;">
      <button class="btn-primary-glow" onclick="closeSupportArticleModal()">Close Article</button>
    </div>
  </div>
</div>

<div id="toast" class="premium-toast"></div>

<div class="mobile-nav-premium">
  <a href="#" onclick="showPage('home')" class="mobile-nav-item active"><i class="fas fa-home"></i><span>Home</span></a>
  <a href="#" onclick="showPage('products')" class="mobile-nav-item"><i class="fas fa-store"></i><span>Shop</span></a>
  <a href="#" onclick="showPage('contact')" class="mobile-nav-item"><i class="fas fa-headset"></i><span>Support</span></a>
  <a href="#" onclick="openCartModal()" class="mobile-nav-item"><i class="fas fa-shopping-bag"></i><span>Cart</span></a>
</div>

<script src="script.js"></script>

</body>
</html>
