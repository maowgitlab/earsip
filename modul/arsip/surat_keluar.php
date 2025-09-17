<?php
include "config/koneksi.php";

$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, $_GET['search']) : '';
$filter_status = isset($_GET['filter_status']) ? mysqli_real_escape_string($koneksi, $_GET['filter_status']) : 'semua';

// Pisahkan parameter search jika mengandung " - "
$search_no_surat = '';
$search_perihal = '';
if (!empty($search) && strpos($search, ' - ') !== false) {
    list($search_no_surat, $search_perihal) = explode(' - ', $search, 2);
    $search_no_surat = mysqli_real_escape_string($koneksi, trim($search_no_surat));
    $search_perihal = mysqli_real_escape_string($koneksi, trim($search_perihal));
} else {
    $search_no_surat = $search;
    $search_perihal = $search;
}
?>

<div class="card mt-4">
    <div class="card-header bg-warning">
        Data Arsip Surat Keluar
    </div>
    <div class="card-body">
        <?php if ($_SESSION['username'] == 'sekretariat') : ?>
            <a href="?halaman=tambah_keluar" class="btn btn-info mb-3">Tambah Data</a>
        <?php endif; ?>
        <div class="filter-group mb-3">
            <form method="GET" action="">
                <input type="hidden" name="halaman" value="surat_keluar">
                <label for="filter_status">Filter Status:</label>
                <select name="filter_status" id="filter_status" onchange="this.form.submit()">
                    <option value="semua" <?php echo $filter_status == 'semua' ? 'selected' : ''; ?>>Semua</option>
                    <option value="terverifikasi" <?php echo $filter_status == 'terverifikasi' ? 'selected' : ''; ?>>Terverifikasi</option>
                    <option value="menunggu" <?php echo $filter_status == 'menunggu' ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                </select>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped">
            <tr>
                <th style='text-align: center'>No</th>
                <th style='text-align: center'>No Surat</th>
                <th style='text-align: center'>Tanggal Surat</th>
                <th style='text-align: center'>Tanggal Diterima</th>
                <th style='text-align: center'>Tujuan Surat</th>
                <th style='text-align: center'>Perihal</th>
                <th style='text-align: center'>Departemen</th>
                <th style='text-align: center'>Pengirim</th>
                <th style='text-align: center'>Tujuan</th>
                <th style='text-align: center'>Status</th>
                <th style='text-align: center'>Aksi</th>
            </tr>
            <?php
            $query = "SELECT 
                        tbl_arsip_keluar.*,
                        tbl_departemen.nama_departemen,
                        tbl_pegawai.nama AS nama_pengirim,
                        tbl_pengirim_surat.nama_pengirim AS nama_tujuan
                      FROM 
                        tbl_arsip_keluar
                        JOIN tbl_departemen ON tbl_arsip_keluar.id_departemen = tbl_departemen.id_departemen
                        JOIN tbl_pegawai ON tbl_arsip_keluar.id_pengirim = tbl_pegawai.id_pegawai
                        JOIN tbl_pengirim_surat ON tbl_arsip_keluar.id_tujuan = tbl_pengirim_surat.id_pengirim
                      WHERE 1=1";

            // Filter pencarian
            if (!empty($search)) {
                if (!empty($search_no_surat) && !empty($search_perihal)) {
                    $query .= " AND (tbl_arsip_keluar.no_surat LIKE '%$search_no_surat%' AND tbl_arsip_keluar.perihal LIKE '%$search_perihal%')";
                } else {
                    $query .= " AND (tbl_arsip_keluar.no_surat LIKE '%$search%' OR tbl_arsip_keluar.perihal LIKE '%$search%' OR tbl_arsip_keluar.tujuan_surat LIKE '%$search%')";
                }
            }

            // Filter status
            if ($filter_status == 'terverifikasi') {
                $query .= " AND tbl_arsip_keluar.status = '1'";
            } elseif ($filter_status == 'menunggu') {
                $query .= " AND tbl_arsip_keluar.status = '0'";
            }

            $query .= " ORDER BY tbl_arsip_keluar.id_arsip_keluar DESC";
            $tampil = mysqli_query($koneksi, $query);
            $no = 1;
            if (mysqli_num_rows($tampil) > 0) {
                while ($data = mysqli_fetch_array($tampil)) :
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($data['no_surat']) ?></td>
                    <td><?= htmlspecialchars($data['tanggal_surat']) ?></td>
                    <td><?= htmlspecialchars($data['tanggal_diterima'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($data['tujuan_surat']) ?></td>
                    <td><?= htmlspecialchars($data['perihal']) ?></td>
                    <td><?= htmlspecialchars($data['nama_departemen']) ?></td>
                    <td><?= htmlspecialchars($data['nama_pengirim']) ?></td>
                    <td><?= htmlspecialchars($data['nama_tujuan']) ?></td>
                    <td>
                        <?php
                        if ($data['status'] === "0") {
                            echo "<span class='badge badge-warning'>Menunggu Persetujuan</span>";
                        } elseif ($data['status'] === "1") {
                            echo "<span class='badge badge-success'>Disetujui</span>";
                        } else {
                            echo "<span class='badge badge-danger'>Ditolak</span>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['username'] == 'sekretariat') : ?>
                            <a href="?halaman=edit_keluar&hal=edit&id=<?= $data['id_arsip_keluar'] ?>" class="btn btn-primary">Edit</a>
                            <a href="?halaman=hapus_keluar&id=<?= $data['id_arsip_keluar'] ?>" class="btn btn-danger" 
                               onclick="return confirm('Apakah yakin ingin menghapus data ini?')">Hapus</a>
                            <?php if ($data['status'] == "1") : ?>
                                <!-- <a href="?halaman=arsip_surat_keluar_kirim&id=<?= $data['id_arsip_keluar'] ?>" class="btn btn-success" 
                                   onclick="return confirm('Apakah yakin ingin mengirim data ini?')">Kirim</a> -->
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($_SESSION['username'] == 'kadisdag' && $data['status'] == "0") : ?>
                            <a href="?halaman=verifikasi_keluar&id=<?= $data['id_arsip_keluar'] ?>&aksi=setujui" class="btn btn-success"
                               onclick="return confirm('Setujui surat keluar ini?')">Setujui</a>
                            <a href="?halaman=verifikasi_keluar&id=<?= $data['id_arsip_keluar'] ?>&aksi=tolak" class="btn btn-danger"
                               onclick="return confirm('Tolak surat keluar ini?')">Tolak</a>
                        <?php endif; ?>
                        <?php if ($data['status'] == "1") : ?>
                            <a href="?halaman=cetak_data_keluar&id=<?= $data['id_arsip_keluar'] ?>" class="btn btn-secondary">Cetak <i class="bi bi-printer"></i></a>
                        <?php endif; ?>
                        <?php if ($data['status'] == "2") : ?>
                            <span class="text-muted">Menunggu revisi sekretariat</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
                endwhile;
            } else {
            ?>
                <tr>
                    <td colspan="11" style="text-align: center;">Tidak ada data ditemukan.</td>
                </tr>
            <?php } ?>
        </table>
        </div>
    </div>
</div>