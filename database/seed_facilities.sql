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

-- ════════════════════════════════════════════════════════════
-- THÊM FACILITIES MỚI (ID 17 trở đi)
-- Chạy phần này SAU KHI đã có 16 facilities ở trên
-- ════════════════════════════════════════════════════════════

INSERT INTO facilities (facility_id, category_id, facility_name, location, capacity, open_time, close_time, facility_status, description) VALUES

-- ── Classroom (thêm) ───────────────────────────────────
(17, 1, 'Phòng B1-103',        'Tòa B1 – Tầng 1',           45, '07:00:00', '21:00:00', 'available',   'Phòng học lý thuyết, bảng thông minh tương tác, điều hòa'),
(18, 1, 'Phòng B1-205',        'Tòa B1 – Tầng 2',           50, '07:00:00', '21:00:00', 'available',   'Phòng học có hệ thống ghi âm bài giảng tự động'),
(19, 1, 'Phòng B2-302',        'Tòa B2 – Tầng 3',           40, '07:00:00', '21:00:00', 'available',   'Phòng học thiết kế theo kiểu lớp học đảo ngược (flipped classroom)'),
(20, 1, 'Phòng B2-303',        'Tòa B2 – Tầng 3',           35, '07:00:00', '21:00:00', 'available',   'Phòng học nhỏ, bàn ghế di động, phù hợp học nhóm linh hoạt'),
(21, 1, 'Phòng B3-101',        'Tòa B3 – Tầng 1',           55, '07:00:00', '21:00:00', 'available',   'Phòng học tiêu chuẩn, máy chiếu 2 màn hình, hệ thống điều hòa trung tâm'),
(22, 1, 'Phòng B3-201',        'Tòa B3 – Tầng 2',           60, '07:00:00', '21:00:00', 'available',   'Phòng học lớn, âm thanh hội trường, phù hợp báo cáo đồ án'),
(23, 1, 'Phòng B3-401',        'Tòa B3 – Tầng 4',           30, '07:00:00', '21:00:00', 'unavailable', 'Phòng học đang bảo trì, dự kiến mở lại sau 2 tuần'),

-- ── Laboratory (thêm) ──────────────────────────────────
(24, 2, 'Lab 101 – Lập trình cơ bản', 'Tòa A – Tầng 1',     40, '08:00:00', '18:00:00', 'available',   'Lab nhập môn lập trình, máy tính cấu hình cơ bản, phù hợp sinh viên năm 1'),
(25, 2, 'Lab 102 – Web Dev',   'Tòa A – Tầng 1',             35, '08:00:00', '18:00:00', 'available',   'Lab lập trình web, cài đầy đủ IDE, Node.js, PHP, MySQL, XAMPP'),
(26, 2, 'Lab 201 – Bảo mật',   'Tòa A – Tầng 2',            25, '08:00:00', '17:00:00', 'available',   'Lab an toàn thông tin, máy ảo Kali Linux, môi trường thực hành penetration testing'),
(27, 2, 'Lab 202 – IoT',       'Tòa A – Tầng 2',             20, '08:00:00', '17:00:00', 'available',   'Lab IoT & nhúng, Arduino, Raspberry Pi, cảm biến, thiết bị kết nối'),
(28, 2, 'Lab 303 – CSDL',      'Tòa A – Tầng 3',            30, '08:00:00', '17:00:00', 'available',   'Lab cơ sở dữ liệu, MySQL, PostgreSQL, Oracle, công cụ quản trị CSDL'),
(29, 2, 'Lab 403 – Cloud',     'Tòa A – Tầng 4',             20, '08:00:00', '17:00:00', 'available',   'Lab điện toán đám mây, thực hành AWS/Azure/GCP, Docker, Kubernetes'),

-- ── Sports Court (thêm) ────────────────────────────────
(30, 3, 'Sân cầu lông C',      'Khu thể thao – Sân 3',      20, '06:00:00', '21:00:00', 'available',   'Sân cầu lông trong nhà mới xây, sàn gỗ chuyên dụng, đèn LED'),
(31, 3, 'Sân cầu lông D',      'Khu thể thao – Sân 4',      20, '06:00:00', '21:00:00', 'available',   'Sân cầu lông trong nhà, gần khu vực thay đồ và tủ khóa'),
(32, 3, 'Sân bóng bàn',        'Nhà thi đấu – Tầng 1',      16, '07:00:00', '21:00:00', 'available',   '4 bàn bóng bàn tiêu chuẩn, hệ thống đèn chiếu sáng chuyên nghiệp'),
(33, 3, 'Sân tennis',          'Khu thể thao – Sân ngoài',  20, '06:00:00', '20:00:00', 'available',   'Sân tennis ngoài trời mặt cứng tiêu chuẩn, lưới mới, khu vực khán giả'),
(34, 3, 'Phòng gym',           'Nhà thi đấu – Tầng 2',      30, '06:00:00', '22:00:00', 'available',   'Phòng tập thể hình, máy cardio, tạ tự do, gương toàn thân'),
(35, 3, 'Phòng yoga & aerobic','Nhà thi đấu – Tầng 2',      25, '06:00:00', '21:00:00', 'available',   'Phòng tập yoga và aerobic, sàn gỗ mềm, gương, hệ thống âm thanh'),
(36, 3, 'Sân bóng chuyền',     'Khu thể thao – Sân ngoài',  24, '06:00:00', '20:00:00', 'available',   'Sân bóng chuyền ngoài trời cát biển, tiêu chuẩn thi đấu'),

-- ── Meeting Room (thêm) ────────────────────────────────
(37, 4, 'Phòng họp 102',       'Tòa C – Tầng 1',            10, '08:00:00', '18:00:00', 'available',   'Phòng họp nhỏ, TV 55 inch, phù hợp họp nhanh 5-10 người'),
(38, 4, 'Phòng họp 202',       'Tòa C – Tầng 2',            15, '08:00:00', '18:00:00', 'available',   'Phòng họp vừa, màn hình kép, hệ thống hội nghị video Zoom'),
(39, 4, 'Phòng seminar B',     'Tòa C – Tầng 3',            45, '08:00:00', '20:00:00', 'available',   'Phòng seminar lớn, bố trí rạp hát, màn chiếu 200 inch'),
(40, 4, 'Phòng đào tạo',       'Tòa D – Tầng 1',            35, '08:00:00', '20:00:00', 'available',   'Phòng đào tạo kỹ năng mềm, bảng flip chart, bàn nhóm 6 người'),
(41, 4, 'Phòng brainstorm',    'Tòa D – Tầng 1',            12, '08:00:00', '20:00:00', 'available',   'Phòng sáng tạo, tường viết được, bàn ghế đứng, phù hợp hackathon nhỏ'),
(42, 4, 'Phòng họp VIP',       'Tòa D – Tầng 3',            20, '08:00:00', '18:00:00', 'available',   'Phòng họp cao cấp, nội thất sang trọng, hệ thống hội nghị truyền hình'),
(43, 4, 'Hội trường lớn',      'Tòa E – Tầng 1',           200, '07:00:00', '22:00:00', 'available',   'Hội trường sức chứa 200 người, sân khấu, hệ thống âm thanh ánh sáng chuyên nghiệp, livestream 4K');

-- ── Rules cho facilities mới ───────────────────────────
INSERT INTO facility_rules (facility_id, max_booking_hours, min_notice_hours, requires_approval) VALUES
-- Classroom mới
(17, 4, 24, 1),
(18, 4, 24, 1),
(19, 3, 24, 1),
(20, 3, 24, 1),
(21, 4, 24, 1),
(22, 4, 24, 1),
(23, 4, 24, 1),
-- Lab mới
(24, 3, 24, 1),
(25, 3, 24, 1),
(26, 4, 48, 1),
(27, 3, 24, 1),
(28, 3, 24, 1),
(29, 4, 48, 1),
-- Sports mới
(30, 2, 2,  0),
(31, 2, 2,  0),
(32, 2, 2,  0),
(33, 2, 4,  1),
(34, 2, 2,  0),
(35, 2, 2,  0),
(36, 2, 2,  1),
-- Meeting mới
(37, 3, 24, 1),
(38, 4, 24, 1),
(39, 4, 24, 1),
(40, 4, 24, 1),
(41, 3, 24, 1),
(42, 4, 48, 1),
(43, 8, 72, 1);
