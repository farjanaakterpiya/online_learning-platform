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
function make_slug($text) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $text), '-'));
    return $slug;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Create Category | EduSpace</title>
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
          <h1>Category Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Categories</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Category Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_name = sqlv($conn, $_POST['name'] ?? '', false, null);
                    $s_slug = "'" . $conn->real_escape_string(make_slug($_POST['name'])) . "'";
                    $s_description = sqlv($conn, $_POST['description'] ?? '', true, null);
                    $s_status = sqlv($conn, $_POST['status'] ?? '', false, 'active');

                    $sql = "INSERT INTO categories (name, slug, description, status)
                            VALUES ($s_name, $s_slug, $s_description, $s_status)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Category Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              
              ?>

              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Category Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter Category Name" value="<?php echo e($old['name'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea name="description" rows="4" class="form-control" placeholder="Enter Description"><?php echo e($old['description'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Status</label>
                  <select name="status" class="form-select" required>
                    <option value="active" <?php echo (($old['status'] ?? 'active') == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo (($old['status'] ?? 'active') == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
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
