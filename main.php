<?php 

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

	<!-- Latest compiled JavaScript -->
	<script src="/static/bootstrap/js/bootstrap.min.js"></script>
	
	<script src="/static/jquery-ui/jquery-ui.min.js"></script>
	<script src="/static/jtable/jquery.jtable.min.js"></script>
	<link href="/static/jquery-ui/jquery-ui.theme.css" rel="stylesheet" type="text/css" />
	<link href="/static/jtable/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />
	<title><?php echo get_title(); ?></title>
	<!-- tabelas -->
	<script src="/static/carteira.js"></script>
	<script src="/static/operacoes.js"></script>
	<script src="static/dividendos.js"></script>
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
			<div class="row">
				<div class="col-lg-3"><span>Seja bem-vindo, <?php	echo $_SESSION['username'];	?></span></div>
				<div class="col-lg-7"></div>
				<div class="col-lg-2">
					<form method="POST" action="/index.php">
						<input type="hidden" name="action" value="logout">
						<button type="submit" class="btn btn-danger btn-sm btn-block">Sair</button>
					</form>
				</div>
			</div>
		</div>
		<div class="container">
			<ul class="nav nav-pills nav-justified">
				<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#jt_carteira">Carteira</a></li>
				<li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#jt_operacoes">Operações</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="pill" href="#jt_dividendos">Dividendos</a></li>
			</ul>
		</div>
		<div class="container">
			<div class="tab-content">
				<div id="jt_carteira" class="tab-pane fade"></div>
				<div id="jt_operacoes" class="tab-pane fade active show"></div>
				<div id="jt_dividendos" class="tab-pane fade"></div>
			</div>
		</div>
	</div>
  </body>
</html>