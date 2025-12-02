#!/bin/bash
set -e

echo "========================================"
echo "  نظام إدارة الرقابة والتفتيش"
echo "  سكريبت النشر - MySQL/XAMPP"
echo "========================================"
echo ""

PROJECT_DIR="${1:-.}"
cd "$PROJECT_DIR"

echo "⚠️  تأكد من أخذ نسخة احتياطية قبل المتابعة!"
echo "   mysqldump -u root -p your_database > backup.sql"
echo ""
echo "اضغط Enter للمتابعة أو Ctrl+C للإلغاء..."
read

echo ""
echo "🔧 الخطوة 1: تفعيل وضع الصيانة..."
php artisan down --message="جاري تحديث النظام..." --retry=60 || true

echo ""
echo "📚 الخطوة 2: تثبيت التبعيات..."
composer install --no-dev --optimize-autoloader

echo ""
echo "🗄️ الخطوة 3: تشغيل الترحيلات..."
php artisan migrate --force

echo ""
echo "🔑 الخطوة 4: إزالة قيد record_number الفريد..."
php artisan tinker --execute="try { DB::statement('ALTER TABLE records DROP INDEX records_record_number_unique'); echo 'تم إزالة القيد'; } catch(\Exception \$e) { echo 'القيد غير موجود أو تم إزالته مسبقاً'; }" || echo "تم بالفعل"

echo ""
echo "🔄 الخطوة 5: مسح وتحديث الكاش..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan cache:clear

echo ""
echo "✅ الخطوة 6: إلغاء وضع الصيانة..."
php artisan up

echo ""
echo "========================================"
echo "  ✅ تم النشر بنجاح!"
echo "========================================"
echo ""
echo "📋 تحقق من:"
echo "   - تسجيل الدخول يعمل"
echo "   - الصلاحيات تعمل"
echo "   - إضافة سجل بنفس رقم الصادر"
echo ""
