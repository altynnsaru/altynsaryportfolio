<?php
/**
 * index.php
 * Отображает форму входа.
 * Демонстрирует работу с GET-запросом: после неудачной попытки логина
 * login.php делает редирект сюда с параметрами ?error=...&username=...
 */

$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
$username_val = isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';

$isLoggedIn = isset($_COOKIE['logged_in']) && $_COOKIE['logged_in'] === '1';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему — Login Lab</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Вход в систему</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <p>Вы уже вошли как <b><?= htmlspecialchars($_COOKIE['username']) ?></b>.</p>
            <a class="btn" href="dashboard.php">Перейти в профиль</a>
            <a class="btn btn-secondary" href="logout.php">Выйти</a>
        <?php else: ?>
            <form action="login.php" method="POST" novalidate>
                <div class="field">
                    <label for="username">Логин</label>
                    <input type="text" id="username" name="username" value="<?= $username_val ?>" placeholder="от 3 до 20 символов" required>
                </div>
                <div class="field">
                    <label for="password">Пароль</label>
                    <input type="password" id="password" name="password" placeholder="минимум 6 символов" required>
                </div>
                <div class="field field-checkbox">
                    <label>
                        <input type="checkbox" name="remember" value="1"> Запомнить меня (7 дней)
                    </label>
                </div>
                <button type="submit" class="btn">Войти</button>
            </form>
            <p class="hint">Тестовые данные: <b>admin</b> / <b>admin123</b></p>
        <?php endif; ?>
    </div>
</body>
</html>
