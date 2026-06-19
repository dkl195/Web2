<?php
/**
 * VIEW: user/create_booking.php (Controller xử lý POST)
 * Backend: BookingService::createBooking()
 */
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

if (isAdmin()) {
    header('Location: /webfinal/admin/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /webfinal/user/facilities.php');
    exit;
}

$data = [
    'facility_id' => (int) ($_POST['facility_id'] ?? 0),
    'booking_date' => $_POST['booking_date'] ?? '',
    'start_time' => $_POST['start_time'] ?? '',
    'end_time' => $_POST['end_time'] ?? '',
    'participant_count' => (int) ($_POST['participant_count'] ?? 0),
    'purpose' => trim($_POST['purpose'] ?? ''),
];

$bookingService = new BookingService();
$result = $bookingService->createBooking(Auth::userId(), $data);

if ($result['success']) {
    Flash::success($result['message']);
    header('Location: /webfinal/user/my_bookings.php');
    exit;
}

$_SESSION['booking_errors'] = $result['errors'] ?? ['Unknown error'];
$_SESSION['booking_old'] = $data;
header('Location: /webfinal/user/booking_form.php?facility_id=' . $data['facility_id']);
exit;
