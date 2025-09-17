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
                        tbl_arsip_keluar.*,
                        tbl_departemen.nama_departemen,
                        tbl_pegawai.nama AS nama_pengirim,
                        tbl_pengirim_surat.nama_pengirim AS nama_tujuan
                      FROM 
                        tbl_arsip_keluar
                        JOIN tbl_departemen ON tbl_arsip_keluar.id_departemen = tbl_departemen.id_departemen
                        JOIN tbl_pegawai ON tbl_arsip_keluar.id_pengirim = tbl_pegawai.id_pegawai
                        JOIN tbl_pengirim_surat ON tbl_arsip_keluar.id_tujuan = tbl_pengirim_surat.id_pengirim
                      WHERE id_arsip_keluar = '$_GET[id]'
                    ");
    $d = mysqli_fetch_assoc($data);
  ?>
  <hr style="border: 2px double black">
  <h2 style="text-align: center; margin-top: 20px;"><u>SURAT KELUAR</u></h2>
  <p style="text-align: center; margin-bottom: 20px">Nomor: <?= $d['no_surat']; ?></p>

  <p>Kepada yang terhormat dibawah ini: </p>
  <table>
    <tr>
      <td width="200px">Tanggal Surat</td>
      <td width="10px">:</td>
      <td><?= date('d F Y', strtotime($d['tanggal_surat'])); ?></td>
    </tr>
    <tr>
      <td>Perihal</td>
      <td>:</td>
      <td><?= $d['perihal']; ?></td>
    </tr>
    <tr>
      <td>Tujuan Surat</td>
      <td>:</td>
      <td><?= $d['tujuan_surat']; ?></td>
    </tr>
    <tr>
      <td>Instansi</td>
      <td>:</td>
      <td><?= $d['nama_departemen']; ?></td>
    </tr>
  </table>
  <br>
  <p>Surat ini dikeluarkan oleh <?= $d['nama_pengirim']; ?>, pada tanggal <?= date('d F Y', strtotime($d['tanggal_surat'])); ?>. Oleh karena itu, berikut kami sampaikan salinan surat keluar ini yang dikeluarkan oleh <?= $d['nama_pengirim']; ?>, sebagai acuan dan bahan pertimbangan dalam proses penyelesaian surat ini.</p>
  <span style="text-align: right; display: block; margin-top: 20px;">Banjarmasin, <?= date('d F Y'); ?></span>
  <span style="text-align: right; display: block; margin-right: 55px;">Mengetahui</span>
  <br><br>
  <span style="text-align: right; display: block; margin-top: 20px;">Kepala Dinas Perdagangan</span>
</body>

</html>