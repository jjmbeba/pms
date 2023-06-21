<?php

session_start();
if(empty($_SESSION['username'])){
    header('location: ../auth/login.php');
}

if($_SESSION['acType'] !== 'admin'){
    header('location: ../auth/login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<?php include 'header.php'; ?>

<body>
    Admin dashboard
    <?php echo 'Welcome ' . $_SESSION['username'];?>
</body>
</html>