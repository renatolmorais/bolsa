<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include("db.php");

if ( $_SERVER['REQUEST_METHOD'] == 'GET' )
{
	if ( array_key_exists("token",$_GET) )
	{
		$token = $_GET["token"];
		if ( ! is_token_valid($token) ) header("HTTP/1.0 403 Forbidden");
		else
		{
			update_token($token);
			if ( array_key_exists('action',$_GET) && $_GET['action'] == 'get_user' )
			{	
				$username = (array_key_exists("username",$_GET) ? $_GET["username"] : "");
				if ( user_exists($username) ) 
				{
					echo json_encode(
								array(
									"status_code" => "200",
									"message" => "Usuário já existe!"
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
}
else
{
	header("HTTP/1.0 403 Forbidden");
}
?>