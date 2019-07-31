<?PHP
#include('/usr/share/sgar/web/lib/postgres.php');
include_once('senhas.php');

function connect(){
	if($GLOBALS['my_pg_conn'] === FALSE){
		$credenciais=getCredenciaisBanco();
		$GLOBALS['my_pg_conn'] = pg_connect("dbname=system user=$credenciais[usuario] password=$credenciais[senha]");
#		echo "CRIADO.";
		if($GLOBALS['my_pg_conn'] === FALSE) {
			echo "Sem conexao";
		}
		pg_query($GLOBALS['my_pg_conn'],"BEGIN;");
	}
#	echo "VALOR:";var_dump($GLOBALS['my_pg_conn']);
	return $GLOBALS['my_pg_conn'];
}

function get_operacoes($username)
{
	$conn = connect();
	$result = pg_query($conn,"SELECT * FROM operacoes join usuario on operacoes.id_usuario = usuario.id where username = '$username' order by data desc;");
	//Add all records to an array
	$rows = array();
	while($row = pg_fetch_assoc($result)) $rows[] = $row;
 	//Return result to jTable
	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	$jTableResult['Records'] = $rows;
	return json_encode($jTableResult);
}

function insert_operacoes($info = array())
{
	$conn = connect();
	$info["username"] = get_user_id($info["username"]);
	$query = "insert into operacoes (data,operacao,codigo,quantidade,preco,valor,id_usuario) values ($1,$2,$3,$4,$5,$6,$7)";
	pg_query_params($conn,$query,$info);
	commit();
	$result = pg_query("SELECT * FROM operacoes order by id desc limit 1");
	$row = pg_fetch_assoc($result);
 
	//Return result to jTable
	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	$jTableResult['Record'] = $row;
	return json_encode($jTableResult);
}

function get_user_id($username)
{
	$conn = connect();
	$result = pg_query($conn,"select id from usuario where username = '$username';");
	if($result)
	{
		$row = pg_fetch_assoc($result);
		return $row["id"];
	}
	return "";
}

function create_token()
{
	$token = substr(md5(microtime()),0,22);
	$conn = connect();
	$result = pg_query_params($conn,"insert into api_busca (token,used,created_at) values ($1,$2,$3)", array($token,'N',date("c")) );
	commit();
	return $token;
}

function update_token($token)
{
	$conn = connect();
	$result = pg_query_params($conn,"update api_busca set used = 'Y',used_at = $1 where token = '$token'", array(date("c")));
	commit();
}

function is_token_valid($token)
{
	$conn = connect();
	$result = pg_query($conn,"select * from api_busca where token = '$token'");
	if($result)
	{
		$row = pg_fetch_assoc($result);
		$used = $row["used"];
		if ($used == 'N') return true;
	}
	return false;
}

function login($username,$password)
{
	$retval = false;
	$conn = connect();
	$result = pg_query($conn,"select id,password,salt from usuario where username = '$username'");
	if($result)
	{
		$row = pg_fetch_assoc($result);
		if (password_hash($password,PASSWORD_BCRYPT,array( "salt" => $row["salt"])) == $row["password"]) 
		{
			$retval = true;
			$GLOBALS['uid'] = $row['id'];
		}
	}
	return $retval;
}

function insert_new_user($username,$name,$password)
{
	$retval = false;
	$conn = connect();
	$salt = substr(md5(microtime()),0,22);
	$result = pg_query_params($conn, 'INSERT INTO usuario (username,name,password,salt) values ($1,$2,$3,$4);', array($username,$name,password_hash($password,PASSWORD_BCRYPT,array( "salt" => $salt)),$salt));
	if ($result) $retval = true;
	commit();
	return $retval;
}

function user_exists($username)
{
	$conn = connect();
	$result = pg_query($conn,"select * from usuario where username = '$username'");
	if($result)
	{
		$row = pg_fetch_assoc($result);
		if($row) return true;
	}
	return false;
}

function adaptContentsScrow($contents){
	mb_regex_encoding('UTF-8');
	$contents=mb_eregi_replace("[\n\r]"," ",$contents);
	return $contents;
}

function adaptContents($contents,$maxsize="57",$maxlines="2",$onlysize=false){
	mb_regex_encoding('UTF-8');
	$finalstring="";
	$currentline="";

	if($onlysize == true){
		$finalstring = mb_substr($contents,0,$maxsize);
		return $finalstring;
	}

	if(mb_strlen($contents)>$maxsize*$maxlines){
		$contents=mb_eregi_replace("Comissão","Com.",$contents);
		$contents=mb_eregi_replace("Especial","Esp.",$contents);
		$contents=mb_eregi_replace("Indicação","Ind.",$contents);
		$contents=mb_eregi_replace("Presidente","Pres.",$contents);
	}

        $words = mb_split('\s', $contents);

	foreach ($words as $word){
		$linelength = mb_strlen($currentline." ".$word);
		#echo "|$currentline|,|$word|,|$linelength|\n";
		if($linelength > $maxsize){
			$currentline=mb_eregi_replace("\s+$","",$currentline);
			$finalstring.="$currentline\n";
			$currentline=$word." ";
		} else {
			$currentline.=$word." ";
		}
	}
	$currentline=mb_eregi_replace("\s+$","",$currentline);
	$finalstring.="$currentline";
#	echo "\n\n$finalstring\n\n";
	$lines = mb_split('\n',$finalstring);
	if(count($lines)>$maxlines){
		$finalstring="";
		for($i=0;$i<$maxlines-1;$i++){
			$finalstring.=$lines[$i]."\n";
		}
		$finalstring.=$lines[$maxlines-1]." ...";
	}
	return $finalstring;
}


$my_pg_conn = FALSE;

function decodeReuniaoCodigoParaApi($status){
	switch($status){
	case 'A':
		return 3;
	case 'P':
		return 2;
	case 'T':
		return 4;
	case 'I':
		return 1;
	case 'N':
		return 5;
	}
}

function commit(){
	$conn=connect();
	$result = pg_query($conn,"COMMIT;");
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
#	var_dump($conn);
	pg_query($conn,"BEGIN;");
}

function close(){
	global $my_pg_conn;
	pg_close($my_pg_conn);
	$my_pg_conn = FALSE;
}

function mylog ($user,$sistema, $mensagem){
	$conn = connect();
	$ip = (array_key_exists('REMOTE_ADDR',$_SERVER))? $_SERVER['REMOTE_ADDR']." - ":"";
#	echo "$user, $sistema, $mensagem $ip\n";
#	echo 'INSERT INTO log (usuario,sistema,log) values ($1,$2,$3)'; var_dump(array($user,$sistema,"$ip$mensagem"));
	$result = pg_query_params($conn, 'INSERT INTO log (usuario,sistema,log) values ($1,$2,$3);', array($user,$sistema,adaptContents("$ip$mensagem",200,1)));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
}

function getlogs ($sistema){
	$conn = connect();
	$result = pg_query_params($conn, 'SELECT * FROM log where sistema = $1 order by data desc limit 5',array($sistema));
	$retval = "\n";
	while ($row = pg_fetch_assoc($result) ) {
		$retval .= "$row[data] $row[usuario] - $row[sistema] - $row[log]\n";
	}
	return $retval;
}

function insereReinicio($local){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'SELECT * FROM  locais where ACOD_LOCAL = $1', array($local));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	} else {
		if ($row = pg_fetch_assoc($result) ) {
	  		$result = pg_query_params($conn, 'UPDATE locais SET DATAREINICIO = current_timestamp where ACOD_LOCAL = $1', array($local));
			if ($result === FALSE) {
				$error = pg_last_error($conn);
				echo $error;
			}
		} else {
			$result = pg_query_params($conn, 'INSERT INTO locais(ACOD_LOCAL) values ($1)', array($local));
			if ($result === FALSE) {
				$error = pg_last_error($conn);
				echo $error;
			}
		}
	}
}

function checaReinicio($local){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'SELECT * FROM  locais where ACOD_LOCAL = $1 AND DATAREINICIO + INTERVAL \'1 hour\' > CURRENT_TIMESTAMP ', array($local));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	} else {
		if ($row = pg_fetch_assoc($result) ) {
			return 0;
		} else {
			insereReinicio($local);
			return 1;
		}
	}
	return 1;
}


function insereLogReuniao($acodcomissao,$acodtipo,$hora,$data,$local,$tipo,$subtipo,$descricao){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'INSERT INTO log_reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,TIPO,SUBTIPO,DESCRICAO) values ($1,$2,$3,$4,$5,$6,$7,$8)', array($acodcomissao,$acodtipo,$hora,$data,$local,$tipo,$subtipo,$descricao));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}

//function insereLogTransmissao($acodcomissao,$acodtipo,$hora,$data,$local,$tipo,$subtipo,$descricao){
function insereLogTransmissao($id_transmissao,$grupo,$ambiente,$url,$datatransmissao,$horainicio,$horafim){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'INSERT INTO log_transmissoes(ID_TRANSMISSAO,GRUPO,AMBIENTE,URL,DATA_TRANSMISSAO,HORA_INICIO,HORA_FIM) values ($1,$2,$3,$4,$5,$6,$7)', array($id_transmissao,$grupo,$ambiente,$url,$datatransmissao,$horainicio,$horafim));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	return $result;
}

function updateLogTransmissao($id_transmissao,$horafim){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'UPDATE log_transmissoes SET HORA_FIM = $2 WHERE ID_TRANSMISSAO = $1', array($id_transmissao,$horafim));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}

function getTransmissoesAnteriores($grupo,$gerente=false)
{
	$conn = connect();
	$result = "";
	$retval = "\n";
	if ($gerente) 
	{
		$result = pg_query_params($conn, 'SELECT * FROM log_transmissoes order by id_transmissao desc',array());
		while ( $row = pg_fetch_assoc($result) )
		{
			$hora_fim = $row['hora_fim'];
			$style = "";
			if ($hora_fim == '00:00:00')
			{
				$hora_fim = "Em andamento";
				$style = "font-weight:bold;color:red;";
			}	
			$retval .= "<tr style=\"$style\"><td id=\"_filter-$row[grupo]\">$row[grupo]</td><td id=\"_filter-$row[data_transmissao]\">$row[data_transmissao]</td><td id=\"_filter-$row[ambiente]\">$row[ambiente]</td><td>$row[hora_inicio]</td><td>$hora_fim</td></tr>\n";
		}
	}
	else
	{
		$result = pg_query_params($conn, 'SELECT * FROM log_transmissoes where grupo = $1 and hora_fim <> \'00:00:00\' order by id_transmissao desc limit 5',array($grupo));
		while ( $row = pg_fetch_assoc($result) )
		{
			$retval .= "<tr><td>$row[data_transmissao]</td><td>$row[ambiente]</td><td>$row[hora_inicio]</td><td>$row[hora_fim]</td></tr>\n";
		}
	}	
	return $retval;
}

function insereLogReuniaoCaracteres($acodcomissao,$acodtipo,$hora,$data,$local){
	#echo "INSERE C $acodcomissao,$acodtipo,$hora,$data,$local\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'INSERT INTO log_reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,TIPO,SUBTIPO,DESCRICAO) SELECT ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,\'E\',\'P\',\'\' FROM reunioes where ACOD_LOCAL = $1 AND ACOD_COMISSAO <> $2 AND ACOD_TIPO <> $3 AND AHOR_REUNIAO <> $4 AND ADAT_REUNIAO <> $5 AND STATUS = \'A\'', array($local,$acodcomissao,$acodtipo,$hora,$data));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	insereLogReuniao($acodcomissao,$acodtipo,$hora,$data,$local,'E','C','');
}

function insereLogReuniaoReinicio($local){
	#echo "INSERE C $acodcomissao,$acodtipo,$hora,$data,$local\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'INSERT INTO log_reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,TIPO,SUBTIPO,DESCRICAO) SELECT ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,\'A\',\'R\',\'Reinicio\' FROM reunioes where ACOD_LOCAL = $1 AND CARACTERES <> \'N\'', array($local));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}

function localAberto($local) {
	$conn = connect();
	$result = pg_query_params($conn, 'SELECT * from reunioes where ACOD_LOCAL = $1 and STATUS in (\'A\') LIMIT 1', array($local));
//,\'P\'
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	if(($row = pg_fetch_assoc($result) ) ) {
		return true;
	}
	return false;
}

function insereReuniao($acodcomissao,$acodtipo,$hora,$data,$local) {
	$conn = connect();
	$row=array();
	$result = pg_query_params($conn, 'SELECT * from reunioes where ACOD_COMISSAO = $1 AND ACOD_TIPO = $2 AND AHOR_REUNIAO = $3 and ADAT_REUNIAO = $4', array($acodcomissao, $acodtipo, $hora,$data));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	if(!($row = pg_fetch_assoc($result) ) ) {
		$result = pg_query_params($conn, 'INSERT INTO reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL) values ($1,$2,$3,$4,$5)', array($acodcomissao,$acodtipo,$hora,$data,$local));
		if ($result === FALSE) {
	           $error = pg_last_error($conn);
		    echo $error;
		}
		insereLogReuniao($acodcomissao,$acodtipo,$hora,$data,$local,'A','I','Inserida.');

		$result = pg_query_params($conn, 'SELECT * from reunioes where ACOD_COMISSAO = $1 AND ACOD_TIPO = $2 AND AHOR_REUNIAO = $3 and ADAT_REUNIAO = $4', array($acodcomissao, $acodtipo, $hora,$data));
		if ($result === FALSE) {
	           $error = pg_last_error($conn);
		    echo $error;
		}
		if(($row = pg_fetch_assoc($result) ) ) {
		}else{
	           $error = pg_last_error($conn);
		    echo $error;
		}
	} else {
#		var_dump($row);
		if($row['acod_local'] != $local){
			insereLogReuniao($acodcomissao,$acodtipo,$hora,$data,$local,'A','L','Local Atualizado.');
			$result = pg_query_params($conn, 'UPDATE reunioes set ACOD_LOCAL = $1 where ACOD_COMISSAO = $2 and ACOD_TIPO = $3 and AHOR_REUNIAO = $4 and ADAT_REUNIAO = $5', array($local,$acodcomissao,$acodtipo,$hora,$data));
			if ($result === FALSE) {
		            $error = pg_last_error($conn);
			    echo $error;
			}

			if($row['status'] == 'A' || $row['status'] == 'P'){
				$result = pg_query_params($conn, 'UPDATE reunioes set STATUS = $1, CARACTERES = $2 where ACOD_LOCAL = $3 AND ACOD_COMISSAO = $4 and ACOD_TIPO = $5 and AHOR_REUNIAO = $6 and ADAT_REUNIAO = $7', array('P','N',$local,$acodcomissao,$acodtipo,$hora,$data));
				if ($result === FALSE) {
			            $error = pg_last_error($conn);
				    echo $error;
				}
				$row['status'] = 'P';
				$row['caracteres'] = 'N';
			}
		}
	}
	return $row;
}

function terminaReunioes(){
	$conn =	connect();
	#echo 'UPDATE reunioes set ACOD_LOCAL = 0';
	#$result = pg_query_params($conn, 'UPDATE reunioes set ACOD_LOCAL = $1', array(0));
	#if ($result === FALSE) {
        #    $error = pg_last_error($conn);
	#    echo $error;
	#}
	$data = date('Y-m-d',strtotime("-1 days"));

	$result = pg_query_params($conn, 'UPDATE reunioes set STATUS = $1 WHERE STATUS IN (\'A\',\'P\') and adat_reuniao <= $2', array('T',$data));

	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$result = pg_query_params($conn, 'UPDATE reunioes set STATUS = $1 WHERE STATUS IN (\'I\') and adat_reuniao <= $2', array('N',$data));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
}

function historicoReunioes(){
	$conn =	connect();
	#echo 'UPDATE reunioes set ACOD_LOCAL = 0';
	#$result = pg_query_params($conn, 'UPDATE reunioes set ACOD_LOCAL = $1', array(0));
	#if ($result === FALSE) {
        #    $error = pg_last_error($conn);
	#    echo $error;
	#}
	$data = date('Y-m-d',strtotime("-5 days"));
#	$result = pg_query_params($conn, 'insert into log_reunioes_historico select * from log_reunioes where data <= $1',array("$data 00:00:00"));
	$result = pg_query_params($conn, 'insert into log_reunioes_historico select * from log_reunioes where adat_reuniao <= $1',array("$data"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
#	$result = pg_query_params($conn, 'delete from log_reunioes where data <= $1',array("$data 00:00:00"));
	$result = pg_query_params($conn, 'delete from log_reunioes where adat_reuniao <= $1',array("$data"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}

	$data = date('Y-m-d',strtotime("-1 days"));
	$result = pg_query_params($conn, 'insert into evento_historico select * from evento where to_date(adat_evento,\'YYYY-MM-DD\') <= to_date($1,\'YYYY-MM-DD\')',array("$data"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$result = pg_query_params($conn, 'delete from evento where to_date(adat_evento,\'YYYY-MM-DD\') <= to_date($1,\'YYYY-MM-DD\')',array("$data"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$data = date('Y-m-d',strtotime("-3 days"));
	$result = pg_query_params($conn, 'insert into log_historico select * from log where data <= $1',array("$data 00:00:00"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$result = pg_query_params($conn, 'delete from log where data <= $1',array("$data 00:00:00"));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
}



function clearReunioes(){
	$conn =	connect();
	#echo 'UPDATE reunioes set ACOD_LOCAL = 0';
	#$result = pg_query_params($conn, 'UPDATE reunioes set ACOD_LOCAL = $1', array(0));
	#if ($result === FALSE) {
        #    $error = pg_last_error($conn);
	#    echo $error;
	#}
	$result = pg_query_params($conn, 'DELETE from reunioes where ADAT_REUNIAO <> $1', array(date("Y-m-d")));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
}

#function clearReunioesOutrosLocais($local){
#	$conn = connect();
#	#echo "DELETE FROM reunioes where ACOD_LOCAL <> $local";
#	$result = pg_query_params($conn, 'DELETE FROM reunioes where ACOD_LOCAL <> $1', array($local));
#	if ($result === FALSE) {
#            $error = pg_last_error($conn);
#	    echo $error;
#	}
#}

function getReunioesHistorico(){
	$conn = connect();
	$data = date('Y-m-d',strtotime("-14 days"));
	$sql = 'SELECT ACOD_COMISSAO,ACOD_TIPO, ADAT_REUNIAO, AHOR_REUNIAO from log_reunioes_historico where tipo = \'A\' and subtipo = \'I\' and data >= $1 order by data';
	$result = pg_query_params($conn, $sql,array("$data 00:00:00"));
	$retval = array();
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		#print_r($result);
		while ($row = pg_fetch_assoc($result) ) {
#			var_dump($row);
			array_push($retval,$row);
		}
	}
	return $retval;
}

function getAcoesReunioes($comissao,$tipo,$data,$hora){
	$conn = connect();
	$sql = 'select subtipo,data,acod_local from (
			select * from log_reunioes 
			where acod_comissao = $1 and acod_tipo = $2 and adat_reuniao = $3 and ahor_reuniao = $4  
			union
			select * from log_reunioes_historico where
			acod_comissao = $1 and acod_tipo = $2 and adat_reuniao = $3 and ahor_reuniao = $4 
			) a  where tipo = \'E\' and subtipo <> \'C\' order by data';
#	echo $sql;
	$result = pg_query_params($conn, $sql,array($comissao,$tipo,$data,$hora));
	$retval = array();
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		#print_r($result);
		while ($row = pg_fetch_assoc($result) ) {
#			var_dump($row);
			array_push($retval,$row);
		}
	}
	return $retval;
}


function getReunioes($local,$status="",$caracteres=""){
	$conn = connect();
	$campos = "SELECT ACOD_COMISSAO as acodcomissao, ACOD_TIPO as acodtipo, AHOR_REUNIAO as hora, ADAT_REUNIAO as data, STATUS as status, CARACTERES as caracteres, ACOD_LOCAL as acodlocal";
	$order = ' order by AHOR_REUNIAO,ADAT_REUNIAO,ACOD_COMISSAO';
	$where = array(' ACOD_LOCAL = $1 ');
	$array = array($local);
	$i = 2;

	if($status != ""){
		array_push($where," STATUS = \$$i ");
		array_push($array,$status);
		$i++;
	}
	if($caracteres != ""){
		array_push($where," CARACTERES = \$$i ");
		array_push($array,$caracteres);
		$i++;
	}
	if(count($where) > 0){
		$where = " WHERE ".join(' AND ',$where);
	} else {
		$where = "";
	}
	$sql = "$campos FROM reunioes $where $order";
#	echo $sql;
#	var_dump($array);
	$result = pg_query_params($conn, $sql,$array);
	$retval = array();
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		#print_r($result);
		while ($row = pg_fetch_assoc($result) ) {
#			var_dump($row);
			array_push($retval,$row);
		}
	}
	return $retval;
}

# status;
# A - ativo
# I - inativo
# P - pausa
# C - seta caracteres.
# T - termina a reunião.
# N - não ocorreu

function countReuniao($acodcomissao,$acodtipo,$hora,$data) {
	$conn =	connect();
	$result = pg_query_params($conn, 'select count(*) as contador from log_reunioes where tipo = \'E\' and subtipo = \'T\' AND ACOD_COMISSAO = $1 AND ACOD_TIPO = $2 AND AHOR_REUNIAO = $3 and ADAT_REUNIAO = $4', array($acodcomissao, $acodtipo, $hora,$data));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	if(!($row = pg_fetch_assoc($result) ) ) {
		return array('codigo'=>500,'mensagem'=>"Reunião não encontrada.");
	}else{
		return array('codigo'=>200,'contador'=>$row['contador']);
	}
}


function updateReuniao($acodcomissao,$acodtipo,$hora,$data,$status,$local) {
	$conn =	connect();
	$result = pg_query_params($conn, 'SELECT * from reunioes where ACOD_COMISSAO = $1 AND ACOD_TIPO = $2 AND AHOR_REUNIAO = $3 and ADAT_REUNIAO = $4', array($acodcomissao, $acodtipo, $hora,$data));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	if(!($row = pg_fetch_assoc($result) ) ) {
		return array('codigo'=>500,'mensagem'=>"Reunião não encontrada.");
	}
	if($status == 'C'){
		insereLogReuniaoCaracteres($acodcomissao,$acodtipo,$hora,$data,$local);
#		echo "UPDATE reunioes set CARACTERES = $1";
		$result = pg_query_params($conn, 'UPDATE reunioes set CARACTERES = $1 where ACOD_LOCAL = $2 ', array('N',$local));
		if ($result === FALSE) {
        	    $error = pg_last_error($conn);
		    echo $error;
		}
#		echo 'UPDATE reunioes set CARACTERES = $1 where ACOD_COMISSAO = $2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5'; var_dump(array($status,$acodcomissao,$acodtipo,$hora,$data));
		$result = pg_query_params($conn, 'UPDATE reunioes set CARACTERES = $1 where ACOD_COMISSAO = $2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5', array($status,$acodcomissao,$acodtipo,$hora,$data));
		if ($result === FALSE) {
        	    $error = pg_last_error($conn);
		    echo $error;
		}

		$result = pg_query_params($conn, 'UPDATE reunioes set STATUS = $1 where ACOD_LOCAL = $2 AND NOT (ACOD_COMISSAO = $3 AND ACOD_TIPO = $4 AND ADAT_REUNIAO = $5 AND AHOR_REUNIAO = $6) AND STATUS=$7', array('P',$local,$acodcomissao,$acodtipo,$data,$hora,'A'));
		if ($result === FALSE) {
        	    $error = pg_last_error($conn);
		    echo $error;
		}

	} else {
		insereLogReuniao($acodcomissao,$acodtipo,$hora,$data,$local,'E',$status,'');
	#	echo 'UPDATE reunioes set STATUS = $1 where ACOD_COMISSAO = $2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5'; var_dump(array($status,$acodcomissao,$acodtipo,$hora,$data));
		$result = pg_query_params($conn, 'UPDATE reunioes set STATUS = $1 where ACOD_COMISSAO = $2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5', array($status,$acodcomissao,$acodtipo,$hora,$data));
		if ($result === FALSE) {
        	    $error = pg_last_error($conn);
		    echo $error;
		}
		if($status == 'A'){
			updateReuniao($acodcomissao,$acodtipo,$hora,$data,'C',$local);
		}elseif($status == 'I' || $status == 'T' || $status == 'N'){
		#	echo "UPDATE reunioes set CARACTERES = $1 where ACOD_COMISSAO =$2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5";
			$result = pg_query_params($conn, 'UPDATE reunioes set CARACTERES = $1 where ACOD_COMISSAO =$2 AND ACOD_TIPO=$3 AND AHOR_REUNIAO=$4 AND ADAT_REUNIAO = $5', array('N',$acodcomissao,$acodtipo,$hora,$data));
			if ($result === FALSE) {
        		    $error = pg_last_error($conn);
			    echo $error;
			}
		}
	}
	return array('codigo'=>200,'mensagem'=>"Reunião modificada.");
}

function verifyArquivoTerminado($local,$dataarquivo,$dataevento,$acodcomissao,$acodtipo,$hora,$data){
	$conn = connect();
	$result = pg_query_params($conn, 'SELECT * from log_reunioes where ACOD_LOCAL = $1 AND TIPO = \'E\' AND SUBTIPO = \'T\' AND DATA>=to_timestamp($2, \'YYYY-MM-DD HH24:MI:SS\') AND DATA < to_timestamp($3, \'YYYY-MM-DD HH24:MI:SS\')AND ACOD_COMISSAO = $4 AND ACOD_TIPO = $5 AND AHOR_REUNIAO = $6 AND ADAT_REUNIAO = $7 ORDER BY DATA DESC  LIMIT 1', array($local,$dataevento,$dataarquivo,$acodcomissao,$acodtipo,$hora,$data));
#	print 'SELECT * from log_reunioes where ACOD_LOCAL = $1 AND TIPO = \'E\' AND SUBTIPO = \'T\' AND DATA>=to_timestamp($2, \'YYYY-MM-DD HH24:MI:SS\') AND DATA < to_timestamp($3, \'YYYY-MM-DD HH24:MI:SS\')AND ACOD_COMISSAO = $4 AND ACOD_TIPO = $5 AND AHOR_REUNIAO = $6 AND ADAT_REUNIAO = $7 ORDER BY DATA DESC  LIMIT 1';
#	var_dump(array($local,$dataevento,$dataarquivo,$acodcomissao,$acodtipo,$hora,$data));
#	echo "<br>";
	if ($result === FALSE) {
		$error = pg_last_error($conn);
		echo $error;
	}
	if(($row = pg_fetch_assoc($result) ) ) {
		return true;
	}
	return false;
}


function getReunioesArquivo($local,$data,$duracao,$arquivo){
	$conn = connect();
	$chaves = array();
#	echo "$local,$data,$duracao,$arquivo\n";
	$result = pg_query_params($conn, 'SELECT DISTINCT * FROM ((SELECT * from log_reunioes where ACOD_LOCAL = $1 AND TIPO = \'E\' AND SUBTIPO = \'C\' AND DATA < to_timestamp($2, \'YYYY-MM-DD HH24:MI:SS\') ORDER BY DATA DESC  LIMIT 1) UNION SELECT * from log_reunioes where ACOD_LOCAL=$1 AND TIPO =\'E\' AND SUBTIPO=\'C\' AND DATA >= to_timestamp($2, \'YYYY-MM-DD HH24:MI:SS\') AND DATA <= to_timestamp($2, \'YYYY-MM-DD HH24:MI:SS\') + $3::time) tabela ORDER BY DATA', array($local,$data,$duracao));

	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	$verifier = array();
	while(($row = pg_fetch_assoc($result) ) ) {
#		var_dump($row);
		$comissao = $row['acod_comissao']; $tipo = $row['acod_tipo'];
		$hora = $row['ahor_reuniao']; $dia = $row['adat_reuniao'];
		$datareu = $row['data'];
		if(verifyArquivoTerminado($local,$data,$datareu,$comissao,$tipo,$hora,$dia)){
			continue;
		}
		if(!array_key_exists("$comissao|$tipo|$hora|$dia",$verifier)){
			$verifier["$comissao|$tipo|$hora|$dia"] = 1;
			array_push($chaves,array('comissao'=>$comissao,'tipo'=>$tipo,'hora'=>$hora,'dia'=>$dia,'data'=>$datareu));
		}
	}
	return $chaves;
}

function getNomeEvento($id_evento){
	$conn = connect();
	$retval = array();
	$data = date('Y-m-d',strtotime("-10 days"));
	$result = pg_query_params($conn, 'SELECT atit_evento from (SELECT * from evento UNION SELECT * from evento_historico where adat_evento >= $1) tabela WHERE acod_evento = $2',array($data,$id_evento));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$retval = "";
	while(($row = pg_fetch_assoc($result))) {
		$retval = $row['atit_evento'];
	}
	return $retval;
}

function getTipoEvento($id_evento){
	$conn = connect();
	$retval = array();
	$data = date('Y-m-d',strtotime("-10 days"));
	$result = pg_query_params($conn, 'SELECT tipo_evento from (SELECT * from evento UNION SELECT * from evento_historico where adat_evento >= $1) tabela WHERE acod_evento = $2',array($data,$id_evento));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$retval = "";
	while(($row = pg_fetch_assoc($result))) {
		$retval = $row['tipo_evento'];
	}
	return $retval;
}

function getDescricaoEvento($id_evento){
	$conn = connect();
	$retval = array();
	$data = date('Y-m-d',strtotime("-10 days"));
	$result = pg_query_params($conn, 'SELECT atxt_descricao from (SELECT * from evento UNION SELECT * from evento_historico where adat_evento >= $1) tabela WHERE acod_evento = $2',array($data,$id_evento));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$retval = "";
	while(($row = pg_fetch_assoc($result))) {
		$retval = $row['atxt_descricao'];
	}
	return $retval;
}

function getPlaylistEvento($id_evento){
	$conn = connect();
	$retval = array();
	$data = date('Y-m-d',strtotime("-10 days"));
	$result = pg_query_params($conn, 'SELECT playlist_youtube from (SELECT * from evento UNION SELECT * from evento_historico where adat_evento >= $1) tabela WHERE acod_evento = $2',array($data,$id_evento));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	}
	$retval = "";
	while(($row = pg_fetch_assoc($result))) {
		$retval = $row['playlist_youtube'];
	}
	return $retval;
}


function getReunioesYoutube(){
	$conn = connect();
	$retval = array();
	$data = date('Y-m-d',strtotime("-4 days"));

	$result = pg_query_params($conn, 'SELECT * from (SELECT * from log_reunioes UNION SELECT * from log_reunioes_historico where adat_reuniao >= $1) tabela  where ACOD_LOCAL > 0 AND( TIPO = \'L2\' OR TIPO = \'Y\' OR TIPO = \'F\') ORDER BY DATA',array($data));
#$result = pg_query_params($conn, 'SELECT * from (SELECT * from log_reunioes UNION SELECT * from log_reunioes_historico where adat_reuniao >= $1) tabela  where ( TIPO = \'L2\' OR TIPO = \'Y\' OR TIPO = \'F\') ORDER BY DATA',array($data));
#	$result = pg_query_params($conn, 'SELECT * from log_reunioes where TIPO = \'L2\' OR TIPO = \'Y\' OR TIPO = \'F\' UNION SELECT * FROM log_reunioes_historico where acod_comissao < 0 and adat_reuniao >= \'2017-11-22\' ORDER BY DATA',array());

	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}

	while(($row = pg_fetch_assoc($result))) {
		$chave = "$row[acod_comissao]_$row[acod_tipo]_$row[adat_reuniao]_$row[ahor_reuniao]";
		$chave=preg_replace("/[:\-]/","_",$chave);
		$comissao = $row['acod_comissao']; $tipor = $row['acod_tipo'];
		$hora = $row['ahor_reuniao']; $dia = $row['adat_reuniao'];
		$dia2 = str_replace("-","/",$dia);
		$tipo = trim($row['tipo']); $subtipo = trim($row['subtipo']);
		$descricao = $row['descricao'];
		if(!array_key_exists($chave,$retval)){
			$retval[$chave] = array('linha1'=>'','linha2'=>'','youtube'=>"",'arquivo'=>"","hora"=>"$hora","data"=>"$dia","datab"=>$dia2,"comissao"=>$comissao,"tipo"=>$tipor);
		}
		if($tipo == 'L2' && $subtipo == 'B'){
			$retval[$chave]['linha1'] = $descricao;
		} elseif($tipo == 'L2' && $subtipo == 'C'){
			$retval[$chave]['linha2'] = $descricao;
		} elseif($tipo == 'Y'){
			$retval[$chave]['youtube'] = $descricao;
		} elseif($tipo == 'F'){
			$retval[$chave]['arquivo'] = $descricao;
		}

	}
	return $retval;
}

function getChavesYoutube(){
	$conn = connect();
	$retval = array();
	
	$data = date('Y-m-d',strtotime("-7 days"));

	$result = pg_query_params($conn, 'SELECT * from (select * from log_reunioes UNION select * from log_reunioes_historico where adat_reuniao >= $1) foo where TIPO = \'Y\' ORDER BY DATA',array($data));
#	echo 'SELECT * from (select * from log_reunioes UNION select * from log_reunioes_historico) foo where TIPO = \'Y\' ORDER BY DATA';
#	$result = pg_query_params($conn, 'SELECT * from log_reunioes where TIPO = \'L2\' OR TIPO = \'Y\' OR TIPO = \'F\' UNION SELECT * FROM log_reunioes_historico where acod_comissao < 0 and adat_reuniao >= \'2017-11-22\' ORDER BY DATA',array());

	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}

	while(($row = pg_fetch_assoc($result))) {
		array_push($retval,$row['descricao']);
	}
	return $retval;
}



function insereLogArquivo($local,$data,$arquivo,$camera,$timezone,$duracao){
	#echo "INSERE $local,$data,$arquivo,$camera,$timezone\n";
	$reunioes = getReunioesArquivo($local,$data,$duracao,$arquivo);
#	var_dump($reunioes);
	$conn =	connect();

	$result = pg_query($conn, "SET TIME ZONE $timezone");
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
#	var_dump(array($comissao,$tipo,$hora,$dia,$local,"G","$camera","$data","$arquivo"));
#	return;
	$retval = array();
	foreach ($reunioes as $reuniao){
		$chave = "$reuniao[comissao]_$reuniao[tipo]_$reuniao[hora]_$reuniao[dia]";
		$chave=preg_replace("/[:\-]/","_",$chave);
#		echo $chave;
	#	echo 'INSERT INTO log_reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,TIPO,SUBTIPO,DATA,DESCRICAO) values ($1,$2,$3,$4,$5,$6,$7,to_timestamp($8, \'YYYY-MM-DD HH24:MI:SS\'),$9)';
	#	var_dump(array($reuniao['comissao'],$reuniao['tipo'],$reuniao['hora'],$reuniao['dia'],$local,"G","$camera","$data","$arquivo,$duracao"));
		$result = pg_query_params($conn, 'INSERT INTO log_reunioes(ACOD_COMISSAO,ACOD_TIPO,AHOR_REUNIAO,ADAT_REUNIAO,ACOD_LOCAL,TIPO,SUBTIPO,DATA,DESCRICAO) values ($1,$2,$3,$4,$5,$6,$7,to_timestamp($8, \'YYYY-MM-DD HH24:MI:SS\'),$9)', array($reuniao['comissao'],$reuniao['tipo'],$reuniao['hora'],$reuniao['dia'],$local,"G","$camera","$data","$arquivo,$duracao"));
		if ($result === FALSE) {
			$error = pg_last_error($conn);
			if(preg_match("/log_reunioes_pkey/",$error)){
				$retval[$chave] = "OK Already in.";
			} else {
				$retval[$chave] = $error;
			}
		} else {
			$retval[$chave] = "OK";
		}
	}
	return $retval;
}

function selectLogArquivo($data){
	$conn =	connect();

	$result = pg_query_params($conn, 'SELECT * from log_reunioes where ACOD_LOCAL > 0 AND ADAT_REUNIAO = $1 AND (TIPO = \'G\' OR (TIPO = \'E\' AND (SUBTIPO = \'C\' OR SUBTIPO = \'I\'))) ORDER BY DATA', array($data));

	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
	$retval = array();
	while(($row = pg_fetch_assoc($result))) {
		$chave = "$row[acod_comissao]_$row[acod_tipo]_$row[adat_reuniao]_$row[ahor_reuniao]";
		$chave=preg_replace("/[:\-]/","_",$chave);
		if(!array_key_exists($chave,$retval)){
			$retval[$chave]=array('files'=>array());
		}
		$cam = trim($row['subtipo']);
		if(trim($row['tipo']) == 'G'){
			$local = $row['acod_local'];
			if(!array_key_exists($cam,$retval[$chave]['files'])){
				$retval[$chave]['files'][$cam] = array();
			}
			if(preg_match("/^(.+?),(.+)$/",$row['descricao'],$matches)){
				$arquivo = $matches[1];
				$duracao = $matches[2];
				if(preg_match("/^(.+)(\d)[\-_](\d+)-(\d+)-(\d+)[\-_](\d+)[\-h](\d+)[\-m](\d+)[\-s]+(.+)\.flv/",$arquivo,$matches)) {
					$mydata = "$matches[3]-$matches[4]-$matches[5] $matches[6]:$matches[7]:$matches[8]";
					$tz = ($matches[9]='BRT' || $matches[9] == '03')?"-3":"-2";
					$reunioes = getReunioesArquivo($local,$mydata,$duracao,$arquivo);
					if(count($reunioes) > 0){
						array_push($retval[$chave]['files'][$cam],array('file'=>"$arquivo", 'reunioes'=>$reunioes));
					}
				}
			}
		}elseif((trim($row['tipo']) == 'E') && trim($row['subtipo']) == 'I'){
			$retval[$chave]=array('files'=>array());
		}
	}

#	$result = pg_query_params($conn, 'SELECT * from log_reunioes where ADAT_REUNIAO = $1 AND (TIPO = \'E\' and SUBTIPO = \'T\') and data < now() - interval \'2 hour\'  ORDER BY DATA', array($data));
#
#	if ($result === FALSE) {
#           $error = pg_last_error($conn);
#	    echo $error;
#	}

	return $retval;
}

function insereEventoSeNaoExiste($adat_evento,$ahor_evento,$atit_evento,$acod_local=100,$playlist_youtube="",$atxt_descricao=""){
	$conn = connect();
	$sql = "SELECT evento.*,ANOM_LOCAL FROM evento left join locais using(acod_local) where adat_evento = $1 and ahor_evento=$2 and atit_evento = $3 ORDER BY ACOD_EVENTO";
	$result = pg_query_params($conn, $sql,array($adat_evento,$ahor_evento,$atit_evento));
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		if ($row = pg_fetch_assoc($result) ) {
#			echo "$row[acod_evento]";
			updateEvento($row['acod_evento'],$atit_evento,$adat_evento,$ahor_evento,$acod_local,$row['atxt_fixo'],$row['atxt_slide'],$row['tipo_evento'],$row['facebook'],$row['caracteres'],$atxt_descricao);
			updateEventoPlaylist($adat_evento,$ahor_evento,$acod_local,$playlist_youtube);
			commit();
			return false;
		}else{
			insereEvento($atit_evento,$adat_evento,$ahor_evento,$acod_local,"","","E","N","N",$atxt_descricao);
			updateEventoPlaylist($adat_evento,$ahor_evento,$acod_local,$playlist_youtube);
			commit();
		}
	}
	return true;
}

function insereEvento($atit_evento,$adat_evento,$ahor_evento,$acod_local,$atxt_fixo,$atxt_slide,$tipo_evento,$facebook,$caracteres,$atxt_descricao){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'INSERT INTO evento(ATIT_EVENTO,ADAT_EVENTO,AHOR_EVENTO,ACOD_LOCAL,ATXT_FIXO,ATXT_SLIDE,TIPO_EVENTO,FACEBOOK,CARACTERES,ATXT_DESCRICAO) values ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)', array($atit_evento,$adat_evento,$ahor_evento,$acod_local,$atxt_fixo,$atxt_slide,$tipo_evento,$facebook,$caracteres,$atxt_descricao));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}

function updateEvento($acod_evento,$atit_evento,$adat_evento,$ahor_evento,$acod_local,$atxt_fixo,$atxt_slide,$tipo_evento,$facebook,$caracteres,$atxt_descricao){
	#echo "INSERE $acodcomissao,$acodtipo,$hora,$data,$local,$estado\n";
	$conn =	connect();
	$result = pg_query_params($conn, 'UPDATE evento SET ATIT_EVENTO=$1,ADAT_EVENTO=$2,AHOR_EVENTO=$3,ACOD_LOCAL=$4,ATXT_FIXO=$5,ATXT_SLIDE=$6,TIPO_EVENTO=$7,FACEBOOK=$8,CARACTERES=$9,ATXT_DESCRICAO=$10 WHERE ACOD_EVENTO = $11', array($atit_evento,$adat_evento,$ahor_evento,$acod_local,$atxt_fixo,$atxt_slide,$tipo_evento,$facebook,$caracteres,$atxt_descricao,$acod_evento));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}


function updateEventoPlaylist($adat_evento,$ahor_evento,$acod_local,$playlist_youtube){
	$conn =	connect();
	$result = pg_query_params($conn, 'UPDATE evento SET PLAYLIST_YOUTUBE=$1 WHERE adat_evento=$2 AND ahor_evento=$3 and acod_local = $4', array($playlist_youtube,$adat_evento,$ahor_evento,$acod_local));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}


function getEventos($acod_evento="undef",$acod_local="undef",$adat_evento=""){
	$conn = connect();
	error_log("getEve: $acod_evento, $acod_local, $adat_evento");
	if($acod_evento !== "undef"){
		$sql = 'SELECT * FROM evento left join locais using(acod_local) where ACOD_EVENTO = $1';
		$result = pg_query_params($conn, $sql,array($acod_evento));
	}elseif($acod_local !== "undef"){
		$sql = 'SELECT * FROM evento left join locais using(acod_local) where ACOD_LOCAL = $1 AND ADAT_EVENTO = $2';
		error_log($sql);
#		if($acod_local == 24){
#			echo "$sql $adat_evento";
#		}
		$result = pg_query_params($conn, $sql,array($acod_local,$adat_evento));
	} else {
		$sql = "SELECT evento.*,ANOM_LOCAL FROM evento left join locais using(acod_local) ORDER BY ACOD_EVENTO";
		$result = pg_query($conn, $sql);
	}

	$retval = array();
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		while ($row = pg_fetch_assoc($result) ) {
			array_push($retval,$row);
		}
	}
	return $retval;
}

function removeEvento($acod_evento){
	$conn =	connect();
	$result = pg_query_params($conn, 'DELETE FROM evento WHERE ACOD_EVENTO = $1', array($acod_evento));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	   echo $error;
	}
}

function updateLocal($acod_local,$status_local){
	$conn =	connect();
	$result = pg_query_params($conn, 'UPDATE locais set status_local = $1 WHERE acod_local = $2', array($status_local,$acod_local));
	if ($result === FALSE) {
           $error = pg_last_error($conn);
	    echo $error;
	}
}

function getLocal($acod_local="undef"){
	$conn =	connect();
	if($acod_local != "undef"){
		$sql = "SELECT * from locais where ACOD_LOCAL = $1";
		$result = pg_query_params($conn, $sql, array($acod_local));
	}else{
		$sql = "SELECT * from locais order by acod_local";
		$result = pg_query($conn, $sql);
	}

	$retval = array();
	if ($result === FALSE) {
            $error = pg_last_error($conn);
	    echo $error;
	} else {
		while ($row = pg_fetch_assoc($result) ) {
			array_push($retval,$row);
		}
	}
	return $retval;
}


?>
