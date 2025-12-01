# Records Management System (نظام إدارة الرقابة والتفتيش)

A simplified Laravel-based Records Management System for inspection and monitoring teams.

## Overview

This system allows officers to record and manage inspection records with full CRUD operations, two-tier role-based access control (admin and inspector), advanced search, and a modern Arabic RTL user interface.

## Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Database**: PostgreSQL (Neon)
- **Frontend**: Blade templates with TailwindCSS
- **Authentication**: Laravel session-based auth
- **Session Driver**: File-based sessions
- **Excel Import**: PhpSpreadsheet library

## Features

### Core Features
- Two-tier role-based access control (Admin/Inspector)
- Full CRUD for inspection records
- Advanced search with multiple filters (name, military ID, governorate, rank, station, date range)
- Dashboard with statistics (total, today, month, year records)
- Activity logging for all operations
- Excel import functionality
- Print-friendly report pages

### User Roles
- **Admin (مدير)**: Full access to all features including user management and settings
- **Inspector (مفتش)**: Can view, search, create records, and generate reports

### User Fields
- Name (الاسم الكامل)
- Username (اسم المستخدم)
- Password (كلمة المرور)
- Role (الدور) - admin or inspector
- Rank (الرتبة)
- Office (المكتب/الجهة)

### Record Fields
- Record Number (رقم الصادر)
- Military ID (الرقم العسكري)
- 4-part Arabic Name (الاسم الرباعي)
- Rank (الرتبة)
- Governorate (المحافظة)
- Station (المخفر)
- Action Type (نوع الإجراء)
- Ports (المنافذ)
- Notes (ملاحظات)
- Round Date (تاريخ الجولة)

### Settings (Admin Only)
- **User Management**: Create, edit, delete users with role assignments
- **Station Management**: Manage stations/police stations
- **Port Management**: Manage entry ports

### UI Features
- Modern, clean government-style admin panel
- Full Arabic RTL support
- Responsive layout with sidebar navigation
- Professional forms with validation messages
- Data tables with pagination and search

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/LoginController.php
│   │   ├── HomeController.php
│   │   ├── DashboardController.php
│   │   ├── RecordController.php
│   │   ├── SearchController.php
│   │   ├── ReportController.php
│   │   ├── ImportController.php
│   │   ├── SettingController.php
│   │   ├── UserController.php
│   │   ├── StationController.php
│   │   └── PortController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Record.php
│   ├── Station.php
│   ├── Port.php
│   └── Log.php
├── Policies/
│   └── RecordPolicy.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── layouts/app.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── home.blade.php
├── dashboard.blade.php
├── records/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── show.blade.php
│   └── edit.blade.php
├── search/
│   ├── index.blade.php
│   └── show.blade.php
├── reports/
│   ├── index.blade.php
│   └── print.blade.php
├── import/
│   └── index.blade.php
└── settings/
    ├── index.blade.php
    ├── users/
    ├── stations/
    └── ports/

routes/
├── web.php
└── api.php
```

## Test Credentials

- **Admin Account**:
  - Username: `admin`
  - Password: `Admin123!`

- **Inspector Account**:
  - Username: `supervisor`
  - Password: `123456`

- **Inspector Account**:
  - Username: `user1`
  - Password: `123456`

## Running the Application

The application runs on port 5000 using:
```bash
php artisan serve --host=0.0.0.0 --port=5000 --no-reload
```

**Important**: The `--no-reload` flag is required because Laravel's `ServeCommand` filters environment variables by default. Without this flag, PostgreSQL credentials (DATABASE_URL, PGHOST, etc.) are not passed to PHP child processes.

## Database

Using PostgreSQL (Neon) with the following tables:
- `users` - System users with roles (admin, inspector)
- `records` - Inspection records with soft deletes
- `stations` - Station/Police station management
- `ports` - Ports/Entry points management
- `logs` - Activity tracking
- `sessions` - Session management
- `cache` - Application cache

## Environment Variables

Required environment variables are automatically configured by Replit:
- `DATABASE_URL` - PostgreSQL connection string
- `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD`, `PGDATABASE` - Individual PostgreSQL credentials

The `bootstrap/app.php` file ensures these environment variables are loaded into PHP's environment.

## Development Notes

- Activity logging tracks all CRUD operations
- Soft deletes enabled for records
- Session driver set to `file` for simplicity
- Excel import uses PhpSpreadsheet library
- `AdminMiddleware` restricts admin-only routes

## Technical Notes

### Environment Variable Handling
Laravel's `ServeCommand` filters environment variables when spawning child processes. The `--no-reload` flag bypasses this filtering, allowing PostgreSQL credentials to pass through. Additionally, `bootstrap/app.php` explicitly sets these variables in PHP's environment.

### Session Configuration
Sessions are stored in files (`storage/framework/sessions/`) rather than the database to avoid potential connection issues during development.

### Role-Based Access Control
The system uses a simple two-role system:
- `admin` - Full access to all features including settings and user management
- `inspector` - Can view, search, create records, and generate reports
- `AdminMiddleware` restricts access to admin-only routes (settings, user management)

## Recent Changes (December 2025)

- Simplified role system from 3 roles (admin/supervisor/user) to 2 roles (admin/inspector)
- Removed complex permission system with 68 permissions across 15 modules
- Simplified user model with fields: name, username, password, role, rank, office
- Removed administrative system modules (departments, employees, attendance, leave requests, documents, correspondences)
- Streamlined routes and middleware
