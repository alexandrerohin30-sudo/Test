<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /admin/login.php');
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="giftdrop_users.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Ник', 'Telegram ID', 'Баланс', 'Реф. код', 'Статус', 'Дата регистрации']);

$stmt = $pdo->query("SELECT id, username, telegram_id, balance, ref_code, is_banned, joined_at FROM users ORDER BY joined_at DESC");
while ($row = $stmt->fetch()) {
    fputcsv($output, [
        $row['id'],
        $row['username'],
        $row['telegram_id'],
        $row['balance'],
        $row['ref_code'],
        $row['is_banned'] ? 'Забанен' : 'Активен',
        $row['joined_at']
    ]);
}

fclose($output);
exit;