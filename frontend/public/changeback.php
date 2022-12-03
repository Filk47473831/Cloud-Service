<?php ob_start(); ?>
<?php if (session_status() == PHP_SESSION_NONE) { session_start(); } ?>
<?php require_once("../handlers/main.php"); ?>
<?php authenticated(); ?>
<?php $_SESSION['hostTarget'] == "JSPC-CS1"; ?>