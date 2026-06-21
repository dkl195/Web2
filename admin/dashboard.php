<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Dashboard - VNU-IS Admin';
$baseUrl = '/web 2 final/Web2';
$facilityService = new FacilityService();
$bookingService  = new BookingService();
$userService     = new UserService();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="page-header">
    <div class="page-header-left">
        <p class="page-title">Dashboard</p>
        <p class="page-subtitle">Tổng quan hệ thống quản lý campus</p>
    </div>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">🏛️</div>
        <div class="stat-number"><?= $facilityService->countFacilities() ?></div>
        <div class="stat-label">Total Facilities</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⏳</div>
        <div class="stat-number"><?= $bookingService->countByStatus('PENDING') ?></div>
        <div class="stat-label">Pending Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-number"><?= $bookingService->countByStatus('APPROVED') ?></div>
        <div class="stat-label">Approved Bookings</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-number"><?= $userService->countRegularUsers() ?></div>
        <div class="stat-label">Total Users</div>
    </div>
</div>

<!-- Quick manage -->
<p class="section-heading" style="margin-bottom:1.25rem;">Quản lý nhanh</p>
<div class="card-grid">
    <a href="<?= $baseUrl ?>/admin/bookings.php" class="feature-card admin-card" style="text-decoration:none;">
        <div class="icon">📅</div>
        <h3>Bookings</h3>
        <p>Duyệt hoặc từ chối các booking đang chờ xử lý.</p>
        <span class="btn btn-primary btn-sm" style="align-self:flex-start;margin-top:0.5rem;">Manage</span>
    </a>
    <a href="<?= $baseUrl ?>/admin/facilities.php" class="feature-card admin-card" style="text-decoration:none;">
        <div class="icon">🏛️</div>
        <h3>Facilities</h3>
        <p>Thêm, sửa phòng học, lab và sân thể thao trong campus.</p>
        <span class="btn btn-primary btn-sm" style="align-self:flex-start;margin-top:0.5rem;">Manage</span>
    </a>
    <a href="<?= $baseUrl ?>/admin/users.php" class="feature-card admin-card" style="text-decoration:none;">
        <div class="icon">👥</div>
        <h3>Users</h3>
        <p>Quản lý tài khoản người dùng và phân quyền hệ thống.</p>
        <span class="btn btn-primary btn-sm" style="align-self:flex-start;margin-top:0.5rem;">Manage</span>
    </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
