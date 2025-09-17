<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}

$id_barang = mysqli_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM tbl_barang WHERE id_barang = '$id_barang' AND jenis_barang = 'habis_pakai'";
$result = mysqli_query($koneksi, $query);
$barang = mysqli_fetch_array($result);

if (!$barang) {
    echo "<script>alert('Barang tidak ditemukan atau bukan barang habis pakai!'); window.location='inventaris.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah = (int)mysqli_escape_string($koneksi, $_POST['jumlah']);
    $tipe_transaksi = mysqli_escape_string($koneksi, $_POST['tipe_transaksi']);
    $keterangan = mysqli_escape_string($koneksi, $_POST['keterangan']);
    $id_pegawai = mysqli_escape_string($koneksi, $_POST['id_pegawai']);
    $created_at = date('Y-m-d');

    // Validasi stok tidak negatif untuk transaksi keluar
    if ($tipe_transaksi == 'keluar') {
        $stok_query = "SELECT COALESCE(SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END), 0) as stok
                       FROM tbl_stok_barang s WHERE s.id_barang = '$id_barang'";
        $stok_result = mysqli_query($koneksi, $stok_query);
        $stok = mysqli_fetch_array($stok_result)['stok'];
        if ($jumlah > $stok) {
            echo "<script>alert('Jumlah keluar melebihi stok saat ini!'); window.location='kelola_stok.php?id=$id_barang';</script>";
            exit();
        }
    }

    $query = "INSERT INTO tbl_stok_barang (id_barang, jumlah, tipe_transaksi, keterangan, id_pegawai, created_at)
              VALUES ('$id_barang', '$jumlah', '$tipe_transaksi', '$keterangan', '$id_pegawai', '$created_at')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Transaksi stok berhasil disimpan!'); window.location='kelola_stok.php?id=$id_barang';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan transaksi stok!');</script>";
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
    <title>E-Arsip | Kelola Stok <?php echo htmlspecialchars($barang['nama_barang']); ?></title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h3 class="text-center">Kelola Stok: <?php echo htmlspecialchars($barang['nama_barang']); ?></h3>
                    </div>
                    <div class="card-body">
                        <a href="inventaris.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
                        <h5>Tambah Transaksi Stok</h5>
                        <form method="POST">
                            <div class="form-group">
                                <label>Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" min="1" required
                                 oninvalid="this.setCustomValidity('Data tidak boleh kosong!')" 
                                 oninput="this.setCustomValidity('')">
                            </div>
                            <div class="form-group">
                                <label>Tipe Transaksi</label>
                                <select name="tipe_transaksi" class="form-control" required>
                                    <option value="masuk">Masuk (Tambah Stok)</option>
                                    <option value="keluar">Keluar (Kurangi Stok)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Pegawai</label>
                                <select name="id_pegawai" class="form-control" required>
                                    <?php
                                    $query = "SELECT * FROM tbl_pegawai";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='{$row['id_pegawai']}'>" . htmlspecialchars($row['nama']) . " (" . htmlspecialchars($row['nip']) . ")</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Transaksi</button>
                        </form>
                        <hr>
                        <h5>Riwayat Transaksi Stok</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah</th>
                                    <th>Tipe</th>
                                    <th>Keterangan</th>
                                    <th>Pegawai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT s.created_at, s.jumlah, s.tipe_transaksi, s.keterangan, p.nama
                                    FROM tbl_stok_barang s
                                    LEFT JOIN tbl_pegawai p ON s.id_pegawai = p.id_pegawai
                                    WHERE s.id_barang = '$id_barang'
                                    ORDER BY s.created_at DESC";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td><?php echo $row['jumlah']; ?> unit</td>
                                        <td><?php echo $row['tipe_transaksi'] == 'masuk' ? 'Masuk' : 'Keluar'; ?></td>
                                        <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</body>
</html>