<?php
/**
 * dashboard.php
 * Доступна только авторизованным пользователям.
 * Проверка авторизации выполняется по cookie 'logged_in'.
 */

if (!isset($_COOKIE['logged_in']) || $_COOKIE['logged_in'] !== '1') {
    header('Location: index.php?error=' . urlencode('Сначала войдите в систему'));
    exit;
}

$username = htmlspecialchars($_COOKIE['username'] ?? 'гость');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет — Login Lab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, <?= $username ?>!</h1>
        <p>Вы успешно авторизованы. Эта страница доступна только вошедшим в систему пользователям — доступ проверяется по значению cookie <code>logged_in</code>.</p>
        <a class="btn btn-secondary" href="logout.php">Выйти</a>
    </div>
</body>
</html>
