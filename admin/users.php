<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Users - Campus Booking';
$userService = new UserService();

if (isset($_GET['deactivate'])) {
    $id = (int) $_GET['deactivate'];
    if ($id !== Auth::userId()) {
        $userService->deactivate($id);
        Flash::success('User deactivated successfully.');
    }
    header('Location: /webfinal/admin/users.php');
    exit;
}

if (isset($_GET['activate'])) {
    $userService->activate((int) $_GET['activate']);
    Flash::success('User activated successfully.');
    header('Location: /webfinal/admin/users.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($id !== Auth::userId()) {
        $result = $userService->deleteUser($id);
        Flash::set($result['success'] ? 'success' : 'error', $result['message']);
    }
    header('Location: /webfinal/admin/users.php');
    exit;
}

$users = $userService->getAllUsers();

require_once __DIR__ . '/../includes/admin_header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="card">
    <div class="card-header">
        <h2>Manage Users</h2>
        <a href="/webfinal/admin/user_form.php" class="btn btn-primary btn-sm">+ Add User</a>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Full Name</th><th>Email</th><th>Role</th>
                    <th>Student Code</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['user_id'] ?></td>
                    <td><?= e($u['full_name']) ?></td>
                    <td><?= e($u['email']) ?></td>
                    <td><?= e($u['role_name']) ?></td>
                    <td><?= e($u['student_code'] ?? '-') ?></td>
                    <td><span class="badge badge-<?= $u['account_status'] ?>"><?= e($u['account_status']) ?></span></td>
                    <td>
                        <a href="/webfinal/admin/user_form.php?id=<?= $u['user_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <?php if ($u['user_id'] !== Auth::userId()): ?>
                            <?php if ($u['account_status'] === 'active'): ?>
                                <a href="?deactivate=<?= $u['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deactivate?')">Deactivate</a>
                            <?php else: ?>
                                <a href="?activate=<?= $u['user_id'] ?>" class="btn btn-success btn-sm">Activate</a>
                            <?php endif; ?>
                            <a href="?delete=<?= $u['user_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

