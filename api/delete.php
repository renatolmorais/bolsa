<?php

if (session_status() == PHP_SESSION_NONE) session_start();

include("../db.php");

$method = $_SERVER["REQUEST_METHOD"];

if ( array_key_exists('loginok', $_SESSION) && $_SESSION['loginok'] == 1 )
{
	if ($method == 'POST')
	{
		$info = array(
					"id" => intval($_POST["id"]),
					"username" => $_SESSION["username"]
					);
		echo delete_operacoes($info);
	}
}
else header("HTTP/1.1 403 Forbidden");

?>