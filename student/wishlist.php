<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['student']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $courseId = (int) ($_POST['course_id'] ?? 0);
    $from = $_POST['from'] ?? 'wishlist';
    if (db_one("SELECT id FROM courses WHERE id=? AND status='published'", 'i', [$courseId])) {
        $exists = db_one("SELECT id FROM wishlists WHERE student_id=? AND course_id=?", 'ii', [$me['id'], $courseId]);
        if ($exists) {
            db_run("DELETE FROM wishlists WHERE id=?", 'i', [$exists['id']]);
            flash('Removed from your wishlist.', 'info');
        } else {
            db_run("INSERT INTO wishlists (student_id, course_id) VALUES (?, ?)", 'ii', [$me['id'], $courseId]);
            flash('Saved to your wishlist.');
        }
    }
    redirect($from === 'course' ? 'course.php?id=' . $courseId : 'wishlist.php');
}

$rows = db_all(
    "SELECT c.id, c.title, c.price, cat.name AS category
     FROM wishlists w JOIN courses c ON c.id=w.course_id JOIN categories cat ON cat.id=c.category_id
     WHERE w.student_id=? ORDER BY w.created_at DESC",
    'i', [$me['id']]
);

portal_start('Wishlist');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">SAVED</span>
        <h1>My wishlist</h1>
        <p>Courses you want to take later.</p>
    </div>
    <a class="btn btn-light" href="courses.php">Browse courses</a>
</section>
<?php flash_show(); ?>
<section class="panel">
    <?php if (!$rows): ?><p class="page-note">Your wishlist is empty.</p><?php else: ?>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Course</th><th>Category</th><th>Price</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><a class="text-link" href="course.php?id=<?php echo (int) $r['id']; ?>"><?php echo e($r['title']); ?></a></td>
                    <td><?php echo e($r['category']); ?></td>
                    <td><?php echo (float) $r['price'] > 0 ? 'BDT ' . number_format((float) $r['price'], 0) : 'Free'; ?></td>
                    <td style="text-align:right">
                        <form method="post" style="display:inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="course_id" value="<?php echo (int) $r['id']; ?>">
                            <button class="btn btn-danger-soft btn-sm" type="submit">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php portal_end(); ?>
