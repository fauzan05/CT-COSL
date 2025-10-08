# CT-COSL - Admin Dashboard

![Laravel Version](https://img.shields.io/badge/Laravel-v12.0-FF2D20?logo=laravel)
![Vue Version](https://img.shields.io/badge/Vue.js-v3.0-4FC08D?logo=vue.js)
![License](https://img.shields.io/badge/license-MIT-green.svg)

## 📋 Table of Contents
- [Overview](#-overview)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Prerequisites](#-prerequisites)
- [Installation](#-installation)
- [Development Commands](#-development-commands)
- [Project Structure](#-project-structure)
- [Troubleshooting](#️-troubleshooting)
- [Default Credentials](#-default-credentials)
- [License](#license)
- [Contact](#contact)

## 🚀 Overview
This project built with Laravel 12 and Vue.js 3. Here we are to explain its purpose and main functionalities.

## ✨ Features
- 🔐 Authentication & Authorization
  - Standard Laravel Authentication
  - Role & permission management (not yet complete)
  - Multi-factor authentication (not yet complete)
- 📊 Dashboard Analytics
  - Real-time data visualization (not yet complete)
  - Custom reporting system (complete in toolstring & wellstack)
  - Export functionality (PDF only)
- 👥 User Management
  - CRUD operations
  - Role assignment (not yet complete)
  - Activity logging
- 🎨 UI/UX Features
  - Responsive design
  - Dark/Light theme
  - Dynamic layouts
  - Real-time notifications (not yet complete)

## 💻 Tech Stack
### Backend
- Laravel 12.x
- PHP 8.2+
- MySQL/MariaDB

### Frontend
- Vue.js 3
- Vue Router 4
- Pinia (state management)
- Tailwind CSS
- Vite

### DevOps & Tools
- Docker
- MAMP
- XAMPP
- LAMPP

## 📋 Prerequisites
```bash
PHP >= 8.2
Composer
Node.js >= 22.x
npm or yarn
MySQL/MariaDB
```

# CT-COSL Dashboard - Development Installation Guide

## 🔧 Installation

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js 22.x or higher
- MySQL 8.0 or higher
- Git

### Step-by-Step Installation

#### 1️⃣ Clone the repository
```bash
git clone https://github.com/fauzan05/CT-COSL.git
cd ct-cosl-dashboard
```

#### 2️⃣ Install PHP dependencies
```bash
composer install
```

#### 3️⃣ Copy and configure environment
```bash
cp .env.example .env
```

Edit the `.env` file with your database credentials and application settings:

```env
APP_NAME=CT-COSL
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
ASSET_URL=http://localhost:8000
VITE_API_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_cosl
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### 4️⃣ Generate application key
```bash
php artisan key:generate
```

#### 5️⃣ Create database and run migrations
Make sure your MySQL server is running and create the database:
```sql
CREATE DATABASE ctcosl_db;
```

Then run migrations and seeders:
```bash
php artisan migrate --seed
```

#### 6️⃣ Install Node.js dependencies
```bash
npm install
```

#### 7️⃣ Build frontend assets
For development with hot reload:
```bash
npm run dev
```

For production build:
```bash
npm run build
```

#### 8️⃣ Create storage symlink
```bash
php artisan storage:link
```

#### 9️⃣ Set proper permissions (Linux/Mac)
```bash
sudo chown -R www-data:www-data storage
sudo chown -R www-data:www-data bootstrap/cache
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

#### 🔟 Serve the application
```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

## 🚀 Development Commands

### Frontend Development
```bash
# Start development server with hot reload
npm run dev

# Build for production
npm run build

# Watch for changes
npm run watch
```

### Backend Development
```bash
# Start Laravel development server
php artisan serve

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Seed database
php artisan db:seed

# Generate IDE helper files
php artisan ide-helper:generate
php artisan ide-helper:models
```

## 📂 Project Structure

```
ct-cosl-dashboard/
├── app/                    # Application logic
├── bootstrap/              # Bootstrap files
├── config/                 # Configuration files
├── database/               # Database migrations and seeders
├── public/                 # Public assets
├── resources/              # Views, CSS, JS
├── routes/                 # Route definitions
├── storage/                # Storage files
├── tests/                  # Test files
├── vendor/                 # Composer dependencies
├── .env                    # Environment variables
├── composer.json           # PHP dependencies
├── package.json            # Node.js dependencies
└── README.md              # This file
```

## 🛠️ Troubleshooting

### Common Issues

**1. Permission denied errors**
```bash
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

**2. Database connection issues**
- Make sure MySQL is running
- Check database credentials in `.env`
- Verify database exists

**3. Node.js compilation errors**
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules and reinstall
rm -rf node_modules
npm install
```

**4. Laravel key not set**
```bash
php artisan key:generate
```

## 📝 Default Credentials

After running seeders, you can login with (you can see in AdminSeeder file):
- **Username:** admin123
- **Password:** Rahasia123#


For more detailed documentation, visit the [Laravel Documentation](https://laravel.com/docs) and [Vue.js Documentation](https://vuejs.org/guide/).

> **⚠️ WARNING:** This guide is for development installation only. For production installation, you can adjust it to your server configuration.
