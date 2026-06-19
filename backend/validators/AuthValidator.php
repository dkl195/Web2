<?php
/**
 * AuthValidator - Kiểm tra dữ liệu đăng ký / đăng nhập (Module 1)
 */
class AuthValidator
{
    /** Validate form đăng ký */
    public static function validateRegister(array $input, string $password, string $confirm): array
    {
        $errors = [];

        if (trim($input['full_name'] ?? '') === '') {
            $errors[] = 'Full name is required.';
        }

        $email = trim($input['email'] ?? '');
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        // Rule: password tối thiểu 6 ký tự
        if (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($password !== $confirm) {
            $errors[] = 'Passwords do not match.';
        }

        return $errors;
    }

    /** Validate form đăng nhập */
    public static function validateLogin(string $email, string $password): ?string
    {
        if ($email === '' || $password === '') {
            return 'Please enter email and password.';
        }
        return null;
    }
}
