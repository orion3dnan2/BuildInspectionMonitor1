# Inspection & Monitoring System (نظام التفتيش والمراقبة)

A complete Laravel-based Inspection & Monitoring System for field-inspection teams.

## Overview

This system allows officers to record daily inspection reports for multiple offices with full CRUD operations, role-based access control, and a modern Arabic RTL user interface.

## Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Database**: PostgreSQL (Neon)
- **Frontend**: Blade templates with TailwindCSS
- **Authentication**: Laravel session-based auth + Sanctum for API
- **Session Driver**: File-based sessions

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
- Quick login cards on login page for testing

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

## Test Credentials (Simple for Testing)

- **Admin Account**:
  - Username: `admin`
  - Password: `123456`

- **Inspector Accounts**:
  - Username: `inspector1` / Password: `123456`
  - Username: `inspector2` / Password: `123456`

## Running the Application

The application runs on port 5000 using:
```bash
php artisan serve --host=0.0.0.0 --port=5000 --no-reload
```

**Important**: The `--no-reload` flag is required because Laravel's `ServeCommand` filters environment variables by default. Without this flag, PostgreSQL credentials (DATABASE_URL, PGHOST, etc.) are not passed to PHP child processes.

## Database

Using PostgreSQL (Neon) with the following tables:
- `users` - System users with roles
- `inspection_reports` - Inspection report records (soft deletes enabled)
- `activity_logs` - Activity tracking
- `sessions` - Session management
- `cache` - Application cache
- `jobs` - Queue jobs

## Environment Variables

Required environment variables are automatically configured by Replit:
- `DATABASE_URL` - PostgreSQL connection string
- `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD`, `PGDATABASE` - Individual PostgreSQL credentials

The `bootstrap/app.php` file ensures these environment variables are loaded into PHP's environment.

## Development Notes

- Form validation uses Laravel FormRequest classes
- Policies control authorization for reports and users
- Activity logging tracks all CRUD operations
- Soft deletes enabled for inspection reports
- Session driver set to `file` for simplicity
- Quick login cards on login page show credentials for easy testing

## Technical Notes

### Environment Variable Handling
Laravel's `ServeCommand` filters environment variables when spawning child processes. The `--no-reload` flag bypasses this filtering, allowing PostgreSQL credentials to pass through. Additionally, `bootstrap/app.php` explicitly sets these variables in PHP's environment.

### Session Configuration
Sessions are stored in files (`storage/framework/sessions/`) rather than the database to avoid potential connection issues during development.
