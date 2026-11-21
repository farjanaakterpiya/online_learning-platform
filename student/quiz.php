<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$quizId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$quiz = db_one(
    "SELECT q.*, c.title AS course_title FROM quizzes q JOIN courses c ON c.id=q.course_id
     WHERE q.id=? AND q.status='published'",
    'i', [$quizId]
);
if (!$quiz) {
    flash('That quiz is not available.', 'warning');
    redirect('my_courses.php');
}
$enrollment = db_one("SELECT status FROM enrollments WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $quiz['course_id']]);
if (!$enrollment || !in_array($enrollment['status'], ['active', 'completed'], true)) {
    flash('Enroll in the course to take its quizzes.', 'warning');
    redirect('course.php?id=' . (int) $quiz['course_id']);
}

$questions = db_all("SELECT * FROM questions WHERE quiz_id=? ORDER BY sort_order, id", 'i', [$quizId]);
$optionsByQ = [];
foreach (db_all(
    "SELECT o.* FROM quiz_options o JOIN questions q ON q.id=o.question_id WHERE q.quiz_id=? ORDER BY o.sort_order, o.id",
    'i', [$quizId]
) as $o) {
    $optionsByQ[$o['question_id']][] = $o;
}

/* ---------- submit ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $score = 0.0;
    $total = 0.0;
    foreach ($questions as $q) {
        $marks = (float) $q['marks'];
        $total += $marks;
        $given = $_POST['q'][$q['id']] ?? '';
        $ok = false;
        foreach ($optionsByQ[$q['id']] ?? [] as $o) {
            if (!(int) $o['is_correct']) { continue; }
            if ($q['question_type'] === 'short_answer') {
                if (mb_strtolower(trim((string) $given)) === mb_strtolower(trim($o['option_text']))) { $ok = true; }
            } elseif ((int) $given === (int) $o['id']) {
                $ok = true;
            }
        }
        if ($ok) { $score += $marks; }
    }
    $started = (int) ($_SESSION['quiz_start'][$quizId] ?? time());
    $limit = (int) $quiz['time_limit_minutes'];
    $late = $limit > 0 && (time() - $started) > ($limit * 60 + 60);
    if ($late) { $score = 0.0; }
    unset($_SESSION['quiz_start'][$quizId]);

    db_run(
        "INSERT INTO quiz_attempts (quiz_id, student_id, started_at, submitted_at, score, total_marks, status)
         VALUES (?, ?, FROM_UNIXTIME(?), NOW(), ?, ?, 'graded')",
        'iiidd', [$quizId, $me['id'], $started, $score, $total]
    );
    global $conn;
    $attemptId = (int) $conn->insert_id;
    if ($late) { flash('Time limit exceeded, so this attempt scored 0.', 'warning'); }
    redirect('quiz.php?id=' . $quizId . '&attempt=' . $attemptId);
}

$attemptId = (int) ($_GET['attempt'] ?? 0);
$attempt = $attemptId ? db_one("SELECT * FROM quiz_attempts WHERE id=? AND student_id=? AND quiz_id=?", 'iii', [$attemptId, $me['id'], $quizId]) : null;
$history = db_all("SELECT id, submitted_at, score, total_marks FROM quiz_attempts WHERE quiz_id=? AND student_id=? ORDER BY id DESC LIMIT 10", 'ii', [$quizId, $me['id']]);
if (!$attempt) { $_SESSION['quiz_start'][$quizId] = time(); }

portal_start($quiz['title']);
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">QUIZ</span>
        <h1><?php echo e($quiz['title']); ?></h1>
        <p><?php echo e($quiz['course_title']); ?> · pass mark <?php echo number_format((float) $quiz['pass_mark'], 0); ?>%<?php echo $quiz['time_limit_minutes'] ? ' · ' . (int) $quiz['time_limit_minutes'] . ' minutes' : ''; ?></p>
    </div>
    <a class="btn btn-light" href="learn.php?course=<?php echo (int) $quiz['course_id']; ?>">← Back to course</a>
</section>
<?php flash_show(); ?>

<?php if ($attempt):
    $pct = (float) $attempt['total_marks'] > 0 ? round($attempt['score'] * 100 / $attempt['total_marks'], 1) : 0;
    $passed = $pct >= (float) $quiz['pass_mark'];
?>
<section class="panel score-box">
    <span class="pill <?php echo $passed ? 'green' : 'red'; ?>"><?php echo $passed ? 'Passed' : 'Not passed'; ?></span>
    <strong><?php echo $pct; ?>%</strong>
    <p class="page-note"><?php echo number_format((float) $attempt['score'], 2); ?> / <?php echo number_format((float) $attempt['total_marks'], 2); ?> marks</p>
    <a class="btn btn-primary" href="quiz.php?id=<?php echo $quizId; ?>">Try again</a>
</section>
<?php elseif (!$questions): ?>
<section class="panel"><p class="page-note">This quiz has no questions yet.</p></section>
<?php else: ?>
<form method="post" id="quizForm">
    <?php echo csrf_field(); ?>
    <input type="hidden" name="id" value="<?php echo $quizId; ?>">
    <?php if ($quiz['description']): ?><p class="page-note" style="margin-bottom:14px"><?php echo e($quiz['description']); ?></p><?php endif; ?>
    <?php if ($quiz['time_limit_minutes']): ?><p><b>Time left:</b> <span id="timer" class="pill orange"></span></p><?php endif; ?>
    <?php foreach ($questions as $n => $q): ?>
        <div class="quiz-q">
            <h5><?php echo ($n + 1) . '. ' . e($q['question_text']); ?> <small class="text-muted">(<?php echo rtrim(rtrim(number_format((float) $q['marks'], 2), '0'), '.'); ?> mark)</small></h5>
            <?php if ($q['question_type'] === 'short_answer'): ?>
                <input class="form-control" name="q[<?php echo (int) $q['id']; ?>]" placeholder="Type your answer">
            <?php else: foreach ($optionsByQ[$q['id']] ?? [] as $o): ?>
                <label><input type="radio" name="q[<?php echo (int) $q['id']; ?>]" value="<?php echo (int) $o['id']; ?>"> <?php echo e($o['option_text']); ?></label>
            <?php endforeach; endif; ?>
        </div>
    <?php endforeach; ?>
    <button class="btn btn-primary" type="submit">Submit quiz</button>
</form>
<?php if ($quiz['time_limit_minutes']): ?>
<script>
(function () {
    var left = <?php echo (int) $quiz['time_limit_minutes'] * 60; ?>;
    var el = document.getElementById('timer');
    function tick() {
        var m = Math.floor(left / 60), s = left % 60;
        el.textContent = m + ':' + (s < 10 ? '0' : '') + s;
        if (left <= 0) { document.getElementById('quizForm').submit(); return; }
        left--; setTimeout(tick, 1000);
    }
    tick();
})();
</script>
<?php endif; ?>
<?php endif; ?>

<?php if ($history): ?>
<section class="panel">
    <div class="panel-heading"><div><h2>Your attempts</h2></div></div>
    <div class="table-wrap"><table>
        <thead><tr><th>#</th><th>Submitted</th><th>Score</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($history as $h): ?>
            <tr>
                <td><?php echo (int) $h['id']; ?></td>
                <td><?php echo e($h['submitted_at']); ?></td>
                <td><?php echo number_format((float) $h['score'], 2); ?> / <?php echo number_format((float) $h['total_marks'], 2); ?></td>
                <td><a class="text-link" href="quiz.php?id=<?php echo $quizId; ?>&attempt=<?php echo (int) $h['id']; ?>">View</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</section>
<?php endif; ?>
<?php portal_end(); ?>
