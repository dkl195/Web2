-- Campus Booking System Database
-- Run this in phpMyAdmin or MySQL CLI on XAMPP

CREATE DATABASE IF NOT EXISTS campus_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE campus_booking;

-- Module 1: User & Authentication
CREATE TABLE roles (
    role_id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL
);

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    role_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    account_status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(role_id)
);

CREATE TABLE user_profiles (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    student_code VARCHAR(50) DEFAULT NULL,
    faculty VARCHAR(100) DEFAULT NULL,
    class_name VARCHAR(100) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Module 2: Facility Management
CREATE TABLE facility_categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL
);

CREATE TABLE facilities (
    facility_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    facility_name VARCHAR(150) NOT NULL,
    location VARCHAR(200) NOT NULL,
    capacity INT NOT NULL,
    open_time TIME NOT NULL,
    close_time TIME NOT NULL,
    facility_status ENUM('available', 'unavailable') NOT NULL DEFAULT 'available',
    description TEXT DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES facility_categories(category_id),
    UNIQUE KEY unique_facility_location (facility_name, location)
);

CREATE TABLE facility_rules (
    rule_id INT AUTO_INCREMENT PRIMARY KEY,
    facility_id INT NOT NULL UNIQUE,
    max_booking_hours INT NOT NULL DEFAULT 3,
    min_notice_hours INT NOT NULL DEFAULT 24,
    requires_approval TINYINT(1) NOT NULL DEFAULT 1,
    FOREIGN KEY (facility_id) REFERENCES facilities(facility_id) ON DELETE CASCADE
);

-- Module 3: Booking Management
CREATE TABLE booking_statuses (
    status_id INT AUTO_INCREMENT PRIMARY KEY,
    status_code VARCHAR(30) NOT NULL UNIQUE,
    status_name VARCHAR(50) NOT NULL
);

CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_code VARCHAR(30) NOT NULL UNIQUE,
    user_id INT NOT NULL,
    status_id INT NOT NULL,
    purpose VARCHAR(255) NOT NULL,
    participant_count INT NOT NULL,
    admin_note TEXT DEFAULT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (status_id) REFERENCES booking_statuses(status_id)
);

CREATE TABLE booking_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    facility_id INT NOT NULL,
    booking_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (facility_id) REFERENCES facilities(facility_id)
);

-- Sample Data
INSERT INTO roles (role_id, role_name, description) VALUES
(1, 'ADMIN', 'System administrator'),
(2, 'USER', 'Regular user / student');

INSERT INTO users (user_id, role_id, full_name, email, password_hash, account_status) VALUES
(1, 1, 'System Admin', 'admin@campus.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active'),
(2, 2, 'Linh', 'linh@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'active');

-- Default password for sample accounts: password

INSERT INTO user_profiles (profile_id, user_id, student_code, faculty, class_name) VALUES
(1, 2, '22070006', 'Information Systems', 'QH2022');

INSERT INTO facility_categories (category_id, category_name, description) VALUES
(1, 'Classroom', 'Standard classroom'),
(2, 'Laboratory', 'Computer and science labs'),
(3, 'Sports Court', 'Indoor and outdoor sports courts'),
(4, 'Meeting Room', 'Meeting and conference rooms');

INSERT INTO facilities (facility_id, category_id, facility_name, location, capacity, open_time, close_time, facility_status, description) VALUES
-- Classroom
(1,  1, 'Phòng B1-101', 'Tòa B1 – Tầng 1', 45, '07:00:00', '21:00:00', 'available', 'Phòng học lý thuyết tiêu chuẩn, máy chiếu, bảng trắng'),
(2,  1, 'Phòng B1-202', 'Tòa B1 – Tầng 2', 50, '07:00:00', '21:00:00', 'available', 'Phòng học rộng, hệ thống âm thanh, máy chiếu'),
(3,  1, 'Phòng B2-301', 'Tòa B2 – Tầng 3', 40, '07:00:00', '21:00:00', 'available', 'Phòng học nhỏ, phù hợp seminar và thảo luận nhóm'),
(4,  1, 'Phòng B2-405', 'Tòa B2 – Tầng 4', 60, '07:00:00', '21:00:00', 'unavailable', 'Hội trường nhỏ, có sân khấu, phục vụ sự kiện lớp học'),
-- Laboratory
(5,  2, 'Lab 301 – Máy tính', 'Tòa A – Tầng 3', 35, '08:00:00', '17:00:00', 'available', 'Lab máy tính 35 máy trạm, cài đủ phần mềm lập trình'),
(6,  2, 'Lab 302 – Mạng',    'Tòa A – Tầng 3', 30, '08:00:00', '17:00:00', 'available', 'Lab thực hành mạng máy tính, thiết bị Cisco'),
(7,  2, 'Lab 401 – AI/DS',   'Tòa A – Tầng 4', 25, '08:00:00', '17:00:00', 'available', 'Lab AI & Data Science, GPU workstation, phần mềm chuyên dụng'),
(8,  2, 'Lab 402 – Đa phương tiện', 'Tòa A – Tầng 4', 20, '08:00:00', '17:00:00', 'available', 'Lab thiết kế đồ họa, video editing, màn hình 4K'),
-- Sports Court
(9,  3, 'Sân cầu lông A',  'Khu thể thao – Sân 1', 20, '06:00:00', '21:00:00', 'available', 'Sân cầu lông trong nhà, đủ ánh sáng, sàn gỗ'),
(10, 3, 'Sân cầu lông B',  'Khu thể thao – Sân 2', 20, '06:00:00', '21:00:00', 'available', 'Sân cầu lông trong nhà, đủ ánh sáng, sàn gỗ'),
(11, 3, 'Sân bóng rổ',     'Khu thể thao – Sân ngoài', 30, '06:00:00', '20:00:00', 'available', 'Sân bóng rổ ngoài trời tiêu chuẩn'),
(12, 3, 'Sân bóng đá mini', 'Khu thể thao – Sân cỏ', 50, '06:00:00', '20:00:00', 'available', 'Sân bóng đá mini cỏ nhân tạo 5 người'),
-- Meeting Room
(13, 4, 'Phòng họp 101',   'Tòa C – Tầng 1', 12, '08:00:00', '18:00:00', 'available', 'Phòng họp nhỏ, bàn tròn, TV 65 inch, phù hợp họp nhóm'),
(14, 4, 'Phòng họp 201',   'Tòa C – Tầng 2', 20, '08:00:00', '18:00:00', 'available', 'Phòng họp vừa, máy chiếu, micro, phù hợp hội nghị'),
(15, 4, 'Phòng seminar A', 'Tòa C – Tầng 2', 40, '08:00:00', '20:00:00', 'available', 'Phòng seminar, sắp xếp linh hoạt, hệ thống âm thanh chuyên nghiệp'),
(16, 4, 'Phòng hội thảo',  'Tòa C – Tầng 3', 80, '08:00:00', '20:00:00', 'available', 'Phòng hội thảo lớn, phù hợp workshop, có livestream');

INSERT INTO facility_rules (rule_id, facility_id, max_booking_hours, min_notice_hours, requires_approval) VALUES
-- Classroom
(1,  1, 4, 24, 1),
(2,  2, 4, 24, 1),
(3,  3, 3, 24, 1),
(4,  4, 4, 24, 1),
-- Laboratory
(5,  5, 3, 24, 1),
(6,  6, 3, 24, 1),
(7,  7, 4, 48, 1),
(8,  8, 3, 24, 1),
-- Sports
(9,  9,  2, 2, 0),
(10, 10, 2, 2, 0),
(11, 11, 2, 2, 1),
(12, 12, 2, 2, 1),
-- Meeting
(13, 13, 3, 24, 1),
(14, 14, 4, 24, 1),
(15, 15, 4, 24, 1),
(16, 16, 6, 48, 1);

INSERT INTO booking_statuses (status_id, status_code, status_name) VALUES
(1, 'PENDING', 'Pending'),
(2, 'APPROVED', 'Approved'),
(3, 'REJECTED', 'Rejected'),
(4, 'CANCELLED', 'Cancelled');
