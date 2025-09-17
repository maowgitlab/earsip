
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
    Data Arsip Surat
  </div>
  <div class="card-body">
    <?php if($_SESSION['username'] == 'sekretariat') : ?>
      <a href="?halaman=arsip_surat&hal=tambahdata" class="btn btn-info mb-3">Tambah Data</a>
    <?php endif; ?>
    <div class="filter-group mb-3">
      <form method="GET" action="">
        <input type="hidden" name="halaman" value="arsip_surat">
        <label for="filter_status">Filter Status:</label>
        <select name="filter_status" id="filter_status" onchange="this.form.submit()">
          <option value="semua" <?php echo $filter_status == 'semua' ? 'selected' : ''; ?>>Semua</option>
          <option value="aktif" <?php echo $filter_status == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
          <option value="inaktif" <?php echo $filter_status == 'inaktif' ? 'selected' : ''; ?>>Inaktif</option>
        </select>
      </form>
    </div>
    <table class="table table-bordered table-hover table-striped">
      <tr>
        <th style='text-align: center'>No</th>
        <th style='text-align: center'>No Surat</th>
        <th style='text-align: center'>Tanggal Surat</th>
        <th style='text-align: center'>Tanggal Diterima</th>
        <th style='text-align: center'>Perihal</th>
        <th style='text-align: center'>Departemen / Tujuan</th>
        <th style='text-align: center'>Pengirim</th>
        <th style='text-align: center'>File</th>
        <th style='text-align: center'>Status</th>
        <th style='text-align: center'>Aksi</th>
      </tr>
      <?php
        $query = "SELECT 
                    tbl_arsip.*,
                    tbl_departemen.nama_departemen,
                    tbl_pengirim_surat.nama_pengirim, tbl_pengirim_surat.no_hp
                  FROM 
                    tbl_arsip
                    JOIN tbl_departemen ON tbl_arsip.id_departemen = tbl_departemen.id_departemen
                    JOIN tbl_pengirim_surat ON tbl_arsip.id_pengirim = tbl_pengirim_surat.id_pengirim
                  WHERE 1=1";
        
        // Filter pencarian
        if (!empty($search)) {
          if (!empty($search_no_surat) && !empty($search_perihal)) {
            $query .= " AND (tbl_arsip.no_surat LIKE '%$search_no_surat%' AND tbl_arsip.perihal LIKE '%$search_perihal%')";
          } else {
            $query .= " AND (tbl_arsip.no_surat LIKE '%$search%' OR tbl_arsip.perihal LIKE '%$search%')";
          }
        }

        // Filter status aktif/inaktif
        if ($filter_status == 'aktif') {
          $query .= " AND (tbl_arsip.status = '0' OR tbl_arsip.tanggal_surat >= DATE_SUB(CURDATE(), INTERVAL 5 YEAR))";
        } elseif ($filter_status == 'inaktif') {
          $query .= " AND (tbl_arsip.status = '1' AND tbl_arsip.tanggal_surat < DATE_SUB(CURDATE(), INTERVAL 5 YEAR))";
        }
        
        $query .= " ORDER BY tbl_arsip.id_arsip DESC";
        $tampil = mysqli_query($koneksi, $query);
        $no = 1;
        if (mysqli_num_rows($tampil) > 0) {
          while($data = mysqli_fetch_array($tampil)) :
      ?>
      <tr>
        <td><?=$no++?></td>
        <td><?=$data['no_surat']?></td>
        <td><?=$data['tanggal_surat']?></td>
        <td><?=$data['tanggal_diterima']?></td>
        <td><?=$data['perihal']?></td>
        <td><?=$data['nama_departemen']?></td>
        <td><?=$data['nama_pengirim']?> / <?=$data['no_hp']?></td>
        <td> 
          <?php
            if(empty($data['file'])){
              echo " - ";
            } else {
          ?>
              <a href="file/<?=$data['file']?>" target="_blank"> lihat file </a>
          <?php
            }
          ?>
        </td>
        <td><?= $data['status'] == "0" ? "<span class='badge badge-danger'>Menunggu verifikasi Kadis</span>" : "<span class='badge badge-success'>Terverifikasi</span>" ?></td>
        <td>
          <?php if($_SESSION['username'] == 'sekretariat') : ?>
            <a href="?halaman=arsip_surat&hal=edit&id=<?=$data['id_arsip']?>" class="btn btn-primary">Edit</a>
            <a href="?halaman=arsip_surat&hal=hapus&id=<?=$data['id_arsip']?>" class="btn btn-danger" 
              onclick="return confirm('Apakah yakin ingin menghapus data ini?')">Hapus</a>
            <?php if($data['status'] == "1") : ?>
              <a href="?halaman=arsip_surat_kirim&id=<?=$data['id_arsip']?>" class="btn btn-success" 
              onclick="return confirm('Apakah yakin ingin mengirim data ini?')">Kirim</a>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($_SESSION['username'] == 'kadisdag') : ?>
            <?php if($data['status'] == "0") : ?>
              <a href="?halaman=arsip_surat_verifikasi&id=<?=$data['id_arsip']?>" class="btn btn-success" 
              onclick="return confirm('Anda ingin mengverifikasi data ini?')">Verifikasi</a>
            <?php endif; ?>
          <?php endif; ?>
          <?php if($data['status'] == "1") : ?>
            <a href="?halaman=cetak_arsip_surat&id=<?=$data['id_arsip']?>" class="btn btn-secondary">Cetak <i class="bi bi-printer"></i></a>
          <?php endif; ?>
        </td>
      </tr>
      <?php 
          endwhile; 
        } else {
      ?>
      <tr>
        <td colspan="10" style="text-align: center;">Tidak ada data ditemukan.</td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>