<?php
require_once('../config/database.php');
require_once('../utils/functions.php');

$statementErr = $successMessage = '';

if (isset($_GET['id']) && isset($_GET['redirect'])) {

    $userId = $_GET['id'];
    $redirect = $_GET['redirect'];
    
        $result = deleteUser($conn, $userId);

        if (!empty($result) && $result[0] === false) {
            $statementErr = $result[1];
            header("location:$redirect?error=$statementErr");
        } elseif (!empty($result) && $result[0] === true) {
            $successMessage = $result[1];
            header("location:$redirect?success=$successMessage");
        }

    }