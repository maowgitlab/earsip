<?php
session_start();
include "config/koneksi.php";

// contoh login sederhana, bisa dikembangkan lagi

// password diamankan dengan enkripsi kriptografi md5
@$pass = md5($_POST['password']);

// mysqli_escape_string fungsinya untuk mengamankan karakter aneh yang diinputkan user, seperti sql injection

@$username = mysqli_escape_string($koneksi, $_POST['username']);
@$password = mysqli_escape_string($koneksi, $pass);

$login = mysqli_query($koneksi, "SELECT * from tbl_user
								 WHERE username='$username' and password = '$password' ");
$data = mysqli_fetch_array($login);
if ($data) 
{
	$_SESSION['id_user'] = $data['id_user'];
	$_SESSION['username'] = $data['username'];
	header('location:menu.php');
}
else
{
	echo "<script>
			alert('Maaf Login GAGAL pastikan Username dan Password Anda benar!');
			document.location='index.php';
		  </script>";
}

?>