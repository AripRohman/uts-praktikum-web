<?php
$nim = $_POST['nim'];
$nama_mhs = $_POST['nama_lngkp_mhs'];
$nilai_akhir = $_POST['nilai_akhir'];

require 'koneksi.php';
require_once 'upload_gambar.php';

if ($nilai_akhir >= 81) {
    $nilai_huruf = "A";
    $predikat = "Sangat Baik";
} elseif ($nilai_akhir >= 66) {
    $nilai_huruf = "B";
    $predikat = "Baik";
} elseif ($nilai_akhir >= 51) {
    $nilai_huruf = "C";
    $predikat = "Cukup";
} elseif ($nilai_akhir >= 41) {
    $nilai_huruf = "D";
    $predikat = "Buruk";
} else {
    $nilai_huruf = "E";
    $predikat = "Sangat Buruk";
}

$target_file = uploadGambar($_FILES['gambar']);
if ($target_file === false) {
    exit;
}

$sql = "INSERT INTO nilaimhs (nim, nama_mhs, nilai_akhir, nilai_huruf, predikat, gambar)
VALUES ('$nim', '$nama_mhs', '$nilai_akhir', '$nilai_huruf', '$predikat', '$target_file')";

if (mysqli_query($conn, $sql)) {
    header("Location: adminlte.php");
    exit;
} else {
    echo "Error inserting record: " . $sql . "<br>" . mysqli_error($conn);
}
?>