<?php
require_once 'admin/config.php';

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM admins");
    $count = $stmt->fetchColumn();
    echo "✅ Подключение к БД установлено! Найдено админов: " . $count;
} catch (PDOException $e) {
    echo "❌ Ошибка подключения: " . $e->getMessage();
}