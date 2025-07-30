<?php
include 'db_connect.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        ?>

        <div class="container">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-hashtag"></i> Tracking No.</h6>
                            <p class="card-text"><?= htmlspecialchars($row['tracking_no']) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-file-lines"></i> Document Type</h6>
                            <p class="card-text"><?= htmlspecialchars($row['document_type']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-envelope-open-text"></i> Subject</h6>
                            <p class="card-text"><?= htmlspecialchars($row['subject']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-user"></i> Owner</h6>
                            <p class="card-text"><?= htmlspecialchars($row['sender']) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-calendar-days"></i> Created at</h6>
                            <p class="card-text">
    <?= date("F j, Y", strtotime(htmlspecialchars($row['created_at']))) ?>
</p>

                        </div>
                    </div>
                </div>

                <?php if (!empty($row['required_actions'])): ?>
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-list-check"></i> Required Actions</h6>
                            <p class="card-text"><?= nl2br(htmlspecialchars($row['required_actions'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($row['message'])): ?>
                <div class="col-md-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="card-title text-primary"><i class="fa-solid fa-message"></i> Message</h6>
                            <p class="card-text"><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($row['rejection_reason'])): ?>
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 border-danger">
                        <div class="card-body">
                            <h6 class="card-title text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Rejection Reason</h6>
                            <p class="card-text"><?= nl2br(htmlspecialchars($row['rejection_reason'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
    } else {
        echo "<p class='text-danger'>Document not found.</p>";
    }

    $stmt->close();
}
?>
