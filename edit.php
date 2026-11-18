<?php
session_start();
require "config.php";

if(!isset($_SESSION['id_user'])){
    header("location:login.php");
    exit;
}

$pesan = "";

if(!isset($_GET['id'])){
    header("location:atur.php");
    exit;
}

$id = $_GET['id'];
$pesan = "";
$stmt = mysqli_prepare($conf, "SELECT * FROM pegawai WHERE id_pegawai  = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if(!$data){
    header("location:atur.php");
    exit;
}

$query = mysqli_query($conf, "SELECT * FROM jabatan");

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nama_pegawai = $_POST['nama_pegawai'];
    $id_jabatan = $_POST['id_jabatan'];
    $email = $_POST['email'];
    $tgl_masuk = $_POST['tgl_masuk'];

    if($nama_pegawai == "" || $id_jabatan == "" || $tgl_masuk == "" || $email == ""){
        $pesan = "Semua input wajib diisi!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $pesan = "Format email tidak valid!";
    } else{
        $foto = $data['foto'];
        $fotoBaru = false;
        $fileBaru = "";

        if(isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE){
            if($_FILES['foto']['error'] != UPLOAD_ERR_OK){
                $pesan = "Gagal mengupload foto.";
            } elseif($_FILES['foto']['size'] > 2 * 1024 *1024){
                $pesan = "Ukuran foto maksimal 2 MB!";
            } else{
                $namaFile = $_FILES['foto']['name'];
                $tmpFile = $_FILES['foto']['tmp_name'];
                $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
                $format = ['jpg', 'png', 'webp', 'jpeg', 'gif'];
                if(!in_array($ext, $format)){
                    $pesan = "Foto harus berupa jpg/jpeg/png/webp/gif!";
                } elseif(getimagesize($tmpFile) === false){
                    $pesan = "File yang diupload bukan gambar!";
                } else{
                    $fileBaru = uniqid() . "." . $ext;
                    $lokasi = "uploads/" . $fileBaru;

                    if(move_uploaded_file($tmpFile, $lokasi)){
                        $foto = $fileBaru;
                        $fotoBaru = true;
                    } else{
                        $pesan = "Gagal menyimpan foto baru.";
                    }
                }
            }
        }

        if($pesan == ""){
            $stmt = mysqli_prepare($conf, "UPDATE pegawai SET foto = ?, nama_pegawai = ?, id_jabatan = ?, email = ?, tgl_masuk = ? WHERE id_pegawai = ?");
            mysqli_stmt_bind_param($stmt, "ssissi", $foto, $nama_pegawai, $id_jabatan, $email, $tgl_masuk, $id);

            if(mysqli_stmt_execute($stmt)){
                if($fotoBaru && $data['foto'] != ""){
                    $fotoLama = "uploads/" . $data['foto'];
                    if(file_exists($fotoLama)){
                        unlink($fotoLama);
                    }
                }
                header("location:atur.php");
                exit;
            } else{
                if($fotoBaru && file_exists("uploads/" . $fileBaru)){
                    unlink("uploads/" . $fileBaru);
                }
                $pesan = "Terjadi kesalahan saat mengupdate data.";
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
    <title>Edit Info Pegawai</title>
</head>
<body class="form-page">
    <main class="container py-4">
        <div class="row justify-content-center mt-4">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <h4 class="judul text-center">
                            EDIT INFO PEGAWAI
                        </h4>
                        <?php if($pesan != ""){ ?>
                        <div class="alert alert-danger">
                            <?php echo $pesan; ?>
                        </div>
                        <?php } ?>
                        <!-- isi -->
                         <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Current Photo</label><br>
                                <img src="uploads/<?php echo htmlspecialchars($data['foto']); ?>" 
                                alt="Current Photo"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text"
                                class="form-control"
                                name="nama"
                                value="<?php echo htmlspecialchars($data['nama']); ?>"
                                required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jabatan</label>
                                <select class="form-select" 
                                name="id_jabatan" 
                                required>
                                <?php while($row = mysqli_fetch_assoc($query)){ ?>
                                <option value="<?php echo $row['id_jabatan']; ?>" <?php if($row['id_jabatan'] == $data['id_jabatan']) echo "selected"; ?>>
                                    <?php echo htmlspecialchars($row['nama_jabatan']); ?>
                                </option>
                                <?php } ?>
                            </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                class="form-control"
                                name="nama"
                                value="<?php echo htmlspecialchars($data['email']); ?>"
                                required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Tanggal Masuk</label>
                                <input type="date"
                                class="form-control"
                                name="tgl_masuk"
                                value="<?php echo $data['tgl_masuk']; ?>"
                                required>
                            </div>
                             <button type="submit" class="btn btn-success w-100">
                                Simpan Perubahan
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
