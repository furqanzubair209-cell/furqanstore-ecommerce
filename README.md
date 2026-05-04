**FurqanStore** is a state-of-the-art, premium luxury e-commerce platform designed to provide a "Wow" factor through its cinematic UI and seamless user experience. Built with a robust PHP/MySQL backend and a modern vanilla frontend, it offers a full-featured marketplace for vendors and customers alike.

---

## ✨ Key Features

### 🎨 Visual Excellence
- **Cinematic UI**: Stunning glassmorphism effects, vibrant gradients, and smooth micro-animations.
- **Dynamic Dark Mode**: A premium dark-themed interface that feels alive and interactive.
- **Responsive Design**: Fully optimized for Desktop, Tablet, and Mobile devices.
- **Custom Cursor & Interactions**: High-end interactive elements that react to user movement.

### 🏪 Marketplace Functionality
- **Multi-Vendor Support**: Dedicated dashboards for vendors to manage products and track orders.
- **Advanced Product Filtering**: Search and filter by categories, vendors, price, and ratings.
- **Shopping Cart System**: Real-time cart management with a sleek slide-out modal.
- **Product Gallery**: High-quality product displays with detailed information.

### 🛠 Administrative & Security
- **Secure Authentication**: Multi-role login system (Super Admin, Vendor, Customer).
- **Role-Based Access Control**: Protected routes and dashboards for different user types.
- **Support Center**: Integrated FAQ and support ticket system for customer assistance.
- **Database Security**: PDO/MySQLi prepared statements to prevent SQL injection.

---

## 🚀 Tech Stack

- **Frontend**: 
  - HTML5 & CSS3 (Vanilla)
  - JavaScript (ES6+)
  - FontAwesome & Google Fonts (Plus Jakarta Sans)
- **Backend**: 
  - PHP 8.x
  - MySQL Database
- **Server**: 
  - XAMPP / Apache

---

## 🛠 Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or any PHP/MySQL local server environment.

### Installation Steps
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/your-username/furqanstore.git
   ```
2. **Move to Server Directory**:
   Copy the `furqanstore` folder to your `C:\xampp\htdocs\` directory.
3. **Database Setup**:
   - Open **phpMyAdmin**.
   - Create a new database named `furqanstore_db`.
   - Import the database schema (if provided) or create the necessary tables.
4. **Configuration**:
   - Open `config/db.php`.
   - Update `DB_USER` and `DB_PASS` to match your local MySQL credentials.
5. **Launch**:
   - Start Apache and MySQL in XAMPP.
   - Visit `http://localhost/furqanstore/` in your browser.

---

## 👥 User Roles & Access

| Role | Access Level | Responsibilities |
| :--- | :--- | :--- |
| **Super Admin** | Full Access | Manage users, vendors, and site-wide settings. |
| **Vendor** | Merchant Access | Upload products, manage inventory, view orders. |
| **Customer** | User Access | Browse shop, manage cart, track orders, support. |

---

## 📂 Project Structure

```text
furqanstore/
├── actions/      # PHP logic for various operations
├── admin/        # Admin panel & management scripts
├── api/          # API endpoints for dynamic data
├── auth/         # Login, Register, and Session logic
├── config/       # Database & Site configurations
├── vendor/       # Vendor-specific dashboards & orders
├── script.js     # Main frontend logic & animations
├── style.css     # Global premium styles & theme
├── index.php     # Main landing page
└── README.md     # Project documentation
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👨‍💻 Developer

Developed with ❤️ by **Furqan**.

> "Elevating the e-commerce experience, one pixel at a time."
