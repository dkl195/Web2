<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Bookings - Campus Booking';
$bookingService = new BookingService();
$statusFilter = $_GET['status'] ?? '';

// Quick approve/reject từ list
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quick_action'])) {
    $bookingId = (int) ($_POST['booking_id'] ?? 0);
    $action    = $_POST['quick_action'];
    $note      = trim($_POST['admin_note'] ?? '');

    if ($action === 'approve') {
        $bookingService->approve($bookingId);
        Flash::success('Booking approved.');
    } elseif ($action === 'reject') {
        $bookingService->reject($bookingId, $note ?: 'Rejected by admin.');
        Flash::error('Booking rejected.');
    }
    header('Location: ' . BASE_URL . '/admin/bookings.php' . ($statusFilter ? '?status=' . $statusFilter : ''));
    exit;
}

if (isset($_GET['delete'])) {
    $bookingService->deleteBooking((int) $_GET['delete']);
    Flash::success('Booking deleted.');
    header('Location: ' . BASE_URL . '/admin/bookings.php');
    exit;
}

$bookings = $bookingService->getAllBookings($statusFilter ?: null);

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="page-header-row">
    <div>
        <p class="page-title">Manage Bookings</p>
        <p class="page-subtitle">Review, approve or reject booking requests</p>
    </div>
    <a href="<?= BASE_URL ?>/admin/booking_statuses.php" class="btn btn-secondary btn-sm">Manage Statuses</a>
</div>

<!-- Filter bar -->
<form method="GET" class="filter-bar">
    <div class="form-group">
        <label>Filter by Status</label>
        <select name="status" class="form-control" onchange="this.form.submit()">
            <option value="">All statuses</option>
            <?php foreach (['PENDING','APPROVED','REJECTED','CANCELLED'] as $s): ?>
                <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<div class="card" style="padding:0">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>User</th>
                    <th>Facility</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Pax</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr class="<?= $b['status_code'] === 'PENDING' ? 'row-pending' : '' ?>">
                    <td>
                        <code class="booking-code"><?= e($b['booking_code']) ?></code>
                    </td>
                    <td><?= e($b['full_name']) ?></td>
                    <td><?= e($b['facility_name']) ?></td>
                    <td><?= formatDate($b['booking_date']) ?></td>
                    <td style="white-space:nowrap"><?= formatTime($b['start_time']) ?> – <?= formatTime($b['end_time']) ?></td>
                    <td><?= $b['participant_count'] ?></td>
                    <td><span class="badge <?= statusBadgeClass($b['status_code']) ?>"><?= e($b['status_name']) ?></span></td>
                    <td>
                        <div class="action-cell">
                            <!-- Luôn có nút View -->
                            <a href="<?= BASE_URL ?>/admin/booking_detail.php?id=<?= $b['booking_id'] ?>"
                               class="btn btn-sm btn-view-action">View</a>

                            <!-- Edit — luôn có -->
                            <a href="<?= BASE_URL ?>/admin/booking_edit.php?id=<?= $b['booking_id'] ?>"
                               class="btn btn-sm btn-edit-action">Edit</a>

                            <?php if ($b['status_code'] === 'PENDING'): ?>
                                <!-- Approve nhanh -->
                                <form method="POST" style="display:inline">
                                    <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                    <input type="hidden" name="quick_action" value="approve">
                                    <button type="submit" class="btn btn-sm btn-approve-action"
                                            onclick="return confirm('Approve booking <?= e($b['booking_code']) ?>?')">
                                        Approve
                                    </button>
                                </form>

                                <!-- Reject nhanh — mở inline panel -->
                                <button class="btn btn-sm btn-reject-action"
                                        onclick="toggleReject(<?= $b['booking_id'] ?>)">
                                    Reject
                                </button>
                            <?php endif; ?>

                            <!-- Delete -->
                            <a href="?delete=<?= $b['booking_id'] ?><?= $statusFilter ? '&status='.$statusFilter : '' ?>"
                               class="btn btn-sm btn-delete-action"
                               onclick="return confirm('Delete this booking permanently?')">Delete</a>
                        </div>

                        <?php if ($b['status_code'] === 'PENDING'): ?>
                        <!-- Reject panel — ẩn mặc định -->
                        <div id="reject-<?= $b['booking_id'] ?>" class="reject-panel" style="display:none">
                            <form method="POST" style="display:flex;gap:0.5rem;align-items:center;margin-top:0.5rem">
                                <input type="hidden" name="booking_id" value="<?= $b['booking_id'] ?>">
                                <input type="hidden" name="quick_action" value="reject">
                                <input type="text" name="admin_note" class="form-control"
                                       placeholder="Rejection reason (optional)"
                                       style="font-size:0.8rem;padding:0.4rem 0.7rem;min-width:200px">
                                <button type="submit" class="btn btn-sm btn-delete-action">Confirm Reject</button>
                                <button type="button" class="btn btn-sm btn-secondary"
                                        onclick="toggleReject(<?= $b['booking_id'] ?>)">Cancel</button>
                            </form>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($bookings)): ?>
                <tr><td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted)">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleReject(id) {
    const panel = document.getElementById('reject-' + id);
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    if (panel.style.display === 'block') {
        panel.querySelector('input[name="admin_note"]').focus();
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
