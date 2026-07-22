<?php

return [
    'name' => env('APP_NAME', 'XYZ Barbershop'),
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => env('APP_TIMEZONE', 'Asia/Jakarta'),
    'locale' => env('APP_LOCALE', 'id'),
    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),
    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),
    'cipher' => 'AES-256-CBC',
    'key' => env('APP_KEY'),
    'previous_keys' => [...array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))],
    'cron_secret' => env('CRON_SECRET'),
    'seed_admin' => [
        'name' => env('SEED_ADMIN_NAME', 'Admin XYZ'),
        'phone' => env('SEED_ADMIN_PHONE', '080000000000'),
        'email' => env('SEED_ADMIN_EMAIL'),
        'password' => env('SEED_ADMIN_PASSWORD'),
    ],
    'maintenance' => ['driver' => env('APP_MAINTENANCE_DRIVER', 'file'), 'store' => env('APP_MAINTENANCE_STORE', 'database')],
];
