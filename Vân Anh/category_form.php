<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Category Form - Campus Booking';
$facilityService = new FacilityService();
$errors = [];
$id = (int) ($_GET['id'] ?? 0);
$cat = ['category_name' => '', 'description' => ''];

if ($id) {
    $row = (new FacilityCategoryModel())->findById($id);
    if (!$row) { header('Location: ' . BASE_URL . '/admin/categories.php'); exit; }
    $cat = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['category_name'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $result = $facilityService->saveCategory($id ?: null, $name, $desc);
    if ($result['success']) {
        Flash::success($id ? 'Category updated.' : 'Category created.');
        header('Location: ' . BASE_URL . '/admin/categories.php');
        exit;
    }
    $errors = $result['errors'] ?? ['Error'];
    $cat = ['category_name' => $name, 'description' => $desc];
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h2><?= $id ? 'Edit Category' : 'Add Category' ?></h2>
        <a href="<?= BASE_URL ?>/admin/categories.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endforeach; ?>
    <form method="POST">
        <div class="form-group">
            <label>Category Name *</label>
            <input type="text" name="category_name" class="form-control" value="<?= e($cat['category_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" name="description" class="form-control" value="<?= e($cat['description']) ?>">
        </div>
        <button type="submit" class="btn btn-primary"><?= $id ? 'Update' : 'Create' ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
