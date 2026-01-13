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
$error = '';
$success = '';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Обработка формы добавления товара
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        } elseif (!is_numeric($price) || $price < 0) {
            $error = "Цена должна быть положительным числом";
        } else {
            // Добавляем товар в БД
            $stmt = $conn->prepare("INSERT INTO accessories_ (name, category, price, picture, har1, har2, har3) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$name, $category, $price, $picture, $har1, $har2, $har3]);
            
            if ($result) {
                $success = "Товар \"" . htmlspecialchars($name) . "\" успешно добавлен!";
                // Очищаем форму
                $name = $category = $price = $picture = $har1 = $har2 = $har3 = '';
            } else {
                $error = "Ошибка при добавлении товара";
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
    <title>Добавление товара</title>
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
        
        <div class="add-container test-style">
            <div class="add-header">
                <h1>Добавление товара</h1>
                <p>Создание нового товара в каталоге</p>
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
            
            <form method="POST" class="add-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Название товара *</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name ?? ''); ?>" required>
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
                                    $selected = ($category ?? '') == $cat ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($cat) . "\" $selected>" . htmlspecialchars($cat) . "</option>";
                                }
                            } catch(PDOException $e) {
                                // Если ошибка или категорий нет, показываем стандартные
                                $default_categories = ['Процессоры', 'Видеокарты', 'Материнские платы', 'Оперативная память', 'Накопители', 'Блоки питания', 'Корпуса', 'Охлаждение'];
                                foreach ($default_categories as $cat) {
                                    $selected = ($category ?? '') == $cat ? 'selected' : '';
                                    echo "<option value=\"" . htmlspecialchars($cat) . "\" $selected>" . htmlspecialchars($cat) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="price">Цена (руб.) *</label>
                        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($price ?? ''); ?>" min="0" step="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="picture">URL изображения</label>
                        <input type="url" id="picture" name="picture" value="<?php echo htmlspecialchars($picture ?? ''); ?>" placeholder="https://example.com/image.jpg">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="har1">Характеристика 1</label>
                    <input type="text" id="har1" name="har1" value="<?php echo htmlspecialchars($har1 ?? ''); ?>" placeholder="Например: Частота 3.5 ГГц">
                </div>
                
                <div class="form-group">
                    <label for="har2">Характеристика 2</label>
                    <input type="text" id="har2" name="har2" value="<?php echo htmlspecialchars($har2 ?? ''); ?>" placeholder="Например: 8 ядер">
                </div>
                
                <div class="form-group">
                    <label for="har3">Характеристика 3</label>
                    <input type="text" id="har3" name="har3" value="<?php echo htmlspecialchars($har3 ?? ''); ?>" placeholder="Например: TDP 95W">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-add">Добавить товар</button>
                    <a href="admin.php" class="btn-cancel">Назад</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
