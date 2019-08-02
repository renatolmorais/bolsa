<?php

$config = array(
"title"		=>	array(
						"login.php"		=>	"Entrar",
						"register.php"	=>	"Registrar",
						"index.php"		=>	"Bolsa",
				),
"author"	=>	"Renato L. Morais"
);

function get_title()
{
	global $config;
	$pagename = trim($_SERVER["PHP_SELF"],"\/");
	return $config["title"][$pagename];
}

?>
