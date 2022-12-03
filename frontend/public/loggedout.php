<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated_login_page(); ?>
<?php if($_GET['now'] !== "thanks") { header("Location: login"); } ?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="JSPC Cloud Services" />
  <meta name="keywords" content="JSPC Cloud Services" />
  <meta name="author" content="Chris Groves, https://www.jspc.co.uk/">
  <meta name="date" content="2020-09-01" />
  <meta name="Referrer-Policy" value="no-referrer" />
  <meta name="robots" content="noindex,nofollow">
  <meta property="og:description" content="JSPC Cloud Services" />
  <meta property="og:title" content="JSPC Cloud Services">
  <meta property="og:url" content="https://cloud.jspc.co.uk" />
  <meta property="og:image" content="img/apple-touch-icon.png" />
  <meta property="og:logo" content="img/apple-touch-icon.png" />
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="JSPC Cloud Services" />

  <title>Cloud Management</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-loggedout-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-2">See you soon!</h1>
                    <p class="mb-4">You are totally logged out. Totally!</p>
                    <p class="mb-4">We appreciate your feedback, do you have a suggestion?</p>
                  </div>
                  <form class="user">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="suggestionBox" aria-describedby="suggestionBox" placeholder="Clouds are too fluffy!">
                    </div>
                    <button disabled name="formData[submit]" class="btn btn-primary btn-user btn-block" type="submit">Do This!</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="login">Forget something? Log back in!</a><br> 
                    <a class="small" href="/">Head home.</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>
