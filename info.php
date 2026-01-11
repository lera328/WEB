<?php
$conn = new mysqli("localhost", "root", "741852Bora!", "ACCESSORIES");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
echo "Подключение успешно!";
?>