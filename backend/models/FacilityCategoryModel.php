<?php
/** Model bảng: facility_categories */
class FacilityCategoryModel extends BaseModel
{
    public function findAll(): array
    {
        return $this->db->query("
            SELECT c.*, (SELECT COUNT(*) FROM facilities f WHERE f.category_id = c.category_id) AS facility_count
            FROM facility_categories c ORDER BY category_id
        ")->fetchAll();
    }

    public function findAllSimple(): array
    {
        return $this->db->query("SELECT * FROM facility_categories ORDER BY category_name")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM facility_categories WHERE category_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO facility_categories (category_name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE facility_categories SET category_name = ?, description = ? WHERE category_id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM facility_categories WHERE category_id = ?");
        return $stmt->execute([$id]);
    }

    public function countFacilities(int $categoryId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM facilities WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        return (int) $stmt->fetchColumn();
    }
}
