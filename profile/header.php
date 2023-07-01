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
  <title>Profile</title>
  <style>
    <?php include '../styles/header.css'; ?>
  </style>
</head>

<body>
  <nav>
    <span class="logo">
      Parkit.
    </span>
    <div class="buttons_container">
      <!-- <a href=""  class="signUp">My Profile</a> -->
      <a class="profile__container">
      <div class="profile__img">
          <img src="../assets/user.svg" alt="user-image">
        </div>  
        <?php echo htmlspecialchars($_SESSION['username']);?>
      </a>
      <a href="../auth/logout.php"  class="login">Log out</a>
    </div>
  </nav>