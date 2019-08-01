<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include("../db.php");

$method = $_SERVER["REQUEST_METHOD"];

switch($method)
{
	case 'GET':
		if ( array_key_exists("token",$_REQUEST) )
		{
			$token = $_REQUEST["token"];
			if ( ! is_token_valid($token) ) header("HTTP/1.0 403 Forbidden");
			else
			{
				update_token($token);
				if ( array_key_exists('action',$_REQUEST) && $_REQUEST['action'] == 'get_user' )
				{	
					$username = (array_key_exists("username",$_REQUEST) ? $_REQUEST["username"] : "");
					if ( user_exists($username) ) 
					{
						echo json_encode(
									array(
										"status_code" => "200",
										"message" => "Usuário já existe!\nEscolha outro!"
									)
						);
					}
					else
					{
						echo json_encode(
									array(
										"status_code" => "201",
										"message" => "Usuário não existe!"
									)
						);
					}
				}
			}
		}
		else header("HTTP/1.0 403 Forbidden");
		break;
	case 'POST':
	case 'PUT':
	case 'DELETE':
	default:
		header("HTTP/1.0 403 Forbidden");
		break;
}
?>