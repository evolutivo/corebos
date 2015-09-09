<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//KOMENT
//KOMENT2
include_once("include/database/PearDatabase.php");

//include_once("modules/Messages/Messages.php");
//include_once("modules/evvtApps/KendoContent.php");
//include_once("modules/evvtApps/getOverdue_CAT_custom32382ab9d528b04b258572eae7c32617.php");
//include_once("modules/Reports/Reports.php");
//include("modules/Reports/ReportRun.php");
global $adb;

//$hey='heythere';
//echo $hey;
//$hi=$_REQUEST['records'];
//echo 'hey';

//$ha=json_decode($hi);
$hi2=$_REQUEST['result'];
$hi21=$_REQUEST['result21'];



//$id2 =$_REQUEST['adocmasterid2'];
//echo $id2;
//echo 'hey';
//echo 'hey';
//echo $hi2;
//echo $hi2;
//koment
echo $hi21;
var_dump(json_decode($hi2));
$json_output=json_decode($hi2);
echo $json_output;
foreach ( $json_output as $var1 )
{
     //  $adb->pquery("update vtiger_messages set msgdescription=? where messageid=? ",array($var1->added7,$var1->added11));
   if ($var1->added7 != '' && $var1->added7==$hi21 ){
       
  
   echo "{$var1->added7}\n";}
   
   
  include_once('modules/Messages/Messages.php');
global $current_user;
$focus = new Messages();

if ($var1->added7!='' && $var1->added7==$hi21){
    echo 'ok';
 echo $var1->added888;
     //  $current_user=$var1->added888;
      // echo $current_user->id;
   $focus->column_fields["name"] = $var1->added7;
   $focus->column_fields["assigned_user_id"] =$var1->added888;
   
  // if($mv->mestesso == '1'){  $focus->column_fields["assigned_user_id"] = $current_user->id;}
//   else 

   $focus->column_fields["msgdescription"] = $var1->added7;
   $focus->column_fields["project"] =$var1->added10;
  
  $focus->column_fields["messagecategory"] ="Messaggio sollecito";
   $focus->saveentity("Messages");


   

}}
?>
