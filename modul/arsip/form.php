<?php
    //panggil function.php untuk upload file
    include "config/function.php";

     //Uji Jika klik tombol edit / hapus
    if(isset($_GET['hal']))
    {

      if ($_GET['hal'] == "edit") 
      {
        
        //tampilkan data yang akan diedit
      $tampil = mysqli_query($koneksi, "  SELECT 
                      tbl_arsip.*,
                      tbl_departemen.nama_departemen,
                      tbl_pengirim_surat.nama_pengirim, tbl_pengirim_surat.no_hp
                    FROM 
                      tbl_arsip, tbl_departemen, tbl_pengirim_surat
                    WHERE 
                      tbl_arsip.id_departemen = tbl_departemen.id_departemen
                      and tbl_arsip.id_pengirim = tbl_pengirim_surat.id_pengirim
                      and tbl_arsip.id_arsip= '$_GET[id]' ");
      $data = mysqli_fetch_array($tampil);
      if($data)
      {
        //jika data ditemukan, maka data ditampung ke dalam variabel
        $vno_surat = $data['no_surat'];
        $vtanggal_surat = $data['tanggal_surat'];
        $vtanggal_diterima = $data['tanggal_diterima'];
        $vperihal = $data['perihal'];
        $vid_departemen = $data['id_departemen'];
        $vnama_departemen = $data['nama_departemen'];
        $vid_pengirim = $data['id_pengirim'];
        $vnama_pengirim = $data['nama_pengirim'];
        $vfile = $data['file'];
      }

    }
    elseif ($_GET['hal'] == 'hapus') 
    {
        $arsip = mysqli_query($koneksi, "SELECT file FROM tbl_arsip WHERE id_arsip= '$_GET[id]'");
        $data = mysqli_fetch_array($arsip);
        $file = $data['file'];
        if ($file) {
          unlink("file/" . $file);
        }
        $hapus = mysqli_query($koneksi, "DELETE FROM tbl_arsip WHERE id_arsip= '$_GET[id]'");
      if ($hapus){
        echo "<script>
                alert('Hapus Data Sukses');
                document.location='?halaman=arsip_surat';
                </script>";
      }
    }
      

    }

    //uji jika tombol simpan diklik
// Uji jika tombol simpan diklik
if (isset($_POST['bsimpan'])) {
  // Pengujian apakah data akan diedit / simpan baru
  if (@$_GET['hal'] == "edit") {
        // Perintah edit data
        $arsipQuery = mysqli_query($koneksi, "SELECT file FROM tbl_arsip WHERE id_arsip = '$_GET[id]'");
        $arsipData = mysqli_fetch_array($arsipQuery);
        $fileLama = $arsipData['file'];

        // Cek apakah file baru diunggah
        if ($_FILES['file']['error'] === 4) {
            $file = $fileLama; // Gunakan file lama jika tidak ada file baru
        } else {
            $file = upload(); // Upload file baru
            if ($fileLama && file_exists("file/" . $fileLama)) {
                unlink("file/" . $fileLama); // Hapus file lama
            }
        }

      $ubah = mysqli_query($koneksi, "UPDATE tbl_arsip SET 
                                       no_surat = '$_POST[no_surat]', 
                                       tanggal_surat = '$_POST[tanggal_surat]',
                                       tanggal_diterima = '$_POST[tanggal_diterima]',
                                       perihal = '$_POST[perihal]',
                                       id_departemen = '$_POST[id_departemen]',
                                       id_pengirim = '$_POST[id_pengirim]',
                                       file = '$file'
                                        WHERE id_arsip = '$_GET[id]' ");
      if ($ubah) {
          // Ambil email dan nama pengirim berdasarkan id_pengirim
          $pengirimQuery = mysqli_query($koneksi, "SELECT nama_pengirim, email FROM tbl_pengirim_surat WHERE id_pengirim = '$_POST[id_pengirim]'");
          $pengirimData = mysqli_fetch_array($pengirimQuery);

          // if ($pengirimData) {
          //     $recipientName = $pengirimData['nama_pengirim'];
          //     $recipientEmail = $pengirimData['email'];
          //     $attachmentPath = "file/" . $file; // Path ke file yang akan dilampirkan

          //     // Kirim notifikasi email setelah data berhasil diperbarui
          //     kirimEmail(
          //         $recipientEmail,
          //         $recipientName,
          //         'Data Arsip Diperbarui',
          //         '<h1>Data Arsip Berhasil Diperbarui</h1>
          //         <p>Data dengan nomor surat ' . $_POST['no_surat'] . ' telah berhasil diperbarui.</p>',
          //         $attachmentPath
          //     );
          // }
          echo "<script>
              alert('Ubah Data Sukses');
              document.location='?halaman=arsip_surat';
              </script>";
      } else {
          echo "<script>
              alert('Ubah Data GAGAL!!');
              document.location='?halaman=arsip_surat';
              </script>";
      }
  } else {
      // Perintah simpan data baru
      $file = upload();
      $simpan = mysqli_query($koneksi, "INSERT INTO tbl_arsip (id_arsip, no_surat, tanggal_surat, tanggal_diterima, perihal, id_departemen, id_pengirim, file)
                                    VALUES (null, '$_POST[no_surat]', '$_POST[tanggal_surat]', '$_POST[tanggal_diterima]', '$_POST[perihal]', '$_POST[id_departemen]', '$_POST[id_pengirim]', '$file') ");
      if ($simpan) {
          // Ambil email dan nama pengirim berdasarkan id_pengirim
          $pengirimQuery = mysqli_query($koneksi, "SELECT nama_pengirim, email FROM tbl_pengirim_surat WHERE id_pengirim = '$_POST[id_pengirim]'");
          $pengirimData = mysqli_fetch_array($pengirimQuery);

          // if ($pengirimData) {
          //     $recipientName = $pengirimData['nama_pengirim'];
          //     $recipientEmail = $pengirimData['email'];
          //     $attachmentPath = "file/" . $file; // Path ke file yang akan dilampirkan

          //     // Kirim notifikasi email setelah data berhasil disimpan
          //     kirimEmail(
          //         $recipientEmail,
          //         $recipientName,
          //         'Data Arsip Baru Ditambahkan',
          //         '<h1>Data Arsip Baru Berhasil Disimpan</h1>
          //         <p>Data dengan nomor surat ' . $_POST['no_surat'] . ' telah berhasil ditambahkan.</p>',
          //         $attachmentPath
          //     );
          // }

          echo "<script>
              alert('Simpan Data Sukses');
              document.location='?halaman=arsip_surat';
              </script>";
      } else {
          echo "<script>
              alert('Simpan Data GAGAL!!');
              document.location='?halaman=arsip_surat';
              </script>";
      }
  }
}

   

?>


<div class="card mt-4">
  <div class="card-header bg-warning">
    Form Data Arsip Surat
  </div>
  <div class="card-body">
   <form method="post" action="" enctype="multipart/form-data" >
  <div class="form-group">
    <label for="no_surat">No. Surat</label>
    <input type="text" class="form-control" id="no_surat" name="no_surat" value="<?=@$vno_surat?>"
    required
       oninvalid="this.setCustomValidity('Data tidak boleh kosong!')"
       oninput="this.setCustomValidity('')">
  </div>
  <div class="form-group">
    <label for="tanggal_surat">Tanggal Surat</label>
    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" value="<?=@$vtanggal_surat?>">
  </div>
  <div class="form-group">
    <label for="tanggal_diterima">Tanggal Diterima</label>
    <input type="date" class="form-control" id="tanggal_diterima" name="tanggal_diterima" value="<?=@$vtanggal_diterima?>">
  </div>
  <div class="form-group">
    <label for="perihal">Perihal</label>
    <input type="text" class="form-control" id="perihal" name="perihal" value="<?=@$vperihal?>">
  </div>
  <div class="form-group">
    <label for="id_departemen">Departemen / Tujuan</label>
    <select class="form-control" name="id_departemen">  
        <option value="<?=@$vid_departemen?>"><?=@$vnama_departemen?></option>
        <?php
            $tampil = mysqli_query($koneksi, "SELECT * from tbl_departemen order by nama_departemen asc");
            while($data = mysqli_fetch_array($tampil)){
              echo "<option value = '$data[id_departemen]'> $data[nama_departemen] </option> ";
            }

        ?>
    </select>
  </div>
   <div class="form-group">
    <label for="id_pengirim">Pengirim Surat</label>
    <select class="form-control" name="id_pengirim">  
        <option value="<?=@$vid_pengirim?>"><?=@$vnama_pengirim?></option>
        <?php
            $tampil = mysqli_query($koneksi, "SELECT * from tbl_pengirim_surat order by nama_pengirim asc");
            while($data = mysqli_fetch_array($tampil)){
              echo "<option value = '$data[id_pengirim]'> $data[nama_pengirim] </option> ";
            }
            
        ?>
    </select>
  </div>
  <div class="form-group">
    <label for="file">Pilih File</label>
    <input type="file" class="form-control" id="file" name="file" value="<?=@$vfile?>">
  </div>
  <button type="submit" name="bsimpan" class="btn btn-success">Simpan</button>
  <button type="reset" name="bbatal" class="btn btn-danger">Batal</button>
</form> 
  </div>
</div>