<?php

$statementErr = $successMessage = $bookingId = $bookingName = $bookingLocation = '';

if(isset($_GET['bookingId']) && isset($_GET['name']) && isset($_GET['location'])){
    $bookingId = $_GET['bookingId'];
    $bookingName = $_GET['name'];
    $bookingLocation = $_GET['location'];
}

if(isset($_GET['error'])){
    $statementErr = $_GET['error'];
} elseif (isset($_GET['success'])) {
    $successMessage = $_GET['success'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Slot</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>
<?php include 'header.php'; ?>

<body>
    <form method="POST" action='/pms/actions/payForSlot.php' style="width: 30vw; margin-inline: auto; margin-top:70px;">
        <h1>Pay for slot</h1>
        <div class="input__container">
            <input type="number" name="number" class="input">
            <label for="number" class="input__label">Enter your mpesa number</label>
        </div>
        <input type="hidden" name="bookingId" value="<?php echo $bookingId;?>">
        <input type="hidden" name="bookingLocation" value="<?php echo $bookingLocation;?>">
        <input type="hidden" name="bookingName" value="<?php echo $bookingName;?>">
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
        <input class='submit__btn' type='submit' name='submit' value='Pay now'>
    </form>
</body>
<script src="/pms/script.js"></script>
</html>