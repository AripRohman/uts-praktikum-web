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
?>
