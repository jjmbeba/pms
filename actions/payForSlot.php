<?php
require_once('../config/database.php');
require_once('../utils/functions.php');

session_start();

$userName = $_SESSION['username'];

//Fetch the user
$user = usernameExists($conn, $userName);

$userId = $user['id'] ?? '';

$statementErr = $successMessage = '';
$number = $bookingId = $bookingLocation = $bookingName = $price = '';

if(isset($_POST['submit'])){
    //Validate the number
    if(isset($_POST['number'])){
        $number = $_POST['number'];

        $stringNumber = (string) $number;
        $length = strlen($stringNumber);

        if($length < 10){
            $statementErr = 'The number is too short. Enter at least 10 digits';
        }

        $bookingId = $_POST['bookingId'];
        $bookingName = $_POST['bookingName'];
        $bookingLocation = $_POST['bookingLocation'];

    }else{
        $statementErr = 'Number is required';
    }

    if(empty($statementErr)){

        $totalPrice = getBookingPrice($conn, $bookingId);
        
        $result = mpesaPay($conn, $number, $bookingId, $userId, $bookingName, $bookingLocation, $totalPrice);
        
        if($result && $result[0] === false){
            $statementErr = $result[1];
            header("location: /pms/dashboard/driver.php?error=$statementErr");
        }elseif ($result && $result[0] === true) {
            $successMessage = $result[1];
            header("location: /pms/dashboard/driver.php?success=$successMessage");
        }
        
    }else{
        header("location: /pms/dashboard/pay.php?error=$statementErr");
    }
}