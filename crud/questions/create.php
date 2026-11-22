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
  <title>Create Question | EduSpace</title>
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
          <h1>Question Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Questions</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Question Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_quiz_id = sqlv($conn, $_POST['quiz_id'] ?? '', false, null);
                    $s_question_text = sqlv($conn, $_POST['question_text'] ?? '', false, null);
                    $s_question_type = sqlv($conn, $_POST['question_type'] ?? '', false, 'mcq');
                    $s_marks = sqlv($conn, $_POST['marks'] ?? '', false, '1.00');
                    $s_sort_order = sqlv($conn, $_POST['sort_order'] ?? '', false, '0');

                    $sql = "INSERT INTO questions (quiz_id, question_text, question_type, marks, sort_order)
                            VALUES ($s_quiz_id, $s_question_text, $s_question_type, $s_marks, $s_sort_order)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Question Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              $fk_quiz_id = $conn->query("SELECT id AS v, title AS l FROM quizzes ORDER BY title");
              ?>

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
                  <label class="form-label">Question</label>
                  <textarea name="question_text" rows="4" class="form-control" placeholder="Enter Question" required><?php echo e($old['question_text'] ?? ''); ?></textarea>
                </div>

                <div class="mb-3">
                  <label class="form-label">Question Type</label>
                  <select name="question_type" class="form-select" required>
                    <option value="mcq" <?php echo (($old['question_type'] ?? 'mcq') == 'mcq') ? 'selected' : ''; ?>>Mcq</option>
                    <option value="true_false" <?php echo (($old['question_type'] ?? 'mcq') == 'true_false') ? 'selected' : ''; ?>>True False</option>
                    <option value="short_answer" <?php echo (($old['question_type'] ?? 'mcq') == 'short_answer') ? 'selected' : ''; ?>>Short Answer</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Marks</label>
                  <input type="number" name="marks" class="form-control" step="0.01" value="<?php echo e($old['marks'] ?? '1.00'); ?>" required>
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
