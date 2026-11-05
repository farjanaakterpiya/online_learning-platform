
<?php
session_start();
include "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["email"] = $user["email"];

            header("Location: dashboard.php");
            exit();

        } else {
            $error = "Incorrect password!";
        }

    } else {
        $error = "Email not found!";
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

    <button type="submit">Login</button>

</form>

</body>
</html>
