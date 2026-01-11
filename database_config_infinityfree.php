<?php
// Конфигурация базы данных для InfinityFree
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // Определение окружения
        $host = $_SERVER['HTTP_HOST'] ?? '';
        
        if (strpos($host, 'infinityfree.com') !== false) {
            // InfinityFree продакшен
            $db_host = 'sqlXXX.epizy.com'; // Замените на ваш хост
            $db_name = 'epiz_XXX_ACCESSORIES'; // Замените на имя БД
            $db_user = 'epiz_XXX'; // Замените на пользователя
            $db_password = 'ваш_пароль'; // Замените на пароль
        } elseif (getenv('PORT') || strpos($host, '.onrender.com') !== false) {
            // Render продакшен
            $db_host = getenv('DB_HOST') ?: 'localhost';
            $db_name = getenv('DB_NAME') ?: 'ACCESSORIES';
            $db_user = getenv('DB_USER') ?: 'root';
            $db_password = getenv('DB_PASSWORD') ?: '';
        } else {
            // Локальная разработка
            $db_host = 'localhost';
            $db_name = 'ACCESSORIES';
            $db_user = 'root';
            $db_password = '741852Bora!';
        }
        
        try {
            $this->conn = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Ошибка подключения к БД: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->conn;
    }
}

// Удобная функция для получения подключения
function getDB() {
    return Database::getInstance()->getConnection();
}
?>
