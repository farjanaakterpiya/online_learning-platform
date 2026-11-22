<?php
session_start();
if (!isset($_SESSION['email']) || !in_array($_SESSION['role'] ?? '', ['admin', 'instructor'], true)) {
    header("Location: ../../login.php");
    exit;
}
include_once("../../db_config.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
function course_escape($value) { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
$role = $_SESSION['role'];
$currentUser = null;
if ($role === 'instructor') {
    $identity = $conn->prepare("SELECT id, name, email FROM users WHERE email=? AND role='instructor' LIMIT 1");
    $identity->bind_param("s", $_SESSION['email']);
    $identity->execute();
    $currentUser = $identity->get_result()->fetch_assoc();
    if (!$currentUser) {
        header("Location: ../../login.php");
        exit;
    }
}
$old = [];
$error = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST;
    $instructorId = $role === 'instructor' ? (int) $currentUser['id'] : (int) ($_POST['instructor_id'] ?? 0);
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slugInput = trim($_POST['slug'] ?? '');
    if ($slugInput === '') {
        $slugInput = $title;
    }
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $slugInput), '-'));
    $shortDescription = trim($_POST['short_description'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $thumbnail = trim($_POST['thumbnail'] ?? '');
    $level = $_POST['level'] ?? 'beginner';
    $price = (float) ($_POST['price'] ?? 0);
    $language = trim($_POST['language'] ?? 'English');
    $status = $_POST['status'] ?? 'draft';

    if ($title === '' || $slug === '' || $instructorId < 1 || $categoryId < 1) {
        $error = 'Title, slug, instructor, and category are required.';
    } elseif (!in_array($level, ['beginner', 'intermediate', 'advanced'], true) || !in_array($status, ['draft', 'published', 'archived'], true) || $price < 0) {
        $error = 'Choose valid course settings and a non-negative price.';
    } else {
        try {
            $stmt = $conn->prepare(
                "INSERT INTO courses (instructor_id, category_id, title, slug, short_description, description, thumbnail, level, price, language, status)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param("iissssssdss", $instructorId, $categoryId, $title, $slug, $shortDescription, $description, $thumbnail, $level, $price, $language, $status);
            $stmt->execute();
            $success = true;
            $old = [];
        } catch (mysqli_sql_exception $exception) {
            $error = $exception->getCode() === 1062 ? 'That course slug already exists.' : 'Could not save the course.';
            error_log($exception->getMessage());
        }
    }
}
$instructors = $role === 'admin' ? $conn->query("SELECT id, COALESCE(NULLIF(name, ''), email) AS label FROM users WHERE role='instructor' ORDER BY label") : null;
$categories = $conn->query("SELECT id, name FROM categories ORDER BY name");
?>
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Create Course | EduSpace</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="../../assets/css/admin.css?v=3" rel="stylesheet"></head>
<body class="dash-body"><div class="dash-wrapper">
  <aside class="sidebar offcanvas-lg offcanvas-start" tabindex="-1" id="sidebar"><?php include_once(__DIR__ . "/../../includes/admin_sidebar.php"); ?></aside>
  <div class="dash-main"><?php include __DIR__ . '/../../includes/app_topbar.php'; ?>
  <main class="dash-content">
    <div class="d-flex justify-content-between align-items-center mb-4"><div><h1>Create Course</h1><p class="text-muted mb-0">Add a course to the catalog.</p></div><a href="index.php" class="btn btn-light border">Back to courses</a></div>
    <?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo course_escape($error); ?></div><?php elseif ($success): ?><div class="alert alert-success">Course created successfully.</div><?php endif; ?>
    <div class="card-jp"><div class="card-body"><form method="post">
      <div class="row g-3">
        <div class="col-md-8"><label class="form-label">Title</label><input name="title" maxlength="200" class="form-control" value="<?php echo course_escape($old['title'] ?? ''); ?>" required></div>
        <div class="col-md-4"><label class="form-label">Slug</label><input name="slug" maxlength="220" class="form-control" value="<?php echo course_escape($old['slug'] ?? ''); ?>" placeholder="Generated from title if empty"></div>
        <div class="col-md-6"><label class="form-label">Instructor</label>
          <?php if ($role === 'admin'): ?><select name="instructor_id" class="form-select" required><option value="">Select instructor</option><?php while ($row = $instructors->fetch_assoc()): ?><option value="<?php echo (int) $row['id']; ?>" <?php echo (($old['instructor_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>><?php echo course_escape($row['label']); ?></option><?php endwhile; ?></select>
          <?php else: ?><input class="form-control" value="<?php echo course_escape($currentUser['name'] ?: $currentUser['email']); ?>" readonly><?php endif; ?>
        </div>
        <div class="col-md-6"><label class="form-label">Category</label><select name="category_id" class="form-select" required><option value="">Select category</option><?php while ($row = $categories->fetch_assoc()): ?><option value="<?php echo (int) $row['id']; ?>" <?php echo (($old['category_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>><?php echo course_escape($row['name']); ?></option><?php endwhile; ?></select></div>
        <div class="col-12"><label class="form-label">Short description</label><input name="short_description" maxlength="500" class="form-control" value="<?php echo course_escape($old['short_description'] ?? ''); ?>"></div>
        <div class="col-12"><label class="form-label">Description</label><textarea name="description" rows="5" class="form-control"><?php echo course_escape($old['description'] ?? ''); ?></textarea></div>
        <div class="col-md-3"><label class="form-label">Level</label><select name="level" class="form-select"><?php foreach (['beginner','intermediate','advanced'] as $level): ?><option value="<?php echo $level; ?>" <?php echo ($old['level'] ?? 'beginner') === $level ? 'selected' : ''; ?>><?php echo ucfirst($level); ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><label class="form-label">Price</label><input name="price" type="number" min="0" step="0.01" value="<?php echo course_escape($old['price'] ?? '0.00'); ?>" class="form-control" required></div>
        <div class="col-md-3"><label class="form-label">Language</label><input name="language" maxlength="60" value="<?php echo course_escape($old['language'] ?? 'English'); ?>" class="form-control"></div>
        <div class="col-md-3"><label class="form-label">Status</label><select name="status" class="form-select"><?php foreach (['draft','published','archived'] as $status): ?><option value="<?php echo $status; ?>" <?php echo ($old['status'] ?? 'draft') === $status ? 'selected' : ''; ?>><?php echo ucfirst($status); ?></option><?php endforeach; ?></select></div>
        <div class="col-12"><label class="form-label">Thumbnail URL</label><input name="thumbnail" maxlength="255" value="<?php echo course_escape($old['thumbnail'] ?? ''); ?>" class="form-control"></div>
      </div>
      <button type="submit" class="btn btn-primary mt-4">Save Course</button>
    </form></div></div>
  </main></div>
</div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html>
