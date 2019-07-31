<?php 
	echo password_hash('renato',PASSWORD_BCRYPT,array("salt" => "1qazxsw23edcvfr45tgbnh"));
	echo "\n";
	echo microtime();
	echo "\n";
	echo substr(md5(microtime()),0,22);
	echo "\n";
	echo date("c");
?>
