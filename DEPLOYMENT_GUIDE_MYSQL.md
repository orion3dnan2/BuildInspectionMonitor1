# دليل نشر التحديثات - MySQL/XAMPP
# نظام إدارة الرقابة والتفتيش

---

## المتطلبات
- PHP 8.2+
- Composer
- XAMPP (MySQL)
- الوصول للسيرفر

---

## الخطوة 1: النسخ الاحتياطي (مهم جداً!)

### من phpMyAdmin:
1. افتح phpMyAdmin
2. اختر قاعدة البيانات
3. اضغط "Export" ← "Go"
4. احفظ ملف `.sql`

### أو من Command Line:
```bash
# Windows (XAMPP)
C:\xampp\mysql\bin\mysqldump -u root -p your_database > backup.sql

# Linux
mysqldump -u root -p your_database > backup.sql
```

---

## الخطوة 2: تحضير ملف .env

تأكد من إعدادات MySQL في `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=records_system
DB_USERNAME=root
DB_PASSWORD=
```

---

## الخطوة 3: رفع الكود الجديد

1. حمّل الكود من Replit (Download as ZIP)
2. فك الضغط
3. انسخ الملفات للسيرفر (ما عدا `.env`)

---

## الخطوة 4: تفعيل وضع الصيانة

```bash
cd C:\xampp\htdocs\your_project
php artisan down --message="جاري تحديث النظام..."
```

---

## الخطوة 5: تثبيت التبعيات

```bash
composer install --no-dev --optimize-autoloader
```

---

## الخطوة 6: تشغيل الترحيلات

```bash
php artisan migrate --force
```

---

## الخطوة 7: إزالة قيد record_number الفريد

### الخيار أ: من phpMyAdmin
1. افتح phpMyAdmin
2. اختر جدول `records`
3. اضغط "Structure"
4. ابحث عن `record_number` ← اضغط "Drop" على الـ Index/Unique

### الخيار ب: من Command Line
```bash
php artisan tinker
```
ثم:
```php
DB::statement('ALTER TABLE records DROP INDEX records_record_number_unique');
exit
```

### الخيار ج: SQL مباشر في phpMyAdmin
```sql
ALTER TABLE records DROP INDEX records_record_number_unique;
```

---

## الخطوة 8: مزامنة الصلاحيات

```bash
php artisan tinker
```
ثم:
```php
// إضافة صلاحيات data_entry لدور user
$userRole = \App\Models\Role::where('slug', 'user')->first();
if ($userRole) {
    $dataEntryPerms = \App\Models\Permission::where('module', 'data_entry')->pluck('id');
    $userRole->permissions()->syncWithoutDetaching($dataEntryPerms);
    echo "تم إضافة الصلاحيات بنجاح\n";
}
exit
```

---

## الخطوة 9: مسح وتحديث الكاش

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear
```

---

## الخطوة 10: إلغاء وضع الصيانة

```bash
php artisan up
```

---

## الخطوة 11: التحقق

1. ✅ افتح الموقع وسجل دخول بـ admin
2. ✅ تحقق من صفحة الإعدادات
3. ✅ جرب إضافة سجل بنفس رقم الصادر
4. ✅ تحقق من رقم التتبع التلقائي

---

## خطة الطوارئ

### استعادة قاعدة البيانات:
1. من phpMyAdmin ← Import ← اختر ملف backup.sql
2. أو:
```bash
mysql -u root -p your_database < backup.sql
```

---

## ملاحظة مهمة لـ XAMPP

إذا كنت تستخدم XAMPP على Windows:
```bash
# افتح CMD كـ Administrator
cd C:\xampp\htdocs\your_project

# أو استخدم Git Bash
```

---

## الفرق بين PostgreSQL و MySQL

| الأمر | PostgreSQL | MySQL |
|-------|-----------|-------|
| إزالة constraint | `DROP CONSTRAINT` | `DROP INDEX` |
| النسخ الاحتياطي | `pg_dump` | `mysqldump` |
| الاستعادة | `psql` | `mysql` |

---

## جدول الأوامر السريع

```bash
# 1. نسخة احتياطية
mysqldump -u root -p your_db > backup.sql

# 2. صيانة
php artisan down

# 3. تبعيات
composer install --no-dev --optimize-autoloader

# 4. ترحيلات
php artisan migrate --force

# 5. إزالة القيد (في tinker)
DB::statement('ALTER TABLE records DROP INDEX records_record_number_unique');

# 6. كاش
php artisan config:cache && php artisan route:cache && php artisan view:cache

# 7. رفع الصيانة
php artisan up
```
