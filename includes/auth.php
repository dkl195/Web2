<?php
/**
 * Entry point cũ — giữ lại để các file View không phải sửa đường dẫn.
 * Toàn bộ logic backend nằm trong thư mục /backend/
 */
require_once __DIR__ . '/../backend/bootstrap.php';

// Wrapper functions (backward compatible với code View cũ)
function isLoggedIn(): bool { return Auth::isLoggedIn(); }
function isAdmin(): bool { return Auth::isAdmin(); }
function requireLogin(): void { Auth::requireLogin(); }
function requireAdmin(): void { Auth::requireAdmin(); }
function redirectByRole(): void { Auth::redirectByRole(); }
function setFlash(string $type, string $message): void { Flash::set($type, $message); }
function getFlash(): ?array { return Flash::get(); }
