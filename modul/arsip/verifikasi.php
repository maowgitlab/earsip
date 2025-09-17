<?php 

$id = $_GET['id'];
$tglSekarang = date('Y-m-d');

$verifikasi = mysqli_query($koneksi, "UPDATE tbl_arsip SET status = '1', tanggal_diterima = '$tglSekarang' WHERE id_arsip = '$id'");

if ($verifikasi) {
    echo "<script>
            alert('Verifikasi Data Sukses');
            document.location='?halaman=arsip_surat';
            </script>";
}