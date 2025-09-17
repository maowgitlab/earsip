<script>window.print();</script>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Data Departemen</title>
</head>

<body>
  <table width="100%">
    <tr>
      <td><img src="assets/logo.png" width="100px" height="100px"></td>
      <td>
        <center>
          <font size="5">DINAS PERDAGANGAN</font> <br>
          <font size="4">PROVINSI KALIMANTAN SELATAN</font> <br>
          <font size="2">Jl. S. Parman No.44, Antasan Besar, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70114</font> <br>
          <font size="2">Telepon (0511) 3354219</font> <br>
          <font size="2">Website: www.disdag.kalselprov.go.id/</font> <br>
        </center>
      </td>
    </tr>
  </table>
  <?php
    $no = 1;
    $data = mysqli_query($koneksi, "SELECT * from tbl_departemen ORDER BY id_departemen DESC");
  ?>
  <hr style="border: 2px double black">
  <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px">Laporan Data Departemen</h2>
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th style="text-align: center;">No.</th>
        <th style="text-align: center;">Nama Departemen</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($data as $d) : ?>
        <tr>
          <td style="text-align: center;"><?= $no++; ?></td>
          <td style="text-align: center;"><?= $d['nama_departemen']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <span style="text-align: right; display: block; margin-top: 20px;">Banjarmasin, <?= date('d F Y'); ?></span>
  <span style="text-align: right; display: block; margin-right: 55px;">Mengetahui</span>
  <br><br>
  <span style="text-align: right; display: block; margin-top: 20px;">Kepala Dinas Perdagangan</span>
</body>

</html>