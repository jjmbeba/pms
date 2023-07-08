<?php
include './header.php';

require_once('../config/database.php');
require_once('../utils/functions.php');

//Initialize the error variables
$usernameErr = $statementErr = $emailErr = "";

//Check if the username exists (Finds the user)
$result = usernameExists($conn, $_SESSION['username'], $statementErr);

//Assign variables
$newUsername = $result['username'] ?? '';
$newEmail = $result['email'] ?? '';
$successMessage = '';

//Check if the post request is set
if (isset($_POST['submit'])) {

    //Validate the username
    if (empty($_POST['username'])) {
        $usernameErr = "Username is required";
    } else {
        $newUsername = sanitizeInput($_POST['username']);

        //Check if name field only contains letters, dashes, whitespaces and apostrophes
        if (!preg_match("/^[a-zA-Z-' ]*$/", $newUsername)) {
            $usernameErr = "Only letters and whitespaces are allowed";
        }
    }

    //Validate the email
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } else {
        $newEmail = sanitizeInput($_POST['email']);

        //Check email format
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    //If errors are empty, change user details
    if (empty($usernameErr) && empty($emailErr)) {
        $result = changeDetails($conn, $newUsername, $newEmail);

        //Check if the result returns an error
        if(isset($result) && $result[0] === false){
            $statementErr = $result[1];
        } elseif(isset($result) && $result[0] === true){
            $successMessage = $result[1];

            //Changes the session username
            $_SESSION['username'] = $newUsername;
        }
    }
}

?>

<body>
    <div class="profile-img__container">
        <img src="../assets/user.svg" alt="user-image" class="profile__image">
    </div>
    <div class="form__container">
        <h1>Change User Details</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <div class="input__container">
                <input type="text" name="username" class="input" placeholder=" " value="<?php echo $newUsername; ?>">
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
                <input type="text" name="email" class="input" placeholder=" " value="<?php echo $newEmail; ?>">
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
            <a href="./changePassword.php" class="page-link">Change Password</a>
            <input class="submit__btn" type="submit" name="submit" value="Change Details">
        </form>
    </div>
    <?php include 'footer.php';?>