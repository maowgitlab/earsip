<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username'])) {
    header('location:index.php');
    exit();
}

$id_penyerahan = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : null;

if (!$id_penyerahan) {
    echo "<script>alert('Data penyerahan tidak ditemukan'); window.close();</script>";
    exit();
}

$query = "
    SELECT pen.*, p.kode_permintaan, p.tanggal_permohonan, p.jumlah, p.pemohon, p.keterangan AS keterangan_permohonan,
           p.tanggal_persetujuan, p.pimpinan, b.nama_barang, b.jenis_barang, d.nama_departemen
    FROM tbl_penyerahan_barang pen
    JOIN tbl_permintaan_barang p ON pen.id_permintaan = p.id_permintaan
    JOIN tbl_barang b ON p.id_barang = b.id_barang
    JOIN tbl_departemen d ON p.id_departemen = d.id_departemen
    WHERE pen.id_penyerahan = '$id_penyerahan'";
$result = mysqli_query($koneksi, $query);
$data = mysqli_fetch_array($result);

if (!$data) {
    echo "<script>alert('Data penyerahan tidak ditemukan'); window.close();</script>";
    exit();
}
?>
<script>window.print();</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanda Terima Penyerahan Barang</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 90px; height: 90px; }
        .detail-table td { border-bottom: 1px solid #000; }
        .signature { margin-top: 60px; display: flex; justify-content: space-between; }
        .signature div { width: 40%; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <img src="assets/logo.png" alt="Logo">
        <h2>DINAS PERDAGANGAN PROVINSI KALIMANTAN SELATAN</h2>
        <p>Jl. S. Parman No.44, Antasan Besar, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan 70114<br>
        Telepon (0511) 3354219</p>
        <hr>
        <h3>Tanda Terima Penyerahan Barang</h3>
    </div>

    <table class="detail-table">
        <tr>
            <th style="width: 35%;">Kode Permohonan</th>
            <td><?php echo htmlspecialchars($data['kode_permintaan']); ?></td>
        </tr>
        <tr>
            <th>Tanggal Permohonan</th>
            <td><?php echo htmlspecialchars($data['tanggal_permohonan']); ?></td>
        </tr>
        <tr>
            <th>Nama Barang</th>
            <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
        </tr>
        <tr>
            <th>Jumlah</th>
            <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
        </tr>
        <tr>
            <th>Departemen</th>
            <td><?php echo htmlspecialchars($data['nama_departemen']); ?></td>
        </tr>
        <tr>
            <th>Pemohon</th>
            <td><?php echo htmlspecialchars($data['pemohon']); ?></td>
        </tr>
        <tr>
            <th>Tanggal Persetujuan</th>
            <td><?php echo htmlspecialchars($data['tanggal_persetujuan']); ?></td>
        </tr>
        <tr>
            <th>Pejabat Menyetujui</th>
            <td><?php echo htmlspecialchars($data['pimpinan']); ?></td>
        </tr>
        <tr>
            <th>Tanggal Penyerahan</th>
            <td><?php echo htmlspecialchars($data['tanggal_penyerahan']); ?></td>
        </tr>
        <tr>
            <th>Diserahkan Oleh</th>
            <td><?php echo htmlspecialchars($data['diserahkan_oleh']); ?></td>
        </tr>
        <tr>
            <th>Diterima Oleh</th>
            <td><?php echo htmlspecialchars($data['diterima_oleh']); ?></td>
        </tr>
        <tr>
            <th>Keterangan</th>
            <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
        </tr>
    </table>

    <div class="signature">
        <div>
            <p>Yang Menyerahkan</p>
            <br><br><br>
            <p><?php echo htmlspecialchars($data['diserahkan_oleh']); ?></p>
        </div>
        <div>
            <p>Yang Menerima</p>
            <br><br><br>
            <p><?php echo htmlspecialchars($data['diterima_oleh']); ?></p>
        </div>
    </div>

    <p style="margin-top: 40px;">Dicetak pada: <?php echo date('d F Y H:i'); ?></p>
</body>
</html>
