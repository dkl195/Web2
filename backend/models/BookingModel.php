<?php
/** Model bảng: bookings (header của 1 yêu cầu đặt) */
class BookingModel extends BaseModel
{
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM bookings WHERE booking_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findWithStatus(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, bs.status_code, bs.status_name
            FROM bookings b
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, bs.status_code, bs.status_name,
                   bd.booking_date, bd.start_time, bd.end_time,
                   f.facility_name, f.location
            FROM bookings b
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            INNER JOIN booking_details bd ON b.booking_id = bd.booking_id
            INNER JOIN facilities f ON bd.facility_id = f.facility_id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findAllAdmin(?string $statusCode = null): array
    {
        $sql = "
            SELECT b.*, bs.status_code, bs.status_name, u.full_name, u.email,
                   bd.booking_date, bd.start_time, bd.end_time,
                   f.facility_name, f.location
            FROM bookings b
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            INNER JOIN users u ON b.user_id = u.user_id
            INNER JOIN booking_details bd ON b.booking_id = bd.booking_id
            INNER JOIN facilities f ON bd.facility_id = f.facility_id
        ";
        $params = [];
        if ($statusCode) {
            $sql .= " WHERE bs.status_code = ?";
            $params[] = $statusCode;
        }
        $sql .= " ORDER BY b.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findDetailForAdmin(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT b.*, bs.status_code, bs.status_name, u.full_name, u.email,
                   up.student_code, up.faculty, up.class_name,
                   bd.booking_date, bd.start_time, bd.end_time,
                   f.facility_name, f.location, f.capacity
            FROM bookings b
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            INNER JOIN users u ON b.user_id = u.user_id
            LEFT JOIN user_profiles up ON u.user_id = up.user_id
            INNER JOIN booking_details bd ON b.booking_id = bd.booking_id
            INNER JOIN facilities f ON bd.facility_id = f.facility_id
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $code, int $userId, int $statusId, string $purpose, int $participants): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO bookings (booking_code, user_id, status_id, purpose, participant_count)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$code, $userId, $statusId, $purpose, $participants]);
        return (int) $this->db->lastInsertId();
    }

    public function updateStatus(int $id, int $statusId, ?string $adminNote = null): bool
    {
        $stmt = $this->db->prepare("UPDATE bookings SET status_id = ?, admin_note = ? WHERE booking_id = ?");
        return $stmt->execute([$statusId, $adminNote, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM bookings WHERE booking_id = ?");
        return $stmt->execute([$id]);
    }

    /** Sinh mã booking: BK + YYYYMMDD + số thứ tự (vd: BK20260618001) */
    public function generateCode(): string
    {
        $prefix = 'BK' . date('Ymd');
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE booking_code LIKE ?");
        $stmt->execute([$prefix . '%']);
        $count = (int) $stmt->fetchColumn() + 1;
        return $prefix . str_pad((string) $count, 3, '0', STR_PAD_LEFT);
    }

    public function countByStatusCode(string $code): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM bookings b
            INNER JOIN booking_statuses bs ON b.status_id = bs.status_id
            WHERE bs.status_code = ?
        ");
        $stmt->execute([$code]);
        return (int) $stmt->fetchColumn();
    }

    public function countByUser(int $userId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }
}
