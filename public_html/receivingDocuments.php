<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <title>Dashboard</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

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
    <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="#">
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
                                    <li>
                                        <a href="documentLogs.php">Logs</a>
                                    </li>
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
        <div class="page-container">
        <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                        <form class="form-header" action="" method="POST">
                            <input class="au-input au-input--xl" type="text" name="search" placeholder="Search for datas & reports..." />
                            <button class="au-btn--submit" type="submit"><i class="zmdi zmdi-search"></i></button>
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
                                <h1 class="title-7 m-b-35">Incoming Documents</h1>
                                <div class="table-data__tool">
                                    <div class="table-data__tool-left">
                                    <div class="rs-select2--light rs-select2--md">
                                            <select class="js-select2" name="property">
                                                <option selected="selected">Document Type</option>
                                                <option value="Memorandum">Memorandum</option>
                                                <option value="Letter">Letter</option>
                                                <option value="Endorsement">Endorsement</option>
                                                <option value="Invitation">Invitation</option>
                                                <option value="Certificate">Certificate</option>
                                                <option value="Report">Report</option>
                                                <option value="Other">Other</option>
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
                                            <th>Sender</th>
                                            <th>Date & Time Received</th>
                                            <th>Required Actions</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                // Query to get documents where the logged-in user is inside the JSON array of `sent_to`
                                $search = isset($_POST['search']) ? trim($_POST['search']) : '';
                                $username_json = json_encode($_SESSION['username']);
                                
                                if (!empty($search)) {
                                    $query = "SELECT * FROM documents 
                                              WHERE current_status = 'Pending' 
                                              AND JSON_CONTAINS(sent_to, CAST(? AS JSON)) 
                                              AND (
                                                  tracking_no LIKE CONCAT('%', ?, '%') 
                                                  OR document_type LIKE CONCAT('%', ?, '%') 
                                                  OR sender LIKE CONCAT('%', ?, '%') 
                                                  OR required_actions LIKE CONCAT('%', ?, '%')
                                              )
                                              ORDER BY created_at DESC";
                                
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("sssss", $username_json, $search, $search, $search, $search);
                                } else {
                                    $query = "SELECT * FROM documents 
                                              WHERE current_status = 'Pending' 
                                              AND JSON_CONTAINS(sent_to, CAST(? AS JSON)) 
                                              ORDER BY created_at DESC";
                                
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("s", $username_json);
                                }
                                
                                $stmt->execute();
                                $result = $stmt->get_result();
                                

                                while ($row = $result->fetch_assoc()) {
                                    $tracking_no = htmlspecialchars($row['tracking_no'], ENT_QUOTES, 'UTF-8');
                                    $document_type = htmlspecialchars($row['document_type'], ENT_QUOTES, 'UTF-8');
                                    $attachment = htmlspecialchars($row['attachment'], ENT_QUOTES, 'UTF-8');
                                    $sender = htmlspecialchars($row['sender'], ENT_QUOTES, 'UTF-8');
                                    $date_received = htmlspecialchars($row['date_received'], ENT_QUOTES, 'UTF-8');
                                    $current_status = htmlspecialchars($row['current_status'], ENT_QUOTES, 'UTF-8');
                                    $required_actions = htmlspecialchars($row['required_actions'], ENT_QUOTES, 'UTF-8');
                                    $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');

                                    // Decode sent_to JSON array
                                    $sentToList = json_decode($row['sent_to'], true);
                                    $sentToText = is_array($sentToList) ? implode(', ', $sentToList) : $row['sent_to'];

                                    echo "<tr class='tr-shadow $current_status'>";
                                    echo "<td><label class='au-checkbox'><input type='checkbox'><span class='au-checkmark'></span></label></td>";
                                    echo "<td>$tracking_no</td>";
                                    echo "<td>$document_type</td>";
                                    echo "<td><a href='$attachment' target='_blank'>" . basename($attachment) . "</a></td>";
                                    echo "<td>$sender</td>";
                                    echo "<td>$date_received</td>";
                                    echo "<td>$required_actions</td>";
                                    echo "<td>
                                            <div class='table-data-feature'>
                                                <button class='item view-btn' title='View' data-attachment='{$row['attachment']}'>
                                                    <i class='zmdi zmdi-eye'></i>
                                                </button>
                                                <button class='item approve-btn' 
                                                    data-id='$id' 
                                                    data-sender='$sender' 
                                                    data-type='$document_type' 
                                                    data-tracking='$tracking_no' 
                                                    data-actions='$required_actions' 
                                                    data-toggle='modal' 
                                                    data-target='#confirmModal' 
                                                    title='Approve'>
                                                    <i class='fa-solid fa-check'></i>
                                                </button>
                                                <button class='item reject-btn' 
                                                    data-id='$id' 
                                                    data-sender='$sender' 
                                                    data-type='$document_type' 
                                                    data-tracking='$tracking_no' 
                                                    data-toggle='modal' 
                                                    data-target='#rejectModal' 
                                                    title='Reject'>
                                                    <i class='fa-solid fa-times'></i>
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
    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Document Approval</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to accept this document from <strong><span id="docSender"></span></strong>?
                    </p>

                    <table class="table">
                        <tr>
                            <th>Document Type</th>
                            <td><span id="docType"></span></td>
                        </tr>
                        <tr>
                            <th>Tracking No.</th>
                            <td><span id="docTrackingNo"></span></td>
                        </tr>
                        <tr>
                            <th>Actions Needed</th>
                            <td><textarea class="form-control" id="docActions" readonly></textarea></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="confirmApprove">Accept Document</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Document Rejection</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to reject the document from <strong><span
                                id="rejectDocSender"></span></strong>?</p>

                    <table class="table">
                        <tr>
                            <th>Document Type</th>
                            <td><span id="rejectDocType"></span></td>
                        </tr>
                        <tr>
                            <th>Tracking No.</th>
                            <td><span id="rejectDocTrackingNo"></span></td>
                        </tr>
                        <tr>
                            <th>Reason for Rejection</th>
                            <td>
                                <select class="form-control" id="rejectReason">
                                    <option value="Incomplete Information">Incomplete Information</option>
                                    <option value="Incorrect Document Type">Incorrect Document Type</option>
                                    <option value="Requires Further Revision">Requires Further Revision</option>
                                    <option value="Other">Other (Specify Below)</option>
                                </select>
                            </td>
                        </tr>
                        <tr id="otherReasonRow" style="display: none;">
                            <th>Other Reason</th>
                            <td>
                                <textarea class="form-control" id="otherRejectReason"
                                    placeholder="Specify reason..."></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmReject">Reject Document</button>
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
            var docId;

            $(document).on('click', '.approve-btn', function () {
                docId = $(this).data('id');
            });

            $('#confirmApprove').click(function () {
                $.post('approve_document.php', { id: docId }, function (response) {
                    if (response === "success") {
                        alert("Document Approved Successfully!");
                        location.reload();
                    } else {
                        alert("Error approving document.");
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            // Approve Button Functionality
            $(".approve-btn").click(function () {
                var docId = $(this).data("id");
                var sender = $(this).data("sender");
                var type = $(this).data("type");
                var trackingNo = $(this).data("tracking");
                var actions = $(this).data("actions");
                var acceptedBy = "Records Section"; // Static or dynamic value

                $("#docSender").text(sender);
                $("#docType").text(type);
                $("#docTrackingNo").text(trackingNo);
                $("#docActions").val(actions);
                $("#docAcceptedBy").text(acceptedBy);
                $("#confirmApprove").data("id", docId);
                $("#confirmModal").modal("show");
            });

            $("#confirmApprove").click(function () {
                var docId = $(this).data("id");

                $.post("approve_document.php", { id: docId }, function (response) {
                    if (response === "success") {
                        alert("Document Approved Successfully!");
                        location.reload();
                    } else {
                        alert("Error approving document.");
                    }
                });
            });

            // Reject Button Functionality
            $(".reject-btn").click(function () {
                var docId = $(this).data("id");
                var sender = $(this).data("sender");
                var type = $(this).data("type");
                var trackingNo = $(this).data("tracking");

                $("#rejectDocSender").text(sender);
                $("#rejectDocType").text(type);
                $("#rejectDocTrackingNo").text(trackingNo);
                $("#confirmReject").data("id", docId);
                $("#rejectModal").modal("show");
            });

            // Show "Other" reason input field if selected
            $("#rejectReason").change(function () {
                if ($(this).val() === "Other") {
                    $("#otherReasonRow").show();
                } else {
                    $("#otherReasonRow").hide();
                    $("#otherRejectReason").val(""); // Clear text if hidden
                }
            });

            $("#confirmReject").click(function () {
                var docId = $(this).data("id");
                var reason = $("#rejectReason").val();

                // Use the "Other Reason" if selected
                if (reason === "Other") {
                    reason = $("#otherRejectReason").val();
                }

                if (!reason.trim()) {
                    alert("Please provide a reason for rejection.");
                    return;
                }

                $.post("reject_document.php", { id: docId, reason: reason }, function (response) {
                    if (response === "success") {
                        alert("Document Rejected Successfully!");
                        location.reload();
                    } else {
                        alert("Error rejecting document.");
                    }
                });
            });
        });

    </script>

    <script>
$(document).ready(function () {
    $(".view-btn").click(function () {
        let attachmentUrl = $(this).attr("data-attachment");
        let fileExtension = attachmentUrl.split('.').pop().toLowerCase();

        // Formats that can be previewed via Office Viewer
        let officeFormats = ["doc", "docx", "ppt", "pptx", "xls", "xlsx"];
        let imageAndPdfFormats = ["pdf", "jpg", "jpeg", "png"];

        if (imageAndPdfFormats.includes(fileExtension)) {
            $("#previewFrame").attr("src", attachmentUrl);
            $("#previewModal").modal("show");
        } else if (officeFormats.includes(fileExtension)) {
            let fullUrl = new URL(attachmentUrl, window.location.href).href;
            let encodedUrl = encodeURIComponent(fullUrl);
            let officeViewerUrl = `https://view.officeapps.live.com/op/embed.aspx?src=${encodedUrl}`;

            $("#previewFrame").attr("src", officeViewerUrl);
            $("#previewModal").modal("show");
        } else {
            alert("This file type cannot be previewed. Please download it instead.");
        }
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