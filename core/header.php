<?php

/** Error Report Configuration */
ini_set('display_errors', STATUS_PROJECT === "DEVELOPMENT" ? 'On' : 'Off');
ini_set('display_startup_errors', STATUS_PROJECT === "DEVELOPMENT" ? 'On' : 'Off');
error_reporting(E_ALL);

/** Initialize headers */
date_default_timezone_set('Asia/Tehran');
header("Content-Type: application/json");

ob_start();

// گرفتن Origin از درخواست
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origin = null;

// چک کردن Origin مجاز
if ($origin) {
    if (STATUS_PROJECT === "PRODUCT") {
        if (in_array($origin, AUTHORIZED_URL_PRODUCT)) {
            $allowed_origin = $origin;
        }
    } else {
        if (in_array($origin, AUTHORIZED_URL_DEVELOPMENT)) {
            $allowed_origin = $origin;
        }
    }
} elseif (STATUS_PROJECT === "DEVELOPMENT") {
    // توی DEVELOPMENT، اگه Origin خالی باشه، اجازه بده
    $allowed_origin = 'https://ntapi.ir'; // دامنه سرور
}

// مدیریت درخواست OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    if ($allowed_origin) {
        header("Access-Control-Allow-Origin: $allowed_origin");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization");
        header("Access-Control-Max-Age: 86400");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Length: 0");
        http_response_code(204);
        ob_end_clean();
        exit();
    } else {
        header("Content-Type: application/json");
        http_response_code(403);
        echo json_encode(['error' => 'Origin not allowed']);
        ob_end_flush();
        exit();
    }
}

// متدهای مجاز
$allowed_methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    header("Content-Type: application/json");
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    ob_end_flush();
    exit();
}

// تنظیم CORS برای درخواست‌های اصلی
if ($allowed_origin) {
    header("Access-Control-Allow-Origin: $allowed_origin");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Accept, Authorization");
} else {
    header("Content-Type: application/json");
    http_response_code(403);
    echo json_encode(['error' => 'Origin not allowed']);
    ob_end_flush();
    exit();
}
