<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$rows = db_all(
    "SELECT e.id AS enrollment_id, e.status, e.progress_percent, e.enrolled_at, c.id, c.title,
            (SELECT certificate_code FROM certificates ce WHERE ce.enrollment_id=e.id) AS cert
     FROM enrollments e JOIN courses c ON c.id=e.course_id
     WHERE e.student_id=? ORDER BY e.enrolled_at DESC",
    'i', [$me['id']]
);

portal_start('My courses');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">MY LEARNING</span>
        <h1>My courses</h1>
        <p>Everything you are enrolled in.</p>
    </div>
    <a class="btn btn-primary" href="courses.php">Browse more</a>
</section>
<?php flash_show(); ?>
<section class="panel">
    <?php if (!$rows): ?><p class="page-note">You have not enrolled in any course yet.</p><?php endif; ?>
    <?php foreach ($rows as $i => $r): $pct = (float) $r['progress_percent']; ?>
        <div class="learning-row">
            <div class="learning-thumb <?php echo course_art($i); ?>">▣</div>
            <div class="learning-info">
                <b><?php echo e($r['title']); ?></b>
                <small><?php echo number_format($pct, 0); ?>% completed · <?php echo e(ucfirst($r['status'])); ?></small>
                <div class="progress"><span style="width:<?php echo (int) $pct; ?>%"></span></div>
            </div>
            <?php if (in_array($r['status'], ['active', 'completed'], true)): ?>
                <a class="btn btn-light btn-sm" href="learn.php?course=<?php echo (int) $r['id']; ?>"><?php echo $pct > 0 ? 'Continue' : 'Start'; ?></a>
            <?php endif; ?>
            <?php if ($r['cert']): ?><a class="btn btn-primary btn-sm" href="certificate.php?code=<?php echo e($r['cert']); ?>">Certificate</a><?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
<?php portal_end(); ?>
