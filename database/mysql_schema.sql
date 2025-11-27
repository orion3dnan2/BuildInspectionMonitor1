-- =====================================================
-- نظام إدارة الرقابة والتفتيش - MySQL Database Schema
-- للاستخدام مع XAMPP / MySQL / MariaDB
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS `inspection_system` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `inspection_system`;

-- =====================================================
-- جدول المستخدمين (users)
-- =====================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'اسم المستخدم',
    `username` VARCHAR(255) NOT NULL COMMENT 'اسم الدخول',
    `password` VARCHAR(255) NOT NULL COMMENT 'كلمة المرور',
    `role` ENUM('admin', 'supervisor', 'user') NOT NULL DEFAULT 'user' COMMENT 'الدور',
    `can_create_records` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'صلاحية إنشاء السجلات',
    `can_edit_records` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'صلاحية تعديل السجلات',
    `can_delete_records` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'صلاحية حذف السجلات',
    `can_import` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'صلاحية الاستيراد',
    `can_export` TINYINT(1) NOT NULL DEFAULT 1 COMMENT 'صلاحية التصدير',
    `can_manage_users` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'صلاحية إدارة المستخدمين',
    `can_manage_settings` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'صلاحية إدارة الإعدادات',
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول المخافر (stations)
-- =====================================================
DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'اسم المخفر',
    `governorate` VARCHAR(255) NULL COMMENT 'المحافظة',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول المنافذ (ports)
-- =====================================================
DROP TABLE IF EXISTS `ports`;
CREATE TABLE `ports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'اسم المنفذ',
    `type` VARCHAR(255) NULL COMMENT 'نوع المنفذ',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول السجلات (records)
-- =====================================================
DROP TABLE IF EXISTS `records`;
CREATE TABLE `records` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tracking_number` VARCHAR(255) NULL COMMENT 'رقم التتبع',
    `record_number` VARCHAR(255) NOT NULL COMMENT 'رقم الصادر',
    `military_id` VARCHAR(255) NULL COMMENT 'الرقم العسكري',
    `first_name` VARCHAR(255) NOT NULL COMMENT 'الاسم الأول',
    `second_name` VARCHAR(255) NULL COMMENT 'الاسم الثاني',
    `third_name` VARCHAR(255) NULL COMMENT 'الاسم الثالث',
    `fourth_name` VARCHAR(255) NULL COMMENT 'الاسم الرابع',
    `rank` VARCHAR(255) NULL COMMENT 'الرتبة',
    `governorate` VARCHAR(255) NULL COMMENT 'المحافظة',
    `station` VARCHAR(255) NULL COMMENT 'المخفر',
    `action_type` VARCHAR(255) NULL COMMENT 'نوع الإجراء',
    `ports` VARCHAR(255) NULL COMMENT 'المنافذ',
    `notes` TEXT NULL COMMENT 'الملاحظات المدونة',
    `round_date` DATE NULL COMMENT 'تاريخ الجولة',
    `user_id` BIGINT UNSIGNED NULL COMMENT 'معرف المستخدم المنشئ',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    `deleted_at` TIMESTAMP NULL COMMENT 'تاريخ الحذف الناعم',
    PRIMARY KEY (`id`),
    UNIQUE KEY `records_tracking_number_unique` (`tracking_number`),
    KEY `records_user_id_foreign` (`user_id`),
    KEY `records_governorate_index` (`governorate`),
    KEY `records_station_index` (`station`),
    KEY `records_round_date_index` (`round_date`),
    CONSTRAINT `records_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول السجلات (logs)
-- =====================================================
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL COMMENT 'معرف المستخدم',
    `action` VARCHAR(255) NOT NULL COMMENT 'نوع العملية',
    `model_type` VARCHAR(255) NULL COMMENT 'نوع النموذج',
    `model_id` BIGINT UNSIGNED NULL COMMENT 'معرف النموذج',
    `old_values` JSON NULL COMMENT 'القيم القديمة',
    `new_values` JSON NULL COMMENT 'القيم الجديدة',
    `ip_address` VARCHAR(45) NULL COMMENT 'عنوان IP',
    `user_agent` TEXT NULL COMMENT 'معلومات المتصفح',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `logs_user_id_foreign` (`user_id`),
    CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الأقسام (departments)
-- =====================================================
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL COMMENT 'اسم القسم',
    `description` TEXT NULL COMMENT 'الوصف',
    `parent_id` BIGINT UNSIGNED NULL COMMENT 'القسم الأب',
    `manager_id` BIGINT UNSIGNED NULL COMMENT 'معرف المدير',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `departments_parent_id_foreign` (`parent_id`),
    CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الموظفين (employees)
-- =====================================================
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_number` VARCHAR(255) NULL COMMENT 'الرقم الوظيفي',
    `first_name` VARCHAR(255) NOT NULL COMMENT 'الاسم الأول',
    `second_name` VARCHAR(255) NULL COMMENT 'الاسم الثاني',
    `third_name` VARCHAR(255) NULL COMMENT 'الاسم الثالث',
    `fourth_name` VARCHAR(255) NULL COMMENT 'اسم العائلة',
    `email` VARCHAR(255) NULL COMMENT 'البريد الإلكتروني',
    `phone` VARCHAR(255) NULL COMMENT 'رقم الهاتف',
    `department_id` BIGINT UNSIGNED NULL COMMENT 'معرف القسم',
    `position` VARCHAR(255) NULL COMMENT 'المسمى الوظيفي',
    `hire_date` DATE NULL COMMENT 'تاريخ التعيين',
    `annual_leave_balance` INT NOT NULL DEFAULT 30 COMMENT 'رصيد الإجازات السنوية',
    `sick_leave_balance` INT NOT NULL DEFAULT 15 COMMENT 'رصيد الإجازات المرضية',
    `status` ENUM('active', 'inactive', 'suspended') NOT NULL DEFAULT 'active' COMMENT 'الحالة',
    `user_id` BIGINT UNSIGNED NULL COMMENT 'معرف حساب المستخدم',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
    KEY `employees_department_id_foreign` (`department_id`),
    KEY `employees_user_id_foreign` (`user_id`),
    CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
    CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الحضور (attendances)
-- =====================================================
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف الموظف',
    `date` DATE NOT NULL COMMENT 'التاريخ',
    `check_in` TIME NULL COMMENT 'وقت الحضور',
    `check_out` TIME NULL COMMENT 'وقت الانصراف',
    `status` ENUM('present', 'absent', 'late', 'leave', 'holiday') NOT NULL DEFAULT 'present' COMMENT 'الحالة',
    `notes` TEXT NULL COMMENT 'ملاحظات',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `attendances_employee_date_unique` (`employee_id`, `date`),
    KEY `attendances_date_index` (`date`),
    CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول طلبات الإجازات (leave_requests)
-- =====================================================
DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `employee_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف الموظف',
    `leave_type` ENUM('annual', 'sick', 'emergency', 'unpaid', 'maternity', 'other') NOT NULL COMMENT 'نوع الإجازة',
    `start_date` DATE NOT NULL COMMENT 'تاريخ البداية',
    `end_date` DATE NOT NULL COMMENT 'تاريخ النهاية',
    `reason` TEXT NULL COMMENT 'السبب',
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' COMMENT 'الحالة',
    `rejection_reason` TEXT NULL COMMENT 'سبب الرفض',
    `approved_by` BIGINT UNSIGNED NULL COMMENT 'معتمد بواسطة',
    `approved_at` TIMESTAMP NULL COMMENT 'تاريخ الاعتماد',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `leave_requests_employee_id_foreign` (`employee_id`),
    KEY `leave_requests_approved_by_foreign` (`approved_by`),
    CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول المستندات (documents)
-- =====================================================
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_number` VARCHAR(255) NULL COMMENT 'رقم المستند',
    `title` VARCHAR(255) NOT NULL COMMENT 'عنوان المستند',
    `type` ENUM('internal_memo', 'external_letter', 'circular', 'decision', 'report') NOT NULL COMMENT 'نوع المستند',
    `priority` ENUM('normal', 'urgent', 'very_urgent') NOT NULL DEFAULT 'normal' COMMENT 'الأولوية',
    `content` LONGTEXT NULL COMMENT 'محتوى المستند',
    `file_path` VARCHAR(255) NULL COMMENT 'مسار الملف المرفق',
    `file_name` VARCHAR(255) NULL COMMENT 'اسم الملف المرفق',
    `status` ENUM('draft', 'pending_review', 'pending_approval', 'approved', 'rejected', 'needs_modification') NOT NULL DEFAULT 'draft' COMMENT 'الحالة',
    `created_by` BIGINT UNSIGNED NOT NULL COMMENT 'منشئ المستند',
    `assigned_to` BIGINT UNSIGNED NULL COMMENT 'محال إلى',
    `approved_by` BIGINT UNSIGNED NULL COMMENT 'معتمد بواسطة',
    `approved_at` TIMESTAMP NULL COMMENT 'تاريخ الاعتماد',
    `signature` LONGTEXT NULL COMMENT 'التوقيع الإلكتروني',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `documents_document_number_unique` (`document_number`),
    KEY `documents_created_by_foreign` (`created_by`),
    KEY `documents_assigned_to_foreign` (`assigned_to`),
    KEY `documents_approved_by_foreign` (`approved_by`),
    CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `documents_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `documents_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول سير عمل المستندات (document_workflows)
-- =====================================================
DROP TABLE IF EXISTS `document_workflows`;
CREATE TABLE `document_workflows` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `document_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف المستند',
    `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف المستخدم',
    `action` ENUM('created', 'sent_for_review', 'sent_to_manager', 'approved', 'rejected', 'needs_modification', 'modified') NOT NULL COMMENT 'الإجراء',
    `from_status` VARCHAR(255) NULL COMMENT 'الحالة السابقة',
    `to_status` VARCHAR(255) NULL COMMENT 'الحالة الجديدة',
    `comments` TEXT NULL COMMENT 'التعليقات',
    `signature` LONGTEXT NULL COMMENT 'التوقيع',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `document_workflows_document_id_foreign` (`document_id`),
    KEY `document_workflows_user_id_foreign` (`user_id`),
    CONSTRAINT `document_workflows_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
    CONSTRAINT `document_workflows_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول دفتر القيد (book_entries)
-- =====================================================
DROP TABLE IF EXISTS `book_entries`;
CREATE TABLE `book_entries` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `book_number` VARCHAR(255) NOT NULL COMMENT 'رقم القيد',
    `book_title` VARCHAR(255) NOT NULL COMMENT 'عنوان الكتاب',
    `book_type` ENUM('incoming', 'outgoing', 'internal', 'circular', 'decision') NOT NULL DEFAULT 'incoming' COMMENT 'نوع الكتاب',
    `date_written` DATE NOT NULL COMMENT 'تاريخ الكتابة',
    `description` TEXT NULL COMMENT 'الوصف',
    `writer_name` VARCHAR(255) NOT NULL COMMENT 'اسم الكاتب',
    `writer_rank` VARCHAR(255) NULL COMMENT 'رتبة الكاتب',
    `writer_office` VARCHAR(255) NULL COMMENT 'مكتب الكاتب',
    `status` ENUM('draft', 'submitted', 'approved', 'rejected', 'needs_modification') NOT NULL DEFAULT 'draft' COMMENT 'الحالة',
    `manager_comment` TEXT NULL COMMENT 'ملاحظات المدير',
    `created_by` BIGINT UNSIGNED NOT NULL COMMENT 'منشئ القيد',
    `approved_by` BIGINT UNSIGNED NULL COMMENT 'معتمد بواسطة',
    `approved_at` TIMESTAMP NULL COMMENT 'تاريخ الاعتماد',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `book_entries_book_number_unique` (`book_number`),
    KEY `book_entries_created_by_foreign` (`created_by`),
    KEY `book_entries_approved_by_foreign` (`approved_by`),
    CONSTRAINT `book_entries_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `book_entries_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول التوقيعات الإلكترونية (signatures)
-- =====================================================
DROP TABLE IF EXISTS `signatures`;
CREATE TABLE `signatures` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف المستخدم الموقع',
    `signable_type` VARCHAR(255) NOT NULL COMMENT 'نوع النموذج الموقع عليه',
    `signable_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف النموذج الموقع عليه',
    `signature_data` LONGTEXT NOT NULL COMMENT 'بيانات التوقيع (Base64)',
    `signature_hash` VARCHAR(255) NOT NULL COMMENT 'هاش التوقيع للتحقق',
    `action` ENUM('approved', 'rejected', 'reviewed') NOT NULL COMMENT 'الإجراء',
    `comments` TEXT NULL COMMENT 'التعليقات',
    `ip_address` VARCHAR(45) NULL COMMENT 'عنوان IP',
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `signatures_signature_hash_unique` (`signature_hash`),
    KEY `signatures_user_id_foreign` (`user_id`),
    KEY `signatures_signable_index` (`signable_type`, `signable_id`),
    CONSTRAINT `signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الجلسات (sessions)
-- =====================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الكاش (cache)
-- =====================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول المهام (jobs)
-- =====================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL,
    `cancelled_at` INT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- جدول الهجرات (migrations)
-- =====================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- إدخال البيانات الأساسية
-- =====================================================

-- إدخال مستخدم المدير الافتراضي
INSERT INTO `users` (`name`, `username`, `password`, `role`, `can_create_records`, `can_edit_records`, `can_delete_records`, `can_import`, `can_export`, `can_manage_users`, `can_manage_settings`, `created_at`, `updated_at`) VALUES
('مدير النظام', 'admin', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1, 1, 1, 1, 1, 1, NOW(), NOW()),
('المشرف', 'supervisor', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', 1, 1, 1, 1, 1, 0, 1, NOW(), NOW()),
('مستخدم عادي', 'user1', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, 0, 0, 0, 1, 0, 0, NOW(), NOW());

-- ملاحظة: كلمة المرور الافتراضية هي: password
-- يجب تغييرها بعد تسجيل الدخول

-- إدخال بعض المحافظات كمخافر
INSERT INTO `stations` (`name`, `governorate`, `created_at`, `updated_at`) VALUES
('مخفر العاصمة', 'العاصمة', NOW(), NOW()),
('مخفر حولي', 'حولي', NOW(), NOW()),
('مخفر الفروانية', 'الفروانية', NOW(), NOW()),
('مخفر الأحمدي', 'الأحمدي', NOW(), NOW()),
('مخفر الجهراء', 'الجهراء', NOW(), NOW()),
('مخفر مبارك الكبير', 'مبارك الكبير', NOW(), NOW());

-- إدخال بعض المنافذ
INSERT INTO `ports` (`name`, `type`, `created_at`, `updated_at`) VALUES
('منفذ العبدلي', 'بري', NOW(), NOW()),
('منفذ النويصيب', 'بري', NOW(), NOW()),
('مطار الكويت الدولي', 'جوي', NOW(), NOW()),
('ميناء الشويخ', 'بحري', NOW(), NOW());

-- إدخال أقسام افتراضية
INSERT INTO `departments` (`name`, `description`, `created_at`, `updated_at`) VALUES
('الإدارة العامة', 'الإدارة العليا للمنظمة', NOW(), NOW()),
('الموارد البشرية', 'قسم شؤون الموظفين', NOW(), NOW()),
('الشؤون المالية', 'قسم المحاسبة والمالية', NOW(), NOW()),
('تقنية المعلومات', 'قسم الدعم الفني والتقنية', NOW(), NOW()),
('الشؤون القانونية', 'قسم الاستشارات القانونية', NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- تعليمات الاستخدام:
-- =====================================================
-- 1. افتح phpMyAdmin في XAMPP
-- 2. أنشئ قاعدة بيانات جديدة باسم: inspection_system
-- 3. اختر قاعدة البيانات ثم اضغط على تبويب "Import"
-- 4. اختر هذا الملف واضغط "Go"
-- 
-- بيانات الدخول الافتراضية:
-- المدير: admin / password
-- المشرف: supervisor / password  
-- المستخدم: user1 / password
-- =====================================================
