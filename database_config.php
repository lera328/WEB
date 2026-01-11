<?php
// Конфигурация базы данных для продакшена
class Database {
    private static $instance = null;
    private $conn;
    
    private function __construct() {
        // Выбор конфигурации в зависимости от окружения
        if (getenv('PORT') || strpos($_SERVER['HTTP_HOST'] ?? '', '.onrender.com') !== false) {
            // Продакшен (Render)
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'ACCESSORIES';
            $username = getenv('DB_USER') ?: 'root';
            $password = getenv('DB_PASSWORD') ?: '';
        } else {
            // Локальная разработка
            $host = 'localhost';
            $dbname = 'ACCESSORIES';
            $username = 'root';
            $password = '741852Bora!';
        }
        
        try {
            $this->conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
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
