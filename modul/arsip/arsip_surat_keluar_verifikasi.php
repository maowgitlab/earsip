<?php
include "config/koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'setujui';

if ($id > 0 && $_SESSION['username'] == 'kadisdag') {
    if ($aksi === 'tolak') {
        $query = "UPDATE tbl_arsip_keluar SET status='2', tanggal_kirim=NULL WHERE id_arsip_keluar=$id";
        $pesan = 'Surat keluar ditolak.';
    } else {
        $query = "UPDATE tbl_arsip_keluar SET status='1', tanggal_kirim=CURDATE() WHERE id_arsip_keluar=$id";
        $pesan = 'Surat keluar disetujui.';
    }

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('$pesan'); window.location='?halaman=surat_keluar';</script>";
    } else {
        echo "<script>alert('Gagal memverifikasi data: " . mysqli_error($koneksi) . "');</script>";
    }
} else {
    echo "<script>alert('Akses ditolak atau ID tidak valid'); window.location='?halaman=surat_keluar';</script>";
}
?>