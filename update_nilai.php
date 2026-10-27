<?php
require_once __DIR__ . '/koneksi.php';
require_once __DIR__ . '/upload_gambar.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode permintaan tidak diizinkan.');
}

$nim = trim($_POST['nim'] ?? '');
$nama_mhs = trim($_POST['nama_mhs'] ?? '');
$nilai_akhir = $_POST['nilai_akhir'] ?? '';

if ($nim === '' || $nama_mhs === '' || $nilai_akhir === '' || !is_numeric($nilai_akhir) || $nilai_akhir < 0 || $nilai_akhir > 100) {
    echo "NIM, nama, dan nilai wajib diisi.";
    exit;
}

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

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
        echo "Gagal mengunggah gambar.";
        exit;
    }

    $target_file = uploadGambar($_FILES['gambar']);
    if ($target_file === false) {
        exit;
    }
}

if (isset($target_file)) {
    $stmt = mysqli_prepare($conn, 'UPDATE nilaimhs SET nama_mhs = ?, nilai_akhir = ?, nilai_huruf = ?, predikat = ?, gambar = ? WHERE nim = ?');
    if (!$stmt) {
        http_response_code(500);
        exit('Gagal menyiapkan perubahan data: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'sdssss', $nama_mhs, $nilai_akhir, $nilai_huruf, $predikat, $target_file, $nim);
} else {
    $stmt = mysqli_prepare($conn, 'UPDATE nilaimhs SET nama_mhs = ?, nilai_akhir = ?, nilai_huruf = ?, predikat = ? WHERE nim = ?');
    if (!$stmt) {
        http_response_code(500);
        exit('Gagal menyiapkan perubahan data: ' . mysqli_error($conn));
    }
    mysqli_stmt_bind_param($stmt, 'sdsss', $nama_mhs, $nilai_akhir, $nilai_huruf, $predikat, $nim);
}

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: adminlte.php");
    exit;
} else {
    http_response_code(500);
    echo "Gagal memperbarui data: " . mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
}
?>