<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>E-Arsip | Laporan Inventaris</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h3 class="text-center">Laporan Inventaris</h3>
                    </div>
                    <div class="card-body">
                        <a href="inventaris.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <a href="cetak_laporan_inventaris.php" class="btn btn-secondary float-right"><i class="bi bi-printer"></i> Cetak</a>
                        <h5>Laporan Stok Barang Habis Pakai</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Departemen</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT b.nama_barang, d.nama_departemen,
                                        COALESCE(
                                            (SELECT SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END)
                                             FROM tbl_stok_barang s WHERE s.id_barang = b.id_barang),
                                             0
                                        ) as stok
                                    FROM tbl_barang b
                                    JOIN tbl_departemen d ON b.id_departemen = d.id_departemen
                                    WHERE b.jenis_barang = 'habis_pakai'
                                    ORDER BY b.nama_barang";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                                        <td><?php echo $row['stok']; ?> unit</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <h5>Laporan Status Barang Tidak Habis Pakai</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Departemen</th>
                                    <th>Status</th>
                                    <th>Pegawai</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT b.nama_barang, d.nama_departemen, s.status, p.nama as nama_pegawai
                                    FROM tbl_barang b
                                    JOIN tbl_departemen d ON b.id_departemen = d.id_departemen
                                    LEFT JOIN tbl_status_barang s ON b.id_barang = s.id_barang
                                    LEFT JOIN tbl_pegawai p ON s.id_pegawai = p.id_pegawai
                                    WHERE b.jenis_barang = 'tidak_habis_pakai'
                                    ORDER BY b.nama_barang";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status'] ?? 'Tersedia'); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_pegawai'] ?? '-'); ?></td>
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