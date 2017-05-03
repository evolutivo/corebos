<?php
global $log;
require_once ('include/utils/utils.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once('config.inc.php');
require_once ('data/Tracker.php');
$dbname = $dbconfig['db_name'];
$content=array();
$reportId = $_POST['reportId'];
$result = $adb->pquery("SELECT * from  $dbname.vtiger_selectcolumn 
                        where queryid = ? order by columnindex",array($reportId));
$num_rows=$adb->num_rows($result);
if($num_rows!=0){
    for($i=0;$i<=$num_rows;$i++)
	{
            if($adb->query_result($result,$i,'columnname')!='none'){
                $cn=explode(":",$adb->query_result($result,$i,'columnname'));
		$f = getTranslatedString($cn[2]);
                $index = $adb->query_result($result,$i,'columnindex');
                $id =$cn[0].'.'.$cn[1];
                $f1=str_replace("_"," ",utf8_encode(html_entity_decode($f)));
                $n++;

$content[$i]['value'] = $adb->query_result($result,$i,'fieldname'.$i);
$content[$i]['text'] = getTranslatedString($adb->query_result($result,$i,$id.'|'.$index));
}
}
}
?>
