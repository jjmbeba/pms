<?php
require_once('../config/database.php');
require_once('../utils/functions.php');

//Fetch all parking lots
$lots = getAllLots($conn);
$statementErr = $successMessage = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Slot</title>
    <link rel="stylesheet" href="../styles/dashboard.css">
</head>
<?php include 'header.php';?>
<body>
    <form method="POST" action='/pms/actions/bookSlot.php' style="width: 30vw; margin-inline: auto; margin-top:70px;">
        <h1>Book a slot</h1>
        <div class='input__container'>
            <select name='location' class='input'>
                <?php while ($row = $lots->fetch_assoc()) {
                    $fetchedId = $row['id'];
                    $fetchedName = $row['name'];
                    $fetchedLocation = $row['location'];

                    echo "<option value='$fetchedId'>$fetchedName - $fetchedLocation</option>";
                }; ?>
            </select>
            <label for='location' class='input__label'>Location</label>
            <div class="input__container">
                <input type="datetime-local" name="timeIn" class="input">
                <label for="timeIn" class="input__label">Time in</label>
            </div>
            <div class="input__container">
                <input type="datetime-local" name="timeOut" class="input">
                <label for="timeOut" class="input__label">Time out</label>
            </div>
        </div>
        <input class='submit__btn' type='submit' name='submit' value='Book parking slot'>
    </form>
</body>
<script src="/pms/script.js"></script>

</html>