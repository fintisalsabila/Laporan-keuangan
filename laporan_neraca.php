<?php
$connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Query untuk menampilkan akun pada kelompok aktiva (Asset)
$query_asset = "SELECT nama_coa, saldo FROM table_coa WHERE jenis = 'Asset'";
$result_asset = mysqli_query($connection, $query_asset);

if (!$result_asset) {
    die("Query failed: " . mysqli_error($connection));
}

// Memasukkan hasil query ke dalam array $aktiva
$aktiva = [];
while ($row = mysqli_fetch_assoc($result_asset)) {
    $aktiva[$row['nama_coa']] = $row['saldo'];
}

// Query untuk menampilkan akun pada kelompok pasiva (Liabilitas, Ekuitas, dan laba sebelum pajak)
$query_pasiva = "SELECT nama_coa, saldo FROM table_coa WHERE jenis = 'Liabilitas' OR jenis = 'Ekuitas' OR nama_coa = 'laba sebelum pajak'";
$result_pasiva = mysqli_query($connection, $query_pasiva);

if (!$result_pasiva) {
    die("Query failed: " . mysqli_error($connection));
}

// Memasukkan hasil query ke dalam array $pasiva
$pasiva = [];
while ($row = mysqli_fetch_assoc($result_pasiva)) {
    $pasiva[$row['nama_coa']] = $row['saldo'];
}

// Menghitung total aktiva dan pasiva
$totalAktiva = array_sum($aktiva);
$totalPasiva = array_sum($pasiva);

// Close connection after use
mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Penjurnalan-Menu Laporan</title>
  <!-- Bootstrap CSS CDN link -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      padding-top: 30px;
    }
    .bold-label {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="text-center">
      <h2>Neraca</h2>
    </div>
    <div class="m-auto flex">
      <div class="col-md-6 mt-5 mx-auto">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">Aktiva</th>
              <th scope="col">Saldo Akhir</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($aktiva as $account => $saldo) {
                if ($saldo > 0) {
                    echo "<tr>";
                    echo "<td>{$account}</td>";
                    echo "<td>" . ($saldo) . "</td>"; 
                    echo "</tr>";
                }
            }
            ?>
            <tr>
              <td><strong>Total Aktiva</strong></td>
              <td><strong><?= ($totalAktiva) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-md-6 mt-5 mx-auto">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th scope="col">Pasiva</th>
              <th scope="col">Saldo Akhir</th>
            </tr>
          </thead>
          <tbody>
            <?php
            foreach ($pasiva as $account => $saldo) {
                if ($saldo > 0) {
                    echo "<tr>";
                    echo "<td>{$account}</td>";
                    echo "<td>" . ($saldo) . "</td>";
                    echo "</tr>";
                }
            }
            ?>
            <tr>
              <td><strong>Total Pasiva</strong></td>
              <td><strong><?= ($totalPasiva) ?></strong></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Kembali button -->
    <div class="text-right mb-2 d-flex flex-col mt-5">
      <!-- Print button -->
      <!-- Kembali button -->
      <a href="index.php" class="btn btn-primary mx-auto">Kembali</a>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js CDN links -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- JavaScript for printing -->
  <script>
    function printReport() {
      window.print();
    }
  </script>
</body>
</html>
