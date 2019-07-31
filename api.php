<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include("db.php");

$method = $_SERVER["REQUEST_METHOD"];

switch($method)
{
	case 'POST':
		if ( array_key_exists("token",$_POST) )
		{
			$token = $_POST["token"];
			if ( ! is_token_valid($token) ) header("HTTP/1.0 403 Forbidden");
			else
			{
				update_token($token);
				if ( array_key_exists('action',$_POST) && $_POST['action'] == 'get_user' )
				{	
					$username = (array_key_exists("username",$_POST) ? $_POST["username"] : "");
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
		elseif ( array_key_exists('loginok', $_SESSION) && $_SESSION['loginok'] == 1 )
		{
			//var_dump($_REQUEST);
			echo get_operacoes($_SESSION["username"]);
		}
		else header("HTTP/1.0 403 Forbidden");
		break;
	case 'GET':
		break;
	case 'PUT':
		break;
	case 'DELETE':
		break;
	default:
		header("HTTP/1.0 403 Forbidden");
		break;
}
?>