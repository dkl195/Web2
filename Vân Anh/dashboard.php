<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Admin Dashboard — VNU-IS Booking';
$facilityService = new FacilityService();
$bookingService  = new BookingService();
$userService     = new UserService();

$totalFacilities   = $facilityService->countFacilities();
$pendingBookings   = $bookingService->countByStatus('PENDING');
$approvedBookings  = $bookingService->countByStatus('APPROVED');
$totalUsers        = $userService->countRegularUsers();
$rejectedBookings  = $bookingService->countByStatus('REJECTED');
$cancelledBookings = $bookingService->countByStatus('CANCELLED');

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
?>

<!-- Dashboard Header -->
<div class="dash-header">
    <div>
        <h1 class="dash-title">Admin Dashboard</h1>
        <p class="dash-subtitle">Welcome back — here's what's happening on campus today.</p>
    </div>
    <div class="dash-header-actions">
        <a href="<?= BASE_URL ?>/admin/bookings.php?status=PENDING" class="btn btn-accent">
            Review Pending <span class="btn-badge"><?= $pendingBookings ?></span>
        </a>
    </div>
</div>

<!-- KPI Cards -->
<div class="kpi-grid kpi-grid-5">
    <div class="kpi-card kpi-blue">
        <div class="kpi-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-number"><?= $totalFacilities ?></div>
            <div class="kpi-label">Total Facilities</div>
        </div>
        <a href="<?= BASE_URL ?>/admin/facilities.php" class="kpi-link">View all →</a>
    </div>
    <div class="kpi-card kpi-amber">
        <div class="kpi-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-number"><?= $pendingBookings ?></div>
            <div class="kpi-label">Pending Review</div>
        </div>
        <a href="<?= BASE_URL ?>/admin/bookings.php?status=PENDING" class="kpi-link">Review →</a>
    </div>
    <div class="kpi-card kpi-green">
        <div class="kpi-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-number"><?= $approvedBookings ?></div>
            <div class="kpi-label">Approved</div>
        </div>
        <a href="<?= BASE_URL ?>/admin/bookings.php?status=APPROVED" class="kpi-link">View all →</a>
    </div>
    <div class="kpi-card kpi-red">
        <div class="kpi-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-number"><?= $cancelledBookings ?></div>
            <div class="kpi-label">Cancelled</div>
        </div>
        <a href="<?= BASE_URL ?>/admin/bookings.php?status=CANCELLED" class="kpi-link">View all →</a>
    </div>
    <div class="kpi-card kpi-indigo">
        <div class="kpi-icon">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="kpi-body">
            <div class="kpi-number"><?= $totalUsers ?></div>
            <div class="kpi-label">Registered Users</div>
        </div>
        <a href="<?= BASE_URL ?>/admin/users.php" class="kpi-link">Manage →</a>
    </div>
</div>

<!-- Management Quick Access -->
<div class="dash-section-title">Quick Management</div>
<div class="mgmt-grid">
    <a href="<?= BASE_URL ?>/admin/bookings.php" class="mgmt-card">
        <div class="mgmt-card-icon mgmt-icon-blue">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        </div>
        <div class="mgmt-card-body">
            <h3>Bookings</h3>
            <p>Review, approve or reject booking requests from students.</p>
            <div class="mgmt-stats">
                <span class="mgmt-stat-pill pill-amber"><?= $pendingBookings ?> Pending</span>
                <span class="mgmt-stat-pill pill-green"><?= $approvedBookings ?> Approved</span>
                <span class="mgmt-stat-pill pill-red"><?= $cancelledBookings ?> Cancelled</span>
            </div>
        </div>
        <div class="mgmt-arrow">→</div>
    </a>
    <a href="<?= BASE_URL ?>/admin/facilities.php" class="mgmt-card">
        <div class="mgmt-card-icon mgmt-icon-indigo">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        <div class="mgmt-card-body">
            <h3>Facilities</h3>
            <p>Add, edit or disable classrooms, labs, courts and meeting rooms.</p>
            <div class="mgmt-stats">
                <span class="mgmt-stat-pill pill-blue"><?= $totalFacilities ?> Total</span>
            </div>
        </div>
        <div class="mgmt-arrow">→</div>
    </a>
    <a href="<?= BASE_URL ?>/admin/users.php" class="mgmt-card">
        <div class="mgmt-card-icon mgmt-icon-teal">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
        </div>
        <div class="mgmt-card-body">
            <h3>Users</h3>
            <p>Manage student accounts, assign roles and control access.</p>
            <div class="mgmt-stats">
                <span class="mgmt-stat-pill pill-blue"><?= $totalUsers ?> Users</span>
            </div>
        </div>
        <div class="mgmt-arrow">→</div>
    </a>
    <a href="<?= BASE_URL ?>/admin/categories.php" class="mgmt-card">
        <div class="mgmt-card-icon mgmt-icon-rose">
            <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
        </div>
        <div class="mgmt-card-body">
            <h3>Categories & Rules</h3>
            <p>Configure facility categories and booking rules per space.</p>
        </div>
        <div class="mgmt-arrow">→</div>
    </a>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
