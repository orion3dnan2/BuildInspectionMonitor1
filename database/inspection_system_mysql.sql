-- ========================================================
-- نظام إدارة الرقابة والتفتيش - قاعدة بيانات MySQL
-- Inspection System Database - For XAMPP/MySQL
-- ========================================================

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

-- إنشاء قاعدة البيانات
CREATE DATABASE IF NOT EXISTS `inspection_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `inspection_system`;

-- ========================================================
-- جدول المستخدمين (Users)
-- ========================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','supervisor','user') NOT NULL DEFAULT 'user',
  `rank` varchar(255) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `custom_permissions` json DEFAULT NULL,
  `block_system_access` tinyint(1) NOT NULL DEFAULT '1',
  `admin_system_access` tinyint(1) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الأدوار (Roles)
-- ========================================================
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الصلاحيات (Permissions)
-- ========================================================
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `module` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول صلاحيات الأدوار (Role Permissions)
-- ========================================================
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_permissions_role_id_foreign` (`role_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول المخافر (Stations)
-- ========================================================
DROP TABLE IF EXISTS `stations`;
CREATE TABLE `stations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول المنافذ (Ports)
-- ========================================================
DROP TABLE IF EXISTS `ports`;
CREATE TABLE `ports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول السجلات (Records)
-- ========================================================
DROP TABLE IF EXISTS `records`;
CREATE TABLE `records` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `record_number` varchar(255) NOT NULL,
  `tracking_number` varchar(255) DEFAULT NULL,
  `military_id` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `second_name` varchar(255) NOT NULL,
  `third_name` varchar(255) NOT NULL,
  `fourth_name` varchar(255) NOT NULL,
  `rank` varchar(255) NOT NULL,
  `governorate` varchar(255) NOT NULL,
  `station` varchar(255) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `ports` text,
  `notes` text,
  `round_date` date NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `records_created_by_foreign` (`created_by`),
  KEY `records_tracking_number_index` (`tracking_number`),
  CONSTRAINT `records_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الأقسام (Departments)
-- ========================================================
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `manager_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_parent_id_foreign` (`parent_id`),
  KEY `departments_manager_id_foreign` (`manager_id`),
  CONSTRAINT `departments_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الموظفين (Employees)
-- ========================================================
DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `department_id` bigint UNSIGNED DEFAULT NULL,
  `employee_number` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `annual_leave_balance` int NOT NULL DEFAULT '30',
  `sick_leave_balance` int NOT NULL DEFAULT '15',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employees_employee_number_unique` (`employee_number`),
  KEY `employees_user_id_foreign` (`user_id`),
  KEY `employees_department_id_foreign` (`department_id`),
  CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الحضور (Attendances)
-- ========================================================
DROP TABLE IF EXISTS `attendances`;
CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` enum('present','absent','late','leave','holiday') NOT NULL DEFAULT 'present',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendances_employee_id_date_unique` (`employee_id`,`date`),
  CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول طلبات الإجازات (Leave Requests)
-- ========================================================
DROP TABLE IF EXISTS `leave_requests`;
CREATE TABLE `leave_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `employee_id` bigint UNSIGNED NOT NULL,
  `type` enum('annual','sick','emergency','unpaid') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days` int NOT NULL,
  `reason` text,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `rejection_reason` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `leave_requests_employee_id_foreign` (`employee_id`),
  KEY `leave_requests_approved_by_foreign` (`approved_by`),
  CONSTRAINT `leave_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `leave_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول المستندات (Documents)
-- ========================================================
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `type` enum('internal_memo','external_letter','circular','decision','report') NOT NULL,
  `priority` enum('normal','urgent','very_urgent') NOT NULL DEFAULT 'normal',
  `content` longtext,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending_review','pending_approval','approved','rejected','needs_modification') NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED NOT NULL,
  `current_handler` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_created_by_foreign` (`created_by`),
  KEY `documents_current_handler_foreign` (`current_handler`),
  CONSTRAINT `documents_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `documents_current_handler_foreign` FOREIGN KEY (`current_handler`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول سير عمل المستندات (Document Workflows)
-- ========================================================
DROP TABLE IF EXISTS `document_workflows`;
CREATE TABLE `document_workflows` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_id` bigint UNSIGNED NOT NULL,
  `from_user` bigint UNSIGNED DEFAULT NULL,
  `to_user` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `comments` text,
  `signature_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_workflows_document_id_foreign` (`document_id`),
  KEY `document_workflows_from_user_foreign` (`from_user`),
  KEY `document_workflows_to_user_foreign` (`to_user`),
  CONSTRAINT `document_workflows_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `document_workflows_from_user_foreign` FOREIGN KEY (`from_user`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `document_workflows_to_user_foreign` FOREIGN KEY (`to_user`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول التوقيعات (Signatures)
-- ========================================================
DROP TABLE IF EXISTS `signatures`;
CREATE TABLE `signatures` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `signature_data` longtext NOT NULL,
  `hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `signatures_user_id_foreign` (`user_id`),
  CONSTRAINT `signatures_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول المراسلات والكتب (Correspondences)
-- ========================================================
DROP TABLE IF EXISTS `correspondences`;
CREATE TABLE `correspondences` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `document_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` text,
  `description` text,
  `type` enum('incoming','outgoing') NOT NULL,
  `document_date` date NOT NULL,
  `from_department` varchar(255) DEFAULT NULL,
  `to_department` varchar(255) DEFAULT NULL,
  `status` enum('new','reviewed','completed','archived') NOT NULL DEFAULT 'new',
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_size` bigint UNSIGNED DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correspondences_document_number_unique` (`document_number`),
  KEY `correspondences_created_by_foreign` (`created_by`),
  KEY `correspondences_updated_by_foreign` (`updated_by`),
  KEY `correspondences_type_index` (`type`),
  KEY `correspondences_status_index` (`status`),
  KEY `correspondences_document_date_index` (`document_date`),
  CONSTRAINT `correspondences_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `correspondences_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الإشعارات (Notifications)
-- ========================================================
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `data` json DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  KEY `notifications_is_read_index` (`is_read`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول السجلات/اللوجات (Logs)
-- ========================================================
DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `logs_user_id_foreign` (`user_id`),
  KEY `logs_model_type_model_id_index` (`model_type`,`model_id`),
  CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الجلسات (Sessions)
-- ========================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الكاش (Cache)
-- ========================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول الوظائف (Jobs)
-- ========================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول إعادة تعيين كلمة المرور (Password Reset Tokens)
-- ========================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- جدول Migrations
-- ========================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- إدخال البيانات الأولية (Seed Data)
-- ========================================================

-- إضافة الأدوار الأساسية
INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `is_system`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'مدير النظام', 'صلاحيات كاملة على النظام', 1, NOW(), NOW()),
(2, 'supervisor', 'مشرف', 'صلاحيات إدارية محدودة', 1, NOW(), NOW()),
(3, 'user', 'مستخدم', 'صلاحيات المستخدم العادي', 1, NOW(), NOW());

-- إضافة المستخدمين الافتراضيين
INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `role`, `role_id`, `block_system_access`, `admin_system_access`, `created_at`, `updated_at`) VALUES
(1, 'مدير النظام', 'admin', 'admin@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 1, 1, NOW(), NOW()),
(2, 'المشرف', 'supervisor', 'supervisor@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supervisor', 2, 1, 1, NOW(), NOW()),
(3, 'مستخدم', 'user1', 'user1@example.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 3, 1, 0, NOW(), NOW());

-- إضافة بعض المخافر
INSERT INTO `stations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'المخفر الأول', NOW(), NOW()),
(2, 'المخفر الثاني', NOW(), NOW()),
(3, 'المخفر الثالث', NOW(), NOW());

-- إضافة بعض المنافذ
INSERT INTO `ports` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'المنفذ الشمالي', NOW(), NOW()),
(2, 'المنفذ الجنوبي', NOW(), NOW()),
(3, 'المنفذ الشرقي', NOW(), NOW());

-- إضافة قسم افتراضي
INSERT INTO `departments` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'الإدارة العامة', 'القسم الرئيسي للإدارة', NOW(), NOW()),
(2, 'قسم الموارد البشرية', 'إدارة شؤون الموظفين', NOW(), NOW()),
(3, 'قسم التفتيش والرقابة', 'قسم التفتيش الميداني', NOW(), NOW());

-- إضافة صلاحيات أساسية
INSERT INTO `permissions` (`id`, `key`, `name`, `description`, `module`, `created_at`, `updated_at`) VALUES
(1, 'records.view', 'عرض السجلات', 'عرض جميع السجلات', 'records', NOW(), NOW()),
(2, 'records.create', 'إنشاء سجل', 'إضافة سجلات جديدة', 'records', NOW(), NOW()),
(3, 'records.update', 'تعديل سجل', 'تعديل السجلات', 'records', NOW(), NOW()),
(4, 'records.delete', 'حذف سجل', 'حذف السجلات', 'records', NOW(), NOW()),
(5, 'users.view', 'عرض المستخدمين', 'عرض جميع المستخدمين', 'users', NOW(), NOW()),
(6, 'users.create', 'إنشاء مستخدم', 'إضافة مستخدمين جدد', 'users', NOW(), NOW()),
(7, 'users.update', 'تعديل مستخدم', 'تعديل بيانات المستخدمين', 'users', NOW(), NOW()),
(8, 'users.delete', 'حذف مستخدم', 'حذف المستخدمين', 'users', NOW(), NOW()),
(9, 'settings.manage', 'إدارة الإعدادات', 'الوصول لإعدادات النظام', 'settings', NOW(), NOW()),
(10, 'import.data', 'استيراد البيانات', 'استيراد البيانات من ملفات', 'import', NOW(), NOW());

-- ربط الصلاحيات بدور المدير
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, NOW(), NOW()), (1, 2, NOW(), NOW()), (1, 3, NOW(), NOW()), (1, 4, NOW(), NOW()),
(1, 5, NOW(), NOW()), (1, 6, NOW(), NOW()), (1, 7, NOW(), NOW()), (1, 8, NOW(), NOW()),
(1, 9, NOW(), NOW()), (1, 10, NOW(), NOW());

-- ربط الصلاحيات بدور المشرف
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(2, 1, NOW(), NOW()), (2, 2, NOW(), NOW()), (2, 3, NOW(), NOW()), (2, 9, NOW(), NOW()), (2, 10, NOW(), NOW());

-- ربط الصلاحيات بدور المستخدم
INSERT INTO `role_permissions` (`role_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(3, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS=1;
COMMIT;

-- ========================================================
-- ملاحظات التثبيت:
-- 1. قم بإنشاء قاعدة البيانات: CREATE DATABASE inspection_system
-- 2. استورد هذا الملف: mysql -u root -p inspection_system < inspection_system_mysql.sql
-- 3. أو استخدم phpMyAdmin لاستيراد الملف
-- 4. تأكد من تحديث ملف .env بإعدادات قاعدة البيانات الصحيحة
-- 
-- كلمات مرور المستخدمين الافتراضية:
-- admin: password (يجب تغييرها فوراً)
-- supervisor: password (يجب تغييرها فوراً)
-- user1: password (يجب تغييرها فوراً)
-- ========================================================
