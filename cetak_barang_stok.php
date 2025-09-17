
<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}

// Ambil filter dari parameter GET
$jenis_barang = isset($_GET['jenis_barang']) ? mysqli_real_escape_string($koneksi, $_GET['jenis_barang']) : 'semua';
$filter_stok = isset($_GET['filter_stok']) ? mysqli_real_escape_string($koneksi, $_GET['filter_stok']) : 'semua';
$filter_status = isset($_GET['filter_status']) ? mysqli_real_escape_string($koneksi, $_GET['filter_status']) : 'semua';

// Bangun query berdasarkan filter
$query = "
    SELECT b.id_barang, b.nama_barang, b.jenis_barang, d.nama_departemen,
        COALESCE(
            (SELECT SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END)
             FROM tbl_stok_barang s WHERE s.id_barang = b.id_barang),
             0
        ) as stok,
        (SELECT s.status FROM tbl_status_barang s 
         WHERE s.id_barang = b.id_barang ORDER BY s.created_at DESC LIMIT 1) as status
    FROM tbl_barang b
    JOIN tbl_departemen d ON b.id_departemen = d.id_departemen
    WHERE 1=1
";

// Filter jenis barang
if ($jenis_barang == 'habis_pakai' || $jenis_barang == 'tidak_habis_pakai') {
    $query .= " AND b.jenis_barang = '$jenis_barang'";
}

// Filter stok (hanya untuk barang habis pakai)
if ($filter_stok != 'semua' && $jenis_barang != 'tidak_habis_pakai') {
    if ($filter_stok == 'kosong') {
        $query .= " AND COALESCE(
                        (SELECT SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END)
                         FROM tbl_stok_barang s WHERE s.id_barang = b.id_barang),
                         0
                     ) = 0";
    } elseif ($filter_stok == 'ada') {
        $query .= " AND COALESCE(
                        (SELECT SUM(CASE WHEN s.tipe_transaksi = 'masuk' THEN s.jumlah ELSE -s.jumlah END)
                         FROM tbl_stok_barang s WHERE s.id_barang = b.id_barang),
                         0
                     ) > 0";
    }
}

// Filter status (hanya untuk barang tidak habis pakai atau semua)
if ($filter_status != 'semua' && $jenis_barang != 'habis_pakai') {
    $query .= " AND (SELECT s.status FROM tbl_status_barang s 
                     WHERE s.id_barang = b.id_barang ORDER BY s.created_at DESC LIMIT 1) = '$filter_status'";
}

$query .= " ORDER BY b.nama_barang";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Barang dan Stok</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 5px; text-align: center; }
        .header { margin-bottom: 20px; }
        .signature { text-align: right; display: block; margin-top: 20px; }
        .filter-group { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .btn-print { background-color: #28a745; color: #fff; padding: 6px 12px; border-radius: 4px; text-decoration: none; }
        @media print {
            .filter-group { display: none; }
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
    <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Laporan Data Barang dan Stok</h2>
    <div class="container">
        <div class="filter-group">
            <form method="GET" action="">
                <label for="jenis_barang">Jenis Barang:</label>
                <select name="jenis_barang" id="jenis_barang" onchange="this.form.submit()">
                    <option value="semua" <?php echo $jenis_barang == 'semua' ? 'selected' : ''; ?>>Semua</option>
                    <option value="habis_pakai" <?php echo $jenis_barang == 'habis_pakai' ? 'selected' : ''; ?>>Habis Pakai</option>
                    <option value="tidak_habis_pakai" <?php echo $jenis_barang == 'tidak_habis_pakai' ? 'selected' : ''; ?>>Tidak Habis Pakai</option>
                </select>
                <label for="filter_stok">Stok:</label>
                <select name="filter_stok" id="filter_stok" onchange="this.form.submit()">
                    <option value="semua" <?php echo $filter_stok == 'semua' ? 'selected' : ''; ?>>Semua</option>
                    <option value="kosong" <?php echo $filter_stok == 'kosong' ? 'selected' : ''; ?>>Stok Kosong</option>
                    <option value="ada" <?php echo $filter_stok == 'ada' ? 'selected' : ''; ?>>Stok Tersedia</option>
                </select>
                <label for="filter_status">Status:</label>
                <select name="filter_status" id="filter_status" onchange="this.form.submit()">
                    <option value="semua" <?php echo $filter_status == 'semua' ? 'selected' : ''; ?>>Semua</option>
                    <option value="tersedia" <?php echo $filter_status == 'tersedia' ? 'selected' : ''; ?>>Tersedia</option>
                    <option value="dipinjam" <?php echo $filter_status == 'dipinjam' ? 'selected' : ''; ?>>Dipinjam</option>
                    <option value="rusak" <?php echo $filter_status == 'rusak' ? 'selected' : ''; ?>>Rusak</option>
                </select>
            </form>
            <a href="#" class="btn-print" onclick="window.print()"><i class="bi bi-printer"></i> Cetak</a>
        </div>
    </div>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">No.</th>
                <th style="text-align: center;">Nama Barang</th>
                <th style="text-align: center;">Jenis Barang</th>
                <th style="text-align: center;">Departemen</th>
                <?php if ($filter_stok != 'semua' && $jenis_barang != 'tidak_habis_pakai') : ?>
                    <th style="text-align: center;">Stok</th>
                <?php endif; ?>
                <?php if ($filter_status != 'semua' && $jenis_barang != 'habis_pakai') : ?>
                    <th style="text-align: center;">Status</th>
                <?php endif; ?>
                <?php if ($filter_stok == 'semua' && $filter_status == 'semua') : ?>
                    <th style="text-align: center;">Stok/Status</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $result = mysqli_query($koneksi, $query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $stok = ($row['jenis_barang'] == 'habis_pakai') ? $row['stok'] . ' unit' : '-';
                    $status = ($row['jenis_barang'] == 'tidak_habis_pakai') ? ($row['status'] ?? 'Tersedia') : '-';
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $no++; ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                        <td style="text-align: center;"><?php echo $row['jenis_barang'] == 'habis_pakai' ? 'Habis Pakai' : 'Tidak Habis Pakai'; ?></td>
                        <td style="text-align: center;"><?php echo htmlspecialchars($row['nama_departemen']); ?></td>
                        <?php if ($filter_stok != 'semua' && $jenis_barang != 'tidak_habis_pakai') : ?>
                            <td style="text-align: center;"><?php echo htmlspecialchars($stok); ?></td>
                        <?php endif; ?>
                        <?php if ($filter_status != 'semua' && $jenis_barang != 'habis_pakai') : ?>
                            <td style="text-align: center;"><?php echo htmlspecialchars($status); ?></td>
                        <?php endif; ?>
                        <?php if ($filter_stok == 'semua' && $filter_status == 'semua') : ?>
                            <td style="text-align: center;"><?php echo htmlspecialchars($row['jenis_barang'] == 'habis_pakai' ? $stok : $status); ?></td>
                        <?php endif; ?>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo 4 + ($filter_stok != 'semua' && $jenis_barang != 'tidak_habis_pakai' ? 1 : 0) + ($filter_status != 'semua' && $jenis_barang != 'habis_pakai' ? 1 : 0); ?>" style="text-align: center;">Tidak ada data untuk kriteria ini.</td>
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