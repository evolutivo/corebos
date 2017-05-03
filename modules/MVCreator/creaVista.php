<?php
include "dbclass.php";
$db=$_POST['nameDb'];
$connect = new MysqlClass();
$connect->connetti($db);
$query = $_POST['query'];
$nomeVista = $_POST['nameView'];;
$result="";
$creazioneTabella="";
$inserimento="";
$queryInserimento="insert into viste (nome_vista, query) values ('$nomeVista', '$query')";

if(controlloQuery($query)){
     $result = mysql_query($query); 
     if (!table_exists("viste", $db)) {
	$queryCreazioneTabella="create table viste (nome_vista varchar(100), query varchar (9000));";
        $creazioneTabella=mysql_query($queryCreazioneTabella); 
    }
    $inserimento=mysql_query($queryInserimento);
    echo ' <b>La vista materializzata "'.$nomeVista.'" è stata creata con successo!</b>';
}
else{
    echo " <b>Attenzione la query inserita è errata, si prega di ricontrollare i parametri inseriti!</b>";
}
$connect->disconnettiMysql();

function controlloQuery($stringa){
    $position=strpos($stringa, "AS");
    $query =substr($stringa, $position+2);
    $result = mysql_query($query); 
    return $result;
}
function table_exists($table, $db) { 
	$tables = mysql_list_tables ($db); 
	while (list ($temp) = mysql_fetch_array ($tables)) {
		if ($temp == $table) {
			return TRUE;
		}
	}
	return FALSE;
}
?>
