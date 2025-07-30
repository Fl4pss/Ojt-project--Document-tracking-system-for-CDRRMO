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
        <!-- PAGE CONTAINER-->
        <div class="page-container"> <!-- HEADER DESKTOP-->
        <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                        <form class="form-header" action="" method="POST">
                            <input class="au-input au-input--xl" type="text" name="search"
                                placeholder="Search for datas &amp; reports..."
                                value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>" />
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
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="title-7 m-b-35">Documents</h1>
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
                                    </div>
                                    
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small"
                                            data-toggle="modal" data-target="#addItemModal">
                                            <i class="zmdi zmdi-plus"></i> Add Document
                                        </button>
                                        <form method="POST" action="export_documents.php" style="display: inline;">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fa fa-download"></i> Export as CSV
                                        </button>
                                    </form>

                                    </div>
                                </div>
                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                    <thead>
    <tr>
        <th></th>
        <th>Tracking No.</th>
        <th>Document Type & Detail</th>
        <th>Subject</th>
        <th>Attachment</th>
        <th>Owner</th>
        <th>Date Received</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php
    $loggedInUser = $_SESSION['username'];
    $role = $_SESSION['role'];

    $search = isset($_POST['search']) ? mysqli_real_escape_string($conn, $_POST['search']) : '';

    if (!empty($search)) {
        if ($role === 'Superadmin') {
            $query = "SELECT * FROM documents 
                      WHERE current_status != 'archived'
                      AND (
                          tracking_no LIKE '%$search%' OR
                          document_type LIKE '%$search%' OR
                          subject LIKE '%$search%' OR
                          sender LIKE '%$search%' OR
                          required_actions LIKE '%$search%' OR
                          current_status LIKE '%$search%'
                      )
                      ORDER BY created_at DESC";
        } else {
            $query = "SELECT * FROM documents 
                      WHERE (approved_by = '$loggedInUser' OR owner_id = '$loggedInUser')
                      AND current_status != 'archived'
                      AND (
                          tracking_no LIKE '%$search%' OR
                          document_type LIKE '%$search%' OR
                          subject LIKE '%$search%' OR
                          sender LIKE '%$search%' OR
                          required_actions LIKE '%$search%' OR
                          current_status LIKE '%$search%'
                      )
                      ORDER BY created_at DESC";
        }
    } else {
        if ($role === 'Superadmin') {
            $query = "SELECT * FROM documents 
                      WHERE current_status != 'archived'
                      ORDER BY created_at DESC";
        } else {
            $query = "SELECT * FROM documents 
                      WHERE (approved_by = '$loggedInUser' OR owner_id = '$loggedInUser')
                      AND current_status != 'archived'
                      ORDER BY created_at DESC";
        }
    }

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $formattedDate = date("F j, Y", strtotime($row['date_received']));
        echo "<tr>";
        echo "<td><label class='au-checkbox'><input type='checkbox'><span class='au-checkmark'></span></label></td>";
        echo "<td>" . htmlspecialchars($row['tracking_no'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['document_type'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . htmlspecialchars($row['subject'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td><a href='" . htmlspecialchars($row['attachment'], ENT_QUOTES, 'UTF-8') . "' target='_blank'>" . htmlspecialchars(basename($row['attachment']), ENT_QUOTES, 'UTF-8') . "</a></td>";
        echo "<td>" . htmlspecialchars($row['sender'], ENT_QUOTES, 'UTF-8') . "</td>";
        echo "<td>" . $formattedDate . "</td>";                                           
        echo "<td>
            <div class='table-data-feature'>
                <button class='item view-btn' title='View' data-attachment='{$row['attachment']}'>
                    <i class='zmdi zmdi-eye'></i>
                </button>

                <button class='item download-btn' title='Download' 
                data-id='{$row['id']}'
                data-tracking='{$row['tracking_no']}'
                data-type='{$row['document_type']}'
                data-sender='{$row['sender']}'
                data-date='{$row['date_received']}'
                data-attachment='{$row['attachment']}'>
                <i class='fa-solid fa-download'></i>
            </button>

            <button class='item forward-btn' title='Forward' data-id='{$row['id']}' data-toggle='modal' data-target='#forwardModal'>
                <i class='zmdi zmdi-mail-send'></i>
            </button>

                <button class='item archive-btn' title='Archive' data-id='{$row['id']}'>
                    <i class='zmdi zmdi-archive'></i>
                </button>
                <div class='dropdown'>
                    <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton{$row['id']}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                        <i class='fa-solid fa-ellipsis'></i>
                    </button>
                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton{$row['id']}'>
                        <a class='dropdown-item info-btn' href='#' data-id='{$row['id']}'>Info</a>
                        <a class='dropdown-item history-btn' href='#' data-id='{$row['id']}'>History</a>
                        <a class='dropdown-item print-btn' href='#'>Print</a>
                    </div>
                </div>
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
    <input type="hidden" id="documentId" name="document_id">
    
    <div class="form-group">
        <label for="sentToDropdown">Sent To</label>
        <select name="sent_to[]" id="sentToDropdown" class="form-control" multiple required>
            <option value="" disabled selected>Select a department</option>
            <option value="Admin">Admin</option>
            <option value="Training">Training</option>
            <option value="Operations">Operations</option>
            <option value="Warning">Warning</option>
            <option value="Research">Research</option>
            <option value="Planning">Planning</option>
        </select>
    </div>

    <div class="form-group">
        <label for="message">Message</label>
        <textarea class="form-control" id="message" name="message" placeholder="Enter your message" rows="3" required></textarea>
    </div>
</form>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" form="forwardForm">Submit</button>
        </div>
        </div>
    </div>
    </div>

<div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Document</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add_document.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
    <label>Document Type</label>
    <select name="document_type" id="documentTypeSelect" class="form-control" required>
        <option value="">-- Select Document Type --</option>
        <option value="Memorandum">Memorandum</option>
        <option value="Letter">Letter</option>
        <option value="Endorsement">Endorsement</option>
        <option value="Invitation">Invitation</option>
        <option value="Certificate">Certificate</option>
        <option value="Report">Report</option>
        <option value="Other">Other</option>
    </select>
</div>

<div class="form-group" id="otherTypeGroup" style="display: none;">
    <label>Please specify</label>
    <input type="text" name="other_document_type" id="otherDocumentType" class="form-control">
</div>

                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <!-- Hidden field for owner_id -->
                    <input type="hidden" name="owner_id" value="<?php echo $_SESSION['username']; ?>">
                    
                    <div class="form-group">
                        <label>Attachment</label>
                        <input type="file" name="attachment" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveConfirmModal" tabindex="-1" role="dialog" aria-labelledby="archiveConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="archiveConfirmModalLabel">Archive Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please enter your PIN to confirm archiving:</p>
                <input type="password" id="archivePin" class="form-control" placeholder="Enter PIN" required>
                <input type="hidden" id="archiveDocId">
                <div id="archiveErrorMsg" class="text-danger mt-2" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="confirmArchiveBtn" class="btn btn-danger">Archive</button>
            </div>
        </div>
    </div>
</div>

<!-- More Info Modal -->
<div class="modal fade" id="moreInfoModal" tabindex="-1" aria-labelledby="moreInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="moreInfoModalLabel">Document Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="document-details">
        <!-- Document details will be loaded here via jQuery -->
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
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModalLabel">Document History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="document-history">
        <!-- Document history will be loaded here -->
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
        function filterTable(status) {
            let rows = document.querySelectorAll(".table-data2 tbody tr");

            rows.forEach(row => {
                if (status === "all") {
                    row.style.display = "";
                } else if (row.classList.contains(status)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            // Update Active Tab
            document.querySelectorAll("#documentTabs .nav-link").forEach(tab => {
                tab.classList.remove("active");
            });
            document.querySelector(`[onclick="filterTable('${status}')"]`).classList.add("active");
        }
    </script>

    <script>
        $(document).ready(function () {
            $("#addItemForm").submit(function (event) {
                event.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "add_document.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        alert(response);
                        $("#addItemModal").modal("hide");
                        location.reload();
                    },
                    error: function () {
                        alert("Error adding item.");
                    }
                });
            });
        });
        $(document).ready(function () {
    $(".archive-btn").click(function () {
        let docId = $(this).attr("data-id"); // Get document ID

        // Show the modal and store the document ID in the hidden input
        $("#archiveDocId").val(docId);
        $("#archivePin").val(''); // Clear any previous PIN input
        $("#archiveErrorMsg").hide(); // Hide error message initially
        $("#archiveConfirmModal").modal("show"); // Open modal
    });

    // When the user confirms the PIN in the modal
    $("#confirmArchiveBtn").click(function () {
        let docId = $("#archiveDocId").val();
        let pin = $("#archivePin").val().trim();

        if (pin === '') {
            $("#archiveErrorMsg").text("PIN is required!").show();
            return;
        }

        $.ajax({
            url: "verify_pin.php",
            type: "POST",
            data: { pin: pin },
            success: function (response) {
                if (response === "valid") {
                    // Proceed with archiving the document after PIN validation
                    $.ajax({
                        url: "archive_document.php",
                        type: "POST",
                        data: { document_id: docId },
                        success: function (response) {
                            if (response === "success") {
                                // Log the activity after archiving
                                $.ajax({
                                    url: 'log_document_activity.php',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        document_id: docId,
                                        action_type: 'archived',
                                        details: 'Document archived'
                                    }),
                                    success: function () {
                                        alert("Document archived successfully!");
                                        location.reload(); // Refresh the page to reflect changes
                                    },
                                    error: function () {
                                        alert("Document archived, but failed to log activity.");
                                        location.reload();
                                    }
                                });
                            } else {
                                alert("Error archiving document!");
                            }
                        },
                        error: function () {
                            alert("Error processing archive request!");
                        }
                    });
                    $("#archiveConfirmModal").modal("hide"); // Close the modal after successful archive
                } else {
                    $("#archiveErrorMsg").text("Invalid PIN. Please try again.").show();
                }
            },
            error: function () {
                $("#archiveErrorMsg").text("Error verifying PIN. Please try again.").show();
            }
        });
    });
});

$(document).ready(function () {
    $("#documentTypeSelect").change(function () {
        if ($(this).val() === "Other") {
            $("#otherTypeGroup").show();
            $("#otherDocumentType").attr("required", true);
        } else {
            $("#otherTypeGroup").hide();
            $("#otherDocumentType").val("").removeAttr("required");
        }
    });

    // Optional: When form is submitted, use other field if selected
    $("form").submit(function () {
        if ($("#documentTypeSelect").val() === "Other") {
            let customType = $("#otherDocumentType").val().trim();
            if (customType !== "") {
                $("#documentTypeSelect").append(
                    $("<option>").val(customType).text(customType).prop("selected", true)
                );
            }
        }
    });
});

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

$(document).ready(function () {
    $(".download-btn").click(function () {
        let docId = $(this).attr("data-id");
        let docTracking = $(this).attr("data-tracking");
        let docType = $(this).attr("data-type");
        let docSender = $(this).attr("data-sender");
        let docDate = $(this).attr("data-date");
        let docAttachment = $(this).attr("data-attachment");

        let fileName = docAttachment.split('/').pop(); 

        // Save doc ID to hidden input for tracking later
        $("#downloadDocId").val(docId);

        // Populate modal with info
        $("#docTracking").text(docTracking);
        $("#docType").text(docType);
        $("#docSender").text(docSender);
        $("#docDate").text(docDate);
        $("#docAttachment").attr("href", docAttachment);
        $("#docAttachmentName").text(fileName);
        $("#confirmDownloadBtn").attr("href", docAttachment);

        // Show modal
        $("#downloadModal").modal("show");
    });
});
$(document).ready(function () {
    $("#confirmDownloadBtn").click(function () {
        let docId = $("#downloadDocId").val();

        $.ajax({
            url: 'log_document_activity.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                document_id: docId,
                action_type: 'downloaded',
                details: 'Document downloaded by user'
            }),
            success: function () {
                console.log("Download activity logged.");
            },
            error: function () {
                console.error("Failed to log download activity.");
            }
        });
    });
});


        $(document).ready(function () {
            $(".image-preview").click(function () {
                var imageSrc = $(this).attr("data-image"); // Get image URL
                $("#modalImage").attr("src", imageSrc); // Set image in modal
                $("#imageModal").modal("show"); // Show modal
            });
        });
        $(document).ready(function () {
    $(".forward-btn").click(function () {
        let docId = $(this).attr("data-id");
        $("#documentId").val(docId); // Set hidden input value
    });

$("#forwardForm").submit(function (event) {
    event.preventDefault(); // Prevent default form submission

    let documentId = $("#documentId").val();
    let sentTo = $("#sentToDropdown").val(); // Get array of selected departments
    let requiredActions = $("#message").val(); // Reusing the same input but storing as required_actions

    $.ajax({
        url: "forward_document.php",
        type: "POST",
        contentType: "application/json", // Send JSON data
        data: JSON.stringify({
            document_id: documentId,
            sent_to: sentTo, // Send as JSON array
            required_actions: requiredActions // Renamed from message to required_actions
        }),
        dataType: "json",
        success: function (response) {
            if (response.status === "success") {
                alert(response.message);
                $("#forwardModal").modal("hide");
                location.reload();
            } else {
                alert("Error forwarding document: " + response.message);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", status, error);
            alert("Error processing request!");
        }
    });
});



});

    </script>
    <script>
        $(document).ready(function () {
            $('#sentToDropdown').select2({
                placeholder: "Select departments",
                allowClear: true,
                multiple: true // Allow multiple selections
            });
        });

    </script>
<script>
$(document).ready(function () {
    // Info button handler
    $(document).on('click', '.info-btn', function () {
        var docId = $(this).data('id');
        $.ajax({
            url: 'get_document_info.php',
            type: 'POST',
            data: { id: docId },
            success: function (response) {
                $('#document-details').html(response);
                $('#moreInfoModal').modal('show');
            },
            error: function () {
                $('#document-details').html('<p class="text-danger">Failed to load document details.</p>');
                $('#moreInfoModal').modal('show');
            }
        });
    });


// History button click event
$(document).on('click', '.history-btn', function () {
    let docId = $(this).data('id');

    $.ajax({
        url: 'get_document_history.php',
        type: 'POST',
        data: { document_id: docId },
        success: function (response) {
            $('#document-history').html(response);
            $('#historyModal').modal('show');
        },
        error: function () {
            $('#document-history').html('<p class="text-danger">Failed to load history.</p>');
            $('#historyModal').modal('show');
        }
    });
});


// Print function
$(document).on('click', '.print-btn', function () {
    var attachment = $(this).closest('tr').find('td a').attr('href');
    printDocumentDetails(attachment);
});

function printDocumentDetails(attachment) {
    let printContents = document.getElementById("document-details").innerHTML;
    let originalContents = document.body.innerHTML;

    if (attachment && attachment.endsWith('.pdf')) {
        // Open the PDF in a new tab for printing
        window.open(attachment, '_blank');
        alert('The document attachment is a PDF. Please print it from the opened tab.');
    } else {
        // Print non-PDF attachments or document details
        document.body.innerHTML = `
            <html>
                <head>
                    <title>Print Document</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                    </style>
                </head>
                <body>${printContents}</body>
            </html>
        `;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload(); // Optional: refresh to restore state
    }
}

});

$(document).ready(function () {
    // Show modal when archive button is clicked
    $(".archive-btn").click(function () {
        let docId = $(this).attr("data-id"); // Get document ID
        $("#archiveDocId").val(docId); // Store the doc ID in the modal
        $("#archivePin").val(''); // Clear previous PIN input
        $("#archiveErrorMsg").hide(); // Hide error message
        $("#archiveConfirmModal").modal("show"); // Show modal
    });

    // Archive document when confirm button is clicked
    $("#confirmArchiveBtn").click(function () {
        let docId = $("#archiveDocId").val();
        let pin = $("#archivePin").val().trim();

        if (pin === '') {
            $("#archiveErrorMsg").text("PIN is required!").show();
            return;
        }

        $.ajax({
            url: "verify_pin.php",
            type: "POST",
            data: { pin: pin },
            success: function (response) {
                if (response === "valid") {
                    // Archive the document after PIN verification
                    $.ajax({
                        url: "archive_document.php",
                        type: "POST",
                        data: { document_id: docId },
                        success: function (response) {
                            if (response === "success") {
                                // Log archive activity
                                $.ajax({
                                    url: 'log_document_activity.php',
                                    method: 'POST',
                                    contentType: 'application/json',
                                    data: JSON.stringify({
                                        document_id: docId,
                                        action_type: 'archived',
                                        details: 'Document archived'
                                    }),
                                    success: function () {
                                        alert("Document archived successfully!");
                                        location.reload();
                                    },
                                    error: function () {
                                        alert("Document archived, but failed to log activity.");
                                        location.reload();
                                    }
                                });
                            } else {
                                alert("Error archiving document!");
                            }
                        },
                        error: function () {
                            alert("Error processing archive request!");
                        }
                    });
                    $("#archiveConfirmModal").modal("hide");
                } else {
                    $("#archiveErrorMsg").text("Invalid PIN. Please try again.").show();
                }
            },
            error: function () {
                $("#archiveErrorMsg").text("Error verifying PIN. Please try again.").show();
            }
        });
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
<input type="hidden" id="downloadDocId">

</body>

</html>
<!-- end document-->