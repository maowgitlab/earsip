<?php

	//buat koneksi database

	//persiapan identitas server
	$server = "localhost"; //Nama Server
	$user = "root"; //username database server
	$pass = ""; //password database server
	$database = "dbarsipp"; //Nama Database

	//koneksi database
	$koneksi = mysqli_connect($server, $user, $pass, $database) or die(mysqli_error($koneksi));
	

?>