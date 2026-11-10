<?php
require_once "auth.php";
require_once "db_config.php";

$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!verifyCsrfToken($_POST['csrf_token'] ?? null)) {
        $errorMessage = "The form expired. Please try again.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || !is_string($password) || $password === '') {
        $errorMessage = "Please enter a valid email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? AND status = 'active' LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $passwordMatches = false;
        $legacyPassword = false;

        if ($user) {
            $passwordMatches = password_verify($password, $user['password']);

            // Older demo accounts in the supplied SQL file use SHA1 passwords.
            if (!$passwordMatches && hash_equals($user['password'], sha1($password))) {
                $passwordMatches = true;
                $legacyPassword = true;
            }
        }

        if ($passwordMatches) {
            if ($legacyPassword) {
                $newPasswordHash = password_hash($password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update->bind_param("si", $newPasswordHash, $user['id']);
                $update->execute();
                $update->close();
            }

            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'] !== '' ? $user['name'] : $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $dashboards = [
                'admin' => 'admin/admin-dashboard.php',
                'instructor' => 'admin/instructor-dashboard.php',
                'student' => 'admin/student-dashboard.php',
            ];

            if (isset($dashboards[$user['role']])) {
                header("Location: " . $dashboards[$user['role']]);
                exit;
            }

            session_unset();
            session_destroy();
        }

        $errorMessage = "Email or password is incorrect, or the account is inactive.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LearnHub - Login</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<main class="login-container">
    <h2>Learn<span>Hub</span></h2>
    <h3>Login to Your Account</h3>

    <?php if ($errorMessage !== ''): ?>
        <p class="error" role="alert"><?php echo escapeHtml($errorMessage); ?></p>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo escapeHtml(csrfToken()); ?>">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Enter email" autocomplete="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Enter password" autocomplete="current-password" required>

        <button type="submit" class="btn">Login</button>
    </form>

    <a href="index.html">Back to Home</a>
</main>
</body>
</html>
