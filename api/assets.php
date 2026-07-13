<?php

declare(strict_types=1);

$publicPath = realpath(__DIR__ . '/../public');
$relativePath = $_GET['file'] ?? '';

$relativePath = rawurldecode($relativePath);
$relativePath = ltrim($relativePath, '/\\');

if (
    $publicPath === false ||
    $relativePath === '' ||
    str_contains($relativePath, "\0")
) {
    http_response_code(404);
    exit('File not found');
}

$requestedFile = realpath(
    $publicPath . DIRECTORY_SEPARATOR .
    str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativePath)
);

if (
    $requestedFile === false ||
    !is_file($requestedFile) ||
    !str_starts_with(
        $requestedFile,
        $publicPath . DIRECTORY_SEPARATOR
    )
) {
    http_response_code(404);
    exit('File not found');
}

$extension = strtolower(pathinfo($requestedFile, PATHINFO_EXTENSION));

$mimeTypes = [
    'css'   => 'text/css; charset=UTF-8',
    'js'    => 'application/javascript; charset=UTF-8',
    'json'  => 'application/json; charset=UTF-8',
    'map'   => 'application/json; charset=UTF-8',
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
header('X-Content-Type-Options: nosniff');
header('Cache-Control: public, max-age=31536000, immutable');

readfile($requestedFile);
exit;