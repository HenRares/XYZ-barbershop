@echo off
if not exist .env copy .env.example .env
if not exist database\database.sqlite type nul > database\database.sqlite
call composer install
php artisan key:generate
php artisan migrate --seed
call npm install
call npm run build
echo Selesai. Jalankan: php artisan serve
