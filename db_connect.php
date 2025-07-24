<?php
$host = 'localhost';
$dbname = 'bnal_feedback'; // Название БД — bnal, а не bnai!
$user = 'root'; 
$pass = ''; // Пароль пустой (стандарт для XAMPP)

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}
?>