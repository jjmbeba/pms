<?php
include './header.php';

require_once('../config/database.php');
require_once('../utils/functions.php');

//Initialize the error variables
$passwordErr = $newPasswordErr = $confirmPasswordErr = $statementErr  = "";

//Check if the username exists (Finds the user)
$result = usernameExists($conn, $_SESSION['username'], $statementErr);

//Assign variables
$password = $newPassword = $confirmPassword = $successMessage = '';

//Check if the post request is set
if (isset($_POST['submit'])) {
    //Validate the password
    if (empty($_POST['password'])) {
        $passwordErr = "Current Password is required";
    } elseif (strlen($_POST['password']) < 6) {
        $passwordErr = "Password is too short";
    } else {
        $password = sanitizeInput($_POST['password']);
    }

    //Validate the new password
    if (empty($_POST['newPassword'])) {
        $newPasswordErr = "New Password is required";
    } elseif (strlen($_POST['newPassword']) < 6) {
        $newPasswordErr = "Password is too short";
    } else {
        $newPassword = sanitizeInput($_POST['newPassword']);
    }

    //Validate the confirm password
    if (empty($_POST['cpassword'])) {
        $confirmPasswordErr = "Confirm Password is required";
    }  //Check if password and confirm password match
    elseif ($_POST['newPassword'] !== $_POST['cpassword']) {
        $confirmPasswordErr = "Password and confirm password do not match";
    } else {
        $confirmPassword = sanitizeInput($_POST['cpassword']);
    }

    //If there are no errors, trigger the changePassword function
    if (empty($passwordErr) && empty($newPasswordErr) && empty($confirmPasswordErr)) {
        $result = changePassword($conn, $password, $newPassword);

        //If the result returns an error, display it
        if (!empty($result) && $result[0] === false) {
            $statementErr = $result[1];
        } elseif (!empty($result) && $result[0] === true) {
            $successMessage = $result[1];
        }
    }
}

?>

<body>
    <div class="profile-img__container">
        <img src="../assets/user.svg" alt="user-image" class="profile__image">
    </div>
    <div class="form__container">
        <h1>Change User Password</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="input__container">
                <input type="password" name="password" class="input password" placeholder=" " value="<?php echo $password; ?>">
                <img src="../assets/eyeshut.svg" class="password__controls" alt="hidden">
                <label for="password" class="input__label">Current Password</label>
            </div>
            <div class="invalid" style="<?php echo $passwordErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                <div style='display:flex; align-items:center; gap:5px;'>
                <img src='../assets/warning.svg' alt='warning'/>
                $passwordErr
                </div>
                "; ?>
            </div>
            <div class="input__container">
                <input type="password" name="newPassword" class="input password" placeholder=" " value="<?php echo $newPassword; ?>">
                <img src="../assets/eyeshut.svg" class="password__controls" alt="hidden">
                <label for="newPassword" class="input__label">New Password</label>
            </div>
            <div class="invalid" style="<?php echo $newPasswordErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                <div style='display:flex; align-items:center; gap:5px;'>
                <img src='../assets/warning.svg' alt='warning'/>
                $newPasswordErr
                </div>
                "; ?>
            </div>
            <div class="input__container">
                <input type="password" name="cpassword" class="input password" placeholder=" " value="<?php echo $confirmPassword; ?>">
                <img src="../assets/eyeshut.svg" class="password__controls" alt="hidden">
                <label for="cpassword" class="input__label">Confirm Password</label>
            </div>
            <div class="invalid" style="<?php echo $confirmPasswordErr ? 'display:block;' : 'display:none;' ?>">
                <?php echo "
                <div style='display:flex; align-items:center; gap:5px;'>
                <img src='../assets/warning.svg' alt='warning'/>
                $confirmPasswordErr
                </div>
                "; ?>
            </div>
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
            <a href="./changeDetails.php" class="page-link">Change Details</a>
            <input class="submit__btn" type="submit" name="submit" value="Change Details">
        </form>
    </div>
    <?php include 'footer.php'; ?>