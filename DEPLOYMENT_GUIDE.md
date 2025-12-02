# دليل نشر التحديثات - نظام إدارة الرقابة والتفتيش
# Deployment Guide - Records Management System

---

## المتطلبات الأساسية
- PHP 8.2+
- Composer
- PostgreSQL
- الوصول للسيرفر عبر SSH

---

## الخطوة 1: النسخ الاحتياطي (مهم جداً!)

```bash
# نسخ قاعدة البيانات
pg_dump -U postgres -h localhost your_database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# نسخ ملفات المشروع
cp -r /path/to/project /path/to/project_backup_$(date +%Y%m%d)
```

---

## الخطوة 2: تحضير الكود الجديد

### الخيار أ: استخدام Git (موصى به)
```bash
cd /path/to/project

# حفظ التغييرات المحلية
git stash

# سحب التحديثات
git pull origin main

# استعادة التغييرات المحلية إن وجدت
git stash pop
```

### الخيار ب: رفع الملفات يدوياً
1. حمّل الكود من Replit (Download as ZIP)
2. ارفع الملفات للسيرفر
3. تأكد من عدم استبدال ملف `.env`

---

## الخطوة 3: تفعيل وضع الصيانة

```bash
cd /path/to/project
php artisan down --message="جاري تحديث النظام، يرجى الانتظار..." --retry=60
```

---

## الخطوة 4: تثبيت التبعيات

```bash
composer install --no-dev --optimize-autoloader
```

---

## الخطوة 5: تحديث قاعدة البيانات

### أ. تشغيل الترحيلات
```bash
php artisan migrate --force
```

### ب. إزالة القيد الفريد من record_number (إذا لم يتم تلقائياً)
```bash
php artisan tinker
```
ثم:
```php
DB::statement('ALTER TABLE records DROP CONSTRAINT IF EXISTS records_record_number_unique');
exit
```

### ج. مزامنة الصلاحيات
```bash
php artisan tinker
```
ثم:
```php
// إضافة صلاحيات data_entry لدور user
$userRole = \App\Models\Role::where('slug', 'user')->first();
$dataEntryPerms = \App\Models\Permission::where('module', 'data_entry')->pluck('id');
$userRole->permissions()->syncWithoutDetaching($dataEntryPerms);
exit
```

---

## الخطوة 6: مسح وتحديث الكاش

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

---

## الخطوة 7: إلغاء وضع الصيانة

```bash
php artisan up
```

---

## الخطوة 8: التحقق من عمل النظام

1. ✅ سجل دخول بحساب admin
2. ✅ تحقق من صفحة الإعدادات والصلاحيات
3. ✅ جرب إضافة سجل بنفس رقم الصادر
4. ✅ تحقق من رقم التتبع التلقائي
5. ✅ سجل دخول بحساب user1 وتحقق من الوصول

---

## خطة الطوارئ (إذا حدث خطأ)

### استعادة قاعدة البيانات:
```bash
psql -U postgres -h localhost your_database_name < backup_YYYYMMDD_HHMMSS.sql
```

### استعادة الكود:
```bash
rm -rf /path/to/project
cp -r /path/to/project_backup_YYYYMMDD /path/to/project
```

### إعادة تشغيل الخدمات:
```bash
php artisan up
php artisan cache:clear
```

---

## سكريبت النشر التلقائي (اختياري)

احفظ هذا الملف كـ `deploy.sh`:

```bash
#!/bin/bash
set -e

PROJECT_DIR="/path/to/project"
BACKUP_DIR="/path/to/backups"
DATE=$(date +%Y%m%d_%H%M%S)

echo "🚀 بدء عملية النشر..."

# النسخ الاحتياطي
echo "📦 إنشاء نسخة احتياطية..."
pg_dump -U postgres your_database > "$BACKUP_DIR/db_$DATE.sql"
cp -r "$PROJECT_DIR" "$BACKUP_DIR/code_$DATE"

# وضع الصيانة
cd "$PROJECT_DIR"
php artisan down --message="جاري التحديث..." --retry=60

# سحب التحديثات
echo "📥 سحب التحديثات..."
git pull origin main

# تثبيت التبعيات
echo "📚 تثبيت التبعيات..."
composer install --no-dev --optimize-autoloader

# الترحيلات
echo "🗄️ تحديث قاعدة البيانات..."
php artisan migrate --force

# الكاش
echo "🔄 تحديث الكاش..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# رفع وضع الصيانة
php artisan up

echo "✅ تم النشر بنجاح!"
```

---

## ملاحظات مهمة

1. **لا تنسَ النسخ الاحتياطي** قبل أي تحديث
2. **اختبر في بيئة تجريبية** أولاً إن أمكن
3. **وقت الصيانة** سيكون قصيراً (1-2 دقيقة)
4. **الصلاحيات الموجودة** ستعمل بدون تغيير
5. **المستخدمين الحاليين** لن يتأثروا

---

## الدعم

إذا واجهت أي مشكلة، تواصل للمساعدة مع تقديم:
- رسالة الخطأ كاملة
- اسم الخطوة التي توقفت عندها
- نوع السيرفر المستخدم
