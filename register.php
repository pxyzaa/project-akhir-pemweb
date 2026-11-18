<?php
include 'config.php';
$pesan = "";

if (isset($_POST['daftar'])) {
    $nama = mysqli_real_escape_string($conf, $_POST['nama']);
    $username = mysqli_real_escape_string($conf, $_POST['username']);
    $password = $_POST['password'];

    $cek = mysqli_query($conf, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Username sudah dipakai!";
    }elseif(strlen($password) < 6){
        $pesan = "Password minimal 6 karakter!";
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = mysqli_prepare($conf, "INSERT INTO users (username, password, nama) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $username, $password, $nama);
        if(mysqli_stmt_execute($stmt)){
            header("location:login.php?status=registered");
            exit;
        } else{
            $pesan = "Terjadi kesalahan saat menyimpan data.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
<div class="box">
    <div class="row justify-content-center mt-4">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="judul fw-bold text-center mb-4">REGISTER</h2>
                    <?php if($pesan != ""){ ?>
                        <div class="alert alert-danger">
                            <?php echo $pesan; ?>
                        </div>
                        <?php } ?>
                    <form action="register.php" method="post">
                        <div class="mb-3">
                            <input class="form-control" type="text" name="nama" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                        <button class="btn w-100" type="submit" name="daftar">Daftar</button>
                    </form>
                    <p class="text-center">
                        <a class="linkLog" href="login.php">Sudah punya akun? Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
