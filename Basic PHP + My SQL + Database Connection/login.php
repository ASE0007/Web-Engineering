<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$error = "";

// Check if cookies exist
if (isset($_COOKIE['remember_user'])) {
    $_SESSION['username'] = $_COOKIE['remember_user'];
    header("Location: dashborad.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $conn->real_escape_string($_POST['username']);
    $pass = md5($_POST['password']); // use stronger hash in real apps

    $sql = "SELECT * FROM users WHERE username='$user' AND password='$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $_SESSION['username'] = $user;

        if (isset($_POST['remember'])) {
            setcookie("remember_user", $user, time() + (86400 * 7), "/"); // 7 days
        }

        header("Location: dashborad.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
    function togglePassword() {
        var pass = document.getElementById("password");
        if (pass.type === "password") {
            pass.type = "text";
        } else {
            pass.type = "password";
        }
    }
    </script>
</head>
<body>
    <h2>Login Page</h2>
    <form method="POST" action="">
        Username: <input type="text" name="username" required><br><br>
        Password: 
        <input type="password" name="password" id="password" required>
        <input type="checkbox" onclick="togglePassword()"> Show Password<br><br>

        <input type="checkbox" name="remember" value="1"> Remember Me<br><br>
        <input type="submit" value="Login">
    </form>

    <?php if ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>
</body>
</html>
