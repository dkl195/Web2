<?php
/**
 * Auth - Session & Phân quyền (Module 1)
 *
 * Session lưu gì sau khi login?
 *   $_SESSION['user_id']    → ID user trong bảng users
 *   $_SESSION['full_name']  → Tên hiển thị
 *   $_SESSION['role_name']  → 'ADMIN' hoặc 'USER'
 *
 * Business rules:
 *   - requireLogin()  → chưa login thì redirect về login (dùng trước mọi trang booking)
 *   - requireAdmin()  → user thường không vào được trang admin
 */
class Auth
{
    public static function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function isAdmin(): bool
    {
        return self::isLoggedIn() && ($_SESSION['role_name'] ?? '') === 'ADMIN';
    }

    public static function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function roleName(): ?string
    {
        return $_SESSION['role_name'] ?? null;
    }

    /** Tạo session sau khi login thành công */
    public static function login(int $userId, string $fullName, string $roleName, ?string $avatar = null): void
    {
        $_SESSION['user_id'] = $userId;
        $_SESSION['full_name'] = $fullName;
        $_SESSION['role_name'] = $roleName;
        $_SESSION['avatar'] = $avatar;
    }

    public static function setAvatar(?string $avatar): void
    {
        $_SESSION['avatar'] = $avatar;
    }

    /** Xóa session khi logout */
    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            header('Location: /webfinal/auth/login.php');
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: /webfinal/index.php?error=access_denied');
            exit;
        }
    }

    /** Redirect theo role sau login */
    public static function redirectByRole(): void
    {
        if (self::isAdmin()) {
            header('Location: /webfinal/admin/dashboard.php');
        } else {
            header('Location: /webfinal/user/facilities.php');
        }
        exit;
    }
}
