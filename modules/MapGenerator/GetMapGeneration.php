<?php

/**
 * File to load maps
 */
global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $root_directory, $current_user;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
require_once ('include/utils/utils.php');
require_once ('Smarty_setup.php');
require_once ('include/database/PearDatabase.php');
// require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
include('modfields.php');
include_once 'All_functions.php';

$GetALLMaps= explode("#", $_POST['GetALLMaps']);

if ($GetALLMaps[0]=="Mapping") {
	
	$MypType=$GetALLMaps[0];
	$MapID=$GetALLMaps[1];
	$QueryHistory=$GetALLMaps[2];

	// print_r($GetALLMaps);
	// //echo get_first_second_Modules_From_XML(get_form_Map($MapID)); 
	// echo Get_First_Moduls("Potentials");
	// echo GetModulRelOneTomulti("Potentials","Accounts");
	try {

		if (!empty($QueryHistory)) {
			
			$FirstModuleSelected=Get_First_Moduls(get_The_history($QueryHistory,"firstmodule"));
			$SecondModulerelation=GetModulRelOneTomulti(get_The_history($QueryHistory,"firstmodule"),get_The_history($QueryHistory,"secondmodule"));
			$FirstModuleFields=getModFields(get_The_history($QueryHistory,"firstmodule"));
			$SecondModuleFields=getModFields(get_The_history($QueryHistory,"secondmodule"));
			$MapName=get_form_Map($MapID,"mapname");
			$HistoryMap=$QueryHistory.",".$MapID;

			// value for Save As 
			$data="MapGenerator,SaveTypeMaps";
			$dataid="ListData,MapName";
			$savehistory="true";


			$smarty = new vtigerCRM_Smarty();
			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			
			$smarty->assign("MapName", $MapName);

			$smarty->assign("HistoryMap",$HistoryMap);

			$smarty->assign("FirstModuleSelected",$FirstModuleSelected);
			$smarty->assign("SecondModulerelation",$SecondModulerelation);

			//put the smarty modal
			$smarty->assign("Modali",put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings));

			$smarty->assign("FirstModuleFields",$FirstModuleFields);
			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output = $smarty->fetch('modules/MapGenerator/MappingView.tpl');
			echo $output;

		}elseif (!empty($MapID)) {

			$xml=new SimpleXMLElement(get_form_Map($MapID)); 

			$FirstModuleSelected=Get_First_Moduls( $xml->targetmodule[0]->targetname);
			$SecondModulerelation=GetModulRelOneTomulti($xml->targetmodule[0]->targetname ,$xml->originmodule[0]->originname);
			$FirstModuleFields=getModFields($xml->targetmodule[0]->targetname);
			$SecondModuleFields=getModFields(,$xml->originmodule[0]->originname);
			$MapName=get_form_Map($MapID,"mapname");
			$HistoryMap=get_form_Map($MapID,"mvqueryid").",".$MapID;

			// value for Save As 
			$data="MapGenerator,SaveTypeMaps";
			$dataid="ListData,MapName";
			$savehistory="true";


			$smarty = new vtigerCRM_Smarty();
			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			
			$smarty->assign("MapName", $MapName);

			$smarty->assign("HistoryMap",$HistoryMap);

			$smarty->assign("FirstModuleSelected",$FirstModuleSelected);
			$smarty->assign("SecondModulerelation",$SecondModulerelation);

			//put the smarty modal
			$smarty->assign("Modali",put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings));

			$smarty->assign("FirstModuleFields",$FirstModuleFields);
			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output = $smarty->fetch('modules/MapGenerator/MappingView.tpl');
			echo $output;
		}else
		{
			throw new Exception("Error Missing the MapId and the Id of history so can load map ", 1);
			
		}

		
	} catch (Exception $e) {
		$log->debug("<<Map Generator>>Error!! Something was wrong check the Exception "+$ex);
		echo "Missing the Id of the Map and also the Id of query history ";
	}






}else
{
	echo "Missing ";;
}






/**
 * All Function Needet 
 */


/**
 * [put_the_modal_SaveAs description] this function is to insert the modal for save as in every Load map
 * @param  [type] $data         String Are the name of the modul and the file when you save the Map type Mapping 
 * @param  [type] $dataid       String the id (Html elements ) to take the values 
 * @param  [type] $savehistory  String Flag if you want the history or not 
 * @param  [type] $mod_strings  For Translate 
 * @param  [type] $app_strings  
 * @return [type]                return string which contains the modal 
 */
function put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings)
{	
	$smarty = new vtigerCRM_Smarty();
    $smarty->assign("MOD", $mod_strings);
    $smarty->assign("APP", $app_strings);
	$smarty->assign("Datas", $data);
    $smarty->assign("dataid", $dataid);
    $smarty->assign("savehistory", $savehistory);   
    $output = $smarty->fetch('modules/MapGenerator/Modal.tpl');
    return $output;

}



/**
 * This function is to get all moduls and if also to check if someone from those is equels vith the values 
 * @param string $value  is a param type string to check the the list of moduls
 */
function Get_First_Moduls($value="")
{
	global $adb, $root_directory, $log;

	$query = "SELECT * from vtiger_tab where isentitytype=1 and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier' and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and presence=0";

	$result = $adb->query($query);
	$num_rows = $adb->num_rows($result);
	if ($num_rows != 0) {
	//echo "if num rows eshte e madhe se 0 ";
	for ($i = 1; $i <= $num_rows; $i++) {
		//echo "brenda ciklit for ".$i;
		$modul1 = $adb->query_result($result, $i - 1, 'name');
		if (strlen($value) != 0 && $value == $modul1) {
			$a .= '<option selected value="' . $modul1 . '">' . str_replace("'", "", getTranslatedString($modul1)) . '</option>';
			// echo "nese plotesohet kushti firstmodulexml";
		} else {
			$a .= '<option  value="' . $modul1 . '">' . str_replace("'", "", getTranslatedString($modul1)) . '</option>';
			///echo "nese nuk  plotesohet kushti firstmodulexml";
		}
	}
	}
	return $a;
}

/**
 * function to select the query fom mapgeneration_mvqueryhistory
 * @param  string $Id_Encrypt  the id to  filter by this id 
 * @return the query of mapgenertion_mvhistory
 */
function get_The_history($Id_Encrypt="",$field_take="query")
{
	global $adb,$root_directory, $log;

	if(empty($Id_Encrypt))
	{
		throw new Exception("<<Map Generator>> INFO !! The ID for history is Emtpy", 1);
	}

	try {

		$q="SELECT * FROM mapgeneration_queryhistory Where id='$Id_Encrypt' AND active=1 ";

		$result = $adb->query($q);
		$num_rows = $adb->num_rows($result);
		if (empty($field_take)) {
			throw new Exception("<<Map Generator>>Error Missing the Filed you wnat to take", 1);
		}

		if ($num_rows>0) {
			$Resulti = $adb->query_result($result,0, $field_take);

			if (!empty($Resulti)) {
				return $Resulti;
			} else {
				throw new Exception("<<Map Generator>>Error Something was wrong RESULT IS EMPTY", 1);
			}
		} else {
			throw new Exception("<<Map Generator>>Not exist daata with this ID="+$Id_Encrypt,1);
		}
	} catch (Exception $ex) {
		 $log->debug("<<Map Generator>>Error!! Something was wrong check the Exception "+$ex);
		 return "";
	}
}

/**
 * get the value from cbmap 
 * @param  string  $MapID      The id of map 
 * @param  string $field_take  what field you want to take from cbmap 
 * @return string  return the values of the fields from cbmap
 */
function get_form_Map($MapID,$field_take='content')
{
	global $adb,$root_directory, $log;

	if(empty($MapID))
	{
		throw new Exception("<<Map Generator>> INFO !! The ID for Map is Emtpy", 1);
	}

	try {

		$q="SELECT cb.*,cr.* FROM vtiger_cbmap cb JOIN vtiger_crmentity cr ON cb.cbmapid=cr.crmid WHERE cr.deleted=0 and cb.cbmapid='$MapID'";

		$result = $adb->query($q);
		$num_rows = $adb->num_rows($result);
		if (empty($field_take)) {
			throw new Exception("<<Map Generator>>Error Missing the Filed you wnat to take", 1);
		}

		if ($num_rows>0) {
			$Resulti = $adb->query_result($result,0, $field_take);

			if (!empty($Resulti)) {
				return $Resulti;
			} else {
				throw new Exception("<<Map Generator>>Error Something was wrong RESULT IS EMPTY", 1);
			}
		} else {
			throw new Exception("<<Map Generator>>Not exist Map with this ID="+$MapID,1);
		}
	} catch (Exception $ex) {
		 $log->debug("<<Map Generator>>Error!! Something was wrong check the Exception "+$ex);
		 return "";
	}
}


// function get_first_second_Modules_From_XML($xmlData)
// {
// 	if (empty($xmlData)) {
// 		throw new Exception("Missing the XMl Data to convert ", 1);
// 	}

// 	try {
// 		$xml = new SimpleXMLElement($xmlData);
// 		if (!empty($xml->originmodule[0]->originname)) {
// 			return $xml->originmodule[0]->originname;
// 		}

		
// 	} catch (Exception $e) {
		
// 	}


// }
