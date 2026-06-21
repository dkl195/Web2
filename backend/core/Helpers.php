<?php
/** Helpers - hàm tiện ích dùng chung (format, escape HTML) */

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function formatTime(?string $time): string
{
    if (!$time) return '';
    return substr($time, 0, 5);
}

function formatDate(?string $date): string
{
    if (!$date) return '';
    return date('d/m/Y', strtotime($date));
}

function statusBadgeClass(string $code): string
{
    return match ($code) {
        'PENDING' => 'badge-pending',
        'APPROVED' => 'badge-approved',
        'REJECTED' => 'badge-rejected',
        'CANCELLED' => 'badge-cancelled',
        default => 'badge-default',
    };
}

function avatarUrl(?string $path): string
{
    if ($path) {
        return '/webfinal/' . ltrim($path, '/');
    }
    return '/webfinal/assets/images/default-avatar.svg';
}

function currentAvatarUrl(): string
{
    return avatarUrl($_SESSION['avatar'] ?? null);
}
