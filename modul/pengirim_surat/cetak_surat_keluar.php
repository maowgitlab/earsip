<?php
include "config/koneksi.php";

// Ambil parameter filter tanggal
$tanggal_mulai = isset($_GET['tanggal_mulai']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal_mulai']) : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? mysqli_real_escape_string($koneksi, $_GET['tanggal_akhir']) : '';

// Validasi tanggal
if (!empty($tanggal_mulai) && !empty($tanggal_akhir) && $tanggal_mulai > $tanggal_akhir) {
    echo "<script>alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir'); window.location='?halaman=cetak_surat_keluar';</script>";
    $tanggal_mulai = $tanggal_akhir = '';
}

// Query untuk mengambil data surat keluar
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

// Tambahkan filter tanggal jika ada
if (!empty($tanggal_mulai) && !empty($tanggal_akhir)) {
    $query .= " AND tbl_arsip_keluar.tanggal_kirim BETWEEN '$tanggal_mulai' AND '$tanggal_akhir'";
}

$query .= " ORDER BY tbl_arsip_keluar.tanggal_kirim DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Surat Keluar</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
    }
    table.data {
      width: 100%;
      border-collapse: collapse;
    }
    table.data th, table.data td {
      padding: 8px;
      text-align: center;
      border: 1px solid #000;
    }
    table.data th {
      background-color: #f2f2f2;
    }
    .header {
      margin-bottom: 20px;
    }
    .signature {
      text-align: right;
      display: block;
      margin-top: 20px;
      margin-right: 55px;
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
  <div class="header">
    <table width="100%" style="border: none;">
      <tr>
        <td style="width: 120px;"><img src="assets/logo.png" width="100px" height="100px" alt="Logo"></td>
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
  </div>
  <hr style="border: 2px double black">
  <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Laporan Surat Keluar</h2>
  <div class="container">
    <div class="filter-group">
      <form method="GET" action="">
        <input type="hidden" name="halaman" value="cetak_surat_keluar">
        <label for="tanggal_mulai">Tanggal Mulai:</label>
        <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?= htmlspecialchars($tanggal_mulai); ?>">
        <label for="tanggal_akhir">Tanggal Akhir:</label>
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="<?= htmlspecialchars($tanggal_akhir); ?>">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="#" class="btn-print no-print" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</a>
      </form>
    </div>
    <table class="data">
      <thead>
        <tr>
          <th>No</th>
          <th>No Surat</th>
          <th>Tanggal Surat</th>
          <th>Tanggal Kirim</th>
          <th>Tujuan Surat</th>
          <th>Tujuan (Instansi)</th>
          <th>Perihal</th>
          <th>Departemen</th>
          <th>Pengirim</th>
        </tr>
      </thead>
      <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $no = 1;
            while ($data = mysqli_fetch_array($result)) :
        ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($data['no_surat']) ?></td>
            <td><?= htmlspecialchars($data['tanggal_surat']) ?></td>
            <td><?= htmlspecialchars($data['tanggal_kirim'] ?? '-') ?></td>
            <td><?= htmlspecialchars($data['tujuan_surat']) ?></td>
            <td><?= htmlspecialchars($data['nama_tujuan']) ?></td>
            <td><?= htmlspecialchars($data['perihal']) ?></td>
            <td><?= htmlspecialchars($data['nama_departemen']) ?></td>
            <td><?= htmlspecialchars($data['nama_pengirim']) ?></td>
          </tr>
        <?php
            endwhile;
        } else {
        ?>
          <tr>
            <td colspan="10" style="text-align: center;">Tidak ada data ditemukan.</td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <span class="signature">Banjarmasin, <?= date('d F Y'); ?></span>
  <span class="signature">Mengetahui</span>
  <br><br><br><br>
  <span class="signature">Kepala Dinas Perdagangan</span>
</body>

</html>