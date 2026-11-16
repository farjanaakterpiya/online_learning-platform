<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($pageTitle) ?> | EduSpace</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body class="dashboard-container">
<header class="dash-topbar">
    <a class="brand" href="index.html">
        <span class="brand-mark">E</span> EduSpace</a>
        <div class="topbar-right">
            <span class="role-chip">
                <?= htmlspecialchars($role) ?>
                </span><a class="user-mini" href="logout.php">Log out ↗</a>
            </div>
        </header>
<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="side-label">WORKSPACE</div>

        <a class="side-link active" href="admin_dashboard.php">▦ <span>Dashboard</span></a>
        <a class="side-link" href="./courses/courses.php">▣ <span>Courses</span></a>

        <a class="side-link" href="#">♙ <span>Users</span></a> 
            <a class="side-link" href="#">▤ <span>Categories</span></a>
                <a class="side-link" href="#">＋ <span>Create course</span></a>
                <a class="side-link" href="#">✉ <span>Students</span></a>
                    <a class="side-link" href="#">✓ <span>My learning</span></a>
                    <a class="side-link" href="#">▧ <span>Certificates</span></a>
                    <div class="side-bottom">
                        <a class="side-link" href="index.html">⌂ <span>Visit website</span></a>
                    </div>
                </aside>
                <main class="dashboard-content">
                    <div class="dashboard-container">


                    