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
$product = null;
$error = '';
$success = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Получаем ID товара из URL
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    
    if ($product_id > 0) {
        // Загружаем данные товара
        $stmt = $conn->prepare("SELECT * FROM accessories_ WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            $error = "Товар не найден";
        }
    } else {
        $error = "Не указан ID товара";
    }
    
    // Обработка формы редактирования
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $product) {
        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $price = $_POST['price'] ?? '';
        $picture = $_POST['picture'] ?? '';
        $har1 = $_POST['har1'] ?? '';
        $har2 = $_POST['har2'] ?? '';
        $har3 = $_POST['har3'] ?? '';
        
        // Валидация
        if (empty($name) || empty($category) || empty($price)) {
            $error = "Пожалуйста, заполните все обязательные поля";
        } elseif (!is_numeric($price)) {
            $error = "Цена должна быть числом";
        } else {
            // Обновляем товар в БД
            $stmt = $conn->prepare("UPDATE accessories_ SET name = ?, category = ?, price = ?, picture = ?, har1 = ?, har2 = ?, har3 = ? WHERE id = ?");
            $result = $stmt->execute([$name, $category, $price, $picture, $har1, $har2, $har3, $product_id]);
            
            if ($result) {
                $success = "Товар успешно обновлен!";
                // Перезагружаем данные товара
                $stmt = $conn->prepare("SELECT * FROM accessories_ WHERE id = ?");
                $stmt->execute([$product_id]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $error = "Ошибка при обновлении товара";
            }
        }
    }
    
} catch(PDOException $e) {
    $error = "Ошибка подключения: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование товара</title>
    <link href="style.css?v=1.3" rel="stylesheet" type="text/css">
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
        <a href="manage_products.php" class="back-button">← Назад к товарам</a>
        
        <div class="edit-container">
            <div class="edit-header">
                <h1>Редактирование товара</h1>
                <p>Изменение информации о товаре</p>
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
            
            <?php if ($product): ?>
                <div class="product-preview">
                    <img src="<?php echo htmlspecialchars($product['picture']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="product-preview-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><strong>Категория:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                        <p><strong>Текущая цена:</strong> <?php echo number_format($product['price'], 0, '', ' '); ?> ₽</p>
                    </div>
                </div>
                
                <form method="POST" class="edit-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Название товара *</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Категория *</label>
                            <select id="category" name="category" required>
                                <?php
                                // Получаем все уникальные категории из БД
                                try {
                                    $categories_stmt = $conn->query("SELECT DISTINCT category FROM accessories_ ORDER BY category");
                                    $categories = $categories_stmt->fetchAll(PDO::FETCH_COLUMN);
                                    
                                    foreach ($categories as $cat) {
                                        $selected = $product['category'] == $cat ? 'selected' : '';
                                        echo "<option value=\"" . htmlspecialchars($cat) . "\" $selected>" . htmlspecialchars($cat) . "</option>";
                                    }
                                } catch(PDOException $e) {
                                    // Если ошибка, показываем текущую категорию
                                    echo "<option value=\"" . htmlspecialchars($product['category']) . "\" selected>" . htmlspecialchars($product['category']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="price">Цена (руб.) *</label>
                            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" min="0" step="1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="picture">URL изображения</label>
                            <input type="url" id="picture" name="picture" value="<?php echo htmlspecialchars($product['picture']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="har1">Характеристика 1</label>
                        <input type="text" id="har1" name="har1" value="<?php echo htmlspecialchars($product['har1']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="har2">Характеристика 2</label>
                        <input type="text" id="har2" name="har2" value="<?php echo htmlspecialchars($product['har2']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="har3">Характеристика 3</label>
                        <input type="text" id="har3" name="har3" value="<?php echo htmlspecialchars($product['har3']); ?>">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-save">Сохранить изменения</button>
                        <a href="manage_products.php" class="btn-cancel">Назад</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
