<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Roles - Campus Booking';
$roleModel = new RoleModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'create') {
            $roleModel->create(trim($_POST['role_name'] ?? ''), trim($_POST['description'] ?? ''));
            Flash::success('Role created.');
        } elseif ($action === 'update') {
            $roleModel->update((int) $_POST['role_id'], trim($_POST['role_name'] ?? ''), trim($_POST['description'] ?? ''));
            Flash::success('Role updated.');
        }
    } catch (PDOException $e) {
        Flash::error('Role name already exists.');
    }
    header('Location: /web 2 final/Web2/admin/roles.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if ($roleModel->countUsers($id) === 0) {
        $roleModel->delete($id);
        Flash::success('Role deleted.');
    } else {
        Flash::error('Cannot delete role assigned to users.');
    }
    header('Location: /web 2 final/Web2/admin/roles.php');
    exit;
}

$roles = $roleModel->findAll();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
?>

<div class="card">
    <div class="card-header"><h2>Manage Roles</h2></div>
    <form method="POST" class="form-box">
        <input type="hidden" name="action" value="create">
        <div class="form-row">
            <div class="form-group"><label>Role Name</label><input type="text" name="role_name" class="form-control" required></div>
            <div class="form-group"><label>Description</label><input type="text" name="description" class="form-control"></div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Add Role</button>
    </form>
    <div class="table-responsive">
        <table>
            <thead><tr><th>ID</th><th>Role Name</th><th>Description</th><th>Users</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($roles as $r): ?>
                <tr>
                    <td><?= $r['role_id'] ?></td>
                    <td>
                        <form method="POST" style="display:contents;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="role_id" value="<?= $r['role_id'] ?>">
                            <input type="text" name="role_name" class="form-control" value="<?= e($r['role_name']) ?>" style="max-width:150px;">
                    </td>
                    <td><input type="text" name="description" class="form-control" value="<?= e($r['description'] ?? '') ?>"></td>
                    <td><?= $r['user_count'] ?></td>
                    <td style="display:flex;gap:0.5rem;">
                            <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                        </form>
                        <?php if ((int)$r['user_count'] === 0): ?>
                            <a href="?delete=<?= $r['role_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
