<?php
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

$sql = "SELECT * FROM nilaimhs ";
// Execute the SQL query
$result = mysqli_query($conn, $sql);

// Process the result set
if (mysqli_num_rows($result) > 0) {
    echo "<h2>Data Nilai Mahasiswa</h2>";
    echo "<caption><strong>Nilai Mahasiswa</strong></caption><br></br>";
    echo "<table border='1'>
        <tr>
            <th>NIM</th>
            <th>Nama Lengkap</th>
            <th>Nilai Akhir</th>
            <th>Nilai Huruf</th>
            <th>Predikat</th>
            <th>Tanggal Entry</th>
            <th>Action</th>
        </tr>";
  // Output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>" . $row["nim"]. "</td>
            <td>" . $row["nama_mhs"]. "</td>
            <td>" . $row["nilai_akhir"]. "</td>
            <td>" . $row["nilai_huruf"]. "</td>
            <td>" . $row["predikat"]. "</td>
            <td>" . $row["reg_date"]. "</td>
            <td> <a href='form_update_nilai.php?nim=" . $row["nim"] . "'><button>Edit</button></a>
                <a href='hapus_nilai.php?nim=" . $row["nim"] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\"><button>Delete</button></a>
            </td>
        </tr>";
  }
  echo "</table>";
} else {
  echo "0 results";
}

echo "<br><a href='form_nilai.html'><button>Tambah Data</button></a><br>";

mysqli_close($conn);
?>