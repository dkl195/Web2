<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

$id = (int) ($_GET['id'] ?? 0);
$bookingService = new BookingService();
$b = $bookingService->getBookingDetail($id);
if (!$b) { header('Location: ' . BASE_URL . '/admin/bookings.php'); exit; }

$pageTitle = 'Edit Booking ' . $b['booking_code'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'purpose'          => trim($_POST['purpose']          ?? ''),
        'participant_count'=> (int) ($_POST['participant_count'] ?? 0),
        'booking_date'     => $_POST['booking_date']  ?? '',
        'start_time'       => $_POST['start_time']    ?? '',
        'end_time'         => $_POST['end_time']      ?? '',
        'admin_note'       => trim($_POST['admin_note'] ?? ''),
    ];

    $result = $bookingService->editBooking($id, $data);

    if ($result['success']) {
        Flash::success($result['message']);
        header('Location: ' . BASE_URL . '/admin/booking_detail.php?id=' . $id);
        exit;
    }
    $errors = $result['errors'] ?? ['Update failed.'];
    // Repopulate with submitted data
    $b = array_merge($b, $data);
}

require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
if ($flash) echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '">' . e($flash['message']) . '</div>';
?>

<div class="page-header-row">
    <div>
        <p class="page-title">Edit Booking</p>
        <p class="page-subtitle">
            <code class="booking-code"><?= e($b['booking_code']) ?></code>
            &nbsp;·&nbsp;
            <span class="badge <?= statusBadgeClass($b['status_code']) ?>"><?= e($b['status_name']) ?></span>
        </p>
    </div>
    <div style="display:flex;gap:0.5rem">
        <a href="<?= BASE_URL ?>/admin/booking_detail.php?id=<?= $id ?>" class="btn btn-secondary btn-sm">View Detail</a>
        <a href="<?= BASE_URL ?>/admin/bookings.php" class="btn btn-secondary btn-sm">← Back</a>
    </div>
</div>

<div class="card">
    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= e($err) ?></div>
    <?php endforeach; ?>

    <!-- Read-only info -->
    <div class="edit-info-grid">
        <div class="edit-info-item">
            <span class="edit-info-label">User</span>
            <span class="edit-info-value"><?= e($b['full_name']) ?> &lt;<?= e($b['email']) ?>&gt;</span>
        </div>
        <div class="edit-info-item">
            <span class="edit-info-label">Facility</span>
            <span class="edit-info-value"><?= e($b['facility_name']) ?> — <?= e($b['location']) ?></span>
        </div>
        <div class="edit-info-item">
            <span class="edit-info-label">Capacity</span>
            <span class="edit-info-value"><?= $b['capacity'] ?> people max</span>
        </div>
        <div class="edit-info-item">
            <span class="edit-info-label">Created</span>
            <span class="edit-info-value"><?= e($b['created_at']) ?></span>
        </div>
    </div>

    <hr style="border:none;border-top:1px solid var(--border);margin:1.5rem 0">

    <form method="POST">
        <div class="form-row">
            <div class="form-group">
                <label for="booking_date">Booking Date *</label>
                <input type="date" id="booking_date" name="booking_date"
                       class="form-control"
                       value="<?= e($b['booking_date']) ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="participant_count">Participants *</label>
                <input type="number" id="participant_count" name="participant_count"
                       class="form-control"
                       value="<?= e($b['participant_count']) ?>"
                       min="1" max="<?= $b['capacity'] ?>"
                       required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="start_time">Start Time *</label>
                <input type="time" id="start_time" name="start_time"
                       class="form-control"
                       value="<?= e(substr($b['start_time'], 0, 5)) ?>"
                       required>
            </div>
            <div class="form-group">
                <label for="end_time">End Time *</label>
                <input type="time" id="end_time" name="end_time"
                       class="form-control"
                       value="<?= e(substr($b['end_time'], 0, 5)) ?>"
                       required>
            </div>
        </div>

        <div class="form-group">
            <label for="purpose">Purpose *</label>
            <input type="text" id="purpose" name="purpose"
                   class="form-control"
                   value="<?= e($b['purpose']) ?>"
                   placeholder="e.g. Group study session"
                   required>
        </div>

        <div class="form-group">
            <label for="admin_note">Admin Note <span style="font-weight:400;color:var(--text-muted)">(optional)</span></label>
            <input type="text" id="admin_note" name="admin_note"
                   class="form-control"
                   value="<?= e($b['admin_note'] ?? '') ?>"
                   placeholder="Internal note for this booking">
        </div>

        <div style="display:flex;gap:0.75rem;margin-top:1.5rem">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= BASE_URL ?>/admin/booking_detail.php?id=<?= $id ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
