<?php
if (!isset($sidebarTemplateType) || !in_array($sidebarTemplateType, ['dashboard', 'admin'], true)) {
    throw new LogicException('Invalid sidebar template type.');
}

if ($sidebarTemplateType === 'dashboard'):
    $role = $_SESSION['role'] ?? '';
    $currentPage = basename($_SERVER['SCRIPT_NAME'] ?? '');
    $studentDashboardActive = $currentPage === 'student_dashboard.php';
    $studentCoursesActive = in_array($currentPage, ['my_courses.php', 'learn.php', 'quiz.php', 'live.php'], true);
    $studentCatalogActive = in_array($currentPage, ['courses.php', 'course.php', 'enroll.php'], true);
    $studentCertificatesActive = in_array($currentPage, ['certificates.php', 'certificate.php'], true);
    $studentWishlistActive = $currentPage === 'wishlist.php';
    $studentReviewsActive = $currentPage === 'reviews.php';
    $studentProfileActive = $currentPage === 'profile.php';
?>
<aside class="sidebar" id="dashboard-sidebar" aria-label="Dashboard navigation">
    <div class="side-label">WORKSPACE</div>

    <?php if ($role === 'admin'): ?>
        <a class="side-link active" href="../admin/admin_dashboard.php" aria-current="page">▦ <span>Dashboard</span></a>
        <a class="side-link" href="../crud/users/index.php">♙ <span>Users</span></a>
        <a class="side-link" href="../crud/categories/index.php">▤ <span>Categories</span></a>
        <a class="side-link" href="../crud/courses/index.php">▣ <span>Courses</span></a>
        <a class="side-link" href="../crud/course_sections/index.php">▣ <span>Course sections</span></a>
        <a class="side-link" href="../crud/lessons/index.php">▧ <span>Lessons</span></a>
        <a class="side-link" href="../crud/enrollments/index.php">✉ <span>Enrollments</span></a>
        <a class="side-link" href="../crud/certificates/index.php">✓ <span>Certificates</span></a>
        <a class="side-link" href="../crud/course_reviews/index.php">★ <span>Reviews</span></a>
        <a class="side-link" href="../crud/wishlists/index.php">♥ <span>Wishlists</span></a>
        <a class="side-link" href="../crud/quizzes/index.php">? <span>Quizzes</span></a>
        <a class="side-link" href="../crud/questions/index.php">? <span>Questions</span></a>
        <a class="side-link" href="../crud/quiz_options/index.php">◎ <span>Quiz options</span></a>
        <a class="side-link" href="../crud/quiz_attempts/index.php">✓ <span>Quiz attempts</span></a>
        <a class="side-link" href="../crud/live_classes/index.php">● <span>Live classes</span></a>
        <a class="side-link" href="../crud/payments/index.php">¤ <span>Payments</span></a>
    <?php elseif ($role === 'instructor'): ?>
        <a class="side-link active" href="../admin/instructor_dashboard.php" aria-current="page">▦ <span>Dashboard</span></a>
        <a class="side-link" href="../crud/courses/index.php">▣ <span>My courses</span></a>
    <?php else: ?>
        <a class="side-link <?php echo $studentDashboardActive ? 'active' : ''; ?>" href="../admin/student_dashboard.php" <?php echo $studentDashboardActive ? 'aria-current="page"' : ''; ?>>▦ <span>Dashboard</span></a>
        <a class="side-link <?php echo $studentCoursesActive ? 'active' : ''; ?>" href="../student/my_courses.php" <?php echo $studentCoursesActive ? 'aria-current="page"' : ''; ?>>▣ <span>My courses</span></a>
        <a class="side-link <?php echo $studentCatalogActive ? 'active' : ''; ?>" href="../student/courses.php" <?php echo $studentCatalogActive ? 'aria-current="page"' : ''; ?>>⌕ <span>Browse courses</span></a>
        <a class="side-link <?php echo $studentWishlistActive ? 'active' : ''; ?>" href="../student/wishlist.php" <?php echo $studentWishlistActive ? 'aria-current="page"' : ''; ?>>♥ <span>Wishlist</span></a>
        <a class="side-link <?php echo $studentReviewsActive ? 'active' : ''; ?>" href="../student/reviews.php" <?php echo $studentReviewsActive ? 'aria-current="page"' : ''; ?>>★ <span>My reviews</span></a>
        <a class="side-link <?php echo $studentCertificatesActive ? 'active' : ''; ?>" href="../student/certificates.php" <?php echo $studentCertificatesActive ? 'aria-current="page"' : ''; ?>>✓ <span>Certificates</span></a>
        <a class="side-link <?php echo $studentProfileActive ? 'active' : ''; ?>" href="../student/profile.php" <?php echo $studentProfileActive ? 'aria-current="page"' : ''; ?>>♙ <span>My profile</span></a>
    <?php endif; ?>

    <div class="side-bottom">
        <a class="side-link" href="../index.html">⌂ <span>Visit website</span></a>
    </div>
</aside>
<button class="sidebar-backdrop" type="button" aria-label="Close navigation" tabindex="-1"></button>
<?php
else:
    $module = basename(dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    $base = '../../';
    $sidebarActive = static function ($moduleName) use ($module) {
        return $moduleName === $module ? ' class="active" aria-current="page"' : '';
    };
?>
<div class="offcanvas-body">
  <div class="d-flex align-items-center justify-content-between">
    <a href="<?php echo $base; ?>index.html" class="sidebar-brand flex-grow-1">
      <span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>EduSpace
    </a>
    <button type="button" class="btn-close btn-close-white d-lg-none me-3" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
  </div>
  <script src="<?php echo $base; ?>assets/js/admin-sidebar.js?v=2" defer></script>

  <?php if (($_SESSION['role'] ?? '') === 'instructor'): ?>
  <div class="sidebar-heading">Workspace</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>admin/instructor_dashboard.php"<?php echo $sidebarActive('admin'); ?>><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
    <li><a href="<?php echo $base; ?>crud/courses/index.php"<?php echo $sidebarActive('courses'); ?>><i class="bi bi-journal-bookmark"></i> My Courses</a></li>
    <li><a href="<?php echo $base; ?>logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
  </ul>
  <?php else: ?>
  <div class="sidebar-heading">Main</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>admin/admin_dashboard.php"<?php echo $sidebarActive('admin'); ?>><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
  </ul>

  <div class="sidebar-heading">People</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>crud/users/index.php"<?php echo $sidebarActive('users'); ?>><i class="bi bi-people"></i> Users</a></li>
  </ul>

  <div class="sidebar-heading">Catalog</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>crud/categories/index.php"<?php echo $sidebarActive('categories'); ?>><i class="bi bi-tags"></i> Categories</a></li>
    <li><a href="<?php echo $base; ?>crud/courses/index.php"<?php echo $sidebarActive('courses'); ?>><i class="bi bi-journal-bookmark"></i> Courses</a></li>
    <li><a href="<?php echo $base; ?>crud/course_sections/index.php"<?php echo $sidebarActive('course_sections'); ?>><i class="bi bi-list-nested"></i> Sections</a></li>
    <li><a href="<?php echo $base; ?>crud/lessons/index.php"<?php echo $sidebarActive('lessons'); ?>><i class="bi bi-play-circle"></i> Lessons</a></li>
  </ul>

  <div class="sidebar-heading">Learning</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>crud/enrollments/index.php"<?php echo $sidebarActive('enrollments'); ?>><i class="bi bi-person-check"></i> Enrollments</a></li>
    <li><a href="<?php echo $base; ?>crud/certificates/index.php"<?php echo $sidebarActive('certificates'); ?>><i class="bi bi-award"></i> Certificates</a></li>
    <li><a href="<?php echo $base; ?>crud/course_reviews/index.php"<?php echo $sidebarActive('course_reviews'); ?>><i class="bi bi-star"></i> Reviews</a></li>
    <li><a href="<?php echo $base; ?>crud/wishlists/index.php"<?php echo $sidebarActive('wishlists'); ?>><i class="bi bi-heart"></i> Wishlists</a></li>
  </ul>

  <div class="sidebar-heading">Assessment</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>crud/quizzes/index.php"<?php echo $sidebarActive('quizzes'); ?>><i class="bi bi-patch-question"></i> Quizzes</a></li>
    <li><a href="<?php echo $base; ?>crud/questions/index.php"<?php echo $sidebarActive('questions'); ?>><i class="bi bi-question-circle"></i> Questions</a></li>
    <li><a href="<?php echo $base; ?>crud/quiz_options/index.php"<?php echo $sidebarActive('quiz_options'); ?>><i class="bi bi-ui-radios"></i> Quiz Options</a></li>
    <li><a href="<?php echo $base; ?>crud/quiz_attempts/index.php"<?php echo $sidebarActive('quiz_attempts'); ?>><i class="bi bi-clipboard-check"></i> Quiz Attempts</a></li>
  </ul>

  <div class="sidebar-heading">Live & Billing</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>crud/live_classes/index.php"<?php echo $sidebarActive('live_classes'); ?>><i class="bi bi-camera-video"></i> Live Classes</a></li>
    <li><a href="<?php echo $base; ?>crud/payments/index.php"<?php echo $sidebarActive('payments'); ?>><i class="bi bi-receipt"></i> Payments</a></li>
  </ul>

  <div class="sidebar-heading">System</div>
  <ul class="sidebar-nav">
    <li><a href="<?php echo $base; ?>logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
  </ul>
  <?php endif; ?>
</div>
<?php endif; ?>
