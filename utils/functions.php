<?php

function sanitizeInput($input)
{
    //Remove excess whitespaces
    $input = trim($input);

    //Remove backslashes
    $input = stripslashes($input);

    //convert special characters into HTML entities
    $input = htmlspecialchars($input);

    return $input;
    exit();
}

function emailExists($conn, $email)
{
    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        echo "Statement failed";
        exit();
    }

    mysqli_stmt_bind_param($statement, "s", $email);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($statement);
}

function usernameExists($conn, $username)
{
    $sql = "SELECT * FROM users WHERE username = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        $statementErr = "Statement failed";
        exit();
    }

    mysqli_stmt_bind_param($statement, "s", $username);
    mysqli_stmt_execute($statement);

    $resultData = mysqli_stmt_get_result($statement);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($statement);
}

function createUser($conn, $username, $email, $acType, $password, $statementErr)
{
    //Create a SQL query
    $sql = "INSERT INTO users (username, email, acType, password) VALUES (?,?,?,?);";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return "Statement failed";
        exit();
    }

    //Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($statement, "ssss", $username, $email, $acType, $hashedPassword);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    //Redirect to user dashboard
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['acType'] = $acType;

    if ($_SESSION['acType'] === 'driver') {
        header("location: ../dashboard/driver.php");
        exit();
    } elseif ($_SESSION['acType'] === 'manager') {
        header("location: ../dashboard/manager.php");
        exit();
    } elseif ($_SESSION['acType'] === 'admin') {
        header("location: ../dashboard/admin.php");
        exit();
    }
}

function loginUser($conn, $username, $password, $statementErr)
{
    //Check if email exists
    $usernameExists = usernameExists($conn, $username, $statementErr);
    if ($usernameExists === false) {
        return array(false, "Account does not exist");
        exit();
    }

    $hashPassword = $usernameExists['password'];

    //Compare the passwords
    $checkPassword = password_verify($password, $hashPassword);

    //Return error message if password is false
    if ($checkPassword === false) {
        return array(false, "Wrong password");
        exit();
    } elseif ($checkPassword === true) {
        session_start();
        $_SESSION['username'] = $usernameExists['username'];
        $_SESSION['acType'] = $usernameExists['acType'];

        if ($usernameExists['acType'] === 'driver') {
            //Redirect to driver dashboard
            header("location: ../dashboard/driver.php");
            exit();
        } elseif ($_SESSION['acType'] === 'manager') {
            //Redirect to manager dashboard
            header("location: ../dashboard/manager.php");
            exit();
        } elseif ($_SESSION['acType'] === 'admin') {
            //Redirect to admin dashboard
            header("location: ../dashboard/admin.php");
            exit();
        }
    }
}

function changeDetails($conn, $newUsername, $newEmail)
{
    //Store the existing username
    $existingName = $_SESSION['username'];

    //Check if new username exists
    $usernameExists = usernameExists($conn, $newUsername);

    //Fetch the user and store the user Id
    $user = usernameExists($conn, $existingName);
    $userId = $user['id'] ?? '';

    //Check if email exists
    $emailExists = emailExists($conn, $newEmail);

    //If the username and email exists, return the appropriate error
    if ($usernameExists && $emailExists) {
        return array(false, "Account with the same details already exists");
        exit();
    }

    //Create a SQL query
    $sql = "UPDATE users SET username=?, email=? WHERE id = ?;";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return "Statement failed";
        exit();
    }

    mysqli_stmt_bind_param($statement, "ssd", $newUsername, $newEmail, $userId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Account details changed successfully");
}

function changePassword($conn, $currentPass, $newPass)
{
    //Store the existing username
    $existingName = $_SESSION['username'];

    //Fetch the user and store the user Id
    $user = usernameExists($conn, $existingName);
    $userId = $user['id'] ?? '';

    //Store the correct user password
    $dbPass = $user['password'] ?? '';

    //Compare the password and the current password
    $checkPassword = password_verify($currentPass, $dbPass);

    //Return an error if the passwords do not match
    if (!$checkPassword) {
        return array(false, "Wrong password");
    }

    //Create a SQL query
    $sql = "UPDATE users SET password=? WHERE id = ?;";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return "Statement failed";
        exit();
    }

    //Hash the new password
    $hashPass = password_hash($newPass, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($statement, "sd", $hashPass, $userId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Account details changed successfully");
}

function getUsers($conn)
{
    //Create a SQL query
    $sql = "SELECT * FROM users WHERE acType <> 'admin'";

    //Execute the query
    $result = $conn->query($sql);

    return $result;
}

function deleteUser($conn, $userId)
{
    //Create a SQL query
    $sql = "DELETE FROM users WHERE id=?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "d", $userId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "User deleted successfully");
}

function editUser($conn, $userId, $acType)
{
    //Create a SQL query
    $sql = "UPDATE users SET acType=? WHERE id = ?;";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "sd", $acType, $userId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "User edited successfully");
}

function getUserLots($conn, $userId)
{
    //Create a SQL query
    $sql = "SELECT * FROM lots WHERE userId = ?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "d", $userId);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    mysqli_stmt_close($statement);

    return $result;
}

function createLot($conn, $userId, $name, $location, $capacity, $price)
{

    //Create a sql query
    $sql = "INSERT INTO lots (userId, name, location, capacity, price) VALUES (?,?,?,?,?);";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "sssss", $userId, $name, $location, $capacity, $price);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Parking lot registered successfully");
}

function getAllLots($conn)
{
    //Create a SQL query
    $sql = "SELECT * FROM lots";

    //Execute the query
    $result = $conn->query($sql);

    return $result;
}

function editLot($conn, $lotId, $name, $location, $capacity, $price){
    //Create a SQL query
    $sql = "UPDATE lots SET name=?, location= ?,capacity = ?, price = ? WHERE id = ?;";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false,"Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "ssddd", $name, $location, $capacity, $price, $lotId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Lot details changed successfully");
}

function deleteLot($conn, $lotId){
    //Create a SQL query
    $sql = "DELETE FROM lots WHERE id = ?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false,"Statement failed");
        exit();
    } 
    
    mysqli_stmt_bind_param($statement, "d", $lotId);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Lot record deleted successfully");
}

function bookSlot($conn, $userId, $lotId, $timeIn, $timeOut, $totalHours, $totalPrice){
    //Create a SQL query
    $sql = "INSERT INTO bookings (userId, lotId, timeIn, timeOut, totalHours, totalPrice, status) VALUES (?,?,?,?,?,?,?);";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false,"Statement failed");
        exit();
    }

    $status = "not paid";

    mysqli_stmt_bind_param($statement, "iissdds", $userId, $lotId, $timeIn, $timeOut, $totalHours, $totalPrice, $status);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    return array(true, "Booking successfull");
}

function getUserBookings($conn, $userId){
    //Create a SQL query
    $sql = "SELECT bookings.id, bookings.lotId, lots.name, lots.location, lots.price, bookings.timeIn, bookings.timeOut, bookings.totalHours, bookings.totalPrice, bookings.status FROM bookings, lots WHERE bookings.lotId = lots.id AND bookings.userId=?;";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "i", $userId);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    mysqli_stmt_close($statement);

    return $result;
}

function getLot($conn, $lotId){
    //Create a SQL query
    $sql = "SELECT * FROM lots WHERE lotId = ?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "i", $lotId);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    mysqli_stmt_close($statement);

    return $result;
}

function getUnitPrice($conn, $lotId){
    //Create a SQL query
    $sql = "SELECT price FROM lots WHERE id = ?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "i", $lotId);
    mysqli_stmt_execute($statement);

    //Bind the result variable to the price field
    mysqli_stmt_bind_result($statement, $price);

    //Fetch the result
    mysqli_stmt_fetch($statement);

    return $price;
}

function getBookingPrice($conn, $bookingId){
    //Create a SQL query
    $sql = "SELECT totalPrice FROM bookings WHERE id = ?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "i", $bookingId);
    mysqli_stmt_execute($statement);

    //Bind the result variable to the price field
    mysqli_stmt_bind_result($statement, $totalPrice);

    //Fetch the result
    mysqli_stmt_fetch($statement);

    return $totalPrice;
}

function mpesaPay($conn, $number, $bookingId, $userId, $bookingName, $bookingLocation, $totalPrice){

    $formattedNumber = '';

    if(substr($number,0,1) === '0'){
        $formattedNumber = "254".substr($number,1);
    }else{
        $formattedNumber = $number;
    }    
    $ch = curl_init('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ',
        'Content-Type: application/json'
    ]);

    $payload = json_encode([
        "BusinessShortCode" => 174379,
        "Password" => "MTc0Mzc5YmZiMjc5ZjlhYTliZGJjZjE1OGU5N2RkNzFhNDY3Y2QyZTBjODkzMDU5YjEwZjc4ZTZiNzJhZGExZWQyYzkxOTIwMjMwNzEzMTMzMjU2",
        "Timestamp" => "20230713133256",
        "TransactionType" => "CustomerPayBillOnline",
        "Amount" => $totalPrice,
        "PartyA" => 254708374149,
        "PartyB" => 174379,
        "PhoneNumber" => $formattedNumber,
        "CallBackURL" => "https://mydomain.com/path",
        "AccountReference" => "Parkit",
        "TransactionDesc" => "Payment for booking $bookingId at $bookingName - $bookingLocation" 
    ]);

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    curl_close($ch);

    //Create a SQL query
    $sql = "INSERT INTO payments (bookingId, userId, price) VALUES (?,?,?);";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "iid", $bookingId, $userId, $totalPrice);
    mysqli_stmt_execute($statement);

    mysqli_stmt_close($statement);

    $sql2 = "UPDATE bookings SET status=? WHERE id = ?;";

    //Initialize a prepared statement
    $statement2 = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement2, $sql2)) {
        return array(false, "Statement failed");
        exit();
    }

    $updatedStatus = 'paid';

    mysqli_stmt_bind_param($statement2, "si", $updatedStatus, $bookingId);
    mysqli_stmt_execute($statement2);

    mysqli_stmt_close($statement2);

    return array(true, "Payment successful");

}

function getUserPayments($conn, $userId){
    //Create a SQL query
    $sql = "SELECT payments.id,bookings.id, lots.name, lots.location, bookings.timeIn, bookings.timeOut, bookings.totalHours, payments.price, bookings.status FROM bookings, lots, payments WHERE payments.userId = bookings.userId=?";

    //Initialize a prepared statement
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        return array(false, "Statement failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "i", $userId);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);

    mysqli_stmt_close($statement);

    return $result;
}

function sendEmail($conn, $email, $token, $expires){
    //Delete any record of the email in the pwdreset table
    $sql = "DELETE FROM pwdreset WHERE email = ?";

    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        return array(false, "An error occurred");
    }

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $sql2 = "INSERT INTO pwdreset(email, token,expires) VALUES(?,?,?)";

    $stmt2 = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt2, $sql2)){
        return array(false, "An error occurred");
    }

    mysqli_stmt_bind_param($stmt2, "sss", $email, $token, $expires);
    mysqli_stmt_execute($stmt2);

    mysqli_stmt_close($stmt);
    mysqli_stmt_close($stmt2);

           
    $resetlink = "localhost/pms/auth/reset-password.php?token=$token";
    $message = "<p>We have a password reset request.Here is your password reset link:</p><br/><a href='$resetlink'>$resetlink</a>";
    $subject = "Password reset";
    $headers = "From: Parkit <parkitke@gmail.com>";

    mail($email, $subject, $message, $headers);

    return array(true, "A reset link has been reset to your email");
}
