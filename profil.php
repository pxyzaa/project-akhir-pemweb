<?php
include 'cek_login.php';

$id = (int) $_SESSION['id_user'];
$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $password = $_POST['password'];

    if ($nama == "") {
        $pesan = "Nama tidak boleh kosong!";
    } else {
        $stmt = mysqli_prepare($conf, "UPDATE users SET nama = ? WHERE id_user = ?");
        mysqli_stmt_bind_param($stmt, "si", $nama, $id);
        mysqli_stmt_execute($stmt);
        $_SESSION['nama'] = $nama;

        // password diganti hanya kalau diisi
        if ($password != "") {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conf, "UPDATE users SET password = ? WHERE id_user = ?");
            mysqli_stmt_bind_param($stmt, "si", $hash, $id);
            mysqli_stmt_execute($stmt);
        }
        $pesan = "Profil berhasil diperbarui.";
    }
}

$stmt = mysqli_prepare($conf, "SELECT * FROM users WHERE id_user = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Profil</title>
</head>
<body>
<nav class="navbar navbar-dark bg-primary px-3">
    <div>
        <a class="text-white text-decoration-none me-3" href="dashboard.php">Dashboard</a>
        <a class="text-white text-decoration-none" href="profil.php">Profil</a>
    </div>
    <div class="text-white">
        Halo, <b><?php echo htmlspecialchars($_SESSION['nama']); ?></b> |
        <a class="text-white" href="logout.php">Logout</a>
    </div>
</nav>

<main class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="text-center fw-bold mb-3">PROFIL SAYA</h4>
                    <?php if ($pesan != "") { ?>
                        <div class="alert alert-info"><?php echo $pesan; ?></div>
                    <?php } ?>
                    <form action="profil.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password baru (kosongkan jika tidak diganti)</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Simpan</button>
                        <a href="dashboard.php" class="btn btn-secondary w-100 mt-2">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
</body>
</html>