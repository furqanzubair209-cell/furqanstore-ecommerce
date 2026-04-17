-- ============================================
-- DATABASE: furqanstore_db
-- Complete E-commerce Database
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS furqanstore_db;
USE furqanstore_db;

-- ============================================
-- TABLE: users (all user roles)
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'vendor', 'customer') DEFAULT 'customer',
    status ENUM('active', 'pending', 'suspended') DEFAULT 'pending',
    vendor_name VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: categories
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL,
    icon VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- TABLE: products
-- ============================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 0,
    image_url VARCHAR(500) DEFAULT NULL,
    category_id INT,
    vendor_id INT NOT NULL,
    rating DECIMAL(2,1) DEFAULT 0,
    reviews INT DEFAULT 0,
    badge VARCHAR(20) DEFAULT NULL,
    status ENUM('active', 'inactive', 'pending') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: cart_items
-- ============================================
CREATE TABLE IF NOT EXISTS cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: orders
-- ============================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_method VARCHAR(50) DEFAULT 'cod',
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: order_items
-- ============================================
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    vendor_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    vendor_earning DECIMAL(10,2) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- TABLE: contact_messages
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- INSERT SAMPLE CATEGORIES
-- ============================================
INSERT INTO categories (name, slug, icon) VALUES
('Electronics', 'electronics', 'fas fa-laptop'),
('Fashion', 'fashion', 'fas fa-tshirt'),
('Footwear', 'footwear', 'fas fa-shoe-prints'),
('Audio', 'audio', 'fas fa-headphones'),
('Appliances', 'appliances', 'fas fa-blender'),
('Sports', 'sports', 'fas fa-bicycle');

-- ============================================
-- INSERT SAMPLE USERS
-- Password for all demo users is: password
-- ============================================
INSERT INTO users (full_name, email, phone, password, role, status, vendor_name) VALUES
('Super Admin', 'superadmin@furqan.com', '03000000000', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active', NULL),
('Admin User', 'admin@furqan.com', '03000000001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active', NULL),
('Nike Store', 'vendor@furqan.com', '03000000002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'vendor', 'active', 'Nike Store'),
('Customer', 'customer@furqan.com', '03000000003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', 'active', NULL);

-- ============================================
-- INSERT SAMPLE PRODUCTS
-- ============================================
INSERT INTO products (name, price, stock, image_url, category_id, vendor_id, rating, reviews, badge, status) VALUES
('Premium Sneakers', 7999, 50, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400', 3, 3, 4.5, 128, 'hot', 'active'),
('Wireless Headphones', 12499, 30, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400', 4, 3, 4.8, 256, 'best', 'active'),
('Smart Watch Pro', 15999, 25, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400', 1, 3, 4.6, 89, 'new', 'active'),
('Bluetooth Speaker', 6999, 40, 'https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=400', 4, 3, 4.3, 67, NULL, 'active'),
('Leather Backpack', 8999, 35, 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400', 2, 3, 4.7, 45, 'sale', 'active'),
('Mechanical Keyboard', 10999, 20, 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400', 1, 3, 4.9, 312, 'hot', 'active'),
('4K Ultra HD TV', 84999, 10, 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400', 1, 3, 4.7, 156, NULL, 'active'),
('Gaming Laptop', 149999, 15, 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?w=400', 1, 3, 4.9, 203, 'best', 'active'),
('DSLR Camera', 124999, 8, 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=400', 1, 3, 4.8, 98, NULL, 'active'),
('Fitness Tracker', 8999, 45, 'https://images.unsplash.com/photo-1576243336142-81d589a81b80?w=400', 6, 3, 4.2, 67, NULL, 'active'),
('Tablet Pro', 45999, 20, 'https://images.unsplash.com/photo-1561154464-82e9adf32764?w=400', 1, 3, 4.6, 134, NULL, 'active'),
('Noise Cancelling Earbuds', 18999, 30, 'https://images.unsplash.com/photo-1590658165737-15a047b8b5e3?w=400', 4, 3, 4.7, 178, 'new', 'active'),
('Designer Sunglasses', 12999, 25, 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400', 2, 3, 4.4, 56, NULL, 'active'),
('Leather Jacket', 18999, 15, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400', 2, 3, 4.6, 89, 'sale', 'active'),
('Classic Wristwatch', 22999, 12, 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=400', 2, 3, 4.8, 145, 'best', 'active'),
('Formal Dress Shoes', 15999, 28, 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=400', 3, 3, 4.5, 78, NULL, 'active'),
('Designer Handbag', 34999, 10, 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=400', 2, 3, 4.7, 92, NULL, 'active'),
('Casual T-Shirt Pack', 4999, 50, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400', 2, 3, 4.3, 234, NULL, 'active'),
('Winter Coat', 28999, 8, 'https://images.unsplash.com/photo-1551028711-2f6d4a3a3b47?w=400', 2, 3, 4.6, 67, NULL, 'active'),
('Sports Shoes', 11999, 35, 'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=400', 3, 3, 4.7, 156, 'hot', 'active'),
('Coffee Maker', 24999, 20, 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400', 5, 3, 4.5, 89, NULL, 'active'),
('Air Purifier', 18999, 15, 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400', 5, 3, 4.4, 56, NULL, 'active'),
('Blender', 12999, 30, 'https://images.unsplash.com/photo-1553531384-397c80973a0b?w=400', 5, 3, 4.3, 78, NULL, 'active'),
('Robot Vacuum', 54999, 10, 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400', 5, 3, 4.6, 112, NULL, 'active'),
('Microwave Oven', 32999, 12, 'https://images.unsplash.com/photo-1599580420607-6d68eb6ba6b6?w=400', 5, 3, 4.4, 67, NULL, 'active'),
('Electric Kettle', 5999, 45, 'https://images.unsplash.com/photo-1514228742587-6b1558fcf93a?w=400', 5, 3, 4.2, 145, NULL, 'active'),
('Mountain Bike', 89999, 5, 'https://images.unsplash.com/photo-1532298229144-0ec0c57515c7?w=400', 6, 3, 4.8, 67, NULL, 'active'),
('Camping Tent', 29999, 12, 'https://images.unsplash.com/photo-1504851149312-7a075b496cc7?w=400', 6, 3, 4.5, 45, NULL, 'active'),
('Yoga Mat', 4999, 60, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?w=400', 6, 3, 4.3, 234, NULL, 'active'),
('Dumbbell Set', 18999, 25, 'https://images.unsplash.com/photo-1534367507877-0edd93bd013b?w=400', 6, 3, 4.6, 89, NULL, 'active');

-- ============================================
-- UPDATE CATEGORY IDs
-- ============================================
UPDATE products SET category_id = 1 WHERE name IN ('Smart Watch Pro', 'Mechanical Keyboard', '4K Ultra HD TV', 'Gaming Laptop', 'DSLR Camera', 'Tablet Pro');
UPDATE products SET category_id = 2 WHERE name IN ('Leather Backpack', 'Designer Sunglasses', 'Leather Jacket', 'Classic Wristwatch', 'Designer Handbag', 'Casual T-Shirt Pack', 'Winter Coat');
UPDATE products SET category_id = 3 WHERE name IN ('Premium Sneakers', 'Formal Dress Shoes', 'Sports Shoes');
UPDATE products SET category_id = 4 WHERE name IN ('Wireless Headphones', 'Bluetooth Speaker', 'Noise Cancelling Earbuds');
UPDATE products SET category_id = 5 WHERE name IN ('Coffee Maker', 'Air Purifier', 'Blender', 'Robot Vacuum', 'Microwave Oven', 'Electric Kettle');
UPDATE products SET category_id = 6 WHERE name IN ('Fitness Tracker', 'Mountain Bike', 'Camping Tent', 'Yoga Mat', 'Dumbbell Set');
