<?php
// /bot.php
header('Content-Type: application/json');

$token = '8289959791:AAEa26Oglke-BHz-vGB8VkxosvcLuFmoR5o'; // Замените!
$update = json_decode(file_get_contents('php://input'), true);

if (isset($update['message'])) {
    $chat_id = $update['message']['chat']['id'];
    $text = $update['message']['text'];
    $username = $update['message']['from']['username'] ?? 'anon';
    $first_name = $update['message']['from']['first_name'] ?? 'Аноним';

    if ($text === '/start') {
        $url = "https://giftdrop.ru/auth.php?id=$chat_id&username=$username&first_name=" . urlencode($first_name);
        $message = "👋 Добро пожаловать!\n\n🔗 Нажмите, чтобы войти в аккаунт:\n<a href='$url'>Войти через Telegram</a>";
        
        file_get_contents("https://api.telegram.org/bot$token/sendMessage?chat_id=$chat_id&text=" . urlencode($message) . "&parse_mode=HTML");
    }
}