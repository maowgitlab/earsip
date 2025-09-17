<?php
    //panggil function.php untuk upload file
    include "config/function.php";

     //Uji Jika klik tombol edit / hapus
    if(isset($_GET['hal']))
    {

      if ($_GET['hal'] == "edit") 
      {
        
        //tampilkan data yang akan diedit
      $tampil = mysqli_query($koneksi, " SELECT 
                      tbl_pegawai.*,
                      tbl_departemen.nama_departemen
                    FROM 
                      tbl_pegawai, tbl_departemen
                    WHERE 
                      tbl_pegawai.departemen = tbl_departemen.id_departemen
                      and tbl_pegawai.id_pegawai= '$_GET[id]'");
      $data = mysqli_fetch_array($tampil);
      if($data)
      {
        //jika data ditemukan, maka data ditampung ke dalam variabel
        $vnama = $data['nama'];
        $vnip = $data['nip'];
        $vjabatan = $data['jabatan'];
        $vjenkel = $data['jenis_kelamin'];
        $vdepartemen = $data['departemen'];
      }

    }
    elseif ($_GET['hal'] == 'hapus') 
    {
        $hapus = mysqli_query($koneksi, "DELETE FROM tbl_pegawai WHERE id_pegawai= '$_GET[id]'");
      if ($hapus){
        echo "<script>
                alert('Hapus Data Sukses');
                document.location='?halaman=pegawai';
                </script>";
      }
    }
      

    }

    //uji jika tombol simpan diklik
    if(isset($_POST['bsimpan']))
    {

        //pengujian apakah data akan diedit / simpan baru
        if (@$_GET['hal'] == "edit"){

          $ubah = mysqli_query($koneksi, "UPDATE tbl_pegawai SET 
                                         nama = '$_POST[nama]', 
                                         nip = '$_POST[nip]',
                                         jabatan = '$_POST[jabatan]',
                                         jenis_kelamin = '$_POST[jenkel]',
                                         departemen = '$_POST[departemen]'
                                        WHERE id_pegawai = '$_GET[id]' ");
          if($ubah)
      {
          echo "<script>
                alert('Ubah Data Sukses');
                document.location='?halaman=pegawai';
                </script>";
      }
      else
      {
          echo "<script>
                alert('Ubah Data GAGAL!!');
                document.location='?halaman=pegawai';
                </script>";
      }
        }
        else
        {
          //perintah simpan data baru
          //simpan data
          $tgl = date('Y-m-d');
          $simpan = mysqli_query($koneksi,"INSERT INTO tbl_pegawai 
                                      VALUES (   null, 
                                                  '$_POST[nama]', 
                                                  '$_POST[nip]',
                                                  '$_POST[jabatan]',
                                                  '$_POST[departemen]',
                                                  '$_POST[jenkel]',
                                                  '$tgl'
                                                ) ");
      if($simpan)
      {
          echo "<script>
                alert('Simpan Data Sukses');
                document.location='?halaman=pegawai';
                </script>";
      }else
      {
        echo "<script>
                alert('Simpan Data GAGAL!!');
                document.location='?halaman=pegawai';
                </script>";
        }
      }



    }

   

?>


<div class="card mt-4">
  <div class="card-header bg-warning">
    Form Data Pegawai
  </div>
  <div class="card-body">
   <form method="post" action="" enctype="multipart/form-data" >
  <div class="form-group">
    <label for="nama">Nama</label>
    <input type="text" class="form-control" id="nama" name="nama" value="<?=@$vnama?>"
    required oninvalid="this.setCustomValidity('Data tidak boleh kosong!')" oninput="setCustomValidity('')">
  </div>
  <div class="form-group">
    <label for="nip">NIP</label>
    <input type="text" class="form-control" id="nip" name="nip" value="<?=@$vnip?>"
   required oninvalid="this.setCustomValidity('Data tidak boleh kosong!')" oninput="setCustomValidity('')">
  </div>
  <div class="form-group">
    <label for="jenkel">Jenis Kelamin</label>
    <select name="jenkel" id="jenkel" class="form-control">
      <option disabled selected> Pilih Jenis Kelamin</option>
      <option value="L" <?= @$vjenkel == 'L' ? "selected" : null ?>>Laki-laki</option>
      <option value="P" <?= @$vjenkel == 'P' ? "selected" : null ?>>Perempuan</option>
    </select>
  </div>
  <div class="form-group">
    <label for="jabatan">Jabatan</label>
    <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?=@$vjabatan?>"
   required oninvalid="this.setCustomValidity('Data tidak boleh kosong!')" oninput="setCustomValidity('')">
  </div>
  <div class="form-group">
    <label for="id_departemen">Departemen</label>
    <select class="form-control" name="departemen">  
        <option value="<?=@$vid_departemen?>"><?=@$vnama_departemen?></option>
        <?php
            $tampil = mysqli_query($koneksi, "SELECT * from tbl_departemen order by nama_departemen asc");
        ?>
        <?php foreach($tampil as $data) : ?>
              <option value = "<?= $data['id_departemen'] ?>" <?= @$_GET['hal'] == 'edit' && $data['id_departemen'] == $vdepartemen ? "selected" : null ?>> <?= $data['nama_departemen'] ?> </option>
        <?php endforeach; ?>
    </select>
  </div>
  <button type="submit" name="bsimpan" class="btn btn-success">Simpan</button>
  <button type="reset" name="bbatal" class="btn btn-danger">Batal</button>
</form> 
  </div>
</div>