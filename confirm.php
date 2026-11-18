<?php
include 'cek_login.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $password = $_POST['password'];
    $passManager = "inimanager123";

    if ($password == $passManager) {
        $_SESSION['mode_atur'] = true;
        header("location:atur.php");
    } else {
        $_SESSION['mode_atur'] = false;
        header("location:dashboard.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Konfirmasi Akses</title>
</head>
<body class="confirm-page">
    <div class="confirm-box">
        <div class="card shadow-sm">
            <div class="card-body">
                <h4 class="judul text-center fw-bold">Konfirmasi Akses</h4>
                <p class="confirm-text text-center">Masukkan password untuk mengatur pegawai.</p>
                <form action="confirm.php" method="POST">
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password"
                               placeholder="Masukkan password" required>
                    </div>
                    <div class="confirm-buttons">
                        <button type="submit" class="btn confirm-btn">Konfirmasi</button>
                        <a href="dashboard.php" class="btn btn-secondary confirm-btn">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>