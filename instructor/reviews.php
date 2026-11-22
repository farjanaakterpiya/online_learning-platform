<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$courses = db_all("SELECT id, title FROM courses WHERE instructor_id=? ORDER BY title", 'i', [$me['id']]);
$courseId = (int) ($_GET['course'] ?? 0);
$sql = "SELECT c.title AS course,
               COALESCE(NULLIF(u.name, ''), u.email) AS student,
               r.rating, r.review_text, r.created_at
        FROM course_reviews r
        JOIN enrollments e ON e.id=r.enrollment_id
        JOIN courses c ON c.id=e.course_id
        JOIN users u ON u.id=e.student_id
        WHERE c.instructor_id=?";
$types = 'i';
$params = [$me['id']];
if ($courseId > 0) {
    $sql .= " AND c.id=?";
    $types .= 'i';
    $params[] = $courseId;
}
$rows = db_all($sql . " ORDER BY r.created_at DESC", $types, $params);
$average = db_one(
    "SELECT COALESCE(AVG(r.rating), 0) AS average, COUNT(r.id) AS total
     FROM course_reviews r
     JOIN enrollments e ON e.id=r.enrollment_id
     JOIN courses c ON c.id=e.course_id
     WHERE c.instructor_id=?",
    'i',
    [$me['id']]
);

portal_start('Course Reviews');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">FEEDBACK</span>
        <h1>Course reviews</h1>
        <p>See ratings and feedback from students in your courses.</p>
    </div>
</section>
<div class="metric-grid">
    <article class="metric-card">
        <span>Average rating</span>
        <strong><?php echo number_format((float) $average['average'], 1); ?></strong>
        <small>Out of 5</small>
    </article>
    <article class="metric-card">
        <span>Total reviews</span>
        <strong><?php echo (int) $average['total']; ?></strong>
        <small>Student feedback</small>
    </article>
</div>
<form class="toolbar" method="get">
    <select class="form-select" name="course" onchange="this.form.submit()">
        <option value="0">All my courses</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?php echo (int) $course['id']; ?>" <?php echo (int) $course['id'] === $courseId ? 'selected' : ''; ?>>
                <?php echo e($course['title']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
<section class="panel">
    <?php if (!$rows): ?>
        <p class="page-note">No course reviews yet.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Student</th><th>Course</th><th>Rating</th><th>Review</th><th>Date</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><b><?php echo e($row['student']); ?></b></td>
                        <td><?php echo e($row['course']); ?></td>
                        <td><?php echo str_repeat('&#9733;', (int) $row['rating']); ?> <small>(<?php echo (int) $row['rating']; ?>/5)</small></td>
                        <td><?php echo e($row['review_text'] ?: 'No written review'); ?></td>
                        <td><?php echo e($row['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
