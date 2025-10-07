<?php
$host = 'localhost';
$dbname = 'a1160245_123';
$username = 'a1160245_123'; // замените!
$password = 'Sederik321'; // замените!

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>