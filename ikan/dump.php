<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NilaCare - Sistem Pakar Diagnosa Penyakit Ikan Nila</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --lightblue: #f6f9fc;
            --blue: #3498db;
        }

        a, a:hover { color: inherit; }
        a:hover { text-decoration: none; }
        .bg-lightblue { background: var(--lightblue); }
        .bg-blue { background: var(--blue); }
        .text-blue { color: var(--blue); }
        .container-fluid-max { max-width: 1440px; }
        .cover { background: no-repeat center/cover; }
        .p-15 { padding: 15px; }

        .scroll .page-header { background: var(--blue); }
        .scroll .hero { transform: scale(0.98); }

        .page-header {
            transition: background 0.5s ease-in-out;
            background: var(--blue);
        }
        .page-header .navbar { padding: 1rem 0; }
        .page-header .navbar-toggler { border-color: var(--white); }

        .hero {
           
            background-blend-mode: overlay;
            transition: transform 0.5s ease-in-out;
        }
        .hero::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(
                rgba(0, 0, 0, 0.5) 0,
                rgba(0, 0, 0, 0.3) 50%,
                rgba(0, 0, 0, 0.1) 100%
            );
        }
        .hero .container-fluid { z-index: 10; }

        .page-footer .footer-links { text-align: right; }

        @media screen and (max-width: 991px) {
            .page-header { background: var(--blue); }
        }

        @media screen and (max-width: 767px) {
            .page-footer .footer-child { text-align: center; }
        }

        #gejalaTable th, #gejalaTable td {
            vertical-align: middle;
        }
    </style>
</head>
<body>
<header class="fixed-top page-header">
  <div class="container-fluid container-fluid-max">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" id="homeku" href="#home">NilaCare</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-lg-between" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" id="diagnosisku" href="#diagnosis">Diagnosa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="infoku" href="#info">Info</a>
          </li>
        </ul>
        <div class="text-white">
          <a href="admin/index.php">
            <i class="fas fa-sign-in-alt"></i>
            <div class="d-none d-xl-inline">Login Admin</div>
          </a>
        </div>
      </div>
    </nav>
  </div>
</header>

<main>
<section id="home" class="d-flex align-items-center position-relative cover hero" style="background-image:url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/162656/cappadocia.jpg);">
    <div class="container-fluid container-fluid-max" id="ho">
      <div class="row">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <h1 class="text-white">Diagnosa Penyakit Ikan Nila dengan Mudah!</h1>
          <div class="mt-3">
            <button class="btn bg-white text-blue mr-2" id="diagnosisBtn">Mulai Diagnosa</button>
            <button class="btn bg-white text-blue" id="infoBtn">Pelajari Lebih Lanjut</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="diagnosis" class="diagnosis py-5" style="display: none;">
  <div class="container-fluid container-fluid-max">
    <div class="row">
      <div class="col-12 pb-4">
        <h2 class="text-blue">Diagnosa Penyakit Ikan Nila</h2>
      </div>
      <div class="col-12">
        <form id="diagnosisForm">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" required></textarea>
          </div>
          <table id="gejalaTable" class="table table-striped">
            <!-- ... (kode table tetap sama) ... -->
          </table>
          <button type="submit" class="btn bg-blue text-white mt-3">Cek Diagnosa</button>
        </form>
      </div>
    </div>
  </div>
</section>

  <section id="info" class="info bg-lightblue py-5 mt-lg-5">
    <div class="container-fluid container-fluid-max">
      <div class="row text-center">
        <div class="col-12 pb-4">
          <h2 class="text-blue">Informasi Penyakit Ikan Nila</h2>
        </div>
        <!-- Tambahkan informasi penyakit ikan nila di sini -->
      </div>
    </div>
  </section>
</main>

<footer class="py-5 page-footer">
  <div class="container-fluid container-fluid-max">
    <div class="row">
      <div class="col-12 col-md-6 footer-child copyright">
        NilaCare © 2024 All Rights Reserved
      </div>
      <div class="col-12 col-md-6 footer-child footer-links">
        <a href="" class="mr-3">Kebijakan Privasi</a>
        <a href="">FAQ</a>
      </div>
    </div>
  </div>
</footer>

<!-- Modal untuk menampilkan hasil diagnosa -->
<div class="modal fade" id="hasilModal" tabindex="-1" aria-labelledby="hasilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hasilModalLabel">Hasil Diagnosa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="hasilDiagnosa">
        <!-- Hasil diagnosa akan ditampilkan di sini -->
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Fungsi untuk memuat gejala dari server
    function loadGejala() {
        $.getJSON('get_gejala.php', function(data) {
            var gejalaTableBody = $('#gejalaTable tbody');
            $.each(data, function(index, gejala) {
                gejalaTableBody.append(`
                    <tr>
                        <td>${gejala.id}: ${gejala.deskripsi}</td>
                        <td>
                            <input class="form-check-input" type="radio" name="${gejala.id}" id="${gejala.id}_ya" value="1" required>
                        </td>
                        <td>
                            <input class="form-check-input" type="radio" name="${gejala.id}" id="${gejala.id}_tidak" value="0" required>
                        </td>
                    </tr>
                `);
            });
        });
    }

    // Memuat gejala saat halaman dimuat
    // loadGejala();
    $('#diagnosis').hide();
    $('#info').hide();
    $('#home').hide();
    // $('#ho').hide();
    $('#home').addClass('vh-100');

    $('#diagnosisBtn').on('click', function() {
        $('#home').hide();
        $('#diagnosis').show();
        $('#info').hide();
        $('#diagnosisku').click();
        $('#home').removeClass('vh-100');
        $('#ho').hide();
        loadGejala(); // Load gejala saat tombol diklik
    });

    
    $('#infoBtn').on('click', function() {
        $('#home').hide();
        $('#diagnosis').hide();
        $('#info').show();
        $('#infoku').click();
        $('#home').removeClass('vh-100');
        $('#ho').hide();
        loadInfoPenyakit(); // Fungsi baru untuk memuat info penyakit
    });

    $('#diagnosisku').on('click', function() {
        $('#home').hide();
        $('#diagnosis').show();
        $('#info').hide();
        $('#home').removeClass('vh-100');
        $('#ho').hide();
        // loadGejala(); // Load gejala saat tombol diklik
    });

    $('#infoku').on('click', function() {
        $('#home').hide();
        $('#diagnosis').hide();
        $('#info').show();
        $('#home').removeClass('vh-100');
        $('#ho').hide();
        loadInfoPenyakit(); // Fungsi baru untuk memuat info penyakit
    });

    $('#homeku').on('click', function() {
        $('#home').show();
        $('#diagnosis').hide();
        $('#info').hide();
        $('#home').addClass('vh-100');
        $('#ho').show();
        // loadGejala(); // Load gejala saat tombol diklik
    });


    function loadInfoPenyakit() {
        $.getJSON('get_penyakit.php', function(data) {
            var infoContainer = $('#info .row');
            infoContainer.empty(); // Bersihkan konten sebelumnya
            infoContainer.append('<div class="col-12 pb-4"><h2 class="text-blue">Informasi Penyakit Ikan Nila</h2></div>');
            
            $.each(data, function(index, penyakit) {
                infoContainer.append(`
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title text-blue">${penyakit.nama}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">Penyebab:</h6>
                                <p class="card-text">${penyakit.penyebab}</p>
                                <h6 class="card-subtitle mb-2 text-muted">Pengendalian:</h6>
                                <p class="card-text">${penyakit.pengendalian}</p>
                            </div>
                        </div>
                    </div>
                `);
            });
        });
    }

    // Handler untuk form submit
    $('#diagnosisForm').on('submit', function(e) {
        e.preventDefault();
        var data = {
        nama: $('#nama').val(),
        alamat: $('#alamat').val()
    };
    $('#gejalaTable input:radio:checked').each(function() {
        data[$(this).attr('name')] = $(this).val();
    });

        $.ajax({
            url: 'diagnosa.php',
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                // Tampilkan hasil diagnosa dalam modal
                var hasil = '<h4>Hasil Diagnosa:</h4>' +
                    '<p>Kemungkinan penyakit: <strong>' + response.penyakit + '</strong></p>' +
                    '<p>Persentase keyakinan: <strong>' + response.persentase + '%</strong></p>' +
                    '<h4>Penyebab:</h4>' +
                    '<p>' + response.penyebab + '</p>' +
                    '<h4>Pengendalian:</h4>' +
                    '<ul>';
                
                var pengendalian = response.pengendalian.split('\n');
                pengendalian.forEach(function(item) {
                    hasil += '<li>' + item + '</li>';
                });
                
                hasil += '</ul>' +
                    '<h4>Langkah-langkah Perhitungan:</h4>';
                
                for (var penyakit in response.langkah_perhitungan) {
                    hasil += '<h5>' + penyakit + ':</h5><ul>';
                    response.langkah_perhitungan[penyakit].forEach(function(step) {
                        hasil += '<li>' + step + '</li>';
                    });
                    hasil += '</ul>';
                }

                // Tambahkan bagian rumus
                hasil += '<h4>Rumus yang digunakan:</h4><ul>';
                response.rumus.forEach(function(rumus) {
                    hasil += '<li>' + rumus + '</li>';
                });
                hasil += '</ul>';

                $('#hasilDiagnosa').html(hasil);
                var myModal = new bootstrap.Modal(document.getElementById('hasilModal'));
                myModal.show();
                hasil += '<button id="cetakPDF" class="btn bg-blue text-white mt-3">Cetak PDF</button>';

                $('#hasilDiagnosa').html(hasil);
                var myModal = new bootstrap.Modal(document.getElementById('hasilModal'));
                myModal.show();

                // Event listener untuk tombol cetak PDF
                $('#cetakPDF').on('click', function() {
                    window.open('cetak_pdf.php?id=' + response.id, '_blank');
                });
            },
            error: function(xhr, status, error) {
                console.error("Error: " + error);
                alert("Terjadi kesalahan saat memproses permintaan.");
            }
        });
    });


    // Smooth scroll untuk navigasi
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
});
</script>
</body>
</html>