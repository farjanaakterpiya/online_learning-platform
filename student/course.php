<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$id = (int) ($_GET['id'] ?? 0);
$course = db_one(
    "SELECT c.*, cat.name AS category, COALESCE(NULLIF(u.name,''), u.email) AS instructor
     FROM courses c JOIN categories cat ON cat.id=c.category_id JOIN users u ON u.id=c.instructor_id
     WHERE c.id=? AND c.status='published'",
    'i', [$id]
);
if (!$course) {
    flash('That course is not available.', 'warning');
    redirect('courses.php');
}

$enrollment = db_one("SELECT * FROM enrollments WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $id]);
$inWishlist = (bool) db_one("SELECT id FROM wishlists WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $id]);
$sections = db_all("SELECT * FROM course_sections WHERE course_id=? ORDER BY sort_order, id", 'i', [$id]);
$lessonsBySection = [];
foreach (db_all("SELECT l.* FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE s.course_id=? ORDER BY l.sort_order, l.id", 'i', [$id]) as $l) {
    $lessonsBySection[$l['section_id']][] = $l;
}
$reviews = db_all(
    "SELECT r.rating, r.review_text, r.created_at, COALESCE(NULLIF(u.name,''), u.email) AS student
     FROM course_reviews r JOIN enrollments e ON e.id=r.enrollment_id JOIN users u ON u.id=e.student_id
     WHERE e.course_id=? ORDER BY r.created_at DESC LIMIT 10",
    'i', [$id]
);
$avg = $reviews ? array_sum(array_column($reviews, 'rating')) / count($reviews) : null;
$access = $enrollment && in_array($enrollment['status'], ['active', 'completed'], true);

portal_start($course['title']);
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow"><?php echo e(strtoupper($course['category'])); ?></span>
        <h1><?php echo e($course['title']); ?></h1>
        <p>by <?php echo e($course['instructor']); ?> · <?php echo e(ucfirst($course['level'])); ?> · <?php echo e($course['language']); ?><?php echo $avg !== null ? ' · ★ ' . number_format($avg, 1) : ''; ?></p>
    </div>
    <a class="btn btn-light" href="courses.php">← All courses</a>
</section>
<?php flash_show(); ?>

<section class="panel">
    <p><?php echo e($course['short_description']); ?></p>
    <div class="toolbar" style="margin:14px 0 0">
        <span class="price" style="font-size:20px"><?php echo (float) $course['price'] > 0 ? 'BDT ' . number_format((float) $course['price'], 2) : 'Free'; ?></span>
        <?php if ($access): ?>
            <a class="btn btn-primary" href="learn.php?course=<?php echo $id; ?>">Continue learning →</a>
        <?php elseif ($enrollment): ?>
            <span class="pill orange">Enrollment <?php echo e($enrollment['status']); ?></span>
        <?php else: ?>
            <a class="btn btn-primary" href="enroll.php?course=<?php echo $id; ?>">Enroll now</a>
        <?php endif; ?>
        <form method="post" action="wishlist.php" style="display:inline">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course_id" value="<?php echo $id; ?>">
            <input type="hidden" name="from" value="course">
            <button class="btn btn-light" type="submit"><?php echo $inWishlist ? '♥ Saved' : '♡ Save to wishlist'; ?></button>
        </form>
    </div>
</section>

<?php if (trim((string) $course['description']) !== ''): ?>
<section class="panel">
    <div class="panel-heading"><div><h2>About this course</h2></div></div>
    <div class="lesson-text"><?php echo nl2br(e($course['description'])); ?></div>
</section>
<?php endif; ?>

<section class="panel">
    <div class="panel-heading"><div><h2>Course content</h2><p><?php echo count($sections); ?> sections</p></div></div>
    <?php if (!$sections): ?><p class="page-note">The instructor has not added content yet.</p><?php endif; ?>
    <?php foreach ($sections as $s): ?>
        <div class="block-card">
            <h3><?php echo e($s['title']); ?></h3>
            <?php foreach ($lessonsBySection[$s['id']] ?? [] as $l): ?>
                <div class="lesson-row">
                    <span><?php echo e($l['title']); ?> <small>(<?php echo e($l['lesson_type']); ?><?php echo (int) $l['duration_minutes'] > 0 ? ', ' . (int) $l['duration_minutes'] . ' min' : ''; ?>)</small></span>
                    <?php if ($l['is_preview'] && !$access): ?><a class="text-link" href="learn.php?course=<?php echo $id; ?>&lesson=<?php echo (int) $l['id']; ?>">Preview</a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>
</section>

<section class="panel">
    <div class="panel-heading"><div><h2>Reviews</h2><p><?php echo count($reviews); ?> review(s)</p></div></div>
    <?php if (!$reviews): ?><p class="page-note">No reviews yet.</p><?php endif; ?>
    <?php foreach ($reviews as $r): ?>
        <div class="lesson-row" style="display:block">
            <b><?php echo e($r['student']); ?></b> <span class="stars"><?php echo str_repeat('★', (int) $r['rating']) . str_repeat('☆', 5 - (int) $r['rating']); ?></span>
            <div class="lesson-text"><?php echo nl2br(e($r['review_text'])); ?></div>
        </div>
    <?php endforeach; ?>
</section>
<?php portal_end(); ?>
