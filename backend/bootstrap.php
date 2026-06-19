<?php
/**
 * =============================================================================
 * BOOTSTRAP - File khởi tạo backend
 * =============================================================================
 * Mọi trang PHP đều require file này (qua includes/auth.php).
 *
 * Kiến trúc 3 tầng:
 *   1. MODEL    → Chỉ làm việc với database (SELECT, INSERT, UPDATE, DELETE)
 *   2. SERVICE  → Business logic (validate, transaction, quy tắc nghiệp vụ)
 *   3. VIEW     → Các file auth/*.php, user/*.php, admin/*.php (chỉ hiển thị UI)
 *
 * Luồng xử lý request:
 *   User bấm Submit → View nhận $_POST → gọi Service → Service gọi Model → trả kết quả
 * =============================================================================
 */

require_once __DIR__ . '/../config/database.php';

// --- Core (hạ tầng) ---
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Flash.php';
require_once __DIR__ . '/core/Helpers.php';

// --- Models (tầng dữ liệu - 1 model / 1 bảng) ---
require_once __DIR__ . '/models/BaseModel.php';
require_once __DIR__ . '/models/RoleModel.php';
require_once __DIR__ . '/models/UserModel.php';
require_once __DIR__ . '/models/UserProfileModel.php';
require_once __DIR__ . '/models/FacilityCategoryModel.php';
require_once __DIR__ . '/models/FacilityModel.php';
require_once __DIR__ . '/models/FacilityRuleModel.php';
require_once __DIR__ . '/models/BookingStatusModel.php';
require_once __DIR__ . '/models/BookingModel.php';
require_once __DIR__ . '/models/BookingDetailModel.php';

// --- Validators (kiểm tra dữ liệu đầu vào) ---
require_once __DIR__ . '/validators/AuthValidator.php';
require_once __DIR__ . '/validators/FacilityValidator.php';
require_once __DIR__ . '/validators/BookingValidator.php';

// --- Services (business logic - phần quan trọng nhất khi defense) ---
require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/services/FacilityService.php';
require_once __DIR__ . '/services/BookingService.php';

// Khởi tạo session cho toàn hệ thống
Auth::initSession();
