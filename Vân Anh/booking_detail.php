<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$bookingService = new BookingService();
$b = $bookingService->getBookingDetail($id);
if (!$b) { header('Location: ' . BASE_URL . '/admin/bookings.php'); exit; }

$pageTitle = 'Booking ' . $b['booking_code'];
require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
}
?>

<div class="card">
    <div class="card-header">
        <h2>Booking Detail: <?= e($b['booking_code']) ?></h2>
        <a href="<?= BASE_URL ?>/admin/bookings.php" class="btn btn-secondary btn-sm">Back</a>
    </div>
    <ul class="detail-list">
        <li><span class="label">Status</span><span class="value"><span class="badge <?= statusBadgeClass($b['status_code']) ?>"><?= e($b['status_name']) ?></span></span></li>
        <li><span class="label">User</span><span class="value"><?= e($b['full_name']) ?> (<?= e($b['email']) ?>)</span></li>
        <li><span class="label">Student Code</span><span class="value"><?= e($b['student_code'] ?? '-') ?></span></li>
        <li><span class="label">Facility</span><span class="value"><?= e($b['facility_name']) ?> — <?= e($b['location']) ?></span></li>
        <li><span class="label">Date</span><span class="value"><?= formatDate($b['booking_date']) ?></span></li>
        <li><span class="label">Time</span><span class="value"><?= formatTime($b['start_time']) ?> - <?= formatTime($b['end_time']) ?></span></li>
        <li><span class="label">Participants</span><span class="value"><?= $b['participant_count'] ?> / <?= $b['capacity'] ?></span></li>
        <li><span class="label">Purpose</span><span class="value"><?= e($b['purpose']) ?></span></li>
        <li><span class="label">Created At</span><span class="value"><?= e($b['created_at']) ?></span></li>
        <?php if ($b['admin_note']): ?>
            <li><span class="label">Admin Note</span><span class="value"><?= e($b['admin_note']) ?></span></li>
        <?php endif; ?>
    </ul>
    <?php if ($b['status_code'] === 'PENDING'): ?>
        <div class="form-actions" style="margin-top:1.5rem;">
            <form method="POST" action="<?= BASE_URL ?>/admin/update_booking_status.php" style="display:inline;">
                <input type="hidden" name="booking_id" value="<?= $id ?>">
                <input type="hidden" name="action" value="approve">
                <button type="submit" class="btn btn-sm btn-success-action">Approve</button>
            </form>
            <form method="POST" action="<?= BASE_URL ?>/admin/update_booking_status.php" style="display:inline-flex;gap:0.5rem;align-items:flex-end;">
                <input type="hidden" name="booking_id" value="<?= $id ?>">
                <input type="hidden" name="action" value="reject">
                <input type="text" name="admin_note" class="form-control" placeholder="Rejection reason..." required style="min-width:250px;">
                <button type="submit" class="btn btn-sm btn-delete-action">Reject</button>
            </form>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
