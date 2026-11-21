<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$quizId = (int) ($_GET['quiz'] ?? $_POST['quiz'] ?? 0);
$quiz = db_one(
    "SELECT q.*, c.title AS course_title FROM quizzes q JOIN courses c ON c.id=q.course_id
     WHERE q.id=? AND c.instructor_id=?",
    'ii', [$quizId, $me['id']]
);
if (!$quiz) {
    flash('Quiz not found.', 'warning');
    redirect('quizzes.php');
}

$ownQuestion = function ($qid) use ($quizId) {
    return db_one("SELECT id, question_type FROM questions WHERE id=? AND quiz_id=?", 'ii', [(int) $qid, $quizId]);
};
$ownOption = function ($oid) use ($quizId) {
    return db_one(
        "SELECT o.id FROM quiz_options o JOIN questions q ON q.id=o.question_id WHERE o.id=? AND q.quiz_id=?",
        'ii', [(int) $oid, $quizId]
    );
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $a = $_POST['action'] ?? '';
    global $conn;
    try {
        if ($a === 'add_q' || $a === 'edit_q') {
            $text = trim($_POST['question_text'] ?? '');
            $type = $_POST['question_type'] ?? 'mcq';
            $marks = max(0, (float) ($_POST['marks'] ?? 1));
            $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
            if ($text === '' || !in_array($type, ['mcq', 'true_false', 'short_answer'], true)) {
                flash('Question text and type are required.', 'danger');
            } elseif ($a === 'add_q') {
                db_run("INSERT INTO questions (quiz_id, question_text, question_type, marks, sort_order) VALUES (?, ?, ?, ?, ?)", 'issdi', [$quizId, $text, $type, $marks, $sort]);
                $newId = (int) $conn->insert_id;
                if ($type === 'true_false') {
                    $correct = ($_POST['tf_correct'] ?? 'True') === 'False' ? 'False' : 'True';
                    foreach (['True', 'False'] as $i => $label) {
                        db_run("INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order) VALUES (?, ?, ?, ?)", 'isii', [$newId, $label, $label === $correct ? 1 : 0, $i + 1]);
                    }
                }
                flash('Question added.');
            } elseif ($ownQuestion($_POST['question_id'] ?? 0)) {
                db_run("UPDATE questions SET question_text=?, question_type=?, marks=?, sort_order=? WHERE id=?", 'ssdii', [$text, $type, $marks, $sort, (int) $_POST['question_id']]);
                flash('Question updated.');
            }
        } elseif ($a === 'del_q' && $ownQuestion($_POST['question_id'] ?? 0)) {
            db_run("DELETE FROM questions WHERE id=?", 'i', [(int) $_POST['question_id']]);
            flash('Question deleted.', 'info');
        } elseif ($a === 'add_o' && $ownQuestion($_POST['question_id'] ?? 0)) {
            $text = trim($_POST['option_text'] ?? '');
            if ($text === '') {
                flash('Option text is required.', 'danger');
            } else {
                $correct = isset($_POST['is_correct']) ? 1 : 0;
                $sort = max(0, (int) ($_POST['sort_order'] ?? 0));
                db_run("INSERT INTO quiz_options (question_id, option_text, is_correct, sort_order) VALUES (?, ?, ?, ?)", 'isii', [(int) $_POST['question_id'], $text, $correct, $sort]);
                flash('Option added.');
            }
        } elseif ($a === 'toggle_o' && $ownOption($_POST['option_id'] ?? 0)) {
            db_run("UPDATE quiz_options SET is_correct = 1 - is_correct WHERE id=?", 'i', [(int) $_POST['option_id']]);
            flash('Option updated.');
        } elseif ($a === 'del_o' && $ownOption($_POST['option_id'] ?? 0)) {
            db_run("DELETE FROM quiz_options WHERE id=?", 'i', [(int) $_POST['option_id']]);
            flash('Option deleted.', 'info');
        }
    } catch (mysqli_sql_exception $ex) {
        error_log($ex->getMessage());
        flash('Could not save. Please check your input.', 'danger');
    }
    redirect('questions.php?quiz=' . $quizId);
}

$questions = db_all("SELECT * FROM questions WHERE quiz_id=? ORDER BY sort_order, id", 'i', [$quizId]);
$optionsByQ = [];
foreach (db_all("SELECT o.* FROM quiz_options o JOIN questions q ON q.id=o.question_id WHERE q.quiz_id=? ORDER BY o.sort_order, o.id", 'i', [$quizId]) as $o) {
    $optionsByQ[$o['question_id']][] = $o;
}
$typeLabels = ['mcq' => 'Multiple choice', 'true_false' => 'True / False', 'short_answer' => 'Short answer'];

portal_start('Questions');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow"><?php echo e(strtoupper($quiz['course_title'])); ?></span>
        <h1><?php echo e($quiz['title']); ?></h1>
        <p>Add questions and mark the correct option(s). For short answers, add the accepted answer as a correct option.</p>
    </div>
    <a class="btn btn-light" href="quizzes.php?course=<?php echo (int) $quiz['course_id']; ?>">← Quizzes</a>
</section>
<?php flash_show(); ?>

<section class="block-card">
    <h3>Add a question</h3>
    <form method="post" class="row g-2 mt-1">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="quiz" value="<?php echo $quizId; ?>">
        <input type="hidden" name="action" value="add_q">
        <div class="col-12"><textarea class="form-control" name="question_text" rows="2" placeholder="Question text" required></textarea></div>
        <div class="col-md-3"><select class="form-select" name="question_type"><?php foreach ($typeLabels as $k => $v): ?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php endforeach; ?></select></div>
        <div class="col-md-2"><input class="form-control" type="number" step="0.5" min="0" name="marks" value="1" title="Marks"></div>
        <div class="col-md-2"><input class="form-control" type="number" min="0" name="sort_order" value="<?php echo count($questions) + 1; ?>" title="Order"></div>
        <div class="col-md-3"><select class="form-select" name="tf_correct" title="Only used for True / False"><option value="True">True/False answer: True</option><option value="False">True/False answer: False</option></select></div>
        <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Add</button></div>
    </form>
</section>

<?php foreach ($questions as $n => $q): ?>
<section class="block-card">
    <div class="d-flex justify-content-between align-items-start gap-2">
        <div><h3><?php echo ($n + 1) . '. ' . e($q['question_text']); ?></h3>
            <p class="page-note"><?php echo e($typeLabels[$q['question_type']] ?? $q['question_type']); ?> · <?php echo e($q['marks']); ?> mark(s)</p></div>
        <form method="post" onsubmit="return confirm('Delete this question?')">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="quiz" value="<?php echo $quizId; ?>">
            <input type="hidden" name="action" value="del_q">
            <input type="hidden" name="question_id" value="<?php echo (int) $q['id']; ?>">
            <button class="btn btn-danger-soft btn-sm" type="submit">Delete</button>
        </form>
    </div>

    <?php foreach ($optionsByQ[$q['id']] ?? [] as $o): ?>
        <div class="lesson-row">
            <span><?php echo e($o['option_text']); ?> <?php echo $o['is_correct'] ? '<span class="pill green">correct</span>' : ''; ?></span>
            <span class="d-flex gap-2">
                <form method="post"><?php echo csrf_field(); ?><input type="hidden" name="quiz" value="<?php echo $quizId; ?>"><input type="hidden" name="action" value="toggle_o"><input type="hidden" name="option_id" value="<?php echo (int) $o['id']; ?>"><button class="btn btn-light btn-sm" type="submit"><?php echo $o['is_correct'] ? 'Unmark' : 'Mark correct'; ?></button></form>
                <form method="post" onsubmit="return confirm('Delete this option?')"><?php echo csrf_field(); ?><input type="hidden" name="quiz" value="<?php echo $quizId; ?>"><input type="hidden" name="action" value="del_o"><input type="hidden" name="option_id" value="<?php echo (int) $o['id']; ?>"><button class="btn btn-danger-soft btn-sm" type="submit">Delete</button></form>
            </span>
        </div>
    <?php endforeach; ?>

    <form method="post" class="row g-2 mt-2">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="quiz" value="<?php echo $quizId; ?>">
        <input type="hidden" name="action" value="add_o">
        <input type="hidden" name="question_id" value="<?php echo (int) $q['id']; ?>">
        <input type="hidden" name="sort_order" value="<?php echo count($optionsByQ[$q['id']] ?? []) + 1; ?>">
        <div class="col-md-7"><input class="form-control" name="option_text" placeholder="<?php echo $q['question_type'] === 'short_answer' ? 'Accepted answer' : 'New option'; ?>" required></div>
        <div class="col-md-3 d-flex align-items-center"><label><input type="checkbox" name="is_correct" value="1" <?php echo $q['question_type'] === 'short_answer' ? 'checked' : ''; ?>> Correct</label></div>
        <div class="col-md-2"><button class="btn btn-light w-100" type="submit">Add option</button></div>
    </form>

    <details class="edit-box">
        <summary>Edit question</summary>
        <form method="post" class="row g-2">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="quiz" value="<?php echo $quizId; ?>">
            <input type="hidden" name="action" value="edit_q">
            <input type="hidden" name="question_id" value="<?php echo (int) $q['id']; ?>">
            <div class="col-12"><textarea class="form-control" name="question_text" rows="2" required><?php echo e($q['question_text']); ?></textarea></div>
            <div class="col-md-4"><select class="form-select" name="question_type"><?php foreach ($typeLabels as $k => $v): ?><option value="<?php echo $k; ?>" <?php echo $q['question_type'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option><?php endforeach; ?></select></div>
            <div class="col-md-3"><input class="form-control" type="number" step="0.5" min="0" name="marks" value="<?php echo e($q['marks']); ?>"></div>
            <div class="col-md-3"><input class="form-control" type="number" min="0" name="sort_order" value="<?php echo (int) $q['sort_order']; ?>"></div>
            <div class="col-md-2"><button class="btn btn-light w-100" type="submit">Save</button></div>
        </form>
    </details>
</section>
<?php endforeach; ?>
<?php portal_end(); ?>
