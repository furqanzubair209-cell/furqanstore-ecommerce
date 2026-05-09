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
Images of project in light mode to watch project in dark mode watch vedio:



<img width="1366" height="617" alt="1000048481" src="https://github.com/user-attachments/assets/662a068f-2c44-4d0b-b1d4-469aff3f952f" />
<img width="1366" height="615" alt="1000048482" src="https://github.com/user-attachments/assets/9ebde347-2865-48cd-ab56-e4ee34d9d14d" />
<img width="1366" height="606" alt="1000048483" src="https://github.com/user-attachments/assets/74abc4f9-682b-4b85-aa3f-e8887e21d78a" />
<img width="1347" height="617" alt="1000048484" src="https://github.com/user-attachments/assets/c80bb429-a228-4dc8-9051-7e32a3724252" />
<img width="1366" height="615" alt="1000048485" src="https://github.com/user-attachments/assets/9675b5c9-f6a9-438f-8cbf-c3fc8e6dcc11" />
<img width="1364" height="613" alt="1000048486" src="https://github.com/user-attachments/assets/eb334cae-5f96-41c1-a56a-2c85c44fced8" />
<img width="1366" height="617" alt="1000048487" src="https://github.com/user-attachments/assets/d5d8d8bc-6c67-4cff-9a88-8fc14edcc101" />
<img width="1366" height="597" alt="1000048488" src="https://github.com/user-attachments/assets/9a694499-2cd0-4079-b992-0498a7ec3de5" />
<img width="1366" height="633" alt="1000048489" src="https://github.com/user-attachments/assets/7cc29ebc-6100-4387-bea2-fea1e2a63c2b" />
<img width="1366" height="611" alt="1000048490" src="https://github.com/user-attachments/assets/4c5c141e-d93a-4864-815d-a1a377e0e277" />
<img width="1366" height="626" alt="1000048491" src="https://github.com/user-attachments/assets/808f7ab3-4670-425b-9b13-de3a344b85a5" />
<img width="1366" height="617" alt="1000048492" src="https://github.com/user-attachments/assets/76c072ad-02b8-49a6-b907-53ee1644ffd0" />
<img width="1365" height="615" alt="1000048493" src="https://github.com/user-attachments/assets/a2d1988d-8e71-40b8-ad05-de6dfa962958" />
<img width="1360" height="626" alt="1000048494" src="https://github.com/user-attachments/assets/0f148e1d-710a-4be6-8118-52384db198c7" />
<img width="1364" height="624" alt="1000048495" src="https://github.com/user-attachments/assets/6cee545e-3161-4dd3-8e6c-ea43177f2625" />
<img width="1362" height="657" alt="1000048496" src="https://github.com/user-attachments/assets/ebff67ce-a4f0-402f-a286-07eafa745b49" />
<img width="1362" height="628" alt="1000048497" src="https://github.com/user-attachments/assets/dd83e3e6-e263-4d7c-a081-cb5dfabd2538" />
<img width="1366" height="615" alt="1000048498" src="https://github.com/user-attachments/assets/6fd644bc-4f1f-4b3c-a1b6-568438373073" />
<img width="1366" height="608" alt="1000048499" src="https://github.com/user-attachments/assets/38474e82-402b-4dad-b878-c8673e1e2632" />
<img width="1366" height="624" alt="1000048500" src="https://github.com/user-attachments/assets/c902cccd-69d9-4b73-a338-683de3493728" />
<img width="1366" height="629" alt="1000048501" src="https://github.com/user-attachments/assets/9cb33389-30f9-412f-9c07-2fd135385dc4" />
<img width="1364" height="617" alt="1000048502" src="https://github.com/user-attachments/assets/5c1292eb-4bf2-46a1-add7-8ef7af6b0960" />
<img width="1356" height="608" alt="1000048503" src="https://github.com/user-attachments/assets/12be985a-a23a-424f-a75a-65cf2687a20a" />
<img width="1366" height="611" alt="1000048504" src="https://github.com/user-attachments/assets/787db476-260f-4755-ad92-7416ae708d20" />
<img width="1366" height="622" alt="1000048505" src="https://github.com/user-attachments/assets/d4d49075-ad7e-4ed8-b2c3-c528efa9aaf3" />
<img width="1366" height="613" alt="1000048506" src="https://github.com/user-attachments/assets/bc81cf31-9996-422e-a40e-5872c4796f2a" />
<img width="1366" height="619" alt="1000048507" src="https://github.com/user-attachments/assets/2deef28b-d6eb-4a08-9d1e-e43fa8d1d4a4" />
<img width="1358" height="628" alt="1000048508" src="https://github.com/user-attachments/assets/73c3f6e9-16fe-47ae-895b-45616557ea63" />

<img width="1366" height="619" alt="1000048509" src="https://github.com/user-attachments/assets/3dd9330a-7525-4a77-973d-08d393fa103c" />
<img width="1362" height="624" alt="1000048510" src="https://github.com/user-attachments/assets/87629f1f-d714-40e9-8d07-7e73843afb33" />


<img width="1362" height="615" alt="1000048511" src="https://github.com/user-attachments/assets/afed30f5-82e1-446b-bbe1-64c3501c37bb" />
<img width="1360" height="626" alt="1000048512" src="https://github.com/user-attachments/assets/9ae369a6-9acb-4473-970d-dfb190f782b0" />
<img width="1364" height="619" alt="1000048513" src="https://github.com/user-attachments/assets/664421c3-6d68-486c-81bb-a14c65de2dd8" />
<img width="1364" height="628" alt="1000048514" src="https://github.com/user-attachments/assets/763e1958-fc43-4e92-bcd0-c2988154fc01" />
<img width="1364" height="613" alt="1000048516" src="https://github.com/user-attachments/assets/b6ae2bd6-d454-4f95-8b05-a787b9e1c6e4" />


## 📜 License
Distributed under the MIT License. See `LICENSE` for more information.

---

## 📧 Contact
**Project Owner** - Muhammad Furqan(furqan209@gmail.com)


