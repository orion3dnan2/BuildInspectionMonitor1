#!/bin/bash

echo "========================================"
echo "   حل مشكلة 419 PAGE EXPIRED"
echo "========================================"

cd "$(dirname "$0")/.." || exit

echo "[1/6] إصلاح صلاحيات المجلدات..."
sudo chown -R $USER:$USER storage bootstrap/cache 2>/dev/null || chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

echo "[2/6] إنشاء المجلدات المطلوبة..."
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/logs

echo "[3/6] مسح الجلسات القديمة..."
rm -rf storage/framework/sessions/*
rm -rf storage/framework/views/*
rm -rf storage/framework/cache/*

echo "[4/6] مسح الـ cache..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "[5/6] التحقق من APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate
fi

echo "[6/6] إنشاء رابط storage..."
php artisan storage:link 2>/dev/null

echo ""
echo "========================================"
echo "   تم الإصلاح بنجاح!"
echo "========================================"
echo ""
echo "الآن شغّل السيرفر:"
echo "php artisan serve --host=0.0.0.0 --port=8000"
echo ""
