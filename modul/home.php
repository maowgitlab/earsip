<div class="jumbotron mt-4">
  <h1 class="display-4">Selamat Datang, <?= $_SESSION['username']; ?>!</h1>
  <p class="lead">E-Inventarsip Dinas Perdagangan Provinsi Kalimantan Selatan.</p>
  <hr class="my-4">
  <?php if ($_SESSION['username'] == 'kadisdag') : ?>
    <a class="btn btn-danger btn-lg" href="menu.php" role="button">kembali</a>
  <?php else : ?>
    <a class="btn btn-danger btn-lg" href="logout.php" role="button">Logout</a>
  <?php endif; ?>
</div>