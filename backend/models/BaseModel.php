<?php
/**
 * BaseModel - Lớp cha cho tất cả Model
 *
 * Quy ước:
 * - Model KHÔNG chứa business logic (không validate, không check quyền)
 * - Model CHỈ thực hiện truy vấn SQL thuần
 * - Mỗi Model tương ứng 1 bảng trong database
 */
abstract class BaseModel
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}
