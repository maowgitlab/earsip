<div class="card mt-4">
  <div class="card-header bg-warning">
  Data Pegawai
  </div>
  <div class="card-body">
   <a href="?halaman=pegawai&hal=tambahdata" class="btn btn-info mb-3">Tambah Data</a>
   <table class="table table-borderd table-hovered table-striped">
     <tr>
       <th>No</th>
       <th>Nama</th>
       <th>NIP</th>
       <th>Jenis Kelamin</th>
       <th>Jabatan</th>
       <th>Departemen</th>
       <th>Aksi</th>
     </tr>
     <?php
          $tampil = mysqli_query($koneksi, "SELECT tbl_pegawai.*, tbl_departemen.nama_departemen from tbl_pegawai, tbl_departemen where tbl_pegawai.departemen = tbl_departemen.id_departemen order by id_pegawai desc");
          $no = 1;
          while($data = mysqli_fetch_array($tampil)) :

     ?>
     <tr>
       <td><?=$no++?></td>
       <td><?=$data['nama']?></td>
       <td><?=$data['nip']?></td>
       <td>
         <?php if($data['jenis_kelamin'] == 'L') : ?>
           <span style="text-align: center;">Laki-laki</span>
         <?php else : ?>
           <span style="text-align: center;">Perempuan</span>
         <?php endif; ?>
       </td>
       <td><?=$data['jabatan']?></td>
       <td><?=$data['nama_departemen']?></td>
       <td>
         <a href="?halaman=pegawai&hal=edit&id=<?=$data['id_pegawai']?>" class="btn btn-secondary" >Edit </a>
         <a href="?halaman=pegawai&hal=hapus&id=<?=$data['id_pegawai']?>" class="btn btn-danger" 
          onclick="return confirm ('Apakah yakin ingin menghapus data ini?')" >Hapus </a>
       </td>
     </tr>
     <?php endwhile; ?>
   </table>
  </div>
</div>