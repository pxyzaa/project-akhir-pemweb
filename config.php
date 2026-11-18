<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$host = "localhost";
$user = "root";
$pw = "";
$database = "db_jabtan";

$conf = mysqli_connect($host, $user, $pw, $database);
if(!$conf){
    die("koneksi gagal" . mysqli_connect_error());
}
?>
