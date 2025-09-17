<?php
include "config/koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0 && $_SESSION['username'] == 'kadisdag') {
    $query = "UPDATE tbl_arsip_keluar SET status='1', tanggal_kirim=CURDATE() WHERE id_arsip_keluar=$id";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Data berhasil diverifikasi'); window.location='?halaman=surat_keluar';</script>";
    } else {
        echo "<script>alert('Gagal memverifikasi data: " . mysqli_error($koneksi) . "');</script>";
    }
} else {
    echo "<script>alert('Akses ditolak atau ID tidak valid'); window.location='?halaman=surat_keluar';</script>";
}
?>