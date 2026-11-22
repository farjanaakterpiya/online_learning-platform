<?php
session_start();
include_once("../db_config.php");

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'instructor') {
    header("Location: ../login.php");
    exit;
}

$pageTitle = 'Instructor Dashboard';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function instructor_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$email = (string) $_SESSION['email'];
$identity = $conn->prepare("SELECT id FROM users WHERE email=? AND role='instructor' LIMIT 1");
$identity->bind_param("s", $email);
$identity->execute();
$user = $identity->get_result()->fetch_assoc();
if (!$user) {
    header("Location: ../login.php");
    exit;
}
$instructorId = (int) $user['id'];

$courseCountResult = $conn->prepare("SELECT COUNT(*) AS total FROM courses WHERE instructor_id=?");
$courseCountResult->bind_param("i", $instructorId);
$courseCountResult->execute();
$courseCount = (int) $courseCountResult->get_result()->fetch_assoc()['total'];

$publishedCountResult = $conn->prepare("SELECT COUNT(*) AS total FROM courses WHERE instructor_id=? AND status='published'");
$publishedCountResult->bind_param("i", $instructorId);
$publishedCountResult->execute();
$publishedCount = (int) $publishedCountResult->get_result()->fetch_assoc()['total'];

$studentCountResult = $conn->prepare(
    "SELECT COUNT(DISTINCT e.student_id) AS total
     FROM enrollments e
     JOIN courses c ON c.id=e.course_id
     WHERE c.instructor_id=?"
);
$studentCountResult->bind_param("i", $instructorId);
$studentCountResult->execute();
$studentCount = (int) $studentCountResult->get_result()->fetch_assoc()['total'];

$lessonCountResult = $conn->prepare(
    "SELECT COUNT(l.id) AS total
     FROM lessons l
     JOIN course_sections s ON s.id=l.section_id
     JOIN courses c ON c.id=s.course_id
     WHERE c.instructor_id=?"
);
$lessonCountResult->bind_param("i", $instructorId);
$lessonCountResult->execute();
$lessonCount = (int) $lessonCountResult->get_result()->fetch_assoc()['total'];

$ratingResult = $conn->prepare(
    "SELECT AVG(r.rating) AS total
     FROM course_reviews r
     JOIN enrollments e ON e.id=r.enrollment_id
     JOIN courses c ON c.id=e.course_id
     WHERE c.instructor_id=?"
);
$ratingResult->bind_param("i", $instructorId);
$ratingResult->execute();
$rating = $ratingResult->get_result()->fetch_assoc()['total'];
$rating = $rating !== null ? number_format((float) $rating, 1) : '0.0';

$coursesResult = $conn->prepare(
    "SELECT c.id, c.title, c.status, COUNT(DISTINCT e.student_id) AS students
     FROM courses c
     LEFT JOIN enrollments e ON e.course_id=c.id
     WHERE c.instructor_id=?
     GROUP BY c.id, c.title, c.status, c.updated_at
     ORDER BY c.updated_at DESC
     LIMIT 10"
);
$coursesResult->bind_param("i", $instructorId);
$coursesResult->execute();
$courses = $coursesResult->get_result();

include '../includes/dashboard_header.php';
include '../includes/dashboard_sidebar.php';
?>
<main class="dashboard-content">
    <div class="dashboard-container">
<section class="welcome-row">
    <div>
        <span class="eyebrow">TEACHING SPACE</span>
        <h1>Instructor dashboard</h1>
        <p>Manage your courses and support your learners.</p>
    </div>
    <a class="btn btn-primary" href="../crud/courses/create.php">＋ Add course</a>
</section>
<div class="metric-grid">
    <article class="metric-card">
        <span>My courses</span>
        <strong><?php echo str_pad((string) $courseCount, 2, '0', STR_PAD_LEFT); ?></strong>
        <small><?php echo (int) $publishedCount; ?> published courses</small>
    </article>
    <article class="metric-card">
        <span>Students</span>
        <strong><?php echo number_format($studentCount); ?></strong>
        <small>Across your courses</small>
    </article>
    <article class="metric-card">
        <span>Lessons</span>
        <strong><?php echo number_format($lessonCount); ?></strong>
        <small>Learning materials</small>
    </article>
    <article class="metric-card">
        <span>Reviews</span>
        <strong><?php echo instructor_escape($rating); ?></strong>
        <small>Average course rating</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>My courses</h2>
            <p>Your latest courses</p>
        </div>
        <a class="text-link" href="../crud/courses/index.php">Manage courses</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Students</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($courses && $courses->num_rows > 0): ?>
                    <?php while ($course = $courses->fetch_assoc()):
                        $status = (string) $course['status'];
                        $statusClass = $status === 'published' ? '' : ' pending';
                    ?>
                        <tr>
                            <td><?php echo instructor_escape($course['title']); ?></td>
                            <td><?php echo number_format((int) $course['students']); ?></td>
                            <td><span class="status<?php echo $statusClass; ?>"><?php echo instructor_escape(ucfirst($status)); ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">No courses created yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include '../includes/dashboard_footer.php'; ?>