
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
    <title>E-Inventarsip | Inventaris</title>
    <style>
        .button-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .btn {
            padding: 6px 12px;
            font-size: 16px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: 1px solid #6c757d;
        }
        .btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
        }
        .btn-success {
            background-color: #28a745;
            border: 1px solid #28a745;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-toggle {
            cursor: pointer;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            min-width: 200px;
            z-index: 1000;
        }
        .dropdown-menu.show {
            display: block;
        }
        .dropdown-item {
            display: block;
            padding: 8px 12px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
        }
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h3 class="text-center">Dashboard Inventaris</h3>
                    </div>
                    <div class="card-body">
                        <div class="button-group">
                            <a href="menu.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali ke Menu</a>
                            <a href="tambah_barang.php" class="btn btn-primary"><i class="bi bi-plus"></i> Tambah Barang</a>
                            <a href="permohonan_barang.php" class="btn btn-info"><i class="bi bi-journal-plus"></i> Permohonan Barang</a>
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle" id="dropdownLaporan">
                                    <i class="bi bi-file-earmark-text"></i> Laporan
                                </button>
                                <div class="dropdown-menu" id="dropdownMenu">
                                    <a class="dropdown-item" href="laporan_inventaris.php">Laporan Inventaris</a>
                                    <a class="dropdown-item" href="cetak_barang_stok.php">Laporan Barang & Stok</a>
                                    <a class="dropdown-item" href="cetak_barang_masuk.php">Laporan Barang Masuk</a>
                                    <a class="dropdown-item" href="cetak_permintaan_barang.php">Laporan Permintaan Barang</a>
                                    <a class="dropdown-item" href="cetak_peminjaman_barang.php">Laporan Peminjaman Barang</a>
                                    <a class="dropdown-item" href="cetak_pengembalian_barang.php">Laporan Pengembalian Barang</a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Departemen</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT b.id_barang, b.nama_barang, b.jenis_barang, d.nama_departemen,
                                        COALESCE(
                                            (SELECT SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END)
                                             FROM tbl_stok_barang s WHERE s.id_barang = b.id_barang),
                                             0
                                        ) as stok,
                                        (SELECT s.status FROM tbl_status_barang s 
                                         WHERE s.id_barang = b.id_barang ORDER BY s.created_at DESC LIMIT 1) as status
                                    FROM tbl_barang b
                                    JOIN tbl_departemen d ON b.id_departemen = d.id_departemen
                                    ORDER BY b.id_barang";
                                $result = mysqli_query($koneksi, $query);
                                $no = 1;
                                while ($row = mysqli_fetch_array($result)) {
                                    $stok = ($row['jenis_barang'] == 'habis_pakai') ? $row['stok'] . ' unit' : '-';
                                    $status = ($row['jenis_barang'] == 'tidak_habis_pakai') ? ($row['status'] ?? 'Tersedia') : '-';
                                    $kelola_link = ($row['jenis_barang'] == 'habis_pakai') ? "kelola_stok.php?id={$row['id_barang']}" : "kelola_status.php?id={$row['id_barang']}";
                                    ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                        <td><?php echo $row['jenis_barang'] == 'habis_pakai' ? 'Habis Pakai' : 'Tidak Habis Pakai'; ?></td>
                                        <td><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                                        <td><?php echo htmlspecialchars($stok); ?></td>
                                        <td><?php echo htmlspecialchars($status); ?></td>
                                        <td>
                                            <a href="edit_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> Edit</a>
                                            <a href="hapus_barang.php?id=<?php echo $row['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus barang ini?')"><i class="bi bi-trash"></i> Hapus</a>
                                            <a href="<?php echo $kelola_link; ?>" class="btn btn-info btn-sm"><i class="bi bi-gear"></i> Kelola</a>
                                        </td>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropdownToggle = document.getElementById('dropdownLaporan');
            const dropdownMenu = document.getElementById('dropdownMenu');

            dropdownToggle.addEventListener('click', function () {
                dropdownMenu.classList.toggle('show');
            });

            document.addEventListener('click', function (event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>