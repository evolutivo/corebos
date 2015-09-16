<?php
global $adb;
$current_user->id=1 ;
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
include_once("include/utils/CommonUtils.php");
require_once('include/database/PearDatabase.php');  
require_once("include/utils/utils.php"); 
require_once('vtlib/Vtiger/Module.php');
$focus1=new ReportRun(1);
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
  
$s = $focus1->sGetSQLforReport(1,$nu);
    $fields = explode(",",$s);
if(strpos($fields[0],'_Id')!== false)
{
    $s=str_replace($fields[0].",", " ", $s);
    $s = "select ".$s;
    
}
$fieldname = "Contacts_First_Name,Contacts_Last_Name,Contacts_Lead_Source,Contacts_Account_Name,Accounts_industry";
$fields=$fieldname;
$nrfiel = explode(",",$fields);
for($i=0;$i<count($nrfiel);$i++)
    {
           $colona = explode("_",$nrfiel[$i],2);
           $nm = getTranslatedString($colona[1],  $colona[0]);
           $collabel[$i] = "$nm";
    }
           $collabel1=implode(",",$collabel); 

$adb->pquery("drop table IF EXISTS  mv_1ContactsbyAccountsaaaaaaaaaa");
$adb->pquery("create table mv_1ContactsbyAccountsaaaaaaaaaa ($s)");
$adb->pquery("ALTER TABLE mv_1ContactsbyAccountsaaaaaaaaaa
              ADD COLUMN id INT NOT NULL AUTO_INCREMENT FIRST,
              ADD PRIMARY KEY (id)");
$lq = "SELECT id from vtiger_scripts WHERE name = 'script_report_1ContactsbyAccountsaaaaaaaaaa.php' ";
$id = $adb->pquery($lq);
$id1=$adb->query_result($id,0,"id");
$q1 = "UPDATE `vtiger_scripts` SET `fieldlabel`= '$collabel1' WHERE `id` =$id1 ";
$adb->pquery($q1);