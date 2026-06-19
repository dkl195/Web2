-- Chạy file này trong phpMyAdmin (database: campus_booking)
-- Xóa dữ liệu cũ trước để tránh trùng ID
DELETE FROM facility_rules WHERE facility_id IN (1,2);
DELETE FROM facilities WHERE facility_id IN (1,2);

-- ── Classroom ──────────────────────────────────────────
INSERT INTO facilities (facility_id, category_id, facility_name, location, capacity, open_time, close_time, facility_status, description) VALUES
(1,  1, 'Phòng B1-101',        'Tòa B1 – Tầng 1',           45, '07:00:00', '21:00:00', 'available',   'Phòng học lý thuyết tiêu chuẩn, máy chiếu, bảng trắng'),
(2,  1, 'Phòng B1-202',        'Tòa B1 – Tầng 2',           50, '07:00:00', '21:00:00', 'available',   'Phòng học rộng, hệ thống âm thanh, máy chiếu'),
(3,  1, 'Phòng B2-301',        'Tòa B2 – Tầng 3',           40, '07:00:00', '21:00:00', 'available',   'Phòng học nhỏ, phù hợp seminar và thảo luận nhóm'),
(4,  1, 'Phòng B2-405',        'Tòa B2 – Tầng 4',           60, '07:00:00', '21:00:00', 'unavailable', 'Hội trường nhỏ, có sân khấu, phục vụ sự kiện lớp học'),

-- ── Laboratory ─────────────────────────────────────────
(5,  2, 'Lab 301 – Máy tính',  'Tòa A – Tầng 3',            35, '08:00:00', '17:00:00', 'available',   'Lab máy tính 35 máy trạm, cài đủ phần mềm lập trình'),
(6,  2, 'Lab 302 – Mạng',      'Tòa A – Tầng 3',            30, '08:00:00', '17:00:00', 'available',   'Lab thực hành mạng máy tính, thiết bị Cisco'),
(7,  2, 'Lab 401 – AI/DS',     'Tòa A – Tầng 4',            25, '08:00:00', '17:00:00', 'available',   'Lab AI & Data Science, GPU workstation, phần mềm chuyên dụng'),
(8,  2, 'Lab 402 – Đa phương tiện', 'Tòa A – Tầng 4',       20, '08:00:00', '17:00:00', 'available',   'Lab thiết kế đồ họa, video editing, màn hình 4K'),

-- ── Sports Court ───────────────────────────────────────
(9,  3, 'Sân cầu lông A',      'Khu thể thao – Sân 1',      20, '06:00:00', '21:00:00', 'available',   'Sân cầu lông trong nhà, đủ ánh sáng, sàn gỗ'),
(10, 3, 'Sân cầu lông B',      'Khu thể thao – Sân 2',      20, '06:00:00', '21:00:00', 'available',   'Sân cầu lông trong nhà, đủ ánh sáng, sàn gỗ'),
(11, 3, 'Sân bóng rổ',         'Khu thể thao – Sân ngoài',  30, '06:00:00', '20:00:00', 'available',   'Sân bóng rổ ngoài trời tiêu chuẩn'),
(12, 3, 'Sân bóng đá mini',    'Khu thể thao – Sân cỏ',     50, '06:00:00', '20:00:00', 'available',   'Sân bóng đá mini cỏ nhân tạo 5 người'),

-- ── Meeting Room ───────────────────────────────────────
(13, 4, 'Phòng họp 101',       'Tòa C – Tầng 1',            12, '08:00:00', '18:00:00', 'available',   'Phòng họp nhỏ, bàn tròn, TV 65 inch, phù hợp họp nhóm'),
(14, 4, 'Phòng họp 201',       'Tòa C – Tầng 2',            20, '08:00:00', '18:00:00', 'available',   'Phòng họp vừa, máy chiếu, micro, phù hợp hội nghị'),
(15, 4, 'Phòng seminar A',     'Tòa C – Tầng 2',            40, '08:00:00', '20:00:00', 'available',   'Phòng seminar, sắp xếp linh hoạt, hệ thống âm thanh chuyên nghiệp'),
(16, 4, 'Phòng hội thảo',      'Tòa C – Tầng 3',            80, '08:00:00', '20:00:00', 'available',   'Phòng hội thảo lớn, phù hợp workshop, có livestream');

-- ── Rules ──────────────────────────────────────────────
INSERT INTO facility_rules (facility_id, max_booking_hours, min_notice_hours, requires_approval) VALUES
-- Classroom (yêu cầu duyệt, báo trước 24h)
(1,  4, 24, 1),
(2,  4, 24, 1),
(3,  3, 24, 1),
(4,  4, 24, 1),
-- Lab (yêu cầu duyệt, báo trước 24h)
(5,  3, 24, 1),
(6,  3, 24, 1),
(7,  4, 48, 1),
(8,  3, 24, 1),
-- Sports (sân cầu lông không cần duyệt, báo 2h; bóng rổ/bóng đá cần duyệt)
(9,  2, 2,  0),
(10, 2, 2,  0),
(11, 2, 2,  1),
(12, 2, 2,  1),
-- Meeting (yêu cầu duyệt, hội thảo lớn báo trước 48h)
(13, 3, 24, 1),
(14, 4, 24, 1),
(15, 4, 24, 1),
(16, 6, 48, 1);
