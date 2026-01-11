<?php
// Выход из системы
session_start();
unset($_SESSION['admin_logged_in']);
unset($_SESSION['user']);
session_destroy();

// Перенаправление на страницу авторизации
header('Location: auth.php');
exit();
?>
