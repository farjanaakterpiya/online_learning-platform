<?php
require_once __DIR__ . '/../includes/auth.php';
$me = require_role(['instructor']);

$profile = db_one(
    "SELECT name, email, phone, password FROM users WHERE id=? AND role='instructor' LIMIT 1",
    'i',
    [$me['id']]
);
if (!$profile) {
    redirect('../logout.php');
}

$error = '';
$name = $profile['name'];
$email = $profile['email'];
$phone = $profile['phone'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $phone = trim($_POST['phone'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || mb_strlen($name) > 150) {
        $error = 'Enter a name of 1-150 characters.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
        $error = 'Enter a valid email address.';
    } elseif (mb_strlen($phone) > 30) {
        $error = 'Phone number must be 30 characters or fewer.';
    } elseif (
        $newPassword !== ''
        && !password_verify($currentPassword, (string) $profile['password'])
        && !hash_equals((string) $profile['password'], sha1($currentPassword))
    ) {
        $error = 'Current password is incorrect.';
    } elseif ($newPassword !== '' && strlen($newPassword) < 8) {
        $error = 'New password must be at least 8 characters.';
    } elseif ($newPassword !== '' && $newPassword !== $confirmPassword) {
        $error = 'New password and confirmation do not match.';
    } else {
        try {
            if ($newPassword !== '') {
                db_run(
                    "UPDATE users SET name=?, email=?, phone=?, password=? WHERE id=? AND role='instructor'",
                    'ssssi',
                    [$name, $email, $phone === '' ? null : $phone, password_hash($newPassword, PASSWORD_DEFAULT), $me['id']]
                );
            } else {
                db_run(
                    "UPDATE users SET name=?, email=?, phone=? WHERE id=? AND role='instructor'",
                    'sssi',
                    [$name, $email, $phone === '' ? null : $phone, $me['id']]
                );
            }
            $_SESSION['email'] = $email;
            flash('Profile updated successfully.');
            redirect('profile.php');
        } catch (mysqli_sql_exception $exception) {
            error_log($exception->getMessage());
            $error = $exception->getCode() === 1062
                ? 'That email address is already in use.'
                : 'Could not update your profile. Please try again.';
        }
    }
}

portal_start('My Profile');
?>
<section class="welcome-row">
    <div>
        <span class="eyebrow">ACCOUNT</span>
        <h1>My profile</h1>
        <p>Update your instructor contact details and password.</p>
    </div>
</section>
<?php flash_show(); ?>
<?php if ($error !== ''): ?><div class="alert alert-danger"><?php echo e($error); ?></div><?php endif; ?>
<section class="panel" style="max-width:680px">
    <form method="post">
        <?php echo csrf_field(); ?>
        <label class="form-label" for="profile-name">Full name</label>
        <input id="profile-name" class="form-control mb-3" type="text" name="name" maxlength="150" value="<?php echo e($name); ?>" required>

        <label class="form-label" for="profile-email">Email</label>
        <input id="profile-email" class="form-control mb-3" type="email" name="email" maxlength="190" value="<?php echo e($email); ?>" required>

        <label class="form-label" for="profile-phone">Phone</label>
        <input id="profile-phone" class="form-control mb-4" type="tel" name="phone" maxlength="30" value="<?php echo e($phone); ?>">

        <h2 class="h5">Change password</h2>
        <p class="page-note">Leave these fields blank to keep your current password.</p>
        <label class="form-label" for="current-password">Current password</label>
        <input id="current-password" class="form-control mb-3" type="password" name="current_password" autocomplete="current-password">

        <label class="form-label" for="new-password">New password</label>
        <input id="new-password" class="form-control mb-3" type="password" name="new_password" minlength="8" autocomplete="new-password">

        <label class="form-label" for="confirm-password">Confirm new password</label>
        <input id="confirm-password" class="form-control mb-4" type="password" name="confirm_password" minlength="8" autocomplete="new-password">

        <button class="btn btn-primary" type="submit">Save profile</button>
    </form>
</section>
<?php portal_end(); ?>
