<?php
include "modules/MVCreator/dbclass.php";
 $db=$_POST['nameDbViews'];
// istanza della classe
$connect = new MysqlClass();
// chiamata alla funzione di connessione
$vista='';
$selTab='';
$connect->connetti($db);
$i=0;
$query="select nome_vista from viste;";
$risultato = mysql_query($query);

while ($riga = mysql_fetch_array($risultato, MYSQL_ASSOC)) {
   $vista[$i]=$riga["nome_vista"];
   $i++;
}

if ($vista!=''){
for($i=0;$i<count($vista);$i++){ 
    $selTab=$selTab."<option id='".$vista[$i]."' name='".$vista[$i]."'>".$vista[$i]."</option>";      
} 
$connect->disconnettiMysql();
echo $selTab;
}     
?>



