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
  <title>Create Lesson | EduSpace</title>
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
          <h1>Lesson Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Lessons</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Lesson Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_section_id = sqlv($conn, $_POST['section_id'] ?? '', false, null);
                    $s_title = sqlv($conn, $_POST['title'] ?? '', false, null);
                    $s_lesson_type = sqlv($conn, $_POST['lesson_type'] ?? '', false, 'video');
                    $s_content = sqlv($conn, $_POST['content'] ?? '', true, null);
                    $s_resource_url = sqlv($conn, $_POST['resource_url'] ?? '', true, null);
                    $s_duration_minutes = sqlv($conn, $_POST['duration_minutes'] ?? '', false, '0');
                    $s_is_preview = isset($_POST['is_preview']) ? 1 : 0;
                    $s_sort_order = sqlv($conn, $_POST['sort_order'] ?? '', false, '0');

                    $sql = "INSERT INTO lessons (section_id, title, lesson_type, content, resource_url, duration_minutes, is_preview, sort_order)
                            VALUES ($s_section_id, $s_title, $s_lesson_type, $s_content, $s_resource_url, $s_duration_minutes, $s_is_preview, $s_sort_order)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Lesson Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              $fk_section_id = $conn->query("SELECT s.id AS v, CONCAT(c.title, ' > ', s.title) AS l FROM course_sections s JOIN courses c ON c.id = s.course_id ORDER BY c.title, s.sort_order");
              ?>

              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Section</label>
                  <select name="section_id" class="form-select" required>
                    <option value="">-- Select Section --</option>
                    <?php while ($o = $fk_section_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['section_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Lesson Title</label>
                  <input type="text" name="title" class="form-control" placeholder="Enter Lesson Title" value="<?php echo e($old['title'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Lesson Type</label>
                  <select name="lesson_type" class="form-select" required>
                    <option value="video" <?php echo (($old['lesson_type'] ?? 'video') == 'video') ? 'selected' : ''; ?>>Video</option>
                    <option value="article" <?php echo (($old['lesson_type'] ?? 'video') == 'article') ? 'selected' : ''; ?>>Article</option>
                    <option value="file" <?php echo (($old['lesson_type'] ?? 'video') == 'file') ? 'selected' : ''; ?>>File</option>
                    <option value="link" <?php echo (($old['lesson_type'] ?? 'video') == 'link') ? 'selected' : ''; ?>>Link</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Content</label>
                  <textarea name="content" rows="6" class="form-control" placeholder="Enter Content"><?php echo e($old['content'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Resource URL</label>
                  <input type="url" name="resource_url" class="form-control" placeholder="Enter Resource URL" value="<?php echo e($old['resource_url'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Duration (minutes)</label>
                  <input type="number" name="duration_minutes" class="form-control" value="<?php echo e($old['duration_minutes'] ?? '0'); ?>" required>
                </div>

                <div class="mb-3 form-check">
                  <input type="checkbox" name="is_preview" id="is_preview" value="1" class="form-check-input"
                  <?php echo !empty($old['is_preview']) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="is_preview">Free preview lesson</label>
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
