<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer menggunakan autoloader Composer
require 'vendor/autoload.php';
//persiapan function untuk upload file / foto
function upload()
{
	//deklarasikan variabel kebutuhan
	$namafile = $_FILES['file']['name'];
	$ukuranfile = $_FILES['file']['size'];
	$error = $_FILES['file']['error'];
	$tmpname = $_FILES['file']['tmp_name'];


	//cek apakah yang diupload adalah file / gambar
	$eksfilevalid = ['jpg', 'jpeg', 'png', 'pdf'];
	$eksfile = explode('.', $namafile);
	$eksfile = strtolower(end($eksfile));

	if (!in_array($eksfile, $eksfilevalid)){
		echo "<script> alert('Yang Anda Upload bukan Gambar/File PDF..!') </script>";
		return false;
	}

	// cek jika ukuran file terlalu besar
	if ($ukuranfile > 1000000){
		echo "<script> alert('Ukuran file Anda terlalu besar!') </script>";
		return false;
	}

	//jika lolos pengecekan, file siap diupload
	//generate nama file baru

	$namafilebaru = uniqid();
	$namafilebaru .= '.';
	$namafilebaru .= $eksfile;

	move_uploaded_file($tmpname, 'file/'.$namafilebaru);
	return $namafilebaru;

}

// Fungsi untuk mengirim email
function kirimEmail($recipientEmail, $recipientName, $subject, $bodyContent, $attachmentPath = null) {
    $mail = new PHPMailer(); // Instansiasi PHPMailer dengan mode exception

    try {
        // Konfigurasi Server SMTP
        $mail->isSMTP();
        $mail->Host       = 'mail.project-ku.my.id'; // Alamat server SMTP Mailtrap
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sekretariat.disperdagin@project-ku.my.id'; // Ganti dengan username Mailtrap Anda
        $mail->Password   = 'byybae150602'; // Ganti dengan password Mailtrap Anda
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enkripsi TLS
        $mail->Port       = 465; // Port SMTP Mailtrap

        // Pengirim
        $mail->setFrom('sekretariat.disperdagin@project-ku.my.id', 'Arsip Surat App');

        // Penerima
        $mail->addAddress($recipientEmail, $recipientName); // Alamat email dan nama penerima

		// Lampiran (jika ada)
		if ($attachmentPath && file_exists($attachmentPath)) {
			$mail->addAttachment($attachmentPath); // Tambahkan file sebagai lampiran
		}

        // Konten Email
        $mail->isHTML(true); // Email dalam format HTML
        $mail->Subject = $subject; // Subjek email
        $mail->Body    = $bodyContent; // Isi email

        // Kirim email
        $mail->send();
    } catch (Exception $e) {
        echo "Email tidak dapat dikirim. Error: {$e->getMessage()}";
    }
}

?>