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
      background: #eaeaea;
    }
    .bold-label {
      font-weight: bold;
    }
  </style>
</head>
<body>
  <?php
  $connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');

  if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
  }

  function getTotal($connection, $jenis) {
    $query = "SELECT nama_coa, SUM(saldo) AS total FROM table_coa WHERE jenis = ? AND saldo > 0 GROUP BY nama_coa";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "s", $jenis);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $total = 0;

    if ($jenis === 'Pendapatan') {
      echo "<tr><td colspan='2' class='bold-label'>$jenis</td><td></td></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['nama_coa'] . "</td><td>" . $row['total'] . "</td><td></td></tr>";
        $total += $row['total'];
      }
    } else if ($jenis === 'Beban') {
      echo "<tr><td colspan='2' class='bold-label'>$jenis</td><td></td></tr>";
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['nama_coa'] . "</td><td></td><td>" . $row['total'] . "</td></tr>";
        $total += $row['total'];
      }
    }

    return $total;
  }
  ?>
  
  <div class="container bg-white shadow-lg rounded-lg p-5">
    <div class="text-center">
      <h2>Laporan Laba Rugi</h2>
    </div>
    <table class="table table-striped table-hover mt-5">
      <thead>
        <tr>
          <th>Keterangan</th>
          <th>Debet</th>
          <th>Kredit</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total_pemasukan = getTotal($connection, 'Pendapatan');
        $total_pengeluaran = getTotal($connection, 'Beban');
        
        $net_income_before_tax = $total_pemasukan - $total_pengeluaran;

        //Menambahkan pernyataan SQL untuk melakukan update pada tabel table_coa
        $update_query = "UPDATE table_coa SET saldo = $net_income_before_tax WHERE nama_coa = 'Laba Sebelum Pajak'";
        mysqli_query($connection, $update_query);

        echo "<tr><td><b>Total Pendapatan</b></td><td>$total_pemasukan</td><td></td></tr>";
        echo "<tr><td><b>Total Beban</b></td><td></td><td>$total_pengeluaran</td></tr>";
        echo "<tr><td><b>Laba/Rugi Sebelum Pajak</b></td><td></td><td><b>" . ($net_income_before_tax) . "</b></td></tr>";
        ?>
      </tbody>
    </table>
    
    <div class="text-right mb-2">
      <!-- Print button -->
      <button class="btn btn-success px-3 rounded-pill shadow-lg" onclick="printReport()">Cetak</button>
      <!-- Kembali button -->
      <a href="index.php" class="btn btn-primary px-3 rounded-pill shadow-lg">Kembali</a>
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

  <?php
  mysqli_close($connection);
  ?>
</body>
</html>
