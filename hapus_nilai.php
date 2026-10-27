<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/upload_gambar.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['nim']) || $_GET['nim'] === '') {
  http_response_code(400);
  exit('NIM tidak valid.');
}

$nim = $_GET['nim'];
$imageStmt = mysqli_prepare($conn, 'SELECT gambar FROM nilaimhs WHERE nim = ?');
if (!$imageStmt) {
  http_response_code(500);
  exit('Gagal menyiapkan penghapusan data: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}

mysqli_stmt_bind_param($imageStmt, 's', $nim);
if (!mysqli_stmt_execute($imageStmt)) {
  mysqli_stmt_close($imageStmt);
  http_response_code(500);
  exit('Gagal membaca data foto mahasiswa.');
}

$imagePath = null;
if (!mysqli_stmt_bind_result($imageStmt, $imagePath)) {
  mysqli_stmt_close($imageStmt);
  http_response_code(500);
  exit('Gagal membaca data foto mahasiswa.');
}

$imageFound = mysqli_stmt_fetch($imageStmt);
if ($imageFound === false) {
  mysqli_stmt_close($imageStmt);
  http_response_code(500);
  exit('Gagal membaca data foto mahasiswa.');
}

mysqli_stmt_close($imageStmt);

$stmt = mysqli_prepare($conn, 'DELETE FROM nilaimhs WHERE nim = ?');
if (!$stmt) {
  http_response_code(500);
  exit('Gagal menyiapkan penghapusan data: ' . htmlspecialchars(mysqli_error($conn), ENT_QUOTES, 'UTF-8'));
}

mysqli_stmt_bind_param($stmt, 's', $nim);

if (!mysqli_stmt_execute($stmt)) {
  http_response_code(500);
  exit('Gagal menghapus data: ' . htmlspecialchars(mysqli_stmt_error($stmt), ENT_QUOTES, 'UTF-8'));
}

if (mysqli_stmt_affected_rows($stmt) > 0 && !hapusGambarJikaTidakDipakai($conn, $imageFound ? $imagePath : null)) {
  mysqli_stmt_close($stmt);
  http_response_code(500);
  exit('Data berhasil dihapus, tetapi foto gagal dihapus dari folder gambar.');
}

mysqli_stmt_close($stmt);
header('Location: adminlte.php');
exit;