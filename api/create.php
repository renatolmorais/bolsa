<?php

if (session_status() == PHP_SESSION_NONE) session_start();

include("../db.php");

$method = $_SERVER["REQUEST_METHOD"];

if ( array_key_exists('loginok', $_SESSION) && $_SESSION['loginok'] == 1 )
{
	if ($method == 'POST')
	{
		$info = array(
					"data" => $_POST["data"],
					"oper" => $_POST["operacao"],
					"codigo" => strtoupper($_POST["codigo"]),
					"quant" => intval($_POST["quantidade"]),
					"preco" => floatval($_POST["preco"]),
					"valor" => floatval($_POST["quantidade"]) * floatval($_POST["preco"]),
					"username" => $_SESSION["username"]
					);
		echo insert_operacoes($info);
	}
}
else header("HTTP/1.1 403 Forbidden");

?>