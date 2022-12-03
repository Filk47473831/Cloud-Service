<?php 
/**
 * Cloud Portal Authentication Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2019 Chris Groves
 * @license   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

// Authenticated?

function authenticated() {
  
  global $connection;

  if(isset($_SESSION['id'])) {
    
    $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0) {  
    while($row = $result->fetch_assoc()) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['emailAddress'] = $row['emailAddress'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];   
    }
                           
    $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
    $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
    $stmt->execute();
    $stmt->close();
                                                         
 } else { header("Location: /login"); exit(); } } else {
  
  if(isset($_COOKIE['56949568965849'])) {
    
        $session_value = $_COOKIE['56949568965849'];
        $session_ident = $_COOKIE['56949568965850'];

                            $stmt = $connection->prepare("SELECT session_value, session_user_id FROM session WHERE `session`.`session_ident` = ?");
                            $stmt->bind_param("s",$session_ident);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows !== 0) {
                            while($row = $result->fetch_assoc()) {
                                $session_value_hash = $row['session_value'];
                                $session_user_id = $row['session_user_id']; }
                            $stmt->close(); } else { header("Location: /login"); exit(); }
     
       if(password_verify($session_value, $session_value_hash)) {
         
                    $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_value` = ?");
                    $stmt->bind_param("s", $session_value_hash);
                    $stmt->execute();
                    $stmt->close();
         
                   $session_ident = generateRandomString(11);
                   $session_value = $session_ident . '-' . $session_user_id . '-' . generateRandomString(133);
                   $session_value_hash = password_hash($session_value, PASSWORD_ARGON2ID, array('cost' => 13));
                   $session_ip = $_SERVER['REMOTE_ADDR']; 
                   $session_browser = $_SERVER['HTTP_USER_AGENT'];
         
                          $stmt = $connection->prepare("INSERT INTO session (session_ident, session_value, session_user_id, session_ip, session_browser) VALUES (?, ?, ?, ?, ?)");
                          $stmt->bind_param("ssiss", $session_ident, $session_value_hash, $session_user_id, $session_ip, $session_browser);
                          $stmt->execute();
                          $stmt->close();
       
        $name = "56949568965849";
        $value = $session_value;
        $expiration = time() + (60*60*24*30);
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
         
        $name = "56949568965850";
        $value = $session_ident;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);

        $_SESSION['session_ident'] = $session_ident;
        $_SESSION['id'] = $session_user_id;
         
        $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 0) {  
        while($row = $result->fetch_assoc()) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['emailAddress'] = $row['emailAddress'];
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];
        }
                               
        $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
        $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
        $stmt->execute();
        $stmt->close();
                              
        } else { header("Location: /login"); exit(); } } else { header("Location: /login"); exit(); } } else { header("Location: /login"); exit(); } } 
                
}




// Authenticated already at login page?

function authenticated_login_page() {
  
    global $connection;
  
  if(isset($_SESSION['id'])) {

    $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0) {  
    while($row = $result->fetch_assoc()) {
                $_SESSION['id'] = $row['id'];
                $_SESSION['emailAddress'] = $row['emailAddress'];
                $_SESSION['firstName'] = $row['firstName'];
                $_SESSION['lastName'] = $row['lastName'];           
    }
                           
    $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
    $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
    $stmt->execute();
    $stmt->close();
                              
            header("Location: /"); exit();
                           
 } } else {
  
  if(isset($_COOKIE['56949568965849'])) {
    
        $session_value = $_COOKIE['56949568965849'];
        $session_ident = $_COOKIE['56949568965850'];
        $session_value_hash = "";

                            $stmt = $connection->prepare("SELECT session_value, session_user_id FROM session WHERE `session`.`session_ident` = ?");
                            $stmt->bind_param("s",$session_ident);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows !== 0) {
                            while($row = $result->fetch_assoc()) {
                                $session_value_hash = $row['session_value'];
                                $session_user_id = $row['session_user_id']; }
                            $stmt->close(); }
     
       if(password_verify($session_value, $session_value_hash)) {
         
                    $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_value` = ?");
                    $stmt->bind_param("s", $session_value_hash);
                    $stmt->execute();
                    $stmt->close();
         
                   $session_ident = generateRandomString(11);
                   $session_value = $session_ident . '-' . $session_user_id . '-' . generateRandomString(133);
                   $session_value_hash = password_hash($session_value, PASSWORD_ARGON2ID, array('cost' => 13));
                   $session_ip = $_SERVER['REMOTE_ADDR']; 
                   $session_browser = $_SERVER['HTTP_USER_AGENT'];
         
                          $stmt = $connection->prepare("INSERT INTO session (session_ident, session_value, session_user_id, session_ip, session_browser) VALUES (?, ?, ?, ?, ?)");
                          $stmt->bind_param("ssiss", $session_ident, $session_value_hash, $session_user_id, $session_ip, $session_browser);
                          $stmt->execute();
                          $stmt->close();
       
        $name = "56949568965849";
        $value = $session_value;
        $expiration = time() + (60*60*24*30);
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
         
        $name = "56949568965850";
        $value = $session_ident;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
       
        $_SESSION['session_ident'] = $session_ident;
        $_SESSION['id'] = $session_user_id;
         
        $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 0) {  
        while($row = $result->fetch_assoc()) {
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['emailAddress'] = $row['emailAddress'];
                    $_SESSION['firstName'] = $row['firstName'];
                    $_SESSION['lastName'] = $row['lastName'];           
        }
                               
        $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
        $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
        $stmt->execute();
        $stmt->close();

              header("Location: /"); exit();
                            
        } } } }

}


// Authenticated already at landing page?

function authenticated_landing_page() {
  
  global $connection;

if(isset($_SESSION['id'])) {

  $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
  $stmt->bind_param("i", $_SESSION['id']);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows != 0) {  
  while($row = $result->fetch_assoc()) {
              $_SESSION['id'] = $row['id'];
              $_SESSION['emailAddress'] = $row['emailAddress'];
              $_SESSION['firstName'] = $row['firstName'];
              $_SESSION['lastName'] = $row['lastName'];           
  }
                         
  $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
  $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
  $stmt->execute();
  $stmt->close();
                         
} } else {

if(isset($_COOKIE['56949568965849'])) {
  
      $session_value = $_COOKIE['56949568965849'];
      $session_ident = $_COOKIE['56949568965850'];
      $session_value_hash = "";

                          $stmt = $connection->prepare("SELECT session_value, session_user_id FROM session WHERE `session`.`session_ident` = ?");
                          $stmt->bind_param("s",$session_ident);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          if($result->num_rows !== 0) {
                          while($row = $result->fetch_assoc()) {
                              $session_value_hash = $row['session_value'];
                              $session_user_id = $row['session_user_id']; }
                          $stmt->close(); }
   
     if(password_verify($session_value, $session_value_hash)) {
       
                  $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_value` = ?");
                  $stmt->bind_param("s", $session_value_hash);
                  $stmt->execute();
                  $stmt->close();
       
                 $session_ident = generateRandomString(11);
                 $session_value = $session_ident . '-' . $session_user_id . '-' . generateRandomString(133);
                 $session_value_hash = password_hash($session_value, PASSWORD_ARGON2ID, array('cost' => 13));
                 $session_ip = $_SERVER['REMOTE_ADDR']; 
                 $session_browser = $_SERVER['HTTP_USER_AGENT'];
       
                        $stmt = $connection->prepare("INSERT INTO session (session_ident, session_value, session_user_id, session_ip, session_browser) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("ssiss", $session_ident, $session_value_hash, $session_user_id, $session_ip, $session_browser);
                        $stmt->execute();
                        $stmt->close();
     
      $name = "56949568965849";
      $value = $session_value;
      $expiration = time() + (60*60*24*30);
      $path = "/";
      $domain = "";
      $secure = true;
      $httponly = true;
      setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
       
      $name = "56949568965850";
      $value = $session_ident;
      setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
     
      $_SESSION['session_ident'] = $session_ident;
      $_SESSION['id'] = $session_user_id;
       
      $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE id = ?");
      $stmt->bind_param("i", $_SESSION['id']);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows != 0) {  
      while($row = $result->fetch_assoc()) {
                  $_SESSION['id'] = $row['id'];
                  $_SESSION['emailAddress'] = $row['emailAddress'];
                  $_SESSION['firstName'] = $row['firstName'];
                  $_SESSION['lastName'] = $row['lastName'];           
      }
                             
      $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
      $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
      $stmt->execute();
      $stmt->close();
   
      } } } }

}

// Register user

function register($formData) {
  
  global $connection;
  global $errorDisplay;
  global $registrationSuccessful;

  ratelimitlog(20);
 
  $firstName = $formData['firstName'];
  $lastName = $formData['lastName'];
  $emailAddress = $formData['emailAddress'];
  $passphrase = $formData['passphrase'];
  $passphraseConfirm = $formData['passphraseConfirm'];
  $inviteCode = $formData['inviteCode'];
  $agreement = $formData['agreement'];

    $emailAddress = mb_convert_encoding($emailAddress, "UTF-8", "HTML-ENTITIES");
    
    if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) { $errorDisplay['emailAddress'] = "Invalid Email Address"; }
    if(preg_match("/[^A-Za-z]/", $firstName)) { $errorDisplay['firstName'] = "Invalid First Name"; }
    if(preg_match("/[^A-Za-z]/", $lastName)) { $errorDisplay['lastName'] = "Invalid Last Name"; }
    if($passphrase !== $passphraseConfirm) { $errorDisplay['passphraseConfirm'] = "Passphrases Do Not Match"; }
    if(strlen($passphraseConfirm) < 10) { $errorDisplay['passphrase'] = "Passphrase Is Less Than 10 Characters"; $errorDisplay['passphraseConfirm'] = "Passphrase Is Less Than 10 Characters"; }
    if($inviteCode !== "pprGzRYtcDlgANkQ") { $errorDisplay['inviteCode'] = "Invalid Invite Code"; }
    if(!isset($agreement)) { $errorDisplay['agreement'] = "You must agree before submitting."; }
        
    $stmt = $connection->prepare("SELECT id FROM users WHERE emailAddress = ?");
    $stmt->bind_param("s",$emailAddress);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) { $errorDisplay['emailAddress'] = "Email Address Already Registered"; }

    if(!isset($errorDisplay)) {
      
    $hash = password_hash($passphrase, PASSWORD_ARGON2ID, array('cost' => 13));

    $code = generateRandomString(64);
      
    $stmt = $connection->prepare("INSERT INTO users (emailAddress, passphrase, firstName, lastName, verifyCode) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $emailAddress, $hash, $firstName, $lastName, $code);
    if($stmt->execute()) { verifyEmail($emailAddress,$code); $registrationSuccessful = true; }

    }
  
}

// Forgot Passphrase Reset

function forgotPassphrase($formData) {
  
  global $connection;
  global $errorDisplay;
  global $requestComplete;
 
  ratelimitlog(20);

  $emailAddress = $formData['emailAddress'];

    $emailAddress = mb_convert_encoding($emailAddress, "UTF-8", "HTML-ENTITIES");
    
    if(!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) { $errorDisplay['emailAddress'] = "Invalid Email Address"; }

    if(!isset($errorDisplay)) {

      $stmt = $connection->prepare("SELECT id FROM users WHERE emailAddress = ?");
      $stmt->bind_param("s", $emailAddress);
      $stmt->execute();
      $result = $stmt->get_result();
      if($result->num_rows != 0) {  
        while($row = $result->fetch_assoc()) {

          $code = generateRandomString(64);
        
          $stmt2 = $connection->prepare("UPDATE users SET verifyCode = ? WHERE id = ?");
          $stmt2->bind_param("si",$code,$row['id']);
          if($stmt2->execute()) { $requestComplete = true; passphraseResetEmail($emailAddress,$code); }
          $stmt2->close();

        }
      } else { $requestComplete = true; error_log("No Forgot Passphrase Email Sent"); }
      
    }
  
}

// Verify account with code

function verifyAccount($code) {

  global $connection;

  $stmt = $connection->prepare("UPDATE users SET verified = '1' WHERE verifyCode = ?");
  $stmt->bind_param("s",$code);
  $stmt->execute();
  $stmt->close();

  echo '<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
            <h1 class="h4 text-gray-900 mb-4">Thanks for verifying!</h1>
            <p><a href="login">Login now...</a></p>
          </div>';

}

// Verify account with code

function resetPassphrase($code) {

  if(ratelimitblock() == true) { header("Location: /"); }

  global $connection;

  $stmt = $connection->prepare("SELECT id FROM users WHERE verifyCode = ?");
  $stmt->bind_param("s", $code);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows != 0) {  
  while($row = $result->fetch_assoc()) {
     $id = $row['id'];
  }


  echo '<div class="card o-hidden border-0 shadow-lg my-5">
  <div class="card-body p-0">
    <div class="row">
      <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
      <div class="col-lg-7">
        <div class="p-5">
          <div class="text-center">
          <h1 class="h4 text-gray-900 mb-2">Forgot Your Passphrase?</h1>
          <p class="mb-4">Don\'t worry, you\'ve got this! Enter your new passphrase below.</p>
        </div>
        <form class="user">
        <div class="form-group row">
          <div class="col-sm-6 mb-3 mb-sm-0">
            <input name="passphrase" id="passphrase" type="password" class="form-control form-control-user" placeholder="Passphrase" required>
          </div>
          <div class="col-sm-6">
            <input name="passphraseConfirm" id="passphraseConfirm" type="password" class="form-control form-control-user" placeholder="Repeat Passphrase" required>
          </div>
        </div>
          <button id="resetPassphraseBtn" onclick="resetPassphrase()" class="btn btn-primary btn-user btn-block" type="button">Reset Passphrase</button>
          </form>
          </div><script>
          
          document.getElementById("passphraseConfirm").addEventListener("keyup", function(a) {
            var b = a.which || a.keyCode;
            if (13 == b) return resetPassphrase(), !1
        })

          function resetPassphrase() { 
            
            document.getElementById("resetPassphraseBtn").setAttribute("disabled", true);
            document.getElementById("passphrase").setAttribute("disabled", true);
            document.getElementById("passphraseConfirm").setAttribute("disabled", true);
            
            var newPassword = document.getElementById("passphrase"); var confirmPassword = document.getElementById("passphraseConfirm"); if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
          } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onload = function () {
            if (this.status == 200) {
             if(this.responseText == "Passphrase Updated Successfully") { 
              document.getElementById("passphrase").className = "form-control form-control-user is-valid";
              document.getElementById("passphraseConfirm").className = "form-control form-control-user is-valid";
              document.getElementById("resetPassphraseBtn").innerText = "Passphrase Updated Successfully";
              setTimeout(function(){ location.replace("login"); }, 3000);
             } else {
              document.getElementById("resetPassphraseBtn").removeAttribute("disabled");
              document.getElementById("passphrase").removeAttribute("disabled");
              document.getElementById("passphraseConfirm").removeAttribute("disabled");
              document.getElementById("resetPassphraseBtn").innerText = this.responseText;
              document.getElementById("passphrase").className = "form-control form-control-user is-invalid";
              document.getElementById("passphraseConfirm").className = "form-control form-control-user is-invalid";
             }
            }
          }
          xmlhttp.open("POST", "control/control_auth.php", true);
          xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xmlhttp.send(`resetPassword&verifyCode=' . $code . ' &newPassword=${encodeURIComponent(newPassword.value)}&confirmPassword=${encodeURIComponent(confirmPassword.value)}`); 
        }
        </script>';

} else { header("Location: /"); }

}


// Login form completion

function login($formData) {
  
  global $connection;
  global $errorDisplay;
  global $loginSuccessful;

  ratelimitlog(20);
 
  $emailAddress = $formData['emailAddress'];
  $passphrase = $formData['passphrase'];
  if(isset($formData['rememberMe'])) { $rememberMe = $formData['rememberMe']; }
                                      
              $stmt = $connection->prepare("SELECT passphrase, blocked, verified FROM users WHERE emailAddress = ?");
              $stmt->bind_param("s", $emailAddress);
              $stmt->execute();
              $result = $stmt->get_result();
              if($result->num_rows != 0) {  
              while($row = $result->fetch_assoc()) {
                          $db_passphrase = $row['passphrase'];
                          $blocked = $row['blocked'];
                          $verified = $row['verified'];
              }

   $stmt->close();
    
   if(password_verify($passphrase, $db_passphrase) && $blocked === 0 && $verified === 1) {
          
              $stmt = $connection->prepare("SELECT id, emailAddress, firstName, lastName FROM users WHERE emailAddress = ?");
              $stmt->bind_param("s", $emailAddress);
              $stmt->execute();
              $result = $stmt->get_result();
              if($result->num_rows != 0) {  
              while($row = $result->fetch_assoc()) {
                          $_SESSION['id'] = $row['id'];
                          $_SESSION['emailAddress'] = $row['emailAddress'];
                          $_SESSION['firstName'] = $row['firstName'];
                          $_SESSION['lastName'] = $row['lastName'];           
              }
                
              
    if(isset($rememberMe)) { 
       
                   $session_ident = generateRandomString(11);
                   $session_value = $session_ident . '-' . $_SESSION['id'] . '-' . generateRandomString(133);
                   $session_value_hash = password_hash($session_value, PASSWORD_ARGON2ID, array('cost' => 13));
                   $session_ip = $_SERVER['REMOTE_ADDR']; 
                   $session_browser = $_SERVER['HTTP_USER_AGENT'];
         
                  $stmt = $connection->prepare("INSERT INTO session (session_ident, session_value, session_user_id, session_ip, session_browser) VALUES (?, ?, ?, ?, ?)");
                  $stmt->bind_param("ssiss", $session_ident, $session_value_hash, $_SESSION['id'], $session_ip, $session_browser);
                  $stmt->execute();
                  $stmt->close();
                     
        $name = "56949568965849";
        $value = $session_value;
        $expiration = time() + (60*60*24*30);
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
         
        $name = "56949568965850";
        $value = $session_ident;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
                   
        $_SESSION['session_ident'] = $session_ident;
       
       
      }

              $stmt = $connection->prepare("UPDATE users SET last_login = CURRENT_TIMESTAMP, last_ip = ? WHERE id = ?");
              $stmt->bind_param("si",$_SERVER['REMOTE_ADDR'],$_SESSION['id']);
              $stmt->execute();
              $stmt->close();

              addLogEntry($_SESSION['id'],null,null,5);
                
              $loginSuccessful = true;
                
              } else { $errorDisplay['passphrase'] = "Login Failed"; } } else { $errorDisplay['passphrase'] = "Login Failed"; } } else { $errorDisplay['emailAddress'] = "Login Failed"; }
  
}


// Update own password

function updatePassword($currentPassword,$newPassword,$confirmPassword) {

    global $connection;

    if(strlen($newPassword) < 10) { return "Enter a passphrase of 10 characters or more"; }
    if($newPassword !== $confirmPassword) { return "New passphrases do not match"; }

    $stmt = $connection->prepare("SELECT passphrase, blocked FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0) {  
    while($row = $result->fetch_assoc()) {
                $db_passphrase = $row['passphrase'];
                $blocked = $row['blocked'];
    }
  } else { return "User not found"; }

  $stmt->close();

  if(password_verify($currentPassword, $db_passphrase) && $blocked === 0) {

    $hash = password_hash($newPassword, PASSWORD_ARGON2ID, array('cost' => 13));
    $stmt = $connection->prepare("UPDATE users SET passphrase = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $_SESSION['id']);
    if($stmt->execute()) { return "Passphrase Updated Successfully"; }

  } else { return "Current passphrase incorrect"; }

}

// Update own password

function resetPassword($code,$newPassword,$confirmPassword) {

  global $connection;

  if(strlen($newPassword) < 10) { return "Enter a passphrase of 10 characters or more"; }
  if($newPassword !== $confirmPassword) { return "New passphrases do not match"; }

  $stmt = $connection->prepare("SELECT id FROM users WHERE verifyCode = ?");
  $stmt->bind_param("s", $code);
  $stmt->execute();
  $result = $stmt->get_result();
  if($result->num_rows != 0) {  
  while($row = $result->fetch_assoc()) {
              
    $hash = password_hash($newPassword, PASSWORD_ARGON2ID, array('cost' => 13));
    $stmt2 = $connection->prepare("UPDATE users SET passphrase = ? WHERE id = ?");
    $stmt2->bind_param("si", $hash, $row['id']);
    if($stmt2->execute()) { 
      
      $newCode = generateRandomString(64);

      $stmt3 = $connection->prepare("UPDATE users SET verifyCode = ? WHERE id = ?");
      $stmt3->bind_param("si", $newCode, $row['id']);
      $stmt3->execute();

      return "Passphrase Updated Successfully"; 
    
    }

  }
} else { return "User not found"; }

$stmt->close();

}

// Password reset with confirmation email

function passwordResetConfirm($newPW,$confirmPW) {
  
  global $connection;
 
    ratelimitlog(10);
    $user = $_SESSION['resetThisId'];
    $recipient = array();
    $recipient[] = "dummyuser";


    if ($newPW !== "" || $confirmPW !== "") {
      
    if($newPW === $confirmPW) {
      
    if(strlen($newPW) >= 10) {


        $hash = password_hash($newPW, PASSWORD_ARGON2I, array('cost' => 13));
      
                          $stmt = $connection->prepare("SELECT firstName, username, email FROM users WHERE id = ?");
                          $stmt->bind_param("i", $user);
                          $stmt->execute();
                          $result = $stmt->get_result();
                          if($result->num_rows > 0) { 
                          while($row = $result->fetch_assoc()) {
                              $firstName = $row['firstName'];
                              $username = $row['username'];
                              $email = $row['email']; } }
                          $stmt->close();
        
                          $stmt = $connection->prepare("UPDATE users SET passphrase = ? WHERE id = ?");
                          $stmt->bind_param("si", $hash, $user);
                          $stmt->execute();
                          $stmt->close();
      
                          $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_user_id` = ?");
                          $stmt->bind_param("i", $user);
                          $stmt->execute();
                          $stmt->close();
      
        $name = "65849568965849";
        $value = "";
        $expiration = time() - 3600;
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
                
        $name = "65849568965850";
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);

        $_SESSION = null;
        session_destroy(); 
      
      $recipient[] = $email;

    $message = "Hi " . $firstName . ",<br><br>You have successfully reset your passphrase.<br><br>You can login now with your username '<b>{$username}</b>' and your new passphrase.";
                                              
    $breaks = array("\\n\\n");  
    $message = str_replace($breaks, "<br><br>", $message);
    $breaks = array("\'");  
    $message = str_replace($breaks, "%27", $message);
    $breaks = array("\-");  
    $message = str_replace($breaks, "%96", $message);
   
           $subject = 'JSPC Project Portal: Passphrase Reset Successfully';
           $body = 
                          
                          
"<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <title>JSPC - Project Portal</title>
  
</head>
<body align='center' style='font-family: Arial, sans-serif, ‘Open Sans’' bgcolor='#F8F8F8'>
<div id='page-wrapper'>
<div class='row'><img alt='JSPC Project Portal' src='https://portal.jspc.co.uk/images/jspc_nav_logo.jpg' width='340' height='45'></div>
<br>
                <div class='row'>
                    <div class='col-lg-12'>
                        <h3 id='heading' class='page-header'>Passphrase Reset Successfully</h3><br>
                    </div>

                </div>

                            <div class='row'>
                                  <div class='col-lg-8'>
                                          <div class='panel-body'>
                                              <p id='message'>{$message}</p>
                                          </div>
                                          <div class='panel-footer'>
                                            <small>Please contact JSPC immediately on 01903 767122 if you did not reset your passphrase.</small>
                                          </div>
                                                                              </div>
                                  </div>
                            </div>

        </div>
  
</body>

</html>";
                                
   $altbody = 'JSPC Project Portal: Account Created Successfully - ' . $message;
                   
                         sendMail($recipient, $recipient, $subject, $body, $altbody);

    } else { echo "Passphrase Too Short"; } } else { echo "Passphrases Do Not Match"; } } else { echo "Fields Missing"; }

  
}


// Unblock a user

function unblockUser($userId) {
  
  if(accessLevel() < 20) { header("Location: /"); }

global $connection;
  
                $stmt = $connection->prepare("UPDATE users SET `blocked` = 0 WHERE `users`.`id` = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->close();
  
}


// Forgot password request

function forgotPwRequest($username) {
  
  global $connection;
  
ratelimitlog(10);

  $recipient = array();
  $recipient[] = "dummyuser";
  
                            $stmt = $connection->prepare("SELECT username, email, firstName, lastName FROM users WHERE username = ? OR email = ?");
                            $stmt->bind_param("ss", $username, $username);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows !== 0) {
                              while($row = $result->fetch_assoc()) {

                              $verifyCode = strtoupper(generateRandomString(8));
                              $db_username = $row['username'];
                              
                              $stmt = $connection->prepare("UPDATE users SET verifyCode = ? WHERE username = ? or email = ?");
                              $stmt->bind_param("sss", $verifyCode, $username, $username);
                              $stmt->execute();
                              $stmt->close();
                                
    $recipient[] = $row['email'];

    $message = "Hi " . $row['firstName'] . ",<br><br>There has been a passphrase reset request made for your account with username '<b>{$db_username}</b>'.<br><br>Please use the following code to verify access to this account and allow a passphrase reset.";
                                              
    $breaks = array("\\n\\n");  
    $message = str_replace($breaks, "<br><br>", $message);
    $breaks = array("\'");  
    $message = str_replace($breaks, "%27", $message);
    $breaks = array("\-");  
    $message = str_replace($breaks, "%96", $message);
   
           $subject = 'JSPC Project Portal: Passphrase Reset Request';
           $body = 
                          
                          
"<!DOCTYPE html>
<html lang='en'>

<head>

    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content=''>
    <meta name='author' content=''>

    <title>JSPC - Project Portal</title>
  
</head>
<body align='center' style='font-family: Arial, sans-serif, ‘Open Sans’' bgcolor='#F8F8F8'>
<div id='page-wrapper'>
<div class='row'><img alt='JSPC Project Portal' src='https://portal.jspc.co.uk/images/jspc_nav_logo.jpg' width='340' height='45'></div>
<br>
                <div class='row'>
                    <div class='col-lg-12'>
                        <h3 id='heading' class='page-header'>Passphrase Reset Request</h3><br>
                    </div>

                </div>

                            <div class='row'>
                                  <div class='col-lg-8'>
                                          <div class='panel-body'>
                                              <p id='message'>{$message}</p>
                                              <h3><b>{$verifyCode}</b></h3>
                                          </div>
                                          <div class='panel-footer'>
                                            <small>Please contact JSPC immediately on 01903 767122 if you did not request this passphrase reset.</small>
                                          </div>
                                                                              </div>
                                  </div>
                            </div>

        </div>
  
</body>

</html>";
                                
   $altbody = 'JSPC Project Portal: Passphrase Reset Request - ' . $message;
                   
                         sendMail($recipient, $recipient, $subject, $body, $altbody);
                                                                
              } } else { echo "2"; }
  
}

// Block a user

function blockUser($userId) {
  
    if(accessLevel() < 20) { header("Location: /"); }

    global $connection;

    $blocked = 1;

                                $stmt = $connection->prepare("UPDATE users SET `blocked` = ? WHERE `users`.`id` = ?");
                                $stmt->bind_param("ii", $blocked, $userId);
                                $stmt->execute();
                                $stmt->close();
  
}


// Ratelimit log

function ratelimitlog($x) {
  
  global $connection;

  $attempt_ip = $_SERVER['REMOTE_ADDR'];

                    $stmt = $connection->prepare("INSERT INTO attempts (attempt_ip, attempt_value) VALUES (?, ?)");
                    $stmt->bind_param("si", $attempt_ip, $x);
                    $stmt->execute();
                    $stmt->close();
    }
    

// Ratelimit block

function ratelimitblock() {
  
  global $connection;
  
  $attempt_ip = $_SERVER['REMOTE_ADDR'];
  $startTime = date("Y-m-d H:i:s");
  $now = date('Y-m-d H:i:s',strtotime('-2 minutes',strtotime($startTime)));
  
                    $stmt = $connection->prepare("SELECT attempt_ip, attempt_value FROM attempts WHERE `attempts`.`attempt_ip` = ? AND `attempts`.`attempt_time` < ?");
                    $stmt->bind_param("ss",$attempt_ip, $now);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if($result->num_rows != 0) {  
                    while($row = $result->fetch_assoc()) {
                                $attempt_value[] = $row['attempt_value'];
                    }

                          if(array_sum($attempt_value) > 240) { return true; } else { return false; }

                    } else { return false; }
  
}


// Ratelimit clear

function ratelimitclear() {
  
  global $connection;
  
  $attempt_ip = $_SERVER['REMOTE_ADDR'];
  $startTime = date("Y-m-d H:i:s");
  $now = date('Y-m-d H:i:s',strtotime('-2 minutes',strtotime($startTime)));
    
                    $stmt = $connection->prepare("DELETE FROM attempts WHERE `attempts`.`attempt_ip` = ? AND `attempts`.`attempt_time` < ?");
                    $stmt->bind_param("ss", $attempt_ip, $now);
                    $stmt->execute();
                    $stmt->close();
}


// Backup code display

function backupCode() {
  
  global $connection;
  
  $user_id = $_SESSION['id'];
  
    $stmt = $connection->prepare("SELECT backupCode FROM users WHERE `users`.`id` = ?");
              $stmt->bind_param("i",$user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              if($result->num_rows != 0) {  
              while($row = $result->fetch_assoc()) {
                          $backupcode = $row['backupCode'];
              } }
  
  
  echo $backupcode;
  
}

// Change password for account

function changePW($existingPW,$newPW,$confirmPW) {
  
  global $connection;
 
    ratelimitlog(10);
    $user = $_SESSION['id'];


    if ($existingPW === "" || $newPW === "" || $confirmPW === "") { echo "4"; } 
      else {
    
      
    $stmt = $connection->prepare("SELECT passphrase FROM users WHERE id = ?");
              $stmt->bind_param("i",$user);
              $stmt->execute();
              $result = $stmt->get_result();
              if($result->num_rows !== 0) {
              while($row = $result->fetch_assoc()) {                   
                $existingPWDB = $row['passphrase'];
              }}
              $stmt->close();
  
    if(password_verify($existingPW, $existingPWDB)) {
    
    if(strlen($newPW) >= 10) {
      
    if($newPW === $confirmPW) {

        $hash = password_hash($newPW, PASSWORD_ARGON2I, array('cost' => 13));
        
                          $stmt = $connection->prepare("UPDATE users SET passphrase = ? WHERE id = ?");
                          $stmt->bind_param("si", $hash, $user);
                          $stmt->execute();
                          $stmt->close();
      
                          $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_user_id` = ?");
                          $stmt->bind_param("i", $user);
                          $stmt->execute();
                          $stmt->close();
      
        $name = "65849568965849";
        $value = "";
        $expiration = time() - 3600;
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
                
        $name = "65849568965850";
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
    
    echo "1";
     
    } else { echo "2"; } } else { echo "3"; } } else { echo "3"; } }
  
}


// Check password reset code is correct

function checkPwResetCode($verifyCode) {
  
  global $connection;
  
ratelimitlog(10);
 
                            $stmt = $connection->prepare("SELECT id FROM users WHERE verifyCode = ?");
                            $stmt->bind_param("s", $verifyCode);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows !== 0) {
                              while($row = $result->fetch_assoc()) {
                                $_SESSION['resetThisId'] = $row['id'];
                             } }

  else { echo "Invalid Code"; }
  
}

// What access level are you?

function accessLevel() {
  
  global $connection;
  
  $access = 0;

          $stmt = $connection->prepare("SELECT access FROM users WHERE id = ?");
          $stmt->bind_param("i", $_SESSION['id']);
          $stmt->execute();
          $result = $stmt->get_result();
          if($result->num_rows !== 0) {
            while($row = $result->fetch_assoc()) {
              $access = $row['access'];
            }
          }
  
  return $access;
  
}

// Generate Random String

function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
