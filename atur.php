<?php
include 'cek_atur.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>Atur Pegawai</title>
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
    <h4 class="fw-bold mb-3">Atur Pegawai</h4>

    <form method="get" action="atur.php" class="row g-2 mb-3">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Nama..."
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
            <button type="submit" class="btn btn-primary w-100">Search</button>
        </div>
    </form>

    <a href="add_pegawai.php" class="btn btn-success mb-3">+ Tambah Pegawai</a>

    <?php while ($row = mysqli_fetch_assoc($pegawai)) { ?>
    <div class="card mb-3">
        <div class="card-body d-flex gap-3">
            <div style="width:130px; height:160px; flex-shrink:0; position:relative; background:#e5e7eb; border-radius:4px; overflow:hidden;">
                <?php if ($row['foto'] != "" && file_exists("uploads/" . $row['foto'])) { ?>
                    <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" style="width:100%; height:100%; object-fit:cover;">
                <?php } else { ?>
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Tidak ada foto</div>
                <?php } ?>
                <span style="position:absolute; bottom:0; left:0; background:rgba(0,0,0,.6); color:#fff; font-size:12px; padding:2px 6px;">
                    ID: <?php echo $row['id_pegawai']; ?>
                </span>
            </div>
            <div>
                <p class="mb-1">Nama : <b><?php echo htmlspecialchars($row['nama_pegawai']); ?></b></p>
                <p class="mb-1">Email : <?php echo htmlspecialchars($row['email']); ?></p>
                <p class="mb-2">Jabatan : <?php echo htmlspecialchars($row['nama_jabatan'] ?? '-'); ?></p>

                <a href="edit.php?id=<?php echo $row['id_pegawai']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <form action="delete.php" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin hapus pegawai ini?')">
                    <input type="hidden" name="id" value="<?php echo $row['id_pegawai']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if (mysqli_num_rows($pegawai) == 0) { ?>
        <p>Data tidak ditemukan.</p>
    <?php } ?>

    <p class="text-center mt-4"><a href="dashboard.php">&larr; Kembali ke dashboard biasa</a></p>
</main>
</body>
</html>