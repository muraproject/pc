<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        body {
            color: #6F8BA4;
            
        }
        .section {
            padding: 100px 0;
            position: relative;
        }
        .gray-bg {
            background-color: #f5f5f5;
        }
        img {
            max-width: 100%;
        }
        img {
            vertical-align: middle;
            border-style: none;
        }
        .about-text h3 {
            font-size: 45px;
            font-weight: 700;
            margin: 0 0 6px;
        }
        @media (max-width: 767px) {
            .about-text h3 {
                font-size: 35px;
            }
        }
        .about-text h6 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        @media (max-width: 767px) {
            .about-text h6 {
                font-size: 18px;
            }
        }
        .about-text p {
            font-size: 18px;
            max-width: 450px;
        }
        .about-text p mark {
            font-weight: 600;
            color: #20247b;
        }
        .about-list {
            padding-top: 10px;
        }
        .about-list .media {
            padding: 5px 0;
        }
        .about-list label {
            color: #20247b;
            font-weight: 600;
            width: 88px;
            margin: 0;
            position: relative;
        }
        .about-list label:after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 0;
            right: 11px;
            width: 1px;
            height: 12px;
            background: #20247b;
            transform: rotate(15deg);
            margin: auto;
            opacity: 0.5;
        }
        .about-list p {
            margin: 0;
            font-size: 15px;
        }
        @media (max-width: 991px) {
            .about-avatar {
                margin-top: 30px;
            }
        }
        .about-section .counter {
            padding: 22px 20px;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(31, 45, 61, 0.125);
        }
        .about-section .counter .count-data {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .about-section .counter .count {
            font-weight: 700;
            color: #20247b;
            margin: 0 0 5px;
        }
        .about-section .counter p {
            font-weight: 600;
            margin: 0;
        }
        mark {
            background-image: linear-gradient(rgba(252, 83, 86, 0.6), rgba(252, 83, 86, 0.6));
            background-size: 100% 3px;
            background-repeat: no-repeat;
            background-position: 0 bottom;
            background-color: transparent;
            padding: 0;
            color: currentColor;
        }
        .theme-color {
            color: #fc5356;
        }
        .dark-color {
            color: #20247b;
        }
    </style>
</head>
<body>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <!-- Container wrapper -->
        <div class="container-fluid">
            <!-- Navbar brand -->
            <a class="navbar-brand mt-2 mt-lg-0" href="/booking_restoran">
                <h5 class="pt-1">Booking Restoran</h5>
            </a>
            <!-- Toggle button -->
            <button data-mdb-button-init class="navbar-toggler" type="button" data-mdb-collapse-init data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/booking_restoran">Project</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/booking_restoran/Profil.php">Profil</a>
                    </li>
                </ul>
                <!-- Left links -->
            </div>
            <div class="float-right me-3">
                    <!-- Icon -->
                
                    <a class="text-reset me-3 text-white float-right" href="logout.php">
                        <i class="fas fa-unlock text-white"></i>
                            Logout
                    </a>
                </div>
            <!-- Collapsible wrapper -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    <section class="section about-section gray-bg" id="about">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-lg-6">
                    <div class="about-text go-to">
                        <h3 class="dark-color">About Me</h3>
                        <h6 class="theme-color lead">Mahasiswa Teknik Informatika</h6>
                        <p>Perkenalkan Nama saya Aprilia Cahyanti saya seorang mahasiswa jurusan Teknik Informatika di Universitas Muhammadiyah Ponorogo. Saat ini saya mencari jati diri, menggali potensi dalam hal apapun lebih fokus pada IT. Saat ini saya sedang belajar bahasa pemrograman C++ dan saya juga sedang belajar mengembangkan aplikasi web sederhana menggunakan HTML, CSS, dan JavaScript, serta framework.</p>
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media">
                                    <label>Nama</label>
                                    <p>APIRLIA CAHYANTI</p>
                                </div>
                                <div class="media">
                                    <label>TTL</label>
                                    <p>Pacitan, 15 April 2002</p>
                                </div>
                                <div class="media">
                                    <label>Alamat</label>
                                    <p>Pacitan</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="media">
                                    <label>Status</label>
                                    <p>Mahasiswa</p>
                                </div>
                                <div class="media">
                                    <label>Email</label>
                                    <p>aprilyacahyanti155@gmail.com</p>
                                </div>
                                <div class="media">
                                    <label>Telepon</label>
                                    <p>081450257670</p>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-4">Riwayat Pendidikan</h5>
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media">
                                    <label>SD</label>
                                    <p>SD N 2 Tegalombo (2008-2014)</p>
                                </div>
                                <div class="media">
                                    <label>SMP</label>
                                    <p>SMP N 1 Tegalombo (2014-2017)</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="media">
                                    <label>SMK</label>
                                    <p>SMK N 2 Pacitan (2017-2020)</p>
                                </div>
                                <div class="media">
                                    <label>Universitas</label>
                                    <p>Universitas Muhammadiyah Ponorogo (2020-Selesai)</p>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-4">Hobi</h5>
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media">
                                    <label>Mendengarkan Musik</label>
                                </div>
                                <div class="media">
                                    <label>Menonton Konser</label>
                                </div>
                            </div>
                        </div>
                        <h5 class="mt-4">Program yang Dikuasai</h5>
                        <div class="row about-list">
                            <div class="col-md-6">
                                <div class="media">
                                    <label>Microsoft Office</label>
                                </div>
                                <div class="media">
                                    <label>Matlab</label>
                                </div>
                                <div class="media">
                                    <label>Corel Draw</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-avatar">
                        <img src="https://bootdey.com/img/Content/avatar/avatar7.png" title="" alt="">
                    </div>
                </div>
            </div>
            <!-- <div class="counter">
                <div class="row">
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" data-to="500" data-speed="500">500</h6>
                            <p class="m-0px font-w-600">Happy Clients</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" data-to="150" data-speed="150">150</h6>
                            <p class="m-0px font-w-600">Project Completed</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" data-to="850" data-speed="850">850</h6>
                            <p class="m-0px font-w-600">Photo Capture</p>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="count-data text-center">
                            <h6 class="count h2" data-to="190" data-speed="190">190</h6>
                            <p class="m-0px font-w-600">Telephonic Talk</p>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-center text-white">
        <!-- Grid container -->
        <div class="container p-4 pb-0">
            <!-- Section: Social media -->
            <section class="mb-4">
                <!-- Facebook -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-facebook-f text-white"></i></a>
                <!-- Twitter -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-twitter text-white"></i></a>
                <!-- Google -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-google text-white"></i></a>
                <!-- Instagram -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-instagram text-white"></i></a>
                <!-- Linkedin -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-linkedin-in text-white"></i></a>
                <!-- Github -->
                <a data-mdb-ripple-init class="btn btn-outline-primary btn-floating m-1 border border-white" href="#!" role="button"><i class="fab fa-github text-white"></i></a>
            </section>
            <!-- Section: Social media -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/">A P R E L</a>
        </div>
        <!-- Copyright -->
    </footer>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
