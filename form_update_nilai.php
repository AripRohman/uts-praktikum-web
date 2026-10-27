<?php
require_once __DIR__ . '/koneksi.php';

$nim = $_GET['nim'];
$stmt = mysqli_prepare($conn, 'SELECT * FROM nilaimhs WHERE nim = ?');
mysqli_stmt_bind_param($stmt, 's', $nim);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo 'Data tidak ditemukan';
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Update</title>
</head>

<body>
    <form action="update_nilai.php" method="POST" enctype="multipart/form-data">
        <h1>FORM UPDATE DATA</h1>
        <br><label for="nim">NIM:</label><br>
        <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($data['nim'], ENT_QUOTES, 'UTF-8'); ?>" readonly><br>

        <label for="nama">Nama Mahasiswa:</label><br>
        <input type="text" id="nama" name="nama_mhs" value="<?php echo htmlspecialchars($data['nama_mhs'], ENT_QUOTES, 'UTF-8'); ?>" required placeholder="Masukkan Nama"><br>

        <label for="nilai">Nilai Angka (0-100):</label><br>
        <input type="number" id="nilai" name="nilai_akhir" value="<?php echo $data['nilai_akhir']; ?>" min="0" max="100" step="0.01" required placeholder="Masukkan Nilai"><br><br>

        <?php if (!empty($data['gambar'])): ?>
            <img src="<?php echo htmlspecialchars($data['gambar'], ENT_QUOTES, 'UTF-8'); ?>" alt="Foto mahasiswa" width="80"><br>
        <?php endif; ?>
        <label for="gambar">Ganti foto mahasiswa (opsional):</label><br>
        <input type="file" id="gambar" name="gambar" accept="image/jpeg,image/png,image/gif"><br><br>

        <input type="submit" value="Update">
    </form>

</body>
</html>