<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['document_id'])) {
    $document_id = intval($_POST['document_id']);

    // First, get the current status of the document
    $select_query = "SELECT current_status FROM documents WHERE id = ?";
    $stmt = $conn->prepare($select_query);
    $stmt->bind_param('i', $document_id);
    $stmt->execute();
    $stmt->bind_result($current_status);
    $stmt->fetch();
    $stmt->close();

    if ($current_status === 'Archived') {
        // If it's Archived, retrieve it (set current_status back to Active, and previous_status to Archived)
        $update_query = "UPDATE documents 
                         SET current_status = 'Approved', 
                             previous_status = 'Archived', 
                             updated_at = NOW() 
                         WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('i', $document_id);

        if ($update_stmt->execute()) {
            // Successfully updated
            header("Location: archivedDocuments.php?message=DocumentRetrieved");
            exit();
        } else {
            // If update fails
            echo "Error updating document: " . $conn->error;
        }

        $update_stmt->close();
    } else {
        echo "Document is not in Archived status.";
    }
} else {
    echo "No document ID provided.";
}
?>
