<?php
include 'config.php';
$pesan = "";

if (isset($_SESSION['id_user'])) {
    header("location:dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conf, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama'] = $user['nama'];
        header("location:dashboard.php");
        exit;
    } else {
        $pesan = "Username atau password salah!";
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
    <title>Login</title>
</head>
<body class="login-page">
    <div class="box">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="judul text-center fw-bold">LOGIN</h3>
                <?php if ($pesan != "") { ?>
                    <p class="error text-center"><?php echo $pesan; ?></p>
                <?php } ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn w-100">Login</button>
                    <p class="text-center mt-3">
                        <a href="register.php" class="linkLog">Belum punya akun? Daftar</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>