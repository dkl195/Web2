<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

if (isLoggedIn() && !array_key_exists('avatar', $_SESSION)) {
    $profileUser = (new UserService())->getUserWithProfile((int) $_SESSION['user_id']);
    Auth::setAvatar($profileUser['avatar'] ?? null);
}

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$baseUrl = '/webfinal';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'VNU-IS Booking') ?></title>
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-inner">
        <!-- Logo + Brand -->
        <a href="<?= $baseUrl ?>/index.php" class="navbar-brand">
            <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="VNU-IS Logo" class="navbar-logo">
            <span class="brand-text">VNU<span class="brand-accent">-IS</span></span>
        </a>

        <!-- Search bar (user only) -->
        <?php if (isLoggedIn() && !isAdmin()): ?>
        <form class="navbar-search" action="<?= $baseUrl ?>/user/facilities.php" method="GET">
            <div class="search-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="q" class="search-input" placeholder="Tìm phòng, lab, sân...">
            </div>
        </form>
        <?php endif; ?>

        <!-- Nav links -->
        <ul class="navbar-nav">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="<?= $baseUrl ?>/admin/dashboard.php" class="<?= str_contains($_SERVER['PHP_SELF'], '/admin/') ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/facilities.php">Facilities</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/bookings.php">Bookings</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/users.php">Users</a></li>
                <?php else: ?>
                    <li><a href="<?= $baseUrl ?>/user/my_bookings.php" class="<?= $currentPage === 'my_bookings' ? 'active' : '' ?>">My Bookings</a></li>
                    <li><a href="<?= $baseUrl ?>/user/profile.php" class="<?= $currentPage === 'profile' ? 'active' : '' ?>">Profile</a></li>
                <?php endif; ?>
                <li class="navbar-user-wrap">
                    <img src="<?= e(currentAvatarUrl()) ?>" alt="" class="navbar-avatar">
                    <span class="navbar-user"><?= e($_SESSION['full_name'] ?? '') ?></span>
                </li>
                <li><a href="<?= $baseUrl ?>/auth/logout.php" class="nav-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= $baseUrl ?>/auth/login.php">Login</a></li>
                <li><a href="<?= $baseUrl ?>/auth/register.php" class="btn-register">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main class="main-content">
