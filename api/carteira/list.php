<?php

if (session_status() == PHP_SESSION_NONE) session_start();

include("../../db.php");

$method = $_SERVER["REQUEST_METHOD"];

if ( array_key_exists('loginok', $_SESSION) && $_SESSION['loginok'] == 1 )
{
	if ($method == 'POST')
	{
		if ( isset($_REQUEST["jtStartIndex"]) ) $jtStartIndex = $_REQUEST["jtStartIndex"];
		if ( isset($_REQUEST["jtPageSize"]) ) $jtPageSize = $_REQUEST["jtPageSize"];

		echo get_carteira($_SESSION["username"],$jtStartIndex,$jtPageSize);
	}		
}
else header("HTTP/1.1 403 Forbidden");

?>