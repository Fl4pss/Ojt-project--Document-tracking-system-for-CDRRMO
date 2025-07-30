<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_id'])) {
    $document_id = $_POST['document_id'];

    $stmt = $conn->prepare("SELECT action_type, performed_by, performed_at, details 
                            FROM document_activity 
                            WHERE document_id = ?
                            ORDER BY performed_at DESC");
    $stmt->bind_param("i", $document_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<ul class='list-group'>";
        while ($row = $result->fetch_assoc()) {
            echo "<li class='list-group-item'>";
            echo "<strong>Action:</strong> {$row['action_type']}<br>";
            echo "<strong>Performed by:</strong> {$row['performed_by']}<br>";
            echo "<strong>Time:</strong> {$row['performed_at']}<br>";
            if (!empty($row['details'])) {
                echo "<strong>Details:</strong> {$row['details']}";
            }
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No history found for this document.</p>";
    }

    $stmt->close();
}
?>
