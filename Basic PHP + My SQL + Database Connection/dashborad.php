<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch data
$sql = "SELECT * FROM MyGuests";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h2>Welcome, <?= $_SESSION['username'] ?>!</h2>
<p><a href="out.php">Logout</a></p>

<h3>Guest List:</h3>

<?php if ($result->num_rows > 0): ?>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Reg Date</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['firstname'] ?></td>
            <td><?= $row['lastname'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['reg_date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p>No data found.</p>
<?php endif; ?>

</body>
</html>
