<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$facilityService = new FacilityService();
$f = $facilityService->getFacilityWithRule($id);
if (!$f) { header('Location: /web 2 final/Web2/admin/facilities.php'); exit; }

$pageTitle = e($f['facility_name']) . ' - Facility Detail';
require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2><?= e($f['facility_name']) ?></h2>
        <div style="display:flex;gap:0.5rem;">
            <a href="/web 2 final/Web2/admin/facility_form.php?id=<?= $id ?>" class="btn btn-primary btn-sm">Edit</a>
            <a href="/web 2 final/Web2/admin/facilities.php" class="btn btn-secondary btn-sm">Back</a>
        </div>
    </div>
    <ul class="detail-list">
        <li><span class="label">Category</span><span class="value"><?= e($f['category_name']) ?></span></li>
        <li><span class="label">Location</span><span class="value"><?= e($f['location']) ?></span></li>
        <li><span class="label">Capacity</span><span class="value"><?= $f['capacity'] ?> people</span></li>
        <li><span class="label">Operating Hours</span><span class="value"><?= formatTime($f['open_time']) ?> - <?= formatTime($f['close_time']) ?></span></li>
        <li><span class="label">Status</span><span class="value"><span class="badge badge-<?= $f['facility_status'] ?>"><?= e($f['facility_status']) ?></span></span></li>
        <li><span class="label">Max Booking Hours</span><span class="value"><?= $f['max_booking_hours'] ?? '-' ?></span></li>
        <li><span class="label">Min Notice</span><span class="value"><?= $f['min_notice_hours'] ?? '-' ?> hour(s)</span></li>
        <li><span class="label">Description</span><span class="value"><?= e($f['description'] ?? '-') ?></span></li>
    </ul>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
