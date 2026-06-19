<?php
/** Model bảng: facilities */
class FacilityModel extends BaseModel
{
    public function findAllWithCategory(): array
    {
        return $this->db->query("
            SELECT f.*, c.category_name
            FROM facilities f
            INNER JOIN facility_categories c ON f.category_id = c.category_id
            ORDER BY f.facility_id
        ")->fetchAll();
    }

    /** User chỉ thấy facility available */
    public function findAvailable(?int $categoryId = null): array
    {
        $sql = "
            SELECT f.*, c.category_name
            FROM facilities f
            INNER JOIN facility_categories c ON f.category_id = c.category_id
            WHERE f.facility_status = 'available'
        ";
        $params = [];
        if ($categoryId) {
            $sql .= " AND f.category_id = ?";
            $params[] = $categoryId;
        }
        $sql .= " ORDER BY c.category_name, f.facility_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM facilities WHERE facility_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Lấy facility kèm category + rule (dùng khi booking) */
    public function findWithRule(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT f.*, c.category_name, fr.max_booking_hours, fr.min_notice_hours, fr.requires_approval
            FROM facilities f
            INNER JOIN facility_categories c ON f.category_id = c.category_id
            LEFT JOIN facility_rules fr ON f.facility_id = fr.facility_id
            WHERE f.facility_id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findAvailableById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT f.*, c.category_name, fr.max_booking_hours, fr.min_notice_hours
            FROM facilities f
            INNER JOIN facility_categories c ON f.category_id = c.category_id
            LEFT JOIN facility_rules fr ON f.facility_id = fr.facility_id
            WHERE f.facility_id = ? AND f.facility_status = 'available'
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO facilities (category_id, facility_name, location, capacity, open_time, close_time, facility_status, description)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['category_id'], $data['facility_name'], $data['location'], $data['capacity'],
            $data['open_time'], $data['close_time'], $data['facility_status'], $data['description'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE facilities SET category_id=?, facility_name=?, location=?, capacity=?,
            open_time=?, close_time=?, facility_status=?, description=? WHERE facility_id=?
        ");
        return $stmt->execute([
            $data['category_id'], $data['facility_name'], $data['location'], $data['capacity'],
            $data['open_time'], $data['close_time'], $data['facility_status'], $data['description'], $id,
        ]);
    }

    public function setUnavailable(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE facilities SET facility_status = 'unavailable' WHERE facility_id = ?");
        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM facilities WHERE facility_id = ?");
        return $stmt->execute([$id]);
    }

    public function countAll(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM facilities")->fetchColumn();
    }
}
