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
  <title>Live Class Update | EduSpace</title>
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
          <h1>Live Class Update Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Live Classes</a></li><li class="breadcrumb-item active">Update</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back to Live Class List</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Live Class Update Form</div>
            <div class="card-body">

              <?php
                  $id = (int)($_GET['id'] ?? 0);

                  // Update Record
                  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                      try {
                    $s_course_id = sqlv($conn, $_POST['course_id'] ?? '', false, null);
                    $s_instructor_id = sqlv($conn, $_POST['instructor_id'] ?? '', false, null);
                    $s_title = sqlv($conn, $_POST['title'] ?? '', false, null);
                    $s_meeting_url = sqlv($conn, $_POST['meeting_url'] ?? '', false, null);
                    $s_starts_at = sqlv($conn, str_replace('T', ' ', $_POST['starts_at'] ?? ''), false, null);
                    $s_ends_at = sqlv($conn, str_replace('T', ' ', $_POST['ends_at'] ?? ''), true, null);
                    $s_status = sqlv($conn, $_POST['status'] ?? '', false, 'scheduled');
                    $sets = ["course_id = $s_course_id", "instructor_id = $s_instructor_id", "title = $s_title", "meeting_url = $s_meeting_url", "starts_at = $s_starts_at", "ends_at = $s_ends_at", "status = $s_status"];

                    $conn->query("UPDATE live_classes SET " . implode(", ", $sets) . " WHERE id = $id");

                    echo "<div class='alert alert-success border-0 rounded-3'>Update Success</div>";
                      } catch (mysqli_sql_exception $ex) {
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                      }
                  }

                  // Query for select one record
                  $data = $conn->query("SELECT * FROM live_classes WHERE id = $id");
                  $old = $data->fetch_assoc();
                  $fk_course_id = $conn->query("SELECT id AS v, title AS l FROM courses ORDER BY title");
$fk_instructor_id = $conn->query("SELECT id AS v, CONCAT_WS(' - ', NULLIF(name,''), email) AS l FROM users WHERE role = 'instructor' ORDER BY email");
                  if (!$old) {
                      echo "<div class='alert alert-warning border-0 rounded-3'>Record not found.</div>";
                  }
              ?>

              <?php if ($old) { ?>
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
                  <label class="form-label">Instructor</label>
                  <select name="instructor_id" class="form-select" required>
                    <option value="">-- Select Instructor --</option>
                    <?php while ($o = $fk_instructor_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['instructor_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Class Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Enter Class Title" value="<?php echo e($old['title'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Meeting URL</label>
                  <input type="url" name="meeting_url" class="form-control" placeholder="Enter Meeting URL" value="<?php echo e($old['meeting_url'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Starts At</label>
                  <input type="datetime-local" name="starts_at" class="form-control" value="<?php echo !empty($old['starts_at']) ? e(str_replace(' ', 'T', substr($old['starts_at'], 0, 16))) : ''; ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Ends At</label>
                  <input type="datetime-local" name="ends_at" class="form-control" value="<?php echo !empty($old['ends_at']) ? e(str_replace(' ', 'T', substr($old['ends_at'], 0, 16))) : ''; ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                    <option value="scheduled" <?php echo (($old['status'] ?? 'scheduled') == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="live" <?php echo (($old['status'] ?? 'scheduled') == 'live') ? 'selected' : ''; ?>>Live</option>
                    <option value="completed" <?php echo (($old['status'] ?? 'scheduled') == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo (($old['status'] ?? 'scheduled') == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
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
