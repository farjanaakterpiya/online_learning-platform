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
  <title>Enrollment List | EduSpace</title>
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
          <h1>Enrollment List</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item active">Enrollments</li></ol></nav>
        </div>
        <a class="btn btn-primary" href="create.php"><i class="bi bi-plus-lg me-1"></i> New Entry</a>
      </div>

      <?php if ($msg == 'deleted') { ?>
        <div class="alert alert-success border-0 rounded-3">Record deleted successfully.</div>
      <?php } elseif ($msg == 'error') { ?>
        <div class="alert alert-danger border-0 rounded-3">Could not delete this record. It may be used by other data (foreign key).</div>
      <?php } ?>

      <?php
          $rawData = $conn->query("SELECT e.*, COALESCE(NULLIF(u.name,''), u.email) AS student_name, c.title AS course_title FROM enrollments e LEFT JOIN users u ON u.id = e.student_id LEFT JOIN courses c ON c.id = e.course_id ORDER BY e.id DESC");
      ?>

      <div class="card-jp">
        <div class="table-responsive">
          <table class="table table-jp">
            <thead>
              <tr>
                <th>ID</th>
                <th>Student</th>
                <th>Course</th>
                <th>Status</th>
                <th>Progress</th>
                <th>Enrolled</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody>
            <?php
                while ($row = $rawData->fetch_assoc()) {
            ?>
              <tr>
                <td><?php echo e($row['id'] ?? ''); ?></td>
                <td><strong><?php echo e($row['student_name'] ?? ''); ?></strong></td>
                <td><?php echo e($row['course_title'] ?? ''); ?></td>
                <td><span class="badge badge-soft-secondary"><?php echo e($row['status'] ?? ''); ?></span></td>
                <td><?php echo e($row['progress_percent'] ?? ''); ?>%</td>
                <td><?php echo e($row['enrolled_at'] ?? ''); ?></td>

                <td class="text-end text-nowrap">
                  <a class="btn btn-sm btn-soft-primary" title="Edit Enrollment"
                  href="edit.php?id=<?php echo $row['id']; ?>">
                    <i class="bi bi-pencil"></i> Edit
                  </a>

                  <a onclick="return confirm('Are you sure to delete')"
                  class="btn btn-sm btn-outline-danger" title="Delete Enrollment"
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
