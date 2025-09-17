<?php
include "config/koneksi.php";

$action = isset($_GET['hal']) ? $_GET['hal'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$data = [];

if ($action == 'edit' && $id > 0) {
    $query = "SELECT * FROM tbl_arsip_keluar WHERE id_arsip_keluar = $id";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_array($result);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $no_surat = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $tanggal_diterima = mysqli_real_escape_string($koneksi, $_POST['tanggal_diterima']);
    $tujuan_surat = mysqli_real_escape_string($koneksi, $_POST['tujuan_surat']);
    $id_tujuan = intval($_POST['id_tujuan']);
    $perihal = mysqli_real_escape_string($koneksi, $_POST['perihal']);
    $id_departemen = intval($_POST['id_departemen']);
    $id_pengirim = intval($_POST['id_pengirim']);
    $tanggal_kirim = !empty($_POST['tanggal_kirim']) ? mysqli_real_escape_string($koneksi, $_POST['tanggal_kirim']) : null;
    $created_at = date('Y-m-d');

    // File upload handling
    $file_name = '';
    if (!empty($_FILES['file']['name'])) {
        $file_name = time() . '_' . $_FILES['file']['name'];
        $target_file = "file/" . $file_name;
        move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
    } elseif ($action == 'edit' && !empty($data['file'])) {
        $file_name = $data['file'];
    }

    if ($action == 'edit' && $id > 0) {
        // Update query
        $query = "UPDATE tbl_arsip_keluar SET 
                  no_surat='$no_surat', 
                  tanggal_surat='$tanggal_surat', 
                  tanggal_diterima='$tanggal_diterima', 
                  tujuan_surat='$tujuan_surat', 
                  id_tujuan=$id_tujuan, 
                  perihal='$perihal', 
                  id_departemen=$id_departemen, 
                  id_pengirim=$id_pengirim, 
                  file='$file_name', 
                  tanggal_kirim=" . ($tanggal_kirim ? "'$tanggal_kirim'" : "NULL") . ", 
                  created_at='$created_at' 
                  WHERE id_arsip_keluar=$id";
    } else {
        // Insert query
        $query = "INSERT INTO tbl_arsip_keluar 
                  (no_surat, tanggal_surat, tanggal_diterima, tujuan_surat, id_tujuan, perihal, status, id_departemen, id_pengirim, file, tanggal_kirim, created_at) 
                  VALUES 
                  ('$no_surat', '$tanggal_surat', '$tanggal_diterima', '$tujuan_surat', $id_tujuan, '$perihal', '0', $id_departemen, $id_pengirim, '$file_name', " . ($tanggal_kirim ? "'$tanggal_kirim'" : "NULL") . ", '$created_at')";
    }

   if (mysqli_query($koneksi, $query)) {
    if ($action == 'edit') {
        echo "<script>alert('Ubah Data Sukses'); window.location='?halaman=surat_keluar';</script>";
    } else {
        echo "<script>alert('Simpan Data Sukses'); window.location='?halaman=surat_keluar';</script>";
    }
}}
?>

<div class="card mt-4">
    <div class="card-header bg-warning">
        <?= $action == 'edit' ? 'Edit Data Surat Keluar' : 'Form Arsip Data Surat Keluar' ?>
    </div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>No Surat</label>
                <input type="text" name="no_surat" class="form-control" value="<?= isset($data['no_surat']) ? $data['no_surat'] : '' ?>" 
                 required
                 oninvalid="this.setCustomValidity('Data tidak boleh kosong!')"
                 oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label>Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" value="<?= isset($data['tanggal_surat']) ? $data['tanggal_surat'] : '' ?>" required>
            </div>
            <div class="form-group">
                <label>Tanggal Diterima</label>
                <input type="date" name="tanggal_diterima" class="form-control" value="<?= isset($data['tanggal_diterima']) ? $data['tanggal_diterima'] : '' ?>">
            </div>
            <div class="form-group">
                <label>Tujuan Surat</label>
                <input type="text" name="tujuan_surat" class="form-control" value="<?= isset($data['tujuan_surat']) ? $data['tujuan_surat'] : '' ?>" 
                required
                oninvalid="this.setCustomValidity('Data tidak boleh kosong!')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label>Tujuan (Instansi)</label>
                <select name="id_tujuan" class="form-control" required>
                    <?php
                    $tujuan_query = mysqli_query($koneksi, "SELECT * FROM tbl_pengirim_surat");
                    while ($tujuan = mysqli_fetch_array($tujuan_query)) {
                        echo "<option value='{$tujuan['id_pengirim']}' " . (isset($data['id_tujuan']) && $data['id_tujuan'] == $tujuan['id_pengirim'] ? 'selected' : '') . ">{$tujuan['nama_pengirim']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Perihal</label>
                <input type="text" name="perihal" class="form-control" value="<?= isset($data['perihal']) ? $data['perihal'] : '' ?>" 
                required
                oninvalid="this.setCustomValidity('Data tidak boleh kosong!')"
                oninput="this.setCustomValidity('')">
            </div>
            <div class="form-group">
                <label>Departemen</label>
                <select name="id_departemen" class="form-control" required>
                    <?php
                    $dep_query = mysqli_query($koneksi, "SELECT * FROM tbl_departemen");
                    while ($dep = mysqli_fetch_array($dep_query)) {
                        echo "<option value='{$dep['id_departemen']}' " . (isset($data['id_departemen']) && $data['id_departemen'] == $dep['id_departemen'] ? 'selected' : '') . ">{$dep['nama_departemen']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pengirim (Kadisdag)</label>
                <select name="id_pengirim" class="form-control" required>
                    <?php
                    $pengirim_query = mysqli_query($koneksi, "SELECT * FROM tbl_pegawai WHERE jabatan = 'Kepala Dinas'");
                    while ($pengirim = mysqli_fetch_array($pengirim_query)) {
                        echo "<option value='{$pengirim['id_pegawai']}' " . (isset($data['id_pengirim']) && $data['id_pengirim'] == $pengirim['id_pegawai'] ? 'selected' : '') . ">{$pengirim['nama']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>File</label>
                <input type="file" name="file" class="form-control">
                <?php if ($action == 'edit' && !empty($data['file'])) : ?>
                    <small>File saat ini: <a href="file/<?= $data['file'] ?>" target="_blank"><?= $data['file'] ?></a></small>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Tanggal Kirim</label>
                <input type="date" name="tanggal_kirim" class="form-control" value="<?= isset($data['tanggal_kirim']) ? $data['tanggal_kirim'] : '' ?>">
            </div>
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="?halaman=surat_keluar" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>