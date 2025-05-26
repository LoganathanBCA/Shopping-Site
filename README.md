
# 🛍️ Shopping Site with Sentiment Analysis

[![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](f)  
[![Tech Stack](https://img.shields.io/badge/Tech-HTML%2C%20CSS%2C%20JS%2C%20PHP%2C%20MySQL-blue)](f)  
[![Responsive](https://img.shields.io/badge/Responsive-Yes-success)](f)  
[![Sentiment Analysis](https://img.shields.io/badge/Sentiment-VADER%20in%20PHP-yellow)](f)

> A complete E-Commerce website enhanced with **Sentiment Analysis** of customer reviews using **VADER in PHP**.

---

## 🔑 Key Features

- 🌐 **Responsive Design** — Smooth UX across devices using Bootstrap  
- 🛍️ **Product Listings & Categories** — Filter, search, and detailed product pages  
- 💬 **Customer Reviews + Sentiment** — Analyzes review tone (positive/negative/neutral)  
- 👥 **Role-Based Access**:  
  - **Customers** — Register, login, shop, submit reviews  
  - **Admins** — Add/manage products, view orders & sentiment insights  
- 📈 **Analytics Dashboard** — Track sentiment trends and review scores  
- 💳 **Payment Gateway Integration** (Stripe) — Easy plug-in support  

---

## 🛠️ Tech Stack

| Layer      | Tech Used                                  |
|------------|--------------------------------------------|
| Frontend   | HTML, CSS, Bootstrap, JavaScript           |
| Backend    | PHP, MySQL                                 |
| Analysis   | VADER sentiment analysis (native PHP)      |
| Styling    | Bootstrap                                  |
| Hosting    | XAMPP / LAMP stack                         |

---

## 📦 Installation & Setup

1. **Clone the Repository:**

   ```bash
   git clone https://github.com/LoganathanBCA/Shopping-Site.git


2. **Import the Database:**

   * Open `phpMyAdmin`
   * Create a DB named `ecommerce`
   * Import `ecommerce.sql` from the `database/` folder

3. **Configure `db.php`:**

   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $dbname = "ecommerce";
   ```

4. **Start the Server:**

   * Open XAMPP / MAMP / LAMP
   * Start **Apache** and **MySQL**
   * Visit `http://localhost/shopping-site/`

---

## 📊 Sentiment Analysis in PHP

* **VADER (Valence Aware Dictionary for sEntiment Reasoning)** is integrated using a custom PHP port.
* Each user review is scored:

  * `Positive` (> 0.05)
  * `Neutral` (-0.05 to 0.05)
  * `Negative` (< -0.05)
* Admins see charts and categorized results in the dashboard.

---

## 🔐 Admin Panel

* URL: `/admin/`
* **Default Credentials**:

  * Username: `admin`
  * Password: `admin`

---

## 📝 License

This project is licensed under the **MIT License** – see the [LICENSE](f) file for details.

---

## 🤝 Contribution

Pull requests are welcome! For major changes, open an issue first to discuss what you'd like to change.

---

## 📢 About This Project

**Created by Loganathan M, M.Sc. Computer Science, Government Arts College (Autonomous), Salem-7, as part of Master's degree completion —  feel free to connect via [Portfolio Website](https://loganathanbca.github.io/Portfolio/).**
