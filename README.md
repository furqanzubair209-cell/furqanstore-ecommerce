
# 🛒 Furqan Store - Complete E-Commerce Platform

A fully functional, production-ready e-commerce web application built with **PHP, MySQL, HTML, CSS, and JavaScript**. The platform supports both **customers** (shopping) and **administrators** (store management).

---

## 🚀 Features

### 👤 Customer Features
- 🔐 User Authentication (Login & Registration with password hashing)
- 🛍️ Browse Products with categories
- 🔍 Search & Filter functionality
- 🛒 Shopping Cart (Add, Update, Remove items)
- 📦 Checkout with shipping details
- ✅ Order confirmation with toast notifications
- 📱 Fully Responsive UI (Desktop, Tablet, Mobile)

### 👑 Admin Features
- 📊 Admin Dashboard with statistics
- 📦 Product Management (Add, Edit, Delete products)
- 🛒 Order Management (View & Update order status)
- 👥 User Management (View & Delete users)
- 💰 Revenue tracking
- 🔒 Separate admin panel access (`/admin`)

---

## 💻 Tech Stack

| Layer | Technology |
|-------|------------|
| **Frontend** | HTML5, CSS3, JavaScript |
| **Backend** | PHP (RESTful APIs) |
| **Database** | MySQL |
| **Server** | XAMPP / Apache / WAMP |

---

## 🗄️ Database Structure

| Table | Description |
|-------|-------------|
| `users` | User accounts (admin & customers) |
| `products` | Product catalog (44+ products) |
| `cart` | Shopping cart items |
| `orders` | Order records |
| `order_items` | Products inside each order |
| `wishlist` | Saved items (optional) |

---

## 🔧 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/products.php` | Fetch products (with filters) |
| POST | `/api/auth.php?action=login` | User login |
| POST | `/api/auth.php?action=register` | User registration |
| POST | `/api/cart.php` | Add item to cart |
| PUT | `/api/cart.php` | Update cart quantity |
| DELETE | `/api/cart.php` | Remove item from cart |
| POST | `/api/orders.php` | Place order |

---

## 📁 Project Structure

```
furqan_store_premium/
├── index.php              # Main customer website
├── config/
│   └── db.php             # Database connection
├── api/                   # REST API endpoints
│   ├── auth.php           # Login/Register
│   ├── products.php       # Product CRUD
│   ├── cart.php           # Cart operations
│   └── orders.php         # Order processing
├── admin/                 # Admin Panel
│   ├── index.php          # Dashboard
│   ├── products.php       # Product management
│   ├── orders.php         # Order management
│   └── users.php          # User management
└── assets/
    ├── css/               # Stylesheets
    └── js/                # JavaScript files
```

---

## 🔐 Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | `admin@furqanstore.com` | `password` or `admin123` |
| **Customer** | Register via Sign Up form | User-created |

---

## 🚦 How to Run Locally

1. **Install XAMPP/WAMP** and start Apache & MySQL
2. **Clone or download** the project to `C:\xampp\htdocs\furqan_store_premium\`
3. **Import database** using the provided SQL file in phpMyAdmin
4. **Update database credentials** in `config/db.php`
5. **Access the website**: `http://localhost/furqan_store_premium/`
6. **Admin Panel**: `http://localhost/furqan_store_premium/admin/`

---

## 🧠 What I Learned

- Full-stack application development from scratch
- Building RESTful APIs in PHP
- Database design & foreign key relationships
- Session-based authentication
- Security practices (password hashing, input validation, SQL injection prevention)
- Connecting frontend JavaScript with backend APIs
- Admin panel implementation
- Responsive UI design

---

## 📌 Future Improvements

- 💳 Payment gateway integration (Stripe/PayPal)
- 📧 Email order confirmation
- 📊 Advanced analytics for admin
- 🖼️ Image upload for products
- 📱 Mobile app version

---

## 🤝 Connect

If you liked this project, feel free to ⭐ star this repository and connect with me!

---

## 🏷️ Tags

#WebDevelopment #FullStack #PHP #MySQL #Ecommerce #JavaScript #AdminPanel #PortfolioProject #RESTAPI

---

This README now accurately reflects your complete project with both **customer** and **admin** functionalities! 🎯
