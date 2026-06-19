<?php
/**
 * UserService - Business logic quản lý user & profile (Module 1)
 *
 * Phân quyền:
 *   - User chỉ sửa profile của mình (check user_id = session)
 *   - Admin quản lý toàn bộ user (CRUD, deactivate)
 */
class UserService
{
    private UserModel $userModel;
    private UserProfileModel $profileModel;
    private BookingModel $bookingModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profileModel = new UserProfileModel();
        $this->bookingModel = new BookingModel();
    }

    public function getAllUsers(): array
    {
        return $this->userModel->findAllWithProfile();
    }

    public function getUserWithProfile(int $userId): ?array
    {
        return $this->userModel->findWithProfile($userId);
    }

    /** User cập nhật profile của chính mình */
    public function updateOwnProfile(int $userId, array $data): bool
    {
        $profile = $this->profileModel->findByUserId($userId);
        if ($profile) {
            return $this->profileModel->update($userId, $data['student_code'], $data['faculty'], $data['class_name']);
        }
        return $this->profileModel->create($userId, $data['student_code'], $data['faculty'], $data['class_name']);
    }

    /** Admin tạo / sửa user */
    public function saveUser(?int $userId, array $data, string $password = ''): array
    {
        $errors = [];
        if ($data['full_name'] === '') $errors[] = 'Full name is required.';
        if ($data['email'] === '') $errors[] = 'Email is required.';

        if ($this->userModel->emailExists($data['email'], $userId)) {
            $errors[] = 'Email already exists.';
        }

        if (!$userId && strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        if ($userId) {
            $this->userModel->update($userId, $data);
            if ($password !== '') {
                $this->userModel->updatePassword($userId, password_hash($password, PASSWORD_DEFAULT));
            }
            $this->updateOrCreateProfile($userId, $data);
            return ['success' => true, 'message' => 'User updated successfully.'];
        }

        $newId = $this->userModel->create([
            'role_id' => $data['role_id'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'account_status' => $data['account_status'],
        ]);
        $this->profileModel->create($newId, $data['student_code'] ?? '', $data['faculty'] ?? '', $data['class_name'] ?? '');
        return ['success' => true, 'message' => 'User created successfully.'];
    }

    private function updateOrCreateProfile(int $userId, array $data): void
    {
        if ($this->profileModel->findByUserId($userId)) {
            $this->profileModel->update($userId, $data['student_code'] ?? '', $data['faculty'] ?? '', $data['class_name'] ?? '');
        } else {
            $this->profileModel->create($userId, $data['student_code'] ?? '', $data['faculty'] ?? '', $data['class_name'] ?? '');
        }
    }

    /** Deactivate thay vì xóa khi user đã có booking */
    public function deactivate(int $userId): void
    {
        $this->userModel->updateStatus($userId, 'inactive');
    }

    public function activate(int $userId): void
    {
        $this->userModel->updateStatus($userId, 'active');
    }

    public function deleteUser(int $userId): array
    {
        if ($this->bookingModel->countByUser($userId) > 0) {
            return ['success' => false, 'message' => 'Cannot delete user with existing bookings. Deactivate instead.'];
        }
        $this->userModel->delete($userId);
        return ['success' => true, 'message' => 'User deleted successfully.'];
    }

    public function countRegularUsers(): int
    {
        return $this->userModel->countRegularUsers();
    }
}
