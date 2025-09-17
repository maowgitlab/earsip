<?php

	@$halaman = $_GET['halaman'];

	if($halaman == "pegawai")
	{
		//tampilkan halaman Pegawai
		//echo "Tampil Halaman Modul Pegawai";
		include "modul/pegawai/pegawai.php";

	}
	elseif ($halaman == "pengirim_surat"){
		//tampilkan halaman pengirim surat
		include "modul/pengirim_surat/pengirim_surat.php";
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
else
{
		//echo "Tampil Halaman Home";
		include "modul/home.php";
}

?>