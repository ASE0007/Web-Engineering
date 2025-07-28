<?php
ob_start();  // 🔑 Starts output buffering
require 'connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $imageName = $_FILES['login_image']['name'];
    $imageTmp = $_FILES['login_image']['tmp_name'];

    // Folder to store temporary uploads
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedPath = $uploadDir . basename($imageName);
    move_uploaded_file($imageTmp, $uploadedPath);

    // Match image from database
    $stmt = $conn->prepare("SELECT image_name FROM login_images WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedImage);
    $stmt->fetch();
    $stmt->close();

    if (file_exists($uploadedPath)) {
        unlink($uploadedPath); // Clean up
    }

    if ($storedImage && $storedImage === $imageName) {
        header("Location: index.html");
        exit();  // 🔑 Make sure to call exit after header
    } else {
        echo "<h3>❌ Login Failed. Image doesn't match for user: <b>$username</b>.</h3>";
    }
}
ob_end_flush(); // 🔚 Ends buffering and sends headers
?>
