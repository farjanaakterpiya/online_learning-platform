<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$courses = db_all("SELECT id, title FROM courses WHERE instructor_id=? ORDER BY title", 'i', [$me['id']]);
$statuses = ['scheduled', 'live', 'completed', 'cancelled'];

function dt_in($v) { $v = trim((string) $v); return $v === '' ? null : str_replace('T', ' ', $v) . (strlen($v) === 16 ? ':00' : ''); }
function dt_out($v) { return $v ? str_replace(' ', 'T', substr($v, 0, 16)) : ''; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $a = $_POST['action'] ?? '';
    try {
        if ($a === 'add' || $a === 'edit') {
            $courseId = (int) ($_POST['course_id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $url = trim($_POST['meeting_url'] ?? '');
            $starts = dt_in($_POST['starts_at'] ?? '');
            $ends = dt_in($_POST['ends_at'] ?? '');
            $status = in_array($_POST['status'] ?? '', $statuses, true) ? $_POST['status'] : 'scheduled';
            if (!own_course($courseId, $me['id'])) {
                flash('Choose one of your own courses.', 'danger');
            } elseif ($title === '' || !safe_url($url) || !$starts) {
                flash('Title, a valid https:// meeting link and a start time are required.', 'danger');
            } elseif ($a === 'add') {
                db_run(
                    "INSERT INTO live_classes (course_id, instructor_id, title, meeting_url, starts_at, ends_at, status) VALUES (?, ?, ?, ?, ?, ?, ?)",
                    'iisssss', [$courseId, $me['id'], $title, $url, $starts, $ends, $status]
                );
                flash('Live class scheduled.');
            } else {
                db_run(
                    "UPDATE live_classes SET course_id=?, title=?, meeting_url=?, starts_at=?, ends_at=?, status=? WHERE id=? AND instructor_id=?",
                    'isssssii', [$courseId, $title, $url, $starts, $ends, $status, (int) ($_POST['id'] ?? 0), $me['id']]
                );
                flash('Live class updated.');
            }
        } elseif ($a === 'delete') {
            db_run("DELETE FROM live_classes WHERE id=? AND instructor_id=?", 'ii', [(int) ($_POST['id'] ?? 0), $me['id']]);
            flash('Live class deleted.', 'info');
        }
    } catch (mysqli_sql_exception $ex) {
        error_log($ex->getMessage());
        flash('Could not save the live class.', 'danger');
    }
    redirect('live_classes.php');
}

$rows = db_all(
    "SELECT lc.*, c.title AS course FROM live_classes lc JOIN courses c ON c.id=lc.course_id
     WHERE lc.instructor_id=? ORDER BY lc.starts_at DESC", 'i', [$me['id']]
);

function live_fields($courses, $statuses, $r)
{
    ?>
    <div class="row g-2">
        <div class="col-md-4"><select class="form-select" name="course_id" required><option value="">Course</option><?php foreach ($courses as $c): ?><option value="<?php echo (int) $c['id']; ?>" <?php echo (int) ($r['course_id'] ?? 0) === (int) $c['id'] ? 'selected' : ''; ?>><?php echo e($c['title']); ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><input class="form-control" name="title" placeholder="Class title" value="<?php echo e($r['title'] ?? ''); ?>" required maxlength="200"></div>
        <div class="col-md-4"><input class="form-control" name="meeting_url" placeholder="https://meet.google.com/..." value="<?php echo e($r['meeting_url'] ?? ''); ?>" required></div>
        <div class="col-md-4"><label class="page-note">Starts</label><input class="form-control" type="datetime-local" name="starts_at" value="<?php echo e(dt_out($r['starts_at'] ?? '')); ?>" required></div>
        <div class="col-md-4"><label class="page-note">Ends (optional)</label><input class="form-control" type="datetime-local" name="ends_at" value="<?php echo e(dt_out($r['ends_at'] ?? '')); ?>"></div>
        <div class="col-md-4"><label class="page-note">Status</label><select class="form-select" name="status"><?php foreach ($statuses as $s): ?><option value="<?php echo $s; ?>" <?php echo ($r['status'] ?? 'scheduled') === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option><?php endforeach; ?></select></div>
    </div>
    <?php
}

portal_start('Live classes');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">LIVE</span>
        <h1>Live classes</h1>
        <p>Schedule sessions; enrolled students see them automatically.</p>
    </div>
</section>
<?php flash_show(); ?>
<?php if (!$courses): ?>
    <section class="panel"><p class="page-note">Create a course first.</p></section>
<?php else: ?>
<section class="block-card">
    <h3>Schedule a class</h3>
    <form method="post" class="mt-2">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="add">
        <?php live_fields($courses, $statuses, []); ?>
        <button class="btn btn-primary btn-sm mt-2" type="submit">Schedule</button>
    </form>
</section>
<?php foreach ($rows as $r): ?>
<section class="block-card">
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div><h3><?php echo e($r['title']); ?> <span class="pill <?php echo $r['status'] === 'live' ? 'green' : ($r['status'] === 'cancelled' ? 'red' : 'orange'); ?>"><?php echo e($r['status']); ?></span></h3>
            <p class="page-note"><?php echo e($r['course']); ?> · <?php echo e($r['starts_at']); ?></p></div>
        <form method="post" onsubmit="return confirm('Delete this live class?')">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
            <button class="btn btn-danger-soft btn-sm" type="submit">Delete</button>
        </form>
    </div>
    <details class="edit-box">
        <summary>Edit</summary>
        <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" value="<?php echo (int) $r['id']; ?>">
            <?php live_fields($courses, $statuses, $r); ?>
            <button class="btn btn-light btn-sm mt-2" type="submit">Save</button>
        </form>
    </details>
</section>
<?php endforeach; ?>
<?php endif; ?>
<?php portal_end(); ?>
