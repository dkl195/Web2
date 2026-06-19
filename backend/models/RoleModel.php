<?php
/** Model bảng: roles */
class RoleModel extends BaseModel
{
    public function findAll(): array
    {
        return $this->db->query("
            SELECT r.*, (SELECT COUNT(*) FROM users u WHERE u.role_id = r.role_id) AS user_count
            FROM roles r ORDER BY role_id
        ")->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE role_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name, string $description): bool
    {
        $stmt = $this->db->prepare("INSERT INTO roles (role_name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }

    public function update(int $id, string $name, string $description): bool
    {
        $stmt = $this->db->prepare("UPDATE roles SET role_name = ?, description = ? WHERE role_id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM roles WHERE role_id = ?");
        return $stmt->execute([$id]);
    }

    public function countUsers(int $roleId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role_id = ?");
        $stmt->execute([$roleId]);
        return (int) $stmt->fetchColumn();
    }
}
