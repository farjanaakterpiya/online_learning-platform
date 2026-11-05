<?php
$pageTitle = "Dashboard";
$stats = [
    ["label" => "Total Students", "value" => "1,248", "change" => "+12.5%", "icon" => "👩‍🎓"],
    ["label" => "Total Courses", "value" => "36", "change" => "+4 new", "icon" => "📚"],
    ["label" => "Instructors", "value" => "18", "change" => "+2 new", "icon" => "👨‍🏫"],
    ["label" => "Revenue", "value" => "৳ 48,500", "change" => "+8.2%", "icon" => "💰"]
];
$courses = [
    ["title" => "Web Development with PHP", "instructor" => "Rahim Ahmed", "students" => 245, "status" => "Published"],
    ["title" => "UI/UX Design Basics", "instructor" => "Nusrat Jahan", "students" => 182, "status" => "Published"],
    ["title" => "Database Management", "instructor" => "Sadia Islam", "students" => 96, "status" => "Pending"],
    ["title" => "JavaScript for Beginners", "instructor" => "Hasan Mahmud", "students" => 310, "status" => "Published"]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?> | LearnHub Admin</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <a class="brand" href="index.php"><span class="brand-mark">L</span><span>Learn<span class="brand-light">Hub</span></span></a>
        <p class="menu-label">MAIN MENU</p>
        <nav>
            <a class="nav-link active" href="index.php"><span>▦</span> Dashboard</a>
            <a class="nav-link" href="#"><span>📚</span> Courses</a>
            <a class="nav-link" href="#"><span>👩‍🎓</span> Students</a>
            <a class="nav-link" href="#"><span>👨‍🏫</span> Instructors</a>
            <a class="nav-link" href="#"><span>📝</span> Enrollments</a>
            <a class="nav-link" href="#"><span>💳</span> Payments</a>
            <a class="nav-link" href="#"><span>📊</span> Reports</a>
        </nav>
        <p class="menu-label settings-label">PREFERENCES</p>
        <nav>
            <a class="nav-link" href="#"><span>⚙</span> Settings</a>
            <a class="nav-link" href="#"><span>❓</span> Help Center</a>
        </nav>
        <div class="sidebar-bottom">
            <div class="support-card"><strong>Need help?</strong><p>Visit our help center for support.</p><a href="#">Get support →</a></div>
            <a class="nav-link logout" href="#"><span>↪</span> Log out</a>
        </div>
    </aside>

    <main class="main">
        <header class="topbar">
            <div>
                <p class="eyebrow">OVERVIEW</p>
                <h1>Dashboard</h1>
            </div>
            <div class="top-actions">
                <button class="icon-button" aria-label="Notifications">♧<i></i></button>
                <div class="profile">
                    <div class="avatar">FA</div>
                    <div><strong>Farjana Akter</strong><small>Administrator</small></div>
                    <span class="chevron">⌄</span>
                </div>
            </div>
        </header>

        <section class="welcome">
            <div><p class="welcome-tag">THURSDAY, NOVEMBER 5, 2026</p><h2>Welcome back, Farjana! 👋</h2><p>Here’s what’s happening with your learning platform today.</p></div>
            <a class="primary-button" href="#">＋ Add New Course</a>
        </section>

        <section class="stats-grid">
            <?php foreach ($stats as $stat): ?>
                <article class="stat-card">
                    <div class="stat-top"><span><?= htmlspecialchars($stat["label"]) ?></span><span class="stat-icon"><?= $stat["icon"] ?></span></div>
                    <strong class="stat-value"><?= htmlspecialchars($stat["value"]) ?></strong>
                    <span class="stat-change"><?= htmlspecialchars($stat["change"]) ?> <span class="muted">this month</span></span>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="content-grid">
            <article class="panel courses-panel">
                <div class="panel-heading"><div><h3>Recent Courses</h3><p>Keep track of your latest courses</p></div><a href="#">View all →</a></div>
                <div class="table-wrap">
                    <table>
                        <thead><tr><th>COURSE NAME</th><th>INSTRUCTOR</th><th>STUDENTS</th><th>STATUS</th></tr></thead>
                        <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($course["title"]) ?></strong></td>
                                <td><?= htmlspecialchars($course["instructor"]) ?></td>
                                <td><?= (int)$course["students"] ?></td>
                                <td><span class="status <?= strtolower($course["status"]) ?>"><?= htmlspecialchars($course["status"]) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </article>

            <article class="panel activity-panel">
                <div class="panel-heading"><div><h3>Recent Activity</h3><p>Latest platform updates</p></div><a href="#">See all</a></div>
                <div class="activity-item"><span class="activity-icon purple">＋</span><div><strong>New student enrolled</strong><p>Tanvir Hasan joined PHP Basics</p><small>10 minutes ago</small></div></div>
                <div class="activity-item"><span class="activity-icon green">✓</span><div><strong>Course published</strong><p>UI/UX Design Basics is now live</p><small>1 hour ago</small></div></div>
                <div class="activity-item"><span class="activity-icon orange">৳</span><div><strong>New payment received</strong><p>৳ 1,500 payment completed</p><small>3 hours ago</small></div></div>
            </article>
        </section>
        <footer>© 2026 LearnHub. All rights reserved.</footer>
    </main>
</div>
</body>
</html>
