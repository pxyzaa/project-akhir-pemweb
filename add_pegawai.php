<?php
require "config.php";
$pesan = "";
$query = mysqli_query($conf, "SELECT * FROM jabatan");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nama_pegawai = $_POST['nama_pegawai'];
    $id_jabatan = $_POST['id_jabatan'];
    $email = $_POST['email'];
    $tgl_masuk = $_POST['tgl_masuk'];

    if($nama_pegawai == "" || $id_jabatan == "" || $tgl_masuk == "" || $_FILES['foto']['error'] == UPLOAD_ERR_NO_FILE){
        $pesan = "Semua input wajib diisi!";
    } elseif(!is_numeric($id_jabatan) || $id_jabatan < 1 || $id_jabatan > 3){
        $pesan = "Jabatan tidak valid!";
    } elseif($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)){
        $pesan = "Format email tidak valid!";
    } elseif($_FILES['foto']['error'] != UPLOAD_ERR_OK){
        $pesan = "Terjadi kesalahan saat mengupload foto!";
    } elseif($_FILES['foto']['size'] > 2 * 1024 * 1024){
        $pesan = "Ukuran foto maksimal 2MB!";
    } else{
        $namaFile = $_FILES['foto']['name'];
        $tmpFile = $_FILES['foto']['tmp_name'];

        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        $format = ['jpg', 'png', 'webp', 'gif', 'jpeg'];
        
        if(!in_array($ext, $format)){
            $pesan = "Cover harus berupa jpg/png/webp/gif/jpeg!";
        } elseif(getimagesize($tmpFile) === false){
            $pesan = "File yang diupload bukan gambar!";
        } else{
            $fileBaru = uniqid() . "." . $ext;
            $lokasi = "uploads/" . $fileBaru;

            if(move_uploaded_file($tmpFile, $lokasi)){
                $stmt = mysqli_prepare($conf, "INSERT INTO pegawai(nama_pegawai, foto, id_jabatan, email, tgl_masuk) VALUES (?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssiss", $nama_pegawai, $fileBaru, $id_jabatan, $email, $tgl_masuk);

                if(mysqli_stmt_execute($stmt)){
                    header("location:atur.php");
                    exit;
                } else{
                    unlink($lokasi);
                    $pesan = "Terjadi kesalahan saat menyimpan data.";
                }
            } else{
                $pesan = "Gagal menyimpan file foto.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
    <title>Tambah Pegawai</title>
</head>
<body class="form-page">
    <main class="container py-4">
        <div class="row justify-content-center mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="judul text-center mb-4 fw-bold">TAMBAH PEGAWAI BARU</h4>
                        <?php if($pesan != ""){ ?>
                        <div class="alert alert-danger">
                            <?php echo $pesan; ?>
                        </div>
                        <?php } ?>
                        <!-- ISI -->
                         <form action="add_pegawai.php" method="POST" enctype="multipart/form-data">
                            <!-- foto -->
                             <label class="form-label">Foto Pegawai</label>
                             <input type="file"
                             class="form-control"
                             name="foto"
                             accept=".jpg, .jpeg, .png, .webp, .gif"
                             required>
                            <!-- nama -->
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text"
                                class="form-control"
                                name="nama_pegawai"
                                required>
                            </div>
                            <!-- id jabatan -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Jabatan</label>
                                <select class="form-select" name="id_jabatan" required>
                                    <?php while($row = mysqli_fetch_assoc($query)){ ?>
                                <option value="<?php echo $row['id_jabatan']; ?>">
                                    <?php echo htmlspecialchars($row['nama_jabatan']); ?>
                                </option>
                                <?php } ?>
                                </select>
                            </div>
                            <!-- email -->
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                class="form-control"
                                name="email"
                                required>
                            </div>
                            <!-- tanggal masuk -->
                             <div class="mb-3">
                                <label class="form-label">Tanggal Masuk</label>
                                <input type="date"
                                class="form-control"
                                name="tgl_masuk"
                                required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                Tambah Pegawai
                            </button>
                            <a href="atur.php" class="btn back-btn btn-secondary w-100 mt-2">
                                Kembali
                            </a>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
