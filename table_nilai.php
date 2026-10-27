<?php
require 'koneksi.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db5d";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS nilaimhs (
nim VARCHAR(20) PRIMARY KEY,
nama_mhs VARCHAR(100) NOT NULL,
nilai_akhir DECIMAL(5,2) NOT NULL,
nilai_huruf VARCHAR(2) NOT NULL,
predikat VARCHAR(20) NOT NULL,
gambar VARCHAR(255) DEFAULT NULL,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
  $kolom_gambar = mysqli_query($conn, "SHOW COLUMNS FROM nilaimhs LIKE 'gambar'");
  if (!$kolom_gambar) {
    die("Error checking table column: " . mysqli_error($conn));
  }

  if (mysqli_num_rows($kolom_gambar) === 0) {
    if (!mysqli_query($conn, "ALTER TABLE nilaimhs ADD gambar VARCHAR(255) DEFAULT NULL")) {
      die("Error adding image column: " . mysqli_error($conn));
    }
  }

  echo "Table <strong>nilaimhs</strong> created successfully";
} else {
  echo "Error creating table: " . mysqli_error($conn);
}

// Close connection
mysqli_close($conn);
?>