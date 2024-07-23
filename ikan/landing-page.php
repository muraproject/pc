<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HoneyDreams - Your Perfect Honeymoon Destination</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --lightblue: #f6f9fc;
            --red: #d64041;
        }

        a, a:hover { color: inherit; }
        a:hover { text-decoration: none; }
        .bg-lightblue { background: var(--lightblue); }
        .bg-red { background: var(--red); }
        .text-red { color: var(--red); }
        .container-fluid-max { max-width: 1440px; }
        .cover { background: no-repeat center/cover; }
        .p-15 { padding: 15px; }

        .scroll .page-header { background: var(--red); }
        .scroll .hero { transform: scale(0.98); }

        .page-header {
            transition: background 0.5s ease-in-out;
        }
        .page-header .navbar { padding: 1rem 0; }
        .page-header .navbar-toggler { border-color: var(--white); }

        .hero {
            background-attachment: fixed;
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

        .popular-destinations figure { margin-bottom: 30px; }
        .popular-destinations figcaption {
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.3);
        }
        .popular-destinations img {
            filter: grayscale(100%) blur(3px);
            transition: transform 0.5s, filter 0.75s;
        }
        .popular-destinations a:hover img {
            transform: scale(1.25);
            filter: none;
        }

        .page-footer .footer-links { text-align: right; }

        @media screen and (max-width: 991px) {
            .page-header { background: var(--red); }
        }

        @media screen and (max-width: 767px) {
            .page-footer .footer-child { text-align: center; }
        }
    </style>
</head>
<body>
<header class="fixed-top page-header">
  <div class="container-fluid container-fluid-max">
    <nav id="navbar" class="navbar navbar-expand-lg navbar-dark">
      <a class="navbar-brand" href="#home">HoneyDreams</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-lg-between" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#process">How It Works</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#process">info</a>
          </li>
        </ul>
        <div class="text-white">
          
          <a href="mailto:info@honeydreams.com">
            <i class="fas fa-sign-in"></i>
            <div class="d-none d-xl-inline">Login</div>
          </a>
        </div>
      </div>
    </nav>
  </div>
</header>

<main>
  <section id="home" class="d-flex align-items-center position-relative vh-100 cover hero" style="background-image:url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/162656/cappadocia.jpg);">
    <div class="container-fluid container-fluid-max">
      <div class="row">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
          <h1 class="text-white">Live an unforgettable experience in Cappadocia!</h1>
          <div class="mt-3">
            <a class="btn bg-red text-white mr-2" href="" role="button">Book Now</a>
            <a class="btn bg-red text-white" href="" role="button">Select Your Package</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="process" class="process">
    <div class="container-fluid container-fluid-max">
      <div class="row text-center py-5">
        <div class="col-12 pb-4">
          <h2 class="text-red">How It Works</h2>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x text-red"></i>
            <i class="fas fa-map-marked fa-stack-1x text-white"></i>
          </span>
          <h3 class="mt-3 text-red h4">Choose a destination</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed repudiandae.</p>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x text-red"></i>
            <i class="fas fa-plane fa-stack-1x text-white"></i>
          </span>
          <h3 class="mt-3 text-red h4">Book a flight</h3>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit sed repudiandae.</p>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x text-red"></i>
            <i class="fas fa-car fa-stack-1x text-white"></i>
          </span>
          <h3 class="mt-3 text-red h4">Rent a car</h3>
          <p>Nor again is there anyone who loves or pursues or desires to obtain pain.</p>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <span class="fa-stack fa-2x">
            <i class="fas fa-circle fa-stack-2x text-red"></i>
            <i class="fas fa-home fa-stack-1x text-white"></i>
          </span>
          <h3 class="mt-3 text-red h4">Rent an appartment</h3>
          <p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi.</p>
        </div>
        <div class="col-12 pt-3">
          <a class="btn bg-red text-white" target="_blank" href="https://en.wikipedia.org/wiki/Neuschwanstein_Castle" role="button">Learn More →</a>
        </div>
      </div>
    </div>
  </section>

</main>
<footer class="py-5 page-footer">
  <div class="container-fluid container-fluid-max">
    <div class="row">
      <div class="col-12 col-md-6 footer-child copyright">
        HoneyDreams © 2018 All Rights Reserved
      </div>
      <div class="col-12 col-md-6 footer-child footer-links">
        <a href="" class="mr-3">Privacy Policy</a>
        <a href="">FAQ</a>
        <div>
          <small>Made with <i class="fas fa-heart text-red"></i> by <a href="http://georgemartsoukos.com/" target="_blank">George Martsoukos</a>
          </small>
        </div>
      </div>
    </div>
  </div>
</footer>

    </body>
    </html>