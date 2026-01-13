<?php
// Конфигурация базы данных
function getDB() {
    $host = 'localhost';
    $dbname = 'ACCESSORIES';
    $username = 'root';
    $password = '741852Bora!';
    
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
}
?>
