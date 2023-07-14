<?php

require_once('../config/database.php');
require_once('../utils/functions.php');
require_once('../common/theme.php');
require_once('header.php');

$userName = $_SESSION['username'];

//Initialize variables
$name = $capacity = $price = $location = '';
$successMessage = $statementErr  = $nameErr = $capacityErr = $priceErr = '';

//Fetch the user
$user = usernameExists($conn, $userName);

$userId = $user['id'] ?? '';

$bookings = getUserBookings($conn, $userId);

//Assign the number of bookings to a variable
$numberOfBookings = $bookings->num_rows;

if (isset($_GET['error'])) {
    $statementErr = $_GET['error'];
} elseif (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver</title>
</head>

<body>
    Driver dashboard
    <?php echo 'Welcome ' . $_SESSION['username']; ?>
    <div class="dashboard__container">
        <div class="sidebar">
            <h2>Reports</h2>
            <ul class="report__list">
                <li class="active" data-tab-target='#overview'>Overview</li>
                <li data-tab-target='#bookings'>Booking history</li>
                <li data-tab-target='#payments'>Payment history</li>
            </ul>
        </div>
        <div class="report__area">
            <div id="overview" data-tab-content class="active overview">
                This is an overview :
                <div class="card__container">
                    <div class="card">
                        <span>Number of bookings : </span><br /> <span class="stat"><?php echo $numberOfBookings; ?></span>
                    </div>
                </div>
                <div class="bookSlotLinkContainer">
                    <a href="book.php" class="bookSlotLink">
                        Book a slot
                    </a>
                </div>
            </div>
            <div id="bookings" data-tab-content>
                <h1>Bookings report</h1>
                <?php echo "<p style='margin-top:20px;'>Number of bookings: $numberOfBookings</p>"; ?>
                <table class="report__table" style="margin-left: 0px;">
                    <?php
                    if ($bookings->num_rows > 0) : ?>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Lot</th>
                                <th>Time in</th>
                                <th>Time out</th>
                                <th>Standard price</th>
                                <th>Total hours</th>
                                <th>Total price</th>
                                <th>Status</th>
                                <th>Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $bookings->fetch_assoc()) {
                                $bookingId = $row['id'];
                                $bookingLotName = $row['name'];
                                $bookingLotLocation = $row['location'];
                                $bookingTimeIn = $row['timeIn'];
                                $bookingTimeOut = $row['timeOut'];
                                $bookingTotalHours = $row['totalHours'];
                                $bookingStandardPrice = $row['price'];
                                $totalPrice = $row['totalPrice'];
                                $bookingStatus = $row['status'];

                                $link = ($bookingStatus === 'paid') ? "<a class='paid'>Paid</a>" : "<a href='pay.php?bookingId=$bookingId&name=$bookingLotName&location=$bookingLotLocation' class='pay-btn'>Pay</a>";

                                //Display the data
                                echo "<tr>
                                <td>$bookingId</td>
                                <td>$bookingLotName - $bookingLotLocation</td>
                                <td>$bookingTimeIn</td>
                                <td>$bookingTimeOut</td>
                                <td>$bookingStandardPrice</td>
                                <td>$bookingTotalHours</td>
                                <td>$totalPrice</td>
                                <td>$bookingStatus</td>
                                <td>
                                    $link
                                </td>
                            </tr>
                            ";
                            } ?>
                        <?php endif ?>
                        </tbody>
                </table>
                <div class="invalid" style="<?php echo $statementErr ? 'display:block; margin-left:0;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
                </div>
                <div class="success" style="<?php echo $successMessage ? 'display:block; margin-left:0;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/success.svg' alt='success'/>
                    $successMessage
                    </div>
                    "; ?>
                </div>
            </div>
            <div id="payments" data-tab-content>
                payments page
            </div>
        </div>
    </div>
    <script src="/pms/script.js"></script>
    <?php include 'footer.php'; ?>