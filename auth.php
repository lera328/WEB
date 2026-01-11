<?php
// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_login    = $_POST['username'] ?? '';
    $input_password = $_POST['password'] ?? '';

    try {
        $conn = new PDO("mysql:host=localhost;dbname=ACCESSORIES", "root", "741852Bora!");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
        $stmt->execute([$input_login]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $error = "Пользователь не найден";
        } else {
            // Проверка пароля (здесь будет продолжение)
            if (password_verify($input_password, $user['password'])) {
                // Успешная авторизация
                session_start();
                $_SESSION['user'] = $input_login;
                header('Location: index.php');
                exit();
            } else {
                $error = "Неверный пароль";
            }
        }
    } catch(PDOException $e) {
        $error = "Ошибка соединения с БД: " . $e->getMessage();
    }
}

// Выбор конфигурации в зависимости от окружения
if (getenv('PORT') || strpos($_SERVER['HTTP_HOST'] ?? '', '.onrender.com') !== false) {
    require_once 'config_production.php';
} else {
    require_once 'config_yandex.php';
}

// Генерируем состояние для защиты от CSRF
session_start();
$_SESSION['yandex_state'] = bin2hex(random_bytes(16));
$yandex_auth_url = YANDEX_AUTH_URL . '?' . http_build_query([
    'response_type' => 'code',
    'client_id' => YANDEX_CLIENT_ID,
    'redirect_uri' => YANDEX_REDIRECT_URI,
    'state' => $_SESSION['yandex_state'],
    'scope' => 'login:email login:info'
]);

// Функция для отображения формы с ошибкой
function showError($error) {
    return "<div style='color: red; margin-bottom: 15px; text-align: center;'>$error</div>";
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href="style.css?v=1.4" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="https://20.img.avito.st/image/1/1.U1irNba26bGdkn23gQAYN9yX_bEXtP2zHYL9.gRljDo8KkuQCNStrHOmGgCLdZMsD-3jDaChAD8L2ZZU" alt="Logo">
        </div>
        <div class="menu-toggle">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <ul class="menu">
            <li><a href="index.php">Главная</a></li>
            <li><a href="#">Акции</a></li>
            <li><a href="#">О нас</a></li>
            <li><a href="#">Контакты</a></li>
            <li><a href="auth.php" style="background: #1a2536; color: white; padding: 8px 16px; font-weight: bold; text-align: center; transition: all 0.3s ease;">Авторизация</a></li>
        </ul>
        <script>
            document.querySelector('.menu-toggle').addEventListener('click', function() {
                this.classList.toggle('active');
                document.querySelector('.menu').classList.toggle('active');
            });
        </script>
    </div>

    <main class="main-content">
        <div class="auth-container">
            <h2>Авторизация</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="username">Логин:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="auth-button">Войти</button>
            </form>
            
            <div class="divider">
                <span>или</span>
            </div>
            
            <button class="yandex-auth-button" onclick="window.location.href='<?php echo $yandex_auth_url; ?>'">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="10" r="10" fill="#FFCC00"/>
                    <rect x="7" y="7" width="6" height="6" fill="#000"/>
                    <polygon points="10,4 7,6 13,6" fill="#000"/>
                    <polygon points="10,16 13,14 7,14" fill="#000"/>
                </svg>
                Авторизоваться через Яндекс
            </button>
            
            <div class="auth-links">
                <p><a href="index.php">На главную</a></p>
            </div>
        </div>
    </main>
</body>
</html>
