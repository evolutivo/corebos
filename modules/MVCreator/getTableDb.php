<?php
include "modules/MVCreator/dbclass.php";
$db=$_POST['nameDb'];
$selTab='';
$connect = new MysqlClass();
$connect->connetti($db);
$tabella=$connect->getTableList($db);
$connect->disconnettiMysql();
for($i=0;$i<count($tabella);$i++){ 
    $selTab=$selTab."<li><label for=".$tabella[$i]."><input type='checkbox' name='myCheck[]' id='myCheck' value='".$tabella[$i]."' class='checkbox'>".$tabella[$i]."</label></li>";      
}
echo $selTab;
?>