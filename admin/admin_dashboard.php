<?php
session_start();
include_once("../db_config.php");

if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../login.php");
    exit;
}

function admin_escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

$totalStudents = (int) $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='student'")->fetch_assoc()['total'];
$activeCourses = (int) $conn->query("SELECT COUNT(*) AS total FROM courses WHERE status='published'")->fetch_assoc()['total'];
$totalInstructors = (int) $conn->query("SELECT COUNT(*) AS total FROM users WHERE role='instructor'")->fetch_assoc()['total'];
$totalEnrollments = (int) $conn->query("SELECT COUNT(*) AS total FROM enrollments")->fetch_assoc()['total'];

$recentUsers = $conn->query("SELECT name, email, created_at FROM users ORDER BY created_at DESC LIMIT 3");
$recentCourses = $conn->query("SELECT title, created_at FROM courses ORDER BY created_at DESC LIMIT 3");
$recentEnrollments = $conn->query(
    "SELECT c.title, e.enrolled_at
     FROM enrollments e
     JOIN courses c ON c.id=e.course_id
     ORDER BY e.enrolled_at DESC LIMIT 3"
);

include '../includes/dashboard_header.php';
include '../includes/dashboard_sidebar.php';
?>
<main class="dashboard-content">
    <div class="dashboard-container">

<section class="welcome-row">
    <div>
        <span class="eyebrow">OVERVIEW</span>
        <h1>Admin dashboard</h1>
        <p>Welcome back! Here's what's happening on your platform.</p>
    </div>
    <a class="btn btn-primary" href="../index.html">View website ↗</a>
</section>
<div class="metric-grid">
    <article class="metric-card">
        <span>Total students</span>
        <strong><?php echo number_format($totalStudents); ?></strong>
        <small>Registered learners</small>
    </article>
    <article class="metric-card">
        <span>Active courses</span>
        <strong><?php echo number_format($activeCourses); ?></strong>
        <small>Available courses</small>
    </article>
    <article class="metric-card">
        <span>Instructors</span>
        <strong><?php echo number_format($totalInstructors); ?></strong>
        <small>Teaching members</small>
    </article>
    <article class="metric-card">
        <span>Enrollments</span>
        <strong><?php echo number_format($totalEnrollments); ?></strong>
        <small>Course registrations</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Recent activity</h2>
            <p>Latest platform updates</p>
        </div>
        <a class="text-link" href="../crud/users/index.php">See all users</a>
    </div>
    <div class="activity-list">
        <?php if ($recentUsers): ?>
            <?php while ($user = $recentUsers->fetch_assoc()): ?>
                <div>
                    <span class="activity-icon">＋</span>
                    <p><b><?php echo admin_escape($user['name'] ?: $user['email']); ?> registered</b><small><?php echo admin_escape($user['created_at']); ?></small></p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php if ($recentCourses): ?>
            <?php while ($course = $recentCourses->fetch_assoc()): ?>
                <div>
                    <span class="activity-icon">▣</span>
                    <p><b><?php echo admin_escape($course['title']); ?> was added</b><small><?php echo admin_escape($course['created_at']); ?></small></p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
        <?php if ($recentEnrollments): ?>
            <?php while ($enrollment = $recentEnrollments->fetch_assoc()): ?>
                <div>
                    <span class="activity-icon">✓</span>
                    <p><b>Enrollment in <?php echo admin_escape($enrollment['title']); ?></b><small><?php echo admin_escape($enrollment['enrolled_at']); ?></small></p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</section>
<?php include '../includes/dashboard_footer.php'; ?>