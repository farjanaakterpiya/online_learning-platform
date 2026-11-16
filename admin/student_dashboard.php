<?php include_once("db_config.php"); ?>
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
        <span class="eyebrow">MY LEARNING</span>
        <h1>Student dashboard</h1>
        <p>Keep going! Every lesson brings you closer to your goal.</p>
    </div>
    <a class="btn btn-primary" href="../index.html#courses">Explore courses</a>
</section>
<div class="metric-grid">
    <article class="metric-card">
        <span>Enrolled courses</span>
        <strong>04</strong>
        <small>Your learning list</small>
    </article>
    <article class="metric-card">
        <span>Completed lessons</span>
        <strong>18</strong>
        <small>Great progress</small>
    </article>
    <article class="metric-card">
        <span>Learning hours</span>
        <strong>26</strong>
        <small>Total study time</small>
    </article>
    <article class="metric-card">
        <span>Certificates</span>
        <strong>02</strong>
        <small>Achievements</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>Continue learning</h2>
            <p>Pick up where you left off</p>
        </div>
        <a class="text-link" href="#">My courses</a>
    </div>
    <div class="learning-row">
        <div class="learning-thumb art-purple">&lt;/&gt;</div>
        <div class="learning-info">
            <b>Web Development Fundamentals</b>
            <small>6 of 10 lessons completed</small>
            <div class="progress">
                <span style="width:60%"></span>
            </div>
        </div>
        <a class="btn btn-light" href="#">Continue</a>
    </div>
    <div class="learning-row">
        <div class="learning-thumb art-green">▤</div>
        <div class="learning-info">
            <b>Database Management with MySQL</b>
            <small>3 of 8 lessons completed</small>
            <div class="progress">
                <span style="width:38%"></span>
            </div>
        </div>
        <a class="btn btn-light" href="#">Continue</a>
    </div>
</section>
<?php include '../includes/dashboard_footer.php'; ?>