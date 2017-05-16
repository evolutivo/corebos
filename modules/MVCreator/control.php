<?php
include "modules/MVCreator/dbclass.php";
$db=$_POST['nameDb'];
$connect = new MysqlClass();
$connect->connetti($db);
$query = $_POST['query'];
if(controlloQuery($query)){
    echo " <b>Query inserita corretta</b>";
}
else{
    echo " <b>Attenzione la query inserita è errata, si prega di reinserirla</b>";
}

function controlloQuery($stringa){
    $position=strpos($stringa, "AS");
    $query =substr($stringa, $position+2);
    $result = mysql_query($query); 
    return $result;
}

?>
