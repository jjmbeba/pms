<?php

require_once('../config/database.php');
require_once('../utils/functions.php');

session_start();
if (isset($_SESSION['username'])) {
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

//Initialize the value variables
$username = $email = $password = $confirmPassword = $acType = "";

//Initialize the error variables
$usernameErr = $emailErr = $passwordErr = $confirmPasswordErr = $statementErr = "";

//Check if the submit post request is set
if (isset($_POST['submit'])) {
    //Validate the username
    if (empty($_POST['username'])) {
        $usernameErr = "Username is required";
    } else {
        $username = sanitizeInput($_POST['username']);

        //Check if name field only contains letters, dashes, whitespaces and apostrophes
        if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
            $usernameErr = "Only letters and whitespaces are allowed";
        }

        //Check if email exists
        if (usernameExists($conn, $username, $statementErr) !== false) {
            $usernameErr = "Username exists";
        }
    }

    //Validate the email
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } else {
        $email = sanitizeInput($_POST['email']);

        //Check email format
        if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $emailErr = "Invalid email format";
        }

        //Check if email exists
        if (emailExists($conn, $email, $statementErr) !== false) {
            $emailErr = "Email exists";
        }
    }

    //Assign the account type to a variable
    $acType = $_POST['acType'];

    //Validate the password
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required";
    } elseif (strlen($_POST['password']) < 6) {
        $passwordErr = "Password is too short";
    } else {
        $password = sanitizeInput($_POST['password']);
    }

    //Validate the confirm password
    if (empty($_POST['cpassword'])) {
        $confirmPasswordErr = "Confirm Password is required";
    }  //Check if password and confirm password match
    elseif ($_POST['password'] !== $_POST['cpassword']) {
        $confirmPasswordErr = "Password and confirm password do not match";
    } else {
        $confirmPassword = sanitizeInput($_POST['cpassword']);
    }

    //If all errors are empty, create a user account
    if (empty($usernameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {
        createUser($conn, $username, $email, $acType, $password, $statementErr);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <style>
        <?php include '../styles/auth.css'; ?>
    </style>
</head>

<body>
    <a href="/pms" class="home__link">
        <img src="../assets/home.svg" alt="home" />
        <span>Home</span>
    </a>
    <div class="form__container">
        <h1>Create an account</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="input__container">
                <input type="text" name="username" class="input" placeholder=" " value="<?php echo $username; ?>">
                <label for="username" class="input__label">Username</label>
                <div class="invalid" style="<?php echo $usernameErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $usernameErr
                    </div>
                    "; ?>
                </div>
            </div>
            <div class="input__container">
                <input type="email" name="email" class="input" placeholder=" " value="<?php echo $email; ?>">
                <label for="email" class="input__label">Email</label>
                <div class="invalid" style="<?php echo $emailErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $emailErr
                    </div>
                    "; ?>
                </div>
            </div>
            <div class="input__container">
                <select name="acType" class="input">
                    <option value="driver">Driver</option>
                    <option value="manager">Parking Lot Manager</option>
                </select>
                <label for="acType" class="input__label">Account Type</label>
            </div>
            <div class="input__container">
                <input type="password" name="password" class="input" placeholder=" " value="<?php echo $password; ?>">
                <label for="password" class="input__label">Password</label>
                <div class="invalid" style="<?php echo $passwordErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $passwordErr
                    </div>
                    "; ?>
                </div>
            </div>
            <div class="input__container">
                <input type="password" name="cpassword" class="input" placeholder=" " value="<?php echo $confirmPassword; ?>">
                <label for="cpassword" class="input__label">Confirm Password</label>
                <div class="invalid" style="<?php echo $confirmPasswordErr ? 'display:block;' : 'display:none;' ?>">
                    <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $confirmPasswordErr
                    </div>
                    "; ?>
                </div>
            </div>
            <a href="login.php" class="page-link">
                Already have an account?
            </a>
            <div class="invalid" style="<?php echo $statementErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
            </div>
            <input class="submit__btn" type="submit" name="submit" value="Create an account">
        </form>
    </div>
</body>

</html>