<?php
session_start();
require "db_connect.php"; // Make sure to include your DB connection

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['document_id'])) {
    $docId = intval($_POST['document_id']); // Convert to integer for safety

    $query = "UPDATE documents SET current_status = 'Archived' WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $docId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>
