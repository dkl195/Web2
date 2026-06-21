-- Chạy file này nếu database đã tồn tại trước khi có tính năng avatar
USE campus_booking;

ALTER TABLE user_profiles
    ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER class_name;
