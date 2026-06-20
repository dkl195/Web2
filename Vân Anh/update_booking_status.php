<?php
/**
 * VIEW: admin/update_booking_status.php
 * Backend: BookingService::approve() hoặc ::reject()
 */
require_once __DIR__ . '/../includes/auth.php';
requireAdmin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/admin/bookings.php');
    exit;
}

$bookingId = (int) ($_POST['booking_id'] ?? 0);
$action = $_POST['action'] ?? '';
$adminNote = trim($_POST['admin_note'] ?? '');

$bookingService = new BookingService();

if ($action === 'approve') {
    $result = $bookingService->approve($bookingId);
} elseif ($action === 'reject') {
    $result = $bookingService->reject($bookingId, $adminNote);
} else {
    $result = ['success' => false, 'message' => 'Invalid action.'];
}

Flash::set($result['success'] ? 'success' : 'error', $result['message']);
header('Location: ' . BASE_URL . '/admin/booking_detail.php?id=' . $bookingId);
exit;
