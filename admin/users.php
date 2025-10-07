<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /admin/login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY joined_at DESC");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользователи — GiftDrop.ru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h3>Админка</h3>
            <nav>
                <a href="/admin/dashboard.php">📊 Главная</a>
                <a href="/admin/users.php" class="active">👥 Пользователи</a>
                <a href="/admin/cases.php">🎁 Кейсы</a>
                <a href="/admin/payments.php">💰 Платежи</a>
                <a href="/admin/history.php">📜 История</a>
                <a href="/admin/drops.php">🔥 Ручные дропы</a>
                <a href="/admin/notifications.php">📣 Уведомления</a>
                <a href="/admin/export.php">📥 Экспорт</a>
                <a href="/admin/logout.php">🚪 Выйти</a>
            </nav>
        </aside>

        <main class="main">
            <h1>👥 Пользователи</h1>

            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ник</th>
                        <th>Telegram ID</th>
                        <th>Баланс</th>
                        <th>Реф. код</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= $user['telegram_id'] ?></td>
                        <td><?= number_format($user['balance'], 2) ?> 💎</td>
                        <td><?= $user['ref_code'] ?></td>
                        <td><?= $user['is_banned'] ? '<span style="color: red;">Забанен</span>' : 'Активен' ?></td>
                        <td>
                            <a href="?action=ban&id=<?= $user['id'] ?>" onclick="return confirm('Забанить?')" style="color: red; margin-right: 10px;">🚫</a>
                            <a href="?action=unban&id=<?= $user['id'] ?>" onclick="return confirm('Разбанить?')" style="color: green; margin-right: 10px;">✅</a>
                            <a href="?action=bonus&id=<?= $user['id'] ?>" style="color: #ff4500;">➕ Бонус</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php
            if (isset($_GET['action'])) {
                $id = (int)$_GET['id'];
                switch ($_GET['action']) {
                    case 'ban':
                        $pdo->exec("UPDATE users SET is_banned = 1 WHERE id = $id");
                        header('Location: /admin/users.php');
                        exit;
                    case 'unban':
                        $pdo->exec("UPDATE users SET is_banned = 0 WHERE id = $id");
                        header('Location: /admin/users.php');
                        exit;
                    case 'bonus':
                        $amount = 100;
                        $pdo->exec("UPDATE users SET balance = balance + $amount WHERE id = $id");
                        header('Location: /admin/users.php');
                        exit;
                }
            }
            ?>
        </main>
    </div>
</body>
</html>