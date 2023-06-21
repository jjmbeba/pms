<?php
session_start();
if(empty($_SESSION['username'])){
    header('location: ../auth/login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Lot Manager</title>
</head>
<?php include 'header.php'; ?>

<body>
    Parking Lot Manager dashboard <br/>
    <?php echo 'Welcome ' . $_SESSION['username'];?>
</body>
</html>