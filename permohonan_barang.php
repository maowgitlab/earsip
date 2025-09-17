<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username'])) {
    header('location:index.php');
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type']) && $_POST['form_type'] === 'permohonan') {
        $id_barang = mysqli_real_escape_string($koneksi, $_POST['id_barang']);
        $jumlah = (int)$_POST['jumlah'];
        $id_departemen = mysqli_real_escape_string($koneksi, $_POST['id_departemen']);
        $pemohon = mysqli_real_escape_string($koneksi, trim($_POST['pemohon']));
        $keterangan = mysqli_real_escape_string($koneksi, trim($_POST['keterangan']));
        $tanggal_permohonan = !empty($_POST['tanggal_permohonan']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_permohonan']) : date('Y-m-d');

        if ($jumlah <= 0 || empty($pemohon)) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Jumlah dan nama pemohon wajib diisi dengan benar.'];
            header('Location: permohonan_barang.php');
            exit();
        }

        $barang_query = mysqli_query($koneksi, "SELECT id_barang FROM tbl_barang WHERE id_barang = '$id_barang'");
        $departemen_query = mysqli_query($koneksi, "SELECT id_departemen FROM tbl_departemen WHERE id_departemen = '$id_departemen'");

        if (!$barang_query || mysqli_num_rows($barang_query) === 0 || !$departemen_query || mysqli_num_rows($departemen_query) === 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Barang atau departemen tidak ditemukan.'];
            header('Location: permohonan_barang.php');
            exit();
        }

        $max_query = mysqli_query($koneksi, "SELECT MAX(id_permintaan) AS max_id FROM tbl_permintaan_barang");
        $max_data = mysqli_fetch_array($max_query);
        $next_id = (int)($max_data['max_id'] ?? 0) + 1;
        $kode_permintaan = 'PB-' . date('Ymd', strtotime($tanggal_permohonan)) . '-' . str_pad((string)$next_id, 4, '0', STR_PAD_LEFT);

        $insert_query = "INSERT INTO tbl_permintaan_barang (kode_permintaan, id_barang, jumlah, id_departemen, pemohon, keterangan, status, tanggal_permohonan)
                         VALUES ('$kode_permintaan', '$id_barang', '$jumlah', '$id_departemen', '$pemohon', '$keterangan', 'menunggu', '$tanggal_permohonan')";

        if (mysqli_query($koneksi, $insert_query)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Permohonan barang berhasil ditambahkan.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Gagal menyimpan permohonan barang.'];
        }

        header('Location: permohonan_barang.php');
        exit();
    }

    if (isset($_POST['form_type']) && $_POST['form_type'] === 'persetujuan') {
        if ($username !== 'kadisdag') {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Anda tidak memiliki akses untuk melakukan persetujuan.'];
            header('Location: permohonan_barang.php');
            exit();
        }

        $id_permintaan = mysqli_real_escape_string($koneksi, $_POST['id_permintaan']);
        $action = $_POST['action'] ?? '';
        $catatan = mysqli_real_escape_string($koneksi, trim($_POST['catatan'] ?? ''));

        $status_map = [
            'approve' => 'disetujui',
            'reject' => 'ditolak',
        ];

        if (!isset($status_map[$action])) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Aksi persetujuan tidak dikenal.'];
            header('Location: permohonan_barang.php');
            exit();
        }

        $status_baru = $status_map[$action];
        $tanggal_persetujuan = date('Y-m-d');

        $update_query = "UPDATE tbl_permintaan_barang
                          SET status = '$status_baru', catatan_pimpinan = '$catatan', tanggal_persetujuan = '$tanggal_persetujuan', pimpinan = '$username'
                          WHERE id_permintaan = '$id_permintaan'";

        if (mysqli_query($koneksi, $update_query)) {
            $message = $status_baru === 'disetujui' ? 'Permohonan barang telah disetujui.' : 'Permohonan barang telah ditolak.';
            $_SESSION['flash'] = ['type' => 'success', 'message' => $message];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Terjadi kesalahan saat menyimpan persetujuan.'];
        }

        header('Location: permohonan_barang.php');
        exit();
    }
}

$permohonan_query = "
    SELECT p.*, b.nama_barang, b.jenis_barang, d.nama_departemen, pen.id_penyerahan, pen.tanggal_penyerahan, pen.diserahkan_oleh, pen.diterima_oleh
    FROM tbl_permintaan_barang p
    JOIN tbl_barang b ON p.id_barang = b.id_barang
    JOIN tbl_departemen d ON p.id_departemen = d.id_departemen
    LEFT JOIN tbl_penyerahan_barang pen ON pen.id_permintaan = p.id_permintaan
    ORDER BY p.tanggal_permohonan DESC, p.id_permintaan DESC";
$permohonan_result = mysqli_query($koneksi, $permohonan_query);

$barang_options = mysqli_query($koneksi, "SELECT id_barang, nama_barang FROM tbl_barang ORDER BY nama_barang");
$departemen_options = mysqli_query($koneksi, "SELECT id_departemen, nama_departemen FROM tbl_departemen ORDER BY nama_departemen");

function getStatusBadge(string $status): string
{
    switch ($status) {
        case 'disetujui':
            return '<span class="badge bg-success">Disetujui</span>';
        case 'ditolak':
            return '<span class="badge bg-danger">Ditolak</span>';
        case 'selesai':
            return '<span class="badge bg-primary">Selesai</span>';
        default:
            return '<span class="badge bg-warning text-dark">Menunggu</span>';
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
    <title>E-Inventarsip | Permohonan Barang</title>
    <style>
        .table-responsive { max-height: 500px; overflow-y: auto; }
        .form-inline-actions { display: flex; gap: 8px; align-items: center; }
        .form-inline-actions input[type="text"] { max-width: 220px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-11 mx-auto">
                <div class="card shadow rounded">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Permohonan Barang</h3>
                        <a href="inventaris.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($_SESSION['flash'])) : ?>
                            <div class="alert alert-<?php echo $_SESSION['flash']['type']; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($_SESSION['flash']['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['flash']); ?>
                        <?php endif; ?>

                        <h5 class="mb-3">Ajukan Permohonan Barang</h5>
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="form_type" value="permohonan">
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Permohonan</label>
                                <input type="date" name="tanggal_permohonan" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Barang</label>
                                <select name="id_barang" class="form-control" required>
                                    <option value="">-- Pilih Barang --</option>
                                    <?php while ($barang = mysqli_fetch_array($barang_options)) : ?>
                                        <option value="<?php echo $barang['id_barang']; ?>"><?php echo htmlspecialchars($barang['nama_barang']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Departemen Pemohon</label>
                                <select name="id_departemen" class="form-control" required>
                                    <option value="">-- Pilih Departemen --</option>
                                    <?php while ($departemen = mysqli_fetch_array($departemen_options)) : ?>
                                        <option value="<?php echo $departemen['id_departemen']; ?>"><?php echo htmlspecialchars($departemen['nama_departemen']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nama Pemohon</label>
                                <input type="text" name="pemohon" class="form-control" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Tuliskan kebutuhan atau catatan tambahan"></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Ajukan Permohonan</button>
                            </div>
                        </form>
                        <hr>
                        <h5 class="mb-3">Daftar Permohonan</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Permohonan</th>
                                        <th>Tanggal</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Departemen</th>
                                        <th>Pemohon</th>
                                        <th>Status</th>
                                        <th>Catatan Pimpinan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($permohonan_result)) :
                                        ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($row['kode_permintaan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['tanggal_permohonan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                                            <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                                            <td><?php echo htmlspecialchars($row['pemohon']); ?></td>
                                            <td><?php echo getStatusBadge($row['status']); ?></td>
                                            <td><?php echo htmlspecialchars($row['catatan_pimpinan'] ?? '-'); ?></td>
                                            <td>
                                                <?php if ($row['status'] === 'menunggu' && $username === 'kadisdag') : ?>
                                                    <form method="POST" class="form-inline-actions">
                                                        <input type="hidden" name="form_type" value="persetujuan">
                                                        <input type="hidden" name="id_permintaan" value="<?php echo $row['id_permintaan']; ?>">
                                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan (opsional)">
                                                        <button type="submit" name="action" value="approve" class="btn btn-success btn-sm"><i class="bi bi-check2"></i> Setujui</button>
                                                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm"><i class="bi bi-x"></i> Tolak</button>
                                                    </form>
                                                <?php elseif ($row['status'] === 'disetujui') : ?>
                                                    <?php if (!empty($row['id_penyerahan'])) : ?>
                                                        <a href="cetak_tanda_terima.php?id=<?php echo $row['id_penyerahan']; ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak Tanda Terima</a>
                                                    <?php elseif ($username === 'kadisdag') : ?>
                                                        <a href="penyerahan_barang.php?id=<?php echo $row['id_permintaan']; ?>" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-down"></i> Proses Penyerahan</a>
                                                    <?php else : ?>
                                                        <span class="badge bg-info text-dark">Menunggu Penyerahan</span>
                                                    <?php endif; ?>
                                                <?php elseif ($row['status'] === 'selesai') : ?>
                                                    <a href="cetak_tanda_terima.php?id=<?php echo $row['id_penyerahan']; ?>" class="btn btn-outline-primary btn-sm" target="_blank"><i class="bi bi-printer"></i> Cetak Tanda Terima</a>
                                                <?php else : ?>
                                                    <span class="badge bg-secondary">Tidak ada aksi</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
