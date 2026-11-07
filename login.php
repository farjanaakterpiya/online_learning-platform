<?php include('db.php') ?>
<?php
              if (isset($_POST['login'])) {
                extract($_POST);
                $password = sha1($password);

               
                echo "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
                $result = $conn->query("SELECT * FROM users WHERE email = '$email' AND password = '$password'");

                $row = $result->fetch_assoc();


                if ($result->num_rows > 0) {
                  session_start();
                  $_SESSION['email'] = $email;
                  $_SESSION['name'] = $row['name'];
                  $_SESSION['role'] = $row['role'];


                  if ($row['role'] == 'admin') {
                    $_SESSION['url'] = 'admin_dashboard.php';
                    header("Location: admin/admin_dashboard.php");
                  } else if ($row['role'] == 'employer') {
                    $_SESSION['url'] = 'employer_dashboard.php';
                    header("Location: admin/employer_dashboard.php");
                  } else if ($row['role'] == 'seeker') {
                    $_SESSION['url'] = 'seeker_dashboard.php';
                    header("Location: admin/seeker_dashboard.php");
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

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
</head>
<body>

<h2>Online Learning Platform</h2>
<h3>Admin Login</h3>

<p style="color:red;">
    <?php echo $error; ?>
</p>

<form method="POST">

    <label>Email</label><br>
    <input type="email" name="email" required>
    <br><br>

    <label>Password</label><br>
    <input type="password" name="password" required>
    <br><br>

    <button type="submit" name="login">Login</button>

</form>

</body>
</html>
