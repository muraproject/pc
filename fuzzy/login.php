<?php
include('includes/db.php');
include('includes/functions.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid login details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        .login {
        min-height: 100vh;
        }

        .bg-image {
        background-image: url('https://miro.medium.com/v2/resize:fit:1200/1*904gejb1_IP5mJw78Nx4Pw.jpeg');
        background-size: cover;
        background-position: center;
        }

        .login-heading {
        font-weight: 300;
        }

        .btn-login {
        font-size: 0.9rem;
        letter-spacing: 0.05rem;
        padding: 0.75rem 1rem;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
  
   

    <div class="container-fluid ps-md-0">
  <div class="row g-0">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4">Welcome back!</h3>

              <!-- Sign In Form -->
              <form method="POST" action="">
                <div class="form-floating mb-3">
                  <input type="text" name="username" placeholder="Username" required class="form-control" id="floatingInput">
                  <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating mb-3">
                  <input type="password" name="password" placeholder="Password" required class="form-control" id="floatingPassword">
                  <label for="floatingPassword">Password</label>
                </div>

                <!-- <div class="form-check mb-3">
                  <input class="form-check-input" type="checkbox" value="" id="rememberPasswordCheck">
                  <label class="form-check-label" for="rememberPasswordCheck">
                    Remember password
                  </label>
                </div> -->

                <div class="d-grid">
                  <button class="btn btn-lg btn-primary btn-login text-uppercase fw-bold mb-2" type="submit">Sign in</button>
                  <div class="text-center">
                    <a class="small" href="#">Forgot password?</a>
                  </div>
                </div>
                <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
