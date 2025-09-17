<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}

$id_barang = mysqli_escape_string($koneksi, $_GET['id']);
$query = "SELECT * FROM tbl_barang WHERE id_barang = '$id_barang' AND jenis_barang = 'tidak_habis_pakai'";
$result = mysqli_query($koneksi, $query);
$barang = mysqli_fetch_array($result);

if (!$barang) {
    echo "<script>alert('Barang tidak ditemukan atau bukan barang tidak habis pakai!'); window.location='inventaris.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = mysqli_escape_string($koneksi, $_POST['status']);
    $id_pegawai = $status == 'dipinjam' ? mysqli_escape_string($koneksi, $_POST['id_pegawai']) : null;
    $keterangan = mysqli_escape_string($koneksi, $_POST['keterangan']);
    $created_at = date('Y-m-d');

    $query = "INSERT INTO tbl_status_barang (id_barang, status, id_pegawai, keterangan, created_at)
              VALUES ('$id_barang', '$status', " . ($id_pegawai ? "'$id_pegawai'" : "NULL") . ", '$keterangan', '$created_at')";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Status barang berhasil diperbarui!'); window.location='kelola_status.php?id=$id_barang';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status barang!');</script>";
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
    <title>E-Arsip | Kelola Status <?php echo htmlspecialchars($barang['nama_barang']); ?></title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header">
                        <h3 class="text-center">Kelola Status: <?php echo htmlspecialchars($barang['nama_barang']); ?></h3>
                    </div>
                    <div class="card-body">
                        <a href="inventaris.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Kembali ke Dashboard</a>
                        <h5>Ubah Status Barang</h5>
                        <form method="POST">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" id="status" onchange="togglePegawai()" required>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="dipinjam">Dipinjam</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                            <div class="form-group" id="pegawaiField" style="display: none;">
                                <label>Pegawai</label>
                                <select name="id_pegawai" class="form-control">
                                    <?php
                                    $query = "SELECT * FROM tbl_pegawai";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_array($result)) {
                                        echo "<option value='{$row['id_pegawai']}'>" . htmlspecialchars($row['nama']) . " (" . htmlspecialchars($row['nip']) . ")</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Status</button>
                        </form>
                        <hr>
                        <h5>Riwayat Status Barang</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Pegawai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "
                                    SELECT s.created_at, s.status, s.keterangan, p.nama
                                    FROM tbl_status_barang s
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
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['nama'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
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
    <script>
        function togglePegawai() {
            var status = document.getElementById('status').value;
            var pegawaiField = document.getElementById('pegawaiField');
            pegawaiField.style.display = (status === 'dipinjam') ? 'block' : 'none';
        }
    </script>
</body>
</html>