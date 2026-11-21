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
  <title>Attempt Update | EduSpace</title>
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
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar"><?php include_once(__DIR__ . "/../../includes/admin_sidebar.php"); ?></aside>

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
          <h1>Attempt Update Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Quiz Attempts</a></li><li class="breadcrumb-item active">Update</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back to Attempt List</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Attempt Update Form</div>
            <div class="card-body">

              <?php
                  $id = (int)($_GET['id'] ?? 0);

                  // Update Record
                  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                      try {
                    $s_quiz_id = sqlv($conn, $_POST['quiz_id'] ?? '', false, null);
                    $s_student_id = sqlv($conn, $_POST['student_id'] ?? '', false, null);
                    $s_submitted_at = sqlv($conn, str_replace('T', ' ', $_POST['submitted_at'] ?? ''), true, null);
                    $s_score = sqlv($conn, $_POST['score'] ?? '', false, '0.00');
                    $s_total_marks = sqlv($conn, $_POST['total_marks'] ?? '', false, '0.00');
                    $s_status = sqlv($conn, $_POST['status'] ?? '', false, 'in_progress');
                    $sets = ["quiz_id = $s_quiz_id", "student_id = $s_student_id", "submitted_at = $s_submitted_at", "score = $s_score", "total_marks = $s_total_marks", "status = $s_status"];

                    $conn->query("UPDATE quiz_attempts SET " . implode(", ", $sets) . " WHERE id = $id");

                    echo "<div class='alert alert-success border-0 rounded-3'>Update Success</div>";
                      } catch (mysqli_sql_exception $ex) {
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                      }
                  }

                  // Query for select one record
                  $data = $conn->query("SELECT * FROM quiz_attempts WHERE id = $id");
                  $old = $data->fetch_assoc();
                  $fk_quiz_id = $conn->query("SELECT id AS v, title AS l FROM quizzes ORDER BY title");
$fk_student_id = $conn->query("SELECT id AS v, CONCAT_WS(' - ', NULLIF(name,''), email) AS l FROM users WHERE role = 'student' ORDER BY email");
                  if (!$old) {
                      echo "<div class='alert alert-warning border-0 rounded-3'>Record not found.</div>";
                  }
              ?>

              <?php if ($old) { ?>
              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Quiz</label>
                  <select name="quiz_id" class="form-select" required>
                    <option value="">-- Select Quiz --</option>
                    <?php while ($o = $fk_quiz_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['quiz_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Student</label>
                  <select name="student_id" class="form-select" required>
                    <option value="">-- Select Student --</option>
                    <?php while ($o = $fk_student_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['student_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Submitted At</label>
                  <input type="datetime-local" name="submitted_at" class="form-control" value="<?php echo !empty($old['submitted_at']) ? e(str_replace(' ', 'T', substr($old['submitted_at'], 0, 16))) : ''; ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Score</label>
                  <input type="number" name="score" class="form-control" step="0.01" value="<?php echo e($old['score'] ?? '0.00'); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Total Marks</label>
                  <input type="number" name="total_marks" class="form-control" step="0.01" value="<?php echo e($old['total_marks'] ?? '0.00'); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                    <option value="in_progress" <?php echo (($old['status'] ?? 'in_progress') == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                    <option value="submitted" <?php echo (($old['status'] ?? 'in_progress') == 'submitted') ? 'selected' : ''; ?>>Submitted</option>
                    <option value="graded" <?php echo (($old['status'] ?? 'in_progress') == 'graded') ? 'selected' : ''; ?>>Graded</option>
                  </select>
                </div>

                <input type="submit" name="submit" value="UPDATE" class="btn btn-primary">
              </form>
              <?php } ?>

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
