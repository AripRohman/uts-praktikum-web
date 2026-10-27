<?php

$nim = $_POST['nim'];
$nama_mhs = $_POST['nama_mhs'];
$nilai_akhir = $_POST['nilai_akhir'];
$nilai_huruf = $_POST['nilai_huruf'];
$predikat = $_POST['predikat'];

require 'koneksi.php';

$sql = "INSERT INTO nilaimhs (nim, nama_mhs, nilai_akhir, nilai_huruf, predikat)
VALUES ('$nim', '$nama_mhs', '$nilai_akhir', '$nilai_huruf', '$predikat')";

if (mysqli_query($conn, $sql)) {
  echo "New record created successfully";
} else {
  echo "Error inserting record: " . $sql . "<br>" . mysqli_error($conn);
}

header("Location: baca_data.php");
// mysqli_close($conn);
?>