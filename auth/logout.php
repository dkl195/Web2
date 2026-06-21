<?php
require_once __DIR__ . '/../includes/auth.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
Auth::logout();
header('Location: /web 2 final/Web2/index.php');
exit;
