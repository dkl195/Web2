<?php
/**
 * VIEW: user/cancel_booking.php
 * Backend: BookingService::cancel() — check user_id = session
 */
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if (isAdmin()) {
    header('Location: /webfinal/admin/bookings.php');
    exit;
}

$bookingId = (int) ($_GET['id'] ?? 0);
$bookingService = new BookingService();
$result = $bookingService->cancel($bookingId, Auth::userId());

Flash::set($result['success'] ? 'success' : 'error', $result['message']);
header('Location: /webfinal/user/my_bookings.php');
exit;
