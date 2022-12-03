<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated_login_page(); ?>
<!DOCTYPE html>
<html lang="en">
<?php header("X-XSS-Protection: 1; mode=block"); ?>
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

  <title>JSPC Cloud Services</title>

  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <link href="css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div id="registrationContainer" class="container">

  <?php if(isset($_GET['verify'])) { echo verifyAccount(htmlspecialchars($_GET['verify'])); } else { ?>
    
  <?php
    
    $token = bin2hex(openssl_random_pseudo_bytes(16)); 
    $name = "CSRFtoken";
    $value = $token;
    $path = "/";
    $domain = "";
    $secure = true;
    $httponly = true;
    $expiration = time() + 60 * 60 * 24;
    setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
    
  ?>

  <?php if(isset($_POST['formData']['submit'])) { if(isset($_POST["CSRFtoken"], $_COOKIE["CSRFtoken"])) { if($_POST["CSRFtoken"] == $_COOKIE["CSRFtoken"]) { register($_POST['formData']); } } } ?>

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
              </div>
              <form id="registerForm" action="register" method="POST" class="user">
               <fieldset  <?php if(isset($_POST['formData']['submit'])){ if($registrationSuccessful) { echo 'disabled="disabled"'; } } ?>>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input name="formData[firstName]" type="text" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['firstName'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="First Name" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['firstName'])) { echo htmlspecialchars($_POST['formData']['firstName']); } } ?>" required>
                  </div>
                  <div class="col-sm-6">
                    <input name="formData[lastName]" type="text" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['lastName'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Last Name" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['lastName'])) { echo htmlspecialchars($_POST['formData']['lastName']); } } ?>" required>
                  </div>

                </div>
                <div class="form-group">
                  <input name="formData[emailAddress]" type="email" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['emailAddress'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Email Address" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['emailAddress'])) { echo htmlspecialchars($_POST['formData']['emailAddress']); } } ?>" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input name="formData[passphrase]" type="password" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['passphrase'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Passphrase" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['passphrase'])) { echo htmlspecialchars($_POST['formData']['passphrase']); } } ?>" required>
                  </div>
                  <div class="col-sm-6">
                    <input name="formData[passphraseConfirm]" type="password" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['passphraseConfirm'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Repeat Passphrase" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['passphraseConfirm'])) { echo htmlspecialchars($_POST['formData']['passphraseConfirm']); } } ?>" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-12 mb-3">
                    <input name="formData[inviteCode]" type="text" maxlength="16" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['inviteCode'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Invite Code" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['inviteCode'])) { echo htmlspecialchars($_POST['formData']['inviteCode']); } } ?>" required>
                  </div>
                </div>
                <div class="form-group">
                  <div class="form-check text-center">
                    <input <?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['agreement'])) { echo "checked"; } } ?> name="formData[agreement]" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['agreement'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-check-input" type="checkbox" value="" id="agreementCheck">
                    <label class="form-check-label" for="agreementCheck">
                      Agree to terms and conditions
                    </label>
                    <div class="invalid-feedback">
                      You must agree before submitting.
                    </div>
                  </div>
                </div>
                <input name="CSRFtoken" type="hidden" value="<?php echo $token; ?>">
                <button name="formData[submit]" class="btn btn-primary btn-user btn-block" type="submit"><?php if(isset($_POST['formData']['submit'])){ if($registrationSuccessful) { echo "Registration Successful"; } else { echo "Register Account"; } } else { echo "Register Account"; } ?></button>
                </fieldset>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot">Forgot Passphrase?</a>
              </div>
              <div class="text-center">
                <a class="small" href="login">Already have an account? Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <?php } ?>

  </div>

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <script src="js/sb-admin-2.js"></script>

  <?php if(isset($_POST['formData']['submit'])){ if($registrationSuccessful) { ?>
    <script>
      setTimeout(function(){ 
        
        document.getElementById("registrationContainer").innerHTML = `<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Check your email to verify your account!</h1>
            <p><a href="login">Login now...</a></p>
          </div>`;        
        
        }, 3000);
    </script>
  <?php } } ?>

</body>

</html>
