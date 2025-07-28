<?php
require 'connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);  // sanitize id as integer

    // Prepare statement to prevent SQL injection
    $sql = "DELETE FROM biodata WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect back to view page after deletion
        header("Location: view_biodata.php?message=deleted");
        exit();
    } else {
        echo "❌ Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    echo "❌ No ID provided for deletion.";
}

$conn->close();
?>
