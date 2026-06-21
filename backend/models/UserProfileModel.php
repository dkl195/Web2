<?php
/** Model bảng: user_profiles (1 user - 1 profile) */
class UserProfileModel extends BaseModel
{
    public function findByUserId(int $userId): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $userId, string $studentCode, string $faculty, string $className): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO user_profiles (user_id, student_code, faculty, class_name) VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $studentCode, $faculty, $className]);
    }

    public function update(int $userId, string $studentCode, string $faculty, string $className): bool
    {
        $stmt = $this->db->prepare("
            UPDATE user_profiles SET student_code = ?, faculty = ?, class_name = ? WHERE user_id = ?
        ");
        return $stmt->execute([$studentCode, $faculty, $className, $userId]);
    }

    public function updateAvatar(int $userId, string $avatarPath): bool
    {
        $profile = $this->findByUserId($userId);
        if ($profile) {
            $stmt = $this->db->prepare("UPDATE user_profiles SET avatar = ? WHERE user_id = ?");
            return $stmt->execute([$avatarPath, $userId]);
        }

        $stmt = $this->db->prepare("
            INSERT INTO user_profiles (user_id, student_code, faculty, class_name, avatar)
            VALUES (?, '', '', '', ?)
        ");
        return $stmt->execute([$userId, $avatarPath]);
    }
}
