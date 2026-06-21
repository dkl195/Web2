<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'User Form - Campus Booking';
$userService = new UserService();
$roleModel = new RoleModel();
$errors = [];
$userId = (int) ($_GET['id'] ?? 0);
$isEdit = $userId > 0;
$user = ['full_name' => '', 'email' => '', 'role_id' => 2, 'account_status' => 'active', 'student_code' => '', 'faculty' => '', 'class_name' => ''];

if ($isEdit) {
    $user = $userService->getUserWithProfile($userId);
    if (!$user) { header('Location: /webfinal/admin/users.php'); exit; }
}

$roles = $roleModel->findAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'role_id' => (int) ($_POST['role_id'] ?? 2),
        'account_status' => $_POST['account_status'] ?? 'active',
        'student_code' => trim($_POST['student_code'] ?? ''),
        'faculty' => trim($_POST['faculty'] ?? ''),
        'class_name' => trim($_POST['class_name'] ?? ''),
    ];
    $result = $userService->saveUser($isEdit ? $userId : null, $data, $_POST['password'] ?? '');

    if ($result['success']) {
        Flash::success($result['message']);
        header('Location: /webfinal/admin/users.php');
        exit;
    }
    $errors = $result['errors'] ?? [$result['message'] ?? 'Error'];
    $user = array_merge($user, $data);
}

require_once __DIR__ . '/../includes/admin_header.php';
?>

<div class="card">
    <div class="card-header">
        <h2><?= $isEdit ? 'Edit User' : 'Add User' ?></h2>
        <a href="/webfinal/admin/users.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <?php foreach ($errors as $err): ?><div class="alert alert-error"><?= e($err) ?></div><?php endforeach; ?>
    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label>Full Name *</label>
                <input type="text" name="full_name" class="form-control" value="<?= e($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" class="form-control" value="<?= e($user['email']) ?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Role</label>
                <select name="role_id" class="form-control">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['role_id'] ?>" <?= ($user['role_id'] ?? 2) == $r['role_id'] ? 'selected' : '' ?>><?= e($r['role_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="account_status" class="form-control">
                    <option value="active" <?= ($user['account_status'] ?? '') === 'active' ? 'selected' : '' ?>>active</option>
                    <option value="inactive" <?= ($user['account_status'] ?? '') === 'inactive' ? 'selected' : '' ?>>inactive</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Password <?= $isEdit ? '(leave blank to keep)' : '*' ?></label>
            <input type="password" name="password" class="form-control" <?= $isEdit ? '' : 'required minlength="6"' ?>>
        </div>
        <div class="form-group">
            <label>Student Code</label>
            <input type="text" name="student_code" class="form-control" value="<?= e($user['student_code'] ?? '') ?>">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Faculty</label>
                <input type="text" name="faculty" class="form-control" value="<?= e($user['faculty'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Class</label>
                <input type="text" name="class_name" class="form-control" value="<?= e($user['class_name'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Create' ?></button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

