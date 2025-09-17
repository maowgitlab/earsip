<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_barang = mysqli_escape_string($koneksi, $_POST['nama_barang']);
    $jenis_barang = mysqli_escape_string($koneksi, $_POST['jenis_barang']);
    $deskripsi = mysqli_escape_string($koneksi, $_POST['deskripsi']);
    $id_departemen = mysqli_escape_string($koneksi, $_POST['id_departemen']);
    $created_at = date('Y-m-d');

    $query = "INSERT INTO tbl_barang (nama_barang, jenis_barang, deskripsi, id_departemen, created_at) 
              VALUES ('$nama_barang', '$jenis_barang', '$deskripsi', '$id_departemen', '$created_at')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Barang berhasil ditambahkan!'); window.location='inventaris.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan barang!');</script>";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>E-Arsip | Tambah Barang</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h3 class="text-center">Tambah Barang</h3>
                    </div>
                    <div class="card-body">
                        <a href="inventaris.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <form method="POST">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" name="nama_barang" class="form-control" required 
                                oninvalid="this.setCustomValidity('Data tidak boleh kosong!')" 
                                oninput="this.setCustomValidity('')">
                            </div>
                            <div class="form-group">
                                <label>Jenis Barang</label>
                                <select name="jenis_barang" class="form-control" required>
                                    <option value="habis_pakai">Habis Pakai</option>
                                    <option value="tidak_habis_pakai">Tidak Habis Pakai</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                               <textarea name="deskripsi" class="form-control" required
                                oninvalid="this.setCustomValidity('Data tidak boleh kosong!')"
                                oninput="this.setCustomValidity('')"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Departemen</label>
                                <select name="id_departemen" class="form-control" required>
                                    <?php
                                    $query = "SELECT * FROM tbl_departemen";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='{$row['id_departemen']}'>" . htmlspecialchars($row['nama_departemen']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>