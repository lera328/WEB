<?php
// Проверка авторизации
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: auth.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
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
            <li><a href="admin.php" style="background: #1a2536; color: white; padding: 8px 16px; font-weight: bold; text-align: center; transition: all 0.3s ease;">Админ-панель</a></li>
            <li><a href="#">Акции</a></li>
            <li><a href="#">О нас</a></li>
            <li><a href="#">Контакты</a></li>
        </ul>
        <script>
            document.querySelector('.menu-toggle').addEventListener('click', function() {
                this.classList.toggle('active');
                document.querySelector('.menu').classList.toggle('active');
            });
        </script>
    </div>

    <main class="main-content">
        <a href="logout.php" class="logout-button">Выйти</a>
        
        <div class="admin-container">
            <div class="admin-header">
                <h1>Админ-панель</h1>
                <p>Управление товарами и каталогом</p>
            </div>
            
            <div class="welcome-message">
                Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']); ?>! Вы вошли как администратор.
            </div>
            
            <div class="admin-buttons">
                <a href="add_product.php" class="admin-button">
                    <div class="admin-button-icon">➕</div>
                    <div class="admin-button-title">Добавить товар</div>
                    <div class="admin-button-desc">Создать новый товар в каталоге</div>
                </a>
                
                <a href="manage_products.php" class="admin-button">
                    <div class="admin-button-icon">📋</div>
                    <div class="admin-button-title">Посмотреть, отредактировать, удалить товары</div>
                    <div class="admin-button-desc">Просмотреть и изменить полный список товаров</div>
                </a>
            </div>
        </div>
    </main>
</body>
</html>
