<?php 
    include "config/function.php";
$id = $_GET['id'];
// Ambil email dan nama pengirim berdasarkan id_pengirim
$pengirimQuery = mysqli_query($koneksi, "SELECT nama_pengirim, email, tbl_arsip.file, tbl_arsip.no_surat FROM tbl_pengirim_surat INNER JOIN tbl_arsip ON tbl_pengirim_surat.id_pengirim = tbl_arsip.id_pengirim WHERE id_arsip = '$id'");
$pengirimData = mysqli_fetch_array($pengirimQuery);

if ($pengirimData) {
    $recipientName = $pengirimData['nama_pengirim'];
    $recipientEmail = $pengirimData['email'];
    $file = $pengirimData['file'];
    $no_surat = $pengirimData['no_surat'];

    $attachmentPath = "file/" . $file; // Path ke file yang akan dilampirkan

    // Kirim notifikasi email setelah data berhasil disimpan
    kirimEmail(
        $recipientEmail,
        $recipientName,
        'Arsip Terkirim',
        '<h1>Data Arsip Baru Berhasil Terkirim</h1>
        <p>Data dengan nomor surat ' .$no_surat . ' telah berhasil ditambahkan.</p>',
        $attachmentPath
    );
    echo "<script>
        alert('Kirim Data Sukses');
        document.location='?halaman=arsip_surat';
        </script>";
} else {
    echo "<script>
        alert('Kirim Data GAGAL!!');
        document.location='?halaman=arsip_surat';
        </script>";
}