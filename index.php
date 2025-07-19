<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akuntansi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
        .jumbotron {
            height: 80vh !important;
            display: flex;
        }

        .ini {
            overflow: hidden;
            margin: auto;
        }
    </style>
</head>
<body>
    
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="#">Akuntansi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active fw-medium" aria-current="page" href="input_transaksi.php">Keuangan</a>
                    </li>
                

                    <li class="nav-item">
                    <a class="nav-link fw-medium" href="daftar_baptis.php">Pendaftaran Baptis</a>
                    </li>

                <li class="nav-item">
                    <a class="nav-link fw-medium" href="daftar_jemaat_baru.php">Pendaftaran Jemaat Baru</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-medium" href="daftar_jemaat_keluar.php">Pendaftaran Jemaat Keluar</a>
                </li>
            
                <li class="nav-item dropdown">
                        <a class="nav-link fw-medium dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="buku_besar.php">Buku Besar</a></li>
                            <li><a class="dropdown-item" href="laporan_neraca.php">Neraca</a></li>
                            <li><a class="dropdown-item" href="laporan_labarugi.php">Laba-rugi</a></li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </nav>
    <!-- navbar -->

    <!-- header -->
    <!-- Bootstrap Static Header -->
    <div style="background-image: url(https://hariannusantara.com/wp-content/uploads/2019/06/gambar-pemandangan-alam-indah.jpg);" class="jumbotron bg-cover">
        <div class="container ini py-5 text-center">
            <h1 class="display-4 font-weight-bold">Kelola Kas Anda bersama kami</h1>
            <p class="font-italic mb-0">Kami yakin data kas anda tidak aman.</p>
        </div>
    </div>

    <!-- header -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>