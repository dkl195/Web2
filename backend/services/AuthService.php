<?php
/**
 * AuthService - Business logic Module 1: User & Authentication
 *
 * Flow Register:
 *   1. Validate input (AuthValidator)
 *   2. Check email chưa tồn tại (UserModel)
 *   3. Hash password — KHÔNG lưu mật khẩu thô
 *   4. INSERT users (role_id = 2 = USER)
 *   5. INSERT user_profiles
 *
 * Flow Login:
 *   1. Tìm user theo email
 *   2. password_verify()
 *   3. Check account_status = active
 *   4. Tạo session (Auth::login)
 */
class AuthService
{
    private UserModel $userModel;
    private UserProfileModel $profileModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->profileModel = new UserProfileModel();
    }

    /**
     * Đăng ký tài khoản mới
     * @return array{success: bool, errors?: string[], redirect?: string}
     */
    public function register(array $input, string $password, string $confirm): array
    {
        $errors = AuthValidator::validateRegister($input, $password, $confirm);
        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Business rule: email không được trùng
        if ($this->userModel->emailExists($input['email'])) {
            return ['success' => false, 'errors' => ['Email already exists.']];
        }

        Database::beginTransaction();
        try {
            // Hash password bằng bcrypt (PASSWORD_DEFAULT)
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $userId = $this->userModel->create([
                'role_id' => 2, // USER role
                'full_name' => $input['full_name'],
                'email' => $input['email'],
                'password_hash' => $hash,
                'account_status' => 'active',
            ]);

            $this->profileModel->create(
                $userId,
                $input['student_code'] ?? '',
                $input['faculty'] ?? '',
                $input['class_name'] ?? ''
            );

            Database::commit();
            Flash::success('Registration successful! Please login.');
            return ['success' => true, 'redirect' => '/webfinal/auth/login.php'];
        } catch (Exception $e) {
            Database::rollBack();
            return ['success' => false, 'errors' => ['Registration failed. Please try again.']];
        }
    }

    /**
     * Đăng nhập
     * @return array{success: bool, error?: string, redirect?: string}
     */
    public function login(string $email, string $password): array
    {
        $validationError = AuthValidator::validateLogin($email, $password);
        if ($validationError) {
            return ['success' => false, 'error' => $validationError];
        }

        $user = $this->userModel->findByEmailWithRole($email);

        // Không tiết lộ email có tồn tại hay không — báo chung
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // Business rule: account inactive không login được
        if ($user['account_status'] !== 'active') {
            return ['success' => false, 'error' => 'Your account is inactive. Please contact admin.'];
        }

        // Tạo session
        Auth::login(
            (int) $user['user_id'],
            $user['full_name'],
            $user['role_name'],
            $user['avatar'] ?? null
        );

        return ['success' => true, 'redirect' => Auth::isAdmin()
            ? '/webfinal/admin/dashboard.php'
            : '/webfinal/user/facilities.php'
        ];
    }
}
