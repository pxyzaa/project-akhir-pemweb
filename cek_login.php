<?php
include 'config.php';

if (!isset($_SESSION['id_user'])) {
    header("location:login.php");
    exit;
}
?>