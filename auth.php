<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id'])) {
    die('Нет данных от Telegram. <a href="https://t.me/GiftDropBot">Нажмите здесь, чтобы авторизоваться</a>');
}

$user_id = $_GET['id'];
$username = $_GET['username'] ?? 'Пользователь_' . substr($user_id, -4);
$first_name = $_GET['first_name'] ?? 'Аноним';

// Генерируем реферальный код
$ref_code = substr(md5($user_id), 0, 8);

$stmt = $pdo->prepare("SELECT id FROM users WHERE telegram_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    $stmt = $pdo->prepare("INSERT INTO users (telegram_id, username, first_name, ref_code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $username, $first_name, $ref_code]);
    $user_id_db = $pdo->lastInsertId();
} else {
    $user_id_db = $user['id'];
}

// Сохраняем в сессию
$_SESSION['user'] = [
    'id' => $user_id_db,
    'username' => $username,
    'ref_code' => $ref_code,
    'joined_at' => date('Y-m-d H:i:s')
];

header('Location: /');
exit;