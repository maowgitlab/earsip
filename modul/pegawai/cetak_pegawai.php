<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Pegawai</title>
    <style>
        .search-form {
            margin: 20px 0;
        }
        .search-form input {
            padding: 5px;
            margin-right: 10px;
            width: 300px;
        }
        .search-form button, .print-button {
            padding: 5px 10px;
            cursor: pointer;
        }
        .print-button {
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            margin-left: 10px;
        }
        .print-button:hover {
            background: #45a049;
        }
    </style>
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

    <hr style="border: 2px double black">

    <!-- Form Pencarian -->
    <div class="search-form no-print">
        <form method="GET" action="admin.php">
            <input type="hidden" name="halaman" value="cetak_pegawai">
            <input type="text" name="search" placeholder="Cari NIP, Nama, Departemen, Jabatan, atau Jenis Kelamin" value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit">Cari</button>
            <a href="admin.php?halaman=cetak_pegawai" style="text-decoration: none; padding: 5px 10px; background: #ccc;">Reset</a>
            <button type="button" class="print-button" onclick="window.print()">Print</button>
        </form>
    </div>

    <?php
    $no = 1;
    // Query dasar
    $query = "SELECT tbl_pegawai.*, tbl_departemen.nama_departemen 
              FROM tbl_pegawai 
              JOIN tbl_departemen ON tbl_pegawai.departemen = tbl_departemen.id_departemen 
              WHERE 1=1";

    // Kondisi pencarian
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($koneksi, $_GET['search']);
        // Normalisasi pencarian untuk jenis kelamin
        $search_lower = strtolower($search);
        $jk_search = $search; // Default: gunakan input asli

        // Petakan "laki-laki" atau "perempuan" (dan variasinya) ke nilai enum
        if (strpos($search_lower, 'laki') !== false) {
            $jk_search = 'L';
        } elseif (strpos($search_lower, 'perempuan') !== false || strpos($search_lower, 'wanita') !== false) {
            $jk_search = 'P';
        }

        // Tambahkan kondisi pencarian
        $query .= " AND (tbl_pegawai.nip LIKE '%$search%' 
                        OR tbl_pegawai.nama LIKE '%$search%' 
                        OR tbl_pegawai.jenis_kelamin LIKE '%$jk_search%'
                        OR tbl_departemen.nama_departemen LIKE '%$search%' 
                        OR tbl_pegawai.jabatan LIKE '%$search%')";
    }

    $query .= " ORDER BY tbl_pegawai.id_pegawai DESC";
    $data = mysqli_query($koneksi, $query);
    ?>

    <h2 style="text-align: center; margin-top: 20px; margin-bottom: 20px">Laporan Data Pegawai</h2>
    <table width="100%" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th style="text-align: center;">No.</th>
                <th style="text-align: center;">Nama Pegawai</th>
                <th style="text-align: center;">NIP</th>
                <th style="text-align: center;">Jenis Kelamin</th>
                <th style="text-align: center;">Jabatan</th>
                <th style="text-align: center;">Departemen</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($data) > 0) : ?>
                <?php foreach ($data as $d) : ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++; ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($d['nama']); ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($d['nip']); ?></td>
                        <td style="text-align: center;">
                            <?php if ($d['jenis_kelamin'] == 'L') : ?>
                                <span>Laki-laki</span>
                            <?php else : ?>
                                <span>Perempuan</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: center;"><?= htmlspecialchars($d['jabatan']); ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($d['nama_departemen']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data ditemukan</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <span style="text-align: right; display: block; margin-top: 20px;">Banjarmasin, <?= date('d F Y'); ?></span>
    <span style="text-align: right; display: block; margin-right: 55px;">Mengetahui</span>
    <br><br>
    <span style="text-align: right; display: block; margin-top: 20px;">Kepala Dinas Perdagangan</span>
</body>

</html>