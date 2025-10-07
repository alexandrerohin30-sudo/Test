<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /admin/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $item = trim($_POST['item']);
    $value = (float)$_POST['value'];

    $stmt = $pdo->prepare("INSERT INTO case_history (user_id, case_name, item_name, rarity, value) VALUES (?, 'Ручной дроп', ?, 'Легендарный', ?)");
    $stmt->execute([$user_id, $item, $value]);

    $stmt = $pdo->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
    $stmt->execute([$value, $user_id]);

    header('Location: /admin/drops.php?success=1');
    exit;
}

$stmt = $pdo->query("SELECT id, username FROM users WHERE is_banned = 0 ORDER BY username");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ручные дропы — GiftDrop.ru</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-container">
        <aside class="sidebar">
            <h3>Админка</h3>
            <nav>
                <a href="/admin/dashboard.php">📊 Главная</a>
                <a href="/admin/users.php">👥 Пользователи</a>
                <a href="/admin/cases.php">🎁 Кейсы</a>
                <a href="/admin/payments.php">💰 Платежи</a>
                <a href="/admin/history.php">📜 История</a>
                <a href="/admin/drops.php" class="active">🔥 Ручные дропы</a>
                <a href="/admin/notifications.php">📣 Уведомления</a>
                <a href="/admin/export.php">📥 Экспорт</a>
                <a href="/admin/logout.php">🚪 Выйти</a>
            </nav>
        </aside>

        <main class="main">
            <h1>🔥 Ручной дроп</h1>

            <?php if (isset($_GET['success'])): ?>
                <div class="success">✅ Дроп успешно выдан!</div>
            <?php endif; ?>

            <form method="POST">
                <select name="user_id" required>
                    <option value="">Выберите пользователя...</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                </select>

                <input type="text" name="item" placeholder="Название предмета (например: AWP | Dragon Lore)" required>
                <input type="number" name="value" placeholder="Цена в 💎 (например: 1000)" min="1" step="1" required>

                <button type="submit">Выдать дроп</button>
            </form>
        </main>
    </div>
</body>
</html>