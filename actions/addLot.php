<?php

require_once('../config/database.php');
require_once('../utils/functions.php');

$statementErr = $successMessage = '';

if (isset($_POST['submit']) && isset($_POST['redirect'])) {

    $redirect = $_POST['redirect'];
    $userId = $_POST['userId'];

    //Validate the name
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } else {
        $name = sanitizeInput($_POST['name']);

        //Check if name field only contains letters, numbers, dashes, whitespaces and apostrophes
        if (!preg_match("/^[a-zA-Z1-9-' ]*$/", $name)) {
            $nameErr = "Only letters, numbers and whitespaces are allowed";
        }
    }

    //Validate the capacity
    if (empty($_POST['capacity'])) {
        $capacityErr = "Capacity is required.";
    } else {
        $capacity = sanitizeInput($_POST['capacity']);

        //Check if the capacity is not a number
        if (!filter_var($capacity, FILTER_VALIDATE_INT)) {
            $capacityErr = "Capacity should be a number";
        }
    }

    //Validate the capacity
    if (empty($_POST['price'])) {
        $priceErr = "Price is required.";
    } else {
        $price = sanitizeInput($_POST['price']);

        //Check if the price is not a number
        if (!filter_var($price, FILTER_VALIDATE_INT)) {
            $priceErr = "Price should be a number";
        }
    }

    //Assign the location variable
    $location = $_POST['location'];

    if (empty($nameErr) && empty($priceErr) && empty($capacityErr)) {
        $result = createLot($conn, $userId, $name, $location, $capacity, $price);

        $name = $location = $capacity = $price = '';

        if($result && $result[0] === false){
            $statementErr = $result[1];
            header("location:$redirect?error=$statementErr");
        }elseif ($result && $result[0] === true) {
            $successMessage = $result[1];
            header("location:$redirect?success=$successMessage");
        }
    }
}