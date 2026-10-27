<?php
function uploadGambar($gambar)
{
    if (!isset($gambar['tmp_name']) || $gambar['tmp_name'] === '') {
        echo "Pilih gambar terlebih dahulu.";
        return false;
    }

    $imageInfo = getimagesize($gambar['tmp_name']);
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if ($imageInfo === false || !in_array($imageInfo['mime'], $allowedMimeTypes, true)) {
        echo "File yang diunggah bukan gambar JPEG, PNG, atau GIF yang valid.";
        return false;
    }

    if ($gambar['size'] > 1000000) {
        echo "Ukuran gambar melebihi 1MB.";
        return false;
    }

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
    ];
    $fileName = bin2hex(random_bytes(16)) . '.' . $extensions[$imageInfo['mime']];
    $targetPath = __DIR__ . DIRECTORY_SEPARATOR . 'gambar' . DIRECTORY_SEPARATOR . $fileName;

    if (!move_uploaded_file($gambar['tmp_name'], $targetPath)) {
        echo "Gagal menyimpan gambar yang diunggah.";
        return false;
    }

    return 'gambar/' . $fileName;
}

function hapusGambarJikaTidakDipakai(mysqli $conn, ?string $imagePath): bool
{
    if ($imagePath === null || !preg_match('/\Agambar\/[a-f0-9]{32}\.(?:jpg|png|gif)\z/i', $imagePath)) {
        return true;
    }

    $stmt = mysqli_prepare($conn, 'SELECT 1 FROM nilaimhs WHERE gambar = ? LIMIT 1');
    if (!$stmt) {
        return false;
    }

    mysqli_stmt_bind_param($stmt, 's', $imagePath);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        return false;
    }

    mysqli_stmt_store_result($stmt);
    $isUsed = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    if ($isUsed) {
        return true;
    }

    $filePath = __DIR__ . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
    return !is_file($filePath) || unlink($filePath);
}
?>
