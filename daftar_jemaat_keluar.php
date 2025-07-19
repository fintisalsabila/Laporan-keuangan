<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Form Jemaat Keluar</title>
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
    <form action="daftar_jemaat_keluar.php" method="POST" enctype="multipart/form-data" id="myForm" class="bg-primary text-white p-5 rounded-lg shadow-lg">
      <h2>Form Jemaat Keluar</h2>

      <div class="form-group">
        <label for="tanggal" class="bold-label">Tanggal:</label>
        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
      </div>

      <div class="form-group">
        <label for="nama_jemaat" class="bold-label">Nama Jemaat:</label>
        <input type="text" class="form-control" id="nama_jemaat" name="nama_jemaat" required>
      </div>

      <div class="form-group">
        <label for="tujuan" class="bold-label">Tujuan Gereja:</label>
        <input type="text" class="form-control" id="tujuan" name="tujuan" required>
      </div>

      <div class="form-group">
        <label for="dokumen" class="bold-label">Upload Dokumen (PDF/JPG/PNG):</label>
        <input type="file" class="form-control-file bg-white text-dark" id="dokumen" name="dokumen[]" accept=".pdf,.jpg,.jpeg,.png" multiple required>
      </div>

      <div class="row justify-content-between">
        <div class="col-auto">
          <input type="submit" class="btn btn-success rounded-pill px-3 shadow-lg" value="Simpan">
          <input type="reset" class="btn btn-danger rounded-pill px-3 shadow-lg" value="Batal" onclick="resetForm();">
        </div>
        <div class="col-auto">
          <a href="index.php" class="btn btn-warning text-white fw-medium rounded-pill px-3">Kembali</a>
        </div>
      </div>

      <?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn = mysqli_connect('localhost', 'root', '', 'aplikasiakuntansi');
    if (!$conn) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    // Ambil input
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $nama_jemaat = mysqli_real_escape_string($conn, $_POST['nama_jemaat']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);

    $uploadDir = 'uploads/';
    $uploadedFiles = [];

    // Pastikan direktori ada
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Proses tiap file
    foreach ($_FILES['dokumen']['tmp_name'] as $index => $tmpName) {
        $originalName = basename($_FILES['dokumen']['name'][$index]);
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];

        if (in_array($ext, $allowedTypes)) {
            $uniqueName = time() . "_" . rand(1000, 9999) . "_" . $originalName;
            $filePath = $uploadDir . $uniqueName;

            if (move_uploaded_file($tmpName, $filePath)) {
                $uploadedFiles[] = mysqli_real_escape_string($conn, $filePath);
            }
        }
    }

    // Gabungkan semua path jadi satu string
    $dokumenPaths = implode(",", $uploadedFiles);

    // Simpan ke database
    $sql = "INSERT INTO daftar_jemaat_keluar (tanggal, nama_jemaat, tujuan_gereja, dokumen)
            VALUES ('$tanggal', '$nama_jemaat', '$tujuan', '$dokumenPaths')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data berhasil disimpan.');</script>";
    } else {
        echo "<script>alert('Gagal menyimpan: " . mysqli_error($conn) . "');</script>";
    }

    mysqli_close($conn);
}
?>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
