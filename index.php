<?php
$pageTitle = 'VNU-IS Campus Booking';
$baseUrl   = '/web 2 final/Web2';
require_once __DIR__ . '/includes/header.php';
$flash = getFlash();
?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
<div class="alert alert-error" style="position:fixed;top:calc(var(--nav-h)+1rem);left:50%;transform:translateX(-50%);z-index:999;min-width:360px;">
    ⛔ Access denied. Admin privileges required.
</div>
<?php endif; ?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['type']==='success'?'success':'error' ?>" style="position:fixed;top:calc(var(--nav-h)+1rem);left:50%;transform:translateX(-50%);z-index:999;min-width:360px;">
    <?= e($flash['message']) ?>
</div>
<?php endif; ?>

<!-- ═══════════════ HERO ═══════════════ -->
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-shape hero-shape-1"></div>
    <div class="hero-shape hero-shape-2"></div>

    <div class="hero-inner">
        <div class="hero-text">
            <div class="hero-label">VNU-IS Campus Booking System</div>
            <h1>Đặt <span class="highlight">không gian</span><br>của bạn</h1>
            <p class="hero-desc">Phòng học, lab máy tính, sân thể thao và phòng họp — đặt chỗ nhanh gọn, theo dõi dễ dàng ngay trong campus.</p>
            <div class="hero-btns">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?= $baseUrl ?>/admin/dashboard.php" class="btn btn-accent">Dashboard →</a>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>/user/facilities.php"  class="btn btn-accent">Xem Facilities →</a>
                        <a href="<?= $baseUrl ?>/user/my_bookings.php" class="btn btn-ghost">My Bookings</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/auth/login.php"    class="btn btn-accent">ĐẶT NGAY</a>
                    <a href="<?= $baseUrl ?>/auth/register.php" class="btn btn-ghost">Đăng ký miễn phí</a>
                <?php endif; ?>
            </div>
        </div>

        <div class="hero-panel">
            <div class="hero-stat">
                <div class="hero-stat-num">4+</div>
                <div class="hero-stat-lbl">Loại cơ sở</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">24/7</div>
                <div class="hero-stat-lbl">Hỗ trợ online</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">Fast</div>
                <div class="hero-stat-lbl">Xác nhận nhanh</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">Free</div>
                <div class="hero-stat-lbl">Miễn phí sử dụng</div>
            </div>
        </div>
    </div>

    <div class="hero-scroll">
        <div class="hero-scroll-line"></div>
        <span>Scroll</span>
    </div>
</section>

<!-- ═══════════════ SPLIT: Giới thiệu ═══════════════ -->
<div class="split-section">
    <div class="split-image">
        <img src="<?= $baseUrl ?>/assets/images/campus.jpg" alt="VNU-IS Campus" onerror="this.parentElement.style.background='linear-gradient(135deg,#0D3B7C 0%,#1A6AC4 100%)'">
        <div class="split-image-overlay"></div>
        <span class="split-badge">VNU — IS</span>
    </div>
    <div class="split-content">
        <div class="section-tag">Về chúng tôi</div>
        <h2 class="section-title">Hệ thống đặt<br>dịch vụ campus</h2>
        <p class="section-subtitle">VNU-IS Campus Booking giúp sinh viên, giảng viên dễ dàng tìm kiếm và đặt chỗ phòng học, lab, sân thể thao trong campus.</p>
        <div class="split-list">
            <div class="split-list-item">
                <div class="split-list-icon">🏛️</div>
                <div><strong>Đa dạng loại cơ sở</strong><br>Phòng học, lab máy tính, sân thể thao, phòng họp và nhiều hơn nữa.</div>
            </div>
            <div class="split-list-item">
                <div class="split-list-icon">⚡</div>
                <div><strong>Đặt chỗ tức thì</strong><br>Quy trình đặt chỗ đơn giản, xác nhận nhanh từ admin trong campus.</div>
            </div>
            <div class="split-list-item">
                <div class="split-list-icon">📱</div>
                <div><strong>Theo dõi dễ dàng</strong><br>Xem lịch sử booking, trạng thái và quản lý tất cả tại một nơi.</div>
            </div>
        </div>
        <div style="margin-top:2rem;">
            <a href="<?= $baseUrl ?>/auth/register.php" class="btn btn-primary">Tạo tài khoản →</a>
        </div>
    </div>
</div>

<!-- ═══════════════ SERVICES ═══════════════ -->
<section class="services-section">
    <div class="section-inner">
        <div class="section-tag">Dịch vụ</div>
        <h2 class="section-title">Khám phá các cơ sở</h2>
        <p class="section-subtitle">Tìm và đặt không gian phù hợp với nhu cầu học tập, nghiên cứu hoặc thể thao của bạn.</p>

        <div class="services-grid">
            <a href="<?= $baseUrl ?>/user/facilities.php?category=1" class="service-card" style="text-decoration:none;">
                <div class="service-icon">🏫</div>
                <h3>Phòng học</h3>
                <p>Phòng học lý thuyết cho các buổi học nhóm và seminar.</p>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=2" class="service-card" style="text-decoration:none;">
                <div class="service-icon">💻</div>
                <h3>Lab máy tính</h3>
                <p>Phòng lab được trang bị máy tính hiện đại cho thực hành.</p>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=3" class="service-card" style="text-decoration:none;">
                <div class="service-icon">⚽</div>
                <h3>Sân thể thao</h3>
                <p>Sân cầu lông, bóng đá, bóng rổ trong khuôn viên campus.</p>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=4" class="service-card" style="text-decoration:none;">
                <div class="service-icon">🤝</div>
                <h3>Phòng họp</h3>
                <p>Phòng meeting, hội thảo cho các nhóm nghiên cứu và dự án.</p>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════ STATS BANNER ═══════════════ -->
<div class="stats-banner">
    <div class="stats-banner-inner">
        <div class="stat-item">
            <div class="stat-num">100<span>+</span></div>
            <div class="stat-lbl">Cơ sở vật chất</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">500<span>+</span></div>
            <div class="stat-lbl">Sinh viên đăng ký</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">24<span>/7</span></div>
            <div class="stat-lbl">Hỗ trợ online</div>
        </div>
        <div class="stat-item">
            <div class="stat-num">4<span>+</span></div>
            <div class="stat-lbl">Loại dịch vụ</div>
        </div>
    </div>
</div>

<!-- ═══════════════ FEATURE CARDS ═══════════════ -->
<section class="section" style="background:var(--white);">
    <div class="section-inner">
        <div class="section-tag">Khám phá</div>
        <h2 class="section-title">Chọn loại không gian</h2>
        <p class="section-subtitle">Mỗi cơ sở được thiết kế phù hợp cho mục đích sử dụng riêng.</p>

        <div class="feature-grid">
            <a href="<?= $baseUrl ?>/user/facilities.php?category=2" class="feature-card">
                <div class="feature-card-bg" style="background:linear-gradient(135deg,#0D3B7C,#1565C0);"></div>
                <div class="feature-card-overlay"></div>
                <div class="feature-card-content">
                    <span class="feature-tag">Lab</span>
                    <h3>Phòng học & Lab</h3>
                    <p>Đặt phòng học, lab máy tính và phòng thí nghiệm cho nhóm học tập.</p>
                    <span class="feature-card-link">Xem ngay →</span>
                </div>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=3" class="feature-card">
                <div class="feature-card-bg" style="background:linear-gradient(135deg,#0a3880,#1976D2);"></div>
                <div class="feature-card-overlay"></div>
                <div class="feature-card-content">
                    <span class="feature-tag">Sports</span>
                    <h3>Sân thể thao</h3>
                    <p>Đặt sân cầu lông, sân bóng và khu vực thể thao trong campus.</p>
                    <span class="feature-card-link">Xem ngay →</span>
                </div>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=4" class="feature-card">
                <div class="feature-card-bg" style="background:linear-gradient(135deg,#071F45,#154BA0);"></div>
                <div class="feature-card-overlay"></div>
                <div class="feature-card-content">
                    <span class="feature-tag">Meeting</span>
                    <h3>Phòng họp</h3>
                    <p>Đặt phòng họp cho meeting, seminar và sự kiện nhóm.</p>
                    <span class="feature-card-link">Xem ngay →</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════ HOW IT WORKS ═══════════════ -->
<section class="section" style="background:var(--bg-section);">
    <div class="section-inner">
        <div class="section-tag">Quy trình</div>
        <h2 class="section-title">Đặt chỗ chỉ 3 bước</h2>

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2.5rem;margin-top:3.5rem;">
            <?php foreach ([
                ['🔍','1. Tìm cơ sở','Duyệt qua danh sách phòng, lab và sân thể thao trong campus.'],
                ['📅','2. Chọn thời gian','Chọn ngày, giờ bắt đầu và kết thúc phù hợp với lịch của bạn.'],
                ['✅','3. Nhận xác nhận','Gửi yêu cầu và nhận thông báo xác nhận từ admin nhanh chóng.'],
            ] as [$icon,$title,$desc]): ?>
            <div style="text-align:center;padding:2.5rem 2rem;background:var(--white);border-radius:var(--radius-lg);border:1px solid var(--border);box-shadow:var(--shadow-sm);">
                <div style="width:64px;height:64px;border-radius:50%;background:var(--sky);display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 1.25rem;">
                    <?= $icon ?>
                </div>
                <h3 style="font-size:1rem;font-weight:800;color:var(--text);margin-bottom:.5rem;"><?= $title ?></h3>
                <p style="font-size:.83rem;color:var(--text-muted);line-height:1.75;"><?= $desc ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:3rem;">
            <a href="<?= $baseUrl ?>/auth/login.php" class="btn btn-primary" style="padding:.8rem 2.5rem;font-size:.8rem;">
                BẮT ĐẦU NGAY →
            </a>
        </div>
    </div>
</section>

<!-- ═══════════════ SPLIT: CTA ═══════════════ -->
<div class="split-section">
    <div class="split-content dark" style="order:1;">
        <div class="section-tag" style="color:var(--accent-bright);">
            <span style="background:var(--accent-bright);"></span>Đặt ngay hôm nay
        </div>
        <h2 class="section-title" style="color:#fff;">Còn chờ gì nữa?</h2>
        <p class="section-subtitle" style="color:rgba(255,255,255,.65);">
            Tạo tài khoản miễn phí và bắt đầu đặt không gian trong campus của bạn ngay hôm nay.
        </p>
        <div style="margin-top:2.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
            <a href="<?= $baseUrl ?>/auth/register.php" class="btn btn-accent">Tạo tài khoản miễn phí</a>
            <a href="<?= $baseUrl ?>/auth/login.php"    class="btn btn-ghost">Đăng nhập</a>
        </div>
    </div>
    <div class="split-image" style="order:2;">
        <img src="<?= $baseUrl ?>/assets/images/cta-bg.jpg" alt="Campus" onerror="this.parentElement.style.background='linear-gradient(135deg,#071F45 0%,#154BA0 100%)'">
        <div class="split-image-overlay"></div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
