<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$code = trim($_GET['code'] ?? '');
$cert = db_one(
    "SELECT ce.certificate_code, ce.issued_at, c.title, COALESCE(NULLIF(u.name,''), u.email) AS student,
            COALESCE(NULLIF(i.name,''), i.email) AS instructor
     FROM certificates ce
     JOIN enrollments e ON e.id=ce.enrollment_id
     JOIN courses c ON c.id=e.course_id
     JOIN users u ON u.id=e.student_id
     JOIN users i ON i.id=c.instructor_id
     WHERE ce.certificate_code=? AND e.student_id=?",
    'si', [$code, $me['id']]
);
if (!$cert) {
    flash('Certificate not found.', 'warning');
    redirect('certificates.php');
}

portal_start('Certificate');
?>
<div class="no-print toolbar">
    <a class="btn btn-light" href="certificates.php">← Back</a>
    <button class="btn btn-primary" onclick="window.print()">Print / Save as PDF</button>
</div>
<div class="certificate">
    <span class="eyebrow">CERTIFICATE OF COMPLETION</span>
    <h1>EduSpace</h1>
    <p class="page-note">This certifies that</p>
    <div class="who"><?php echo e($cert['student']); ?></div>
    <p class="page-note">has successfully completed the course</p>
    <h2 style="font-size:24px;margin:10px 0 24px"><?php echo e($cert['title']); ?></h2>
    <p class="page-note">Instructor: <?php echo e($cert['instructor']); ?> · Issued <?php echo e(substr($cert['issued_at'], 0, 10)); ?></p>
    <p class="page-note">Certificate No. <b><?php echo e($cert['certificate_code']); ?></b></p>
</div>
<?php portal_end(); ?>
