<?php
include 'cek_login.php';
include 'config.php';

$_SESSION['mode_atur'] = false;

$cari = "%" . trim($_GET['cari'] ?? '') . "%";
$filter = (int) ($_GET['jabatan'] ?? 0);

$sql = "SELECT p.*, j.nama_jabatan
        FROM pegawai p
        LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
        WHERE p.nama_pegawai LIKE ?";
if ($filter > 0) {
    $sql .= " AND p.id_jabatan = ?";
}
$sql .= " ORDER BY p.id_pegawai";

$stmt = mysqli_prepare($conf, $sql);
if ($filter > 0) {
    mysqli_stmt_bind_param($stmt, "si", $cari, $filter);
} else {
    mysqli_stmt_bind_param($stmt, "s", $cari);
}
mysqli_stmt_execute($stmt);
$pegawai = mysqli_stmt_get_result($stmt);

$list_jabatan = mysqli_query($conf, "SELECT * FROM jabatan ORDER BY id_jabatan");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@6.0.0-alpha.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-B/GM4XqrwHnWXNOWMbloTmrYXZg10cakYGmpfsR/bbzQ6JAJI4ihuyADKLnBgrCe" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Dashboard</title>
</head>
<body class="dashboard-page">
<nav class="dashboard-navbar">
    <div class="nav-container">
        <a class="navbar-greeting" href="dashboard.php">
            Halo, <b><?php echo htmlspecialchars($_SESSION['nama']); ?></b>
        </a>     
        <div class="nav-menu">
            <a class="nav-link" href="dashboard.php">Dashboard</a>
            <a class="nav-link" href="profil.php">Profil</a>
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<main class="dashboard-main">
    <h4 class="dashboard-title fw-bold" style="margin-bottom: 10px; color: #3d537b">Daftar Pegawai</h4>

    <form method="get" action="dashboard.php" class="search-form mb-3">
        <div class="row g-2">
            <div class="col-md-6">
                <input style="padding: 12px 15px; box-sizing: border-box" type="text" name="cari" class="form-control" placeholder="Nama..."
                   value="<?php echo htmlspecialchars($_GET['cari'] ?? ''); ?>">
                </div>
                <div class="col-md-4">
                    <select name="jabatan" class="form-select">
                        <option value="0">Jabatan: All</option>
                        <?php while ($j = mysqli_fetch_assoc($list_jabatan)) { ?>
                        <option value="<?php echo $j['id_jabatan']; ?>" <?php if ($filter == $j['id_jabatan']) echo "selected"; ?>>
                            <?php echo htmlspecialchars($j['nama_jabatan']); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn w-100">Search</button>
                </div>
        </div>
    </form>

    <?php while ($row = mysqli_fetch_assoc($pegawai)) { ?>
    <div class="pegawai-card mb-3">
        <div class="pegawai-card-body d-flex gap-3">
            <div class="foto-pegawai">
                <?php if ($row['foto'] != "" && file_exists("uploads/" . $row['foto'])) { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php } else { ?>
                    <div class="no-foto">Tidak ada foto</div>
                <?php } ?>
                <span class="id-pegawai">
                    ID: <?php echo $row['id_pegawai']; ?>
                </span>
            </div>
            <div class="data-pegawai">
                <p class="mb-1">Nama : <b><?php echo htmlspecialchars($row['nama_pegawai']); ?></b></p>
                <p class="mb-1">Email : <?php echo htmlspecialchars($row['email']); ?></p>
                <p class="mb-1">Jabatan : <?php echo htmlspecialchars($row['nama_jabatan'] ?? '-'); ?></p>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (mysqli_num_rows($pegawai) == 0) { ?>
        <p>Data tidak ditemukan.</p>
    <?php } ?>

    <a class="atur-pegawai" href="confirm.php">Atur pegawai?</a>
</main>
</body>
</html>
