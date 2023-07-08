<?php

require_once('../config/database.php');
require_once('../utils/functions.php');

//Initialize variables
$successMessage = $statementErr = '';

//Fetch all users in the database
$users = getUsers($conn);

//Assign the number of users to a variable
$numberOfUsers = $users->num_rows;

$location = $_SERVER['PHP_SELF'];

if(isset($_GET['success'])){
    $successMessage = $_GET['success'];
}elseif (isset($_GET['error'])) {
    $statementErr = $_GET['error'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<?php include 'header.php'; ?>

<body>
    <?php echo 'Welcome ' . $_SESSION['username']; ?>
    <div class="dashboard__container">
        <div class="sidebar">
            <h2>Reports</h2>
            <ul class="report__list">
                <li class="active">Users</li>
                <li>Bookings</li>
            </ul>
        </div>
        <div class="report__area">
            <h1>User report</h1>
            <?php echo "<p style='margin-top:20px;'>Number of users: $numberOfUsers</p>"; ?>
            <table class="report__table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Account Type</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($users->num_rows > 0) {
                        while ($row = $users->fetch_assoc()) {
                            $userId = $row['id'];
                            $username = $row['username'];
                            $email = $row['email'];
                            $acType = $row['acType'];

                            //Display the data
                            echo "<tr>
                                <td>$userId</td>
                                <td>$username</td>
                                <td>$email</td>
                                <td>$acType</td>
                                <td>
                                    <form method='POST' action='/pms/dashboard/admin.php?id=$userId'>
                                        <input type='submit' class='edit__btn' value='Edit'/> 
                                    </form>
                                </td>
                                <td>
                                    <a class='delete__btn' href='../actions/deleteUser.php?id=$userId&redirect=$location'>
                                        Delete
                                    </a>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <div class="invalid" style="<?php echo $statementErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
            </div>
            <div class="success" style="<?php echo $successMessage ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/success.svg' alt='success'/>
                    $successMessage
                    </div>
                    "; ?>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>