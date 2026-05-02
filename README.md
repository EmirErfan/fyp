# Laravel Project Name

<p align="center">
  <img src="https://picperf.io/https://laravelnews.s3.amazonaws.com/images/laravel-featured.png" width="200" alt="Laravel Logo">
</p>

## 📌 Project Overview
Provide a brief description of what this application does. Mention the core features and the primary goal of the project.

---

## 🚀 Getting Started

Follow these steps to get your development environment synchronized and running.

### 1. Prerequisites
Ensure you have the following installed:
*   **PHP** (>= 8.2 recommended)
*   **Composer**
*   **Node.js & NPM**
*   **MySQL**

### 2. Installation
```bash
# Clone the repository
git clone https://github.com/EmirErfan/fyp.git

# Navigate to the directory
cd fyp

# Install PHP dependencies
composer install

# Install and build frontend assets
npm install && npm run build

# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=create_any_database_name_you_want
DB_USERNAME=root
DB_PASSWORD=