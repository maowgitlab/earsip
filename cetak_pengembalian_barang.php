<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}
?>
<script>window.print();</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Pengembalian Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; text-align: center; }
        .header { margin-bottom: 20px; }
        .signature { text-align: right; display: block; margin-top: 20px; }
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
    <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Laporan Data Pengembalian Barang</h2>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">No.</th>
                <th style="text-align: center;">Nama Barang</th>
                <th style="text-align: center;">Departemen</th>
                <th style="text-align: center;">Tanggal</th>
                <th style="text-align: center;">Pegawai</th>
                <th style="text-align: center;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = "
                SELECT b.nama_barang, d.nama_departemen, s.created_at, p.nama as nama_pegawai, s.keterangan
                FROM tbl_status_barang s
                JOIN tbl_barang b ON s.id_barang = b.id_barang
                JOIN tbl_departemen d ON b.id_departemen = d.id_departemen
                LEFT JOIN tbl_pegawai p ON s.id_pegawai = p.id_pegawai
                WHERE s.status = 'tersedia'
                ORDER BY s.created_at DESC";
            $result = mysqli_query($koneksi, $query);
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td style="text-align: center;"><?php echo $no++; ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                    <td style="text-align: center;"><?php echo $row['created_at']; ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($row['nama_pegawai'] ?? '-'); ?></td>
                    <td style="text-align: center;"><?php echo htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <span class="signature">Banjarmasin, <?php echo date('d F Y'); ?></span>
    <span class="signature" style="margin-right: 55px;">Mengetahui</span>
    <br><br>
    <span class="signature">Kepala Dinas Perdagangan</span>
</body>
</html>