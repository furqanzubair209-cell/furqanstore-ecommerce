<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Furqan Store | Premium Online Shopping</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* ==================== GLOBAL STYLES ==================== */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
    }

    :root {
      --primary: #0f172a;
      --primary-light: #1e293b;
      --secondary: #38bdf8;
      --secondary-dark: #0284c7;
      --accent: #22c55e;
      --accent-dark: #16a34a;
      --danger: #ef4444;
      --danger-dark: #dc2626;
      --warning: #f59e0b;
      --light: #f8fafc;
      --dark: #020617;
      --gray: #64748b;
      --gray-light: #94a3b8;
      --card-bg: #ffffff;
      --shadow-sm: 0 4px 6px -1px rgb(0 0 0 / 0.1);
      --shadow: 0 10px 25px -5px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 20px 40px -10px rgb(0 0 0 / 0.15);
      --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
      --radius-sm: 8px;
      --radius: 12px;
      --radius-lg: 18px;
    }

    body {
      background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
      color: var(--dark);
      min-height: 100vh;
    }

    /* ==================== UTILITY CLASSES ==================== */
    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 0 5%;
    }

    .hidden {
      display: none !important;
    }

    /* ==================== NAVBAR ==================== */
    nav {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: rgba(15, 23, 42, 0.95);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(56, 189, 248, 0.2);
    }

    .nav-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 1rem 5%;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 900;
      letter-spacing: -1px;
      background: linear-gradient(135deg, #ffffff, #38bdf8);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .nav-links {
      display: flex;
      gap: 2rem;
      list-style: none;
      align-items: center;
    }

    .nav-links a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      padding: 0.5rem 0;
      position: relative;
      transition: var(--transition);
      cursor: pointer;
    }

    .nav-links a:hover {
      color: var(--secondary);
    }

    .nav-links a::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: width 0.3s ease;
    }

    .nav-links a:hover::after {
      width: 100%;
    }

    .cart-link {
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(56, 189, 248, 0.15);
      padding: 0.6rem 1.2rem;
      border-radius: 50px;
      transition: var(--transition);
      cursor: pointer;
    }

    .cart-link:hover {
      background: var(--secondary);
      transform: translateY(-2px);
    }

    #cartCount {
      background: var(--danger);
      padding: 2px 8px;
      border-radius: 50px;
      font-size: 0.75rem;
      font-weight: 700;
      min-width: 22px;
      text-align: center;
    }

    .menu-btn {
      display: none;
      font-size: 1.8rem;
      color: white;
      cursor: pointer;
    }

    /* ==================== SLIDER ==================== */
    .slider {
      position: relative;
      height: 70vh;
      overflow: hidden;
    }

    .slide {
      position: absolute;
      inset: 0;
      opacity: 0;
      transition: opacity 0.8s ease;
      background-size: cover;
      background-position: center;
    }

    .slide.active {
      opacity: 1;
    }

    .slide::before {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(15,23,42,0.9), rgba(2,6,23,0.8));
    }

    .slide-content {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      color: white;
      text-align: center;
      z-index: 1;
    }

    .slide-content h1 {
      font-size: 4rem;
      font-weight: 900;
      letter-spacing: -2px;
      margin-bottom: 1rem;
      animation: fadeInUp 1s ease;
    }

    .slide-content p {
      font-size: 1.5rem;
      opacity: 0.9;
      animation: fadeInUp 1s ease 0.2s both;
    }

    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(255,255,255,0.2);
      backdrop-filter: blur(4px);
      border: 1px solid rgba(255,255,255,0.3);
      color: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      font-size: 1.5rem;
      cursor: pointer;
      transition: var(--transition);
      z-index: 2;
    }

    .slider-btn:hover {
      background: var(--secondary);
      border-color: var(--secondary);
      transform: translateY(-50%) scale(1.1);
    }

    .prev { left: 30px; }
    .next { right: 30px; }

    /* ==================== PRODUCTS SECTION ==================== */
    .products-section {
      padding: 5rem 5%;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 3rem;
      flex-wrap: wrap;
      gap: 2rem;
    }

    .section-title {
      font-size: 2.5rem;
      font-weight: 800;
      color: var(--primary);
      letter-spacing: -1px;
      position: relative;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 0;
      width: 80px;
      height: 4px;
      background: linear-gradient(90deg, var(--secondary), var(--accent));
      border-radius: 2px;
    }

    .filters {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .search-box {
      position: relative;
      display: flex;
      align-items: center;
    }

    .search-box i {
      position: absolute;
      left: 15px;
      color: var(--gray);
    }

    #searchInput {
      padding: 0.8rem 1rem 0.8rem 45px;
      border: 2px solid rgba(0,0,0,0.05);
      border-radius: 50px;
      width: 250px;
      font-size: 0.95rem;
      transition: var(--transition);
      background: white;
    }

    #searchInput:focus {
      outline: none;
      border-color: var(--secondary);
      box-shadow: 0 0 0 4px rgba(56,189,248,0.1);
      width: 300px;
    }

    .category-filter {
      padding: 0.8rem 1.5rem;
      border: 2px solid rgba(0,0,0,0.05);
      border-radius: 50px;
      background: white;
      color: var(--primary);
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }

    .category-filter:hover {
      border-color: var(--secondary);
      background: var(--secondary);
      color: white;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .product-card {
      background: var(--card-bg);
      border-radius: var(--radius-lg);
      overflow: hidden;
      box-shadow: var(--shadow-sm);
      transition: var(--transition);
      position: relative;
      border: 1px solid rgba(0,0,0,0.05);
      animation: fadeIn 0.5s ease;
    }

    .product-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-lg);
    }

    .badge {
      position: absolute;
      top: 15px;
      left: 15px;
      padding: 5px 12px;
      font-size: 0.7rem;
      font-weight: 700;
      border-radius: 50px;
      color: white;
      text-transform: uppercase;
      letter-spacing: 1px;
      z-index: 2;
    }

    .badge.hot { background: var(--danger); }
    .badge.new { background: var(--accent); }
    .badge.sale { background: var(--warning); }
    .badge.best { background: #8b5cf6; }

    .product-image {
      width: 100%;
      height: 260px;
      object-fit: cover;
      transition: var(--transition);
    }

    .product-card:hover .product-image {
      transform: scale(1.08);
    }

    .product-info {
      padding: 1.5rem;
      text-align: center;
    }

    .product-name {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--primary);
    }

    .product-category {
      font-size: 0.8rem;
      color: var(--gray);
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .product-price {
      color: var(--danger);
      font-size: 1.3rem;
      font-weight: 800;
      margin-bottom: 1rem;
      background: rgba(239, 68, 68, 0.08);
      display: inline-block;
      padding: 4px 15px;
      border-radius: 50px;
    }

    .product-rating {
      color: #fbbf24;
      margin-bottom: 1rem;
    }

    .product-rating span {
      color: var(--gray);
      margin-left: 5px;
      font-size: 0.9rem;
    }

    .add-to-cart {
      background: linear-gradient(135deg, var(--accent), var(--accent-dark));
      color: white;
      border: none;
      padding: 0.8rem 2rem;
      border-radius: 50px;
      cursor: pointer;
      font-weight: 700;
      transition: var(--transition);
      width: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .add-to-cart:hover:not(:disabled) {
      filter: brightness(1.1);
      transform: scale(1.02);
    }

    .add-to-cart:disabled {
      background: var(--gray);
      cursor: not-allowed;
      opacity: 0.7;
    }

    /* ==================== PAGINATION ==================== */
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.8rem;
      flex-wrap: wrap;
    }

    .page-btn {
      background: white;
      border: 2px solid var(--gray-light);
      color: var(--primary);
      padding: 0.6rem 1.2rem;
      border-radius: var(--radius-sm);
      cursor: pointer;
      font-weight: 600;
      transition: var(--transition);
    }

    .page-btn:hover:not(.active):not(:disabled) {
      background: var(--secondary);
      border-color: var(--secondary);
      color: white;
    }

    .page-btn.active {
      background: var(--secondary);
      border-color: var(--secondary);
      color: white;
    }

    .page-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    /* ==================== CART MODAL ==================== */
    .modal {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.7);
      backdrop-filter: blur(8px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      animation: fadeIn 0.3s ease;
    }

    .modal.active {
      display: flex;
    }

    .modal-content {
      background: white;
      padding: 2.5rem;
      border-radius: var(--radius-lg);
      max-width: 500px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
      position: relative;
      animation: slideUp 0.4s ease;
    }

    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 1.8rem;
      cursor: pointer;
      color: var(--gray);
      transition: var(--transition);
    }

    .close-btn:hover {
      color: var(--danger);
      transform: rotate(90deg);
    }

    .cart-items {
      margin: 1.5rem 0;
    }

    .cart-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem 0;
      border-bottom: 1px solid #eee;
    }

    .cart-item-img {
      width: 60px;
      height: 60px;
      border-radius: var(--radius-sm);
      object-fit: cover;
    }

    .cart-item-info {
      flex: 1;
    }

    .cart-item-title {
      font-weight: 600;
      color: var(--primary);
    }

    .cart-item-price {
      color: var(--danger);
      font-weight: 700;
    }

    .cart-item-quantity {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quantity-btn {
      background: var(--light);
      border: none;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      cursor: pointer;
      transition: var(--transition);
    }

    .quantity-btn:hover {
      background: var(--secondary);
      color: white;
    }

    .cart-total {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 2px solid #eee;
      font-size: 1.2rem;
      font-weight: 700;
    }

    .checkout-btn {
      background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
      color: white;
      border: none;
      padding: 1rem;
      border-radius: var(--radius);
      font-weight: 700;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 1rem;
      width: 100%;
    }

    .checkout-btn:hover {
      filter: brightness(1.1);
      transform: translateY(-2px);
    }

    /* ==================== TOAST NOTIFICATION ==================== */
    .toast {
      position: fixed;
      bottom: 30px;
      right: 30px;
      background: white;
      color: var(--dark);
      padding: 1rem 1.5rem;
      border-radius: var(--radius);
      box-shadow: var(--shadow-lg);
      display: flex;
      align-items: center;
      gap: 12px;
      transform: translateX(400px);
      transition: transform 0.3s ease;
      z-index: 3000;
      border-left: 4px solid var(--accent);
    }

    .toast.show {
      transform: translateX(0);
    }

    .toast i {
      color: var(--accent);
      font-size: 1.2rem;
    }

    /* ==================== LOADING SKELETON ==================== */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: loading 1.5s infinite;
    }

    /* ==================== EMPTY STATES ==================== */
    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
      color: var(--gray);
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 1rem;
    }

    /* ==================== FOOTER ==================== */
    footer {
      background: var(--dark);
      color: var(--gray-light);
      padding: 4rem 5% 2rem;
    }

    .footer-content {
      max-width: 1400px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 3rem;
    }

    .footer-section h3 {
      color: white;
      font-size: 1.3rem;
      margin-bottom: 1.5rem;
    }

    .footer-section p {
      margin-bottom: 0.8rem;
      line-height: 1.6;
    }

    .social-icons {
      display: flex;
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .social-icons a {
      color: white;
      background: rgba(255,255,255,0.1);
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
    }

    .social-icons a:hover {
      background: var(--secondary);
      transform: translateY(-3px);
    }

    /* ==================== ANIMATIONS ==================== */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(50px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes loading {
      0% { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }

    /* ==================== RESPONSIVE ==================== */
    @media (max-width: 768px) {
      .nav-links {
        position: fixed;
        top: 70px;
        left: 0;
        width: 100%;
        background: var(--primary);
        flex-direction: column;
        padding: 2rem;
        gap: 1.5rem;
        transform: translateY(-100%);
        opacity: 0;
        transition: var(--transition);
      }

      .nav-links.show {
        transform: translateY(0);
        opacity: 1;
      }

      .menu-btn {
        display: block;
      }

      .slider {
        height: 50vh;
      }

      .slide-content h1 {
        font-size: 2.5rem;
      }

      .slide-content p {
        font-size: 1.2rem;
      }

      .section-header {
        flex-direction: column;
        align-items: stretch;
      }

      #searchInput {
        width: 100%;
      }

      #searchInput:focus {
        width: 100%;
      }

      .slider-btn {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
      }

      .prev { left: 15px; }
      .next { right: 15px; }
    }

    @media (max-width: 480px) {
      .slide-content h1 {
        font-size: 1.8rem;
      }

      .section-title {
        font-size: 2rem;
      }

      .products-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- ==================== NAVIGATION ==================== -->
  <nav>
    <div class="nav-container">
      <div class="logo">FurqanStore</div>
      
      <ul class="nav-links" id="navLinks">
        <li><a href="#home" onclick="showSection('home')">Home</a></li>
        <li><a href="#products" onclick="showSection('products')">Products</a></li>
        <li><a href="#contact" onclick="showSection('contact')">Contact</a></li>
        <li><a href="#" onclick="openAuthModal('login')">Login</a></li>
        <li><a href="#" onclick="openAuthModal('signup')">Sign Up</a></li>
        <li class="cart-link" onclick="openCartModal()">
          <i class="fas fa-shopping-cart"></i>
          Cart
          <span id="cartCount">0</span>
        </li>
      </ul>
      
      <div class="menu-btn" id="menuBtn">
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </nav>

  <!-- ==================== HERO SLIDER ==================== -->
  <section id="home" class="slider">
    <div class="slide active" style="background-image: url('https://images.unsplash.com/photo-1556740714-a8395b3a74dd?w=1600')">
      <div class="slide-content">
        <h1>Smart Shopping Starts Here</h1>
        <p>Premium products • Trusted quality • Fast delivery</p>
      </div>
    </div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1515165562835-c4c9b3f5e8b8?w=1600')">
      <div class="slide-content">
        <h1>Latest Tech Collection</h1>
        <p>Upgrade your lifestyle today</p>
      </div>
    </div>
    <div class="slide" style="background-image: url('https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=1600')">
      <div class="slide-content">
        <h1>Fashion Meets Comfort</h1>
        <p>Trendy styles at best prices</p>
      </div>
    </div>
    <button class="slider-btn prev" onclick="prevSlide()">❮</button>
    <button class="slider-btn next" onclick="nextSlide()">❯</button>
  </section>

  <!-- ==================== PRODUCTS SECTION ==================== -->
  <section id="products" class="products-section">
    <div class="section-header">
      <h2 class="section-title">Featured Products</h2>
      <div class="filters">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search products...">
        </div>
        <select id="categoryFilter" class="category-filter">
          <option value="all">All Categories</option>
          <option value="footwear">Footwear</option>
          <option value="audio">Audio</option>
          <option value="electronics">Electronics</option>
          <option value="fashion">Fashion</option>
          <option value="appliances">Appliances</option>
          <option value="sports">Sports</option>
        </select>
      </div>
    </div>

    <div id="productGrid" class="products-grid">
      <!-- Loading skeleton -->
      <div class="skeleton" style="height: 400px; border-radius: var(--radius-lg);"></div>
      <div class="skeleton" style="height: 400px; border-radius: var(--radius-lg);"></div>
      <div class="skeleton" style="height: 400px; border-radius: var(--radius-lg);"></div>
    </div>
    <div id="pagination" class="pagination"></div>
  </section>

  <!-- ==================== FOOTER ==================== -->
  <footer id="contact">
    <div class="footer-content">
      <div class="footer-section">
        <h3>FurqanStore</h3>
        <p>Your premier destination for premium online shopping. Quality products, competitive prices, and exceptional service.</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-section">
        <h3>Contact Info</h3>
        <p><i class="fas fa-map-marker-alt"></i> Lahore, Pakistan</p>
        <p><i class="fas fa-phone"></i> +92 300 1234567</p>
        <p><i class="fas fa-envelope"></i> support@furqanstore.com</p>
        <p><i class="fas fa-clock"></i> Mon-Fri: 9AM - 6PM</p>
      </div>
      <div class="footer-section">
        <h3>Quick Links</h3>
        <p><a href="#home" onclick="showSection('home')" style="color: var(--gray-light); text-decoration: none;">Home</a></p>
        <p><a href="#products" onclick="showSection('products')" style="color: var(--gray-light); text-decoration: none;">Products</a></p>
        <p><a href="#contact" style="color: var(--gray-light); text-decoration: none;">Contact Us</a></p>
        <p><a href="#" style="color: var(--gray-light); text-decoration: none;">Privacy Policy</a></p>
        <p><a href="#" style="color: var(--gray-light); text-decoration: none;">Terms of Service</a></p>
      </div>
    </div>
    <div class="copyright" style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
      <p>© 2026 FurqanStore. All rights reserved. | Made with <i class="fas fa-heart" style="color: var(--danger);"></i> in Pakistan</p>
    </div>
  </footer>

  <!-- ==================== CART MODAL ==================== -->
  <div id="cartModal" class="modal">
    <div class="modal-content">
      <span class="close-btn" onclick="closeCartModal()">&times;</span>
      <h2 style="color: var(--primary); margin-bottom: 1rem;">Your Cart</h2>
      <div id="cartItems" class="cart-items"></div>
      <div id="cartEmpty" class="empty-state hidden">
        <i class="fas fa-shopping-cart"></i>
        <h3>Your cart is empty</h3>
        <p style="margin-top: 0.5rem;">Start shopping to add items!</p>
      </div>
      <div id="cartFooter" class="hidden">
        <div class="cart-total">
          <span>Total:</span>
          <span id="cartTotalPrice" style="color: var(--danger);">PKR 0</span>
        </div>
        <button class="checkout-btn" onclick="checkout()">
          <i class="fas fa-lock"></i> Proceed to Checkout
        </button>
      </div>
    </div>
  </div>

  <!-- ==================== AUTH MODAL ==================== -->
  <div id="authModal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
      <span class="close-btn" onclick="closeAuthModal()">&times;</span>
      <h2 id="authTitle" style="color: var(--primary); margin-bottom: 1.5rem;">Login</h2>
      <input type="email" id="authEmail" placeholder="Email" style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 2px solid #eee; border-radius: var(--radius);">
      <input type="password" id="authPassword" placeholder="Password" style="width: 100%; padding: 0.8rem; margin-bottom: 1rem; border: 2px solid #eee; border-radius: var(--radius);">
      <button onclick="handleAuth()" style="width: 100%; padding: 0.8rem; background: linear-gradient(135deg, var(--secondary), var(--secondary-dark)); color: white; border: none; border-radius: var(--radius); font-weight: 700; cursor: pointer; margin-bottom: 1rem;">
        <span id="authButtonText">Login</span>
      </button>
      <p id="toggleAuth" style="text-align: center; color: var(--gray);">
        Don't have an account?
        <span onclick="toggleAuthMode()" style="color: var(--secondary); font-weight: 600; cursor: pointer;">Sign Up</span>
      </p>
    </div>
  </div>

  <!-- ==================== TOAST NOTIFICATION ==================== -->
  <div id="toast" class="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage">Product added to cart!</span>
  </div>

  <!-- ==================== JAVASCRIPT (DATABASE CONNECTED) ==================== -->
  <script>
    // API Base URL
    const API_BASE = window.location.origin + '/furqan-store/api/';
    
    // Global state
    let currentPage = 1;
    let productsPerPage = 12;
    let totalPages = 1;
    let currentUser = null;
    let currentSlide = 0;
    let slideInterval;
    let cartItems = [];

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
      
      // Search and filter events
      document.getElementById('searchInput').addEventListener('input', () => {
        currentPage = 1;
        loadProducts();
      });
      
      document.getElementById('categoryFilter').addEventListener('change', () => {
        currentPage = 1;
        loadProducts();
      });
    });

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
      const authLinks = navLinks.querySelectorAll('a[onclick*="openAuthModal"]');
      authLinks.forEach(link => {
        link.parentElement.remove();
      });
      
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
          headers: { 'Content-Type': 'application/json' },
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
          headers: { 'Content-Type': 'application/json' },
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
        
        const inCart = cartItems.some(item => item.product_id === product.id);
        
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
              <button class="add-to-cart" onclick="addToCart(${product.id})" ${inCart ? 'disabled' : ''}>
                <i class="fas fa-cart-plus"></i> ${inCart ? 'Added to Cart' : 'Add to Cart'}
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
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        if (data.success) {
          showToast('Added to cart!', 'success');
          loadCart();
          loadProducts(); // Refresh to update button state
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
        cartItems = [];
        return;
      }
      
      try {
        const response = await fetch(`${API_BASE}cart.php`);
        const data = await response.json();
        if (data.success) {
          cartItems = data.data.items || [];
          const totalItems = cartItems.reduce((sum, item) => sum + item.quantity, 0);
          document.getElementById('cartCount').innerText = totalItems;
          if (document.getElementById('cartModal').classList.contains('active')) {
            renderCartItems(cartItems);
          }
        }
      } catch (error) {
        console.error('Failed to load cart:', error);
      }
    }

    async function updateCartQuantity(cartId, quantity) {
      if (quantity <= 0) {
        await removeFromCart(cartId);
        return;
      }
      
      try {
        const response = await fetch(`${API_BASE}cart.php`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
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
          loadProducts(); // Refresh to enable add to cart button
          showToast('Item removed', 'success');
        }
      } catch (error) {
        console.error('Failed to remove from cart:', error);
      }
    }

    function renderCartItems(items) {
      const cartItemsDiv = document.getElementById('cartItems');
      const cartEmptyDiv = document.getElementById('cartEmpty');
      const cartFooterDiv = document.getElementById('cartFooter');

      if (!items || items.length === 0) {
        cartItemsDiv.innerHTML = '';
        cartEmptyDiv.classList.remove('hidden');
        cartFooterDiv.classList.add('hidden');
        return;
      }
      
      cartEmptyDiv.classList.add('hidden');
      cartFooterDiv.classList.remove('hidden');
      
      let total = 0;
      cartItemsDiv.innerHTML = items.map(item => {
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
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ address, phone })
        });
        
        const data = await response.json();
        if (data.success) {
          showToast('Order placed successfully!', 'success');
          closeCartModal();
          loadCart();
          loadProducts();
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
      renderCartItems(cartItems);
      document.body.style.overflow = 'hidden';
    }

    function closeCartModal() {
      document.getElementById('cartModal').classList.remove('active');
      document.body.style.overflow = 'auto';
    }

    let currentAuthMode = 'login';

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
      const slides = document.querySelectorAll('.slide');
      slides.forEach(slide => slide.classList.remove('active'));
      slides[index].classList.add('active');
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

    // ============== EXPORT FUNCTIONS ==============
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
  </script>
</body>
</html>