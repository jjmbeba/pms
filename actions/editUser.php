<?php
require_once('../config/database.php');
require_once('../utils/functions.php');

$statementErr = $successMessage = '';

if (isset($_GET['id']) && isset($_GET['redirect']) && isset($_GET['acType'])) {

    $userId = $_GET['id'];
    $redirect = $_GET['redirect'];
    $acType = $_GET['acType'];

    echo "account type : $acType <br/> id : $userId <br/> redirect : $redirect";
    
        $result = editUser($conn, $userId, $acType);

        if (!empty($result) && $result[0] === false) {
            $statementErr = $result[1];
            header("location:$redirect?error=$statementErr");
        } elseif (!empty($result) && $result[0] === true) {
            $successMessage = $result[1];
            header("location:$redirect?success=$successMessage");
        }

    }