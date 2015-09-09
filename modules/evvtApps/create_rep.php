<script src="modules/evvtApps/js/evvtapps.js" type="text/javascript"></script>

<?php
global $adb, $log, $app_strings, $current_user,$currentModule;

require_once('Smarty_setup.php');
require_once('config.inc.php');
require_once("data/Tracker.php");
require_once('modules/Contacts/Contacts.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/database/Postgres8.php');
     $id=$current_user->id;
     $db1=$_POST['db1'];
     $db2=$_POST['db2'];
$show.="<div style='height:900px' align='center'><table width='80%'>";
$show.="<tr><td width='10%' align='center' class='lvtCol' style='font-size:14px'><strong>Orario</strong></td><td class='lvtCol' width='15%' align='center' style='font-size:14px'><strong>Titolo</strong></td><td width='7%' class='lvtCol' align='center' style='font-size:14px' ><font color='black'><strong>Database di appartenenza</strong></font></td></tr>
    <tr height='10'><td style='font-size:16px;background-color:#D0D0D0></td><td></td><td style='font-size:16px;background-color:#D0D0D0></td></tr>";



$qus=$adb->query("select activityid, date_start, time_start,subject,eventstatus,activitytype,due_date,time_end from vtiger_activity join vtiger_crmentity on crmid=activityid and deleted=0 where date_start>'2011-12-31' union
    select activityid, date_start, time_start,subject,eventstatus,activitytype,due_date,time_end from $db1.vtiger_activity join vtiger_crmentity on crmid=activityid and deleted=0 where date_start>'2011-12-31'
    union select activityid, date_start, time_start,subject,eventstatus,activitytype,due_date,time_end from $db2.vtiger_activity join vtiger_crmentity on crmid=activityid and deleted=0 where date_start>'2011-12-31'  order by date_start,time_start");
       for($j=0;$j<$adb->num_rows($qus);$j++){
        $rec=date("d-m-Y",strtotime($adb->query_result($qus,$j,"date_start"))).' '.$adb->query_result($qus,$j,"time_start");
                                  $d=date("d-m-Y",strtotime($adb->query_result($qus,$j,"due_date")));
if($d=="01-01-1970") $d="";
        $de[$j]=$d.' '.$adb->query_result($qus,$j,"time_end");

        $tt=$adb->query_result($qus,$j,"subject");
                $status[$j]=$app_strings[$adb->query_result($qus,$j,"eventstatus")];
                                $type[$j]=$adb->query_result($qus,$j,"activitytype");

        $tt=$adb->query_result($qus,$j,"subject");

    $mon=$adb->pquery("select activityid, date_start, time_start,subject from vtiger_activity where activityid=?",array($adb->query_result($qus,$j,"activityid")));
         $bio=$adb->pquery("select activityid, date_start, time_start,subject from $db1.vtiger_activity where activityid=?",array($adb->query_result($qus,$j,"activityid")));

    if($adb->num_rows($mon)!=0)
                $db=$dbconfig['db_name'];
        else if($adb->num_rows($bio)!=0)
                $db=$db1;
                else $db=$db2;
     $show.= "<tr><td width='10%'  align='center' style='font-size:16px; background-color:#D0D0D0' onmouseover=\"fnvshNrm('show$j'); posLay(this,'show$j'); \" onmouseout='hide_tc($j)'><b><input type='hidden' name='db' id='db' value='$db' ><div id='show$j' style='display:none;z-index:10000000001; width:350px; font-size:12px; text-align:left'  class=\"layerPopup thickborder\"><b>Dettagli</b> <br><br><br>Stato: $status[$j] &nbsp;&nbsp; Tipo: $type[$j]<br>Data fine: $de[$j]</div>$rec<b/></td><td width='7%' align='center' style='font-size:14px; background-color:#F8F8F8 '>$tt</td><td width='7%'align='center' style='font-size:14px;background-color:#D0D0D0 ' >$db</td></tr>";
      }

$show.="</table>";

 $show.= "<br><div id='res'></div></div>";
 echo $show;
?>
