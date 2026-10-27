<?php
require_once __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['nim']) || $_GET['nim'] === '') {
  http_response_code(400);
  exit('NIM tidak valid.');
}

$nim = $_GET['nim'];
$stmt = mysqli_prepare($conn, 'DELETE FROM nilaimhs WHERE nim = ?');
mysqli_stmt_bind_param($stmt, 's', $nim);

if (!mysqli_stmt_execute($stmt)) {
  http_response_code(500);
  exit('Gagal menghapus data: ' . htmlspecialchars(mysqli_stmt_error($stmt), ENT_QUOTES, 'UTF-8'));
}

header('Location: adminlte.php');
exit;

// mysqli_close($conn);
?>