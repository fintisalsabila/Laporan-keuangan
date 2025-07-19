<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Penjurnalan-Menu Jurnal</title>
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
  <script>
    function resetForm() {
      document.getElementById("myForm").reset();
    }

    function refreshPage() {
      window.location.reload();
    }
  </script>
</head>
<body>
  <div class="container-sm">
    <div class="text-center">
      </div>
      <form action="#" method="post" id="myForm" class="bg-primary text-white p-5 rounded-lg shadow-lg">
      <h2>Keuangan</h2>
      <div class="form-group">
        <label for="tanggal" class="bold-label">Tanggal:</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
      </div>
      
      <div class="form-group">
        <label for="nama_transaksi" class="bold-label">Nama Transaksi:</label>
        <input type="text" class="form-control" id="nama_transaksi" name="nama_transaksi" required>
      </div>
      
      <div class="form-group">
        <label for="akun_debet" class="bold-label">Akun Didebet:</label>
        <select class="form-control" id="akun_debet" name="akun_debet" required>
          <?php
          $connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');
          if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }
          
          $query = "SELECT nama_coa FROM table_coa";
          $result = mysqli_query($connection, $query);
          
          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['nama_coa'] . "'>" . $row['nama_coa'] . "</option>";
            }
          } else {
            echo "<option value=''>No data available</option>";
          }
          
          mysqli_close($connection);
          ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="akun_kredit" class="bold-label">Akun Dikredit:</label>
        <select class="form-control" id="akun_kredit" name="akun_kredit" required>
          <?php
          $connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');
          if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
          }
          
          $query = "SELECT nama_coa FROM table_coa";
          $result = mysqli_query($connection, $query);
          
          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['nama_coa'] . "'>" . $row['nama_coa'] . "</option>";
            }
          } else {
            echo "<option value=''>No data available</option>";
          }
          
          mysqli_close($connection);
          ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="nominal" class="bold-label">Nominal:</label>
        <input type="number" class="form-control" id="nominal" name="nominal" required>
      </div>

      <div class="row justify-content-between">
        <div class="col-auto">
          <input type="submit" class="btn btn-success rounded-pill px-3 shadow-lg" value="Simpan">
          <input type="reset" class="btn btn-danger rounded-pill px-3 shadow-lg" value="Batal" onclick="resetForm();">
        </div>
        <div class="col-auto">
          <a href="index.php" class="btn text-white fw-medium  rounded-pill px-3">Kembali</a>
        </div>
      </div>
      
      <!-- PHP code for inserting transaction -->
      <?php
      $connection = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');
      if ($connection->connect_error) {
          die("Connection failed: " . $connection->connect_error);
      }

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $tanggal = $_POST["tanggal"];
          $nama_transaksi = $_POST["nama_transaksi"];
          $akun_debet = $_POST["akun_debet"];
          $akun_kredit = $_POST["akun_kredit"];
          $nominal = $_POST["nominal"];

          $query = "INSERT INTO table_transaksi (tanggal, nama_transaksi, akun_debet, akun_kredit, nominal) VALUES ('$tanggal', '$nama_transaksi', '$akun_debet', '$akun_kredit', '$nominal')";
          if (mysqli_query($connection, $query)) {
              echo "<script>alert('Data berhasil disimpan.');</script>";

              // Ambil jenis dari table_coa untuk akun debet
              $queryDebet = "SELECT jenis FROM table_coa WHERE nama_coa = '$akun_debet'";
              $resultDebet = mysqli_query($connection, $queryDebet);
              $jenisDebet = mysqli_fetch_assoc($resultDebet)['jenis'];

              // Ambil jenis dari table_coa untuk akun kredit
              $queryKredit = "SELECT jenis FROM table_coa WHERE nama_coa = '$akun_kredit'";
              $resultKredit = mysqli_query($connection, $queryKredit);
              $jenisKredit = mysqli_fetch_assoc($resultKredit)['jenis'];

              // Update saldo pada table_coa untuk akun debet berdasarkan jenis
              if ($jenisDebet == 'Asset' || $jenisDebet == 'Beban') {
                $queryUpdateDebet = "UPDATE table_coa SET saldo = saldo + $nominal WHERE nama_coa = '$akun_debet'";
              } else if ($jenisDebet == 'Liabilitas' || $jenisDebet == 'Ekuitas' || $jenisDebet == 'Pendapatan') {
                $queryUpdateDebet = "UPDATE table_coa SET saldo = saldo - $nominal WHERE nama_coa = '$akun_debet'";
              }
              mysqli_query($connection, $queryUpdateDebet);

              // Update saldo pada table_coa untuk akun kredit berdasarkan jenis
              if ($jenisKredit == 'Asset' || $jenisKredit == 'Beban') {
                $queryUpdateKredit = "UPDATE table_coa SET saldo = saldo - $nominal WHERE nama_coa = '$akun_kredit'";
              } else if ($jenisKredit == 'Liabilitas' || $jenisKredit == 'Ekuitas' || $jenisKredit == 'Pendapatan') {
                $queryUpdateKredit = "UPDATE table_coa SET saldo = saldo + $nominal WHERE nama_coa = '$akun_kredit'";
              }

              mysqli_query($connection, $queryUpdateKredit);
            } else {
              echo "<script>alert('Error: " . mysqli_error($connection) . "');</script>";
            }
          }

          mysqli_close($connection);
          ?>
    </form>
  </div>

  <!-- Bootstrap JS and Popper.js CDN links -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
