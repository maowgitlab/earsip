<?php session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>E-Inventarsip | Dinas Perdagangan Provinsi Kalimantan Selatan</title>
    <style>
      @media print {
        .no-print {
          display: none;
        }
      }
      .search-container {
        position: relative;
      }
      .suggestions {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1000;
        background: white;
        border: 1px solid #ccc;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      }
      .suggestion-item {
        padding: 8px;
        cursor: pointer;
      }
      .suggestion-item:hover {
        background-color: #f0f0f0;
      }
    </style>

  </head>
  <body>
    <!-- Awal Nav / Menu -->
    <nav class="navbar navbar-expand-lg navbar-light bg-warning">
      <a class="navbar-brand" href="?">E-Inventarsip Dinas Perdagangan</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="?">Beranda <span class="sr-only">(current)</span></a>
          </li>
          <?php if($_SESSION['username'] == 'kadisdag') : ?>
            <li class="nav-item">
              <a class="nav-link" href="?halaman=departemen">Departemen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?halaman=pegawai">Pegawai</a>
            </li>
            <li class="nav-item">
              <?php 
                $jumlah_arsip = mysqli_num_rows(mysqli_query($koneksi, "SELECT status FROM tbl_arsip WHERE status = '0'"));
              ?>
              <a class="nav-link" href="?halaman=arsip_surat">Surat Masuk<span class="badge badge-danger"><?=$jumlah_arsip?></span></a>
            </li>
            <li class="nav-item">
              <?php 
                $jumlahKeluar = mysqli_num_rows(mysqli_query($koneksi, "SELECT status FROM tbl_arsip_keluar WHERE status = '0'"));
              ?>
              <a class="nav-link" href="?halaman=surat_keluar">Surat Keluar <span class="badge badge-danger"><?=$jumlahKeluar?></span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Laporan
              </a>
              <div class="dropdown-menu bg-warning" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="?halaman=cetak_pegawai">Laporan Data Pegawai</a>
                <a class="dropdown-item" href="?halaman=cetak_departemen">Laporan Data Departemen</a>
                <a class="dropdown-item" href="?halaman=cetak_pengirim_surat">Laporan Data Pengirim Surat</a>
                <a class="dropdown-item" href="?halaman=cetak_arsip">Laporan Surat Masuk</a>
                <a class="dropdown-item" href="?halaman=cetak_surat_keluar">Laporan Surat Keluar</a>
                <!-- <div class="dropdown-divider"></div> -->
                <!-- <a class="dropdown-item" href="?halaman=cetak_semua_laporan">Cetak Semua Laporan</a> -->
              </div>
            </li>
          <?php elseif($_SESSION['username'] == 'sekretariat') : ?>
            <li class="nav-item">
              <a class="nav-link" href="?halaman=arsip_surat">Surat Masuk</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?halaman=surat_keluar">Surat Keluar</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="?halaman=pengirim_surat">Pengirim Surat</a>
            </li>
          <?php endif; ?>
        </ul>
        <form class="form-inline my-2 my-lg-0 search-container" action="?halaman=arsip_surat" method="GET">
          <input type="hidden" name="halaman" value="arsip_surat">
          <input class="form-control mr-sm-2" type="search" name="search" id="searchInput" autocomplete="off" placeholder="Cari No Surat atau Perihal" aria-label="Search">
          <div class="suggestions" id="suggestions"></div>
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cari</button>
        </form>
      </div>
    </nav>
    <!--Akhir Nav / Menu -->
    <!-- Awal Container -->
    <div class="container">