<script>window.print();</script>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Laporan Data Arsip</title>
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
    $data = mysqli_query($koneksi, "SELECT 
                      tbl_arsip.*,
                      tbl_departemen.nama_departemen,
                      tbl_pengirim_surat.nama_pengirim, tbl_pengirim_surat.no_hp
                    FROM 
                      tbl_arsip, tbl_departemen, tbl_pengirim_surat
                    WHERE 
                      tbl_arsip.id_departemen = tbl_departemen.id_departemen
                      and tbl_arsip.id_pengirim = tbl_pengirim_surat.id_pengirim
                      and tbl_arsip.id_arsip= '$_GET[id]'
                    ");
    $d = mysqli_fetch_assoc($data);
  ?>
  <hr style="border: 2px double black">
  <h2 style="text-align: center; margin-top: 20px;"><u>SURAT KIRIM</u></h2>
  <p style="text-align: center; margin-bottom: 20px">Nomor: <?= $d['no_surat']; ?></p>

  <p>Kepada yang terhormat dibawah ini: </p>
  <table>
    <tr>
      <td width="200px">Tanggal Surat</td>
      <td width="10px">:</td>
      <td><?= $d['tanggal_surat']; ?></td>
    </tr>
    <tr>
      <td>Perihal</td>
      <td>:</td>
      <td><?= $d['perihal']; ?></td>
    </tr>
    <tr>
      <td>Departemen / Tujuan</td>
      <td>:</td>
      <td><?= $d['nama_departemen']; ?></td>
    </tr>
    <tr>
      <td>pengirim</td>
      <td>:</td>
      <td><?= $d['nama_pengirim']; ?></td>
    </tr>
  </table>
  <br>
  <p>Surat kirim ini diterima oleh <?= $d['nama_pengirim']; ?>, pada tanggal <?= $d['tanggal_diterima']; ?>. Oleh karena itu, berikut kami sampaikan salinan surat kirim yang diterima oleh <?= $d['nama_pengirim']; ?>, sebagai acuan dan bahan pertimbangan dalam proses penyelesaian surat kirim.</p>
  <span style="text-align: right; display: block; margin-top: 20px;">Banjarmasin, <?= date('d F Y'); ?></span>
  <span style="text-align: right; display: block; margin-right: 55px;">Mengetahui</span>
  <br><br>
  <span style="text-align: right; display: block; margin-top: 20px;">Kepala Dinas Perdagangan</span>
</body>

</html>