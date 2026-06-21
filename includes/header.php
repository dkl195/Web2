<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$isAdminPage = str_contains($_SERVER['PHP_SELF'], '/admin/');
$baseUrl = '/web 2 final/Web2';
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
        <a href="<?= $baseUrl ?>/index.php" class="navbar-brand">
            <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="VNU-IS" class="navbar-logo">
            <span class="brand-text">VNU<span class="brand-accent">-IS</span></span>
        </a>

        <?php if (isLoggedIn() && !isAdmin()): ?>
        <form class="navbar-search" action="<?= $baseUrl ?>/user/facilities.php" method="GET">
            <div class="search-wrap">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                </svg>
                <input type="text" name="q" class="search-input" placeholder="Tìm phòng, lab, sân...">
            </div>
        </form>
        <?php endif; ?>

        <ul class="navbar-nav">
            <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                    <li><a href="<?= $baseUrl ?>/admin/dashboard.php" class="<?= $currentPage==='dashboard' ? 'active' : '' ?>">Dashboard</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/facilities.php" class="<?= $currentPage==='facilities' ? 'active' : '' ?>">Facilities</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/bookings.php" class="<?= $currentPage==='bookings' ? 'active' : '' ?>">Bookings</a></li>
                    <li><a href="<?= $baseUrl ?>/admin/users.php" class="<?= $currentPage==='users' ? 'active' : '' ?>">Users</a></li>
                <?php else: ?>
                    <li><a href="<?= $baseUrl ?>/user/facilities.php" class="<?= $currentPage==='facilities' ? 'active' : '' ?>">Facilities</a></li>
                    <li><a href="<?= $baseUrl ?>/user/my_bookings.php" class="<?= $currentPage==='my_bookings' ? 'active' : '' ?>">My Bookings</a></li>
                    <li><a href="<?= $baseUrl ?>/user/profile.php" class="<?= $currentPage==='profile' ? 'active' : '' ?>">Profile</a></li>
                <?php endif; ?>
                <li><span class="navbar-user">👤 <?= e($_SESSION['full_name'] ?? '') ?></span></li>
                <li><a href="<?= $baseUrl ?>/auth/logout.php" class="nav-logout">Logout</a></li>
            <?php else: ?>
                <li><a href="<?= $baseUrl ?>/auth/login.php">Đăng nhập</a></li>
                <li><a href="<?= $baseUrl ?>/auth/register.php" class="btn-register">Đăng ký</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<?php if ($isAdminPage && isAdmin()): ?>
<!-- Admin layout: sidebar + main -->
<div class="admin-layout">
<aside class="sidebar">
    <div class="sidebar-section">
        <span class="sidebar-label">Menu</span>
        <a href="<?= $baseUrl ?>/admin/dashboard.php" class="sidebar-link <?= $currentPage==='dashboard' ? 'active' : '' ?>">
            <span class="sidebar-icon">📊</span> Dashboard
        </a>
        <a href="<?= $baseUrl ?>/admin/bookings.php" class="sidebar-link <?= $currentPage==='bookings' ? 'active' : '' ?>">
            <span class="sidebar-icon">📅</span> Bookings
        </a>
        <a href="<?= $baseUrl ?>/admin/facilities.php" class="sidebar-link <?= in_array($currentPage,['facilities','facility_form','facility_detail','facility_rules']) ? 'active' : '' ?>">
            <span class="sidebar-icon">🏛️</span> Facilities
        </a>
        <a href="<?= $baseUrl ?>/admin/categories.php" class="sidebar-link <?= in_array($currentPage,['categories','category_form']) ? 'active' : '' ?>">
            <span class="sidebar-icon">🏷️</span> Categories
        </a>
        <a href="<?= $baseUrl ?>/admin/users.php" class="sidebar-link <?= in_array($currentPage,['users','user_form']) ? 'active' : '' ?>">
            <span class="sidebar-icon">👥</span> Users
        </a>
        <a href="<?= $baseUrl ?>/admin/roles.php" class="sidebar-link <?= $currentPage==='roles' ? 'active' : '' ?>">
            <span class="sidebar-icon">🔑</span> Roles
        </a>
        <a href="<?= $baseUrl ?>/admin/booking_statuses.php" class="sidebar-link <?= $currentPage==='booking_statuses' ? 'active' : '' ?>">
            <span class="sidebar-icon">⚙️</span> Statuses
        </a>
    </div>
</aside>
<div class="admin-main">
<?php else: ?>
<main class="main-content">
<?php endif; ?>
