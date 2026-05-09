# 👑 FurqanStore | Premium Luxury E-Commerce

[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Vanilla CSS](https://img.shields.io/badge/Vanilla_CSS-Modern-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![Glassmorphism](https://img.shields.io/badge/Design-Glassmorphism-FF69B4?style=for-the-badge&logo=designernews&logoColor=white)]()

**FurqanStore** is a state-of-the-art, full-stack e-commerce platform designed with a focus on luxury aesthetics and high-performance database architecture. Featuring a stunning glassmorphism UI, a robust PHP backend, and advanced SQL implementation, it provides a seamless shopping experience for customers and a powerful management dashboard for vendors and admins.

---

## ✨ Key Features

### 🎨 Visual Excellence
- **Modern UI/UX**: Premium glassmorphism design with sleek dark mode as the default experience.
- **Interactive Elements**: Animated background with gradient spheres, custom cursor tracking, and smooth transitions.
- **Responsive Design**: Fully optimized for mobile, tablet, and desktop views with a dedicated mobile navigation bar.
- **Dynamic Hero Slider**: Captivating hero section showcasing luxury collections and trending products.

### 🏪 E-Commerce Core
- **Multi-Role System**: Distinct workflows for **Superadmins**, **Vendors**, and **Customers**.
- **Product Discovery**: Advanced filtering by categories and vendors, dynamic search, and multiple sorting options (Price, Rating, etc.).
- **Shopping Cart**: Fully functional cart with persistent state, real-time total calculation, and a seamless checkout flow.
- **Support Center**: Integrated FAQ section and a support ticket system with automated email/UI feedback.

### ⚙️ Backend & Database
- **RESTful API**: Custom-built PHP API endpoints for dynamic product loading and filtering.
- **Advanced SQL Architecture**:
  - **Triggers**: Automated inventory management (e.g., low-stock warnings, stock protection).
  - **Stored Procedures**: Optimized data operations for complex queries.
  - **Views**: Simplified data access for reports and analytics.
  - **Transactions**: Ensures data integrity during order placement and inventory updates.
- **Authentication**: Secure registration and login system, including demo account access for testing.

---

## 💾 Database Architecture & Advanced Queries

This project demonstrates proficiency in advanced database management using **MySQL**. Below are key implementations of various SQL features:

### 1. Complex Joins & Views
We use views to simplify complex queries and provide consistent data interfaces across the application.
- **`v_product_details`**: A 3-way join combining `products`, `categories`, and `users` (vendors) to provide a unified product profile.
- **`v_order_summary`**: Joins `orders`, `users`, and `order_items` with a `GROUP BY` clause to calculate order totals and item counts.

### 2. Aggregations & Analytics
The dashboard utilizes aggregation functions (`SUM`, `COUNT`, `AVG`) combined with `HAVING` and `ORDER BY` for reporting.
```sql
-- Example: Vendor Performance Report
SELECT 
    u.name as vendor_name,
    COUNT(oi.id) as total_units_sold,
    SUM(oi.vendor_earning) as total_earnings
FROM users u
JOIN order_items oi ON u.id = oi.vendor_id
GROUP BY u.id
HAVING total_earnings > 100
ORDER BY total_earnings DESC;
```

### 3. Automated Logic (Triggers)
Automated workflows are handled directly by the database to ensure data integrity.
- **Before Insert Guard**: Prevents orders if a product is out of stock or if the requested quantity exceeds availability.
- **After Insert Automation**: Automatically deducts stock levels after a successful order and dynamically updates product badges (`low_stock`, `out_of_stock`).

### 4. Stored Procedures & Subqueries
- **Subqueries**: We use correlated and non-correlated subqueries for complex data retrieval, such as identifying top-selling products:
```sql
SELECT name, price 
FROM products 
WHERE id IN (
    SELECT product_id 
    FROM order_items 
    GROUP BY product_id 
    ORDER BY SUM(quantity) DESC
) LIMIT 5;
```
- **Stored Procedures**: Procedures like `sp_get_user_orders` encapsulate business logic, allowing for efficient execution of parameterized queries from the PHP layer.

### 5. ACID Transactions & Security
- **Transactions**: Implemented during the checkout process to ensure atomicity. If any part of the order fails (e.g., payment or stock update), the entire transaction is rolled back.
- **Prepared Statements**: All queries are executed using PHP Data Objects (PDO) or MySQLi prepared statements to prevent SQL injection attacks.

---


## 🚀 Installation & Setup

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed on your system.

### Steps
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/furqanstore.git
   ```
2. **Move to Web Directory**:
   Copy the `furqanstore` folder to your `C:\xampp\htdocs\` directory.
3. **Database Configuration**:
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
   - Create a new database named `furqanstore_db`.
   - Import the following SQL file 
     1. `furqanstore_db full sql code.sql` 
2. **Configure Connection**:
   Update `config/db_connect.php` (if necessary) with your database credentials.
3. **Run the Project**:
   Start Apache and MySQL in XAMPP and visit `http://localhost/furqanstore`.

---

## 🔑 Demo Accounts

To explore the platform without creating an account, use these pre-configured credentials:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Superadmin** | `superadmin@furqan.com` | `password` |
| **Vendor** | `vendor@furqan.com` | `password` |
| **Customer** | `customer@furqan.com` | `password` |

---

## 📸 Screenshots



## 📜 License
Distributed under the MIT License. See `LICENSE` for more information.

---

## 📧 Contact
**Project Owner** - Muhammad Furqan(furqan209@gmail.com)


