<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Formulir Daftar Jemaat Baru</title>
  <!-- Bootstrap CSS CDN -->
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
  </script>
</head>
<body>
  <div class="container-sm">
    <form action="#" method="post" enctype="multipart/form-data" id="myForm" class="bg-primary text-white p-5 rounded-lg shadow-lg">
      <h2 class="text-white">Formulir Daftar Jemaat Baru</h2>

      <div class="form-group">
        <label for="tanggal" class="bold-label">Tanggal:</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
      </div>

      <div class="form-group">
        <label for="nama_jemaat" class="bold-label">Nama Jemaat:</label>
        <input type="text" class="form-control" id="nama_jemaat" name="nama_jemaat" required>
      </div>

      <div class="form-group">
        <label for="asal_gereja" class="bold-label">Asal Gereja:</label>
        <input type="text" class="form-control" id="asal_gereja" name="asal_gereja" required>
      </div>

      <div class="form-group">
        <label for="ttl" class="bold-label">Tempat Tanggal Lahir:</label>
        <input type="text" class="form-control" id="ttl" name="ttl" placeholder="Contoh: Bandung, 01 Januari 2000" required>
      </div>

      <div class="form-group">
        <label for="dokumen" class="bold-label">Upload Dokumen:</label>
        <input type="file" class="form-control-file" id="dokumen" name="dokumen" accept=".pdf,.jpg,.jpeg,.png" required>
      </div>

      <div class="row justify-content-between">
        <div class="col-auto">
          <input type="submit" class="btn btn-success rounded-pill px-3 shadow-lg" value="Simpan">
          <input type="reset" class="btn btn-danger rounded-pill px-3 shadow-lg" value="Batal" onclick="resetForm();">
        </div>
        <div class="col-auto">
          <a href="index.php" class="btn text-white fw-medium rounded-pill px-3">Kembali</a>
        </div>
      </div>

      <?php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $tanggal = $_POST["tanggal"];
          $nama_jemaat = $_POST["nama_jemaat"];
          $asal_gereja = $_POST["asal_gereja"];
          $ttl = $_POST["ttl"];
          $upload_folder = "uploads/";

          // Handle file upload
          $file_name = basename($_FILES["dokumen"]["name"]);
          $target_path = $upload_folder . $file_name;
          $file_type = strtolower(pathinfo($target_path, PATHINFO_EXTENSION));

          if (move_uploaded_file($_FILES["dokumen"]["tmp_name"], $target_path)) {
              $conn = mysqli_connect('localhost', 'root', '', 'database_gereja');
              if ($conn->connect_error) {
                  die("Connection failed: " . $conn->connect_error);
              }

              $sql = "INSERT INTO daftar_baptis (tanggal, nama_jemaat, asal_gereja, ttl, dokumen_path)
                      VALUES ('$tanggal', '$nama_jemaat', '$asal_gereja', '$ttl', '$target_path')";

              if (mysqli_query($conn, $sql)) {
                  echo "<script>alert('Data berhasil disimpan.');</script>";
              } else {
                  echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
              }

              mysqli_close($conn);
          } else {
              echo "<script>alert('Gagal mengunggah dokumen.');</script>";
          }
      }
      ?>
    </form>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
