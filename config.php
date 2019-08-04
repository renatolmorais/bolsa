<?php

$config = array(
"login.php"		=>	array(
						"title"		=>	"Entrar",
						"banner"	=>	"Bolsa",
						"subbanner"	=>	"Controle seus investimentos"
					),
"index.php"		=>	array(
						"title"		=> "Bolsa",
						"banner"	=> "Bolsa",
						"subbanner"	=>	"Controle seus investimentos"
					),
"register.php"	=>	array(
						"title"		=>	"Registrar",
						"banner"	=>	"Bolsa",
						"subbanner"	=>	"Crie um novo usuário"
					),
"author"		=>	"Renato L. Morais"
);

function get_page()
{
	return trim($_SERVER["PHP_SELF"],"\/");
}

function get_title()
{
	global $config;
	$pagename = get_page();
	return $config[$pagename]["title"];
}

function get_banner()
{
	global $config;
	$pagename = get_page();
	return $config[$pagename]["banner"];
}

function get_subbanner()
{
	global $config;
	$pagename = get_page();
	return $config[$pagename]["subbanner"];
}

?>
