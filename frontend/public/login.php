<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated_login_page(); ?>

<!DOCTYPE html>
<html lang="en">
<?php header("X-XSS-Protection: 1; mode=block"); ?>
<head>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" />
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

  <div class="container">

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
      
    <?php if(isset($_POST['formData']['submit'])) { if(isset($_POST["CSRFtoken"], $_COOKIE["CSRFtoken"])) { if(hash_equals($_COOKIE["CSRFtoken"], $_POST['CSRFtoken'])) { login($_POST['formData']); } } } ?>

    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <form id="loginForm" action="login" method="POST" class="user">
                  <fieldset  <?php if(isset($_POST['formData']['submit'])){ if($loginSuccessful) { echo 'disabled="disabled"'; } } ?>>
                    <div class="form-group">
                      <input id="emailAddress" name="formData[emailAddress]" autocomplete type="email" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['emailAddress']) || isset($errorDisplay['passphrase'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" aria-describedby="emailHelp" placeholder="Enter Email Address..." value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['emailAddress'])) { echo htmlspecialchars($_POST['formData']['emailAddress']); } } ?>" required>
                    </div>
                    <div class="form-group">
                      <input id="passphrase" name="formData[passphrase]" autocomplete type="password" class="<?php if(isset($_POST['formData']['submit'])){ if(isset($errorDisplay['emailAddress']) || isset($errorDisplay['passphrase'])) { echo "is-invalid"; } else { echo "is-valid"; } } ?> form-control form-control-user" placeholder="Passphrase" value="<?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['passphrase'])) { echo htmlspecialchars($_POST['formData']['passphrase']); } } ?>" required>
                    </div>
                    <div class="form-group">
                      <div class="text-center custom-control custom-checkbox small">
                        <input <?php if(isset($_POST['formData']['submit'])){ if(isset($_POST['formData']['rememberMe'])) { echo "checked"; } } ?> name="formData[rememberMe]" type="checkbox" class="custom-control-input" id="rememberMe">
                        <label class="custom-control-label" for="rememberMe">Remember Me</label>
                      </div>
                    </div>
                    <input name="CSRFtoken" type="hidden" value="<?php echo $token; ?>">
<button <?php if(ratelimitblock() == true) { ?> disabled <?php } ?> id="loginBtn" name="formData[submit]" class="btn btn-primary btn-user btn-block" type="submit"><?php if(isset($_POST['formData']['submit'])){ if($loginSuccessful) { echo "Login Successful"; } else { echo "Login Failed"; } } else { if(ratelimitblock() == true) { echo "Rate Limited"; } else { echo "Login"; } } ?></button>
                    </fieldset>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="forgot">Forgot Passphrase?</a>
                  </div>
                  <div class="text-center">
                    <a class="small" href="register">Create an Account!</a>
                  </div>
                  <div class="text-center">
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

<script>
  if(document.getElementById("emailAddress").value != "" && document.getElementById("passphrase").value != ""
){
document.getElementById("loginBtn").addEventListener("click",function(){
    document.getElementById("loginBtn").innerHTML = "Please Wait"
  })
  }
</script>
  
  <?php if(isset($_POST['formData']['submit'])){ if($loginSuccessful) { ?>
    <script>
      setTimeout(function(){ window.location.href = "/"; }, 1000);
    </script>
  <?php } } ?>

</body>

</html>
