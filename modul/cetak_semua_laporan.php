<?php 
function formatTanggalIndonesia($tanggal) {
  $bulanIndo = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];
  
  $tanggalObj = new DateTime($tanggal);
  $bulan = $bulanIndo[(int)$tanggalObj->format('m') - 1];
  
  return $tanggalObj->format('d') . ' ' . $bulan . ' ' . $tanggalObj->format('Y');
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Data Keseluruhan</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      margin: 0;
      padding: 20px;
    }

    .page-break {
      page-break-before: always;
    }

    .header {
      margin-bottom: 30px;
    }

    .letterhead {
      width: 100%;
      border-collapse: collapse;
    }

    .letterhead img {
      max-width: 100px;
      height: auto;
    }

    .letterhead-text {
      text-align: center;
      padding-left: 20px;
    }

    .letterhead-text .title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .letterhead-text .subtitle {
      font-size: 18px;
      margin-bottom: 5px;
    }

    .letterhead-text .address {
      font-size: 12px;
      color: #666;
    }

    .divider {
      border: 2px double #000;
      margin: 20px 0;
    }

    .section-title {
      text-align: center;
      margin: 30px 0;
      color: #2c3e50;
    }

    .filter-form {
      max-width: 500px;
      margin: 30px auto;
      padding: 20px;
      background: #f8f9fa;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    .form-group input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }

    .btn {
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }

    .btn-primary {
      background: #007bff;
      color: white;
    }

    .btn-secondary {
      background: #6c757d;
      color: white;
      margin-left: 10px;
    }

    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      background: white;
    }

    .data-table th {
      background: #f8f9fa;
      padding: 12px;
      border: 1px solid #dee2e6;
      font-weight: bold;
    }

    .data-table td {
      padding: 10px;
      border: 1px solid #dee2e6;
    }

    .data-table tr:nth-child(even) {
      background: #f8f9fa;
    }

    .signature {
      margin-top: 50px;
      text-align: right;
    }

    .signature .date {
      margin-bottom: 10px;
    }

    .signature .title {
      margin-top: 60px;
      font-weight: bold;
    }

    @media print {
      .filter-form {
        display: none;
      }
      
      body {
        padding: 0;
        margin: 0;
      }
      
      .data-table {
        page-break-inside: auto;
      }
      
      .data-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
      }
    }
  </style>
</head>

<body>
  <?php
  // Get filter dates if set
  $tgl_awal = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : '';
  $tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';
  ?>

  <!-- Filter Form Always Visible -->
  <div class="filter-form">
    <h2 class="section-title">Filter Laporan</h2>
    <form method="GET" action="">
      <input type="hidden" name="halaman" value="cetak_semua_laporan">
      <div class="form-group">
        <label for="tgl_awal">Tanggal Awal:</label>
        <input type="date" name="tgl_awal" id="tgl_awal" required value="<?= $tgl_awal ?>">
      </div>
      <div class="form-group">
        <label for="tgl_akhir">Tanggal Akhir:</label>
        <input type="date" name="tgl_akhir" id="tgl_akhir" required value="<?= $tgl_akhir ?>">
      </div>
      <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
      <?php if(!empty($tgl_awal) && !empty($tgl_akhir)): ?>
        <a href="?halaman=cetak_semua_laporan" class="btn btn-secondary">Reset Filter</a>
      <?php endif; ?>
    </form>
  </div>

  <?php if(!empty($tgl_awal) && !empty($tgl_akhir)): ?>
  <!-- Print trigger after data is loaded -->
  <script>
    window.onload = function() {
      window.print();
    }
  </script>

  <div class="header">
    <table class="letterhead">
      <tr>
        <td width="100"><img src="assets/logo.png" alt="Logo"></td>
        <td class="letterhead-text">
          <div class="title">DINAS PERDAGANGAN</div>
          <div class="subtitle">PROVINSI KALIMANTAN SELATAN</div>
          <div class="address">
            Jl. S. Parman No.44, Antasan Besar, Kec. Banjarmasin Tengah<br>
            Kota Banjarmasin, Kalimantan Selatan 70114<br>
            Telepon (0511) 3354219<br>
            Website: www.disdag.kalselprov.go.id/
          </div>
        </td>
      </tr>
    </table>
  </div>
  
  <hr class="divider">

  <!-- Arsip Section -->
  <h2 class="section-title">Laporan Surat Masuk</h2>
  <p style="text-align: center;">Periode: <?= formatTanggalIndonesia($tgl_awal) ?> - <?= formatTanggalIndonesia($tgl_akhir) ?></p>  
  <?php
  $no = 1;
  $query_arsip = "SELECT 
    tbl_arsip.*,
    tbl_departemen.nama_departemen,
    tbl_pengirim_surat.nama_pengirim,
    tbl_pengirim_surat.no_hp
  FROM 
    tbl_arsip
    JOIN tbl_departemen ON tbl_arsip.id_departemen = tbl_departemen.id_departemen
    JOIN tbl_pengirim_surat ON tbl_arsip.id_pengirim = tbl_pengirim_surat.id_pengirim
  WHERE 
    tbl_arsip.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'
  ORDER BY tbl_arsip.id_arsip DESC";
  
  $data_arsip = mysqli_query($koneksi, $query_arsip);
  ?>
  
  <table class="data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th>No Surat</th>
        <th>Tanggal Surat</th>
        <th>Tanggal Diterima</th>
        <th>Perihal</th>
        <th>Departemen</th>
        <th>Pengirim</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data_arsip as $d): ?>
      <tr>
        <td style="text-align: center;"><?= $no++; ?></td>
        <td><?= $d['no_surat']; ?></td>
        <td><?= date('d/m/Y', strtotime($d['tanggal_surat'])); ?></td>
        <td><?= date('d/m/Y', strtotime($d['tanggal_diterima'])); ?></td>
        <td><?= $d['perihal']; ?></td>
        <td><?= $d['nama_departemen']; ?></td>
        <td><?= $d['nama_pengirim']; ?> / <?= $d['no_hp']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Departemen Section -->
  <div class="page-break"></div>
  <h2 class="section-title">Laporan Data Departemen</h2>
  
  <?php
  $no = 1;
  $query_dept = "SELECT * FROM tbl_departemen 
                 WHERE created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'
                 ORDER BY id_departemen DESC";
  $data_dept = mysqli_query($koneksi, $query_dept);
  ?>
  
  <table class="data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama Departemen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data_dept as $d): ?>
      <tr>
        <td style="text-align: center;"><?= $no++; ?></td>
        <td><?= $d['nama_departemen']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Pegawai Section -->
  <div class="page-break"></div>
  <h2 class="section-title">Laporan Data Pegawai</h2>
  
  <?php
  $no = 1;
  $query_pegawai = "SELECT tbl_pegawai.*, tbl_departemen.nama_departemen 
                    FROM tbl_pegawai
                    JOIN tbl_departemen ON tbl_pegawai.departemen = tbl_departemen.id_departemen
                    WHERE tbl_pegawai.created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'
                    ORDER BY id_pegawai DESC";
  $data_pegawai = mysqli_query($koneksi, $query_pegawai);
  ?>
  
  <table class="data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama Pegawai</th>
        <th>NIP</th>
        <th>Jabatan</th>
        <th>Departemen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data_pegawai as $d): ?>
      <tr>
        <td style="text-align: center;"><?= $no++; ?></td>
        <td><?= $d['nama']; ?></td>
        <td><?= $d['nip']; ?></td>
        <td><?= $d['jabatan']; ?></td>
        <td><?= $d['nama_departemen']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Pengirim Surat Section -->
  <div class="page-break"></div>
  <h2 class="section-title">Laporan Data Pengirim Surat</h2>
  
  <?php
  $no = 1;
  $query_pengirim = "SELECT * FROM tbl_pengirim_surat 
                     WHERE created_at BETWEEN '$tgl_awal' AND '$tgl_akhir'
                     ORDER BY id_pengirim DESC";
  $data_pengirim = mysqli_query($koneksi, $query_pengirim);
  ?>
  
  <table class="data-table">
    <thead>
      <tr>
        <th>No.</th>
        <th>Nama Pengirim</th>
        <th>Alamat</th>
        <th>No HP</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data_pengirim as $d): ?>
      <tr>
        <td style="text-align: center;"><?= $no++; ?></td>
        <td><?= $d['nama_pengirim']; ?></td>
        <td><?= $d['alamat']; ?></td>
        <td><?= $d['no_hp']; ?></td>
        <td><?= $d['email']; ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="signature">
    <div class="date">Banjarmasin, <?= formatTanggalIndonesia(date('d M Y')); ?></div>
    <div>Mengetahui</div>
    <div class="title">Kepala Dinas Perdagangan</div>
  </div>

  <?php endif; ?>
</body>
</html>