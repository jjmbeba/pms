<?php

require_once('../config/database.php');
require_once('../utils/functions.php');
require_once('../common/theme.php');

$email = $emailErr = $successMessage = $statementErr = "";

if (isset($_POST['submit'])) {
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } else {
        $email = sanitizeInput($_POST['email']);

        //Check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }

        //Check if email exists
        if (emailExists($conn, $email, $statementErr) === false) {
            $emailErr = "Email does not exist";
        }

        if (empty($emailErr)) {

            $token = bin2hex(random_bytes(32));

            //Set expiry time to 1 hour from current time
            $expires = date("U") + 1800;

            //Send the email
            $result = sendEmail($conn, $email, $token, $expires);

            if ($result && $result[0] === false) {
                $statementErr = $result[1];
            } elseif ($result && $result[0] === true) {
                $successMessage = $result[1];
            }
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
    <a href="/pms" class="home__link">
        <img src="../assets/home.svg" alt="home" />
        <span>Home</span>
    </a>
    <?php echo $theme === 'dark' ? "<img class='theme__icon' src='/pms/assets/sun.svg' alt='sun'>" : "<img class='theme__icon' src='/pms/assets/moon.svg' alt='moon'>"; ?>
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
            <div class="invalid" style="<?php echo $statementErr ? 'display:block; margin-left:0;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/warning.svg' alt='warning'/>
                    $statementErr
                    </div>
                    "; ?>
            </div>
            <div class="success" style="<?php echo $successMessage ? 'display:block; margin-left:0;' : 'display:none;' ?>">
                <?php echo "
                    <div style='display:flex; align-items:center; gap:5px;'>
                    <img src='../assets/success.svg' alt='success'/>
                    $successMessage
                    </div>
                    "; ?>
            </div>
            <input class="submit__btn" type="submit" name="submit" value="Reset">
        </form>
    </div>
    <script src="/pms/script.js"></script>
</body>

</html>