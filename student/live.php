<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$rows = db_all(
    "SELECT lc.title, lc.meeting_url, lc.starts_at, lc.ends_at, lc.status, c.title AS course
     FROM live_classes lc
     JOIN courses c ON c.id=lc.course_id
     JOIN enrollments e ON e.course_id=lc.course_id AND e.student_id=? AND e.status IN ('active','completed')
     WHERE lc.status IN ('scheduled','live')
     ORDER BY lc.starts_at",
    'i', [$me['id']]
);

portal_start('Live classes');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">LIVE</span>
        <h1>Live classes</h1>
        <p>Sessions for the courses you are enrolled in.</p>
    </div>
</section>
<section class="panel">
    <?php if (!$rows): ?><p class="page-note">No upcoming live classes.</p><?php else: ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Class</th><th>Course</th><th>Starts</th><th>Status</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): $join = safe_url($r['meeting_url']); ?>
            <tr>
                <td><?php echo e($r['title']); ?></td>
                <td><?php echo e($r['course']); ?></td>
                <td><?php echo e($r['starts_at']); ?></td>
                <td><span class="pill <?php echo $r['status'] === 'live' ? 'green' : 'orange'; ?>"><?php echo e(ucfirst($r['status'])); ?></span></td>
                <td><?php if ($join !== ''): ?><a class="btn btn-light btn-sm" href="<?php echo e($join); ?>" target="_blank" rel="noopener noreferrer">Join ↗</a><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
