<?php
require_once('config.inc.php');
global $log, $adb;
$dbname = $dbconfig['db_name'];
$content=array();
$reportId = $_REQUEST['filter']['filters'][0]['value'];
$result = $adb->pquery("SELECT * from  $dbname.vtiger_selectcolumn 
                        where queryid = ? and columnname<> 'none' order by columnindex",array($reportId));
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
    for($i=0;$i<$num_rows;$i++)
	{
                $cn=explode(":",$adb->query_result($result,$i,'columnname'));
		$f = getTranslatedString($cn[2]);
                $index = $adb->query_result($result,$i,'columnindex');
                $id =$cn[0].':'.$cn[1].":".$index;
                $f1=str_replace("_"," ",utf8_encode(html_entity_decode($f)));
$content[$i]['groupId'] = $id ;
$content[$i]['groupValue'] = $f1;
}
echo json_encode($content);
}
?>