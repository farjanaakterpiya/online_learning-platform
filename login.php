<?php include_once("./db_config.php"); ?>

<?php
if (isset($_POST['login'])) {
    extract($_POST);
    $password = sha1($password);


    // echo  "SELECT * FROM users WHERE email = '$email' AND password = '$password'";


    $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");

    $row = $result->fetch_assoc();

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $row['name'];
        $_SESSION['role'] = $row['role'];

        if ($row['role'] == 'admin') {


            $_SESSION['url'] = 'admin/admin_dashboard.php';

            header("Location: admin/admin_dashboard.php");
            //exit;
        } else if ($row['role'] == 'instructor') {
            $_SESSION['url'] = 'instructor_dashboard.php';
            header("Location: admin/instructor_dashboard.php");
            //exit;
        } else if ($row['role'] == 'student') {
            $_SESSION['url'] = 'student_dashboard.php';
            header("Location: admin/student_dashboard.php");
            //exit;
        }
    } else {
        echo '
            <div class="alert border-0 bg-danger-subtle alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <div class="text-danger">Problem Login</div>
                </div>
            </div>
            ';
    }
}
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | EduSpace</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="auth-page">
    <header class="site-header">
        <a class="brand" href="index.html"><span class="brand-mark">E</span> EduSpace</a>
        <a class="text-link" href="index.html">← Back to home</a>
    </header>
    <main class="auth-wrap">
        <section class="auth-card">
            <span class="eyebrow">WELCOME BACK</span>
            <h1>Log in to your account</h1>
            <p>Continue your learning journey.</p>
            <form action="login.php" method="post">
                <label>Email address</label>
                <input type="email" name="email" placeholder="you@example.com" required>
                <label>Password</label><input type="password" name="password" placeholder="Enter your password" required>
                <button class="btn btn-primary btn-full" type="submit" name="login">Log in</button>
            </form>
            <p class="auth-bottom">New to EduSpace? <a href="register.php">Create an account</a></p>
        </section>
    </main>
    <script src="assets/js/app.js"></script>
</body>

</html>