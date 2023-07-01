<?php

require_once('../config/database.php');
require_once('../utils/functions.php');

$email = $emailErr = $statementErr = "";

if(isset($_POST['submit'])){
    if(empty($_POST['email'])){
        $emailErr = "Email is required";
    } else{
        $email = sanitizeInput($_POST['email']);

        //Check email format
        if (!filter_var($email, FILTER_SANITIZE_EMAIL)) {
            $emailErr = "Invalid email format";
        }

        //Check if email exists
        if (emailExists($conn, $email, $statementErr) === false) {
            $emailErr = "Email does not exist";
        }

        if (empty($emailErr)) {
            echo $email;
        }
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
    <div class="form__container">
        <h1>Forgot password</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <p>
                An e-mail will be sent to you with instructions on how to reset your password
            </p>
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
            <input class="submit__btn" type="submit" name="submit" value="Reset">
        </form>
    </div>
</body>

</html>