# 🏆 Sports Academy Management System

A modern Sports Academy Management System built with **Laravel**. This application helps sports academies efficiently manage players, coaches, batches, fees, roles,  and permissions from a centralized dashboard.

---

## 📌 Features

### 🔐 Authentication
- Login & Logout
- Forgot Password
- Change Password
- Profile Management

### 👥 User Management
- Create User
- Edit User
- Delete User
- View User Details
- Activate/Deactivate User
- Role Assignment

### 🛡️ Role & Permission Management
- Role Management
- Permission Management
- Module-wise Permissions
- Access Control using Spatie Laravel Permission
- Admin Full Access

### 🏃 Player Management
- Add Player
- Edit Player
- Delete Player
- Player Details
- Player Status

### 🧑‍🏫 Coach Management
- Add Coach
- Edit Coach
- Delete Coach
- Assign Coach to Batch

### 🏅 Sports Management
- Add Sport
- Edit Sport
- Delete Sport

### 📚 Level Management
- Beginner
- Intermediate
- Advanced

### 🗓️ Batch Management
- Create Batch
- Assign Coach
- Assign Sport
- Assign Level
- Batch Capacity
- Batch Schedule

### 💰 Fee Management
- Monthly Fees
- Fee Collection
- Pending Fees
- Payment History

### 🎁 Discount Management
- Percentage Discount
- Fixed Discount
- Discount Configuration

### ⚠️ Penalty Management
- Late Payment Penalty
- Penalty Configuration

### 📤 Import & Export
- Excel Import
- Excel Export
- Export Multiple Modules
- Single Sheet Export

### 📈 Dashboard
- Total Users
- Total Players
- Total Coaches
- Total Sports
- Total Batches
- Attendance Summary
- Revenue Overview

---

# 🚀 Technology Stack

- Laravel
- PHP 8+
- MySQL
- Bootstrap 5
- CoreUI
- jQuery
- AJAX
- Yajra DataTables
- Spatie Laravel Permission
- Laravel Excel (Maatwebsite)

---

# 📦 Packages Used

- spatie/laravel-permission
- maatwebsite/excel
- yajra/laravel-datatables
- laravel/ui or breeze
- sweetalert2

---

# ⚙️ Installation

Clone the repository

```bash
git clone https://github.com/prem3706/sports-academy-management.git
```

Go to project directory

```bash
cd sports-academy-management
```

Install dependencies

```bash
composer install
```

Install Node packages

```bash
npm install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database in `.env`

Run migrations

```bash
php artisan migrate
```

(Optional) Seed database

```bash
php artisan db:seed
```

Create storage link

```bash
php artisan storage:link
```

Start development server

```bash
php artisan serve
```

Compile assets

```bash
npm run dev
```

# 🔒 Security

- CSRF Protection
- Authentication
- Authorization
- Role-Based Access Control
- Input Validation
- Database Transactions
- Password Hashing

---

# 📈 Future Enhancements

- QR Code Attendance
- Mobile Application
- WhatsApp Notifications
- SMS Notifications
- Email Notifications
- Payment Gateway Integration
- AI Performance Analytics
- Online Registration
- REST API

---



# 👨‍💻 Developer

**Prem**

Laravel Developer

GitHub: https://github.com/prem3706

---

⭐ If you like this project, don't forget to star the repository.
