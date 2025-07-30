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

    <title>Users</title>

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
                                <h1 class="title-7 m-b-35">Users</h1>
                                <?php
$query = "SELECT id, username, role, status FROM admin_users";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0): ?>
<table class="table table-bordered table-hover mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                    <?php if ($row['status'] === 'active'): ?>
                        <span class="badge badge-success">Active</span>
                    <?php else: ?>
                        <span class="badge badge-danger">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
    <a href="#"
       class="btn btn-sm btn-primary edit-user-btn"
       data-id="<?= $row['id'] ?>"
       data-username="<?= htmlspecialchars($row['username']) ?>"
       title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <?php if ($row['status'] === 'active'): ?>
        <button class="btn btn-sm btn-warning deactivate-btn" data-id="<?= $row['id'] ?>" title="Deactivate">
            <i class="fas fa-user-slash"></i>
        </button>
    <?php else: ?>
        <button class="btn btn-sm btn-success activate-btn" data-id="<?= $row['id'] ?>" title="Activate">
            <i class="fas fa-user-check"></i>
        </button>
    <?php endif; ?>

    <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" title="Delete"
       onclick="return confirm('Are you sure you want to delete this user?');">
        <i class="fas fa-trash-alt"></i>
    </a>
</td>

            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php else: ?>
    <p>No admin users found.</p>
<?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="editUserForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="id">
                    <div class="form-group">
                        <label for="editUsername">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">New Password <small>(Leave blank to keep current)</small></label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                    </div>
                    <div class="form-group">
                        <label for="editPin">New PIN <small>(Leave blank to keep current)</small></label>
                        <input type="text" class="form-control" id="editPin" name="pin" maxlength="6" pattern="\d{4,6}" title="Enter a 4-6 digit PIN">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
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
<script>
$(document).ready(function () {
    // When clicking edit button
    $('.edit-user-btn').click(function () {
        const userId = $(this).data('id');
        const username = $(this).data('username');

        $('#editUserId').val(userId);
        $('#editUsername').val(username);
        $('#editPassword').val(''); // clear the password field
        $('#editPin').val(''); // clear the PIN field

        $('#editUserModal').modal('show');
    });

    // Handle form submission
    $('#editUserForm').submit(function (e) {
        e.preventDefault();

        const formData = {
            id: $('#editUserId').val(),
            username: $('#editUsername').val(),
            password: $('#editPassword').val(),
            pin: $('#editPin').val()
        };

        $.ajax({
            url: 'update_user.php',
            method: 'POST',
            data: formData,
            success: function (response) {
                alert("User updated successfully.");
                $('#editUserModal').modal('hide');
                location.reload(); // refresh to show changes
            },
            error: function () {
                alert("Failed to update user.");
            }
        });
    });
});
</script>

</html>
<!-- end document-->