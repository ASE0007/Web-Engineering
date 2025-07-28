<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert 5 Guests</title>
</head>
<body>
<h2>Enter Details of 5 People</h2>

<form method="post" action="">
    <?php for ($i = 1; $i <= 2; $i++): ?>
        <fieldset style="margin-bottom: 15px;">
            <legend>Person <?= $i ?>:</legend>
            First Name: <input type="text" name="firstname[]" required><br>
            Last Name: <input type="text" name="lastname[]" required><br>
            Email: <input type="email" name="email[]"><br>
        </fieldset>
    <?php endfor; ?>
    <input type="submit" name="submit" value="Insert Data">
</form>

<?php
// Only run after form submission
if (isset($_POST['submit'])) {
    // Show all errors
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "php";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data arrays
    $firstnames = $_POST['firstname'];
    $lastnames = $_POST['lastname'];
    $emails = $_POST['email'];

    $successCount = 0;

    for ($i = 0; $i < 5; $i++) {
        $fname = $conn->real_escape_string($firstnames[$i]);
        $lname = $conn->real_escape_string($lastnames[$i]);
        $email = $conn->real_escape_string($emails[$i]);

        $sql = "INSERT INTO MyGuests (firstname, lastname, email)
                VALUES ('$fname', '$lname', '$email')";

        if ($conn->query($sql) === TRUE) {
            $successCount++;
        } else {
            echo "❌ Error inserting data for person " . ($i + 1) . ": " . $conn->error . "<br>";
        }
    }

    echo "<br>✅ Successfully inserted $successCount out of 5 people.";

    $conn->close();
}
?>
</body>
</html>
