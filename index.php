<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furqan Store | Premium Online Shopping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        :root {
            --primary: #0f172a;
            --secondary: #38bdf8;
            --secondary-dark: #0284c7;
            --accent: #22c55e;
            --accent-dark: #16a34a;
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --warning: #f59e0b;
            --dark: #020617;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --light: #f8fafc;
            --shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 40px -10px rgba(0,0,0,0.15);
            --radius: 12px;
            --radius-lg: 18px;
        }

        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: var(--dark);
            min-height: 100vh;
        }

        /* Navigation */
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
            background: linear-gradient(135deg, #ffffff, #38bdf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            cursor: pointer;
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
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .nav-links a:hover {
            color: var(--secondary);
        }

        /* Auth Buttons */
        .auth-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .nav-login-btn {
            background: transparent;
            border: 2px solid var(--secondary);
            color: var(--secondary);
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .nav-login-btn:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
        }

        .nav-signup-btn {
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            border: none;
            color: white;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .nav-signup-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }

        /* User Menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(56, 189, 248, 0.15);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
        }

        .user-menu i {
            font-size: 1.2rem;
            color: var(--secondary);
        }

        .user-menu span {
            color: white;
            font-weight: 600;
        }

        .user-menu .logout-btn {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            padding: 4px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-menu .logout-btn:hover {
            background: #ef4444;
            color: white;
        }

        .user-menu .admin-link {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            padding: 4px 12px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }

        .user-menu .admin-link:hover {
            background: #f59e0b;
            color: white;
        }

        .cart-link {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(56, 189, 248, 0.15);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: white;
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
        }

        .menu-btn {
            display: none;
            font-size: 1.8rem;
            color: white;
            cursor: pointer;
        }

        /* Slider */
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
            margin-bottom: 1rem;
            animation: fadeInUp 1s ease;
        }

        .slide-content p {
            font-size: 1.5rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        .slider-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
            border: none;
        }

        .slider-btn:hover {
            background: var(--secondary);
        }

        .prev { left: 30px; }
        .next { right: 30px; }

        /* Products Section */
        .products-section {
            padding: 5rem 5%;
            max-width: 1400px;
            margin: 0 auto;
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
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--gray);
        }

        #searchInput {
            padding: 0.8rem 1rem 0.8rem 45px;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            width: 250px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        #searchInput:focus {
            outline: none;
            border-color: var(--secondary);
            width: 300px;
        }

        .category-filter {
            padding: 0.8rem 1.5rem;
            border: 2px solid #e2e8f0;
            border-radius: 50px;
            background: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
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
            background: white;
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
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
            transition: all 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
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
        }

        .product-price {
            color: var(--danger);
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            display: inline-block;
            padding: 4px 15px;
            background: rgba(239,68,68,0.08);
            border-radius: 50px;
        }

        .product-rating {
            color: #fbbf24;
            margin-bottom: 1rem;
        }

        .add-to-cart {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-to-cart:hover {
            filter: brightness(1.1);
            transform: scale(1.02);
        }

        .add-to-cart:disabled {
            background: var(--gray);
            cursor: not-allowed;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .page-btn {
            background: white;
            border: 2px solid #cbd5e1;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .page-btn:hover:not(.active) {
            background: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }

        .page-btn.active {
            background: var(--secondary);
            border-color: var(--secondary);
            color: white;
        }

        /* Modals */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 2000;
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
            max-height: 85vh;
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
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            color: var(--danger);
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--secondary);
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
        }

        .modal-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--gray);
        }

        .modal-footer span {
            color: var(--secondary);
            font-weight: 600;
            cursor: pointer;
        }

        .modal-footer span:hover {
            text-decoration: underline;
        }

        /* Cart Items */
        .cart-items {
            margin: 1.5rem 0;
        }

        .cart-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .cart-item-img {
            width: 60px;
            height: 60px;
            border-radius: 8px;
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
            background: #f1f5f9;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
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
            border-top: 2px solid #e2e8f0;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--secondary), var(--secondary-dark));
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
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

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--gray);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .hidden {
            display: none !important;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: #94a3b8;
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
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--secondary);
            transform: translateY(-3px);
        }

        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Animations */
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

        /* Responsive */
        @media (max-width: 768px) {
            .nav-links {
                position: fixed;
                top: 70px;
                left: 0;
                width: 100%;
                background: var(--primary);
                flex-direction: column;
                padding: 2rem;
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 999;
            }
            
            .nav-links.show {
                transform: translateY(0);
                opacity: 1;
            }
            
            .menu-btn {
                display: block;
            }
            
            .auth-buttons {
                flex-direction: column;
                width: 100%;
                gap: 10px;
            }
            
            .nav-login-btn, .nav-signup-btn {
                width: 100%;
                text-align: center;
            }
            
            .user-menu {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .slider {
                height: 50vh;
            }
            
            .slide-content h1 {
                font-size: 2rem;
            }
            
            .slide-content p {
                font-size: 1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            #searchInput:focus {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-container">
            <div class="logo" onclick="location.reload()">FurqanStore</div>
            
            <ul class="nav-links" id="navLinks">
                <li><a onclick="showSection('home')">Home</a></li>
                <li><a onclick="showSection('products')">Products</a></li>
                <li><a onclick="showSection('contact')">Contact</a></li>
                <li id="authLinks" class="auth-buttons">
                    <button class="nav-login-btn" onclick="openLoginModal()">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </button>
                    <button class="nav-signup-btn" onclick="openSignupModal()">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </button>
                </li>
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

    <!-- Hero Slider -->
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

    <!-- Products Section -->
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
        <div id="productGrid" class="products-grid"></div>
        <div id="pagination" class="pagination"></div>
    </section>

    <!-- Footer -->
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
                <p><a onclick="showSection('home')">Home</a></p>
                <p><a onclick="showSection('products')">Products</a></p>
                <p><a onclick="showSection('contact')">Contact Us</a></p>
                <p><a href="#">Privacy Policy</a></p>
                <p><a href="#">Terms of Service</a></p>
            </div>
        </div>
        <div class="copyright">
            <p>© 2026 FurqanStore. All rights reserved. | Made with <i class="fas fa-heart"></i> in Pakistan</p>
        </div>
    </footer>

    <!-- Cart Modal -->
    <div id="cartModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeCartModal()">&times;</span>
            <h2>Your Cart</h2>
            <div id="cartItems" class="cart-items"></div>
            <div id="cartEmpty" class="empty-state hidden">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Start shopping to add items!</p>
            </div>
            <div id="cartFooter" class="hidden">
                <div class="cart-total">
                    <span>Total:</span>
                    <span id="cartTotalPrice">PKR 0</span>
                </div>
                <button class="checkout-btn" onclick="checkout()">
                    <i class="fas fa-lock"></i> Proceed to Checkout
                </button>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeLoginModal()">&times;</span>
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Welcome Back! 👋</h2>
            <form id="loginForm" onsubmit="handleLogin(event)">
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="loginEmail" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="loginPassword" required placeholder="Enter your password">
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            <p class="modal-footer">
                Don't have an account? 
                <span onclick="switchToSignup()">Create Account</span>
            </p>
        </div>
    </div>

    <!-- Signup Modal -->
    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeSignupModal()">&times;</span>
            <h2 style="text-align: center; margin-bottom: 1.5rem;">Create Account 🎉</h2>
            <form id="signupForm" onsubmit="handleSignup(event)">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Full Name</label>
                    <input type="text" id="signupName" required placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" id="signupEmail" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-phone"></i> Phone Number</label>
                    <input type="tel" id="signupPhone" required placeholder="Enter your phone number">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" id="signupPassword" required placeholder="Create a password (min 6 characters)">
                </div>
                <div class="form-group">
                    <label><i class="fas fa-check-circle"></i> Confirm Password</label>
                    <input type="password" id="signupConfirmPassword" required placeholder="Confirm your password">
                </div>
                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>
            <p class="modal-footer">
                Already have an account? 
                <span onclick="switchToLogin()">Login Here</span>
            </p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Product added to cart!</span>
    </div>

    <script>
        // API Base URL - FIXED for furqan_store_premium
        const API_BASE = window.location.origin + '/furqan_store_premium/api/';
        
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
            
            // Search and filter
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
                    showToast(`Welcome back, ${currentUser.full_name}! 🎉`, 'success');
                    
                    if (currentUser.role === 'admin') {
                        showToast('You are logged in as Admin. Access admin panel from menu.', 'info');
                    }
                }
            } catch (error) {
                console.error('Auth check failed:', error);
            }
        }

        function updateUIForLoggedInUser() {
            const authLinks = document.getElementById('authLinks');
            const adminLink = currentUser.role === 'admin' ? 
                `<a href="/furqan_store_premium/admin/" class="admin-link" target="_blank"><i class="fas fa-crown"></i> Admin Panel</a>` : '';
            
            authLinks.innerHTML = `
                <div class="user-menu">
                    <i class="fas fa-user-circle"></i>
                    <span>${currentUser.full_name}</span>
                    <a onclick="logout()" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    ${adminLink}
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

        // ============== LOGIN HANDLER ==============
        async function handleLogin(event) {
            event.preventDefault();
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;
            
            const submitBtn = event.target.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Logging in...';
            submitBtn.disabled = true;
            
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
                    loadProducts();
                    
                    if (currentUser.role === 'admin') {
                        showToast(`✅ Welcome Admin! You have full control of the store.`, 'success');
                    } else {
                        showToast(`✅ Login successful! Welcome back, ${currentUser.full_name}!`, 'success');
                    }
                    
                    document.getElementById('loginForm').reset();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('Login failed. Please try again.', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // ============== SIGNUP HANDLER ==============
        async function handleSignup(event) {
            event.preventDefault();
            const fullName = document.getElementById('signupName').value;
            const email = document.getElementById('signupEmail').value;
            const phone = document.getElementById('signupPhone').value;
            const password = document.getElementById('signupPassword').value;
            const confirmPassword = document.getElementById('signupConfirmPassword').value;
            
            if (fullName.length < 3) {
                showToast('Name must be at least 3 characters', 'error');
                return;
            }
            
            if (!email.includes('@') || !email.includes('.')) {
                showToast('Please enter a valid email address', 'error');
                return;
            }
            
            if (phone.length < 10) {
                showToast('Please enter a valid phone number', 'error');
                return;
            }
            
            if (password.length < 6) {
                showToast('Password must be at least 6 characters', 'error');
                return;
            }
            
            if (password !== confirmPassword) {
                showToast('Passwords do not match!', 'error');
                return;
            }
            
            const submitBtn = event.target.querySelector('.submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
            submitBtn.disabled = true;
            
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
                    showToast(`🎉 Welcome to Furqan Store, ${fullName}! Your account has been created successfully.`, 'success');
                    document.getElementById('signupForm').reset();
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('Registration failed. Please try again.', 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
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
                    showToast(data.message || 'Failed to load products', 'error');
                }
            } catch (error) {
                console.error('Failed to load products:', error);
                showToast('Failed to load products. Please check connection.', 'error');
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

        // ============== CART ==============
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
                        renderCartItems();
                    }
                }
            } catch (error) {
                console.error('Failed to load cart:', error);
            }
        }

        async function addToCart(productId) {
            if (!currentUser) {
                showToast('Please login to add items to cart', 'error');
                openLoginModal();
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
                } else {
                    showToast(data.message, 'error');
                }
            } catch (error) {
                showToast('Failed to add to cart', 'error');
            }
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

        async function checkout() {
    // Check if user is logged in
    if (!currentUser) {
        showToast('Please login to checkout', 'error');
        openLoginModal();
        return;
    }
    
    // Check if cart has items
    if (!cartItems || cartItems.length === 0) {
        showToast('Your cart is empty! Add items before checkout.', 'error');
        return;
    }
    
    // Get shipping details
    const name = prompt('Enter your full name for delivery:');
    if (!name) {
        showToast('Name is required for delivery', 'error');
        return;
    }
    
    const address = prompt('Enter your complete shipping address:');
    if (!address) {
        showToast('Shipping address is required', 'error');
        return;
    }
    
    const phone = prompt('Enter your phone number:');
    if (!phone) {
        showToast('Phone number is required', 'error');
        return;
    }
    
    // Show loading state
    showToast('Placing your order...', 'info');
    
    try {
        const response = await fetch(`${API_BASE}orders.php`, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ 
                name: name, 
                address: address, 
                phone: phone 
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showToast('🎉 ' + data.message, 'success');
            closeCartModal();
            // Refresh cart and products
            await loadCart();
            await loadProducts();
        } else {
            showToast('❌ ' + data.message, 'error');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showToast('Checkout failed. Please try again.', 'error');
    }
}
        // ============== MODAL FUNCTIONS ==============
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
            document.querySelectorAll('.slide').forEach((slide, i) => {
                slide.classList.toggle('active', i === index);
            });
        }

        function showSection(section) {
            document.getElementById(section).scrollIntoView({ behavior: 'smooth' });
            document.getElementById('navLinks').classList.remove('show');
        }

        // ============== TOAST ==============
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = toast.querySelector('i');
            const text = toast.querySelector('span');
            
            text.innerText = message;
            
            if (type === 'success') {
                icon.className = 'fas fa-check-circle';
                icon.style.color = 'var(--accent)';
            } else if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
                icon.style.color = 'var(--danger)';
            } else {
                icon.className = 'fas fa-info-circle';
                icon.style.color = 'var(--secondary)';
            }
            
            toast.classList.add('show');
            setTimeout(() => toast.classList.remove('show'), 4000);
        }

        // Export to window
        window.showSection = showSection;
        window.changePage = changePage;
        window.addToCart = addToCart;
        window.updateQuantity = updateQuantity;
        window.removeFromCart = removeFromCart;
        window.openCartModal = openCartModal;
        window.closeCartModal = closeCartModal;
        window.openLoginModal = openLoginModal;
        window.closeLoginModal = closeLoginModal;
        window.openSignupModal = openSignupModal;
        window.closeSignupModal = closeSignupModal;
        window.switchToSignup = switchToSignup;
        window.switchToLogin = switchToLogin;
        window.handleLogin = handleLogin;
        window.handleSignup = handleSignup;
        window.nextSlide = nextSlide;
        window.prevSlide = prevSlide;
        window.checkout = checkout;
        window.logout = logout;
    </script>
</body>
</html>