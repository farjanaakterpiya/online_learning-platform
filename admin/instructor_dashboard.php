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
        <span class="eyebrow">TEACHING SPACE</span>
        <h1>Instructor dashboard</h1>
        <p>Manage your courses and support your learners.</p>
    </div>
    <a class="btn btn-primary" href="#">＋ Add course</a>
</section>
<div class="metric-grid">
    <article class="metric-card">
        <span>My courses</span>
        <strong>08</strong>
        <small>Published courses</small>
    </article>
    <article class="metric-card">
        <span>Students</span>
        <strong>324</strong>
        <small>Across your courses</small>
    </article>
    <article class="metric-card">
        <span>Lessons</span>
        <strong>56</strong>
        <small>Learning materials</small>
    </article>
    <article class="metric-card">
        <span>Reviews</span>
        <strong>4.8</strong>
        <small>Average course rating</small>
    </article>
</div>
<section class="panel">
    <div class="panel-heading">
        <div>
            <h2>My courses</h2>
            <p>Example course list</p>
        </div>
        <a class="text-link" href="#">Manage courses</a>
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
                <tr>
                    <td>Web Development with PHP</td>
                    <td>124</td>
                    <td>
                        <span class="status">Published</span>
                    </td>
                </tr>
                <tr>
                    <td>HTML & CSS Basics</td>
                    <td>98</td>
                    <td><span class="status">Published</span></td>
                </tr>
                <tr>
                    <td>MySQL for Beginners</td>
                    <td>102</td>
                    <td><span class="status pending">Draft</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
<?php include '../includes/dashboard_footer.php'; ?>