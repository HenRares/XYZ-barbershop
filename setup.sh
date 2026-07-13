#!/usr/bin/env bash
set -euo pipefail
command -v composer >/dev/null || { echo "Composer belum terpasang."; exit 1; }
command -v npm >/dev/null || { echo "Node.js/npm belum terpasang."; exit 1; }
[ -f .env ] || cp .env.example .env
[ -f database/database.sqlite ] || touch database/database.sqlite
composer install
php artisan key:generate
php artisan migrate:fresh --seed
npm install
npm run build
printf '\nSelesai. Jalankan: php artisan serve\nBuka: http://127.0.0.1:8000\n'
