<?php
session_start();
require_once __DIR__ . '/db_config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $conn->prepare('SELECT id, name, email, password, role, status FROM users WHERE email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $valid = false;
    if ($row && $row['status'] === 'active') {
        $storedPassword = (string) $row['password'];
        if (password_verify($password, $storedPassword)) {
            $valid = true;
        } elseif (hash_equals($storedPassword, sha1($password))) {
            $valid = true;
            $upgradedPassword = password_hash($password, PASSWORD_DEFAULT);
            $upgrade = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
            $upgrade->bind_param('si', $upgradedPassword, $row['id']);
            $upgrade->execute();
            $upgrade->close();
        }
    }

    if ($valid) {
        session_regenerate_id(true);
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = (int)$row['id'];
        if ($row['role'] === 'admin') $destination = 'admin/admin_dashboard.php';
        elseif ($row['role'] === 'instructor') $destination = 'admin/instructor_dashboard.php';
        else $destination = 'admin/student_dashboard.php';
        $_SESSION['url'] = $destination;
        header('Location: ' . $destination);
        exit;
    }
    $error = 'Login failed. Please check your email, password, and account status.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login | EduSpace</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body class="auth-page"><header class="site-header"><a class="brand" href="index.php"><span class="brand-mark">E</span> EduSpace</a><a class="text-link" href="index.php">← Back to home</a></header>
<main class="auth-wrap"><section class="auth-card"><span class="eyebrow">WELCOME BACK</span><h1>Log in to your account</h1><p>Continue your learning journey.</p>
<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
<form action="login.php" method="post"><label>Email address</label><input type="email" name="email" placeholder="you@example.com" required><label>Password</label><input type="password" name="password" placeholder="Enter your password" required><button class="btn btn-primary btn-full" type="submit" name="login">Log in</button></form>
<p class="auth-bottom">New to EduSpace? <a href="register.php">Create an account</a></p></section></main><script src="assets/js/app.js"></script></body></html>
