
<?php
include "config/koneksi.php";

// Ambil parameter filter tanggal dari GET
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal_mulai']) : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal_akhir']) : '';

// Bangun query berdasarkan filter tanggal
$query = "SELECT 
            tbl_arsip.*,
            tbl_departemen.nama_departemen,
            tbl_pengirim_surat.nama_pengirim, 
            tbl_pengirim_surat.no_hp
          FROM 
            tbl_arsip
            JOIN tbl_departemen ON tbl_arsip.id_departemen = tbl_departemen.id_departemen
            JOIN tbl_pengirim_surat ON tbl_arsip.id_pengirim = tbl_pengirim_surat.id_pengirim
          WHERE 1=1";
if (!empty($tanggal_mulai) && !empty($tanggal_akhir)) {
    $query .= " AND tbl_arsip.tanggal_surat BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
} elseif (!empty($tanggal_mulai)) {
    $query .= " AND tbl_arsip.tanggal_surat >= '$tanggal_mulai'";
} elseif (!empty($tanggal_akhir)) {
    $query .= " AND tbl_arsip.tanggal_surat <= '$tanggal_akhir'";
}
$query .= " ORDER BY tbl_arsip.id_arsip DESC";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Data Arsip</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 5px;
      text-align: center;
    }
    .header {
      margin-bottom: 20px;
    }
    .signature {
      text-align: right;
      display: block;
      margin-top: 20px;
    }
    .filter-group {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }
    .btn-print {
      background-color: #28a745;
      color: #fff;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
    }
    @media print {
      .filter-group {
        display: none;
      }
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body>
  <table width="100%" class="header">
    <tr>
      <td><img src="assets/logo.png" width="100px" height="100px" alt="Logo"></td>
      <td>
        <center>
          <font size="5">DINAS PERDAGANGAN</font><br>
          <font size="4">PROVINSI KALIMANTAN SELATAN</font><br>
          <font size="2">Jl. S. Parman No.44, Antasan Besar, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70114</font><br>
          <font size="2">Telepon (0511) 3354219</font><br>
          <font size="2">Website: www.disdag.kalselprov.go.id/</font>
        </center>
      </td>
    </tr>
  </table>
  <hr style="border: 2px double black">
  <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Laporan Surat Masuk</h2>
  <div class="container">
    <div class="filter-group">
      <form method="GET" action="admin.php">
        <input type="hidden" name="halaman" value="cetak_arsip">
        <label for="tanggal_mulai">Tanggal Mulai:</label>
        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>">
        <label for="tanggal_akhir">Tanggal Akhir:</label>
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir); ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="#" class="btn-print no-print" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</a>
      </form>
    </div>
  </div>
  <?php
    $no = 1;
    $data = mysqli_query($koneksi, $query);
  ?>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th style="text-align: center;">No.</th>
        <th style="text-align: center;">Nomor Surat</th>
        <th style="text-align: center;">Tanggal Surat</th>
        <th style="text-align: center;">Tanggal Diterima</th>
        <th style="text-align: center;">Perihal</th>
        <th style="text-align: center;">Departemen / Tujuan</th>
        <th style="text-align: center;">Pengirim</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($data) > 0) : ?>
        <?php foreach($data as $d) : ?>
          <tr>
            <td style="text-align: center;"><?= $no++; ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['no_surat']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['tanggal_surat']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['tanggal_diterima']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['perihal']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['nama_departemen']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['nama_pengirim']); ?> / <?= htmlspecialchars($d['no_hp']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
        <tr>
          <td colspan="8" style="text-align: center;">Tidak ada data untuk rentang tanggal ini.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <span class="signature">Banjarmasin, <?= date('d F Y'); ?></span>
  <span class="signature" style="margin-right: 55px;">Mengetahui</span>
  <br><br>
  <span class="signature">Kepala Dinas Perdagangan</span>
</body>

</html>