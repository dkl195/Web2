<?php
$pageTitle = 'Trang chủ - VNU-IS Booking';
require_once __DIR__ . '/includes/header.php';

$flash = getFlash();
if (isset($_GET['error']) && $_GET['error'] === 'access_denied') {
    echo '<div class="alert alert-error" style="max-width:1280px;margin:1rem auto;padding:0 2rem;">Access denied. Admin privileges required.</div>';
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
                        <a href="/webfinal/admin/dashboard.php" class="btn btn-yellow">Dashboard</a>
                    <?php else: ?>
                        <a href="/webfinal/user/facilities.php" class="btn btn-yellow">Xem Facilities</a>
                        <a href="/webfinal/user/my_bookings.php" class="btn btn-ghost">My Bookings</a>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/webfinal/auth/login.php" class="btn btn-yellow">Đăng nhập</a>
                    <a href="/webfinal/auth/register.php" class="btn btn-ghost">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="hero-image-right">
            <img src="/webfinal/assets/images/hero-right.png" alt="Campus Booking">
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
            <a href="/webfinal/user/facilities.php?category=2" class="feature-card">
                <div class="feature-card-img">
                    <img src="/webfinal/assets/images/lab.jpg" alt="Phòng học & Lab" onerror="this.style.display='none'">
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
            <a href="/webfinal/user/facilities.php?category=3" class="feature-card">
                <div class="feature-card-img">
                    <img src="/webfinal/assets/images/sports.jpg" alt="Sân thể thao" onerror="this.style.display='none'">
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
            <a href="/webfinal/user/facilities.php?category=4" class="feature-card">
                <div class="feature-card-img">
                    <img src="/webfinal/assets/images/meeting.jpg" alt="Phòng họp" onerror="this.style.display='none'">
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

<?php require_once __DIR__ . '/includes/footer.php'; ?>