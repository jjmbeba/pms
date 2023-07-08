<?php
require_once('../common/theme.php');

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
  <title>Parkit.</title>
  <style>
    <?php include '../styles/header.css'; ?>
    <?php include '../styles/dashboard.css'; ?>
  </style>
</head>

<body class="<?php echo $theme;?>">
  <nav>
    <span class="logo">
      Parkit.
    </span>
    <?php echo $theme === 'dark' ? "<img class='theme__icon' src='/pms/assets/sun.svg' alt='sun'>" : "<img class='theme__icon' src='/pms/assets/moon.svg' alt='moon'>"; ?> <div class="form__container">
    <div class="buttons_container">
      <a class="profile__container" href="../profile/changeDetails.php">
        <div class="profile__img">
          <img src="../assets/user.svg" alt="user-image">
        </div>
        <?php echo htmlspecialchars($_SESSION['username']);?>
      </a>
      <a href="../auth/logout.php"  class="login">Log out</a>
    </div>
  </nav>