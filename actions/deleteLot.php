<?php 
require_once('../config/database.php');
require_once('../utils/functions.php');

$statementErr = $successMessage = '';

if (isset($_GET['id']) && isset($_GET['redirect'])){
    $lotId = $_GET['id'];
    $redirect = $_GET['redirect'];

    $result = deleteLot($conn, $lotId);

    if (!empty($result) && $result[0] === false) {
        $statementErr = $result[1];
        header("location:$redirect?error=$statementErr");
    } elseif (!empty($result) && $result[0] === true) {
        $successMessage = $result[1];
        header("location:$redirect?success=$successMessage");
    }
}