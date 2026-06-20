<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Booking Statuses - Campus Booking';
$bookingService = new BookingService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $result = $bookingService->saveStatus(null, $_POST['status_code'] ?? '', $_POST['status_name'] ?? '');
    } else {
        $result = $bookingService->saveStatus((int) $_POST['status_id'], $_POST['status_code'] ?? '', $_POST['status_name'] ?? '');
    }
    Flash::set($result['success'] ? 'success' : 'error', $result['message'] ?? 'Saved.');
    header('Location: ' . BASE_URL . '/admin/booking_statuses.php');
    exit;
}

if (isset($_GET['delete'])) {
    $result = $bookingService->deleteStatus((int) $_GET['delete']);
    Flash::set($result['success'] ? 'success' : 'error', $result['message']);
    header('Location: ' . BASE_URL . '/admin/booking_statuses.php');
    exit;
}

$statuses = $bookingService->getAllStatuses();

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
?>

<div class="card">
    <div class="card-header">
        <h2>Manage Booking Statuses</h2>
        <a href="<?= BASE_URL ?>/admin/bookings.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <form method="POST" class="form-box">
        <input type="hidden" name="action" value="create">
        <div class="form-row">
            <div class="form-group"><label>Status Code</label><input type="text" name="status_code" class="form-control" required></div>
            <div class="form-group"><label>Status Name</label><input type="text" name="status_name" class="form-control" required></div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Add Status</button>
    </form>
    <div class="table-responsive">
        <table>
            <thead><tr><th>ID</th><th>Code</th><th>Name</th><th>Bookings</th><th>Actions</th></tr></thead>
            <tbody>
                <?php foreach ($statuses as $s): ?>
                <tr>
                    <td><?= $s['status_id'] ?></td>
                    <td>
                        <form method="POST" style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="status_id" value="<?= $s['status_id'] ?>">
                            <input type="text" name="status_code" class="form-control" value="<?= e($s['status_code']) ?>" style="max-width:150px;">
                    </td>
                    <td><input type="text" name="status_name" class="form-control" value="<?= e($s['status_name']) ?>" style="max-width:150px;"></td>
                    <td><?= $s['booking_count'] ?></td>
                    <td style="display:flex;gap:0.5rem;">
                            <button type="submit" class="btn btn-secondary btn-sm">Save</button>
                        </form>
                        <?php if ((int)$s['booking_count'] === 0): ?>
                            <a href="?delete=<?= $s['status_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
