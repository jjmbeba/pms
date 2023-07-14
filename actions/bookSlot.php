<?php 
session_start();
require_once('../config/database.php');
require_once('../utils/functions.php');

$userName = $_SESSION['username'];

//Initialize variables
$lotId = $location = $timeIn = $timeOut = '';
$timeInErr = $timeOutErr = $statementErr = $successMessage = '';

//Fetch the user
$user = usernameExists($conn, $userName);

$userId = $user['id'] ?? '';

if (isset($_POST['submit'])) {
    //Validate the time in
    if (empty($_POST['timeIn'])) {
        $timeInErr = 'Time in property is required';
    } else {
        $timeIn = $_POST['timeIn'];
    }

    //Validate the time out
    if (empty($_POST['timeOut'])) {
        $timeOutErr = 'Time out property is required';
    } else {
        $timeOut = $_POST['timeOut'];
    }

    $lotId = $_POST['location'];

    if (empty($timeInErr) && empty($timeOutErr)) {
        $start = new DateTime($timeIn);
        $stop = new DateTime($timeOut);

        //Calculate the difference between timeIn and timeOut
        $difference = $start->diff($stop);
        $hours = $difference->h;
        $minutes = $difference->i;
        $minutesInHours = $minutes / 60;
        $totalTime = $hours + $minutesInHours;

        
        $unitPrice = getUnitPrice($conn, $lotId);
        $totalPrice = $unitPrice * $totalTime;
        
        $result = bookSlot($conn, $userId, $lotId, $timeIn, $timeOut, $totalTime, $totalPrice);

        if (!empty($result) && $result[0] === false) {
            $statementErr = $result[1];
            header("location: /pms/dashboard/driver.php?error=$statementErr");
        } else if (!empty($result) && $result[0] === true) {
            $successMessage = $result[1];
            header("location: /pms/dashboard/driver.php?success=$successMessage");
        }
    }
}