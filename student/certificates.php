<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$rows = db_all(
    "SELECT ce.certificate_code, ce.issued_at, c.title
     FROM certificates ce JOIN enrollments e ON e.id=ce.enrollment_id JOIN courses c ON c.id=e.course_id
     WHERE e.student_id=? ORDER BY ce.issued_at DESC",
    'i', [$me['id']]
);

portal_start('Certificates');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">ACHIEVEMENTS</span>
        <h1>My certificates</h1>
        <p>Complete every lesson in a course to earn its certificate.</p>
    </div>
</section>
<section class="panel">
    <?php if (!$rows): ?><p class="page-note">No certificates yet.</p><?php else: ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Course</th><th>Code</th><th>Issued</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?php echo e($r['title']); ?></td>
                <td><?php echo e($r['certificate_code']); ?></td>
                <td><?php echo e($r['issued_at']); ?></td>
                <td><a class="btn btn-primary btn-sm" href="certificate.php?code=<?php echo e($r['certificate_code']); ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
