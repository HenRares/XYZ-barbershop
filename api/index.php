<?php

declare(strict_types=1);

use Illuminate\Http\Request;

$basePath   = dirname(__DIR__);
$publicPath = $basePath . DIRECTORY_SEPARATOR . 'public';

/*
|--------------------------------------------------------------------------
| Folder writable Vercel
|--------------------------------------------------------------------------
*/

$tmpStorage = '/tmp/storage';
$tmpCache   = '/tmp/laravel-cache';

$directories = [
    $tmpStorage,
    $tmpStorage . '/app',
    $tmpStorage . '/app/public',
    $tmpStorage . '/framework',
    $tmpStorage . '/framework/cache',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/framework/views',
    $tmpStorage . '/logs',
    $tmpCache,
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

/*
|--------------------------------------------------------------------------
| Arahkan seluruh cache Laravel ke /tmp
|--------------------------------------------------------------------------
*/

$runtimeVariables = [
    'LARAVEL_STORAGE_PATH' => $tmpStorage,
    'VIEW_COMPILED_PATH'   => $tmpStorage . '/framework/views',

    'APP_CONFIG_CACHE'     => $tmpCache . '/config.php',
    'APP_EVENTS_CACHE'     => $tmpCache . '/events.php',
    'APP_PACKAGES_CACHE'   => $tmpCache . '/packages.php',
    'APP_ROUTES_CACHE'     => $tmpCache . '/routes.php',
    'APP_SERVICES_CACHE'   => $tmpCache . '/services.php',
];

foreach ($runtimeVariables as $key => $value) {
    putenv($key . '=' . $value);

    $_ENV[$key]    = $value;
    $_SERVER[$key] = $value;
}

/*
|--------------------------------------------------------------------------
| Layani file statis dari folder public
|--------------------------------------------------------------------------
*/

$uri = rawurldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
);

$relativePath   = ltrim($uri, '/');
$realPublicPath = realpath($publicPath);

$requestedFile = realpath(
    $publicPath . DIRECTORY_SEPARATOR . $relativePath
);

if (
    $uri !== '/' &&
    $requestedFile !== false &&
    $realPublicPath !== false &&
    strncmp(
        $requestedFile,
        $realPublicPath . DIRECTORY_SEPARATOR,
        strlen($realPublicPath . DIRECTORY_SEPARATOR)
    ) === 0 &&
    is_file($requestedFile)
) {
    $extension = strtolower(
        pathinfo($requestedFile, PATHINFO_EXTENSION)
    );

    $mimeTypes = [
        'css'   => 'text/css',
        'js'    => 'application/javascript',
        'json'  => 'application/json',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'svg'   => 'image/svg+xml',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
    ];

    header(
        'Content-Type: ' .
        ($mimeTypes[$extension] ?? 'application/octet-stream')
    );

    header('Content-Length: ' . filesize($requestedFile));

    readfile($requestedFile);
    exit;
}

/*
|--------------------------------------------------------------------------
| Jalankan Laravel
|--------------------------------------------------------------------------
*/

require $basePath . '/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once $basePath . '/bootstrap/app.php';

$app->useStoragePath($tmpStorage);

$app->handleRequest(Request::capture());