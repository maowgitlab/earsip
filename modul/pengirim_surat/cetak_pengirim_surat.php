
<?php
include "config/koneksi.php";

$query = "SELECT * FROM tbl_pengirim_surat ORDER BY id_pengirim DESC";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Data Pengirim Surat</title>
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
  <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Laporan Data Pengirim Surat</h2>
  <!-- Tombol Cetak -->
<div class="no-print" style="text-align: center; margin-bottom: 20px;">
  <a href="#" class="btn-print" onclick="window.print()">
    <i class="bi bi-printer"></i> Cetak
  </a>
</div>
  <div class="container">
  </div>
  <?php
    $no = 1;
    $data = mysqli_query($koneksi, $query);
  ?>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th style="text-align: center;">No.</th>
        <th style="text-align: center;">Nama Pengirim</th>
        <th style="text-align: center;">Alamat</th>
        <th style="text-align: center;">Nomor HP</th>
        <th style="text-align: center;">Email</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($data) > 0) : ?>
        <?php foreach($data as $d) : ?>
          <tr>
            <td style="text-align: center;"><?= $no++; ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['nama_pengirim']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['alamat']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['no_hp']); ?></td>
            <td style="text-align: center;"><?= htmlspecialchars($d['email']); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else : ?>
      <?php endif; ?>
    </tbody>
  </table>
  <span class="signature">Banjarmasin, <?= date('d F Y'); ?></span>
  <span class="signature" style="margin-right: 55px;">Mengetahui</span>
  <br><br>
  <span class="signature">Kepala Dinas Perdagangan</span>
</body>

</html>