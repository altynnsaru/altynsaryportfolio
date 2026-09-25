<?php
/**
 * login.php
 * Принимает данные формы методом POST, выполняет серверную валидацию
 * и, при успехе, устанавливает cookie авторизации.
 */

// Разрешаем только POST-запросы к этому скрипту
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
$remember = isset($_POST['remember']);

$errors = [];

// ---------- Валидация ----------
if ($username === '') {
    $errors[] = 'Введите логин';
} elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
    $errors[] = 'Логин: 3-20 символов, латиница/цифры/подчёркивание';
}

if ($password === '') {
    $errors[] = 'Введите пароль';
} elseif (strlen($password) < 6) {
    $errors[] = 'Пароль должен быть не менее 6 символов';
}

// "База данных" пользователей (в реальном проекте — таблица в БД с хешами паролей)
$users = [
    'admin' => 'admin123',
    'user1' => 'password1',
];

if (empty($errors)) {
    if (!isset($users[$username]) || $users[$username] !== $password) {
        $errors[] = 'Неверный логин или пароль';
    }
}

// ---------- Результат ----------
if (!empty($errors)) {
    $msg = implode('; ', $errors);
    // Редирект обратно на форму с сообщением об ошибке через GET-параметры
    header('Location: index.php?error=' . urlencode($msg) . '&username=' . urlencode($username));
    exit;
}

// Успешный вход — устанавливаем cookie
$cookieLifetime = $remember ? time() + 60 * 60 * 24 * 7 : 0; // 7 дней или до закрытия браузера
setcookie('username', $username, $cookieLifetime, '/');
setcookie('logged_in', '1', $cookieLifetime, '/');

header('Location: dashboard.php');
exit;
