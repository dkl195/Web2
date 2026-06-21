<?php
require_once __DIR__ . '/../includes/auth.php';

$id = (int) ($_GET['id'] ?? 0);
$facilityService = new FacilityService();
$f = $facilityService->getAvailableFacility($id);

if (!$f) {
    header('Location: /web 2 final/Web2/user/facilities.php');
    exit;
}

$pageTitle = e($f['facility_name']) . ' - Facility Detail';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2><?= e($f['facility_name']) ?></h2>
        <a href="/web 2 final/Web2/user/facilities.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <ul class="detail-list">
        <li><span class="label">Category</span><span class="value"><?= e($f['category_name']) ?></span></li>
        <li><span class="label">Location</span><span class="value"><?= e($f['location']) ?></span></li>
        <li><span class="label">Capacity</span><span class="value"><?= $f['capacity'] ?> people</span></li>
        <li><span class="label">Operating Hours</span><span class="value"><?= formatTime($f['open_time']) ?> - <?= formatTime($f['close_time']) ?></span></li>
        <li><span class="label">Max Booking</span><span class="value"><?= $f['max_booking_hours'] ?? 3 ?> hour(s) per session</span></li>
        <li><span class="label">Advance Notice</span><span class="value"><?= $f['min_notice_hours'] ?? 24 ?> hour(s) minimum</span></li>
        <li><span class="label">Description</span><span class="value"><?= e($f['description'] ?? '-') ?></span></li>
    </ul>
    <div style="margin-top:1.5rem;">
        <?php if (isLoggedIn() && !isAdmin()): ?>
            <a href="/web 2 final/Web2/user/booking_form.php?facility_id=<?= $id ?>" class="btn btn-primary">Book Now</a>
        <?php elseif (!isLoggedIn()): ?>
            <a href="/web 2 final/Web2/auth/login.php" class="btn btn-primary">Login to Book</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
