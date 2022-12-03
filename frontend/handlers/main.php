<?php 

// Load Dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// Load Environment Variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// Load Handlers
require_once("connection.php");
require_once("auth.php");
require_once("functions.php");
require_once("powershell.php");
require_once("email.php");

?>