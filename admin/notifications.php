<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: /admin/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message']);
    $type = $_POST['type'];

    if ($type === 'all') {
        $stmt = $pdo->query("SELECT id FROM users WHERE is_banned = 0");
        while ($user = $stmt->fetch()) {
            $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)")
                ->execute([$user['id'], $message]);
        }
    } else {
        $user_id = (int)$_POST['user_id'];
        $pdo->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)")
            ->execute([$user_id, $message]);
    }

    header('Location: /admin/notifications.php?sent=1');
    exit;
}

$stmt = $pdo->query("SELECT id, username FROM users WHERE is_banned = 0 ORDER BY username");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Уведомления — GiftDrop.ru</title>
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
                <a href="/admin/drops.php">🔥 Ручные дропы</a>
                <a href="/admin/notifications.php" class="active">📣 Уведомления</a>
                <a href="/admin/export.php">📥 Экспорт</a>
                <a href="/admin/logout.php">🚪 Выйти</a>
            </nav>
        </aside>

        <main class="main">
            <h1>📣 Отправка уведомлений</h1>

            <?php if (isset($_GET['sent'])): ?>
                <div class="success">✅ Уведомления отправлены!</div>
            <?php endif; ?>

            <form method="POST">
                <textarea name="message" placeholder="Введите сообщение..." rows="5" required></textarea><br><br>

                <label>
                    <input type="radio" name="type" value="all" checked> Отправить всем
                </label><br>
                <label>
                    <input type="radio" name="type" value="single"> Отправить одному:
                </label>
                <select name="user_id">
                    <option value="">Выберите пользователя...</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['username']) ?></option>
                    <?php endforeach; ?>
                </select><br><br>

                <button type="submit">Отправить</button>
            </form>
        </main>
    </div>
</body>
</html>