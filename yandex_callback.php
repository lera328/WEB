<?php
require_once 'config_yandex.php';
require_once 'database_config.php';

// Проверяем наличие кода авторизации
if (!isset($_GET['code'])) {
    header('Location: auth.php?error=yandex_cancelled');
    exit();
}

$code = $_GET['code'];

// Обмен кода на токен доступа
$token_data = [
    'grant_type' => 'authorization_code',
    'code' => $code,
    'client_id' => YANDEX_CLIENT_ID,
    'client_secret' => YANDEX_CLIENT_SECRET,
    'redirect_uri' => YANDEX_REDIRECT_URI
];

$ch = curl_init(YANDEX_TOKEN_URL);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($token_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    header('Location: auth.php?error=yandex_token_failed');
    exit();
}

$token_info = json_decode($response, true);

if (!isset($token_info['access_token'])) {
    header('Location: auth.php?error=yandex_token_invalid');
    exit();
}

$access_token = $token_info['access_token'];

// Получение информации о пользователе
$ch = curl_init(YANDEX_USER_INFO_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: OAuth ' . $access_token]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$user_response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
    header('Location: auth.php?error=yandex_user_info_failed');
    exit();
}

$user_info = json_decode($user_response, true);

// Проверяем, есть ли пользователь в базе данных
try {
    $conn = getDB();
    
    $yandex_id = $user_info['id'];
    $email = $user_info['default_email'] ?? '';
    $name = $user_info['real_name'] ?? $user_info['display_name'] ?? 'Yandex User';
    $login = 'yandex_' . $yandex_id;
    
    // Проверяем, существует ли пользователь с таким Yandex ID
    $stmt = $conn->prepare("SELECT * FROM admins WHERE yandex_id = ?");
    $stmt->execute([$yandex_id]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Пользователь существует, обновляем данные
        $stmt = $conn->prepare("UPDATE admins SET last_login = NOW(), email = ? WHERE yandex_id = ?");
        $stmt->execute([$email, $yandex_id]);
    } else {
        // Создаем нового пользователя
        $password_hash = password_hash(uniqid(), PASSWORD_DEFAULT); // Случайный пароль
        $stmt = $conn->prepare("INSERT INTO admins (username, password, email, yandex_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$login, $password_hash, $email, $yandex_id]);
    }
    
    // Устанавливаем сессию
    session_start();
    $_SESSION['user'] = $login;
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['yandex_user'] = true;
    $_SESSION['user_info'] = [
        'name' => $name,
        'email' => $email,
        'yandex_id' => $yandex_id
    ];
    
    // Перенаправляем в админ-панель
    header('Location: admin.php');
    exit();
    
} catch(PDOException $e) {
    header('Location: auth.php?error=database_error');
    exit();
}
?>
