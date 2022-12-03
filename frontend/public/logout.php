<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php");

global $connection;

        addLogEntry($_SESSION['id'],null,null,6);

        $stmt = $connection->prepare("DELETE FROM session WHERE `session`.`session_ident` = ?");
        $stmt->bind_param("s", $_SESSION['session_ident']);
        $stmt->execute();
        $stmt->close();

        $name = "56949568965849";
        $value = "";
        $expiration = time() - 3600;
        $path = "/";
        $domain = "";
        $secure = true;
        $httponly = true;
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);
                
        $name = "56949568965850";
        setcookie($name,$value,$expiration,$path,$domain,$secure,$httponly);

        $_SESSION = null;

        setcookie('PHPSESSID', '', time() - 7000000, '/');

        session_destroy(); 

        header("Location: loggedout?now=thanks");

?>