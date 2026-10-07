<?php
/**
 * Forward root requests to index.html or public directory for XAMPP / local Apache servers.
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If user visits the root folder in XAMPP, serve index.html directly
if ($uri === '/webgiadung' || $uri === '/webgiadung/' || $uri === '/' || $uri === '') {
    if (file_exists(__DIR__ . '/index.html')) {
        header('Content-Type: text/html; charset=UTF-8');
        header('Cache-Control: no-cache, no-store, must-revalidate, max-age=0');
        readfile(__DIR__ . '/index.html');
        exit;
    }
}

$publicPath = __DIR__ . '/public';

if ($uri !== '/' && file_exists($publicPath . $uri)) {
    return false;
}

require_once $publicPath . '/index.php';
