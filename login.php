<?php
include 'config.php';
$pesan = "";

if(isset($_GET['status']) && $_GET['status'] == "registered"){
    $sukses = "Registrasi berhasil ! Silahkan login";
}

if (isset($_SESSION['id_user'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conf, $_POST['username']);
    $password = $_POST['password'];

    $query = mysqli_query($conf, "SELECT * FROM users WHERE username='$username'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        $pesan = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
<div class="box">
    <div class="row justify-content-center mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                    <h2 class="judul fw-bold text-center mt-4 mb-4">LOGIN</h2>
                    <?php if($pesan != ""){ ?>
                        <div class="alert alert-danger">
                            <?php echo $pesan; ?>
                        </div>
                        <?php } ?>
                    <form action="login.php" method="post">
                        <div class="mb-3">
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                        </div>
                        <button class="btn w-100" type="submit" name="login">Login</button>
                    </form>
                    <p class="text-center">
                        <a class="linkLog" href="register.php">Belum punya akun? Daftar</a>
                    </p>
                </div>
            </div>
        </div>
    </div>    
</div>
</body>
</html>
