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
  <div class="container bg-white rounded-lg p-5 shadow-lg">
    <div class="">
      <h2>Buku Besar</h2>
      <?php
      if (isset($_GET['selectedAccount']) && !empty($_GET['selectedAccount'])) {
          echo '<h4>' . $_GET['selectedAccount'] . '</h4>';
      }
      ?>
    </div>
    
    <form method="GET">
      <div class="form-group">
        <label for="selectAkun">Pilih Akun:</label>
        <select class="form-control" id="selectAkun" name="selectedAccount">
          <option value="">Pilih Akun</option>
          <?php
          $connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');
          if ($connection->connect_error) {
              die("Connection failed: " . $connection->connect_error);
          }
          
          // Query untuk mendapatkan daftar akun yang tersedia
          $query_akun = "SELECT DISTINCT akun_debet AS akun FROM table_transaksi UNION SELECT DISTINCT akun_kredit AS akun FROM table_transaksi";
          $result_akun = mysqli_query($connection, $query_akun);
          while ($row_akun = mysqli_fetch_assoc($result_akun)) {
              echo "<option value='" . $row_akun['akun'] . "'>" . $row_akun['akun'] . "</option>";
          }
          ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary rounded-pill px-3 shadow-lg">Tampilkan</button>
    </form>
    
    <?php
    if (isset($_GET['selectedAccount']) && !empty($_GET['selectedAccount'])) {
        $selectedAccount = $_GET['selectedAccount'];
        $query_buku_besar = "SELECT tanggal, nama_transaksi, 
                                    CASE WHEN akun_debet = '$selectedAccount' THEN nominal ELSE 0 END AS debet,
                                    CASE WHEN akun_kredit = '$selectedAccount' THEN nominal ELSE 0 END AS kredit
                                    FROM table_transaksi
                                    WHERE akun_debet = '$selectedAccount' OR akun_kredit = '$selectedAccount'";
        $result_buku_besar = mysqli_query($connection, $query_buku_besar);

        echo '<table class="table">
                <thead>
                  <tr>
                    <th scope="col">Tanggal</th>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Debet</th>
                    <th scope="col">Kredit</th>
                    <th scope="col">Saldo</th>
                  </tr>
                </thead>
                <tbody>';

        $saldo = 0;
        while ($row_buku_besar = mysqli_fetch_assoc($result_buku_besar)) {
            $saldo += $row_buku_besar['debet'] - $row_buku_besar['kredit'];
            $saldo_positif = abs($saldo); // Menggunakan nilai absolut dari saldo

            echo "<tr>";
            echo "<td>" . $row_buku_besar['tanggal'] . "</td>";
            echo "<td>" . $row_buku_besar['nama_transaksi'] . "</td>";
            echo "<td>" . $row_buku_besar['debet'] . "</td>";
            echo "<td>" . $row_buku_besar['kredit'] . "</td>";
            echo "<td>" . $saldo_positif . "</td>"; // Menampilkan saldo positif
            echo "</tr>";
        }

        echo '</tbody></table>';
    }
    ?>

    <!-- Kembali button -->
    <div class="text-right mb-2">
      <!-- Kembali button -->
      <a href="index.php" class="btn btn-primary rounded-pill px-3 shadow-lg">Kembali</a>
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

<?php
if (isset($connection)) {
    mysqli_close($connection);
}
?>
