<?php
/** Model bảng: booking_statuses */
class BookingStatusModel extends BaseModel
{
    public function findAll(): array
    {
        return $this->db->query("
            SELECT bs.*, (SELECT COUNT(*) FROM bookings b WHERE b.status_id = bs.status_id) AS booking_count
            FROM booking_statuses bs ORDER BY status_id
        ")->fetchAll();
    }

    public function findByCode(string $code): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM booking_statuses WHERE status_code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch() ?: null;
    }

    public function getIdByCode(string $code): ?int
    {
        $row = $this->findByCode($code);
        return $row ? (int) $row['status_id'] : null;
    }

    public function create(string $code, string $name): bool
    {
        $stmt = $this->db->prepare("INSERT INTO booking_statuses (status_code, status_name) VALUES (?, ?)");
        return $stmt->execute([$code, $name]);
    }

    public function update(int $id, string $code, string $name): bool
    {
        $stmt = $this->db->prepare("UPDATE booking_statuses SET status_code = ?, status_name = ? WHERE status_id = ?");
        return $stmt->execute([$code, $name, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM booking_statuses WHERE status_id = ?");
        return $stmt->execute([$id]);
    }

    public function countBookings(int $statusId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM bookings WHERE status_id = ?");
        $stmt->execute([$statusId]);
        return (int) $stmt->fetchColumn();
    }
}
