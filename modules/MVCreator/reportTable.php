<?php

global $adb,$log,$mod_strings;
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
include_once("include/utils/CommonUtils.php");
require_once('include/database/PearDatabase.php');

$tab = $_REQUEST['nometab'];
$acc = $_REQUEST['accins'];
$mod = $_REQUEST['reportId'];
$nr = $_REQUEST['nr'];
$val = $_REQUEST['count'];

$colname = array();
$modname = array();
$fieldname = array();
$colname1 =array();
$modname1 = array();
$fieldname1 = array();
for($i=1;$i<$val;$i++)
{   
    if( isset($_REQUEST['checkf'.$i]) && $_REQUEST['checkf'.$i]==1){
        $fieldname1[$i] = $_REQUEST['field'.$i];
        $colname1[$i]=$_REQUEST['colname'.$i];
        $modname1[$i] = $_REQUEST['modul'.$i];
    }
        $fieldname[$i] = $_REQUEST['field'.$i];
        $colname[$i]= $_REQUEST['colname'.$i];
        $modname[$i] = $_REQUEST['modul'.$i];
}
$colname2 = implode(",",$colname);
$modname2 = implode(",",$modname);
$fieldname2 = implode(",",$fieldname);
$colname21 =implode(",",$colname1);
$modname21 = implode(",",$modname1);
$fieldname21 = implode(",",$fieldname1);
if($acc !=''){
$ai=$adb->query("select * from vtiger_accountinstallation 
                 join vtiger_crmentity on crmid=accountinstallationid 
                 join vtiger_account on accountid=linktoacsd 
                 where accountinstallationid=$acc");
$port=$adb->query_result($ai,0,"port");
$ip=$adb->query_result($ai,0,"hostname");
$pass=$adb->query_result($ai,0,"password");
$us=$adb->query_result($ai,0,"username");
$path=$adb->query_result($ai,0,"vtigerpath");
$dbname=$adb->query_result($ai,0,"dbname");
$acno=$adb->query_result($ai,0,"acin_no");
}
$reportid = $_REQUEST['reportId'];
$query="SELECT * from $acno$dbname.vtiger_report where reportid = $reportid";
$result = $adb->query($query);
$fl= $adb->query_result($result,0,'reportname');
$query1="SELECT * from $acno$dbname.vtiger_tab where name ='BiServer'";
$result1 = $adb->query($query1);
$nrel = $adb->num_rows($result1);
$col1=Array();
if($nr == 0){
   $fl1 = str_replace(" ","",$fl);
   $fl2 = str_replace("-","",$fl1);
   $id = str_replace(" ","",$reportid);
$my_file = $root_directory.'script_report_'.$id."".$fl2."".$tab.'.php';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$data = '<?php
global $adb;
$current_user->id=1 ;
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
include_once("include/utils/CommonUtils.php");
require_once(\'include/database/PearDatabase.php\');  
require_once("include/utils/utils.php"); 
require_once(\'vtlib/Vtiger/Module.php\');';
fwrite($handle, $data);
$data  = '
$focus1=new ReportRun('.$reportid.');
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow["tabid"])." ".$currencyfieldrow["fieldlabel"];
				$modprefixedlabel = str_replace(" ","_",$modprefixedlabel);

				if($currencyfieldrow["uitype"]!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
  
$s = $focus1->sGetSQLforReport('.$reportid.',$nu);
    $fields = explode(",",$s);
if(strpos($fields[0],\'_Id\')!== false)
{
    $s=str_replace($fields[0].",", " ", $s);
    $s = "select ".$s;
    
}
$fieldname = "'.$fieldname2.'";
$fields=$fieldname;
$nrfiel = explode(",",$fields);
for($i=0;$i<count($nrfiel);$i++)
    {
           $colona = explode("_",$nrfiel[$i],2);
           $nm = getTranslatedString($colona[1],  $colona[0]);
           $collabel[$i] = "$nm";
    }
           $collabel1=implode(",",$collabel); 

$adb->pquery("drop table IF EXISTS  mv_'.$id."".$fl2."".$tab.'");
$adb->pquery("create table mv_'.$id."".$fl2."".$tab.' ($s)");
$adb->pquery("ALTER TABLE mv_'.$id."".$fl2."".$tab.'
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");
$lq = "SELECT id from vtiger_scripts WHERE name = \'script_report_'.$id."".$fl2."".$tab.'.php\' ";
$id = $adb->pquery($lq);
$id1=$adb->query_result($id,0,"id");
$q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= \'$collabel1\' WHERE `id` =$id1 ";
$adb->pquery($q1);';
fwrite($handle, $data);
fclose($handle);
}
else
{
    $fl1 = str_replace(" ","",$fl);
    $fl2 = str_replace("-","",$fl1);
    $id = str_replace(" ","",$reportid);
    $my_file = $root_directory.'script_report_'.$id."".$fl2."".$tab.'.php';

$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$data = '<?php
global $adb;
$current_user->id=1 ;
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
include_once("include/utils/CommonUtils.php");
require_once(\'include/database/PearDatabase.php\');  
require_once("include/utils/utils.php"); 
require_once(\'vtlib/Vtiger/Module.php\'); 
$val = '.$val.';
$colname1 = "'.$colname21.'";
$modname1 = "'.$modname21.'";
$fieldname1 = "'.$fieldname21.'";
$colname = "'.$colname2.'";
$modname = "'.$modname2.'";
$fieldname = "'.$fieldname2.'";';
fwrite($handle, $data);
$data  = '
$focus1=new ReportRun('.$reportid.');
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow["tabid"])." ".$currencyfieldrow["fieldlabel"];
				$modprefixedlabel = str_replace(" ","_",$modprefixedlabel);

				if($currencyfieldrow["uitype"]!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
$s = $focus1->sGetSQLforReport('.$reportid.',$nu);
$result = $adb->query("$s");
$nr = $adb->num_rows($result);
$colonne=array();
$f=array();
$nrmod = explode(",",$modname1);
$fields=$fieldname;
$nrfiel = explode(",",$fields);
for($i=0;$i<count($nrfiel);$i++)
      {
           $colona = explode("_",$nrfiel[$i],2);
           $nm = getTranslatedString($colona[1],  $colona[0]);
           $collabel[$i] = "$nm";
      }
    $collabel1=implode(",",$collabel);
    for($i=0;$i<count($nrfiel);$i++)
          {
            $colona = preg_replace(\'/[^A-Za-z0-9]/\',\'\',$nrfiel[$i]);
            $colonne[$i]="$colona VARCHAR(250)";
          }
$col = implode(",",$colonne);
$adb->pquery("drop table mv_'.$id."".$fl2."".$tab.'");
$q1 = "create table mv_'.$id."".$fl2."".$tab.' ($col) ENGINE=InnoDB";
$adb->pquery("ALTER TABLE mv_'.$id."".$fl2."".$tab.'
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");                                                
$adb->pquery($q1);
$fieldname = explode(",",$fieldname1);
  for($i=0;$i<$nr;$i++)
  {
       $k =0;
       for($j=0;$j<count($nrfiel);$j++)
       {
          if($nrfiel[$j] == $fieldname[$k] && $k<count($nrmod) )
           {
              $l = "SELECT tablename,fieldname,entityidfield from vtiger_entityname WHERE tabid = $nrmod[$k] "; 
              $name = $adb->pquery($l);
              $name1=$adb->query_result($name,0,"fieldname");
              $tab1=$adb->query_result($name,0,"tablename");
              $ent1=$adb->query_result($name,0,"entityidfield");
              $valuefield=$adb->query_result($result,$i,$j);
              if($valuefield !=\'\'){
               $q ="SELECT $name1 from $tab1 WHERE $ent1 = \'$valuefield\' ";
               $uiname = $adb->pquery($q);
               $uiname1=str_replace("\'","\\\'",$adb->query_result($uiname,0,0));
               $colona = preg_replace(\'/[^A-Za-z0-9]/\',\'\',$nrfiel[$j]);
               $f[$j] = "$colona = \'$uiname1\'";}else{$colona = preg_replace(\'/[^A-Za-z0-9]/\',\'\',$nrfiel[$j]);$f[$j] = "$colona = \'\'";}
               $k++;                                 
          }
          else
         {
          $vl =$adb->query_result($result,$i,$j);
          $colona = preg_replace(\'/[^A-Za-z0-9]/\',\'\',$nrfiel[$j]);
          $f[$j] = "$colona = \'$vl\'";
          }
       }
     $f1=implode(",",$f);
     $q =  "insert into mv_'.$id."".$fl2."".$tab.' set $f1";
     echo $q;
     $adb->pquery($q); 
     $lq = "SELECT id from vtiger_scripts WHERE name = \'script_report_'.$id."".$fl2."".$tab.'.php\' ";
     $id = $adb->pquery($lq);
     $id1=$adb->query_result($id,0,"id");
     $q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= \'$collabel1\' WHERE `id` =$id1 ";
     $adb->pquery($q1);  
  }';
    fwrite($handle, $data);
    fclose($handle);  
}

if($ip != '193.182.16.244' ){
if (!function_exists("ssh2_connect")) die("function ssh2_connect doesn't exist");
if(!($con = ssh2_connect($ip, $port))){
    $msgc=$mod_strings["failc"];
} else {
    // try to authenticate with username root, password secretpassword
    if(!ssh2_auth_password($con, $us, $pass)) {
        $msgc=$mod_strings["faila"];
    } else {

        $msgc=$mod_strings["succ"];
        $fl1 = str_replace(" ","",$fl);
        $fl2 = str_replace("-","",$fl1);
        $id = str_replace(" ","",$reportid);
        $loc =$root_directory."script_report_".$id."".$fl2."".$tab.".php";
        if($nrel==0)
        $serv = $path."/modules/TbCompanion/Reports/script_report_".$id."".$fl2."".$tab.".php";
        else
        $serv = $path."/modules/BiServer/Reports/script_report_".$id."".$fl2."".$tab.".php";
 echo $serv;       
ssh2_scp_send($con,$loc ,$serv , 0777);
$stream = ssh2_exec($con, "cd ".$path." ; script_report_'.$tab.'.php");
//$stream = ssh2_exec($con, "cd ".$path." ; vim result.txt ");
stream_set_blocking($stream, true);
$lang1=stream_get_contents($stream);
}
}
}
else {
        $msgc=$mod_strings["succ"];
        $fl1 = str_replace(" ","",$fl);
        $fl2 = str_replace("-","",$fl1);
        $id = str_replace(" ","",$reportid);
        $loc =$root_directory."script_report_".$id."".$fl2."".$tab.".php";
        if($nrel==0)
        $serv = $path."/modules/TbCompanion/Reports/script_report_".$id."".$fl2."".$tab.".php";
        else
        $serv = $path."/modules/BiServer/Reports/script_report_".$id."".$fl2."".$tab.".php";
        echo $serv;
        shell_exec("cp $loc $serv");
}  
?>
