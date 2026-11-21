<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$courseId = (int) ($_GET['course'] ?? $_POST['course'] ?? 0);
$course = db_one(
    "SELECT c.*, COALESCE(NULLIF(u.name,''), u.email) AS instructor
     FROM courses c JOIN users u ON u.id=c.instructor_id
     WHERE c.id=? AND c.status='published'",
    'i', [$courseId]
);
if (!$course) {
    flash('That course is not available.', 'warning');
    redirect('my_courses.php');
}

$enrollment = db_one("SELECT * FROM enrollments WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $courseId]);
$access = $enrollment && in_array($enrollment['status'], ['active', 'completed'], true);

$sections = db_all("SELECT * FROM course_sections WHERE course_id=? ORDER BY sort_order, id", 'i', [$courseId]);
$lessonsBySection = [];
$flat = [];
foreach (db_all(
    "SELECT l.* FROM lessons l JOIN course_sections s ON s.id=l.section_id
     WHERE s.course_id=? ORDER BY s.sort_order, s.id, l.sort_order, l.id",
    'i', [$courseId]
) as $l) {
    $lessonsBySection[$l['section_id']][] = $l;
    $flat[] = $l;
}
$done = [];
if ($enrollment) {
    foreach (db_all("SELECT lesson_id FROM lesson_progress WHERE enrollment_id=?", 'i', [$enrollment['id']]) as $p) {
        $done[(int) $p['lesson_id']] = true;
    }
}

$lessonId = (int) ($_GET['lesson'] ?? $_POST['lesson'] ?? 0);
$current = null;
foreach ($flat as $l) { if ((int) $l['id'] === $lessonId) { $current = $l; } }
if (!$current && $flat) {
    $current = $flat[0];
    if ($access) { foreach ($flat as $l) { if (empty($done[(int) $l['id']])) { $current = $l; break; } } }
}
if ($current && !$access && !$current['is_preview']) {
    flash('Enroll in this course to open its lessons.', 'warning');
    redirect('course.php?id=' . $courseId);
}

/* ---------- actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!$access) { redirect('course.php?id=' . $courseId); }
    $action = $_POST['action'] ?? '';

    if (($action === 'complete' || $action === 'uncomplete') && $current) {
        if ($action === 'complete') {
            db_run("INSERT IGNORE INTO lesson_progress (enrollment_id, lesson_id) VALUES (?, ?)", 'ii', [$enrollment['id'], $current['id']]);
        } else {
            db_run("DELETE FROM lesson_progress WHERE enrollment_id=? AND lesson_id=?", 'ii', [$enrollment['id'], $current['id']]);
        }
        $pct = recalc_progress($enrollment['id']);
        $next = $current;
        if ($action === 'complete') {
            foreach ($flat as $k => $l) { if ((int) $l['id'] === (int) $current['id'] && isset($flat[$k + 1])) { $next = $flat[$k + 1]; } }
        }
        flash($pct >= 100 ? 'Course completed! Your certificate is ready.' : 'Progress saved (' . number_format($pct, 0) . '%).');
        redirect('learn.php?course=' . $courseId . '&lesson=' . (int) $next['id']);
    }

    if ($action === 'review') {
        $rating = (int) ($_POST['rating'] ?? 0);
        $text = trim($_POST['review_text'] ?? '');
        if ($rating < 1 || $rating > 5) {
            flash('Please choose a rating from 1 to 5.', 'danger');
        } else {
            $old = db_one("SELECT id FROM course_reviews WHERE enrollment_id=?", 'i', [$enrollment['id']]);
            if ($old) {
                db_run("UPDATE course_reviews SET rating=?, review_text=? WHERE id=?", 'isi', [$rating, $text, $old['id']]);
            } else {
                db_run("INSERT INTO course_reviews (enrollment_id, rating, review_text) VALUES (?, ?, ?)", 'iis', [$enrollment['id'], $rating, $text]);
            }
            flash('Thanks for your review!');
        }
        redirect('learn.php?course=' . $courseId . ($current ? '&lesson=' . (int) $current['id'] : ''));
    }
}

$myReview = $enrollment ? db_one("SELECT rating, review_text FROM course_reviews WHERE enrollment_id=?", 'i', [$enrollment['id']]) : null;
$quizzes = $access ? db_all("SELECT id, title, pass_mark, time_limit_minutes FROM quizzes WHERE course_id=? AND status='published' ORDER BY id", 'i', [$courseId]) : [];
$live = $access ? db_all("SELECT title, meeting_url, starts_at, status FROM live_classes WHERE course_id=? AND status IN ('scheduled','live') ORDER BY starts_at LIMIT 5", 'i', [$courseId]) : [];
$pct = $enrollment ? (float) $enrollment['progress_percent'] : 0;

portal_start($course['title']);
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">LEARNING</span>
        <h1><?php echo e($course['title']); ?></h1>
        <p>by <?php echo e($course['instructor']); ?><?php echo $access ? ' · ' . number_format($pct, 0) . '% completed' : ' · preview'; ?></p>
    </div>
    <a class="btn btn-light" href="<?php echo $access ? 'my_courses.php' : 'course.php?id=' . $courseId; ?>">← Back</a>
</section>
<?php if ($access): ?><div class="progress" style="margin:-10px 0 18px;max-width:100%"><span style="width:<?php echo (int) $pct; ?>%"></span></div><?php endif; ?>
<?php flash_show(); ?>

<?php if (!$flat): ?>
    <section class="panel"><p class="page-note">The instructor has not added lessons yet.</p></section>
<?php else: ?>
<div class="lesson-layout">
    <nav class="lesson-nav">
        <?php foreach ($sections as $s): ?>
            <h4><?php echo e($s['title']); ?></h4>
            <?php foreach ($lessonsBySection[$s['id']] ?? [] as $l):
                $locked = !$access && !$l['is_preview'];
                $cls = (!empty($done[(int) $l['id']]) ? ' done' : '') . ((int) $l['id'] === (int) $current['id'] ? ' current' : '');
            ?>
                <?php if ($locked): ?>
                    <a class="<?php echo trim($cls); ?>" style="opacity:.5;pointer-events:none"><span class="tick">🔒</span><?php echo e($l['title']); ?></a>
                <?php else: ?>
                    <a class="<?php echo trim($cls); ?>" href="learn.php?course=<?php echo $courseId; ?>&lesson=<?php echo (int) $l['id']; ?>"><span class="tick"><?php echo !empty($done[(int) $l['id']]) ? '✓' : ''; ?></span><?php echo e($l['title']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </nav>

    <div>
        <section class="panel" style="margin-top:0">
            <div class="panel-heading"><div><h2><?php echo e($current['title']); ?></h2><p><?php echo e(ucfirst($current['lesson_type'])); ?><?php echo (int) $current['duration_minutes'] > 0 ? ' · ' . (int) $current['duration_minutes'] . ' min' : ''; ?></p></div></div>
            <?php
            $url = safe_url($current['resource_url']);
            $embed = $url !== '' ? youtube_embed($url) : null;
            ?>
            <?php if ($current['lesson_type'] === 'video' && $embed): ?>
                <div class="video-frame"><iframe src="<?php echo e($embed); ?>" allowfullscreen></iframe></div>
            <?php elseif ($url !== ''): ?>
                <p><a class="btn btn-primary" href="<?php echo e($url); ?>" target="_blank" rel="noopener noreferrer">Open <?php echo e($current['lesson_type']); ?> ↗</a></p>
            <?php endif; ?>
            <?php if (trim((string) $current['content']) !== ''): ?>
                <div class="lesson-text"><?php echo nl2br(e($current['content'])); ?></div>
            <?php endif; ?>

            <?php if ($access): ?>
                <form method="post" class="toolbar" style="margin:22px 0 0">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="course" value="<?php echo $courseId; ?>">
                    <input type="hidden" name="lesson" value="<?php echo (int) $current['id']; ?>">
                    <?php if (!empty($done[(int) $current['id']])): ?>
                        <button class="btn btn-light" name="action" value="uncomplete" type="submit">Mark as not done</button>
                        <span class="pill green">Completed</span>
                    <?php else: ?>
                        <button class="btn btn-primary" name="action" value="complete" type="submit">Mark complete &amp; continue →</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </section>

        <?php if ($quizzes): ?>
        <section class="panel">
            <div class="panel-heading"><div><h2>Quizzes</h2><p>Test what you learned</p></div></div>
            <?php foreach ($quizzes as $q): ?>
                <div class="lesson-row">
                    <span><?php echo e($q['title']); ?> <small>(pass mark <?php echo number_format((float) $q['pass_mark'], 0); ?>%<?php echo $q['time_limit_minutes'] ? ', ' . (int) $q['time_limit_minutes'] . ' min' : ''; ?>)</small></span>
                    <a class="btn btn-light btn-sm" href="quiz.php?id=<?php echo (int) $q['id']; ?>">Take quiz</a>
                </div>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>

        <?php if ($live): ?>
        <section class="panel">
            <div class="panel-heading"><div><h2>Live classes</h2><p>Upcoming sessions</p></div></div>
            <?php foreach ($live as $lc): $join = safe_url($lc['meeting_url']); ?>
                <div class="lesson-row">
                    <span><?php echo e($lc['title']); ?> <small><?php echo e($lc['starts_at']); ?> · <?php echo e($lc['status']); ?></small></span>
                    <?php if ($join !== ''): ?><a class="btn btn-light btn-sm" href="<?php echo e($join); ?>" target="_blank" rel="noopener noreferrer">Join ↗</a><?php endif; ?>
                </div>
            <?php endforeach; ?>
        </section>
        <?php endif; ?>

        <?php if ($access): ?>
        <section class="panel">
            <div class="panel-heading"><div><h2>Rate this course</h2><p><?php echo $myReview ? 'You can update your review' : 'Share your experience'; ?></p></div></div>
            <form method="post" style="max-width:520px">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="course" value="<?php echo $courseId; ?>">
                <input type="hidden" name="lesson" value="<?php echo (int) $current['id']; ?>">
                <input type="hidden" name="action" value="review">
                <select name="rating" class="form-select mb-2" required>
                    <option value="">Rating</option>
                    <?php for ($i = 5; $i >= 1; $i--): ?><option value="<?php echo $i; ?>" <?php echo ($myReview['rating'] ?? 0) == $i ? 'selected' : ''; ?>><?php echo $i; ?> ★</option><?php endfor; ?>
                </select>
                <textarea name="review_text" rows="3" class="form-control mb-2" placeholder="Write a short review (optional)"><?php echo e($myReview['review_text'] ?? ''); ?></textarea>
                <button class="btn btn-primary btn-sm" type="submit">Save review</button>
            </form>
        </section>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php portal_end(); ?>
