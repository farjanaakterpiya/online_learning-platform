    <!doctype html>
    <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Register | EduSpace</title>
            <link rel="stylesheet" href="assets/css/style.css">
        </head>
        <body class="auth-page"><header class="site-header">
            <a class="brand" href="index.html"><span class="brand-mark">E</span> EduSpace</a>
            <a class="text-link" href="index.html">← Back to home</a>
        </header>
        <main class="auth-wrap">
            <section class="auth-card"><span class="eyebrow">START LEARNING</span>
            <h1>Create your account</h1><p>Join the EduSpace learning community.</p>

    <form action="register.php" method="post">
        <label>Full name</label>
        <input name="name" placeholder="Your full name" required>
        <label>Email address</label><input type="email" name="email" placeholder="you@example.com" required>
        <label>Password</label><input type="password" name="password" placeholder="Create a password" minlength="6" required>
        <button class="btn btn-primary btn-full" type="submit">Create account</button>
    </form>

        <p class="auth-bottom">Already registered? <a href="login.php">Log in</a></p>
    </section>
        </main>
        <script src="assets/js/app.js"></script>
        </body>
        </html>