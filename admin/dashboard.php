<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Dashboard - Campus Booking';
$facilityService = new FacilityService();
$bookingService = new BookingService();
$userService = new UserService();

require_once __DIR__ . '/../includes/admin_header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<style>
    .dashboard-vertical .stats-grid,
    .dashboard-vertical .card-grid {
        grid-template-columns: 1fr;
        max-width: 760px;
    }

    .dashboard-vertical .stat-card,
    .dashboard-vertical .feature-card {
        width: 100%;
    }

    .dashboard-vertical .feature-card {
        padding: 1.5rem;
    }
</style>

<div class="dashboard-vertical">
    <p class="page-title">Dashboard</p>
    <p class="page-subtitle">Tổng quan hệ thống</p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number"><?= $facilityService->countFacilities() ?></div>
            <div class="stat-label">Total Facilities</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $bookingService->countByStatus('PENDING') ?></div>
            <div class="stat-label">Pending Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $bookingService->countByStatus('APPROVED') ?></div>
            <div class="stat-label">Approved Bookings</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?= $userService->countRegularUsers() ?></div>
            <div class="stat-label">Total Users</div>
        </div>
    </div>

    <p class="section-heading">Quản lý nhanh</p>
    <div class="card-grid">
        <div class="feature-card">
            <div class="icon">BK</div>
            <h3>Bookings</h3>
            <p>Duyệt hoặc từ chối các booking đang chờ.</p>
            <a href="/webfinal/admin/bookings.php" class="btn btn-primary btn-sm mt-md">Manage</a>
        </div>
        <div class="feature-card">
            <div class="icon">FC</div>
            <h3>Facilities</h3>
            <p>Thêm, sửa phòng, lab và sân thể thao.</p>
            <a href="/webfinal/admin/facilities.php" class="btn btn-primary btn-sm mt-md">Manage</a>
        </div>
        <div class="feature-card">
            <div class="icon">US</div>
            <h3>Users</h3>
            <p>Quản lý tài khoản và phân quyền.</p>
            <a href="/webfinal/admin/users.php" class="btn btn-primary btn-sm mt-md">Manage</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>