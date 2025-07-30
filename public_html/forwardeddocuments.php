<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html><html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Documents</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="css\themess.css" rel="stylesheet" media="all">


</head>

<body>
    <div class="page-wrapper">
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="dashboard.php">
                            <img src="img\sanjuan.png" alt="CoolAdmin" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="dashboard.php">
                    <img src="img\sanjuan.png" alt="Cool Admin" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="active has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <hr>
                        <p class="sidebar-section-title">Documents Management</p>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Documents</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="documents.php">Documents</a>
                                </li>
                                <li>
                                    <a href="receivingDocuments.php">Incoming</a>
                                </li>
                                <li>
                                    <a href="forwardeddocuments.php">Forwarded</a>
                                </li>

                                <li>
                                    <a href="archivedDocuments.php">Archived</a>
                                </li>
                                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Superadmin'): ?>
                                    <li>
                                        <a href="documentLogs.php">Logs</a>
                                    </li>
                                <?php endif; ?>

                            </ul>
                        </li>
                        <hr>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Superadmin'): ?>
                            <hr>
                            <p class="sidebar-section-title">User Management</p>
                            <li class="has-sub">
                                <a class="js-arrow" href="#">
                                    <i class="fas fa-copy"></i>Account Management</a>
                                <ul class="list-unstyled navbar__sub-list js-sub-list">
                                    <li>
                                        <a href="users.php">Users</a>
                                    </li>
                                    <li>
                                        <a href="userresgistration.php">Register User</a>
                                    </li>
                                </ul>
                            </li> 
                        <?php endif; ?>


                    </ul>
                </nav>
            </div>
        </aside>

        <!-- PAGE CONTAINER-->
        <div class="page-container"> <!-- HEADER DESKTOP-->
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                                <input class="au-input au-input--xl" type="text" name="search"
                                    placeholder="Search for datas &amp; reports..." />
                                <button class="au-btn--submit" type="submit">
                                    <i class="zmdi zmdi-search"></i>
                                </button>
                            </form>
                            <div class="header-button">
                                <?php
                                include 'db_connect.php';

                                $username = $_SESSION['username'];

                                $sql = "
                                SELECT 
                                    n.*, 
                                    d.document_type, 
                                    d.subject, 
                                    d.attachment, 
                                    d.sender, 
                                    d.date_received, 
                                    d.required_actions 
                                FROM notifications n
                                LEFT JOIN documents d ON n.document_id = d.id
                                WHERE n.status = 'unread' AND n.notified_user_id LIKE ?
                                ORDER BY n.created_at DESC
                                ";
                                
                                $stmt = $conn->prepare($sql);
                                $likePattern = '%"'.$username.'"%';
                                $stmt->bind_param("s", $likePattern);                                
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $notification_count = $result->num_rows;
                                ?>

                                <div class="noti-wrap">
                                    <div class="noti__item js-item-menu">
                                        <i class="zmdi zmdi-notifications"></i>
                                        <span class="quantity"><?php echo $notification_count; ?></span>
                                        <div class="notifi-dropdown js-dropdown">
                                            <div class="notifi__title">
                                                <p>You have <?php echo $notification_count; ?> new notifications</p>
                                            </div>

                                            <?php while ($row = $result->fetch_assoc()) { 
                                                // Add this logic at the top of the loop
                                                $isUnread = $row['status'] === 'unread';
                                                $iconClass = $isUnread ? 'zmdi-email-open' : 'zmdi-email';
                                                $iconBgClass = $isUnread ? 'bg-c1' : 'bg-secondary';
                                                $readClass = $isUnread ? 'unread' : 'read';
                                            ?>
                                                <div class="notifi__item <?php echo $readClass; ?>"
                                                    data-docid="<?php echo $row['document_id']; ?>" 
                                                    data-trackingno="<?php echo htmlspecialchars($row['tracking_no']); ?>"
                                                    data-doctype="<?php echo htmlspecialchars($row['document_type'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-subject="<?php echo htmlspecialchars($row['subject'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-attachment="<?php echo htmlspecialchars($row['attachment'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-sender="<?php echo htmlspecialchars($row['sender'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-datereceived="<?php echo !empty($row['date_received']) ? date('F j, Y', strtotime($row['date_received'])) : ''; ?>"
                                                    data-actions="<?php echo htmlspecialchars($row['required_actions'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    onclick="showNotificationModal(this)">
                                                    
                                                    <div class="<?php echo $iconBgClass; ?> img-cir img-40">
                                                        <i class="zmdi <?php echo $iconClass; ?>"></i>
                                                    </div>
                                                    <div class="content">
                                                        <p><?php echo htmlspecialchars($row['message'], ENT_QUOTES, 'UTF-8'); ?></p>
                                                        <span class="date"><?php echo date("F j, Y, g:i a", strtotime($row['created_at'])); ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>



                                            <div class="notifi__footer">
                                                <a href="notifications.php">See all notifications</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Dropdown -->
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="img\cdrrmo1.png" alt="John Doe" />
                                        </div>
                                        <div class="content">
                                            <a href="#"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . " admin" : 'Guest'; ?></a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="img\cdrrmo1.png" alt="John Doe" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . " admin" : 'Guest'; ?></a>
                                                    </h5>
                                                    <span class="email">Cdrrmoadmin@gmail.com</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="index.php">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="title-7 m-b-35">Forwarded Documents</h1>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                        <div class="rs-select2--light rs-select2--md">
                                            <select class="js-select2" name="property">
                                                <option selected="selected">All Properties</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <div class="rs-select2--light rs-select2--sm">
                                            <select class="js-select2" name="time">
                                                <option selected="selected">Today</option>
                                                <option value="">3 Days</option>
                                                <option value="">1 Week</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                        <button class="au-btn-filter">
                                            <i class="zmdi zmdi-filter-list"></i>filters</button>
                                    </div>
                                </div>

                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Tracking No.</th>
                                                <th>Document Type & Detail</th>
                                                <th>Attachment</th>
                                                <th>Date & Time Forwarded</th>
                                                <th>Current Status</th>
                                                <th>Required Actions</th>
                                                <th>Sent To</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $loggedInUser = $_SESSION['username'];

                                            $query = "SELECT * FROM documents WHERE forwarded='yes' AND owner_id = '$loggedInUser' ORDER BY created_at DESC";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                echo "<tr class='tr-shadow {$row['current_status']}'>";
                                                echo "<td><label class='au-checkbox'><input type='checkbox'><span class='au-checkmark'></span></label></td>";
                                                echo "<td>{$row['tracking_no']}</td>";
                                                echo "<td>{$row['document_type']}</td>";
                                                echo "<td><a href='{$row['attachment']}' target='_blank'>" . basename($row['attachment']) . "</a></td>";
                                                echo "<td>{$row['updated_at']}</td>";
                                                echo "<td><span class='status--{$row['current_status']}'>{$row['current_status']}</span></td>";
                                                echo "<td>{$row['required_actions']}</td>";
                                                echo "<td>
                                                    <div class='dropdown'>
                                                        <button class='btn btn-secondary dropdown-toggle' type='button' id='sentToDropdown{$row['id']}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            View Recipients
                                                        </button>
                                                        <div class='dropdown-menu' aria-labelledby='sentToDropdown{$row['id']}'>";
                                                        
                                                        $sentToList = explode(',', $row['sent_to']); // Assuming sent_to is stored as a comma-separated list
                                                        foreach ($sentToList as $recipient) {
                                                            echo "<a class='dropdown-item'>$recipient</a>";
                                                        }

                                            echo "      </div>
                                                    </div>
                                                </td>";

                                                echo "<td>
                                                <div class='table-data-feature'>
                                                    <button class='item view-btn' title='View' data-attachment='{$row['attachment']}'>
                                                            <i class='zmdi zmdi-eye'></i>
                                                    </button>
                                                        <button class='item return-btn' data-id='{$row['id']}' title='Return'><i class='fa-solid fa-rotate-left'></i></button>
                                                    <button class='item download-btn' title='Download' 
                                                        data-id='{$row['id']}'
                                                        data-tracking='{$row['tracking_no']}'
                                                        data-type='{$row['document_type']}'
                                                        data-sender='{$row['sender']}'
                                                        data-date='{$row['date_received']}'
                                                        data-attachment='{$row['attachment']}'>
                                                        <i class='fa-solid fa-download'></i>
                                                    </button>
                                                    </div>
                                                </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Document Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Document Preview</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <iframe id="previewFrame" src="" width="100%" height="500px" style="border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    <!-- Return Confirmation Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Confirm Return</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to revert this forwarded document?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmReturn">Yes, Revert</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Download Confirmation Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Confirm Download</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><strong>Tracking No:</strong> <span id="docTracking"></span></p>
                <p><strong>Document Type:</strong> <span id="docType"></span></p>
                <p><strong>Sender:</strong> <span id="docSender"></span></p>
                <p><strong>Date Received:</strong> <span id="docDate"></span></p>
                <p><strong>Attachment:</strong> <a href="#" id="docAttachment" target="_blank"><span id="docAttachmentName"></span></a></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="confirmDownloadBtn" class="btn btn-primary" download>Confirm Download</a>
            </div>
        </div>
    </div>
</div>
<!-- Forward Modal using Bootstrap -->
<div class="modal fade" id="forwardModal" tabindex="-1" role="dialog" aria-labelledby="forwardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forwardModalLabel">Forward Document</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="forwardForm">
          <!-- Department Selection -->
          <div class="form-group">
            <label for="sentToDropdown">Sent To</label>
            <select name="sent_to" id="sentToDropdown" class="form-control" required>
              <option value="" disabled selected>Select a department</option>
              <option value="Admin">Admin</option>
              <option value="Training">Training</option>
              <option value="Operations">Operations</option>
              <option value="Warning">Warning</option>
              <option value="Research">Research</option>
              <option value="Planning">Planning</option>
            </select>
          </div>
          <!-- Subject Input -->
          <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter subject" required>
          </div>
          <!-- Message/Notes Input -->
          <div class="form-group">
            <label for="message">Message</label>
            <textarea class="form-control" id="message" name="message" placeholder="Enter your message" rows="3" required></textarea>
          </div>
          <!-- Additional fields (e.g., Document ID) can be added as hidden inputs if needed -->
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" form="forwardForm">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- Updated Notification Detail Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content shadow border-0 rounded-lg">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title font-weight-bold" id="notificationModalLabel">
          <i class="zmdi zmdi-notifications m-r-5"></i> Notification Details
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body px-4 py-3" style="background-color: #f5f8fa;">
        <div class="row mb-2">
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Tracking No:</label>
            <div class="bg-white p-2 rounded border" id="modalTrackingNo"></div>
          </div>
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Document Type:</label>
            <div class="bg-white p-2 rounded border" id="modalDocType"></div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Subject:</label>
            <div class="bg-white p-2 rounded border" id="modalSubject"></div>
          </div>
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Attachment:</label>
            <div class="bg-white p-2 rounded border" id="modalAttachment"></div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Sender:</label>
            <div class="bg-white p-2 rounded border" id="modalSender"></div>
          </div>
          <div class="col-md-6">
            <label class="text-muted font-weight-bold">Date Received:</label>
            <div class="bg-white p-2 rounded border" id="modalDateReceived"></div>
          </div>
        </div>

        <div class="row mb-2">
          <div class="col-12">
            <label class="text-muted font-weight-bold">Required Actions:</label>
            <div class="bg-white p-2 rounded border" id="modalRequiredActions"></div>
          </div>
        </div>
      </div>

      <div class="modal-footer bg-light border-top-0">
        <small class="text-muted">Click outside the modal or press "Close" to dismiss</small>
        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
    <script src="vendor/jquery-3.2.1.min.js"></script>

    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>

    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

    <script src="js/main.js"></script>
    <script>
        $(document).ready(function () {
            $('#sentToDropdown').select2({
                placeholder: "Select a department",
                allowClear: true
            });
        });
    </script>
    <script>
        $(document).ready(function () {
    $(".view-btn").click(function () {
        let attachmentUrl = $(this).attr("data-attachment");

        // Ensure the file is a valid previewable type
        let fileExtension = attachmentUrl.split('.').pop().toLowerCase();
        let previewableFormats = ["pdf", "jpg", "jpeg", "png"];

        if (previewableFormats.includes(fileExtension)) {
            $("#previewFrame").attr("src", attachmentUrl);
            $("#previewModal").modal("show");
        } else {
            alert("This file type cannot be previewed. Please download it instead.");
        }
    });
});
        // download button
        $(document).ready(function () {
            $(".download-btn").click(function () {
                let docTracking = $(this).attr("data-tracking");
                let docType = $(this).attr("data-type");
                let docSender = $(this).attr("data-sender");
                let docDate = $(this).attr("data-date");
                let docAttachment = $(this).attr("data-attachment");

                // Extract file name from attachment URL
                let fileName = docAttachment.split('/').pop(); 

                // Set modal content
                $("#docTracking").text(docTracking);
                $("#docType").text(docType);
                $("#docSender").text(docSender);
                $("#docDate").text(docDate);
                $("#docAttachment").attr("href", docAttachment);
                $("#docAttachmentName").text(fileName); // Show the file name
                $("#confirmDownloadBtn").attr("href", docAttachment);

                // Show the modal
                $("#downloadModal").modal("show");
            });
        });

        $(document).ready(function () {
            let returnDocId;
            $('.return-btn').on('click', function () {
                returnDocId = $(this).data('id');
                $('#returnModal').modal('show');
            });

            $('#confirmReturn').on('click', function () {
                window.location.href = 'returnDocument.php?id=' + returnDocId;
            });
        });
    </script>
        <script>
function fetchNotifications() {
    fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
            const notiWrap = document.querySelector('.notifi-dropdown .notifi__title p');
            const notiList = document.querySelector('.notifi-dropdown');

            // Update notification count
            document.querySelector('.quantity').textContent = data.length;

            // Update notification dropdown
            notiWrap.innerHTML = `You have ${data.length} new notifications`;

            // Remove old notifications
            document.querySelectorAll('.notifi__item').forEach(item => item.remove());

            // Add new notifications
            data.forEach(notification => {
                const notiItem = document.createElement('div');
                notiItem.classList.add('notifi__item');
                notiItem.innerHTML = `
                    <div class="bg-c1 img-cir img-40">
                        <i class="zmdi zmdi-email-open"></i>
                    </div>
                    <div class="content">
                        <p>${notification.document_id}</p>
                        <span class="date">${notification.created_at}</span>
                    </div>
                `;
                notiList.insertBefore(notiItem, notiList.lastElementChild);
            });
        })
        .catch(error => console.error('Error fetching notifications:', error));
}

setInterval(fetchNotifications, 5000);

    </script>
    <script>
function showNotificationModal(element) {
    const docId = element.getAttribute('data-docid');
    const trackingNo = element.getAttribute('data-trackingno');
    const docType = element.getAttribute('data-doctype');
    const subject = element.getAttribute('data-subject');
    const attachment = element.getAttribute('data-attachment');
    const sender = element.getAttribute('data-sender');
    const dateReceived = element.getAttribute('data-datereceived');
    const requiredActions = element.getAttribute('data-actions');

    document.getElementById('modalTrackingNo').textContent = trackingNo;
    document.getElementById('modalDocType').textContent = docType;
    document.getElementById('modalSubject').textContent = subject;
    document.getElementById('modalAttachment').innerHTML = attachment 
        ? `<a href="${attachment}" target="_blank">View Attachment</a>` 
        : 'No attachment';
    document.getElementById('modalSender').textContent = sender;
    document.getElementById('modalDateReceived').textContent = dateReceived;
    document.getElementById('modalRequiredActions').textContent = requiredActions;

    $('#notificationModal').modal('show');

    // Send AJAX request to mark notification as read
    fetch('mark_notification_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `document_id=${encodeURIComponent(docId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Change icon to show it's been read
            const iconContainer = element.querySelector('.bg-c1');
            iconContainer.classList.remove('bg-c1');
            iconContainer.classList.add('bg-secondary');
            const icon = iconContainer.querySelector('i');
            icon.classList.remove('zmdi-email-open');
            icon.classList.add('zmdi-email'); // Use closed email icon to indicate it's read

            // Optionally reduce opacity or change background
            element.style.opacity = "0.6";
        }
    });
}

function updateNotificationBadge() {
    fetch('get_unread_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.querySelector('.quantity');
            const titleText = document.querySelector('.notifi__title p');

            badge.textContent = data.count;
            titleText.textContent = `You have ${data.count} new notification${data.count === 1 ? '' : 's'}`;
        })
        .catch(error => console.error('Error updating badge count:', error));
}

</script>

</body>

</html>
<!-- end document-->