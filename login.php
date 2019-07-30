<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include("db.php");

if ( $_SERVER['REQUEST_METHOD'] == 'POST')
{
	if ( array_key_exists("action",$_POST) && $_POST["action"] == "login" )
	{
		$username = (array_key_exists("username",$_POST) ? $_POST["username"] : "");
		$password = (array_key_exists("password",$_POST) ? $_POST["password"] : "");
		if ( login($username,$password) )
		{
			$_SESSION['loginok'] = 1;
			$_SESSION['username'] = $username;
			header("Location:index.php");
		}	
	}
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="pragma" content="no-cache">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css">

	<!-- jQuery library -->
	<script src="/static/jquery-3.4.1.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="/static/bootstrap/js/bootstrap.js"></script>
  </head>
  <body>
	<div class="container">
		<div class="container">
			<div class="jumbotron">
				<h1>Bootstrap Tutorial</h1> 
				<p>Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile-first projects on the web.</p> 
			</div>
		</div>
		<form class="form-vertical" action="" method="POST" onsubmit="return validate()">
			<div class="form-group">
				<label for="userame">Username:</label>
				<input type="text" class="form-control" name="username" id="username">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" class="form-control" name="password" id="password">
			</div>
			<input type="hidden" name="action" value="login">
			<div class="btn-group btn-group-sm">
				<button type="submit" class="btn btn-success">Entrar</button>
				<button class="btn btn-primary" onclick="window.location.assign('register.php')">Novo usuário</a></button>
			</div>
		</form>
	</div>
  </body>
  <script>
  function validate()
  {
	  if ( $("#username").val() == "")
	  {
		  $("#username").parent().addClass("has-warning");
		  $("#username").parent().addClass("has-feedback");
		  $("#username").focus();
		  return false;
	  }
	  if ( $("#password").val() == "")
	  {
		  $("#password").parent().addClass("has-warning");
		  $("#password").parent().addClass("has-feedback");
		  $("#password").focus();
		  return false;
	  }
	  return true;
  }
  </script>
</html>