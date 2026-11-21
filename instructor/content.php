<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$courses = db_all("SELECT id, title FROM courses WHERE instructor_id=? ORDER BY title", 'i', [$me['id']]);
$courseId = (int) ($_GET['course'] ?? $_POST['course'] ?? 0);
if (!$courseId && $courses) { $courseId = (int) $courses[0]['id']; }
$course = $courseId ? own_course($courseId, $me['id']) : null;

function lesson_fields($l)
{
    $types = ['video', 'article', 'file', 'link'];
    ?>
    <div class="row g-2">
        <div class="col-md-6"><input class="form-control" name="title" placeholder="Lesson title" value="<?php echo e($l['title'] ?? ''); ?>" required maxlength="200"></div>
        <div class="col-md-3"><select class="form-select" name="lesson_type"><?php foreach ($types as $t): ?><option value="<?php echo $t; ?>" <?php echo ($l['lesson_type'] ?? 'video') === $t ? 'selected' : ''; ?>><?php echo ucfirst($t); ?></option><?php endforeach; ?></select></div>
        <div class="col-md-3"><input class="form-control" type="number" min="0" name="duration_minutes" placeholder="Minutes" value="<?php echo e($l['duration_minutes'] ?? 0); ?>"></div>
        <div class="col-12"><input class="form-control" name="resource_url" placeholder="Video / file / link URL (https://...)" value="<?php echo e($l['resource_url'] ?? ''); ?>" maxlength="500"></div>
        <div class="col-12"><textarea class="form-control" name="content" rows="3" placeholder="Lesson text / notes"><?php echo e($l['content'] ?? ''); ?></textarea></div>
        <div class="col-md-3"><input class="form-control" type="number" min="0" name="sort_order" placeholder="Order" value="<?php echo e($l['sort_order'] ?? 0); ?>"></div>
        <div class="col-md-5 d-flex align-items-center"><label><input type="checkbox" name="is_preview" value="1" <?php echo !empty($l['is_preview']) ? 'checked' : ''; ?>> Free preview lesson</label></div>
    </div>
    <?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (!$course) { flash('Choose one of your courses first.', 'warning'); redirect('content.php'); }
    $a = $_POST['action'] ?? '';
    $back = 'content.php?course=' . $courseId;

    $ownSection = function ($sid) use ($courseId) {
        return db_one("SELECT id FROM course_sections WHERE id=? AND course_id=?", 'ii', [(int) $sid, $courseId]);
    };
    $ownLesson = function ($lid) use ($courseId) {
        return db_one("SELECT l.id FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE l.id=? AND s.course_id=?", 'ii', [(int) $lid, $courseId]);
    };

    try {
        if ($a === 'add_section' || $a === 'edit_section') {
            $title = trim($_POST['title'] ?? '');
            $desc = trim($_POST['description'] ?? '');
            $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
            if ($title === '') { flash('Section title is required.', 'danger'); redirect($back); }
            if ($a === 'add_section') {
                db_run("INSERT INTO course_sections (course_id, title, description, sort_order) VALUES (?, ?, ?, ?)", 'issi', [$courseId, $title, $desc, $sort]);
                flash('Section added.');
            } elseif ($ownSection($_POST['section_id'] ?? 0)) {
                db_run("UPDATE course_sections SET title=?, description=?, sort_order=? WHERE id=?", 'ssii', [$title, $desc, $sort, (int) $_POST['section_id']]);
                flash('Section updated.');
            }
        } elseif ($a === 'del_section' && $ownSection($_POST['section_id'] ?? 0)) {
            db_run("DELETE FROM course_sections WHERE id=?", 'i', [(int) $_POST['section_id']]);
            flash('Section deleted (its lessons were removed too).', 'info');
        } elseif ($a === 'add_lesson' || $a === 'edit_lesson') {
            $title = trim($_POST['title'] ?? '');
            $type = $_POST['lesson_type'] ?? 'video';
            $url = trim($_POST['resource_url'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $dur = max(0, (int) ($_POST['duration_minutes'] ?? 0));
            $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
            $prev = isset($_POST['is_preview']) ? 1 : 0;
            if ($title === '' || !in_array($type, ['video', 'article', 'file', 'link'], true)) {
                flash('Lesson title and a valid type are required.', 'danger'); redirect($back);
            }
            if ($url !== '' && !safe_url($url)) { flash('The URL must start with http:// or https://', 'danger'); redirect($back); }
            if ($a === 'add_lesson') {
                if ($ownSection($_POST['section_id'] ?? 0)) {
                    db_run(
                        "INSERT INTO lessons (section_id, title, lesson_type, content, resource_url, duration_minutes, is_preview, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                        'issssiii', [(int) $_POST['section_id'], $title, $type, $content, $url, $dur, $prev, $sort]
                    );
                    flash('Lesson added.');
                }
            } elseif ($ownLesson($_POST['lesson_id'] ?? 0)) {
                db_run(
                    "UPDATE lessons SET title=?, lesson_type=?, content=?, resource_url=?, duration_minutes=?, is_preview=?, sort_order=? WHERE id=?",
                    'ssssiiii', [$title, $type, $content, $url, $dur, $prev, $sort, (int) $_POST['lesson_id']]
                );
                flash('Lesson updated.');
            }
        } elseif ($a === 'del_lesson' && $ownLesson($_POST['lesson_id'] ?? 0)) {
            db_run("DELETE FROM lessons WHERE id=?", 'i', [(int) $_POST['lesson_id']]);
            flash('Lesson deleted.', 'info');
        }
    } catch (mysqli_sql_exception $ex) {
        error_log($ex->getMessage());
        flash('Could not save. Please check your input.', 'danger');
    }
    redirect($back);
}

$sections = $course ? db_all("SELECT * FROM course_sections WHERE course_id=? ORDER BY sort_order, id", 'i', [$courseId]) : [];
$lessonsBySection = [];
if ($course) {
    foreach (db_all("SELECT l.* FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE s.course_id=? ORDER BY l.sort_order, l.id", 'i', [$courseId]) as $l) {
        $lessonsBySection[$l['section_id']][] = $l;
    }
}

portal_start('Sections & lessons');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">COURSE CONTENT</span>
        <h1>Sections &amp; lessons</h1>
        <p>Build the curriculum of your courses.</p>
    </div>
    <a class="btn btn-light" href="../crud/courses/create.php">＋ New course</a>
</section>
<?php flash_show(); ?>

<?php if (!$courses): ?>
    <section class="panel"><p class="page-note">You have no courses yet. <a class="text-link" href="../crud/courses/create.php">Create your first course</a>.</p></section>
<?php else: ?>
<form class="toolbar" method="get">
    <select class="form-select" name="course" onchange="this.form.submit()">
        <?php foreach ($courses as $c): ?><option value="<?php echo (int) $c['id']; ?>" <?php echo (int) $c['id'] === $courseId ? 'selected' : ''; ?>><?php echo e($c['title']); ?></option><?php endforeach; ?>
    </select>
</form>

<section class="block-card">
    <h3>Add a section</h3>
    <form method="post" class="row g-2 mt-1">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="course" value="<?php echo $courseId; ?>">
        <input type="hidden" name="action" value="add_section">
        <div class="col-md-5"><input class="form-control" name="title" placeholder="Section title" required maxlength="200"></div>
        <div class="col-md-4"><input class="form-control" name="description" placeholder="Short description (optional)"></div>
        <div class="col-md-1"><input class="form-control" type="number" min="0" name="sort_order" placeholder="#" value="<?php echo count($sections) + 1; ?>"></div>
        <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Add</button></div>
    </form>
</section>

<?php foreach ($sections as $s): ?>
<section class="block-card">
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div><h3><?php echo e($s['title']); ?></h3><p class="page-note"><?php echo e($s['description']); ?> · order <?php echo (int) $s['sort_order']; ?></p></div>
        <form method="post" onsubmit="return confirm('Delete this section and all its lessons?')">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course" value="<?php echo $courseId; ?>">
            <input type="hidden" name="action" value="del_section">
            <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
            <button class="btn btn-danger-soft btn-sm" type="submit">Delete section</button>
        </form>
    </div>
    <details class="edit-box">
        <summary>Edit section</summary>
        <form method="post" class="row g-2">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course" value="<?php echo $courseId; ?>">
            <input type="hidden" name="action" value="edit_section">
            <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
            <div class="col-md-5"><input class="form-control" name="title" value="<?php echo e($s['title']); ?>" required maxlength="200"></div>
            <div class="col-md-4"><input class="form-control" name="description" value="<?php echo e($s['description']); ?>"></div>
            <div class="col-md-1"><input class="form-control" type="number" min="0" name="sort_order" value="<?php echo (int) $s['sort_order']; ?>"></div>
            <div class="col-md-2"><button class="btn btn-light w-100" type="submit">Save</button></div>
        </form>
    </details>

    <?php foreach ($lessonsBySection[$s['id']] ?? [] as $l): ?>
        <div class="lesson-row" style="display:block">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <span><?php echo e($l['title']); ?> <small>(<?php echo e($l['lesson_type']); ?>, <?php echo (int) $l['duration_minutes']; ?> min<?php echo $l['is_preview'] ? ', preview' : ''; ?>)</small></span>
                <form method="post" onsubmit="return confirm('Delete this lesson?')">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="course" value="<?php echo $courseId; ?>">
                    <input type="hidden" name="action" value="del_lesson">
                    <input type="hidden" name="lesson_id" value="<?php echo (int) $l['id']; ?>">
                    <button class="btn btn-danger-soft btn-sm" type="submit">Delete</button>
                </form>
            </div>
            <details class="edit-box">
                <summary>Edit lesson</summary>
                <form method="post">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="course" value="<?php echo $courseId; ?>">
                    <input type="hidden" name="action" value="edit_lesson">
                    <input type="hidden" name="lesson_id" value="<?php echo (int) $l['id']; ?>">
                    <?php lesson_fields($l); ?>
                    <button class="btn btn-light btn-sm mt-2" type="submit">Save lesson</button>
                </form>
            </details>
        </div>
    <?php endforeach; ?>

    <details class="edit-box" style="margin-top:14px">
        <summary>＋ Add lesson to this section</summary>
        <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="course" value="<?php echo $courseId; ?>">
            <input type="hidden" name="action" value="add_lesson">
            <input type="hidden" name="section_id" value="<?php echo (int) $s['id']; ?>">
            <?php lesson_fields(['sort_order' => count($lessonsBySection[$s['id']] ?? []) + 1]); ?>
            <button class="btn btn-primary btn-sm mt-2" type="submit">Add lesson</button>
        </form>
    </details>
</section>
<?php endforeach; ?>
<?php endif; ?>
<?php portal_end(); ?>
