<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['document_id'])) {
        $document_id = $_POST['document_id'];
        
        // Delete the document from the database
        $query = "DELETE FROM documents WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $document_id);
        
        if ($stmt->execute()) {
            // After successful deletion, refresh the page
            echo "<script>location.reload();</script>";
        } else {
            // If deletion fails, show an error message
            echo "Error deleting document.";
        }
    }
}
?>
