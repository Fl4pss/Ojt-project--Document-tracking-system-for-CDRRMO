<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // First, update the document
        $query = "UPDATE documents SET current_status = 'Approved', received = 'yes', approved_by = ? WHERE id = ?";
        if ($stmt = mysqli_prepare($conn, $query)) {
            mysqli_stmt_bind_param($stmt, "si", $username, $id);

            if (mysqli_stmt_execute($stmt)) {
                // ✅ Get tracking_no and sender (to notify the document owner)
                $docQuery = "SELECT tracking_no, sender FROM documents WHERE id = ?";
                if ($docStmt = mysqli_prepare($conn, $docQuery)) {
                    mysqli_stmt_bind_param($docStmt, "i", $id);
                    mysqli_stmt_execute($docStmt);
                    mysqli_stmt_bind_result($docStmt, $tracking_no, $sender);
                    mysqli_stmt_fetch($docStmt);
                    mysqli_stmt_close($docStmt);

                    // ✅ Compose notification
                    $message = "Document with Tracking No. $tracking_no has been approved.";
                    $notified_user_id = json_encode([$sender]); // Wrap in JSON format
                    $action = "forward"; // Add action value

                    // ✅ Insert into notifications
                    $notifQuery = "INSERT INTO notifications 
                                   (document_id, tracking_no, message, status, notified_user_id, sender, created_at, action)
                                   VALUES (?, ?, ?, 'unread', ?, ?, NOW(), ?)";
                    if ($notifStmt = mysqli_prepare($conn, $notifQuery)) {
                        mysqli_stmt_bind_param($notifStmt, "isssss", $id, $tracking_no, $message, $notified_user_id, $username, $action);
                        mysqli_stmt_execute($notifStmt);
                        mysqli_stmt_close($notifStmt);
                    }
                }

                echo "success";
            } else {
                echo "error";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "error";
        }
    }
} else {
    echo "User not logged in.";
}

mysqli_close($conn);
?>
