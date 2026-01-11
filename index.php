<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="style.css" rel="stylesheet" type="text/css">
    <title>Комплектующие для ПК</title>
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
            <li><a href="main.html">Главная</a></li>
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
        
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
            <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']); ?>! Вы вошли как администратор.
            </div>
        <?php endif; ?>
        
        <h2>Комплектующие для ПК</h2>
        <div class="components-list">
            <div class="component-card">
                <img src="https://cdn1.youla.io/files/images/780_780/65/2b/652b6a2e526e04b3a90b9986-2.jpg" alt="Основные комплектующие"> 
                <div class="component-name">Основные комплектующие</div>
            </div>
            <div class="component-card">
                <img src="https://main-cdn.sbermegamarket.ru/big1/hlr-system/974/712/518/122/422/25/100052035130b0.jpg" alt="Устройства расширения"> 
                <div class="component-name">Устройства расширения</div>
            </div>
            <div class="component-card">
                <img src="https://p1.zoon.ru/f/8/555a00bd40c08829298efbee_5b0d8ae5d90a6.jpg" alt="Моддинг и обслуживание"> 
                <div class="component-name">Моддинг и обслуживание</div>
            </div>
            <div class="component-card">
                <img src="https://avatars.mds.yandex.net/i?id=abd7dc50225b84c22e92eeb9c115b07c3eb9b5fa-5530840-images-thumbs&n=13" alt="Комплектующие"> 
                <div class="component-name">Основные комплектующие для ПК</div>
            </div>
        </div>
        
        <h2>Популярные категории</h2>
        <div class="components-list">
        <?php
// Подключение к базе данных
$servername = "localhost";
$username = "root"; 
$password = "741852Bora!";
$dbname = "ACCESSORIES";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Запрос для получения уникальных категорий и первого изображения для каждой
    $sql = "SELECT DISTINCT a1.category, 
            (SELECT a2.picture FROM accessories_ a2 WHERE a2.category = a1.category LIMIT 1) as image 
            FROM accessories_ a1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Получаем все категории
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($categories) > 0) {
        foreach ($categories as $category) {
            echo '<a href="Страница2.php?category=' . urlencode($category['category']) . '"><div class="component-card">';
            echo '<img src="' . htmlspecialchars($category['image']) . '" alt="' . htmlspecialchars($category['category']) . '">';
            echo '<div class="component-name">' . htmlspecialchars($category['category']) . '</div>';
            echo '</div></a>';
        }
    } else {
        echo "пока нет данных";
    }
} catch(PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
}
$conn = null;
?>

        </div>
        

        <h2>Производители</h2>
        <div class="manufacturers">
            <div class="manufacturer">ASUS</div>
            <div class="manufacturer">acer</div>
            <div class="manufacturer">Acer</div>
            <div class="manufacturer">Apple</div>
            <div class="manufacturer">Asquarius</div>
            <div class="manufacturer">COMPAQ</div>
            <div class="manufacturer">Compaq</div>
            <div class="manufacturer">DELL</div>
            <div class="manufacturer">DNS</div>
        </div>
    </main>
    
</body>
</html>
