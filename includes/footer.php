<?php
$isAdminPage = str_contains($_SERVER['PHP_SELF'], '/admin/');
$isHomePage  = (basename($_SERVER['PHP_SELF'], '.php') === 'index');
$baseUrl     = '/web 2 final/Web2';
?>
<?php if ($isAdminPage && isAdmin()): ?>
    </div><!-- admin-main -->
</div><!-- admin-layout -->
<?php elseif (!$isHomePage): ?>
</main>
<?php endif; ?>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand-wrap">
            <div class="footer-brand">
                <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="VNU-IS">
                <span class="footer-brand-name">VNU<span class="footer-brand-accent">-IS</span></span>
            </div>
            <p class="footer-desc">Hệ thống đặt dịch vụ campus của Đại học Quốc gia Hà Nội — Trường Thông tin và Truyền thông.</p>
        </div>
        <div>
            <p class="footer-col-title">Dịch vụ</p>
            <nav class="footer-links">
                <a href="<?= $baseUrl ?>/user/facilities.php">Phòng học & Lab</a>
                <a href="<?= $baseUrl ?>/user/facilities.php">Sân thể thao</a>
                <a href="<?= $baseUrl ?>/user/facilities.php">Phòng họp</a>
                <a href="<?= $baseUrl ?>/user/my_bookings.php">My Bookings</a>
            </nav>
        </div>
        <div>
            <p class="footer-col-title">Tài khoản</p>
            <nav class="footer-links">
                <a href="<?= $baseUrl ?>/auth/login.php">Đăng nhập</a>
                <a href="<?= $baseUrl ?>/auth/register.php">Đăng ký</a>
                <a href="<?= $baseUrl ?>/user/profile.php">Hồ sơ cá nhân</a>
            </nav>
        </div>
        <div>
            <p class="footer-col-title">Liên hệ</p>
            <nav class="footer-links">
                <a href="#">144 Xuân Thủy, Cầu Giấy, HN</a>
                <a href="tel:024000000">024 xxx xxxx</a>
                <a href="mailto:info@vnu.edu.vn">info@vnu.edu.vn</a>
            </nav>
        </div>
    </div>
    <div class="footer-bottom">
        <p class="footer-copy">&copy; <?= date('Y') ?> <strong>VNU-IS</strong> — Vietnam National University, Information Systems</p>
        <p class="footer-copy">Campus Booking System</p>
    </div>
</footer>
</body>
</html>
