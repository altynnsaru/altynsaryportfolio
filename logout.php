<?php
/**
 * logout.php
 * Удаляет cookie авторизации, устанавливая срок жизни в прошлом.
 */

setcookie('username', '', time() - 3600, '/');
setcookie('logged_in', '', time() - 3600, '/');

header('Location: index.php');
exit;
