</div>
<!-- Akhir Container -->

    <footer class="bg-warning text-center text-black pt-2 pb-2 mt-3 no-print"> Mutiara Nahdatun Najwana </footer>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Pooper.js, then Bootstrap JS -->
    <script src="assets/js/jquery-3.5.1.slim.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- jQuery and Bootstrap JS -->
    <script>
      $(document).ready(function() {
        let searchTimeout;
        $('#searchInput').on('input', function() {
          clearTimeout(searchTimeout);
          const query = $(this).val().trim();
          
          // Delay 300ms untuk mencegah terlalu banyak permintaan AJAX
          searchTimeout = setTimeout(function() {
            if (query.length > 0) {
              $.ajax({
                url: 'sugest.php',
                method: 'GET',
                data: { query: query },
                success: function(data) {
                  console.log('AJAX Success:', data); // Debugging
                  $('#suggestions').html(data).show();
                },
                error: function(xhr, status, error) {
                  console.error('AJAX Error:', status, error); // Debugging
                  $('#suggestions').html('<div class="suggestion-item">Terjadi kesalahan saat mencari</div>').show();
                }
              });
            } else {
              $('#suggestions').hide();
            }
          }, 300);
        });

        // Sembunyikan saran saat klik di luar
        $(document).on('click', function(e) {
          if (!$(e.target).closest('.search-container').length) {
            $('#suggestions').hide();
          }
        });

        // Arahkan ke halaman arsip_surat saat memilih saran
        $(document).on('click', '.suggestion-item', function() {
          const query = $(this).text();
          window.location.href = '?halaman=arsip_surat&search=' + encodeURIComponent(query);
        });
      });
    </script>
  </body>
</html>