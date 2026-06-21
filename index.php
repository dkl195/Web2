<?php
$pageTitle = 'Trang chủ - VNU-IS Booking';
$baseUrl = '/web 2 final/Web2';
require_once __DIR__ . '/includes/header.php';

$flash = getFlash();
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    echo '<div class="alert alert-error" style="max-width:1280px;margin:1rem auto;padding:0 2rem;">⛔ Access denied. Admin privileges required.</div>';
}
if ($flash) {
    echo '<div class="alert alert-' . ($flash['type'] === 'success' ? 'success' : 'error') . '" style="max-width:1280px;margin:1rem auto;padding:0 2rem;">' . e($flash['message']) . '</div>';
}
?>

<!-- HERO -->
<div class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-text">
            <div class="hero-eyebrow">VNU-IS Campus Booking System</div>
            <h1>Đặt <em>không gian</em><br>của bạn</h1>
            <p class="hero-desc">Phòng học, lab máy tính, sân thể thao và phòng họp — đặt chỗ nhanh gọn, theo dõi dễ dàng ngay trong campus.</p>
            <div class="hero-actions">
                <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                        <a href="<?= $baseUrl ?>/admin/dashboard.php" class="btn btn-accent">📊 Dashboard</a>
                    <?php else: ?>
                        <a href="<?= $baseUrl ?>/user/facilities.php" class="btn btn-accent">🏛️ Xem Facilities</a>
                        <a href="<?= $baseUrl ?>/user/my_bookings.php" class="btn btn-ghost">📅 My Bookings</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/auth/login.php" class="btn btn-accent">Đăng nhập</a>
                    <a href="<?= $baseUrl ?>/auth/register.php" class="btn btn-ghost">Đăng ký miễn phí</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-panel">
            <div class="hero-stat">
                <div class="hero-stat-number">4+</div>
                <div class="hero-stat-label">Loại cơ sở</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number">24/7</div>
                <div class="hero-stat-label">Hỗ trợ online</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number">Fast</div>
                <div class="hero-stat-label">Xác nhận nhanh</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-number">Free</div>
                <div class="hero-stat-label">Miễn phí sử dụng</div>
            </div>
        </div>
    </div>
</div>

<!-- CATEGORY SECTION -->
<div class="category-section">
    <div class="category-section-inner">
        <div class="section-tag">Khám phá</div>
        <h2 class="category-heading">Chọn loại cơ sở</h2>
        <p class="category-sub">Tìm và đặt không gian phù hợp với nhu cầu học tập, nghiên cứu hoặc thể thao của bạn.</p>
        <div class="card-grid">
            <a href="<?= $baseUrl ?>/user/facilities.php?category=2" class="feature-card">
                <div class="feature-card-img">
                    <img src="<?= $baseUrl ?>/assets/images/lab.jpg" alt="Phòng học & Lab" onerror="this.style.display='none'">
                    <div class="feature-card-overlay">
                        <div class="icon">LAB</div>
                    </div>
                </div>
                <div class="feature-card-content">
                    <h3>Phòng học & Lab</h3>
                    <p>Đặt phòng học, lab máy tính và phòng thí nghiệm cho nhóm học tập.</p>
                    <span class="card-arrow">Xem ngay →</span>
                </div>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=3" class="feature-card">
                <div class="feature-card-img">
                    <img src="<?= $baseUrl ?>/assets/images/sports.jpg" alt="Sân thể thao" onerror="this.style.display='none'">
                    <div class="feature-card-overlay">
                        <div class="icon">SPT</div>
                    </div>
                </div>
                <div class="feature-card-content">
                    <h3>Sân thể thao</h3>
                    <p>Đặt sân cầu lông, sân bóng và khu vực thể thao trong campus.</p>
                    <span class="card-arrow">Xem ngay →</span>
                </div>
            </a>
            <a href="<?= $baseUrl ?>/user/facilities.php?category=4" class="feature-card">
                <div class="feature-card-img">
                    <img src="<?= $baseUrl ?>/assets/images/meeting.jpg" alt="Phòng họp" onerror="this.style.display='none'">
                    <div class="feature-card-overlay">
                        <div class="icon">MTG</div>
                    </div>
                </div>
                <div class="feature-card-content">
                    <h3>Phòng họp</h3>
                    <p>Đặt phòng họp cho meeting, seminar và sự kiện nhóm.</p>
                    <span class="card-arrow">Xem ngay →</span>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- HOW IT WORKS -->
<div style="padding:5rem 2.5rem; background:var(--bg);">
    <div style="max-width:1280px;margin:0 auto;">
        <div class="section-tag">Quy trình</div>
        <h2 class="category-heading">Đặt chỗ chỉ 3 bước</h2>
        <p class="category-sub">Nhanh chóng và đơn giản.</p>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;margin-top:2.5rem;">
            <div style="text-align:center;padding:2rem;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;">🔍</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem;color:var(--text);">1. Tìm cơ sở</h3>
                <p style="font-size:0.83rem;color:var(--text-muted);line-height:1.7;">Duyệt qua danh sách phòng, lab và sân thể thao trong campus.</p>
            </div>
            <div style="text-align:center;padding:2rem;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;">📅</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem;color:var(--text);">2. Chọn thời gian</h3>
                <p style="font-size:0.83rem;color:var(--text-muted);line-height:1.7;">Chọn ngày và giờ phù hợp với lịch của bạn.</p>
            </div>
            <div style="text-align:center;padding:2rem;">
                <div style="width:56px;height:56px;border-radius:50%;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.5rem;">✅</div>
                <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.5rem;color:var(--text);">3. Xác nhận</h3>
                <p style="font-size:0.83rem;color:var(--text-muted);line-height:1.7;">Gửi yêu cầu và nhận xác nhận từ admin nhanh chóng.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
