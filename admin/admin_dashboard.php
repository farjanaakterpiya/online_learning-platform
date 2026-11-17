
<?php include_once("./db_config.php"); ?>
<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../includes/dashboard_header.php'; 
?>
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
        <strong>1,248</strong>
        <small>Registered learners</small>
    </article>
    <article class="metric-card">
        <span>Active courses</span>
        <strong>36</strong>
        <small>Available courses</small>
    </article>
    <article class="metric-card">
        <span>Instructors</span>
        <strong>18</strong>
        <small>Teaching members</small>
    </article>
    <article class="metric-card">
        <span>Enrollments</span>
        <strong>856</strong>
        <small>Course registrations</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Recent activity</h2>
            <p>Latest platform updates</p>
        </div>
        <a class="text-link" href="#">See all</a>
    </div>
    <div class="activity-list">
        <div>
            <span class="activity-icon">＋</span>
            <p><b>New student registered</b><small>Today · Sample activity</small></p>
        </div>
        <div>
            <span class="activity-icon">▣</span>
            <p><b>A new course was added</b><small>Yesterday · Sample activity</small></p>
        </div>
        <div>
            <span class="activity-icon">✓</span>
            <p><b>Course enrollment completed</b><small>Yesterday · Sample activity</small></p>
        </div>
    </div>
</section>
<?php include '../includes/dashboard_footer.php'; ?>