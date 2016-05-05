<?php

// Sign-out
If ($IsSignin) {
    $IsSignin = false;
    unset($_SESSION['signin_userid']);
    unset($_SESSION['signin_name']);
}

header('Location: /');
exit();

// 文字コードUTF-8用

?>
