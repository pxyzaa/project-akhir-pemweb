<?php
session_start();
require "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id = $_POST['id'];
    $stmt = mysqli_prepare($conf, "SELECT foto FROM pegawai WHERE id_pegawai = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if($data){
        $file = "uploads/" . $data['foto'];
        if($data['foto'] != "" && file_exists($file)){
            unlink($file);
        }
        $delete = mysqli_prepare($conf, "DELETE FROM pegawai WHERE id_pegawai = ?");
        mysqli_stmt_bind_param($delete, "i", $id);
        mysqli_stmt_execute($delete);
        mysqli_stmt_close($delete);
    }
    mysqli_stmt_close($stmt);
}
header("location:atur.php");
exit;
?>