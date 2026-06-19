<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Categories - Campus Booking';
$facilityService = new FacilityService();

if (isset($_GET['delete'])) {
    $result = $facilityService->deleteCategory((int) $_GET['delete']);
    Flash::set($result['success'] ? 'success' : 'error', $result['message']);
    header('Location: /webfinal/admin/categories.php');
    exit;
}

$categories = $facilityService->getAllCategories();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
?>

<div class="card">
    <div class="card-header">
        <h2>Manage Categories</h2>
        <a href="/webfinal/admin/category_form.php" class="btn btn-primary btn-sm">+ Add Category</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Facilities</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= $c['category_id'] ?></td>
                    <td><?= e($c['category_name']) ?></td>
                    <td><?= e($c['description'] ?? '-') ?></td>
                    <td><?= $c['facility_count'] ?></td>
                    <td>
                        <a href="/webfinal/admin/category_form.php?id=<?= $c['category_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <?php if ((int)$c['facility_count'] === 0): ?>
                            <a href="?delete=<?= $c['category_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
