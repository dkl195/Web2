<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Facility Rules - Campus Booking';
$facilityService = new FacilityService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $facilityService->updateRule(
        (int) $_POST['facility_id'],
        (int) $_POST['max_booking_hours'],
        (int) $_POST['min_notice_hours'],
        isset($_POST['requires_approval']) ? 1 : 0
    );
    Flash::success('Rule updated.');
    header('Location: /web 2 final/Web2/admin/facility_rules.php');
    exit;
}

$rules = $facilityService->getAllRules();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-success">' . e($flash['message']) . '</div>';
?>

<div class="card">
    <div class="card-header">
        <h2>Facility Booking Rules</h2>
        <a href="/web 2 final/Web2/admin/facilities.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>Facility</th><th>Location</th><th>Max Hours</th><th>Min Notice</th><th>Approval</th><th>Action</th></tr></thead>
            <tbody>
                <?php foreach ($rules as $r): ?>
                <tr>
                    <form method="POST">
                        <input type="hidden" name="facility_id" value="<?= $r['facility_id'] ?>">
                        <td><?= e($r['facility_name']) ?></td>
                        <td><?= e($r['location']) ?></td>
                        <td><input type="number" name="max_booking_hours" class="form-control" value="<?= e($r['max_booking_hours'] ?? 3) ?>" style="max-width:80px;"></td>
                        <td><input type="number" name="min_notice_hours" class="form-control" value="<?= e($r['min_notice_hours'] ?? 24) ?>" style="max-width:80px;"></td>
                        <td><input type="checkbox" name="requires_approval" value="1" <?= ($r['requires_approval'] ?? 1) ? 'checked' : '' ?>></td>
                        <td><button type="submit" class="btn btn-primary btn-sm">Save</button></td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
