<?php
/**
 * BookingService - Business logic Module 3: Booking Management
 *
 * ★ FILE QUAN TRỌNG NHẤT KHI DEFENSE ★
 *
 * Flow tạo booking:
 *   1. requireLogin (gọi ở View trước)
 *   2. BookingValidator validate toàn bộ rules
 *   3. Transaction: INSERT bookings (PENDING) + INSERT booking_details
 *
 * Flow Admin approve/reject:
 *   - Chỉ booking PENDING mới được duyệt/từ chối
 *
 * Flow User cancel:
 *   - Chỉ chủ booking (user_id = session) mới cancel được
 *   - Chỉ PENDING hoặc APPROVED mới cancel được
 *   - Update status = CANCELLED (không xóa — giữ lịch sử, giải phóng slot)
 */
class BookingService
{
    private BookingModel $bookingModel;
    private BookingDetailModel $detailModel;
    private BookingStatusModel $statusModel;
    private FacilityModel $facilityModel;
    private BookingValidator $validator;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->detailModel = new BookingDetailModel();
        $this->statusModel = new BookingStatusModel();
        $this->facilityModel = new FacilityModel();
        $this->validator = new BookingValidator();
    }

    /**
     * Tạo booking mới — status mặc định PENDING
     */
    public function createBooking(int $userId, array $data): array
    {
        $facility = $this->facilityModel->findWithRule((int) $data['facility_id']);
        $errors = $this->validator->validate($data, $facility);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        Database::beginTransaction();
        try {
            $pendingId = $this->statusModel->getIdByCode('PENDING');
            $bookingCode = $this->bookingModel->generateCode();

            // Bước 1: INSERT header vào bảng bookings
            $bookingId = $this->bookingModel->create(
                $bookingCode,
                $userId,
                $pendingId,
                $data['purpose'],
                (int) $data['participant_count']
            );

            // Bước 2: INSERT chi tiết vào booking_details
            $this->detailModel->create(
                $bookingId,
                (int) $data['facility_id'],
                $data['booking_date'],
                $data['start_time'],
                $data['end_time']
            );

            Database::commit();
            return [
                'success' => true,
                'booking_code' => $bookingCode,
                'message' => "Booking created successfully! Code: {$bookingCode} (Status: Pending)",
            ];
        } catch (Exception $e) {
            Database::rollBack();
            return ['success' => false, 'errors' => ['Failed to create booking. Please try again.']];
        }
    }

    /** Admin approve booking PENDING → APPROVED */
    public function approve(int $bookingId): array
    {
        $booking = $this->bookingModel->findWithStatus($bookingId);
        if (!$booking || $booking['status_code'] !== 'PENDING') {
            return ['success' => false, 'message' => 'Only pending bookings can be approved.'];
        }

        $approvedId = $this->statusModel->getIdByCode('APPROVED');
        $this->bookingModel->updateStatus($bookingId, $approvedId, null);
        return ['success' => true, 'message' => 'Booking approved successfully.'];
    }

    /** Admin reject booking PENDING → REJECTED + lưu admin_note */
    public function reject(int $bookingId, string $adminNote): array
    {
        if (trim($adminNote) === '') {
            return ['success' => false, 'message' => 'Please provide a rejection reason.'];
        }

        $booking = $this->bookingModel->findWithStatus($bookingId);
        if (!$booking || $booking['status_code'] !== 'PENDING') {
            return ['success' => false, 'message' => 'Only pending bookings can be rejected.'];
        }

        $rejectedId = $this->statusModel->getIdByCode('REJECTED');
        $this->bookingModel->updateStatus($bookingId, $rejectedId, $adminNote);
        return ['success' => true, 'message' => 'Booking rejected.'];
    }

    /**
     * User hủy booking của chính mình
     * Business rule: booking.user_id phải = session user_id
     */
    public function cancel(int $bookingId, int $userId): array
    {
        $booking = $this->bookingModel->findWithStatus($bookingId);

        if (!$booking || (int) $booking['user_id'] !== $userId) {
            return ['success' => false, 'message' => 'Access denied or booking not found.'];
        }

        if (!in_array($booking['status_code'], ['PENDING', 'APPROVED'])) {
            return ['success' => false, 'message' => 'This booking cannot be cancelled.'];
        }

        $cancelledId = $this->statusModel->getIdByCode('CANCELLED');
        $this->bookingModel->updateStatus($bookingId, $cancelledId, null);
        return ['success' => true, 'message' => 'Booking cancelled successfully.'];
    }

    // --- Read operations ---
    public function getMyBookings(int $userId): array
    {
        return $this->bookingModel->findByUser($userId);
    }

    public function getAllBookings(?string $statusCode = null): array
    {
        return $this->bookingModel->findAllAdmin($statusCode);
    }

    public function getBookingDetail(int $id): ?array
    {
        return $this->bookingModel->findDetailForAdmin($id);
    }

    public function deleteBooking(int $id): void
    {
        $this->bookingModel->delete($id);
    }

    public function countByStatus(string $code): int
    {
        return $this->bookingModel->countByStatusCode($code);
    }

    // --- Booking Status CRUD ---
    public function getAllStatuses(): array
    {
        return $this->statusModel->findAll();
    }

    public function saveStatus(?int $id, string $code, string $name): array
    {
        try {
            if ($id) {
                $this->statusModel->update($id, strtoupper($code), $name);
            } else {
                $this->statusModel->create(strtoupper($code), $name);
            }
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Status code already exists.'];
        }
    }

    public function deleteStatus(int $id): array
    {
        if ($this->statusModel->countBookings($id) > 0) {
            return ['success' => false, 'message' => 'Cannot delete status in use.'];
        }
        $this->statusModel->delete($id);
        return ['success' => true, 'message' => 'Status deleted.'];
    }
}
