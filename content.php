<?php

	@$halaman = $_GET['halaman'];

	if($halaman == "departemen")
	{
		//tampilkan halaman Departemen
		//echo "Tampil Halaman Modul Departemen";
		include "modul/departemen/departemen.php";

	}
	elseif ($halaman == "cetak_departemen") {
		include "modul/departemen/cetak_departemen.php";
	}
	elseif ($halaman == "pengirim_surat"){
		//tampilkan halaman pengirim surat
		include "modul/pengirim_surat/pengirim_surat.php";
	}
	elseif ($halaman == "cetak_pengirim_surat") {
		//tampilkan halaman pengirim surat
		include "modul/pengirim_surat/cetak_pengirim_surat.php";
	}
	elseif($halaman == "arsip_surat")
	{
		//tampilkan halaman arsip surat
		if (@$_GET['hal'] == "tambahdata" || @$_GET['hal'] == "edit" || @$_GET['hal'] == "hapus"){
			include "modul/arsip/form.php";
		}else{
			include "modul/arsip/data.php";
		}
	}
	elseif ($halaman == "cetak_arsip") {
		include "modul/arsip/cetak_arsip.php";
	}
	elseif($halaman == "cetak_arsip_surat")
	{
		include "modul/arsip/cetak_data_arsip.php";
	}
elseif($halaman == "pegawai")
	{
		//tampilkan halaman pegawai
		if (@$_GET['hal'] == "tambahdata" || @$_GET['hal'] == "edit" || @$_GET['hal'] == "hapus"){
			include "modul/pegawai/formpegawai.php";
		}else{
			include "modul/pegawai/pegawai.php";
		}
}
elseif ($halaman == "arsip_surat_verifikasi") {
	include "modul/arsip/verifikasi.php";
}
elseif ($halaman == "arsip_surat_kirim") {
	include "modul/arsip/kirim.php";
}
elseif($halaman == "cetak_pegawai")
	{
		//tampilkan halaman cetak pegawai
		include "modul/pegawai/cetak_pegawai.php";
}
elseif($halaman == "cetak_semua_laporan")
	{
		include "modul/cetak_semua_laporan.php";
}
elseif($halaman == "cetak_surat_keluar")
	{
		include "modul/pengirim_surat/cetak_surat_keluar.php";
}
elseif($halaman == "surat_keluar")
	{
		include "modul/arsip/surat_keluar.php";
}
elseif($halaman == "tambah_keluar")
	{
		include "modul/arsip/tambah_edit_surat_keluar.php";
}
elseif($halaman == "edit_keluar")
	{
		include "modul/arsip/tambah_edit_surat_keluar.php";
}
elseif($halaman == "verifikasi_keluar")
	{
		include "modul/arsip/arsip_surat_keluar_verifikasi.php";
}
elseif($halaman == "hapus_keluar")
	{
		include "modul/arsip/arsip_surat_keluar_hapus.php";
}
elseif($halaman == "cetak_data_keluar")
	{
		include "modul/pengirim_surat/cetak_data_keluar.php";
}
else
{
		//echo "Tampil Halaman Home";
		include "modul/home.php";
}

?>