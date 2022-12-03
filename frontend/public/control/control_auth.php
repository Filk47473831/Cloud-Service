<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php
/**
 * Cloud Portal Control Functions.
 * Version 1.0.
 *
 * @author    Chris Groves <chris@thegaff.co.uk>
 * @copyright 2019 Chris Groves
 */

require_once("../../handlers/main.php");

 if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SERVER['HTTP_ORIGIN'] == 'https://cloud.jspc.co.uk') {

    if(isset($_POST['resetPassword'])) {
        echo resetPassword(escape($_POST['verifyCode']),escape($_POST['newPassword']),escape($_POST['confirmPassword']));
    }
    
} else {

    header("Location: /login");

}
