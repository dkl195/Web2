<?php
/**
 * Model bảng: booking_details (chi tiết: facility nào, ngày nào, giờ nào)
 *
 * Quan hệ: bookings 1 --- N booking_details (trong project này mỗi booking có 1 detail)
 */
class BookingDetailModel extends BaseModel
{
    public function create(int $bookingId, int $facilityId, string $date, string $start, string $end): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO booking_details (booking_id, facility_id, booking_date, start_time, end_time)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$bookingId, $facilityId, $date, $start, $end]);
    }

    public function countByFacility(int $facilityId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM booking_details WHERE facility_id = ?");
        $stmt->execute([$facilityId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Kiểm tra TRÙNG LỊCH - Rule quan trọng nhất khi defense
     *
     * Công thức overlap:
     *   New Start < Existing End  AND  New End > Existing Start
     *
     * Chỉ check booking có status PENDING hoặc APPROVED
     * (REJECTED / CANCELLED không giữ slot)
     */
    public function hasConflict(
        int $facilityId,
        string $date,
        string $newStart,
        string $newEnd,
        ?int $excludeBookingId = null
    ): bool {
        $sql = "
            SELECT bd.detail_id
            FROM booking_details bd
            INNER JOIN bookings b ON bd.booking_id = b.booking_id
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            WHERE bd.facility_id = ?
              AND bd.booking_date = ?
              AND bs.status_code IN ('PENDING', 'APPROVED')
              AND bd.start_time < ?
              AND bd.end_time > ?
        ";
        $params = [$facilityId, $date, $newEnd, $newStart];

        if ($excludeBookingId !== null) {
            $sql .= " AND b.booking_id != ?";
            $params[] = $excludeBookingId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }
}
