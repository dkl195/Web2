<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Facilities - Campus Booking';
$facilityService = new FacilityService();

if (isset($_GET['delete'])) {
    $result = $facilityService->deleteFacility((int) $_GET['delete']);
    Flash::set('success', $result['message']);
    header('Location: /web 2 final/Web2/admin/facilities.php');
    exit;
}

$facilities = $facilityService->getAllFacilities();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="card">
    <div class="card-header">
        <h2>Manage Facilities</h2>
        <div style="display:flex;gap:0.5rem;">
            <a href="/web 2 final/Web2/admin/categories.php" class="btn btn-secondary btn-sm">Categories</a>
            <a href="/web 2 final/Web2/admin/facility_rules.php" class="btn btn-secondary btn-sm">Rules</a>
            <a href="/web 2 final/Web2/admin/facility_form.php" class="btn btn-primary btn-sm">+ Add Facility</a>
        </div>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr><th>ID</th><th>Name</th><th>Category</th><th>Location</th><th>Capacity</th><th>Hours</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($facilities as $f): ?>
                <tr>
                    <td><?= $f['facility_id'] ?></td>
                    <td><?= e($f['facility_name']) ?></td>
                    <td><?= e($f['category_name']) ?></td>
                    <td><?= e($f['location']) ?></td>
                    <td><?= $f['capacity'] ?></td>
                    <td><?= formatTime($f['open_time']) ?> - <?= formatTime($f['close_time']) ?></td>
                    <td><span class="badge badge-<?= $f['facility_status'] ?>"><?= e($f['facility_status']) ?></span></td>
                    <td>
                        <a href="/web 2 final/Web2/admin/facility_detail.php?id=<?= $f['facility_id'] ?>" class="btn btn-secondary btn-sm">View</a>
                        <a href="/web 2 final/Web2/admin/facility_form.php?id=<?= $f['facility_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="?delete=<?= $f['facility_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
