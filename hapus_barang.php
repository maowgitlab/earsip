<?php
session_start();
include "config/koneksi.php";

if (!isset($_SESSION['username']) || $_SESSION['username'] != 'kadisdag') {
    header('location:index.php');
    exit();
}

$id_barang = mysqli_escape_string($koneksi, $_GET['id']);
$query = "DELETE FROM tbl_barang WHERE id_barang = '$id_barang'";
if (mysqli_query($koneksi, $query)) {
    echo "<script>alert('Barang berhasil dihapus!'); window.location='inventaris.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus barang!'); window.location='inventaris.php';</script>";
}
?>