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
  <title>Create Payment | EduSpace</title>
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
          <h1>Payment Entry Form</h1>
          <nav><ol class="breadcrumb"><li class="breadcrumb-item"><a href="../../admin/admin_dashboard.php">Dashboard</a></li><li class="breadcrumb-item"><a href="index.php">Payments</a></li><li class="breadcrumb-item active">Create</li></ol></nav>
        </div>
        <a href="index.php" class="btn btn-light border"><i class="bi bi-arrow-left me-1"></i> Back</a>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <div class="card-jp">
            <div class="card-header">Payment Entry Form</div>
            <div class="card-body">

              <?php
              $old = [];

              if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                  try {
                    $s_enrollment_id = sqlv($conn, $_POST['enrollment_id'] ?? '', false, null);
                    $s_transaction_ref = sqlv($conn, $_POST['transaction_ref'] ?? '', true, null);
                    $s_amount = sqlv($conn, $_POST['amount'] ?? '', false, null);
                    $s_currency = sqlv($conn, $_POST['currency'] ?? '', false, 'BDT');
                    $s_payment_method = sqlv($conn, $_POST['payment_method'] ?? '', true, null);
                    $s_payment_status = sqlv($conn, $_POST['payment_status'] ?? '', false, 'pending');
                    $s_paid_at = sqlv($conn, str_replace('T', ' ', $_POST['paid_at'] ?? ''), true, null);

                    $sql = "INSERT INTO payments (enrollment_id, transaction_ref, amount, currency, payment_method, payment_status, paid_at)
                            VALUES ($s_enrollment_id, $s_transaction_ref, $s_amount, $s_currency, $s_payment_method, $s_payment_status, $s_paid_at)";

                    $conn->query($sql);

                    echo "<div class='alert alert-success border-0 rounded-3'>Payment Created Successfully</div>";
                  } catch (mysqli_sql_exception $ex) {
                    $old = $_POST;
                    echo "<div class='alert alert-danger border-0 rounded-3'>Error: " . e($ex->getMessage()) . "</div>";
                  }
              }

              $fk_enrollment_id = $conn->query("SELECT e.id AS v, CONCAT('#', e.id, ' - ', COALESCE(NULLIF(u.name,''), u.email), ' / ', c.title) AS l FROM enrollments e JOIN users u ON u.id = e.student_id JOIN courses c ON c.id = e.course_id ORDER BY e.id DESC");
              ?>

              <form action="" method="post">
                <div class="mb-3">
                  <label class="form-label">Enrollment</label>
                  <select name="enrollment_id" class="form-select" required>
                    <option value="">-- Select Enrollment --</option>
                    <?php while ($o = $fk_enrollment_id->fetch_assoc()) { ?>
                      <option value="<?php echo $o['v']; ?>" <?php echo (($old['enrollment_id'] ?? '') == $o['v']) ? 'selected' : ''; ?>><?php echo e($o['l']); ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Transaction Ref</label>
                  <input type="text" name="transaction_ref" class="form-control" placeholder="Enter Transaction Ref" value="<?php echo e($old['transaction_ref'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Amount</label>
                  <input type="number" name="amount" class="form-control" step="0.01" value="<?php echo e($old['amount'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Currency</label>
                  <input type="text" name="currency" class="form-control" placeholder="Enter Currency" value="<?php echo e($old['currency'] ?? 'BDT'); ?>" required>
                </div>

                <div class="mb-3">
                  <label class="form-label">Payment Method (bKash, Nagad, Card...)</label>
                  <input type="text" name="payment_method" class="form-control" placeholder="Enter Payment Method (bKash, Nagad, Card...)" value="<?php echo e($old['payment_method'] ?? ''); ?>">
                </div>

                <div class="mb-3">
                  <label class="form-label">Payment Status</label>
                  <select name="payment_status" class="form-select" required>
                    <option value="pending" <?php echo (($old['payment_status'] ?? 'pending') == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="paid" <?php echo (($old['payment_status'] ?? 'pending') == 'paid') ? 'selected' : ''; ?>>Paid</option>
                    <option value="failed" <?php echo (($old['payment_status'] ?? 'pending') == 'failed') ? 'selected' : ''; ?>>Failed</option>
                    <option value="refunded" <?php echo (($old['payment_status'] ?? 'pending') == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label">Paid At</label>
                  <input type="datetime-local" name="paid_at" class="form-control" value="<?php echo !empty($old['paid_at']) ? e(str_replace(' ', 'T', substr($old['paid_at'], 0, 16))) : ''; ?>">
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
