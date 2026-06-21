<?php
/**
 * VIEW: auth/login.php
 * Backend flow → AuthService::login()
 */
require_once __DIR__ . '/../includes/auth.php';

if (isLoggedIn()) {
    redirectByRole();
}

$pageTitle = 'Đăng nhập - Campus Booking';
$error = '';
$authService = new AuthService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $authService->login(
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? ''
    );

    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    }
    $error = $result['error'] ?? 'Login failed.';
}

$baseUrl = '/web 2 final/Web2';
require_once __DIR__ . '/../includes/header.php';
$flash = getFlash();
?>

<div class="auth-page">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <img src="<?= $baseUrl ?>/assets/images/logo.png" alt="VNU-IS Logo">
            </div>
            <h2>Đăng nhập</h2>
            <p class="subtitle">Đăng nhập để đặt và quản lý booking</p>

            <?php if ($flash): ?>
                <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'error' ?>"><?= e($flash['message']) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@example.com" value="<?= e($_POST['email'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block" style="margin-top:0.5rem;">Đăng nhập</button>
            </form>
            <div class="auth-footer">
                Chưa có tài khoản? <a href="<?= $baseUrl ?>/auth/register.php">Đăng ký ngay</a>
            </div>
            <div class="auth-hint">Demo: admin@campus.com / password</div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
