<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

$courseId = (int) ($_GET['course'] ?? $_POST['course'] ?? 0);
$course = db_one("SELECT id, title, price FROM courses WHERE id=? AND status='published'", 'i', [$courseId]);
if (!$course) {
    flash('That course is not available.', 'warning');
    redirect('courses.php');
}
if (db_one("SELECT id FROM enrollments WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $courseId])) {
    flash('You are already enrolled in this course.', 'info');
    redirect('course.php?id=' . $courseId);
}

$price = (float) $course['price'];
$methods = ['bKash', 'Nagad', 'Rocket', 'Card', 'Bank transfer'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $method = $_POST['payment_method'] ?? '';
    if ($price > 0 && !in_array($method, $methods, true)) {
        $error = 'Please choose a payment method.';
    } else {
        global $conn;
        try {
            $conn->begin_transaction();
            db_run("INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, 'active')", 'ii', [$me['id'], $courseId]);
            $enrollmentId = (int) $conn->insert_id;
            if ($price > 0) {
                // Simulated checkout: no real gateway is connected, so the payment is recorded as paid.
                $ref = 'TXN-' . strtoupper(bin2hex(random_bytes(6)));
                db_run(
                    "INSERT INTO payments (enrollment_id, transaction_ref, amount, currency, payment_method, payment_status, paid_at)
                     VALUES (?, ?, ?, 'BDT', ?, 'paid', NOW())",
                    'isds', [$enrollmentId, $ref, $price, $method]
                );
            }
            $conn->commit();
            flash('You are enrolled! Happy learning.');
            redirect('learn.php?course=' . $courseId);
        } catch (mysqli_sql_exception $ex) {
            $conn->rollback();
            error_log($ex->getMessage());
            $error = 'Could not complete the enrollment. Please try again.';
        }
    }
}

portal_start('Enroll');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">CHECKOUT</span>
        <h1>Enroll in <?php echo e($course['title']); ?></h1>
        <p><?php echo $price > 0 ? 'Choose a payment method to continue.' : 'This course is free.'; ?></p>
    </div>
    <a class="btn btn-light" href="course.php?id=<?php echo $courseId; ?>">← Back</a>
</section>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<section class="panel" style="max-width:520px">
    <form method="post">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="course" value="<?php echo $courseId; ?>">
        <p><b>Total:</b> <span class="price"><?php echo $price > 0 ? 'BDT ' . number_format($price, 2) : 'Free'; ?></span></p>
        <?php if ($price > 0): ?>
            <label class="form-label">Payment method</label>
            <select name="payment_method" class="form-select mb-3" required>
                <option value="">Select method</option>
                <?php foreach ($methods as $m): ?><option><?php echo e($m); ?></option><?php endforeach; ?>
            </select>
            <p class="page-note">Demo checkout: payments are recorded in the database but no real money is charged.</p>
        <?php endif; ?>
        <button class="btn btn-primary mt-3" type="submit"><?php echo $price > 0 ? 'Pay &amp; enroll' : 'Enroll for free'; ?></button>
    </form>
</section>
<?php portal_end(); ?>
