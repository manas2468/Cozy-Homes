🏠 Cozy Homes – Home Supplies E-Commerce Website

**Cozy Homes** is a PHP-based web application designed to sell essential home supplies online.
The platform allows users to browse products, register/login, and place orders for household items in a simple and user-friendly interface.


## 📌 Project Description

Cozy Homes is an online store focused on providing everyday home essentials such as:

* Kitchen supplies
* Home décor
* Cleaning products
* Furniture essentials
* Storage items

This project demonstrates the implementation of an e-commerce workflow using core PHP and MySQL.


## ✨ Features

* User registration and login system
* Session-based authentication
* Product listing and browsing
* Add-to-cart functionality
* Order placement system
* Contact form for customer inquiries
* Responsive and simple UI


## 🧰 Tech Stack

* **Frontend:** HTML, CSS
* **Backend:** PHP
* **Database:** MySQL
* **Server:** Apache (XAMPP / WAMP / LAMP)


## 📂 Project Structure

```
/cozy-homes
│
├── index.php              # Homepage
├── login.php              # Login system
├── register.php           # User registration
├── logout.php             # Logout logic
├── user.php               # User dashboard
│
├── products.php           # Product listing page
├── cart.php               # Shopping cart
├── checkout.php           # Order placement
│
├── save_contact.php       # Contact form handler
├── insert.php             # Add product/admin actions
│
└── README.md
```

---

## ⚙️ Installation & Setup

### 1️⃣ Move Project to Server Folder

* XAMPP → `htdocs/`
* WAMP → `www/`


### 2️⃣ Create Database

Open phpMyAdmin and create:

```
cozy_homes
```



### 3️⃣ Create Tables

#### Users

```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255)
);
```

#### Products

```sql
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150),
    price DECIMAL(10,2),
    description TEXT,
    image VAR
```
