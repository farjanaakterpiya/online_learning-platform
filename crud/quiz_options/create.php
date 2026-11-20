<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['email']) || ($_SESSION['role'] ?? '') != 'admin') {
    header("Location: ../../login.php");
    exit;
}
include_once("../../db_config.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function e($v) { return htmlspecialchars((string)$v); }
function sqlv($conn, $v, $nullable = false, $default = null) {
    $v = trim((string)$v);
    if ($v === '') {
        if ($nullable) return 'NULL';
        if ($default !== null) return "'" . $conn->real_escape_string($default) . "'";
    }
    return "'" . $conn->real_escape_string($v) . "'";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Option | EduSpace</title>
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
          <h1>Option Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Quiz Options</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Option Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_question_id = sqlv($conn, $_POST['question_id'] ?? '', false, null);
                    $s_option_text = sqlv($conn, $_POST['option_text'] ?? '', false, null);
                    $s_is_correct = isset($_POST['is_correct']) ? 1 : 0;
                    $s_sort_order = sqlv($conn, $_POST['sort_order'] ?? '', false, '0');

                    $sql = "INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order)
                            VALUES ($s_question_id, $s_option_text, $s_is_correct, $s_sort_order)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Option Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              $fk_question_id = $conn->query("SELECT id AS v, LEFT(question_text, 80) AS l FROM questions ORDER BY id DESC");
              ?>

              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Question</label>
                  <select name="question_id" class="form-select" required>
                    <option value="">-- Select Question --</option>
                    <?php while ($o = $fk_question_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['question_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Option Text</label>
                  <textarea name="option_text" rows="2" class="form-control" placeholder="Enter Option Text" required><?php echo e($old['option_text'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" name="is_correct" id="is_correct" value="1" class="form-check-input"
                  <?php echo !empty($old['is_correct']) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_correct">This is the correct answer</label>
                </div>

                <div class="mb-3">
                  <label class="form-label">Sort Order</label>
                  <input type="number" name="sort_order" class="form-control" value="<?php echo e($old['sort_order'] ?? '0'); ?>" required>
                </div>

                <input type="submit" name="submit" value="SAVE" class="btn btn-primary">
              </form>

            </div>
          </div>
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
