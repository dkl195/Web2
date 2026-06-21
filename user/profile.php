<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();

$pageTitle = 'Hồ sơ cá nhân - Campus Booking';
$userService = new UserService();
$userId = Auth::userId();
$success = '';
$errors = [];

$user = $userService->getUserWithProfile($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $userService->updateOwnProfile($userId, [
        'student_code' => trim($_POST['student_code'] ?? ''),
        'faculty' => trim($_POST['faculty'] ?? ''),
        'class_name' => trim($_POST['class_name'] ?? ''),
    ], $_FILES['avatar'] ?? null);

    if ($result['success']) {
        $success = $result['message'];
    } else {
        $errors = $result['errors'] ?? ['Unable to update profile.'];
    }

    $user = $userService->getUserWithProfile($userId);
}

require_once __DIR__ . '/../includes/header.php';
?>

<p class="page-title">Profile</p>
<p class="page-subtitle">Thông tin tài khoản và hồ sơ sinh viên</p>

<div class="card">
    <div class="card-header">
        <h2>Thông tin cá nhân</h2>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <?php foreach ($errors as $error): ?>
                <div><?= e($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="profile-top">
        <div class="profile-avatar-wrap">
            <img src="<?= e(avatarUrl($user['avatar'] ?? null)) ?>" alt="Avatar" class="profile-avatar">
        </div>
        <ul class="detail-list profile-detail-list">
            <li><span class="label">Họ tên</span><span class="value"><?= e($user['full_name']) ?></span></li>
            <li><span class="label">Email</span><span class="value"><?= e($user['email']) ?></span></li>
            <li><span class="label">Role</span><span class="value"><?= e(Auth::roleName()) ?></span></li>
            <li><span class="label">Trạng thái</span><span class="value"><span class="badge badge-<?= $user['account_status'] ?>"><?= e($user['account_status']) ?></span></span></li>
        </ul>
    </div>

    <h3 class="section-label">Cập nhật thông tin</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="avatar">Ảnh đại diện</label>
            <input type="file" id="avatar" name="avatar" class="form-control" accept="image/jpeg,image/png,image/webp">
            <small class="form-hint">JPG, PNG hoặc WEBP. Tối đa 2MB.</small>
        </div>
        <div class="form-group">
            <label for="student_code">Mã sinh viên</label>
            <input type="text" id="student_code" name="student_code" class="form-control" value="<?= e($user['student_code'] ?? '') ?>">
        </div>
        <div class="form-row">
            <div class="form-group">
                <label for="faculty">Khoa</label>
                <input type="text" id="faculty" name="faculty" class="form-control" value="<?= e($user['faculty'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label for="class_name">Lớp</label>
                <input type="text" id="class_name" name="class_name" class="form-control" value="<?= e($user['class_name'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật Profile</button>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
