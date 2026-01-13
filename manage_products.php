<?php
session_start();

// Проверяем, авторизован ли администратор
if (empty($_SESSION['admin_logged_in'])) {
    header('Location: auth.php');
    exit();
}

// Подключение к базе данных
$servername = "localhost";
$username = "root"; 
$password = "741852Bora!";
$dbname = "ACCESSORIES";

$conn = null;
$products = [];
$error = '';
$success = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Обработка удаления товара
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];
        
        if ($delete_id > 0) {
            // Сначала получаем информацию о товаре для сообщения
            $stmt = $conn->prepare("SELECT name FROM accessories_ WHERE id = ?");
            $stmt->execute([$delete_id]);
            $product_to_delete = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($product_to_delete) {
                // Удаляем товар
                $stmt = $conn->prepare("DELETE FROM accessories_ WHERE id = ?");
                $result = $stmt->execute([$delete_id]);
                
                if ($result) {
                    $success = "Товар \"" . htmlspecialchars($product_to_delete['name']) . "\" успешно удален!";
                } else {
                    $error = "Ошибка при удалении товара";
                }
            } else {
                $error = "Товар не найден";
            }
        }
    }
    
    // Получаем все товары
    $stmt = $conn->prepare("SELECT * FROM accessories_ ORDER BY category, name");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Отладочная информация
    if (empty($products)) {
        $error .= " (Товары не найдены в БД)";
    } else {
        $success .= " (Найдено товаров: " . count($products) . ")";
    }
    
} catch(PDOException $e) {
    $error = "Ошибка подключения: " . $e->getMessage();
    $products = [];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление товарами</title>
    <link href="style.css?v=3.1" rel="stylesheet" type="text/css">
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
            <li><a href="logout.php" style="background: #dc3545; color: white; padding: 8px 16px; font-weight: bold; text-align: center; transition: all 0.3s ease;">Выйти</a></li>
        </ul>
        <script>
            document.querySelector('.menu-toggle').addEventListener('click', function() {
                this.classList.toggle('active');
                document.querySelector('.menu').classList.toggle('active');
            });
        </script>
    </div>

    <main class="main-content">
        <a href="admin.php" class="back-button">← Назад к админ-панели</a>
        
        <div class="products-container test-style">
            <div class="products-header">
                <h1>Управление товарами</h1>
                <p>Все товары из каталога</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($products)): ?>
                <div class="no-products">
                    В каталоге пока нет товаров
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card">
                            <div class="product-category"><?php echo htmlspecialchars($product['category']); ?></div>
                            <img src="<?php echo htmlspecialchars($product['picture']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            <div class="product-name"><?php echo htmlspecialchars($product['name']); ?></div>
                            <div class="product-price"><?php echo number_format($product['price'], 0, '', ' '); ?> ₽</div>
                            <div class="product-details">
                                <div> <?php echo htmlspecialchars($product['har1']); ?></div>
                                <div> <?php echo htmlspecialchars($product['har2']); ?></div>
                                <div> <?php echo htmlspecialchars($product['har3']); ?></div>
                            </div>
                            <div class="product-actions">
                                <button class="btn-edit" onclick="window.location.href='edit_product.php?id=<?php echo $product['id']; ?>'">Редактировать</button>
                                <button class="btn-delete" onclick="if(confirm('Вы уверены, что хотите удалить товар: <?php echo htmlspecialchars($product['name']); ?>?')) { window.location.href='manage_products.php?delete_id=<?php echo $product['id']; ?>' }">Удалить</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
