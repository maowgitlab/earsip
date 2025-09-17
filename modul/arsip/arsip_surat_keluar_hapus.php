<?php
include "config/koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0 && $_SESSION['username'] == 'sekretariat') {
    // Ambil nama file untuk dihapus
    $query = "SELECT file FROM tbl_arsip_keluar WHERE id_arsip_keluar=$id";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_array($result);
    
    if ($data['file']) {
        $file_path = "file/" . $data['file'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }

    // Hapus data dari database
    $query = "DELETE FROM tbl_arsip_keluar WHERE id_arsip_keluar=$id";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Hapus Data Sukses'); window.location='?halaman=surat_keluar';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');</script>";
    }
} else {
    echo "<script>alert('Akses ditolak atau ID tidak valid'); window.location='?halaman=surat_keluar';</script>";
}
?>