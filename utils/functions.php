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

function emailExists($conn, $email, $statementErr)
{
    $sql = "SELECT * FROM users WHERE email = ?";
    $statement = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($statement, $sql)) {
        $statementErr = "Statement failed";
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

function usernameExists($conn, $username, $statementErr)
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
        return array(false, "Email does not exist");
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

        if ($_SESSION['acType'] === 'driver') {
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
