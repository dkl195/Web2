<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if (isAdmin()) {
    header('Location: /webfinal/admin/dashboard.php');
    exit;
}

$facilityId = (int) ($_GET['facility_id'] ?? 0);
$facilityService = new FacilityService();
$facility = $facilityService->getAvailableFacility($facilityId);

if (!$facility) {
    Flash::error('Facility not available.');
    header('Location: /webfinal/user/facilities.php');
    exit;
}

$pageTitle = 'Book ' . $facility['facility_name'];
$errors = $_SESSION['booking_errors'] ?? [];
unset($_SESSION['booking_errors']);
$old = $_SESSION['booking_old'] ?? [];
unset($_SESSION['booking_old']);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Booking Form</h2>
        <a href="/webfinal/user/facility_detail.php?id=<?= $facilityId ?>" class="btn btn-secondary btn-sm">Back</a>
    </div>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= e($err) ?></div>
    <?php endforeach; ?>

    <ul class="detail-list" style="margin-bottom:1.5rem;">
        <li><span class="label">Facility</span><span class="value"><?= e($facility['facility_name']) ?> (<?= e($facility['location']) ?>)</span></li>
        <li><span class="label">Capacity</span><span class="value"><?= $facility['capacity'] ?> people</span></li>
        <li><span class="label">Hours</span><span class="value"><?= formatTime($facility['open_time']) ?> - <?= formatTime($facility['close_time']) ?></span></li>
        <li><span class="label">Max Duration</span><span class="value"><?= $facility['max_booking_hours'] ?? 3 ?> hour(s)</span></li>
    </ul>

    <form method="POST" action="/webfinal/user/create_booking.php">
        <input type="hidden" name="facility_id" value="<?= $facilityId ?>">
        <div class="form-row">
            <div class="form-group">
                <label for="booking_date">Date *</label>
                <input type="date" id="booking_date" name="booking_date" class="form-control"
                       value="<?= e($old['booking_date'] ?? '') ?>" min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label for="participant_count">Number of Participants *</label>
                <input type="number" id="participant_count" name="participant_count" class="form-control"
                       value="<?= e($old['participant_count'] ?? '') ?>" min="1" max="<?= $facility['capacity'] ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="start_time">Start Time *</label>
                <input type="time" id="start_time" name="start_time" class="form-control"
                       value="<?= e($old['start_time'] ?? formatTime($facility['open_time'])) ?>" required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time *</label>
                <input type="time" id="end_time" name="end_time" class="form-control"
                       value="<?= e($old['end_time'] ?? '') ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label for="purpose">Purpose *</label>
            <input type="text" id="purpose" name="purpose" class="form-control"
                   value="<?= e($old['purpose'] ?? '') ?>" placeholder="e.g. Group study" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit Booking</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
