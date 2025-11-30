======================================
   حل مشكلة 419 PAGE EXPIRED
   للسيرفر المحلي
======================================

الخطوات:
--------

1. انسخ مجلد fix_419_solution إلى سيرفرك المحلي

2. انسخ ملف session.php إلى config/session.php
   أمر: cp session.php ../config/session.php

3. عدّل ملف .env حسب env_settings.txt

4. نفذ سكريبت الإصلاح:
   chmod +x fix_419.sh
   ./fix_419.sh

5. شغّل السيرفر:
   php artisan serve --host=0.0.0.0 --port=8000

======================================

أو نفذ هذه الأوامر مباشرة:
-------------------------

chmod -R 775 storage bootstrap/cache
rm -rf storage/framework/sessions/*
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan serve --host=0.0.0.0 --port=8000

======================================

إذا استمرت المشكلة:
-------------------

1. تأكد أن SESSION_DRIVER=file في .env
2. تأكد أن SESSION_DOMAIN=null في .env
3. امسح cookies المتصفح
4. جرب في متصفح incognito/private

======================================
