<?php
include 'cek_login.php';

if (empty($_SESSION['mode_atur'])) {
    header("location:confirm.php");
    exit;
}
?>