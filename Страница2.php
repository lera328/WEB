<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категория комплектующих</title>
    <link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
    <?php
    // Подключение к базе данных
    $servername = "localhost";
    $username = "root"; 
    $password = "741852Bora!";
    $dbname = "ACCESSORIES";
    
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Ошибка подключения: " . $e->getMessage());
    }
    
    // Получаем категорию из параметра URL
    $current_category = isset($_GET['category']) ? $_GET['category'] : '';
    ?>
    
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
            <?php if (!empty($_SESSION['admin_logged_in'])): ?>
                <li><a href="admin.php" style="background: #1a2536; color: white; padding: 8px 16px; font-weight: bold; text-align: center; transition: all 0.3s ease;">Админ-панель</a></li>
                <li><a href="logout.php" style="background: #dc3545; color: white; padding: 8px 16px; font-weight: bold; text-align: center; transition: all 0.3s ease;">Выйти</a></li>
            <?php else: ?>
                <li><a href="auth.php" class="auth-button">Авторизация</a></li>
            <?php endif; ?>
        </ul>
        <script>
            document.querySelector('.menu-toggle').addEventListener('click', function() {
                this.classList.toggle('active');
                document.querySelector('.menu').classList.toggle('active');
            });
        </script>
    </div>

    <main class="main-content">
        <?php
        try {
            // Получаем компоненты выбранной категории
            $stmt = $conn->prepare("SELECT * FROM accessories_ WHERE category = :category");
            $stmt->bindParam(':category', $current_category);
            $stmt->execute();
            $components = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "<h2>" . htmlspecialchars(ucfirst($current_category)) . "</h2>";
            
            if (count($components) > 0) {
                echo '<div class="components-list">';
                
                foreach ($components as $component) {
                    echo '<div class="component-card" onclick="document.getElementById(\'modal-' . $component['id'] . '\').style.display=\'block\'">';
                    echo '<img src="' . htmlspecialchars($component['picture']) . '" alt="' . htmlspecialchars($component['name']) . '">';
                    echo '<div class="component-name">' . htmlspecialchars($component['name']) . '</div>';
                    echo '</div>';
                    
                    // Модальное окно для компонента
                    echo '<div id="modal-' . $component['id'] . '" class="modal">';
                    echo '<div class="modal-content">';
                    echo '<span class="close" onclick="document.getElementById(\'modal-' . $component['id'] . '\').style.display=\'none\'">&times;</span>';
                    echo '<h2>' . htmlspecialchars($component['name']) . '</h2>';
                    echo '<div class="detail-item"><strong>Характеристика 1:</strong> ' . htmlspecialchars($component['har1']) . '</div>';
                    echo '<div class="detail-item"><strong>Характеристика 2:</strong> ' . htmlspecialchars($component['har2']) . '</div>';
                    echo '<div class="detail-item"><strong>Характеристика 3:</strong> ' . htmlspecialchars($component['har3']) . '</div>';
                    echo '<div class="price">' . number_format($component['price'], 0, '', ' ') . ' ₽</div>';
                    echo '</div></div>';
                }
                
                echo '</div>';
            } else {
                echo "<div class='no-data'>В этой категории пока нет товаров</div>";
            }
        } catch(PDOException $e) {
            echo "<div class='error'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
        
        $conn = null;
        ?>
    </main>

    <script>
        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            if (event.target.className === 'modal') {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
