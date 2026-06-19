<?php
require_once __DIR__ . '/../includes/auth.php';

$pageTitle = 'Facilities - Campus Booking';
$facilityService = new FacilityService();
$categoryFilter = isset($_GET['category']) ? (int) $_GET['category'] : 0;

$facilities = $facilityService->getAvailableFacilities($categoryFilter ?: null);
$categories = $facilityService->getCategoriesSimple();

require_once __DIR__ . '/../includes/header.php';
?>

<p class="page-title">Facilities</p>
<p class="page-subtitle">Chọn phòng, lab hoặc sân bạn muốn đặt</p>

<form method="GET" class="filter-bar">
    <div class="form-group">
        <label>Lọc theo loại</label>
        <select name="category" class="form-control" onchange="this.form.submit()">
            <option value="0">Tất cả loại</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['category_id'] ?>" <?= $categoryFilter == $c['category_id'] ? 'selected' : '' ?>><?= e($c['category_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</form>

<?php if (empty($facilities)): ?>
    <div class="empty-state">
        <p>Không có facility nào available.</p>
    </div>
<?php else: ?>
    <div class="facility-grid">
        <?php foreach ($facilities as $f): ?>
            <div class="facility-card">
                <div class="facility-card-img">
                    <span class="cat-label"><?= e($f['category_name']) ?></span>
                </div>
                <div class="facility-card-body">
                    <h3><?= e($f['facility_name']) ?></h3>
                    <div class="meta">
                        <div class="meta-item">
                            <i class="meta-icon">📍</i><?= e($f['location']) ?>
                        </div>
                        <div class="meta-item">
                            <i class="meta-icon">👥</i>Capacity: <?= $f['capacity'] ?> người
                        </div>
                        <div class="meta-item">
                            <i class="meta-icon">🕐</i><?= formatTime($f['open_time']) ?> – <?= formatTime($f['close_time']) ?>
                        </div>
                    </div>
                </div>
                <div class="facility-card-footer">
                    <span class="availability-dot">Available</span>
                    <a href="/webfinal/user/facility_detail.php?id=<?= $f['facility_id'] ?>" class="btn btn-primary btn-sm">Xem chi tiết</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
