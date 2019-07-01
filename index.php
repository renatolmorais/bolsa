<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if ( ! array_key_exists('loginok', $_SESSION) || $_SESSION['loginok'] != 1 )
{
	header("Location:login.php");
}
elseif ( array_key_exists("action",$_POST) && $_POST["action"] == "logout" )
{
	session_destroy();
	header("Location: login.php");
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8"> 
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="/static/bootstrap/dist/css/bootstrap.min.css">

	<!-- jQuery library -->
	<script src="/static/jquery.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="/static/bootstrap/dist/js/bootstrap.min.js"></script>
  </head>
  <body>
	<div class="container">
		<div class="container">
			<div class="jumbotron">
				<h1>Bootstrap Tutorial</h1> 
				<p>Bootstrap is the most popular HTML, CSS, and JS framework for developing responsive, mobile-first projects on the web.</p> 
			</div>
		</div>
		<div class="container">
			<span>
			<?php
			echo $_SESSION['username'];
			?>
			<form method="POST" action="">
				<input type="hidden" name="action" value="logout">
				<button type="submit" class="btn btn-danger">Sair</button>
			</form>
			</span>
		</div>
	</div>
  </body>
</html>