# Records Management System (نظام إدارة الرقابة والتفتيش)

A complete Laravel-based Records Management System for inspection and monitoring teams.

## Overview

This system allows officers to record and manage inspection records with full CRUD operations, three-tier role-based access control, advanced search, and a modern Arabic RTL user interface.

## Stack

- **Backend**: Laravel 12 with PHP 8.2+
- **Database**: PostgreSQL (Neon)
- **Frontend**: Blade templates with TailwindCSS
- **Authentication**: Laravel session-based auth + Sanctum for API
- **Session Driver**: File-based sessions
- **Excel Import**: PhpSpreadsheet library

## Features

### Core Features
- Three-tier role-based access control (Admin/Supervisor/User)
- Full CRUD for inspection records
- Advanced search with multiple filters (name, military ID, governorate, rank, station, date range)
- Dashboard with statistics (total, today, month, year records)
- Activity logging for all operations
- Excel import functionality
- Print-friendly report pages
- Home page with Block System and Administrative System tabs

### User Roles
- **Admin (مدير)**: Full access to all features including user management and settings
- **Supervisor (مشرف)**: All features except user management (can manage stations, ports, import data)
- **User (مستخدم)**: View, search, and generate reports only

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

### Administrative System (النظام الإداري)

#### HR Module (الموارد البشرية)
- **Departments (الأقسام)**: Full CRUD for organizational units with parent/child hierarchy
- **Employees (الموظفين)**: Complete employee management with contact info, hire dates, leave balances
- **Attendance (الحضور)**: Daily check-in/out tracking with bulk entry, date/status filtering, and attendance reports
- **Leave Requests (طلبات الإجازات)**: Leave request workflow with approval/rejection, balance tracking, and reason documentation

#### Document Workflow System (نظام المراسلات)
- **Document Types**: Internal memo, external letter, circular, decision, report
- **Priority Levels**: Normal, urgent, very urgent
- **Workflow Stages**: Draft → Pending Review → Pending Approval → Approved/Rejected/Needs Modification
- **Electronic Signatures**: Canvas-based signature capture for manager approval
- **File Attachments**: Word (.doc, .docx) and PDF upload support
- **Inbox**: View documents pending user action
- **My Documents**: Track created documents and their workflow status
- **Print View**: Print-friendly document layout with signature

### UI Features
- Modern, clean government-style admin panel
- Full Arabic RTL support
- Responsive layout with sidebar navigation
- Professional forms with validation messages
- Data tables with pagination and search
- Quick login cards on login page for testing
- Tabbed home page (Block System / Administrative System)

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
│   │   ├── PortController.php
│   │   └── Admin/
│   │       ├── DepartmentController.php
│   │       ├── EmployeeController.php
│   │       ├── AttendanceController.php
│   │       ├── LeaveRequestController.php
│   │       └── DocumentController.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── RoleMiddleware.php
│   └── Requests/
├── Models/
│   ├── User.php
│   ├── Record.php
│   ├── Station.php
│   ├── Port.php
│   ├── Log.php
│   ├── Department.php
│   ├── Employee.php
│   ├── Attendance.php
│   ├── LeaveRequest.php
│   ├── Document.php
│   └── DocumentWorkflow.php
├── Policies/
│   ├── RecordPolicy.php
│   └── UserPolicy.php
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
├── settings/
│   ├── index.blade.php
│   ├── users/
│   ├── stations/
│   └── ports/
└── admin/
    ├── departments/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── show.blade.php
    │   └── edit.blade.php
    ├── employees/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── show.blade.php
    │   └── edit.blade.php
    ├── attendances/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── edit.blade.php
    │   ├── bulk-create.blade.php
    │   └── report.blade.php
    ├── leave-requests/
    │   ├── index.blade.php
    │   ├── create.blade.php
    │   ├── show.blade.php
    │   └── edit.blade.php
    └── documents/
        ├── index.blade.php
        ├── create.blade.php
        ├── show.blade.php
        ├── edit.blade.php
        ├── inbox.blade.php
        ├── my-documents.blade.php
        └── print.blade.php

routes/
├── web.php
└── api.php
```

## Test Credentials

- **Admin Account**:
  - Username: `admin`
  - Password: `Admin123!`

- **Supervisor Account**:
  - Username: `supervisor`
  - Password: `123456`

- **User Account**:
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
- `users` - System users with roles (admin, supervisor, user)
- `records` - Inspection records with soft deletes
- `stations` - Station/Police station management
- `ports` - Ports/Entry points management
- `logs` - Activity tracking
- `sessions` - Session management
- `cache` - Application cache
- `jobs` - Queue jobs
- `departments` - Organizational departments with hierarchy
- `employees` - Employee records with leave balances
- `attendances` - Daily attendance records
- `leave_requests` - Leave request workflow
- `documents` - Document management with workflow states
- `document_workflows` - Document workflow history and signatures

## Environment Variables

Required environment variables are automatically configured by Replit:
- `DATABASE_URL` - PostgreSQL connection string
- `PGHOST`, `PGPORT`, `PGUSER`, `PGPASSWORD`, `PGDATABASE` - Individual PostgreSQL credentials

The `bootstrap/app.php` file ensures these environment variables are loaded into PHP's environment.

## Development Notes

- Policies control authorization for records and users
- Activity logging tracks all CRUD operations
- Soft deletes enabled for records
- Session driver set to `file` for simplicity
- Quick login cards on login page show credentials for easy testing
- Excel import uses PhpSpreadsheet library
- Three middleware for role control: `admin`, `role:admin,supervisor`

## Technical Notes

### Environment Variable Handling
Laravel's `ServeCommand` filters environment variables when spawning child processes. The `--no-reload` flag bypasses this filtering, allowing PostgreSQL credentials to pass through. Additionally, `bootstrap/app.php` explicitly sets these variables in PHP's environment.

### Session Configuration
Sessions are stored in files (`storage/framework/sessions/`) rather than the database to avoid potential connection issues during development.

### Role-Based Access Control
The system uses a combination of middleware and policies:
- `AdminMiddleware`: Restricts access to admin-only routes
- `RoleMiddleware`: Allows multiple roles (e.g., `role:admin,supervisor`)
- `RecordPolicy` and `UserPolicy`: Fine-grained authorization checks
