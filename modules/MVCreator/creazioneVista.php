<?php
include "modules/MVCreator/dbclass.php";

$connect = new MysqlClass();
$connect->connettiMysql();
echo "<div id='divView'>";
echo "<div class='allinea' id='labelNameView'><p>Nome della vista fffffffffff</p></div>";
echo "<div class='allinea' type='text' id='nameViewDiv'><input id='nameView' name='nameView'></div>";
echo "</div>";
echo showInstallationDb();
echo "<div id='tabForm'>";
echo "<ul id='selTab'></ul>";
echo "<button class='pulsante' id='sendTab' onclick='openMenuJoin2()'>Next</button>";
$connect->disconnettiMysql();
global $adb;
function makeDbList() {
    $res = mysql_query("SHOW DATABASES");
    $i=0;
    $dbList=array();
    while ($row = mysql_fetch_assoc($res)){
        $dbList[$i]=$row['Database'];
        $i++;
    }
    $picklist="<div class='selDataBase' id='selDb'>";
    $picklist=$picklist."<select class='dbList' id='dbList' name='dbList' onchange='selDB()' >";
    $picklist=$picklist."<option selected='selected' disabled='disabled'>Select Installation</option>";
    for($j=0; $j<count($dbList); $j++) {  
        $picklist=$picklist."<option id=\"".$dbList[$j]."\">".$dbList[$j]."</option>\"";
    } 
    $picklist=$picklist."</select></div>";
    return  $picklist;
    }
    
 function showInstallationDb() {
     global $adb;
    $res = $adb->query("Select * from vtiger_accountinstallation
                        join vtiger_crmentity on crmid =  accountinstallationid where deleted=0");
    $picklist="<div class='selDataBase' id='selDb'>";
    $picklist = $picklist."<select class='dbList' id='dbList' name='dbList' onchange='selDB(this)' >";
     $picklist=$picklist."<option selected='selected' disabled='disabled'>Select Installation:</option>";
    $nr = $adb->num_rows($res);
    for($i=0;$i<$nr;$i++){
        $dbname = $adb->query_result($res,$i,'dbname');
        $acin_no = $adb->query_result($res,$i,'acin_no');
        $acinstallationname = $adb->query_result($res,$i,'acinstallationname');
        $accountinstallationid = $adb->query_result($res,$i,'accountinstallationid');
        $picklist=$picklist."<option id=\"".$accountinstallationid."-".$acin_no.$dbname."\">".$acinstallationname."</option>\"";
    }
    
   
    $picklist=$picklist."</select></div>";
    return  $picklist;
    }       
    
 ?>
    

