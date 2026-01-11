<?php
// Конфигурация Яндекс ID OAuth для продакшена
define('YANDEX_CLIENT_ID', getenv('YANDEX_CLIENT_ID') ?: 'your_client_id_here');
define('YANDEX_CLIENT_SECRET', getenv('YANDEX_CLIENT_SECRET') ?: 'your_client_secret_here');

// Автоматическое определение URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];
define('YANDEX_REDIRECT_URI', $protocol . '://' . $host . '/yandex_callback.php');

// URL для авторизации через Яндекс ID
define('YANDEX_AUTH_URL', 'https://oauth.yandex.ru/authorize');
define('YANDEX_TOKEN_URL', 'https://oauth.yandex.ru/token');
define('YANDEX_USER_INFO_URL', 'https://login.yandex.ru/info');

// Сессия для хранения состояния OAuth
session_start();
?>
