<?php
/**
 * VIEW: auth/register.php
 * Chỉ nhận form → gọi AuthService → hiển thị kết quả
 */
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Đăng ký - Campus Booking';
$errors = [];
$old = [];
$authService = new AuthService();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = [
        'full_name' => trim($_POST['full_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'student_code' => trim($_POST['student_code'] ?? ''),
        'faculty' => trim($_POST['faculty'] ?? ''),
        'class_name' => trim($_POST['class_name'] ?? ''),
    ];

    $result = $authService->register($old, $_POST['password'] ?? '', $_POST['confirm_password'] ?? '');

    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    }
    $errors = $result['errors'] ?? [];
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="auth-container">
    <div class="auth-card">
        <h2>Đăng ký tài khoản</h2>
        <p class="subtitle">Tạo tài khoản mới để đặt facility trong campus</p>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error"><?= e($err) ?></div>
        <?php endforeach; ?>

        <form method="POST">
            <div class="form-group">
                <label for="full_name">Họ và tên *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="<?= e($old['full_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= e($old['email'] ?? '') ?>" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="password">Mật khẩu *</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu *</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label for="student_code">Mã sinh viên</label>
                <input type="text" id="student_code" name="student_code" class="form-control" value="<?= e($old['student_code'] ?? '') ?>">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="faculty">Khoa</label>
                    <input type="text" id="faculty" name="faculty" class="form-control" value="<?= e($old['faculty'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="class_name">Lớp</label>
                    <input type="text" id="class_name" name="class_name" class="form-control" value="<?= e($old['class_name'] ?? '') ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
        </form>
        <div class="auth-footer">
            Đã có tài khoản? <a href="/webfinal/auth/login.php">Đăng nhập</a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
