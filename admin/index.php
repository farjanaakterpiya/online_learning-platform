<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LearnHub Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="app">

    <aside class="sidebar">
        <a class="brand" href="index.html">
            <span class="brand-mark">L</span>
            <span>Learn<span class="brand-light">Hub</span></span>
        </a>

        <p class="menu-label">MAIN MENU</p>

        <nav>
            <a class="nav-link active" href="index.html"><span>▦</span> Dashboard</a>
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
            <div class="support-card">
                <strong>Need help?</strong>
                <p>Visit our help center for support.</p>
                <a href="#">Get support →</a>
            </div>

            <a class="nav-link logout" href="logout.php">
                <span>↪</span> Log out
            </a>
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
                    <div>
                        <strong>Farjana Akter</strong>
                        <small>Administrator</small>
                    </div>
                    <span class="chevron">⌄</span>
                </div>
            </div>
        </header>

        <section class="welcome">
            <div>
                <p class="welcome-tag">THURSDAY, NOVEMBER 5, 2026</p>
                <h2>Welcome back, Farjana! 👋</h2>
                <p>Here's what's happening with your learning platform today.</p>
            </div>

            <a class="primary-button" href="#">＋ Add New Course</a>
        </section>

        <section class="stats-grid">

            <article class="stat-card">
                <div class="stat-top">
                    <span>Total Students</span>
                    <span class="stat-icon">👩‍🎓</span>
                </div>
                <strong class="stat-value">1,248</strong>
                <span class="stat-change">+12.5% <span class="muted">this month</span></span>
            </article>

            <article class="stat-card">
                <div class="stat-top">
                    <span>Total Courses</span>
                    <span class="stat-icon">📚</span>
                </div>
                <strong class="stat-value">36</strong>
                <span class="stat-change">+4 new <span class="muted">this month</span></span>
            </article>

            <article class="stat-card">
                <div class="stat-top">
                    <span>Instructors</span>
                    <span class="stat-icon">👨‍🏫</span>
                </div>
                <strong class="stat-value">18</strong>
                <span class="stat-change">+2 new <span class="muted">this month</span></span>
            </article>

            <article class="stat-card">
                <div class="stat-top">
                    <span>Revenue</span>
                    <span class="stat-icon">💰</span>
                </div>
                <strong class="stat-value">৳ 48,500</strong>
                <span class="stat-change">+8.2% <span class="muted">this month</span></span>
            </article>

        </section>

        <section class="content-grid">

            <article class="panel courses-panel">

                <div class="panel-heading">
                    <div>
                        <h3>Recent Courses</h3>
                        <p>Keep track of your latest courses</p>
                    </div>
                    <a href="#">View all →</a>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>COURSE NAME</th>
                                <th>INSTRUCTOR</th>
                                <th>STUDENTS</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td><strong>Web Development with PHP</strong></td>
                                <td>Rahim Ahmed</td>
                                <td>245</td>
                                <td><span class="status published">Published</span></td>
                            </tr>

                            <tr>
                                <td><strong>UI/UX Design Basics</strong></td>
                                <td>Nusrat Jahan</td>
                                <td>182</td>
                                <td><span class="status published">Published</span></td>
                            </tr>

                            <tr>
                                <td><strong>Database Management</strong></td>
                                <td>Sadia Islam</td>
                                <td>96</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>

                            <tr>
                                <td><strong>JavaScript for Beginners</strong></td>
                                <td>Hasan Mahmud</td>
                                <td>310</td>
                                <td><span class="status published">Published</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </article>

            <article class="panel activity-panel">

                <div class="panel-heading">
                    <div>
                        <h3>Recent Activity</h3>
                        <p>Latest platform updates</p>
                    </div>
                    <a href="#">See all</a>
                </div>

                <div class="activity-item">
                    <span class="activity-icon purple">＋</span>
                    <div>
                        <strong>New student enrolled</strong>
                        <p>Tanvir Hasan joined PHP Basics</p>
                        <small>10 minutes ago</small>
                    </div>
                </div>

                <div class="activity-item">
                    <span class="activity-icon green">✓</span>
                    <div>
                        <strong>Course published</strong>
                        <p>UI/UX Design Basics is now live</p>
                        <small>1 hour ago</small>
                    </div>
                </div>

                <div class="activity-item">
                    <span class="activity-icon orange">৳</span>
                    <div>
                        <strong>New payment received</strong>
                        <p>৳ 1,500 payment completed</p>
                        <small>3 hours ago</small>
                    </div>
                </div>

            </article>

        </section>

        <footer>
            © 2026 LearnHub. All rights reserved.
        </footer>

    </main>
</div>

</body>
</html>