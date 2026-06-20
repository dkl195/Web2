<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Facility Form - Campus Booking';
$facilityService = new FacilityService();
$errors = [];
$id = (int) ($_GET['id'] ?? 0);
$isEdit = $id > 0;
$f = [
    'category_id' => '', 'facility_name' => '', 'location' => '', 'capacity' => '',
    'open_time' => '08:00', 'close_time' => '17:00', 'facility_status' => 'available', 'description' => '',
    'max_booking_hours' => 3, 'min_notice_hours' => 24, 'requires_approval' => 1,
];

if ($isEdit) {
    $row = $facilityService->getFacilityWithRule($id);
    if (!$row) { header('Location: ' . BASE_URL . '/admin/facilities.php'); exit; }
    $f = $row;
    $f['open_time'] = formatTime($f['open_time']);
    $f['close_time'] = formatTime($f['close_time']);
}

$categories = $facilityService->getCategoriesSimple();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'category_id' => (int) ($_POST['category_id'] ?? 0),
        'facility_name' => trim($_POST['facility_name'] ?? ''),
        'location' => trim($_POST['location'] ?? ''),
        'capacity' => (int) ($_POST['capacity'] ?? 0),
        'open_time' => $_POST['open_time'] ?? '08:00',
        'close_time' => $_POST['close_time'] ?? '17:00',
        'facility_status' => $_POST['facility_status'] ?? 'available',
        'description' => trim($_POST['description'] ?? ''),
        'max_booking_hours' => (int) ($_POST['max_booking_hours'] ?? 3),
        'min_notice_hours' => (int) ($_POST['min_notice_hours'] ?? 24),
        'requires_approval' => isset($_POST['requires_approval']) ? 1 : 0,
    ];
    $result = $facilityService->saveFacility($isEdit ? $id : null, $data);
    if ($result['success']) {
        Flash::success($isEdit ? 'Facility updated.' : 'Facility created.');
        header('Location: ' . BASE_URL . '/admin/facilities.php');
        exit;
    }
    $errors = $result['errors'] ?? ['Error'];
    $f = array_merge($f, $data);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2><?= $isEdit ? 'Edit Facility' : 'Add Facility' ?></h2>
        <a href="<?= BASE_URL ?>/admin/facilities.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endforeach; ?>
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= $c['category_id'] ?>" <?= ($f['category_id'] ?? '') == $c['category_id'] ? 'selected' : '' ?>><?= e($c['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Facility Name *</label>
                <input type="text" name="facility_name" class="form-control" value="<?= e($f['facility_name']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Location *</label>
                <input type="text" name="location" class="form-control" value="<?= e($f['location']) ?>" required>
            </div>
            <div class="form-group">
                <label>Capacity *</label>
                <input type="number" name="capacity" class="form-control" value="<?= e($f['capacity']) ?>" min="1" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Open Time *</label>
                <input type="time" name="open_time" class="form-control" value="<?= e($f['open_time']) ?>" required>
            </div>
            <div class="form-group">
                <label>Close Time *</label>
                <input type="time" name="close_time" class="form-control" value="<?= e($f['close_time']) ?>" required>
            </div>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="facility_status" class="form-control">
                <option value="available" <?= ($f['facility_status'] ?? '') === 'available' ? 'selected' : '' ?>>available</option>
                <option value="unavailable" <?= ($f['facility_status'] ?? '') === 'unavailable' ? 'selected' : '' ?>>unavailable</option>
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= e($f['description'] ?? '') ?></textarea>
        </div>
        <h3 class="section-label">Booking Rules</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Max Booking Hours</label>
                <input type="number" name="max_booking_hours" class="form-control" value="<?= e($f['max_booking_hours']) ?>" min="1">
            </div>
            <div class="form-group">
                <label>Min Notice Hours</label>
                <input type="number" name="min_notice_hours" class="form-control" value="<?= e($f['min_notice_hours']) ?>" min="0">
            </div>
        </div>
        <div class="form-group">
            <label><input type="checkbox" name="requires_approval" value="1" <?= ($f['requires_approval'] ?? 0) ? 'checked' : '' ?>> Requires Admin Approval</label>
        </div>
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Create' ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
