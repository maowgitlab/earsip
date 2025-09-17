<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'kadisdag') {
    header('location:index.php');
    exit();
}

$id_permintaan = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : null;

if (!$id_permintaan) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Permohonan tidak ditemukan.'];
    header('Location: permohonan_barang.php');
    exit();
}

$permintaan_query = "
    SELECT p.*, b.nama_barang, b.jenis_barang, d.nama_departemen
    FROM tbl_permintaan_barang p
    JOIN tbl_barang b ON p.id_barang = b.id_barang
    JOIN tbl_departemen d ON p.id_departemen = d.id_departemen
    WHERE p.id_permintaan = '$id_permintaan'";
$permintaan_result = mysqli_query($koneksi, $permintaan_query);
$permintaan = mysqli_fetch_array($permintaan_result);

if (!$permintaan) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Permohonan tidak ditemukan.'];
    header('Location: permohonan_barang.php');
    exit();
}

$penyerahan_query = mysqli_query($koneksi, "SELECT * FROM tbl_penyerahan_barang WHERE id_permintaan = '$id_permintaan'");
$penyerahan = mysqli_fetch_array($penyerahan_query);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$penyerahan) {
    $tanggal_penyerahan = !empty($_POST['tanggal_penyerahan']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_penyerahan']) : date('Y-m-d');
    $diserahkan_oleh = mysqli_real_escape_string($koneksi, trim($_POST['diserahkan_oleh']));
    $diterima_oleh = mysqli_real_escape_string($koneksi, trim($_POST['diterima_oleh']));
    $keterangan = mysqli_real_escape_string($koneksi, trim($_POST['keterangan']));

    if (empty($diserahkan_oleh) || empty($diterima_oleh)) {
        $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Nama petugas penyerahan dan penerima wajib diisi.'];
        header('Location: penyerahan_barang.php?id=' . $id_permintaan);
        exit();
    }

    $insert_query = "INSERT INTO tbl_penyerahan_barang (id_permintaan, tanggal_penyerahan, diserahkan_oleh, diterima_oleh, keterangan)
                     VALUES ('$id_permintaan', '$tanggal_penyerahan', '$diserahkan_oleh', '$diterima_oleh', '$keterangan')";

    if (mysqli_query($koneksi, $insert_query)) {
        mysqli_query($koneksi, "UPDATE tbl_permintaan_barang SET status = 'selesai' WHERE id_permintaan = '$id_permintaan'");
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Penyerahan barang berhasil disimpan.'];
        header('Location: permohonan_barang.php');
        exit();
    }

    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan data penyerahan.'];
    header('Location: penyerahan_barang.php?id=' . $id_permintaan);
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
    <title>E-Inventarsip | Penyerahan Barang</title>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Penyerahan Barang</h3>
                        <a href="permohonan_barang.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                    </div>
                    <div class="card-body">
                        <h5>Detail Permohonan</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 35%;">Kode Permohonan</th>
                                <td><?php echo htmlspecialchars($permintaan['kode_permintaan']); ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Permohonan</th>
                                <td><?php echo htmlspecialchars($permintaan['tanggal_permohonan']); ?></td>
                            </tr>
                            <tr>
                                <th>Barang</th>
                                <td><?php echo htmlspecialchars($permintaan['nama_barang']); ?></td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td><?php echo htmlspecialchars($permintaan['jumlah']); ?></td>
                            </tr>
                            <tr>
                                <th>Departemen</th>
                                <td><?php echo htmlspecialchars($permintaan['nama_departemen']); ?></td>
                            </tr>
                            <tr>
                                <th>Pemohon</th>
                                <td><?php echo htmlspecialchars($permintaan['pemohon']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo htmlspecialchars(ucfirst($permintaan['status'])); ?></td>
                            </tr>
                        </table>

                        <?php if ($penyerahan) : ?>
                            <div class="alert alert-info">Penyerahan telah dilakukan pada tanggal <?php echo htmlspecialchars($penyerahan['tanggal_penyerahan']); ?>.</div>
                            <a href="cetak_tanda_terima.php?id=<?php echo $penyerahan['id_penyerahan']; ?>" target="_blank" class="btn btn-primary"><i class="bi bi-printer"></i> Cetak Tanda Terima</a>
                        <?php elseif ($permintaan['status'] === 'disetujui') : ?>
                            <hr>
                            <h5>Form Penyerahan</h5>
                            <form method="POST" class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Penyerahan</label>
                                    <input type="date" name="tanggal_penyerahan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Diserahkan Oleh</label>
                                    <input type="text" name="diserahkan_oleh" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Diterima Oleh</label>
                                    <input type="text" name="diterima_oleh" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan keterangan jika diperlukan"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="bi bi-check2-circle"></i> Simpan Penyerahan</button>
                                </div>
                            </form>
                        <?php else : ?>
                            <div class="alert alert-warning">Permohonan belum disetujui sehingga belum dapat diproses penyerahan.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
