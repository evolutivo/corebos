
<?php
/***********************************************************************************
 * The contents of this file are subject to the Evolutivo BPM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  JPL TSolucio, S.L. Open Source
 * The Initial Developer of the Original Code is JPL TSolucio, S.L.
 * Portions created by JPL TSolucio, S.L. are Copyright (C) JPL TSolucio, S.L.
 * All Rights Reserved.
 ************************************************************************************/

// pie graph needs this, but we don't for this vtApp, so I put a stub
//koment

require_once("modules/evvtApps/vtapps/baseapp/vtapp.php");

class Dealer extends vtApp {
	
	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = false;
	var $wwidth = 450;
	var $wheight = 450;

	public function getContent($lang) {
            require_once('Smarty_setup.php');
 global $adb,$current_user,$log;


$today1=date("Y-m-d H:i:s");
$today=substr($today1,0,10);
//echo $today;
$log->debug("datasot".$today);
$year=substr($today,0,4);
//echo $year;
$month=substr($today,5,2);
//echo $month;
$day=substr($today,8,2);
//echo $day;
if ($current_user->is_admin=='off')
{$query3=$adb->query("select P.projectname, P.linktobuyer AS 'Project_LBLGROUPBUYER', P.rma AS 'Project_RMA', P.startdate AS 'Project_Start_Date', P.substatusproj AS 'Project_Substatus', P.statopartech AS 'Project_Stato_parte_chiamata',vtiger_messages.msgdescription AS 'Messages_Message',P.projectid AS 'dy',vtiger_accountRelProject1306.accountname AS 'tre',vtiger_accountRelProject1306.accountid AS 'kater',vtiger_messages.messagesid AS 'pese',P.spar_associato,P.differ,vtiger_crmentity.smownerid,P.giorniapertura,P.projectstatus,P.dayson,vtiger_messages.messagesid,vtiger_users.user_name,vtiger_crmentity.createdtime,vtiger_messages.msgdescription AS 'description',B.modifiedtime AS 'creation',P.projectid from vtiger_project P
                inner join vtiger_crmentity on vtiger_crmentity.crmid=P.projectid
                        
            left join vtiger_users as vtiger_usersProject on vtiger_usersProject.id = vtiger_crmentity.smownerid
                      
            left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid left join vtiger_crmentity as vtiger_crmentityRelProject1306 on vtiger_crmentityRelProject1306.crmid = P.linktobuyer and vtiger_crmentityRelProject1306.deleted=0 left join vtiger_account as vtiger_accountRelProject1306 on vtiger_accountRelProject1306.accountid = vtiger_crmentityRelProject1306.crmid  left join vtiger_messages as vtiger_messages on  vtiger_messages.project = P.projectid  join vtiger_crmentity B on
     B.crmid=messagesid WHERE B.modifiedtime like '%$year%\-%$month%\-$day%' and vtiger_messages.messagecategory like 'Messaggio sollecito' and vtiger_crmentity.deleted=0   and (( P.progetto = '96221' or P.progetto = '95984' or P.progetto='162799' or P.progetto='977173' or P.progetto='2111822' or P.progetto='1578775' or P.progetto='2054186' )  and ( P.projectstatus not like '%hold%' and P.projectstatus not like '%arch%' and (P.projectstatus like '%Chiamata NSE%' or P.projectstatus like '%Riparazione%' or P.projectstatus like '%--none--%') )  and ( P.substatusproj not like '%canc%' and P.substatusproj not like '%clos%' ) and vtiger_crmentity.smownerid='$current_user->id' and P.differ>=0 ) ORDER BY P.projectid
             
");
$query4=$adb->query("select P.projectname, P.linktobuyer AS 'Project_LBLGROUPBUYER', P.rma AS 'Project_RMA', P.startdate AS 'Project_Start_Date', P.substatusproj AS 'Project_Substatus', P.statopartech AS 'Project_Stato_parte_chiamata',vtiger_messages.msgdescription AS 'Messages_Message',P.projectid AS 'dy',vtiger_accountRelProject1306.accountname AS 'tre',vtiger_accountRelProject1306.accountid AS 'kater',vtiger_messages.messagesid AS 'pese',P.spar_associato,P.differ,vtiger_crmentity.smownerid,P.giorniapertura,P.projectstatus,P.dayson,vtiger_messages.messagesid,vtiger_users.user_name,vtiger_crmentity.createdtime,vtiger_messages.msgdescription AS 'description',B.modifiedtime AS 'creation',P.projectid from vtiger_project P
                inner join vtiger_crmentity on vtiger_crmentity.crmid=P.projectid
                        
            left join vtiger_users as vtiger_usersProject on vtiger_usersProject.id = vtiger_crmentity.smownerid
                      
            left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid left join vtiger_crmentity as vtiger_crmentityRelProject1306 on vtiger_crmentityRelProject1306.crmid = P.linktobuyer and vtiger_crmentityRelProject1306.deleted=0 left join vtiger_account as vtiger_accountRelProject1306 on vtiger_accountRelProject1306.accountid = vtiger_crmentityRelProject1306.crmid  left join vtiger_messages as vtiger_messages on  vtiger_messages.project = P.projectid  join vtiger_crmentity B on
B.crmid=messagesid WHERE B.modifiedtime like '%$year%\-%$month%\-$day%' and vtiger_messages.messagecategory like 'CLAIM OPEN%' and vtiger_crmentity.deleted=0   and (( P.progetto = '96221' or P.progetto = '95984' or P.progetto='162799' or P.progetto='977173' or P.progetto='2111822' or P.progetto='1578775' or P.progetto='2054186' )  and ( P.projectstatus not like '%hold%' and P.projectstatus not like '%arch%' and (P.projectstatus like '%Chiamata NSE%' or P.projectstatus like '%Riparazione%' or P.projectstatus like '%--none--%'))  and ( P.substatusproj not like '%canc%' and P.substatusproj not like '%clos%' ) and vtiger_crmentity.smownerid='$current_user->id' and P.differ>=0 ) ORDER BY P.projectid");}
else
   { $query3=$adb->query("select P.projectname, P.linktobuyer AS 'Project_LBLGROUPBUYER', P.rma AS 'Project_RMA', P.startdate AS 'Project_Start_Date', P.substatusproj AS 'Project_Substatus', P.statopartech AS 'Project_Stato_parte_chiamata',vtiger_messages.msgdescription AS 'Messages_Message',P.projectid AS 'dy',vtiger_accountRelProject1306.accountname AS 'tre',vtiger_accountRelProject1306.accountid AS 'kater',vtiger_messages.messagesid AS 'pese',P.spar_associato,P.differ,vtiger_crmentity.smownerid,P.giorniapertura,P.projectstatus,P.dayson,vtiger_messages.messagesid,vtiger_users.user_name,vtiger_crmentity.createdtime,vtiger_messages.msgdescription AS 'description',B.modifiedtime AS 'creation',P.projectid from vtiger_project P
                inner join vtiger_crmentity on vtiger_crmentity.crmid=P.projectid
                        
            left join vtiger_users as vtiger_usersProject on vtiger_usersProject.id = vtiger_crmentity.smownerid
                      
            left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid left join vtiger_crmentity as vtiger_crmentityRelProject1306 on vtiger_crmentityRelProject1306.crmid = P.linktobuyer and vtiger_crmentityRelProject1306.deleted=0 left join vtiger_account as vtiger_accountRelProject1306 on vtiger_accountRelProject1306.accountid = vtiger_crmentityRelProject1306.crmid  left join vtiger_messages as vtiger_messages on  vtiger_messages.project = P.projectid  join vtiger_crmentity B on
     B.crmid=messagesid WHERE B.modifiedtime like '%$year%\-%$month%\-$day%' and vtiger_messages.messagecategory like 'Messaggio sollecito' and vtiger_crmentity.deleted=0   and (( P.progetto = '96221' or P.progetto = '95984' or P.progetto='162799' or P.progetto='977173' or P.progetto='2111822' or P.progetto='1578775' or P.progetto='2054186' )   and ( P.projectstatus not like '%hold%' and P.projectstatus not like '%arch%' and (P.projectstatus like '%Chiamata NSE%' or P.projectstatus like '%Riparazione%' or P.projectstatus like '%--none--%' ))  and ( P.substatusproj not like '%canc%' and P.substatusproj not like '%clos%' )  and P.differ>=0 ) ORDER BY P.projectid");
    $query4=$adb->query("select P.projectname, P.linktobuyer AS 'Project_LBLGROUPBUYER', P.rma AS 'Project_RMA', P.startdate AS 'Project_Start_Date', P.substatusproj AS 'Project_Substatus', P.statopartech AS 'Project_Stato_parte_chiamata',vtiger_messages.msgdescription AS 'Messages_Message',P.projectid AS 'dy',vtiger_accountRelProject1306.accountname AS 'tre',vtiger_accountRelProject1306.accountid AS 'kater',vtiger_messages.messagesid AS 'pese',P.spar_associato,P.differ,vtiger_crmentity.smownerid,P.giorniapertura,P.projectstatus,P.dayson,vtiger_messages.messagesid,vtiger_users.user_name,vtiger_crmentity.createdtime,vtiger_messages.msgdescription AS 'description',B.modifiedtime AS 'creation',P.projectid from vtiger_project P
                inner join vtiger_crmentity on vtiger_crmentity.crmid=P.projectid
                        
            left join vtiger_users as vtiger_usersProject on vtiger_usersProject.id = vtiger_crmentity.smownerid
                      
            left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid left join vtiger_crmentity as vtiger_crmentityRelProject1306 on vtiger_crmentityRelProject1306.crmid = P.linktobuyer and vtiger_crmentityRelProject1306.deleted=0 left join vtiger_account as vtiger_accountRelProject1306 on vtiger_accountRelProject1306.accountid = vtiger_crmentityRelProject1306.crmid  left join vtiger_messages as vtiger_messages on  vtiger_messages.project = P.projectid  join vtiger_crmentity B on
     B.crmid=messagesid WHERE B.modifiedtime like '%$year%\-%$month%\-$day%' and vtiger_messages.messagecategory like 'CLAIM OPEN%' and vtiger_crmentity.deleted=0   and (( P.progetto = '96221' or P.progetto = '95984' or P.progetto='162799' or P.progetto='977173' or P.progetto='2111822' or P.progetto='1578775' or P.progetto='2054186' )   and ( P.projectstatus not like '%hold%' and P.projectstatus not like '%arch%' and (P.projectstatus like '%Chiamata NSE%' or P.projectstatus like '%Riparazione%' or P.projectstatus like '%--none--%' ))  and ( P.substatusproj not like '%canc%' and P.substatusproj not like '%clos%' )  and P.differ>=0 ) ORDER BY P.projectid");
}
    

$rows=$adb->num_rows($query3);
//echo '<br>'.$rows;
//echo '<br>'.$adb->query_result($query3,2,0);
//echo $adb->current_user;
//echo $current_user->user_name;
//echo 'heythere';
$data=array();
for($i=0;$i<$adb->num_rows($query3);$i++){
   
  $prova= $adb->query_result($query3,$i,'dy');
  //echo $prova;
  
$query5=$adb->query("select vtiger_troubletickets.title,vtiger_troubletickets.status,vtiger_project.projectid from vtiger_troubletickets join vtiger_project on project=projectid
    join vtiger_crmentity on crmid=projectid where vtiger_troubletickets.status not like 'Closed' and vtiger_project.projectid=$prova order by ticketid DESC LIMIT 1 ");


    $data[$i]['id']=$i;
    $data[$i]['added4'] =$adb->query_result($query3,$i,0);
if($adb->query_result($query3,$i,'tre')==''){
$data[$i]['cmt']='---';

}
else
  { $data[$i]['cmt']=$adb->query_result($query3,$i,'tre');}
//echo $data[$i]['added8'];
   $data[$i]['added2']=$adb->query_result($query3,$i,2);
    $data[$i]['added9']=$adb->query_result($query3,$i,3);
  $data[$i]['added1']=$adb->query_result($query3,$i,4);
   $data[$i]['duration']=$adb->query_result($query3,$i,5);
   $data[$i]['added7']=$adb->query_result($query3,$i,'description');
   $data[$i]['added10']=$adb->query_result($query3,$i,'dy');
   $data[$i]['added11']=$adb->query_result($query3,$i,10);
    $data[$i]['added77']=$adb->query_result($query5,0,0);
     $data[$i]['added777']=$adb->query_result($query4,$i,'description');
     $data[$i]['added88']=$adb->query_result($query3,$i,12);
    $data[$i]['added888']=$adb->query_result($query3,$i,13);
    $data[$i]['added97']=$adb->query_result($query3,$i,14);
    $data[$i]['added977']=$adb->query_result($query3,$i,15);
$data[$i]['added9112']=$adb->query_result($query3,$i,16);
   

}

$select1.="</SELECT>";
$dataout=  json_encode($data);

$smarty = new vtigerCRM_Smarty;
$smarty->assign("selectContent", $select1);
$smarty->assign("data", $dataout);
$smarty->display("modules/evvtApps/index.tpl");
		
                
	}
}
?>

