<?php
if (session_status() == PHP_SESSION_NONE) session_start();

include("db.php");

//generate search token_get_all
$token = create_token();

if ( $_SERVER['REQUEST_METHOD'] == 'POST')
{
	if ( array_key_exists("action",$_POST) && $_POST["action"] == "register" )
	{
		$username = (array_key_exists("username",$_POST) ? $_POST["username"] : "");
		$name = (array_key_exists("name",$_POST) ? $_POST["name"] : "");
		$password = (array_key_exists("password",$_POST) ? $_POST["password"] : "");
		//$cpassword = (array_key_exists("cpassword",$_POST) ? $_POST["password"] : "");
		if ( ! user_exists($username) )
		{
			$retval = insert_new_user($username,$name,$password);
			if ( $retval )
			{
				$_SESSION['loginok'] = 1;
				$_SESSION['username'] = $username;
				header("Location:index.php");
			}
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
	<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="/static/jquery-3.4.1.js"></script>
	<script src="/static/jquery-ui/jquery-ui.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="/static/bootstrap/js/bootstrap.min.js"></script>
	<script src="/static/validate.js"></script>
	<title>Register</title>
  </head>
  <body>
	<div class="container">
		<div class="container">
			<div class="jumbotron">
				<h1>Bootstrap Tutorial</h1> 
				<p>Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile-first projects on the web.</p> 
			</div>
		</div>
		<div class="container-fluid">
			<form class="form-horizontal" action="" method="POST" onsubmit="return register_validate()">
				<div class="form-group" id="div-username">
					<label class="col-xs-4 control-label" for="username">Username:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="username" id="username" placeholder="Type username">
						<span class="glyphicon"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-4 control-label" for="name">Complete Name:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" name="name" id="name" placeholder="Type your name">
						<span class="glyphicon"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-4 control-label" for="password">Password:</label>
					<div class="col-xs-8">
						<input type="password" class="form-control" name="password" id="password" placeholder="Type password">
						<span class="glyphicon"></span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-4 control-label" for="cpassword">Confirm password:</label>
					<div class="col-xs-8">
						<input type="password" class="form-control" name="cpassword" id="cpassword" placeholder="Confirm password">
						<span class="glyphicon"></span>
					</div>
				</div>
				<input type="hidden" name="action" value="register">
				<div class="btn-group btn-group-sm">
					<button type="submit" class="btn btn-success">Registrar</button>
					<button class="btn btn-danger" onclick="$('input').val('')">Limpar</button>
					<button class="btn btn-warning" onclick="window.location.assign('login.php')">Voltar</button>
				</div>
			</form>
		</div>
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
						<button type="button" class="btn btn-default btn-success" data-dismiss="modal" onclick="window.location.reload()">Fechar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
  </body>
  <script>
  $(document).ready(function()
  {
	  $("#username").focusout(
		function()
		{
			$.get(
				"/api.php",
				{"action":"get_user","username":$("#username").val(),"token":"<?php echo $token ?>"},
				function(data)
				{
					if (data.status_code == "200")
					{
						$("#alert").text(data.message);
						$("#modal-button").trigger("click");
						$("#div-username").addClass("has-error");
						$("#div-username").addClass("has-feedback");
						$("#div-username span").addClass("glyphicon-remove");
						$("#div-username span").addClass("form-control-feedback");
					}
				},
				"json"
			);
		}
	  );
  });
  </script>
</html>