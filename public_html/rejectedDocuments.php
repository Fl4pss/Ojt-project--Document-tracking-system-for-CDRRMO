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
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-table"></i>Inventory</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="Inventory.php"> Inventory Items</a>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <a href="form.html">
                                <i class="far fa-check-square"></i>Media Files</a>
                        </li>
                        <li>
                            <a href="calendar.html">
                                <i class="fas fa-calendar-alt"></i>Stock Availability</a>
                        </li>
                        <hr>
                        <p class="sidebar-section-title">Documents Management</p>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Documents</a>
                            <ul class="list-unstyled navbar__sub-list js-sub-list">
                                <li>
                                    <a href="Documents.php">Documents</a>
                                </li>
                                <li>
                                    <a href="receivingDocuments.php">Incoming</a>
                                </li>
                                <li>
                                    <a href="deferredDocuments.php">Deferred</a>
                                </li>
                                <li>
                                    <a href="archivedDocuments.php">Archived</a>
                                </li>
                                <li>
                                    <a href="rejectedDocuments.php">Rejected</a>
                                </li>
                                <li>
                                    <a href="searchDocuments.php">Search & Track</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>

        <!-- PAGE CONTAINER-->
        <div class="page-container"> <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST"> <input class="au-input au-input--xl"
                                    type="text" name="search" placeholder="Search for datas &amp; reports..." /> <button
                                    class="au-btn--submit" type="submit"> <i class="zmdi zmdi-search"></i> </button>
                            </form>
                            <div class="account-wrap">
                                <div class="account-item clearfix js-item-menu">
                                    <div class="image"> <img src="img\cdrrmo1.png" alt="John Doe" /> </div>
                                    <div class="content"> <a class="js-acc-btn" href="#">Cdrrmo Admin</a> </div>
                                    <div class="account-dropdown js-dropdown">
                                        <div class="info clearfix">
                                            <div class="image"> <a href="#"> <img src="img\cdrrmo1.png"
                                                        alt="John Doe" /> </a> </div>
                                            <div class="content">
                                                <h5 class="name"> <a href="#">Cddrmo admin</a> </h5> <span
                                                    class="email">Cdrrmoadmin@gmail.com</span>
                                            </div>
                                            <div class="account-dropdown__footer"> <a href="login.php"> <i
                                                        class="zmdi zmdi-power"></i>Logout</a> </div>
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
                                <h1 class="title-7 m-b-35">Documents</h1>
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
                                    <div class="table-data__tool-right">
                                        <button class="au-btn au-btn-icon au-btn--green au-btn--small"
                                            data-toggle="modal" data-target="#addItemModal">
                                            <i class="zmdi zmdi-plus"></i> Add Document
                                        </button>
                                        <div class="rs-select2--dark rs-select2--sm rs-select2--dark2">
                                            <select class="js-select2" name="type">
                                                <option selected="selected">Export</option>
                                                <option value="">Option 1</option>
                                                <option value="">Option 2</option>
                                            </select>
                                            <div class="dropDownSelect2"></div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nav nav-tabs" id="documentTabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#" onclick="filterTable('all')">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('Pending')">Pending</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('approved')">Approved</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('released')">Released</a>
                                    </li>
                                </ul>

                                <div class="table-responsive table-responsive-data2">
                                    <table class="table table-data2">
                                        <thead>
                                            <tr>
                                                <th>Tracking No.</th>
                                                <th>Document Type & Detail</th>
                                                <th>Attachment</th>
                                                <th>Sender</th>
                                                <th>Date & Time Received</th>
                                                <th>Reason for Rejection</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $query = "SELECT * FROM documents WHERE current_status = 'Rejected' ORDER BY created_at DESC";
                                            $result = mysqli_query($conn, $query);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $tracking_no = htmlspecialchars($row['tracking_no'], ENT_QUOTES, 'UTF-8');
                                                $document_type = htmlspecialchars($row['document_type'], ENT_QUOTES, 'UTF-8');
                                                $attachment = htmlspecialchars($row['attachment'], ENT_QUOTES, 'UTF-8');
                                                $sender = htmlspecialchars($row['sender'], ENT_QUOTES, 'UTF-8');
                                                $date_received = htmlspecialchars($row['date_received'], ENT_QUOTES, 'UTF-8');
                                                $rejection_reason = htmlspecialchars($row['rejection_reason'], ENT_QUOTES, 'UTF-8');
                                                $id = htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8');

                                                echo "<tr class='tr-shadow Rejected'>";
                                                echo "<td>$tracking_no</td>";
                                                echo "<td>$document_type</td>";
                                                echo "<td><a href='$attachment' target='_blank'>" . basename($attachment) . "</a></td>";
                                                echo "<td>$sender</td>";
                                                echo "<td>$date_received</td>";
                                                echo "<td>$rejection_reason</td>";
                                                echo "<td>
                    <div class='table-data-feature'>
                        <button class='item' title='View Details'><i class='zmdi zmdi-eye'></i></button>
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
    <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
        aria-hidden="true">
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
                            <input type="text" name="document_type" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Sender</label>
                            <input type="text" name="sender" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Required Actions</label>
                            <textarea name="required_actions" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Sent To</label>
                            <input type="text" name="sent_to" class="form-control" required>
                        </div>
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
</body>

</html>
<!-- end document-->