<?php

require_once('../config/database.php');
require_once('../utils/functions.php');
require_once('../common/theme.php');

$password = $passwordErr = $confirmPassword = $confirmPasswordErr = "";

if(isset($_POST['submit'])){
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



}   

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot password</title>
    <style>
        <?php include '../styles/auth.css'; ?>
    </style>
</head>

<body>
    <a href="/pms" class="home__link">
        <img src="../assets/home.svg" alt="home" />
        <span>Home</span>
    </a>
    <?php echo $theme === 'dark' ? "<img class='theme__icon' src='/pms/assets/sun.svg' alt='sun'>" : "<img class='theme__icon' src='/pms/assets/moon.svg' alt='moon'>"; ?>
    <div class="form__container">
        <h1>Reset password</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
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
            <div class="input__container">
                <input type="password" name="cpassword" class="input password" placeholder=" " value="<?php echo $confirmPassword; ?>">
                <img src="../assets/eyeshut.svg" class="password__controls" alt="hidden">
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
            <input class="submit__btn" type="submit" name="submit" value="Reset">
        </form>
    </div>
    <script src="/pms/script.js"></script>
</body>

</html>