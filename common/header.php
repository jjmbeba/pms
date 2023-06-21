<?php
session_start();
if(isset($_SESSION['username'])){
  header('location: dashboard/driver.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Parkit.</title>
  <style>
    <?php include 'styles/header.css'; ?>
    <?php include 'styles/footer.css'; ?>
  </style>
</head>

<body>
  <nav>
    <span class="logo">
      Parkit.
    </span>
    <ul class="links_container">
      <li>
        <a href="#features">Features</a>
      </li>
      <li>
        <a href="#contactUs">Contact us</a>
      </li>
      <li>
        <a href="#aboutUs">About us</a>
      </li>
    </ul>
    <div class="buttons_container">
      <a href="auth/signup.php"  class="signUp">Get started</a>
      <a href="auth/login.php"  class="login">Log in</a>
    </div>
  </nav>