<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../../login.php");
    exit;
}
include_once("../../db_config.php");
function e($v) { return htmlspecialchars((string)$v); }
$msg = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Option List | EduSpace</title>
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../../assets/css/admin.css" rel="stylesheet">
</head>
<body class="dash-body">
<div class="dash-wrapper">

  <!-- ===================== SIDEBAR (Admin) ===================== -->
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar">
    <div class="offcanvas-body">
      <div class="d-flex align-items-center justify-content-between">
        <a href="../../index.html" class="sidebar-brand flex-grow-1"><span class="brand-icon"><i class="bi bi-mortarboard-fill"></i></span>EduSpace</a>
        <button type="button" class="btn-close btn-close-white d-lg-none me-3" data-bs-dismiss="offcanvas" data-bs-target="#sidebar"></button>
      </div>

      <div class="sidebar-heading">Main</div>
      <ul class="sidebar-nav">
        <li><a href="../../admin/admin_dashboard.php"><i class="bi bi-grid-1x2"></i> Dashboard</a></li>
      </ul>

      <div class="sidebar-heading">People</div>
      <ul class="sidebar-nav">
        <li><a href="../../crud/users/index.php"><i class="bi bi-people"></i> Users</a></li>
      </ul>

      <div class="sidebar-heading">Catalog</div>
      <ul class="sidebar-nav">
        <li><a href="../../crud/categories/index.php"><i class="bi bi-tags"></i> Categories</a></li>
        <li><a href="../../admin/courses/courses.php"><i class="bi bi-journal-bookmark"></i> Courses</a></li>
        <li><a href="../../crud/course_sections/index.php"><i class="bi bi-list-nested"></i> Sections</a></li>
        <li><a href="../../crud/lessons/index.php"><i class="bi bi-play-circle"></i> Lessons</a></li>
      </ul>

      <div class="sidebar-heading">Learning</div>
      <ul class="sidebar-nav">
        <li><a href="../../crud/enrollments/index.php"><i class="bi bi-person-check"></i> Enrollments</a></li>
        <li><a href="../../crud/certificates/index.php"><i class="bi bi-award"></i> Certificates</a></li>
        <li><a href="../../crud/course_reviews/index.php"><i class="bi bi-star"></i> Reviews</a></li>
        <li><a href="../../crud/wishlists/index.php"><i class="bi bi-heart"></i> Wishlists</a></li>
      </ul>

      <div class="sidebar-heading">Assessment</div>
      <ul class="sidebar-nav">
        <li><a href="../../crud/quizzes/index.php"><i class="bi bi-patch-question"></i> Quizzes</a></li>
        <li><a href="../../crud/questions/index.php"><i class="bi bi-question-circle"></i> Questions</a></li>
        <li><a href="../../crud/quiz_options/index.php" class="active"><i class="bi bi-ui-radios"></i> Quiz Options</a></li>
        <li><a href="../../crud/quiz_attempts/index.php"><i class="bi bi-clipboard-check"></i> Quiz Attempts</a></li>
      </ul>

      <div class="sidebar-heading">Live & Billing</div>
      <ul class="sidebar-nav">
        <li><a href="../../crud/live_classes/index.php"><i class="bi bi-camera-video"></i> Live Classes</a></li>
        <li><a href="../../crud/payments/index.php"><i class="bi bi-receipt"></i> Payments</a></li>
      </ul>

      <div class="sidebar-heading">System</div>
      <ul class="sidebar-nav">
        <li><a href="../../logout.php"><i class="bi bi-box-arrow-left"></i> Logout</a></li>
      </ul>

      <div class="sidebar-footer"></div>
    </div>
  </aside>

  <!-- ===================== MAIN ===================== -->
  <div class="dash-main">

    <!-- Topbar -->
    <header class="topbar">
      <button class="topbar-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button>
      <div class="ms-auto d-flex align-items-center gap-2">
        <a href="../../index.html" class="topbar-btn" title="View Website"><i class="bi bi-globe2"></i></a>

        <!-- User menu -->
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center gap-2 text-reset ms-1" data-bs-toggle="dropdown">
            <span class="avatar">AD</span>
            <span class="d-none d-sm-block lh-sm">
              <span class="fw-bold d-block">Admin</span>
              <small class="text-muted">Administrator</small>
            </span>
            <i class="bi bi-chevron-down small text-muted d-none d-sm-block"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 p-2">
            <li><a class="dropdown-item rounded-3" href="../../crud/users/index.php"><i class="bi bi-person me-2"></i>Users</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item rounded-3 text-danger" href="../../logout.php"><i class="bi bi-box-arrow-left me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Content -->
    <main class="dash-content">

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 page-title">
        <div>
          <h1>Option List</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item active">Quiz Options</li></ol></nav>
        </div>
        <a class="btn btn-primary" href="create.php"><i class="bi bi-plus-lg me-1"></i> New Entry</a>
      </div>

      <?php if ($msg == 'deleted') { ?>
        <div class="alert alert-success border-0 rounded-3">Record deleted successfully.</div>
      <?php } elseif ($msg == 'error') { ?>
        <div class="alert alert-danger border-0 rounded-3">Could not delete this record. It may be used by other data (foreign key).</div>
      <?php } ?>

      <?php
          $rawData = $conn->query("SELECT o.*, qs.question_text FROM quiz_options o LEFT JOIN questions qs ON qs.id = o.question_id ORDER BY o.question_id, o.sort_order");
      ?>

      <div class="card-jp">
        <div class="table-responsive">
          <table class="table table-jp">
            <thead>
              <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Option</th>
                <th>Correct</th>
                <th>Order</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
                while ($row = $rawData->fetch_assoc()) {
            ?>
              <tr>
                <td><?php echo e($row['id'] ?? ''); ?></td>
                <td><?php echo e(mb_strimwidth((string)($row['question_text'] ?? ''), 0, 60, '...')); ?></td>
                <td><?php echo e(mb_strimwidth((string)($row['option_text'] ?? ''), 0, 60, '...')); ?></td>
                <td><?php echo !empty($row['is_correct']) ? 'Yes' : 'No'; ?></td>
                <td><?php echo e($row['sort_order'] ?? ''); ?></td>

                <td class="text-end text-nowrap">
                  <a class="btn btn-sm btn-soft-primary" title="Edit Option"
                  href="edit.php?id=<?php echo $row['id']; ?>">
                    <i class="bi bi-pencil"></i> Edit
                  </a>

                  <a onclick="return confirm('Are you sure to delete')"
                  class="btn btn-sm btn-outline-danger" title="Delete Option"
                  href="delete.php?id=<?php echo $row['id']; ?>">
                    <i class="bi bi-trash"></i> Delete
                  </a>
                </td>
              </tr>
            <?php
                }
            ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <footer class="dash-footer d-flex flex-column flex-sm-row justify-content-between gap-1">
      <span>&copy; 2026 EduSpace. All rights reserved.</span>
      <span>Made with <i class="bi bi-heart-fill text-danger"></i> in Bangladesh</span>
    </footer>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
