<?php

/**
 * File to load maps
 */

require_once ('include/utils/utils.php');
require_once ('Smarty_setup.php');
require_once ('include/database/PearDatabase.php');
// require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
include('modfields.php');
include_once 'All_functions.php';
include_once 'Staticc.php';




$GetALLMaps= explode("#", $_POST['GetALLMaps']);
$MypType=$GetALLMaps[0];
$MapID=$GetALLMaps[1];
$QueryHistory=$GetALLMaps[2];

if ($MypType=="Mapping") {
	
	try
	{
		if (!empty($QueryHistory) || !empty($MapID)) {

			Mapping_View($QueryHistory,$MapID);

		} else {
			throw new Exception(" Missing the MapID also the Id of mapgenartor_mvqueryhistory", 1);
		}
		

	}catch(Exception $ex)
	{
		$log->debug(TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex);
		echo TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex;
	}

}elseif ($MypType=="MasterDetail") {

	try
	{
		if (!empty($QueryHistory) || !empty($MapID)) {
			Master_detail($QueryHistory,$MapID);
		} else {
			throw new Exception(" Missing the MapID also the Id of mapgenartor_mvqueryhistory", 1);
		}
		

	}catch(Exception $ex)
	{
		$log->debug(TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex);
		echo TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex;
	}
	
}if ($MypType==="ListColumns") {
	
	try
	{
		if (!empty($QueryHistory) || !empty($MapID)) {
			
			// echo GetModuleMultiToOneForLOadListColumns("Documents","DocSettings");
			$MyArray=array();
			$xml=new SimpleXMLElement(get_The_history($QueryHistory,"query")); 

			$SmoduleID=(string) $xml->relatedlists[0]->relatedlist->linkfield;
			$FmoduleID=(string)  $xml->popup->linkfield;

			foreach($xml->relatedlists->relatedlist as $field)
			{
				$araymy=[
					 
					 'DefaultText'=>(string) explode("#", Get_First_Moduls_TextVal($field->columns->field->name))[1],
					 'DefaultValue' =>(string)$field->columns->field->label,
					 'DefaultValueoptionGroup'=>"",
					'FirstModule' =>(string) $Filed->module,
					 'FirstModuleoptionGroup' =>"undefined",
					'FirstfieldID'=>(string)$xml->popup->linkfield,
					'FirstfieldIDoptionGroup'=>"",
					'JsonType'=>"Related",
					'SecondField'=>(string)Get_Modul_fields_check_from_load($xml->originname[0],$field->columns->field->name),
					'SecondFieldoptionGroup'=>(string)$xml->originname[0],
					'SecondfieldID'=>(string)$field->linkfield,
					'SecondfieldIDoptionGroup'=>"",
					'secmodule'=>explode(",", GetModuleMultiToOneForLOadListColumns(get_The_history($QueryHistory,"firstmodule"),$xml->originname)),
					'secmoduleoptionGroup'=>"undefined",
				];

				array_push($MyArray,$araymy);
			}
			print_r($MyArray);
			exit();
			// List_Clomns($QueryHistory,$MapID);

		} else {
			throw new Exception(" Missing the MapID also the Id of mapgenartor_mvqueryhistory", 1);
		}
		

	}catch(Exception $ex)
	{
		$log->debug(TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex);
		echo TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex;
	}
	
}

else
{
	echo "Not Exist This Type of Map? \n Please check the type of mapping and try again.... ";;
}






/**
 * All Function Needet 
 */




/**
 * List_Coluns function is to load the map type list coluns
 * @param [type] $QueryHistory  The id of History of Map 
 * @param [type] $MapID        The id of Map 
 */
function List_Clomns($QueryHistory,$MapID)
{
	global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $root_directory, $current_user,$log;
	$theme_path = "themes/" . $theme . "/";
	$image_path = $theme_path . "images/";

	try {

		if (!empty($QueryHistory)) 
		{
			//TODO: if have query history
			
			$FirstModuleSelected=Get_First_Moduls(get_The_history($QueryHistory,"firstmodule"));

			$SecondModulerelation=GetModulRelOneTomulti(get_The_history($QueryHistory,"firstmodule"),get_The_history($QueryHistory,"secondmodule"));

			$FirstModuleFields=getModFields(get_The_history($QueryHistory,"firstmodule"));

			$SecondModuleFields=getModFields(get_The_history($QueryHistory,"secondmodule"));

			$MapName=get_form_Map($MapID,"mapname");

			$HistoryMap=$QueryHistory.",".$MapID;

			// $MyArray=array();
			// $xml=new SimpleXMLElement(get_The_history($QueryHistory,"query")); 

			// $SmoduleID=(string) $xml->relatedlists[0]->relatedlist->linkfield;
			// $FmoduleID=(string)  $xml->popup->linkfield;

			// foreach($xml->relatedlists->relatedlist as $field)
			// {
			// 	$araymy=[
					 
			// 		 'DefaultText'=>(string) explode("#", Get_First_Moduls_TextVal($field->columns->field->name))[1],
			// 		 'DefaultValue' =>(string)$field->columns->field->label,
			// 		 'DefaultValueoptionGroup'=>"",
			// 		'FirstModule' =>(string) $Filed->module,
			// 		 'FirstModuleoptionGroup' =>"undefined",
			// 		'FirstfieldID'=>$xml->popup->linkfield,
			// 		'FirstfieldIDoptionGroup'=>"",
			// 		'JsonType'=>"Related",
			// 		'SecondField'=>(string)Get_Modul_fields_check_from_load($xml->originname[0],$field->columns->field->name),
			// 		'SecondFieldoptionGroup'=>$xml->originname[0],
			// 		'SecondfieldID'=>(string)$field->linkfield,
			// 		'SecondfieldIDoptionGroup'=>"",
			// 		'secmodule'=>explode(",", GetModuleMultiToOneForLOadListColumns(get_The_history($QueryHistory,"firstmodule"),$xml->originname)),
			// 		'secmoduleoptionGroup'=>"undefined",
			// 	];

			// 	array_push($MyArray,$araymy);
			// }


			
		} elseif (!empty($MapID)) {
			# code...
		}else{
			throw new Exception("Missing the MApID also The QueryHIstory", 1);
		}
		
		
	} catch (Exception $ex) {
		$log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
		echo TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex;
	}
}

/**
 * function to continue with master detail map 
 * @param [type] $QueryHistory the ID of Query History
 * @param [type] $MapID        The Id of MAp
 */
function Master_detail($QueryHistory,$MapID)
{
	global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $root_directory, $current_user,$log;
	$theme_path = "themes/" . $theme . "/";
	$image_path = $theme_path . "images/";
	try {
		
		if (!empty($QueryHistory)) {
			//TODO: if have query history
			
			$FirstModuleSelected=Get_First_Moduls(get_The_history($QueryHistory,"firstmodule"));

			$SecondModulerelation=GetModulRelOneTomulti(get_The_history($QueryHistory,"firstmodule"),get_The_history($QueryHistory,"secondmodule"));

			$FirstModuleFields=getModFields(get_The_history($QueryHistory,"firstmodule"));

			$SecondModuleFields=getModFields(get_The_history($QueryHistory,"secondmodule"));

			$MapName=get_form_Map($MapID,"mapname");

			$HistoryMap=$QueryHistory.",".$MapID;

			$MyArray=array();
			$xml=new SimpleXMLElement(get_The_history($QueryHistory,"query")); 

			$FmoduleID=(string) $xml->linkfields->targetfield;
			$SmoduleID=(string) $xml->linkfields->originfield;

			$nrindex=0;
			foreach($xml->detailview->fields->field as $field)
			{
				$araymy=[
					 
					 'DefaultText'=>"Edmondi Default",
					 'FirstModule' =>(string)explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]))[0],
					 'FirstModuleoptionGroup'=>"udentifined",
					'Firstfield' =>(string) explode(",",Get_Modul_fields_check_from_load($xml->targetmodule[0],$field->fieldname))[0],
					 'FirstfieldID' =>(string) $xml->linkfields[0]->targetfield,
					'FirstfieldIDoptionGroup'=>"",
					'Firstfield_Text'=>(string) explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]))[1],
					'FirstfieldoptionGroup'=>(string)$xml->targetmodule,
					'JsonType'=>"Default",
					'SecondfieldID'=>(string)$xml->linkfields->originfield,

					'editablechk'=>(string) $field->editable,
					'editablechkoptionGroup'=>"",

					'hiddenchk'=>(string) $field->editable,
					'hiddenchkoptionGroup'=>"",

					'mandatorychk'=>(string)$field->mandatory,

					'secmodule' =>(string)explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule,$xml->originmodule))[0],
					'secmoduleoptionGroup'=>"udentifined",

					'sortt6ablechk'=>((string)$xml->sortfield===(string)$field->fieldname)?1:0,
					'sortt6ablechkoptionGroup'=>"",
					

				];

				array_push($MyArray,$araymy);
			}


			// value for Save As 
			$data="MapGenerator,SaveMasterDetail";
			$dataid="ListData,MapName";
			$savehistory="true";
			
			$smarty = new vtigerCRM_Smarty();
			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			
			$smarty->assign("MapName", $MapName);

			$smarty->assign("HistoryMap",$HistoryMap);

			$smarty->assign("FmoduleID",$FmoduleID);
			$smarty->assign("SmoduleID",$SmoduleID);


			$smarty->assign("FirstModuleSelected",$FirstModuleSelected);
			$smarty->assign("SecondModulerelation",$SecondModulerelation);

			//put the smarty modal
			$smarty->assign("Modali",put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings));

			$smarty->assign("FirstModuleFields",$FirstModuleFields);

			$smarty->assign("PopupJS",$MyArray);

			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output = $smarty->fetch('modules/MapGenerator/MasterDetail.tpl');
			echo $output;



		}elseif (!empty($MapID)) {
			//TODO: if exist MAp id 
			
			$xml=new SimpleXMLElement(get_form_Map($MapID)); 
			$FirstModuleSelected=Get_First_Moduls((string) $xml->targetmodule[0]);
			 $SecondModulerelation=GetModulRelOneTomulti((string)$xml->targetmodule[0] ,(string)$xml->targetmodule[0]);
			$FirstModuleFields=getModFields((string)$xml->targetmodule[0]);
			$SecondModuleFields=getModFields((string)$xml->originmodule[0]);
			$MapName=get_form_Map($MapID,"mapname");
			$HistoryMap=get_form_Map($MapID,"mvqueryid").",".$MapID;

			// value for Save As 
			$data="MapGenerator,SaveMasterDetail";
			$dataid="ListData,MapName";
			$savehistory="true";
			
			$MyArray=array();
			// $xml=new SimpleXMLElement(get_The_history($QueryHistory,"query")); 

			$FmoduleID=(string) $xml->linkfields->targetfield;
			$SmoduleID=(string) $xml->linkfields->originfield;

			$nrindex=0;
			foreach($xml->detailview->fields->field as $field)
			{
				$araymy=[
					 
					 'DefaultText'=>"Edmondi Default",
					 'FirstModule' =>(string)explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]))[0],
					 'FirstModuleoptionGroup'=>"udentifined",
					'Firstfield' =>(string) explode(",",Get_Modul_fields_check_from_load($xml->targetmodule[0],$field->fieldname))[0],
					 'FirstfieldID' =>(string) $xml->linkfields[0]->targetfield,
					'FirstfieldIDoptionGroup'=>"",
					'Firstfield_Text'=>(string) explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]))[1],
					'FirstfieldoptionGroup'=>(string)$xml->targetmodule,
					'JsonType'=>"Default",
					'SecondfieldID'=>(string)$xml->linkfields->originfield,

					'editablechk'=>(string) $field->editable,
					'editablechkoptionGroup'=>"",

					'hiddenchk'=>(string) $field->editable,
					'hiddenchkoptionGroup'=>"",

					'mandatorychk'=>(string)$field->mandatory,

					'secmodule' =>(string)explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule,$xml->originmodule))[0],
					'secmoduleoptionGroup'=>"udentifined",

					'sortt6ablechk'=>((string)$xml->sortfield===(string)$field->fieldname)?1:0,
					'sortt6ablechkoptionGroup'=>"",
					

				];

				array_push($MyArray,$araymy);
			}

			$smarty = new vtigerCRM_Smarty();
			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			
			$smarty->assign("MapName", $MapName);

			$smarty->assign("HistoryMap",$HistoryMap);

			$smarty->assign("FmoduleID",$FmoduleID);
			$smarty->assign("SmoduleID",$SmoduleID);


			$smarty->assign("FirstModuleSelected",$FirstModuleSelected);
			$smarty->assign("SecondModulerelation",$SecondModulerelation);

			//put the smarty modal
			$smarty->assign("Modali",put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings));

			$smarty->assign("FirstModuleFields",$FirstModuleFields);

			$smarty->assign("PopupJS",$MyArray);

			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output = $smarty->fetch('modules/MapGenerator/MasterDetail.tpl');
			echo $output;



			
		}else
		{
			throw new Exception("Missing the MApID also The QueryHIstory", 1);
			
		}

	} catch (Exception $ex) {
		$log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
		echo $ex;
	}
}



/**
 * function to load the map type Mapping 
 * @param [type] $QueryHistory Type string is the id of query 
 * @param [type] $MapID        string is the MapId if missing the QueryId
 * @return The template loaded 
 */
function Mapping_View($QueryHistory,$MapID)
{
	global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $root_directory, $current_user,$log;
	$theme_path = "themes/" . $theme . "/";
	$image_path = $theme_path . "images/";
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
			
			$popupArray=array();
			
			$MyArray=array();
			$xml=new SimpleXMLElement(get_The_history($QueryHistory,"query")); 
			$nrindex=0;
			foreach($xml->fields->field as $field)
			{
				$araymy=[
					 'FirstFieldtxt' =>explode(",",  Get_Modul_fields_check_from_load($xml->targetmodule[0]->targetname,$field->fieldname))[1],
					'FirstFieldval' => explode(",",Get_Modul_fields_check_from_load($xml->targetmodule[0]->targetname,$field->fieldname))[0],

					'FirstModuleval' =>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[0],

					'FirstModuletxt' =>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[1],

					'SecondModuletxt' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[1],

					'SecondModuleval' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[1],

					'SecondFieldval' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[0],
					'idJSON'=>$nrindex++,
					 'SecondFieldtext' => explode(",",Get_Modul_fields_check_from_load($field->Orgfields->Relfield->RelModule,$field->Orgfields->Relfield->RelfieldName))[1],

					'SecondFieldval' => explode(",",Get_Modul_fields_check_from_load($field->Orgfields->Relfield->RelModule,$field->Orgfields->Relfield->RelfieldName))[0],
					'SecondFieldOptionGrup'=>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[0]

				];

				array_push($MyArray,$araymy);
			}

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

			$smarty->assign("PopupJson",$MyArray);

			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output = $smarty->fetch('modules/MapGenerator/MappingView.tpl');
			echo $output;

		}elseif(!empty($MapID)){

			$xml=new SimpleXMLElement(get_form_Map($MapID)); 
			$FirstModuleSelected=Get_First_Moduls( $xml->targetmodule[0]->targetname);
			 $SecondModulerelation=GetModulRelOneTomulti($xml->targetmodule[0]->targetname ,$xml->originmodule[0]->originname);
			$FirstModuleFields=getModFields($xml->targetmodule[0]->targetname);
			$SecondModuleFields=getModFields($xml->originmodule[0]->originname);
			$MapName=get_form_Map($MapID,"mapname");
			$HistoryMap=get_form_Map($MapID,"mvqueryid").",".$MapID;

			// value for Save As 
			$data="MapGenerator,SaveTypeMaps";
			$dataid="ListData,MapName";
			$savehistory="true";

			$popupArray=array();
			
			$MyArray=array();
			$xml=new SimpleXMLElement(get_form_Map($MapID)); 
			$nrindex=0;
			foreach($xml->fields->field as $field)
			{
				$araymy=[
					 'FirstFieldtxt' =>explode(",",  Get_Modul_fields_check_from_load($xml->targetmodule[0]->targetname,$field->fieldname))[1],
					'FirstFieldval' => explode(",",Get_Modul_fields_check_from_load($xml->targetmodule[0]->targetname,$field->fieldname))[0],

					'FirstModuleval' =>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[0],

					'FirstModuletxt' =>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[1],

					'SecondModuletxt' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[1],

					'SecondModuleval' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[1],

					'SecondFieldval' =>explode("#",GetModulRelOneTomultiTextVal($xml->targetmodule[0]->targetname,$xml->originmodule[0]->originname))[0],
					'idJSON'=>$nrindex++,
					 'SecondFieldtext' => explode(",",Get_Modul_fields_check_from_load($field->Orgfields->Relfield->RelModule,$field->Orgfields->Relfield->RelfieldName))[1],

					'SecondFieldval' => explode(",",Get_Modul_fields_check_from_load($field->Orgfields->Relfield->RelModule,$field->Orgfields->Relfield->RelfieldName))[0],
					'SecondFieldOptionGrup'=>explode("#", Get_First_Moduls_TextVal($xml->targetmodule[0]->targetname))[0]

				];

				array_push($MyArray,$araymy);
			}


			$smarty = new vtigerCRM_Smarty();
			$smarty->assign("MOD", $mod_strings);
			$smarty->assign("APP", $app_strings);
			
			$smarty->assign("MapName", $MapName);

			$smarty->assign("HistoryMap",$HistoryMap);

			$smarty->assign("FirstModuleSelected",$FirstModuleSelected);
			$smarty->assign("SecondModulerelation",$SecondModulerelation);

			$smarty->assign("PopupJson",$MyArray);

			//put the smarty modal
			$smarty->assign("Modali",put_the_modal_SaveAs($data,$dataid,$savehistory,$mod_strings,$app_strings));

			$smarty->assign("FirstModuleFields",$FirstModuleFields);
			$smarty->assign("SecondModuleFields",$SecondModuleFields);

			$output1 = $smarty->fetch('modules/MapGenerator/MappingView.tpl');
			echo $output1;
		}

		
	} catch (Exception $ex) {
		$log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
		echo "Missing the Id of the Map and also the Id of query history ";
	}
}


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


function Get_First_Moduls_TextVal($value="")
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
			$a =$modul1 . '#' . str_replace("'", "", getTranslatedString($modul1));
			// echo "nese plotesohet kushti firstmodulexml";
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
		throw new Exception(TypeOFErrors::INFOLG." The ID for history is Emtpy", 1);
	}

	try {

		$q="SELECT * FROM mapgeneration_queryhistory Where id='$Id_Encrypt' AND active=1 ";

		$result = $adb->query($q);
		$num_rows = $adb->num_rows($result);
		if (empty($field_take)) {
			throw new Exception(TypeOFErrors::ErrorLG."r Missing the Filed you wnat to take", 1);
		}

		if ($num_rows>0) {
			$Resulti = $adb->query_result($result,0, $field_take);

			if (!empty($Resulti)) {
				return $Resulti;
			} else {
				throw new Exception(TypeOFErrors::ErrorLG." Something was wrong RESULT IS EMPTY", 1);
			}
		} else {
			throw new Exception(TypeOFErrors::ErrorLG."Not exist daata with this ID="+$Id_Encrypt,1);
		}
	} catch (Exception $ex) {
		 $log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
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
		throw new Exception(TypeOFErrors::INFOLG." The ID for Map is Emtpy", 1);
	}

	try {

		$q="SELECT cb.*,cr.* FROM vtiger_cbmap cb JOIN vtiger_crmentity cr ON cb.cbmapid=cr.crmid WHERE cr.deleted=0 and cb.cbmapid='$MapID'";

		$result = $adb->query($q);
		$num_rows = $adb->num_rows($result);
		if (empty($field_take)) {
			throw new Exception(TypeOFErrors::ErrorLG." Missing the Filed you wnat to take", 1);
		}

		if ($num_rows>0) {
			$Resulti = $adb->query_result($result,0, $field_take);

			if (!empty($Resulti)) {
				return $Resulti;
			} else {
				throw new Exception(TypeOFErrors::ErrorLG." Something was wrong RESULT IS EMPTY", 1);
			}
		} else {
			throw new Exception(TypeOFErrors::ErrorLG."Not exist Map with this ID=".$MapID,1);
		}
	} catch (Exception $ex) {
		 $log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
		 return "";
	}
}

/**
 * Function to take the fields from modul 
 * @param  [type] $module Madul name
 * @param  [type] $dbname Null
 *  @param  [type] $Checkname check the field with the filed from map
 * @return [type]         return the filed of modul 
 */
function Get_Modul_fields_check_from_load($module,$checkname,$dbname)
{
	// echo $module." ## ".$checkname;
	// exit();
    global $log;
    $log->debug(TypeOFErrors::INFOLG."Entering getAdvSearchfields(".$module.") method ...");
    global $adb;
    global $current_user;
    global $mod_strings,$app_strings;
    $OPTION_SET.= '';
    $tabid = getTabid($module,$dbname);
    if($tabid==9)
        $tabid="9,16";


    $sql = "select * from  vtiger_field ";
    $sql.= " where vtiger_field.tabid in(?) and";
    $sql.= " vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2)";
    if($tabid == 13 || $tabid == 15)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Add Comment'";
    }
    if($tabid == 14)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Product Image'";
    }
    if($tabid == 9 || $tabid==16)
    {
        $sql.= " and vtiger_field.fieldname not in('notime','duration_minutes','duration_hours')";
    }
    if($tabid == 4)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Contact Image'";
    }
    if($tabid == 13 || $tabid == 10)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Attachment'";
    }
    $sql.= " group by vtiger_field.fieldlabel order by block,sequence";

    $params = array($tabid);


    $result = $adb->pquery($sql, $params);
    $noofrows = $adb->num_rows($result);
    $block = '';
    $select_flag = '';
    //echo "edmondi 2";
    for($i=0; $i<$noofrows; $i++)
    {
        $fieldtablename = $adb->query_result($result,$i,"tablename");
        $fieldcolname = $adb->query_result($result,$i,"columnname");
        $fieldname = $adb->query_result($result,$i,"fieldname");
        $block = $adb->query_result($result,$i,"block");
        $fieldtype = $adb->query_result($result,$i,"typeofdata");
        $fieldtype = explode("~",$fieldtype);
        $fieldtypeofdata = $fieldtype[0];
        if($fieldcolname == 'account_id' || $fieldcolname == 'accountid' || $fieldcolname == 'product_id' || $fieldcolname == 'vendor_id' || $fieldcolname == 'contact_id' || $fieldcolname == 'contactid' || $fieldcolname == 'vendorid' || $fieldcolname == 'potentialid' || $fieldcolname == 'salesorderid' || $fieldcolname == 'quoteid' || $fieldcolname == 'parentid' || $fieldcolname == "recurringtype" || $fieldcolname == "campaignid" || $fieldcolname == "inventorymanager" ||  $fieldcolname == "currency_id")
            $fieldtypeofdata = "V";
        if($fieldcolname == "discontinued" || $fieldcolname == "active")
            $fieldtypeofdata = "C";
        $fieldlabel = $mod_strings[$adb->query_result($result,$i,"fieldlabel")];

        // Added to display customfield label in search options
        if($fieldlabel == "")
            $fieldlabel = $adb->query_result($result,$i,"fieldlabel");

        if($fieldlabel == "Related To")
        {
            $fieldlabel = "Related to";
        }
        if($fieldlabel == "Start Date & Time")
        {
            $fieldlabel = "Start Date";
            if($module == 'Activities' && $block == 19)
                $module_columnlist['vtiger_activity:time_start::Activities_Start Time:I'] = 'Start Time';

        }
        //$fieldlabel1 = str_replace(" ","_",$fieldlabel); // Is not used anywhere
        //Check added to search the lists by Inventory manager
        if($fieldtablename == 'vtiger_quotes' && $fieldcolname == 'inventorymanager')
        {
            $fieldtablename = 'vtiger_usersQuotes';
            $fieldcolname = 'user_name';
        }
        if($fieldtablename == 'vtiger_contactdetails' && $fieldcolname == 'reportsto')
        {
            $fieldtablename = 'vtiger_contactdetails2';
            $fieldcolname = 'lastname';
        }
        if($fieldtablename == 'vtiger_notes' && $fieldcolname == 'folderid'){
            $fieldtablename = 'vtiger_attachmentsfolder';
            $fieldcolname = 'foldername';
        }
        if($fieldlabel != 'Related to')
        {
            if ($i==0)
                $select_flag = "";

            $mod_fieldlabel = $mod_strings[$fieldlabel];
            if($mod_fieldlabel =="") $mod_fieldlabel = $fieldlabel;

            if($fieldlabel == "Product Code") {

                $OPTION_SET .= $fieldtablename . ":" . $fieldcolname . ":" . $fieldname . "::" . $fieldtypeofdata . "" . $select_flag.",".$mod_fieldlabel."#";


            }
            if($fieldlabel == "Reports To"){

                $OPTION_SET .=$fieldtablename.":".$fieldcolname.":".$fieldname."::".$fieldtypeofdata."".$select_flag.",".$mod_fieldlabel."#";

            }

            elseif($fieldcolname == "contactid" || $fieldcolname == "contact_id")
            {

                $OPTION_SET .= "vtiger_contactdetails:lastname:".$fieldname."::".$fieldtypeofdata."".$select_flag.",".$app_strings['LBL_CONTACT_LAST_NAME']."#";
                $OPTION_SET .= "vtiger_contactdetails:firstname:".$fieldname."::".$fieldtypeofdata.",".$app_strings['LBL_CONTACT_FIRST_NAME']."#";



            }
            elseif($fieldcolname == "campaignid")
                $OPTION_SET .= "vtiger_campaign:campaignname:".$fieldname."::".$fieldtypeofdata."".$select_flag.",".$mod_fieldlabel."#";
            else
                $OPTION_SET .=$fieldtablename.":".$fieldcolname.":".$fieldname."::".$fieldtypeofdata." ".$select_flag.",".str_replace("'","`",$fieldlabel)."#";
        }
    }
    //Added to include Ticket ID in HelpDesk advance search
    if($module == 'HelpDesk')
    {
        $mod_fieldlabel = $mod_strings['Ticket ID'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Ticket ID';

        $OPTION_SET .= "vtiger_crmentity:crmid:".$fieldname."::".$fieldtypeofdata.",".$mod_fieldlabel."#";
    }
    //Added to include activity type in activity advance search
    if($module == 'Activities')
    {
        $mod_fieldlabel = $mod_strings['Activity Type'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Activity Type';

        $OPTION_SET .= "vtiger_activity.activitytype:".$fieldname."::".$fieldtypeofdata.",".$mod_fieldlabel."#";
    }
    $log->debug(TypeOFErrors::INFOLG."Exiting getAdvSearchfields method ...");

    $returncorrectdata=explode("#", $OPTION_SET);

    foreach ($returncorrectdata as $value) {
    	if (contains(explode(",", $value)[0],trim($checkname)) == true) {
    		return $value;
    	}
    }

    return "";
}

// 
/**
 * function get all relation module only multi to one
 * @param [type] $m         Moduli
 * @param [type] $CheckNAme  The name of modul you want to check
 */
function GetModuleMultiToOneForLOadListColumns($m,$CheckNAme)
   {
    global $log, $mod_strings,$adb;
    $j = 0;
   $query1 = "SELECT  module, columnname, fieldlabel from  vtiger_fieldmodulerel 
             join  vtiger_field on  vtiger_field.fieldid= vtiger_fieldmodulerel.fieldid
             where relmodule='$m' and module<>'Faq' and module<>'Emails' and module<>'Events' and module<>'Webmails' and module<>'SMSNotifier'
             and module<>'PBXManager' and module<>'Modcomments' and module<>'Calendar' 
             and relmodule in (select name from  vtiger_tab where presence=0) 
             and module in (select name from  vtiger_tab where presence=0)";


    $result1 = $adb->query($query1);
    $num_rows1 = $adb->num_rows($result1);
    if ($num_rows1 != 0) {
        for ($i = 1; $i <= $num_rows1; $i++) {
            $modul1 = $adb->query_result($result1, $i - 1, 'module');
            $column = $adb->query_result($result1, $i - 1, 'columnname');
            $fl = $adb->query_result($result1, $i - 1, 'fieldlabel');
            if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                $a= $modul1 . '(many);' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1));
            }
        }
    }
    if ($m == "Accounts") {
        $query2 = "SELECT name, columnname, fieldlabel from  vtiger_field
                join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where (uitype=73 or uitype=50
                or uitype=51 or uitype=68) and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events'
                and name<>'Webmails' and name<>'SMSNotifier' and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' 
                and  vtiger_tab.presence=0";

        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");
                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a=$modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }                    
                }
        }
    }
    if ($m == "Contacts") {
        $query2 = "SELECT name, columnname, fieldlabel from  vtiger_field 
                  join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid 
                  where (uitype=57 or uitype=68) and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events' 
                  and name<>'Webmails' and name<>'SMSNotifier'and name<>'PBXManager' and name<>'Modcomments' 
                  and name<>'Calendar' and  vtiger_tab.presence=0";

        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");
                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                       $a =$modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }
                }
        }
    }
    if ($m == "Produts") {
        $query2 = "SELECT columnname,name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where  uitype=59 and name<>'$m'
        and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

    ";


        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select *  from vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a=  $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1))."#";
                    }
            }
        }
    }
    if ($m == "Campaigns") {
        $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where  uitype=58 and name<>'$m'
        and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0
    ";


        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a = $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }
            }
        }
    }
    if ($m == "Potentials") {
        $query2 = "SELECT columnname, name ,fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=76 and name<>'$m'
        and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and   vtiger_tab.presence=0

    ";


        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a= $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }
            }
        }
    }
    if ($m == "Quotes") {
        $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=78
        and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

    ";

        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0)
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a = $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }

            }
        }
    }
    if ($m == "SalesOrder") {
        $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=81 and uitype=75 and name<>'$m'
        and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

    ";


        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $$fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0 && $CheckNAme==$modul1)
                    $a =  $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) ."#" ;


            }
        }
    }
    if ($m == "Vendors") {
        $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=80 and name<>'$m'
        and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

    ";


        $result2 = $adb->query($query2);
        $num_rows2 = $adb->num_rows($result2);
        if ($num_rows2 != 0) {
            for ($i = 1; $i <= $num_rows2; $i++) {
                $modul1 = $adb->query_result($result2, $i - 1, 'name');
                $column = $adb->query_result($result2, $i - 1, 'columnname');
                $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
                $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

                if ($adb->num_rows($mo) != 0) {
                    if (strlen($CheckNAme) != 0 && $CheckNAme == $modul1) {
                        $a =  $modul1 . '(many); ' . $column . ',' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . '); ' . str_replace("'", "", getTranslatedString($fl, $modul1));
                    }
                }
            }
        }
    }
    return $a;
}




/**
 * function to find in string if exist a substring
 * @param  [type]  $haystack      the string you want to check inside 
 * @param  [type]  $needle        the substring 
 * @param  boolean $caseSensitive  flag if you want to use the case sensinitive
 * @return [type]                 type bool true or false
 */
function contains($haystack, $needle, $caseSensitive = false) {
	global $log;
	try
	{
		return $caseSensitive ?
		(strpos($haystack, $needle) === false ? false : true):
		(stripos($haystack, $needle) === false ? false : true);
	}catch(Exception $ex)
	{
		$log->debug(TypeOFErrors::ErrorLG."Something was wrong check the Exception ".$ex);
		return FALSE;
	}
}