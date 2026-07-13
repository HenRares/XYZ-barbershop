<?php

declare(strict_types=1);

$basePath   = dirname(__DIR__);
$publicPath = $basePath . DIRECTORY_SEPARATOR . 'public';

/*
|--------------------------------------------------------------------------
| Writable directory untuk Vercel
|--------------------------------------------------------------------------
*/

putenv('VIEW_COMPILED_PATH=/tmp/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_SERVER['VIEW_COMPILED_PATH'] = '/tmp/views';

if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0777, true);
}

/*
|--------------------------------------------------------------------------
| Melayani file CSS, JavaScript, gambar, dan font
|--------------------------------------------------------------------------
*/

$uri = rawurldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'
);

$relativePath = ltrim($uri, '/');
$requestedFile = realpath(
    $publicPath . DIRECTORY_SEPARATOR . $relativePath
);

$realPublicPath = realpath($publicPath);

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
    $extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));

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

require $publicPath . DIRECTORY_SEPARATOR . 'index.php';