<?php
session_start();
if (!isset($_SESSION['email']) || !in_array($_SESSION['role'] ?? '', ['admin', 'instructor'], true)) {
    header("Location: ../../login.php");
    exit;
}
include_once("../../db_config.php");
function course_escape($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
$message = $_GET['msg'] ?? '';
$role = $_SESSION['role'];
$query = "SELECT c.id, c.title, c.status, c.price, c.slug,
                 COALESCE(NULLIF(u.name, ''), u.email) AS instructor_name,
                 cat.name AS category_name
          FROM courses c
          JOIN users u ON u.id = c.instructor_id
          JOIN categories cat ON cat.id = c.category_id";
if ($role === 'instructor') {
    $currentUser = $conn->prepare("SELECT id FROM users WHERE email=? AND role='instructor' LIMIT 1");
    $currentUser->bind_param("s", $_SESSION['email']);
    $currentUser->execute();
    $user = $currentUser->get_result()->fetch_assoc();
    if (!$user) {
        header("Location: ../../login.php");
        exit;
    }
    $instructorId = (int) $user['id'];
    $query .= " WHERE c.instructor_id = $instructorId";
}
$courses = $conn->query($query . " ORDER BY c.id DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Courses | EduSpace</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link href="../../assets/css/admin.css" rel="stylesheet">
</head>
<body class="dash-body">
<div class="dash-wrapper">
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar"><?php include_once(__DIR__ . "/../../includes/admin_sidebar.php"); ?></aside>
  <div class="dash-main">
    <header class="topbar"><button class="topbar-btn d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar"><i class="bi bi-list"></i></button></header>
    <main class="dash-content">
      <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 page-title">
        <div><h1>Courses</h1><p class="text-muted mb-0">Manage <?php echo $role === 'admin' ? 'course catalog entries.' : 'your courses.'; ?></p></div>
        <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> New Course</a>
      </div>
      <?php if ($message === 'deleted'): ?><div class="alert alert-success">Course deleted successfully.</div><?php elseif ($message === 'error'): ?><div class="alert alert-danger">Could not delete the course because it is referenced by other records.</div><?php elseif ($message === 'notfound'): ?><div class="alert alert-warning">Course not found or you do not have permission to manage it.</div><?php endif; ?>
      <div class="card-jp"><div class="table-responsive">
        <table class="table table-jp">
          <thead><tr><th>ID</th><th>Title</th><th>Instructor</th><th>Category</th><th>Status</th><th>Price</th><th class="text-end">Actions</th></tr></thead>
          <tbody>
          <?php if ($courses->num_rows): while ($course = $courses->fetch_assoc()): ?>
            <tr>
              <td><?php echo (int) $course['id']; ?></td>
              <td><strong><?php echo course_escape($course['title']); ?></strong><br><small class="text-muted"><?php echo course_escape($course['slug']); ?></small></td>
              <td><?php echo course_escape($course['instructor_name']); ?></td>
              <td><?php echo course_escape($course['category_name']); ?></td>
              <td><span class="badge badge-soft-secondary"><?php echo course_escape(ucfirst($course['status'])); ?></span></td>
              <td><?php echo course_escape(number_format((float) $course['price'], 2)); ?></td>
              <td class="text-end text-nowrap">
                <a class="btn btn-sm btn-soft-primary" href="edit.php?id=<?php echo (int) $course['id']; ?>"><i class="bi bi-pencil"></i> Edit</a>
                <a class="btn btn-sm btn-outline-danger" href="delete.php?id=<?php echo (int) $course['id']; ?>" onclick="return confirm('Delete this course?')"><i class="bi bi-trash"></i> Delete</a>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="7" class="text-center text-muted py-4">No courses found.</td></tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div></div>
    </main>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
