# Inspection & Monitoring System (نظام التفتيش والمراقبة)

A complete Laravel-based Inspection & Monitoring System for field-inspection teams.

## Overview

This system allows officers to record daily inspection reports for multiple offices with full CRUD operations, role-based access control, and a modern Arabic RTL user interface.

## Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Database**: PostgreSQL
- **Frontend**: Blade templates with TailwindCSS
- **Authentication**: Laravel session-based auth + Sanctum for API

## Features

### Core Features
- User authentication with role-based access (Admin/Inspector)
- Full CRUD for Inspection Reports
- Search and filter reports by record number, officer, office, and date
- Dashboard with statistics (total reports, today's reports, total inspectors)
- Activity logging for all create/update/delete operations
- Print-friendly report pages

### User Roles
- **Admin**: Full access to all features including user management and activity logs
- **Inspector**: Can create and view reports, edit own reports

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
│   │   ├── Api/              # API controllers
│   │   ├── Auth/             # Authentication controllers
│   │   ├── DashboardController.php
│   │   ├── ReportController.php
│   │   ├── UserController.php
│   │   └── ActivityLogController.php
│   ├── Middleware/
│   │   └── AdminMiddleware.php
│   └── Requests/             # Form request validation
├── Models/
│   ├── User.php
│   ├── InspectionReport.php
│   └── ActivityLog.php
├── Policies/
│   ├── ReportPolicy.php
│   └── UserPolicy.php
└── Providers/
    └── AppServiceProvider.php

resources/views/
├── layouts/app.blade.php     # Main layout with sidebar
├── auth/login.blade.php      # Login page
├── dashboard.blade.php       # Dashboard
├── reports/                  # Report views
├── users/                    # User management views
└── activity-logs/            # Activity log views

routes/
├── web.php                   # Web routes
└── api.php                   # API routes
```

## API Endpoints

### Authentication
- `POST /api/login` - Login and get API token
- `POST /api/logout` - Logout (requires auth)

### Reports (requires authentication)
- `GET /api/reports` - List all reports
- `POST /api/reports` - Create new report
- `GET /api/reports/{id}` - Get single report
- `PUT /api/reports/{id}` - Update report
- `DELETE /api/reports/{id}` - Delete report (admin only)

## Default Credentials

- **Admin Account**:
  - Username: `admin`
  - Password: `Admin123!`

- **Inspector Accounts**:
  - Username: `inspector1` / Password: `Inspector123!`
  - Username: `inspector2` / Password: `Inspector123!`

## Running the Application

The application runs automatically on port 5000 using:
```bash
php artisan serve --host=0.0.0.0 --port=5000
```

## Database

Using PostgreSQL with the following tables:
- `users` - System users with roles
- `inspection_reports` - Inspection report records (soft deletes enabled)
- `activity_logs` - Activity tracking
- `sessions` - Session management
- `cache` - Application cache
- `jobs` - Queue jobs

## Environment Variables

Required environment variables are automatically configured:
- `DATABASE_URL` - PostgreSQL connection string
- `DB_CONNECTION=pgsql`
- `APP_LOCALE=ar` - Arabic locale

## Development Notes

- Form validation uses Laravel FormRequest classes
- Policies control authorization for reports and users
- Activity logging tracks all CRUD operations
- Soft deletes enabled for inspection reports
