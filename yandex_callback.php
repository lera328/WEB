<?php
require_once 'config_yandex.php';
require_once 'database_config.php';

// Получаем код от Яндекса
$code = $_GET['code'] ?? '';

if (empty($code)) {
    die('Ошибка: код авторизации не получен');
}

// РЕАЛЬНЫЙ ЗАПРОС К API ЯНДЕКСА
function makeHttpRequest($url, $postData = null, $headers = []) {
    if (!ini_get('allow_url_fopen') || !extension_loaded('openssl')) {
        die('Для работы Яндекс OAuth требуются OpenSSL и allow_url_fopen. Включите в php.ini: extension=openssl');
    }
    
    $options = [
        'http' => [
            'method' => $postData ? 'POST' : 'GET',
            'timeout' => 30,
            'ignore_errors' => true
        ]
    ];
    
    if ($postData) {
        $options['http']['header'] = 'Content-Type: application/x-www-form-urlencoded';
        $options['http']['content'] = $postData;
    }
    
    if (!empty($headers)) {
        $options['http']['header'] = $options['http']['header'] ?? '';
        $options['http']['header'] .= "\r\n" . implode("\r\n", $headers);
    }
    
    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        $error = error_get_last();
        die('Ошибка запроса к API Яндекса: ' . ($error['message'] ?? 'неизвестная ошибка'));
    }
    
    // Получаем HTTP код ответа
    if (function_exists('http_get_last_response_headers')) {
        $headers = http_get_last_response_headers();
        $status_line = $headers[0] ?? '';
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $matches);
        $http_code = $matches[1] ?? 0;
    } else {
        $http_code = 200;
    }
    
    return ['response' => $response, 'http_code' => $http_code];
}

// 1. Обмен кода на токен
$tokenResult = makeHttpRequest(
    YANDEX_TOKEN_URL,
    http_build_query([
        'grant_type' => 'authorization_code',
        'code' => $code,
        'client_id' => YANDEX_CLIENT_ID,
        'client_secret' => YANDEX_CLIENT_SECRET,
        'redirect_uri' => YANDEX_REDIRECT_URI
    ])
);

if ($tokenResult['http_code'] != 200) {
    die('Ошибка получения токена: HTTP ' . $tokenResult['http_code'] . '. Ответ: ' . $tokenResult['response']);
}

$token_data = json_decode($tokenResult['response'], true);

if (!isset($token_data['access_token'])) {
    die('Ошибка в ответе токена: ' . $tokenResult['response']);
}

// 2. Получение данных пользователя
$userResult = makeHttpRequest(
    YANDEX_USER_INFO_URL,
    null,
    ['Authorization: Bearer ' . $token_data['access_token']]
);

if ($userResult['http_code'] != 200) {
    die('Ошибка получения данных пользователя: HTTP ' . $userResult['http_code'] . '. Ответ: ' . $userResult['response']);
}

$user_data = json_decode($userResult['response'], true);

if (!isset($user_data['id'])) {
    die('Ошибка в данных пользователя: ' . $userResult['response']);
}

// Обрабатываем авторизацию с реальными данными
processYandexAuth($user_data);

function processYandexAuth($user_data) {
    try {
        $conn = getDB();
        
        // Проверяем, есть ли администратор с таким yandex_id
        $stmt = $conn->prepare("SELECT username FROM admins WHERE yandex_id = ?");
        $stmt->execute([$user_data['id']]);
        $admin = $stmt->fetch();
        
        if ($admin) {
            // Администратор найден - создаем сессию и входим в админ-панель
            session_start();
            $_SESSION['user'] = $admin['username'];
            $_SESSION['yandex_user'] = true;
            $_SESSION['yandex_id'] = $user_data['id'];
            $_SESSION['user_info'] = $user_data;
            $_SESSION['admin_logged_in'] = true;
            
            // Перенаправляем в админ-панель
            header('Location: admin.php');
            exit();
            
        } else {
            // Администратор не найден - перенаправляем на страницу авторизации с ошибкой
            session_start();
            $_SESSION['error'] = 'Авторизация доступна только для администраторов. Вы не являетесь администратором';
            $_SESSION['debug_info'] = [
                'yandex_id' => $user_data['id'],
                'user_data' => $user_data
            ];
            
            // Перенаправляем на страницу авторизации
            header('Location: auth.php');
            exit();
        }
        
    } catch(PDOException $e) {
        die("Ошибка работы с базой данных: " . $e->getMessage());
    }
}
?>
