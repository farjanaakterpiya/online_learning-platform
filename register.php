<?php
session_start();
require_once __DIR__ . '/db_config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        $error = 'Please enter a valid name and email. Password must be at least 6 characters.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $exists = $stmt->get_result()->num_rows > 0;
        $stmt->close();

        if ($exists) {
            $error = 'This email is already registered. Please log in.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'student', 'active')");
            $stmt->bind_param('sss', $name, $email, $hash);
            if ($stmt->execute()) {
                $success = 'Account created successfully. You can now log in.';
            } else {
                $error = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register | EduSpace</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<header class="site-header"><a class="brand" href="index.php"><span class="brand-mark">E</span> EduSpace</a><a class="text-link" href="index.php">← Back to home</a></header>
<main class="auth-wrap"><section class="auth-card">
<span class="eyebrow">START LEARNING</span><h1>Create your account</h1><p>Join the EduSpace learning community.</p>
<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
<form action="register.php" method="post">
<label>Full name</label><input name="name" placeholder="Your full name" required>
<label>Email address</label><input type="email" name="email" placeholder="you@example.com" required>
<label>Password</label><input type="password" name="password" placeholder="Create a password" minlength="6" required>
<button class="btn btn-primary btn-full" type="submit">Create account</button>
</form>
<p class="auth-bottom">Already registered? <a href="login.php">Log in</a></p>
</section></main><script src="assets/js/app.js"></script></body></html>
