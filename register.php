<?php
include 'config.php';
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($nama == "" || $username == "" || $password == "") {
        $pesan = "Semua input wajib diisi!";
    } elseif (strlen($password) < 6) {
        $pesan = "Password minimal 6 karakter!";
    } else {
        $cek = mysqli_prepare($conf, "SELECT id_user FROM users WHERE username = ?");
        mysqli_stmt_bind_param($cek, "s", $username);
        mysqli_stmt_execute($cek);
        $hasil = mysqli_stmt_get_result($cek);

        if (mysqli_num_rows($hasil) > 0) {
            $pesan = "Username sudah dipakai!";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conf, "INSERT INTO users (username, password, nama) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $username, $hash, $nama);

            if (mysqli_stmt_execute($stmt)) {
                header("location:login.php");
                exit;
            } else {
                $pesan = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Register</title>
</head>
<body class="login-page">
    <div class="box">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="judul text-center fw-bold">REGISTER</h3>
                <?php if ($pesan != "") { ?>
                    <p class="error text-center"><?php echo $pesan; ?></p>
                <?php } ?>
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="nama" placeholder="Nama lengkap" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn w-100">Daftar</button>
                    <p class="text-center mt-3">
                        <a href="login.php" class="linkLog">Sudah punya akun? Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>