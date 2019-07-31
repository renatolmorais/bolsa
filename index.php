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
	<meta http-equiv="pragma" content="no-cache">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="/static/bootstrap/css/bootstrap.css">

	<!-- jQuery library -->
	<script src="/static/jquery-3.4.1.js"></script>

	<!-- Latest compiled JavaScript -->
	<script src="/static/bootstrap/js/bootstrap.js"></script>
	
	<script src="/static/jquery-ui/jquery-ui.min.js"></script>
	<script src="/static/jtable/jquery.jtable.min.js"></script>
	<link href="/static/jquery-ui/jquery-ui.theme.css" rel="stylesheet" type="text/css" />
	<link href="/static/jtable/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />

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
			<span>Seja bem-vindo, <?php	echo $_SESSION['username'];	?></span>
			<form method="POST" action="">
				<input type="hidden" name="action" value="logout">
				<button type="submit" class="btn btn-danger">Sair</button>
			</form>
		</div>
		<div class="container">
			<div id="jt_main"></div>
		</div>
	</div>
  </body>
  <script>
    $(document).ready(function () {
        $('#jt_main').jtable({
            title: 'Investimentos',
            actions: {
                listAction: '/api/list.php',
				createAction: '/api/create.php'
            },
            fields: {
                id: {
                    key: true,
                    list: false
                },
                data: {
                    title: 'Data'
                },
                operacao: {
                    title: 'Operação',
					options: [{Value:"1",DisplayText:"COMPRA"},{Value:"2",DisplayText:"VENDA"}],
                },
                codigo: {
                    title: 'Código'
                },
				quantidade: {
					title: 'Quantidade'
				},
				preco: {
					title: 'Preço'
				},
				valor: {
					title: 'Valor',
					display: function(data) {
						return data.record.preco * data.record.quantidade;
					}
				},
				corretagem: {
					title: "Corretagem",
					display: function(data) {
						return 0.005 * data.record.valor;
					},
					create: false,
					edit: false,
				}
			}
		});
		$("#jt_main").jtable("load");
    });
  </script>
</html>