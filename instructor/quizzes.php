<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$courses = db_all("SELECT id, title FROM courses WHERE instructor_id=? ORDER BY title", 'i', [$me['id']]);
$courseId = (int) ($_GET['course'] ?? $_POST['course'] ?? 0);
if (!$courseId && $courses) { $courseId = (int) $courses[0]['id']; }
$course = $courseId ? own_course($courseId, $me['id']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!$course) { flash('Choose one of your courses first.', 'warning'); redirect('quizzes.php'); }
    $a = $_POST['action'] ?? '';
    $own = function ($qid) use ($courseId) {
        return db_one("SELECT id FROM quizzes WHERE id=? AND course_id=?", 'ii', [(int) $qid, $courseId]);
    };
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $pass = min(100, max(0, (float) ($_POST['pass_mark'] ?? 50)));
    $limit = (int) ($_POST['time_limit_minutes'] ?? 0);
    $status = in_array($_POST['status'] ?? '', ['draft', 'published'], true) ? $_POST['status'] : 'draft';
    try {
        if ($a === 'add' || $a === 'edit') {
            if ($title === '') { flash('Quiz title is required.', 'danger'); redirect('quizzes.php?course=' . $courseId); }
            if ($a === 'add') {
                db_run(
                    "INSERT INTO quizzes (course_id, title, description, pass_mark, time_limit_minutes, status) VALUES (?, ?, ?, ?, NULLIF(?,0), ?)",
                    'issdis', [$courseId, $title, $desc, $pass, $limit, $status]
                );
                flash('Quiz created. Now add questions to it.');
            } elseif ($own($_POST['quiz_id'] ?? 0)) {
                db_run(
                    "UPDATE quizzes SET title=?, description=?, pass_mark=?, time_limit_minutes=NULLIF(?,0), status=? WHERE id=?",
                    'ssdisi', [$title, $desc, $pass, $limit, $status, (int) $_POST['quiz_id']]
                );
                flash('Quiz updated.');
            }
        } elseif ($a === 'delete' && $own($_POST['quiz_id'] ?? 0)) {
            db_run("DELETE FROM quizzes WHERE id=?", 'i', [(int) $_POST['quiz_id']]);
            flash('Quiz deleted.', 'info');
        }
    } catch (mysqli_sql_exception $ex) {
        error_log($ex->getMessage());
        flash('Could not save the quiz.', 'danger');
    }
    redirect('quizzes.php?course=' . $courseId);
}

$quizzes = $course ? db_all(
    "SELECT q.*, (SELECT COUNT(*) FROM questions x WHERE x.quiz_id=q.id) AS qcount
     FROM quizzes q WHERE q.course_id=? ORDER BY q.id DESC", 'i', [$courseId]
) : [];

function quiz_fields($q)
{
    ?>
    <div class="row g-2">
        <div class="col-md-5"><input class="form-control" name="title" placeholder="Quiz title" value="<?php echo e($q['title'] ?? ''); ?>" required maxlength="200"></div>
        <div class="col-md-2"><input class="form-control" type="number" step="0.01" min="0" max="100" name="pass_mark" placeholder="Pass %" value="<?php echo e($q['pass_mark'] ?? 50); ?>"></div>
        <div class="col-md-2"><input class="form-control" type="number" min="0" name="time_limit_minutes" placeholder="Minutes (0 = none)" value="<?php echo e($q['time_limit_minutes'] ?? 0); ?>"></div>
        <div class="col-md-3"><select class="form-select" name="status"><?php foreach (['draft', 'published'] as $s): ?><option value="<?php echo $s; ?>" <?php echo ($q['status'] ?? 'draft') === $s ? 'selected' : ''; ?>><?php echo ucfirst($s); ?></option><?php endforeach; ?></select></div>
        <div class="col-12"><input class="form-control" name="description" placeholder="Description (optional)" value="<?php echo e($q['description'] ?? ''); ?>"></div>
    </div>
    <?php
}

portal_start('Quizzes');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">ASSESSMENT</span>
        <h1>Quizzes</h1>
        <p>Create quizzes for your courses. Students only see published quizzes.</p>
    </div>
</section>
<?php flash_show(); ?>
<?php if (!$courses): ?>
    <section class="panel"><p class="page-note">Create a course first.</p></section>
<?php else: ?>
<form class="toolbar" method="get">
    <select class="form-select" name="course" onchange="this.form.submit()">
        <?php foreach ($courses as $c): ?><option value="<?php echo (int) $c['id']; ?>" <?php echo (int) $c['id'] === $courseId ? 'selected' : ''; ?>><?php echo e($c['title']); ?></option><?php endforeach; ?>
    </select>
</form>

<section class="block-card">
    <h3>New quiz</h3>
    <form method="post" class="mt-2">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="course" value="<?php echo $courseId; ?>">
        <input type="hidden" name="action" value="add">
        <?php quiz_fields([]); ?>
        <button class="btn btn-primary btn-sm mt-2" type="submit">Create quiz</button>
    </form>
</section>

<?php foreach ($quizzes as $q): ?>
<section class="block-card">
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div>
            <h3><?php echo e($q['title']); ?> <span class="pill <?php echo $q['status'] === 'published' ? 'green' : 'orange'; ?>"><?php echo e($q['status']); ?></span></h3>
            <p class="page-note"><?php echo (int) $q['qcount']; ?> question(s) · pass <?php echo number_format((float) $q['pass_mark'], 0); ?>%<?php echo $q['time_limit_minutes'] ? ' · ' . (int) $q['time_limit_minutes'] . ' min' : ''; ?></p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-primary btn-sm" href="questions.php?quiz=<?php echo (int) $q['id']; ?>">Questions</a>
            <form method="post" onsubmit="return confirm('Delete this quiz with all its questions?')">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="course" value="<?php echo $courseId; ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="quiz_id" value="<?php echo (int) $q['id']; ?>">
                <button class="btn btn-danger-soft btn-sm" type="submit">Delete</button>
            </form>
        </div>
    </div>
    <details class="edit-box">
        <summary>Edit quiz</summary>
        <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course" value="<?php echo $courseId; ?>">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="quiz_id" value="<?php echo (int) $q['id']; ?>">
            <?php quiz_fields($q); ?>
            <button class="btn btn-light btn-sm mt-2" type="submit">Save</button>
        </form>
    </details>
</section>
<?php endforeach; ?>
<?php endif; ?>
<?php portal_end(); ?>
