<?php
session_start();
include_once("../db_config.php");

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'student') {
    header("Location: ../login.php");
    exit;
}

$email = $conn->real_escape_string($_SESSION['email']);
$user = $conn->query("SELECT id FROM users WHERE email='$email' AND role='student' LIMIT 1")->fetch_assoc();
if (!$user) {
    header("Location: ../login.php");
    exit;
}
$studentId = (int) $user['id'];
function student_escape($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }

$enrolledCount = (int) $conn->query("SELECT COUNT(*) AS total FROM enrollments WHERE student_id=$studentId")->fetch_assoc()['total'];
$certificateCount = (int) $conn->query(
    "SELECT COUNT(*) AS total
     FROM certificates c
     JOIN enrollments e ON e.id=c.enrollment_id
     WHERE e.student_id=$studentId"
)->fetch_assoc()['total'];
$learning = $conn->query("SELECT c.id,c.title,e.progress_percent FROM enrollments e JOIN courses c ON c.id=e.course_id WHERE e.student_id=$studentId ORDER BY e.enrolled_at DESC LIMIT 10");

include '../includes/dashboard_header.php';
include '../includes/dashboard_sidebar.php';
?>
<main class="dashboard-content">
    <div class="dashboard-container">
<section class="welcome-row">
    <div>
        <span class="eyebrow">MY LEARNING</span>
        <h1>Student dashboard</h1>
        <p>Keep going! Every lesson brings you closer to your goal.</p>
    </div>
    <a class="btn btn-primary" href="../index.html#courses">Explore courses</a></section>
<div class="metric-grid">
    <article class="metric-card">
        <span>Enrolled courses</span>
        <strong><?php echo str_pad((string) $enrolledCount, 2, '0', STR_PAD_LEFT); ?></strong>
        <small>Your learning list</small>
    </article>
    <article class="metric-card">
        <span>Completed lessons</span>
        <strong>0</strong>
        <small>Great progress</small>
    </article>
    <article class="metric-card">
        <span>Learning hours</span>
        <strong>0</strong>
        <small>Total study time</small>
    </article>
    <article class="metric-card">
        <span>Certificates</span>
        <strong><?php echo str_pad((string) $certificateCount, 2, '0', STR_PAD_LEFT); ?></strong>
        <small>Achievements</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Continue learning</h2>
            <p>Pick up where you left off</p>
        </div>
        <a class="text-link" href="../crud/enrollments/index.php">My enrollments</a>
    </div>
    <?php if ($learning && $learning->num_rows > 0): ?>
        <?php while ($course = $learning->fetch_assoc()):
            $progress = (float) $course['progress_percent'];
        ?>
            <div class="learning-row">
                <div class="learning-thumb art-purple">&lt;/&gt;</div>
                <div class="learning-info">
                    <b><?php echo student_escape($course['title']); ?></b>
                    <small><?php echo number_format($progress, 0); ?>% completed</small>
                    <div class="progress"><span style="width:<?php echo (int) $progress; ?>%"></span></div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No enrolled courses yet.</p>
    <?php endif; ?>
</section>
<?php include '../includes/dashboard_footer.php'; ?>