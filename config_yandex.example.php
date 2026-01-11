<?php
// Конфигурация Яндекс ID OAuth
// ЗАМЕНИТЕ ЭТИ ЗНАЧЕНИЯ НА ВАШИ ДАННЫЕ
define('YANDEX_CLIENT_ID', 'your_client_id_here'); // Client ID из Яндекс ID
define('YANDEX_CLIENT_SECRET', 'your_client_secret_here'); // Client Secret из Яндекс ID
define('YANDEX_REDIRECT_URI', 'http://localhost:8000/yandex_callback.php'); // URL для редиректа

// URL для авторизации через Яндекс ID
define('YANDEX_AUTH_URL', 'https://oauth.yandex.ru/authorize');
define('YANDEX_TOKEN_URL', 'https://oauth.yandex.ru/token');
define('YANDEX_USER_INFO_URL', 'https://login.yandex.ru/info');

// Сессия для хранения состояния OAuth
session_start();
?>
