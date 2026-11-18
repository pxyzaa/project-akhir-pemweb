<?php
session_start();
require "config.php";
$pesan = "";

if(!isset($_SESSION['id_user'])){
    header("location:login.php");
    exit;
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $password = $_POST['password'];
    $passManager = "inimanager123";
    if($password == $passManager){
        $_SESSION['mode_atur'] = true;
        header("location:atur.php");
        exit;
    } else{
        $_SESSION['mode_atur'] = false;
        header("location:dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Konfirmasi Akses</title>
</head>
<body class="confirm-page">
    <div class="confirm-box">
        <div class="row justify-content-center mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h3 class="judul fw-bold text-center mb-3">KONFIRMASI AKSES</h3>
                        <p style="color: #3d537b;" 
                        class="text-center confirm-text">
                            Masukkan password untuk mengakses data pegawai.
                        </p>
                        <form action="confirm.php" method="POST">
                            <div class="mb-3">
                                <input class="form-control" type="password" name="password" placeholder="Password" required>
                            </div>
                            <div class="confirm-buttons">
                                <button type="submit" class="btn confirm-btn">Konfirmasi</button>
                                <a href="dashboard.php" class="btn confirm-btn">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
