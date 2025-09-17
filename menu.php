<?php session_start(); ?>
<?php 
    if (!isset($_SESSION['username'])) {
        header('location:index.php');
    }
?>
<?php if (isset($_SESSION['username']) && $_SESSION['username'] == 'kadisdag') : ?>
    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <title>E-Inventarsip | Dinas Perdagangan Provinsi Kalimantan Selatan</title>
    </head>

    <body>
        <!-- Awal Container -->
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="card shadow rounded">
                        <div class="card-body">
                            <h1 class="text-center">Selamat Datang di E-Inventarsip Dinas Perdagangan Provinsi Kalimantan Selatan</h1>
                            <h6 class="text-center">Silahkan Pilih Menu Berikut :</h6>
                            <div class="text-center">
                                <a href="admin.php" class="btn btn-primary text-monospace">Arsip Surat</a>
                                <a href="inventaris.php" class="btn btn-success text-monospace">Inventaris</a>
                                <a href="logout.php" class="btn btn-danger text-monospace">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Container -->
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Pooper.js, then Bootstrap JS -->
        <script src="assets/js/jquery-3.5.1.slim.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php else : ?>
    <script>
        window.location = 'admin.php';
    </script>
<?php endif; ?>