<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$courseId = (int) ($_GET['course'] ?? 0);
$courses = db_all("SELECT id, title FROM courses WHERE instructor_id=? ORDER BY title", 'i', [$me['id']]);

$sql = "SELECT c.title AS course, COALESCE(NULLIF(u.name,''), u.email) AS student, u.email,
               e.status, e.progress_percent, e.enrolled_at
        FROM enrollments e JOIN courses c ON c.id=e.course_id JOIN users u ON u.id=e.student_id
        WHERE c.instructor_id=?";
$types = 'i';
$params = [$me['id']];
if ($courseId > 0) { $sql .= " AND c.id=?"; $types .= 'i'; $params[] = $courseId; }
$rows = db_all($sql . " ORDER BY e.enrolled_at DESC", $types, $params);

portal_start('My students');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">LEARNERS</span>
        <h1>My students</h1>
        <p>Students enrolled in your courses.</p>
    </div>
</section>
<form class="toolbar" method="get">
    <select class="form-select" name="course" onchange="this.form.submit()">
        <option value="0">All my courses</option>
        <?php foreach ($courses as $c): ?><option value="<?php echo (int) $c['id']; ?>" <?php echo (int) $c['id'] === $courseId ? 'selected' : ''; ?>><?php echo e($c['title']); ?></option><?php endforeach; ?>
    </select>
</form>
<section class="panel">
    <?php if (!$rows): ?><p class="page-note">No enrollments yet.</p><?php else: ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Student</th><th>Course</th><th>Status</th><th>Progress</th><th>Enrolled</th></tr></thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><b><?php echo e($r['student']); ?></b><br><small class="text-muted"><?php echo e($r['email']); ?></small></td>
                <td><?php echo e($r['course']); ?></td>
                <td><span class="pill <?php echo $r['status'] === 'completed' ? 'green' : 'orange'; ?>"><?php echo e(ucfirst($r['status'])); ?></span></td>
                <td><?php echo number_format((float) $r['progress_percent'], 0); ?>%</td>
                <td><?php echo e($r['enrolled_at']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
