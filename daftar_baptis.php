<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pendaftaran Baptis</title>
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
      document.getElementById("baptisForm").reset();
    }
  </script>
</head>
<body>
  <div class="container-sm">
    <form action="#" method="post" enctype="multipart/form-data" id="baptisForm" class="bg-primary text-white p-5 rounded-lg shadow-lg">
      <h2 class="mb-4">Formulir Pendaftaran Baptis</h2>

      <div class="form-group">
        <label for="nama_ortu" class="bold-label">Nama Orang Tua:</label>
        <input type="text" class="form-control" id="nama_ortu" name="nama_ortu" required>
      </div>

      <div class="form-group">
        <label for="nama_anak" class="bold-label">Nama Anak:</label>
        <input type="text" class="form-control" id="nama_anak" name="nama_anak" required>
      </div>

      <div class="form-group">
        <label for="ttl" class="bold-label">Tempat, Tanggal Lahir:</label>
        <input type="text" class="form-control" id="ttl" name="ttl" placeholder="Contoh: Jakarta, 12 Januari 2020" required>
      </div>

      <div class="form-group">
        <label for="dokumen" class="bold-label">Upload Dokumen:</label>
        <input type="file" class="form-control-file bg-white p-2 rounded" id="dokumen" name="dokumen[]" multiple required>
      </div>

      <div class="row justify-content-between mt-4">
        <div class="col-auto">
          <input type="submit" class="btn btn-success rounded-pill px-4 shadow-lg" value="Kirim">
          <input type="reset" class="btn btn-danger rounded-pill px-4 shadow-lg" value="Batal" onclick="resetForm();">
        </div>
        <div class="col-auto">
          <a href="index.php" class="btn btn-warning text-white fw-medium rounded-pill px-3">Kembali</a>
        </div>
      </div>

      <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = mysqli_connect("localhost", "root", "", "aplikasiakuntansi");
  if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
  }

  $nama_ortu = mysqli_real_escape_string($conn, $_POST["nama_ortu"]);
  $nama_anak = mysqli_real_escape_string($conn, $_POST["nama_anak"]);
  $ttl = mysqli_real_escape_string($conn, $_POST["ttl"]);
  $tanggal = date('Y-m-d');

  $upload_folder = "uploads/";
  if (!file_exists($upload_folder)) {
    mkdir($upload_folder, 0777, true);
  }

  $dokumen_paths = [];
  foreach ($_FILES['dokumen']['tmp_name'] as $key => $tmp_name) {
    $file_name = basename($_FILES['dokumen']['name'][$key]);
    $file_tmp = $_FILES['dokumen']['tmp_name'][$key];
    $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
    if (in_array($file_type, $allowed_types)) {
      $unique_name = time() . "_" . rand(1000, 9999) . "_" . $file_name;
      $destination = $upload_folder . $unique_name;

      if (move_uploaded_file($file_tmp, $destination)) {
        $dokumen_paths[] = mysqli_real_escape_string($conn, $destination);
      }
    }
  }

  $dokumen_path_str = implode(",", $dokumen_paths);

  $sql = "INSERT INTO daftar_baptis (tanggal, nama_ortu, nama_anak, ttl, dokumen_path) 
          VALUES ('$tanggal', '$nama_ortu', '$nama_anak', '$ttl', '$dokumen_path_str')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('Pendaftaran berhasil disimpan.');</script>";
  } else {
    echo "<script>alert('Gagal menyimpan: " . mysqli_error($conn) . "');</script>";
  }

  mysqli_close($conn);
}
?>

    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
