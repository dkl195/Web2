<?php
/**
 * Database - Singleton kết nối PDO
 *
 * Tại sao dùng Singleton?
 * - Mỗi request chỉ cần 1 kết nối MySQL
 * - Tránh mở nhiều connection không cần thiết
 */
class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::$connection = getDB(); // từ config/database.php
        }
        return self::$connection;
    }

    /** Bắt đầu transaction khi cần ghi nhiều bảng cùng lúc (vd: register = users + user_profiles) */
    public static function beginTransaction(): void
    {
        self::getConnection()->beginTransaction();
    }

    public static function commit(): void
    {
        self::getConnection()->commit();
    }

    public static function rollBack(): void
    {
        self::getConnection()->rollBack();
    }
}
