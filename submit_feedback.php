<?php
header('Content-Type: application/json');

// Подключаемся к БД
require_once 'db_connect.php';

// Получаем данные из формы
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

// Валидация
if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Укажите ваше имя']);
    exit;
}

if (empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Укажите телефон']);
    exit;
}

try {
    // Подготовленный запрос для безопасности
    $stmt = $db->prepare("INSERT INTO feedback (name, phone, message, created_at) 
                         VALUES (:name, :phone, :message, NOW())");
    
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':message' => $message
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Сообщение отправлено!']);
    
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>