<?php
/**
 * BOOTSTRAP - File khởi tạo backend
 */

// Đảm bảo output UTF-8
if (!headers_sent()) {
    header('Content-Type: text/html; charset=UTF-8');
}
mb_internal_encoding('UTF-8');

// --- BASE_URL: detect từ SCRIPT_NAME, encode dấu cách ---
if (!defined('BASE_URL')) {
    $sn = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    if (preg_match('#^(.*?/Web2)(/|$)#i', $sn, $m)) {
        // Encode dấu cách thành %20 để dùng được trong href
        define('BASE_URL', str_replace(' ', '%20', $m[1]));
    } else {
        define('BASE_URL', '/web%202%20final/Web2');
    }
}

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
