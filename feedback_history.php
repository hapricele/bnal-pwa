<?php
session_start();
$showThanks = false;

if (isset($_SESSION['feedback_success'])) {
    $showThanks = true;
    unset($_SESSION['feedback_success']);
}

$db_host = 'egor';
$db_user = 'localhost';
$db_pass = '';
$db_name = 'egor_base';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Ошибка подключения к БД: " . $conn->connect_error);
}

// Получаем историю сообщений
$history = [];
$sql = "SELECT name, phone, message, created_at 
        FROM feedback 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>История обратной связи</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .thank-you {
            background-color: #dff0d8;
            color: #3c763d;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 25px;
        }
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .history-table th, .history-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .history-table th {
            background-color: #f2f2f2;
        }
        .history-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <?php if ($showThanks): ?>
        <div class="thank-you">
            <h2>Спасибо за ваше сообщение!</h2>
            <p>Ваш отзыв успешно отправлен и появится в истории ниже.</p>
        </div>
    <?php endif; ?>

    <h1>История обратной связи</h1>
    
    <?php if (!empty($history)): ?>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Сообщение</th>
                    <th>Дата отправки</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $entry): ?>
                    <tr>
                        <td><?= htmlspecialchars($entry['name']) ?></td>
                        <td><?= htmlspecialchars($entry['phone']) ?></td>
                        <td><?= htmlspecialchars($entry['message']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($entry['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>История сообщений пока пуста.</p>
    <?php endif; ?>
</body>
</html>