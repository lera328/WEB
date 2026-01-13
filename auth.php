<?php
session_start();

// Проверяем есть ли ошибка от Яндекс OAuth
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']); // Удаляем ошибку после отображения
}

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
            if (password_verify($input_password, $user['password'])) {
                // Успешная авторизация
                $_SESSION['user'] = $input_login;
                $_SESSION['admin_logged_in'] = true;
                header('Location: admin.php');
                exit();
            } else {
                $error = "Неверный пароль";
            }
        }
    } catch(PDOException $e) {
        $error = "Ошибка соединения с БД: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link href="style.css?v=4.0" rel="stylesheet" type="text/css">
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
                document.querySelector('.menu').toggleClass('active');
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
            
            <a href="<?php 
                require_once 'config_yandex.php';
                $params = [
                    'response_type' => 'code',
                    'client_id' => YANDEX_CLIENT_ID,
                    'redirect_uri' => YANDEX_REDIRECT_URI
                ];
                echo 'https://oauth.yandex.ru/authorize?' . http_build_query($params);
            ?>" class="yandex-auth-button">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                Войти через Яндекс
            </a>
            
            <div class="auth-links">
                <p><a href="index.php">На главную</a></p>
            </div>
        </div>
    </main>
</body>
</html>
