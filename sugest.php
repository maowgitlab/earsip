
<?php
include "config/koneksi.php";

if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($koneksi, $_GET['query']);
    $sql = "SELECT no_surat, perihal 
            FROM tbl_arsip 
            WHERE no_surat LIKE '%$query%' OR perihal LIKE '%$query%'
            LIMIT 10";
    $result = mysqli_query($koneksi, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $suggestion = htmlspecialchars($row['no_surat'] . ' - ' . $row['perihal']);
            echo "<div class='suggestion-item'>$suggestion</div>";
        }
    } else {
        echo "<div class='suggestion-item'>Tidak ada hasil</div>";
    }
} else {
    echo "<div class='suggestion-item'>Masukkan kata kunci</div>";
}
?>