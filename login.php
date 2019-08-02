<?php
if (session_status() == PHP_SESSION_NONE) session_start();

require("config.php");
include("db.php");

$show_modal = false;

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
			$_SESSION['uid'] = $GLOBALS['uid'];
			header("Location:index.php");
			$show_modal = false;
		}
		else $show_modal = true;
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
	<title><?php echo get_title(); ?></title>
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
		<button id="modal-button" style="display:none" type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Error</button>
		<div id="myModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
			<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<p><h3>Atenção!</h3></p>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<p id="alert"></p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-success" data-dismiss="modal">Fechar</button>
					</div>
				</div>
			</div>
		</div>
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
  function show_modal(message)
  {
	  $("#alert").text(message);
	  $("#modal-button").trigger("click");
  }
	<?php if ($show_modal)
	{?>
		show_modal("Usuário ou senha incorretos!");
	<?php
	}
	$show_modal = false;
	?>
  </script>
</html>