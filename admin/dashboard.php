<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /admin/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админка — GiftDrop.ru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <!-- Боковое меню -->
        <aside class="sidebar">
            <h3>Админка</h3>
            <nav>
                <a href="/admin/dashboard.php" class="active">📊 Главная</a>
                <a href="/admin/users.php">👥 Пользователи</a>
                <a href="/admin/cases.php">🎁 Кейсы</a>
                <a href="/admin/payments.php">💰 Платежи</a>
                <a href="/admin/history.php">📜 История</a>
                <a href="/admin/drops.php">🔥 Ручные дропы</a>
                <a href="/admin/notifications.php">📣 Уведомления</a>
                <a href="/admin/export.php">📥 Экспорт</a>
                <a href="/admin/logout.php">🚪 Выйти</a>
            </nav>
        </aside>

        <!-- Основное содержимое -->
        <main class="main">
            <h1>📊 Статистика</h1>

            <div class="stats-grid">
                <?php
                $totalUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE is_banned = 0")->fetchColumn();
                $totalPaid = $pdo->query("SELECT SUM(amount_usd) FROM payments WHERE status = 'confirmed'")->fetchColumn();
                $totalDrops = $pdo->query("SELECT COUNT(*) FROM case_history")->fetchColumn();
                $todayUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE DATE(joined_at) = CURDATE()")->fetchColumn();
                ?>
                <div class="stat-card">
                    <h3>Пользователей</h3>
                    <p><?= number_format($totalUsers) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Доход</h3>
                    <p><?= number_format($totalPaid, 2) ?> $</p>
                </div>
                <div class="stat-card">
                    <h3>Открыто кейсов</h3>
                    <p><?= number_format($totalDrops) ?></p>
                </div>
                <div class="stat-card">
                    <h3>Новых сегодня</h3>
                    <p><?= number_format($todayUsers) ?></p>
                </div>
            </div>

            <h2>🔥 Последние дропы</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Пользователь</th>
                        <th>Кейс</th>
                        <th>Предмет</th>
                        <th>Цена</th>
                        <th>Дата</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT u.username, ch.case_name, ch.item_name, ch.value, ch.created_at 
                        FROM case_history ch 
                        JOIN users u ON ch.user_id = u.id 
                        ORDER BY ch.created_at DESC LIMIT 10
                    ");
                    while ($row = $stmt->fetch()):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['case_name']) ?></td>
                        <td><?= htmlspecialchars($row['item_name']) ?></td>
                        <td>+<?= number_format($row['value'], 2) ?>💎</td>
                        <td><?= date('d.m.Y H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>