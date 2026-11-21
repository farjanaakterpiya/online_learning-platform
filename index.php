<?php
session_start();
include_once(__DIR__ . "/db_config.php");

function h($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

$loggedIn = !empty($_SESSION['email']);
$role = $_SESSION['role'] ?? '';
$dash = $role === 'admin' ? 'admin/admin_dashboard.php' : ($role === 'instructor' ? 'admin/instructor_dashboard.php' : 'admin/student_dashboard.php');
$browse = $loggedIn && $role === 'student' ? 'student/courses.php' : 'login.php';

$stat = function ($sql) use ($conn) { $r = $conn->query($sql); return $r ? (int) $r->fetch_assoc()['t'] : 0; };
$countCourses = $stat("SELECT COUNT(*) t FROM courses WHERE status='published'");
$countInstructors = $stat("SELECT COUNT(*) t FROM users WHERE role='instructor' AND status='active'");
$countStudents = $stat("SELECT COUNT(*) t FROM users WHERE role='student' AND status='active'");

$courses = $conn->query(
    "SELECT c.id, c.title, c.short_description, c.price, cat.name AS category,
            (SELECT COUNT(*) FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE s.course_id=c.id) AS lessons,
            (SELECT AVG(r.rating) FROM course_reviews r JOIN enrollments e ON e.id=r.enrollment_id WHERE e.course_id=c.id) AS rating
     FROM courses c JOIN categories cat ON cat.id=c.category_id
     WHERE c.status='published' ORDER BY c.created_at DESC LIMIT 6"
);
$arts = ['art-purple', 'art-peach', 'art-green'];
$symbols = ['&lt;/&gt;', '✳', '▤'];
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>EduSpace | Learn without limits</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <!-- Bootstrap 5 + Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
<body>
<header class="site-header">
    <a class="brand" href="index.php"><span class="brand-mark">E</span> EduSpace</a>
    <nav><a href="index.php">Home</a><a href="#courses">Courses</a><a href="#about">About</a></nav>
    <div class="header-actions">
        <?php if ($loggedIn): ?>
        <a class="btn btn-primary" href="<?php echo h($dash); ?>">My dashboard</a>
        <a class="btn btn-light" href="logout.php">Log out</a>
        <?php else: ?>
        <a class="btn btn-light" href="login.php">Log in</a>
        <a class="btn btn-primary" href="register.php">Get started</a>
        <?php endif; ?>
    </div>
    <button class="menu-toggle" aria-label="Open menu">☰</button>
</header>
<main>
    <section class="hero wrap">
        <div>
            <span class="eyebrow">LEARN • GROW • ACHIEVE</span>
            <h1>Build skills for a <span>brighter future.</span></h1>
            <p class="lead">Learn practical skills from expert instructors and move forward at your own pace.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="register.php">Start learning <span>→</span></a>
                <a class="text-link" href="#courses">Explore courses</a>
            </div>
            <div class="hero-note">
                <div class="avatar-stack"><i>J</i><i>M</i><i>A</i></div>
                <span><b><?php echo $countStudents; ?></b> learners growing with us</span>
            </div>
        </div>
        <div class="hero-art">
            <div class="hero-card card-main">
                <div class="card-top">
                    <span class="tag">YOUR LEARNING SPACE</span>
                    <span class="dot"></span>
                </div>
                <div class="book-icon">✦</div>
                <h3>Keep learning.<br>Keep growing.</h3>
                <p>Your next skill starts here.</p>
                <div class="progress-label"><span>Weekly progress</span><b>78%</b>
                </div>
                <div class="progress">
                    <span style="width:78%"></span>
                </div>
            </div>
            <div class="float-card float-one">✓ Course completed</div>
            <div class="float-card float-two">✧ Learn at your pace</div>
        </div>
    </section>
<section class="stats wrap">
    <div>
        <b><?php echo $countCourses; ?></b><span>Published courses</span>
    </div>
    <div>
        <b><?php echo $countInstructors; ?></b><span>Instructors</span>
    </div>
    <div>
        <b><?php echo $countStudents; ?></b><span>Learners</span>
    </div>
    <div><b>24/7</b><span>Learn anytime</span></div>
</section>
<section class="section wrap" id="courses">
    <div class="section-heading">
        <div>
            <span class="eyebrow">CHOOSE YOUR PATH</span>
            <h2>Explore popular courses</h2>
            <p>Find a course that matches your goals and interests.</p>
        </div>
        <a class="text-link" href="<?php echo h($browse); ?>">View all courses →</a>
    </div>
    <div class="course-grid">
<?php if ($courses && $courses->num_rows > 0): $i = 0; while ($c = $courses->fetch_assoc()): ?>
<article class="course-card">
    <div class="course-art <?php echo $arts[$i % 3]; ?>">
        <span><?php echo h(strtoupper(substr($c['category'], 0, 10))); ?></span><span class="art-symbol"><?php echo $symbols[$i % 3]; ?></span>
    </div>
    <div class="course-body"><span class="course-type"><?php echo h(strtoupper($c['category'])); ?></span>
        <h3><?php echo h($c['title']); ?></h3>
        <p><?php echo h($c['short_description']); ?></p>
        <div class="course-meta"><span>◷ <?php echo (int) $c['lessons']; ?> lessons</span><span><?php echo $c['rating'] !== null ? '★ ' . number_format((float) $c['rating'], 1) : ((float) $c['price'] > 0 ? 'BDT ' . number_format((float) $c['price'], 0) : 'Free'); ?></span></div></div>
</article>
<?php $i++; endwhile; else: ?>
<p>No published courses yet. Please check back soon.</p>
<?php endif; ?>
</div>
</section>

<section class="section about-section" id="about">
    <div class="wrap about-grid">
        <div class="about-visual">
            <div class="about-bubble">✦</div>
            <div class="about-panel">
                <span class="eyebrow">A BETTER WAY TO LEARN</span>
                <h3>Small steps.<br>Big achievements.</h3>
                <p>Learn something useful every day.</p>
            </div>
        </div>
        <div>
            <span class="eyebrow">WHY EDUSPACE</span>
            <h2>Learning that fits your life</h2>
            <p class="lead">Whether you're starting something new or building on what you know, EduSpace gives you a friendly place to learn.</p>
            <ul class="check-list">
                <li>Flexible learning at your own pace</li>
                <li>Lessons organized into clear sections</li>
                <li>Track your progress in one place</li>
                <li>Learn from experienced instructors</li>
            </ul>
            <a class="btn btn-primary" href="register.php">Join our community</a>
        </div>
    </div>
</section>
<section class="cta wrap">
    <div>
        <span class="eyebrow">READY TO BEGIN?</span>
        <h2>Your next chapter starts today.</h2>
        <p>Choose a course and take your first step.</p>
    </div>
    <a class="btn btn-white" href="register.php">Create free account →</a>
</section>
</main>
<footer class="site-footer">
    <div class="wrap footer-inner">
        <a class="brand" href="index.php">
            <span class="brand-mark">E</span> EduSpace</a>
            <p>Learn with purpose. Grow with confidence.</p>
            <div class="footer-links">
                <a href="#about">About</a>
                <a href="#courses">Courses</a>
                <a href="login.php">Login</a>
            </div>
            <small>© 2026 EduSpace. All rights reserved.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/app.js"></script></body>
    </html>