<?php

require("config.php");

if (session_status() == PHP_SESSION_NONE) session_start();

if ( array_key_exists("action",$_POST) && $_POST["action"] == "logout" )
{
	session_destroy();
	header("Location: login.php");
}
elseif ( array_key_exists('loginok', $_SESSION) && $_SESSION['loginok'] == 1 )
{
	include_once("main.php");
}
else header("Location: login.php");

?>

