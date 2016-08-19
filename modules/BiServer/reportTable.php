<?php

global $adb,$log,$mod_strings;
include_once("modules/Reports/Reports.php");
include_once("modules/Reports/ReportRun.php");
include_once("include/utils/CommonUtils.php");
require_once('include/database/PearDatabase.php');
global $root_directory;

$mvtype = $_REQUEST['mvtype'];
// remove spaces and special characters from tab name
$tab = trim($_REQUEST['nometab']);
$nr = $_REQUEST['nr'];
$val = $_REQUEST['count'];
if($mvtype == "report"){
$mod = $_REQUEST['reportId'];
$Deleted = trim($_REQUEST['checkdeleted']);
if($Deleted == "on") $BiServerDeleted = 1;
else $BiServerDeleted = 0;
$colname = array();
$modname = array();
$fieldname = array();
$colname1 =array();
$modname1 = array();
$selectedReportColumns = array();
$selectedReportColumnsLabel = array();
$replaceColumnsContainingDots = array();
$replacedColumnsContainingDots = array();
$selectedReportColumnsLabel[0] = "id";
for($i=0;$i<$val;$i++)
 {   
    //get selected fields
    $j= $i+1;
    if(isset($_REQUEST['checkf'. $j])){
        $selectedReportColumns[$i] = preg_replace('/[^A-Za-z0-9_=\s,<>%\'\'()!.:\\-àèòùì]/','',html_entity_decode($_REQUEST['field'. $j]));
        if(strpos($selectedReportColumns[$i],".") || strpos($selectedReportColumns[$i],"-"))
        {
          $replaceColumnsContainingDots[] = $selectedReportColumns[$i];
          $selectedReportColumns[$i] = str_replace('.', '', $_REQUEST['field'. $j]);
          $selectedReportColumns[$i] = str_replace('-', '', $selectedReportColumns[$i]);
          $replacedColumnsContainingDots[] = $selectedReportColumns[$i] ;
        }
        $colname1[$i]=$_REQUEST['colname'.$i];
        $modname1[$i] = $_REQUEST['modul'.$i];
        $selectedReportColumnsLabel[$j] = $_REQUEST['modulfieldlabel'.$j];
      }
}
$replace = implode(",",$replaceColumnsContainingDots);
$replaced = implode(",",$replacedColumnsContainingDots);
$fieldLabels = implode(",", $selectedReportColumnsLabel);
if($BiServerDeleted ==1){
    //add column deleted
$selectedReportColumns[]="deleted";    
$mvtableColumns = implode(",", $selectedReportColumns);  
}
else $mvtableColumns = implode(",", $selectedReportColumns);
$colname2 = implode(",",$colname);
$modname2 = implode(",",$modname);
$fieldname2 = implode(",",$fieldname);
$colname21 =implode(",",$colname1);
$modname21 = implode(",",$modname1);
$fieldname21 = implode(",",$selectedReportColumns);

$reportid = $_REQUEST['reportId'];
$query="SELECT * from vtiger_report where reportid = $reportid";
$result = $adb->query($query);
$fl= $adb->query_result($result,0,'reportname');

if($nr == 0){
   $fl1 = str_replace(" ","",$fl);
   $fl2 = str_replace("-","",$fl1);
   $id = str_replace(" ","",$reportid);
   
$my_file = $root_directory.'modules/BiServer/Reports/script_report_'.$id."".$fl2."".$tab.'.php';
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
$data='
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

$s = $focus1->sGetSQLforReport('.$reportid.',$nu,"","",'.$BiServerDeleted.');
$fields = explode(",",$s);
$replaceColumnsContainingDots  = "'.$replaceColumnsContainingDots.'"; 
$replacedColumnsContainingDots  = "'.$replacedColumnsContainingDots.'";
$reportClumns = preg_replace("/[^A-Za-z0-9_=\s,<>%\'\'()!.:\\\-]/","",$focus1->getQueryColumnsList('.$reportid.'));
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
$id1 = $adb->query_result($id,0,"id");
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
    $my_file = $root_directory.'modules/BiServer/Reports/script_report_'.$id."".$fl2."".$tab.'.php';

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
$selectedReportColumns = "'.$fieldname21.'";
$replaceColumnsContainingDots  = explode(",","'.$replace.'"); 
$replacedColumnsContainingDots  = explode(",","'.$replaced.'");
$colname = "'.$colname2.'";
$modname = "'.$modname2.'";
$fieldname = "'.$fieldname2.'";
$fieldLabels = "'.$fieldLabels.'";';
fwrite($handle, $data);
$data = '
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
//Replace special characters on columns of report query   
$reportQuery = $focus1->sGetSQLforReport('.$reportid.',$nu,"","",'.$BiServerDeleted.');
$SQLforReport = preg_replace("/[^A-Za-z0-9_=\s,<>%\"\'\\\-()!.:àèòùì]/","",html_entity_decode($reportQuery));
$SQLforReport = str_replace($replaceColumnsContainingDots, $replacedColumnsContainingDots, $SQLforReport);
//$result = $adb->query("$s");
$reportClumns = preg_replace("/[^A-Za-z0-9_=\s,<>%\"\'\\\-()!.:àèòùì]/","",$focus1->getQueryColumnsList('.$reportid.'));
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
//CREATING MV TABLE
$adb->pquery("drop table IF EXISTS  mv_'.$id."".$fl2."".$tab.'");
$q1 = $adb->query("create table mv_'.$id."".$fl2."".$tab.' AS Select  '.$mvtableColumns.' From ($SQLforReport) AS reportTable");
//Adding primary key to the new created table
$adb->pquery("ALTER TABLE mv_'.$id."".$fl2."".$tab.'
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");                                                
$fieldname = explode(",",$selectedReportColumns);
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
              $valuefield = $adb->query_result($result,$i,$j);
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
     echo $f1;
     $q =  "insert into mv_'.$id."".$fl2."".$tab.' set $f1";
 //    $adb->pquery($q); 
     $lq = "SELECT id from vtiger_scripts WHERE name = \'script_report_'.$id."".$fl2."".$tab.'.php\' ";
     $id = $adb->pquery($lq);
     $id1=$adb->query_result($id,0,"id");
     $q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= \'$fieldLabels\' WHERE `id` =$id1 ";
     $adb->pquery($q1);  
  }
     $setColumnLabels= "SELECT id from vtiger_scripts WHERE name = \'script_report_'.$id."".$fl2."".$tab.'.php\' ";
     $id = $adb->query($setColumnLabels);
     $scriptId = $adb->query_result($id,0,"id");
     $q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= \'$fieldLabels\' WHERE `id` =$scriptId ";
     $adb->query($q1);
  ';
    fwrite($handle, $data);
    fclose($handle);  
}
}
else{
//mvmap
$mapid = $_REQUEST['mapsql'];
//Get map fields and new labels
$selectedMapColumnsLabel = array();
$selectedMapColumnsLabel[0] = "id";
$selectedMapColumns = array();
for($i=0;$i<$val;$i++)
 {   
    //get selected fields
    $j= $i+1;
    if( isset($_REQUEST['checkf'. $j]) && $_REQUEST['checkf'. $j]==1){
        $selectedMapColumns[$i] = preg_replace('/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/','',$_REQUEST['mapfield'. $j]);
        $selectedBiMapColumns[$i] = preg_replace('/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/','',$_REQUEST['colaliasname'. $j]);
        $colname1[$i]=$_REQUEST['colname'.$i];
        $modname1[$i] = $_REQUEST['modul'.$i];
        $selectedMapColumnsLabel[$j] = $_REQUEST['modulfieldlabel'.$j];
      }
  }
$replace = implode(",",$replaceColumnsContainingDots);
$replaced = implode(",",$replacedColumnsContainingDots);
$fieldLabels = implode(",", $selectedMapColumnsLabel);
$mvtableSelectColumns = implode(",", $selectedMapColumns);
$mvtableColumns = implode(",", $selectedBiMapColumns);
$my_file = $root_directory.'modules/BiServer/Map/script_report_'."map".$tab.'.php';
$handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
$data = '<?php
global $adb;
$current_user->id=1 ;
include_once("include/utils/CommonUtils.php");
require_once(\'include/database/PearDatabase.php\');  
require_once("include/utils/utils.php"); 
require_once(\'vtlib/Vtiger/Module.php\'); 

//get map query 
$SQLforMap = $adb->pquery("Select content from vtiger_cbmap where cbmapid = ?",array('.$mapid.'));
$mapSql = str_replace(\'"\',\'\',html_entity_decode($adb->query_result($SQLforMap,0,"content"),ENT_QUOTES));
$fromQueryPart = explode("FROM",$mapSql);
$fieldLabels = "'.$fieldLabels.'";
//CREATING MV TABLE
$adb->pquery("drop table IF EXISTS  mv_map'.$tab.'"); 
$q1 = $adb->query("create table mv_map'.$tab.' AS Select '.$mvtableColumns.' From (SELECT '.$mvtableSelectColumns.' FROM $fromQueryPart[1]) AS mapTable");
//Adding primary key to the new created table
$adb->pquery("ALTER TABLE mv_map'.$tab.'
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");  
     $lq = "SELECT id from vtiger_scripts WHERE name = \'script_report_'."map".$tab.'.php\' ";
     $id = $adb->pquery($lq);
     $id1=$adb->query_result($id,0,"id");
     $q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= \'$fieldLabels\' WHERE `id` =$id1 ";
     $adb->pquery($q1);                
';
    fwrite($handle, $data);
    fclose($handle); 
}
echo $my_file;
?>
