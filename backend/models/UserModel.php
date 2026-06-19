<?php
/** Model bảng: users */
class UserModel extends BaseModel
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /** Login: lấy user kèm role_name từ bảng roles */
    public function findByEmailWithRole(string $email): ?array
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name
            FROM users u
            INNER JOIN roles r ON u.role_id = r.role_id
            WHERE u.email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findAllWithProfile(): array
    {
        return $this->db->query("
            SELECT u.*, r.role_name, up.student_code, up.faculty, up.class_name
            FROM users u
            INNER JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN user_profiles up ON u.user_id = up.user_id
            ORDER BY u.user_id
        ")->fetchAll();
    }

    public function findWithProfile(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT u.*, r.role_name, up.profile_id, up.student_code, up.faculty, up.class_name
            FROM users u
            INNER JOIN roles r ON u.role_id = r.role_id
            LEFT JOIN user_profiles up ON u.user_id = up.user_id
            WHERE u.user_id = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function emailExists(string $email, ?int $excludeUserId = null): bool
    {
        $sql = "SELECT user_id FROM users WHERE email = ?";
        $params = [$email];
        if ($excludeUserId !== null) {
            $sql .= " AND user_id != ?";
            $params[] = $excludeUserId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (role_id, full_name, email, password_hash, account_status)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['role_id'],
            $data['full_name'],
            $data['email'],
            $data['password_hash'],
            $data['account_status'] ?? 'active',
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE users SET full_name = ?, email = ?, role_id = ?, account_status = ? WHERE user_id = ?
        ");
        return $stmt->execute([
            $data['full_name'], $data['email'], $data['role_id'], $data['account_status'], $id,
        ]);
    }

    public function updatePassword(int $id, string $hash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET account_status = ? WHERE user_id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = ?");
        return $stmt->execute([$id]);
    }

    public function countByRole(int $roleId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE role_id = ?");
        $stmt->execute([$roleId]);
        return (int) $stmt->fetchColumn();
    }

    public function countRegularUsers(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM users WHERE role_id = 2")->fetchColumn();
    }
}
