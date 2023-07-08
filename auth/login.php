<?php

require_once('../config/database.php');
require_once('../utils/functions.php');
require_once('../common/theme.php');

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

//Initialize the variables
$username = $password = "";

//Initialize the error variables
$usernameErr = $passwordErr = $statementErr = "";

//Check if the post request is set
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
    }

    //Validate the password
    if (empty($_POST['password'])) {
        $passwordErr = "Password is required";
    } elseif (strlen($_POST['password']) < 6) {
        $passwordErr = "Password is too short";
    } else {
        $password = sanitizeInput($_POST['password']);
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $result = loginUser($conn, $username, $password, $statementErr);
        if ($result[0] === false) {
            $statementErr = $result[1];
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in</title>
    <style>
        <?php include '../styles/auth.css'; ?>
    </style>
</head>

<body class="<?php echo $theme;?>">
    <a href="/pms" class="home__link">
        <img src="../assets/home.svg" alt="home" />
        <span>Home</span>
    </a>
    <?php echo $theme === 'dark' ? "<img class='theme__icon' src='/pms/assets/sun.svg' alt='sun'>" : "<img class='theme__icon' src='/pms/assets/moon.svg' alt='moon'>";?>
    <div class="form__container">
        <h1>Login</h1>
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
                <input type="password" name="password" class="input password" placeholder=" " value="<?php echo $password; ?>">
                <img src="../assets/eyeshut.svg" class="password__controls" alt="hidden">
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
            <a href="forgot-password.php" class="page-link">
                Forgot your password?
            </a>
            <a href="signup.php" class="page-link">
                Don't have an account?
            </a>
            <div class="invalid" style="<?php echo $statementErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
            </div>
            <input class="submit__btn" type="submit" name="submit" value="Login">
        </form>
    </div>
    <script src="/pms/script.js"></script>
</body>

</html>