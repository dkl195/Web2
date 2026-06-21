<?php
$isAdminPage = str_contains($_SERVER['PHP_SELF'], '/admin/');
$baseUrl = '/web 2 final/Web2';
?>
<?php if ($isAdminPage && isAdmin()): ?>
</div><!-- .admin-main -->
</div><!-- .admin-layout -->
<?php else: ?>
</main>
<?php endif; ?>

<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="VNU-IS">
            <span class="footer-brand-text">VNU<span class="footer-brand-accent">-IS</span></span>
        </div>
        <div class="footer-text">
            Hệ thống Đặt Dịch vụ Campus &copy; <?= date('Y') ?><br>
            <span style="font-size:0.7rem;opacity:0.6;">Vietnam National University – Information Systems</span>
        </div>
    </div>
</footer>
</body>
</html>
