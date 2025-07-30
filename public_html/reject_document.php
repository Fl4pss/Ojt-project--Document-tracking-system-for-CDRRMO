<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id"]) && isset($_POST["reason"])) {
        $id = intval($_POST["id"]);
        $reason = trim($_POST["reason"]);

        $query = "UPDATE documents SET current_status = 'Rejected', received = 'No', rejection_reason = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $reason, $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "success";
        } else {
            echo "error";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "error";
    }
}

mysqli_close($conn);
?>  