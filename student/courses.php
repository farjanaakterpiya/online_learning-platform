<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$q = trim($_GET['q'] ?? '');
$cat = (int) ($_GET['category'] ?? 0);

$sql = "SELECT c.id, c.title, c.short_description, c.price, c.level, cat.name AS category,
               COALESCE(NULLIF(u.name,''), u.email) AS instructor,
               (SELECT COUNT(*) FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE s.course_id=c.id) AS lessons,
               (SELECT AVG(r.rating) FROM course_reviews r JOIN enrollments e ON e.id=r.enrollment_id WHERE e.course_id=c.id) AS rating,
               (SELECT status FROM enrollments e WHERE e.course_id=c.id AND e.student_id=?) AS my_status
        FROM courses c
        JOIN categories cat ON cat.id=c.category_id
        JOIN users u ON u.id=c.instructor_id
        WHERE c.status='published'";
$types = 'i';
$params = [$me['id']];
if ($q !== '') {
    $sql .= " AND (c.title LIKE ? OR c.short_description LIKE ?)";
    $like = '%' . $q . '%';
    $types .= 'ss';
    $params[] = $like;
    $params[] = $like;
}
if ($cat > 0) {
    $sql .= " AND c.category_id=?";
    $types .= 'i';
    $params[] = $cat;
}
$sql .= " ORDER BY c.created_at DESC";
$courses = db_all($sql, $types, $params);
$categories = db_all("SELECT id, name FROM categories WHERE status='active' ORDER BY name");

portal_start('Browse courses');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">CATALOG</span>
        <h1>Browse courses</h1>
        <p>Find a course that matches your goals.</p>
    </div>
    <a class="btn btn-light" href="my_courses.php">My courses</a>
</section>
<?php flash_show(); ?>
<form class="toolbar" method="get">
    <input class="form-control" type="search" name="q" placeholder="Search courses..." value="<?php echo e($q); ?>">
    <select class="form-select" name="category">
        <option value="0">All categories</option>
        <?php foreach ($categories as $c): ?>
            <option value="<?php echo (int) $c['id']; ?>" <?php echo $cat === (int) $c['id'] ? 'selected' : ''; ?>><?php echo e($c['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <button class="btn btn-primary" type="submit">Search</button>
    <?php if ($q !== '' || $cat > 0): ?><a class="text-link" href="courses.php">Clear</a><?php endif; ?>
</form>

<?php if (!$courses): ?>
    <section class="panel"><p class="page-note">No courses found.</p></section>
<?php else: ?>
<div class="course-grid">
    <?php foreach ($courses as $i => $c): ?>
        <article class="course-card">
            <div class="course-art <?php echo course_art($i); ?>">
                <span><?php echo e(strtoupper(substr($c['category'], 0, 12))); ?></span><span class="art-symbol">✦</span>
            </div>
            <div class="course-body">
                <span class="course-type"><?php echo e(strtoupper($c['category'])); ?></span>
                <h3><a href="course.php?id=<?php echo (int) $c['id']; ?>"><?php echo e($c['title']); ?></a></h3>
                <p><?php echo e($c['short_description']); ?></p>
                <p class="page-note">by <?php echo e($c['instructor']); ?> · <?php echo e(ucfirst($c['level'])); ?></p>
                <div class="badge-row">
                    <?php if ($c['my_status']): ?><span class="pill green"><?php echo e(ucfirst($c['my_status'])); ?></span><?php endif; ?>
                </div>
                <div class="course-meta" style="margin-top:14px">
                    <span>◷ <?php echo (int) $c['lessons']; ?> lessons <?php echo $c['rating'] !== null ? ' · ★ ' . number_format((float) $c['rating'], 1) : ''; ?></span>
                    <span class="price"><?php echo (float) $c['price'] > 0 ? 'BDT ' . number_format((float) $c['price'], 0) : 'Free'; ?></span>
                </div>
            </div>
        </article>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php portal_end(); ?>
