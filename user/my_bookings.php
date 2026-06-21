<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if (isAdmin()) {
    header('Location: ' . BASE_URL . '/admin/bookings.php');
    exit;
}

$pageTitle = 'My Bookings - Campus Booking';
$bookingService = new BookingService();
$bookings = $bookingService->getMyBookings(Auth::userId());

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<p class="page-title">My Bookings</p>
<p class="page-subtitle">Theo dõi trạng thái các booking của bạn</p>

<div class="card">
    <div class="card-header">
        <h2>Danh sách booking</h2>
        <a href="<?= BASE_URL ?>/user/facilities.php" class="btn btn-primary btn-sm">Book a Facility</a>
    </div>

    <?php if (empty($bookings)): ?>
        <div class="empty-state">
            <p>Bạn chưa có booking nào.</p>
            <a href="<?= BASE_URL ?>/user/facilities.php" class="btn btn-primary">Browse Facilities</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Code</th><th>Facility</th><th>Date</th><th>Time</th>
                        <th>Participants</th><th>Purpose</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?= e($b['booking_code']) ?></td>
                        <td><?= e($b['facility_name']) ?></td>
                        <td><?= formatDate($b['booking_date']) ?></td>
                        <td><?= formatTime($b['start_time']) ?> - <?= formatTime($b['end_time']) ?></td>
                        <td><?= $b['participant_count'] ?></td>
                        <td><?= e($b['purpose']) ?></td>
                        <td><span class="badge <?= statusBadgeClass($b['status_code']) ?>"><?= e($b['status_name']) ?></span></td>
                        <td>
                            <?php if (in_array($b['status_code'], ['PENDING', 'APPROVED'])): ?>
                                <a href="/web 2 final/Web2/user/cancel_booking.php?id=<?= $b['booking_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Cancel this booking?')">Cancel</a>
                            <?php else: ?> - <?php endif; ?>
                            <?php if ($b['admin_note']): ?>
                                <br><small class="note-text">Note: <?= e($b['admin_note']) ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
