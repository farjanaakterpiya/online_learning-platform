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
  <title>Create Quiz | EduSpace</title>
  <!-- Google Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="../../assets/css/admin.css?v=3" rel="stylesheet">
</head>
<body class="dash-body">
<div class="dash-wrapper">

  <!-- ===================== SIDEBAR (Admin) ===================== -->
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar"><?php include_once(__DIR__ . "/../../includes/admin_sidebar.php"); ?></aside>

  <!-- ===================== MAIN ===================== -->
  <div class="dash-main">

    <!-- Topbar -->
    <?php include __DIR__ . '/../../includes/app_topbar.php'; ?>

    <!-- Content -->
    <main class="dash-content">

      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 page-title">
        <div>
          <h1>Quiz Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Quizzes</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Quiz Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_course_id = sqlv($conn, $_POST['course_id'] ?? '', false, null);
                    $s_title = sqlv($conn, $_POST['title'] ?? '', false, null);
                    $s_description = sqlv($conn, $_POST['description'] ?? '', true, null);
                    $s_pass_mark = sqlv($conn, $_POST['pass_mark'] ?? '', false, '50.00');
                    $s_time_limit_minutes = sqlv($conn, $_POST['time_limit_minutes'] ?? '', true, null);
                    $s_status = sqlv($conn, $_POST['status'] ?? '', false, 'draft');

                    $sql = "INSERT INTO quizzes (course_id, title, description, pass_mark, time_limit_minutes, status)
                            VALUES ($s_course_id, $s_title, $s_description, $s_pass_mark, $s_time_limit_minutes, $s_status)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Quiz Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              $fk_course_id = $conn->query("SELECT id AS v, title AS l FROM courses ORDER BY title");
              ?>

              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Course</label>
                  <select name="course_id" class="form-select" required>
                    <option value="">-- Select Course --</option>
                    <?php while ($o = $fk_course_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['course_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Quiz Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Enter Quiz Title" value="<?php echo e($old['title'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea name="description" rows="4" class="form-control" placeholder="Enter Description"><?php echo e($old['description'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Pass Mark (%)</label>
                  <input type="number" name="pass_mark" class="form-control" step="0.01" value="<?php echo e($old['pass_mark'] ?? '50.00'); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Time Limit (minutes)</label>
                  <input type="number" name="time_limit_minutes" class="form-control" value="<?php echo e($old['time_limit_minutes'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                    <option value="draft" <?php echo (($old['status'] ?? 'draft') == 'draft') ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo (($old['status'] ?? 'draft') == 'published') ? 'selected' : ''; ?>>Published</option>
                  </select>
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
