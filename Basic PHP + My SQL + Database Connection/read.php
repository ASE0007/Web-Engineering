<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "php";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = $_POST['id'];
    $firstname = $conn->real_escape_string($_POST['firstname']);
    $lastname = $conn->real_escape_string($_POST['lastname']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "UPDATE MyGuests SET firstname='$firstname', lastname='$lastname', email='$email' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Record updated successfully.</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}

// Get the row to edit
$editId = isset($_GET['edit']) ? $_GET['edit'] : null;

// Fetch data
$sql = "SELECT * FROM MyGuests";
$result = $conn->query($sql);

echo "<h2>Guest List</h2>";
echo "<table border='1' cellpadding='10'>
<tr>
    <th>ID</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // If user is editing this row
        if ($editId == $row['id']) {
            echo "<form method='POST'>
            <tr>
                <td>{$row['id']}<input type='hidden' name='id' value='{$row['id']}'></td>
                <td><input type='text' name='firstname' value='{$row['firstname']}' required></td>
                <td><input type='text' name='lastname' value='{$row['lastname']}' required></td>
                <td><input type='email' name='email' value='{$row['email']}'></td>
                <td>
                    <input type='submit' name='update' value='Update'>
                    <a href='guests.php'>Cancel</a>
                </td>
            </tr>
            </form>";
        } else {
            // Normal display row
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['firstname']}</td>
                <td>{$row['lastname']}</td>
                <td>{$row['email']}</td>
                <td><a href='guests.php?edit={$row['id']}'>Edit</a></td>
              </tr>";
        }
    }
} else {
    echo "<tr><td colspan='5'>No records found.</td></tr>";
}

echo "</table>";
$conn->close();
?>
