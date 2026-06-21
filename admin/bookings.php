<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$pageTitle = 'Manage Bookings - Campus Booking';
$bookingService = new BookingService();
$statusFilter = $_GET['status'] ?? '';

if (isset($_GET['delete'])) {
    $bookingService->deleteBooking((int) $_GET['delete']);
    Flash::success('Booking deleted.');
    header('Location: /webfinal/admin/bookings.php');
    exit;
}

$bookings = $bookingService->getAllBookings($statusFilter ?: null);

require_once __DIR__ . '/../includes/admin_header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="card">
    <div class="card-header">
        <h2>Manage Bookings</h2>
        <a href="/webfinal/admin/booking_statuses.php" class="btn btn-secondary btn-sm">Statuses</a>
    </div>
    <form method="GET" class="filter-bar">
        <div class="form-group">
            <label>Filter by Status</label>
            <select name="status" class="form-control" onchange="this.form.submit()">
                <option value="">All</option>
                <?php foreach (['PENDING','APPROVED','REJECTED','CANCELLED'] as $s): ?>
                    <option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <div class="table-responsive">
        <table>
            <thead>
                <tr><th>Code</th><th>User</th><th>Facility</th><th>Date</th><th>Time</th><th>Participants</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b): ?>
                <tr>
                    <td><?= e($b['booking_code']) ?></td>
                    <td><?= e($b['full_name']) ?></td>
                    <td><?= e($b['facility_name']) ?></td>
                    <td><?= formatDate($b['booking_date']) ?></td>
                    <td><?= formatTime($b['start_time']) ?> - <?= formatTime($b['end_time']) ?></td>
                    <td><?= $b['participant_count'] ?></td>
                    <td><span class="badge <?= statusBadgeClass($b['status_code']) ?>"><?= e($b['status_name']) ?></span></td>
                    <td>
                        <a href="/webfinal/admin/booking_detail.php?id=<?= $b['booking_id'] ?>" class="btn btn-secondary btn-sm">View</a>
                        <a href="?delete=<?= $b['booking_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

