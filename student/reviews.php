<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$error = '';
$createEnrollmentId = (int) ($_POST['enrollment_id'] ?? 0);
$createRating = (int) ($_POST['rating'] ?? 5);
$createText = trim($_POST['review_text'] ?? '');

$editId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
if ($editId === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'update') {
    $editId = (int) ($_POST['review_id'] ?? 0);
}
if ($editId > 0) {
    $editReview = db_one(
        "SELECT r.id, r.enrollment_id, r.rating, r.review_text, c.title
         FROM course_reviews r
         JOIN enrollments e ON e.id=r.enrollment_id
         JOIN courses c ON c.id=e.course_id
         WHERE r.id=? AND e.student_id=?",
        'ii',
        [$editId, $me['id']]
    );
    if (!$editReview) {
        flash('Review not found.', 'warning');
        redirect('reviews.php');
    }
} elseif (isset($_GET['edit'])) {
    flash('Review not found.', 'warning');
    redirect('reviews.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $deleted = db_run(
            "DELETE r FROM course_reviews r
             JOIN enrollments e ON e.id=r.enrollment_id
             WHERE r.id=? AND e.student_id=?",
            'ii',
            [$reviewId, $me['id']]
        );
        flash($deleted->affected_rows > 0 ? 'Review deleted.' : 'Review not found.', $deleted->affected_rows > 0 ? 'success' : 'warning');
        redirect('reviews.php');
    }

    $rating = (int) ($_POST['rating'] ?? 0);
    $reviewText = trim($_POST['review_text'] ?? '');
    if ($action === 'update' && isset($editReview)) {
        $editReview['rating'] = $rating;
        $editReview['review_text'] = $reviewText;
    }
    if ($rating < 1 || $rating > 5) {
        $error = 'Choose a rating from 1 to 5.';
    } elseif (strlen($reviewText) > 65535) {
        $error = 'Review is too long to save.';
    } elseif ($action === 'create') {
        $enrollmentId = (int) ($_POST['enrollment_id'] ?? 0);
        $enrollment = db_one(
            "SELECT id FROM enrollments
             WHERE id=? AND student_id=? AND status IN ('active','completed')",
            'ii',
            [$enrollmentId, $me['id']]
        );
        if (!$enrollment) {
            $error = 'Choose one of your active or completed courses.';
        } elseif (db_one("SELECT id FROM course_reviews WHERE enrollment_id=?", 'i', [$enrollmentId])) {
            $error = 'You already reviewed this course. Edit your existing review instead.';
        } else {
            try {
                db_run(
                    "INSERT INTO course_reviews (enrollment_id, rating, review_text) VALUES (?, ?, ?)",
                    'iis',
                    [$enrollmentId, $rating, $reviewText === '' ? null : $reviewText]
                );
                flash('Review added.');
                redirect('reviews.php');
            } catch (mysqli_sql_exception $exception) {
                error_log($exception->getMessage());
                $error = $exception->getCode() === 1062
                    ? 'You already reviewed this course. Edit your existing review instead.'
                    : 'Could not save your review. Please try again.';
            }
        }
    } elseif ($action === 'update') {
        $reviewId = (int) ($_POST['review_id'] ?? 0);
        $ownedReview = db_one(
            "SELECT r.id FROM course_reviews r
             JOIN enrollments e ON e.id=r.enrollment_id
             WHERE r.id=? AND e.student_id=?",
            'ii',
            [$reviewId, $me['id']]
        );
        if (!$ownedReview) {
            $error = 'Review not found.';
        } else {
            db_run(
                "UPDATE course_reviews r
                 JOIN enrollments e ON e.id=r.enrollment_id
                 SET r.rating=?, r.review_text=?
                 WHERE r.id=? AND e.student_id=?",
                'isii',
                [$rating, $reviewText === '' ? null : $reviewText, $reviewId, $me['id']]
            );
            flash('Review updated.');
            redirect('reviews.php');
        }
    } else {
        $error = 'Invalid review action.';
    }
}

$availableEnrollments = db_all(
    "SELECT e.id, c.title
     FROM enrollments e
     JOIN courses c ON c.id=e.course_id
     WHERE e.student_id=? AND e.status IN ('active','completed')
       AND NOT EXISTS (SELECT 1 FROM course_reviews r WHERE r.enrollment_id=e.id)
     ORDER BY e.enrolled_at DESC",
    'i',
    [$me['id']]
);
$reviews = db_all(
    "SELECT r.id, r.enrollment_id, r.rating, r.review_text, r.created_at, c.title
     FROM course_reviews r
     JOIN enrollments e ON e.id=r.enrollment_id
     JOIN courses c ON c.id=e.course_id
     WHERE e.student_id=?
     ORDER BY r.created_at DESC, r.id DESC",
    'i',
    [$me['id']]
);

portal_start('My reviews');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">FEEDBACK</span>
        <h1>My reviews</h1>
        <p>Write, update, or remove reviews for your enrolled courses.</p>
    </div>
</section>
<?php flash_show(); ?>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>

<?php if (!empty($editReview)): ?>
<section class="panel" style="max-width:680px">
    <div class="panel-heading"><div><h2>Edit review</h2><p><?php echo e($editReview['title']); ?></p></div></div>
    <form method="post" action="reviews.php?edit=<?php echo (int) $editReview['id']; ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="review_id" value="<?php echo (int) $editReview['id']; ?>">
        <label class="form-label" for="edit-rating">Rating</label>
        <select id="edit-rating" name="rating" class="form-select mb-3" required>
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <option value="<?php echo $i; ?>" <?php echo (int) ($editReview['rating'] ?? 0) === $i ? 'selected' : ''; ?>><?php echo $i; ?> ★</option>
            <?php endfor; ?>
        </select>
        <label class="form-label" for="edit-review">Review</label>
        <textarea id="edit-review" name="review_text" class="form-control mb-3" rows="4"><?php echo e($editReview['review_text'] ?? ''); ?></textarea>
        <button class="btn btn-primary" type="submit">Save changes</button>
        <a class="btn btn-light" href="reviews.php">Cancel</a>
    </form>
</section>
<?php endif; ?>

<section class="panel" style="max-width:680px">
    <div class="panel-heading"><div><h2>Add a review</h2><p>One review is allowed per enrolled course.</p></div></div>
    <?php if (!$availableEnrollments): ?>
        <p class="page-note">No eligible courses to review. Enroll in a course first; courses you have already reviewed are listed below.</p>
    <?php else: ?>
        <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" value="create">
            <label class="form-label" for="review-enrollment">Course</label>
            <select id="review-enrollment" name="enrollment_id" class="form-select mb-3" required>
                <option value="">Choose a course</option>
                <?php foreach ($availableEnrollments as $enrollment): ?>
                    <option value="<?php echo (int) $enrollment['id']; ?>" <?php echo $createEnrollmentId === (int) $enrollment['id'] ? 'selected' : ''; ?>><?php echo e($enrollment['title']); ?></option>
                <?php endforeach; ?>
            </select>
            <label class="form-label" for="new-rating">Rating</label>
            <select id="new-rating" name="rating" class="form-select mb-3" required>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <option value="<?php echo $i; ?>" <?php echo $createRating === $i ? 'selected' : ''; ?>><?php echo $i; ?> ★</option>
                <?php endfor; ?>
            </select>
            <label class="form-label" for="new-review">Review</label>
            <textarea id="new-review" name="review_text" class="form-control mb-3" rows="4"><?php echo e($createText); ?></textarea>
            <button class="btn btn-primary" type="submit">Add review</button>
        </form>
    <?php endif; ?>
</section>

<section class="panel">
    <div class="panel-heading"><div><h2>Reviews you have written</h2><p><?php echo count($reviews); ?> review(s)</p></div></div>
    <?php if (!$reviews): ?><p class="page-note">You have not reviewed any courses yet.</p><?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <article class="block-card">
                <div class="panel-heading">
                    <div>
                        <h3><?php echo e($review['title']); ?></h3>
                        <p><?php echo e($review['created_at']); ?> · <?php echo str_repeat('★', (int) $review['rating']) . str_repeat('☆', 5 - (int) $review['rating']); ?></p>
                    </div>
                    <div class="toolbar">
                        <a class="btn btn-light btn-sm" href="reviews.php?edit=<?php echo (int) $review['id']; ?>">Edit</a>
                        <form method="post" onsubmit="return confirm('Delete this review?');">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="review_id" value="<?php echo (int) $review['id']; ?>">
                            <button class="btn btn-danger-soft btn-sm" type="submit">Delete</button>
                        </form>
                    </div>
                </div>
                <div class="lesson-text"><?php echo nl2br(e($review['review_text'] ?? '')); ?></div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
