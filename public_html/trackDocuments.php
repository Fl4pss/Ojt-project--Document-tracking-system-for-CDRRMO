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
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li>
                            <a href="chart.html">
                                <i class="fas fa-chart-bar"></i>Charts</a>
                        </li>
                        <li>
                            <a href="table.html">
                                <i class="fas fa-table"></i>Tables</a>
                        </li>
                        <li>
                            <a href="form.html">
                                <i class="far fa-check-square"></i>Forms</a>
                        </li>
                        <li>
                            <a href="calendar.html">
                                <i class="fas fa-calendar-alt"></i>Calendar</a>
                        </li>

                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-copy"></i>Pages</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="register.html">Register</a>
                                </li>
                                <li>
                                    <a href="forget-pass.html">Forget Password</a>
                                </li>
                            </ul>
                        </li>
                        <li class="has-sub">
                            <a class="js-arrow" href="#">
                                <i class="fas fa-desktop"></i>UI Elements</a>
                            <ul class="navbar-mobile-sub__list list-unstyled js-sub-list">
                                <li>
                                    <a href="button.html">Button</a>
                                </li>
                                <li>
                                    <a href="badge.html">Badges</a>
                                </li>
                                <li>
                                    <a href="tab.html">Tabs</a>
                                </li>
                                <li>
                                    <a href="card.html">Cards</a>
                                </li>
                                <li>
                                    <a href="alert.html">Alerts</a>
                                </li>
                                <li>
                                    <a href="progress-bar.html">Progress Bars</a>
                                </li>
                                <li>
                                    <a href="modal.html">Modals</a>
                                </li>
                                <li>
                                    <a href="switch.html">Switchs</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grids</a>
                                </li>
                                <li>
                                    <a href="fontawesome.html">Fontawesome Icon</a>
                                </li>
                                <li>
                                    <a href="typo.html">Typography</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
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
                        <li>
                            <a href="table.html">
                                <i class="fas fa-table"></i>Inventory</a>
                        </li>
                        <li>
                            <a href="form.html">
                                <i class="far fa-check-square"></i>Media Files</a>
                        </li>
                        <li>
                            <a href="calendar.html">
                                <i class="fas fa-calendar-alt"></i>Stock Availability</a>
                        </li>
                        <hr> <!-- Sidebar Divider -->

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
                                    <a href="searchDocuments.php">Search & Track</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

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
            </header> <!-- END HEADER DESKTOP--> <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <h1 class="title-7 m-b-35">Documents</h1>
                                <!-- Tabs for Document Status -->
                                <ul class="nav nav-tabs" id="documentTabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#" onclick="filterTable('all')">All</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('pending')">Pending</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('approved')">Approved</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('forwarded')">Forwarded</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#" onclick="filterTable('released')">Released</a>
                                    </li>
                                </ul>

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
                                                <th>Current Status</th>
                                                <th>Required Actions</th>
                                                <th>Forwarded To</th>
                                                <th>Date & Time Forwarded</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="tr-shadow pending">
                                                <td><label class="au-checkbox"><input type="checkbox"><span
                                                            class="au-checkmark"></span></label></td>
                                                <td>TRK-20250212-001</td>
                                                <td>Purchase Order - Office Supplies</td>
                                                <td><a href="#">PO_OfficeSupplies.pdf</a></td>
                                                <td>Procurement Department</td>
                                                <td>2025-02-12 09:15 AM</td>
                                                <td><span class="status--pending">Pending</span></td>
                                                <td>Review & Approval</td>
                                                <td>Finance Department</td>
                                                <td>2025-02-12 10:30 AM</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <button class="item" title="View"><i
                                                                class="zmdi zmdi-eye"></i></button>
                                                        <button class="item" title="Forward"><i
                                                                class="zmdi zmdi-mail-send"></i></button>
                                                        <button class="item" title="Archive"><i
                                                                class="zmdi zmdi-archive"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="tr-shadow approved">
                                                <td><label class="au-checkbox"><input type="checkbox"><span
                                                            class="au-checkmark"></span></label></td>
                                                <td>TRK-20250212-002</td>
                                                <td>Employee Contract - John Doe</td>
                                                <td><a href="#">Contract_JohnDoe.docx</a></td>
                                                <td>HR Department</td>
                                                <td>2025-02-12 08:45 AM</td>
                                                <td><span class="status--approved">Approved</span></td>
                                                <td>Send to Legal</td>
                                                <td>Legal Department</td>
                                                <td>2025-02-12 09:50 AM</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <button class="item" title="View"><i
                                                                class="zmdi zmdi-eye"></i></button>
                                                        <button class="item" title="Forward"><i
                                                                class="zmdi zmdi-mail-send"></i></button>
                                                        <button class="item" title="Archive"><i
                                                                class="zmdi zmdi-archive"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="tr-shadow released">
                                                <td><label class="au-checkbox"><input type="checkbox"><span
                                                            class="au-checkmark"></span></label></td>
                                                <td>TRK-20250212-003</td>
                                                <td>Project Proposal - New IT System</td>
                                                <td><a href="#">IT_Proposal.pdf</a></td>
                                                <td>IT Department</td>
                                                <td>2025-02-12 07:30 AM</td>
                                                <td><span class="status--released">Released</span></td>
                                                <td>Implementation</td>
                                                <td>Operations Department</td>
                                                <td>2025-02-12 08:00 AM</td>
                                                <td>
                                                    <div class="table-data-feature">
                                                        <button class="item" title="View"><i
                                                                class="zmdi zmdi-eye"></i></button>
                                                        <button class="item" title="Forward"><i
                                                                class="zmdi zmdi-mail-send"></i></button>
                                                        <button class="item" title="Archive"><i
                                                                class="zmdi zmdi-archive"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- END DATA TABLE -->
                            </div>
                        </div>
                    </div>
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
</body>

</html>
<!-- end document-->