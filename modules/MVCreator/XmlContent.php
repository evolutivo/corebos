<?php
/**
 * Created by PhpStorm.
 * User: Edmond Kacaj
 * Date: 5/10/2017
 * Time: 2:20 PM
 */
require_once('include/utils/utils.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once('database/DatabaseConnection.php');
require_once('include/CustomFieldUtil.php');
require_once('data/Tracker.php');



//global $log, $adb;

//if (isset($_POST['MapID'])){
//    $mapid=$_POST['MapID'];
//    $XmlConvertertoarray=array();
//    $query=$adb->query("select description from vtiger_crmentity where crmid=$mapid");
//    $description=$adb->query_result($query,0,"description");
//    $movies = new SimpleXMLElement($description);
//    $FirstSecondModule=array();
//    $Fields=array();
//    $FirstSecondModule[]=array(
//        'FmoduleID'=>$movies->Fmodule[0]->FmoduleID,
//        'FmoduleName'=>$movies->Fmodule[0]->FmoduleName,
//        'SecmoduleID'=>$movies->Secmodule[0]->SecmoduleID,
//        'SecmoduleName'=>$movies->Secmodule[0]->SecmoduleName,
//
//    );
//     foreach($movies->fields->field as $field) {
//         $Fields[]=array(
//                'fieldname' => $field->fieldname,
//                'fieldID'=>$field->fieldID,
//                );
//
//     }
//
//}

function takeFirstMOduleFromXMLMap($MapID)
{
    global $log, $adb;
  if (isset($MapID)) {
      $FmoduleID = "";
      $FmoduleName = "";
      $query = $adb->query("select description from vtiger_crmentity where crmid=$MapID");
      $description = $adb->query_result($query, 0, "description");
      $movies = new SimpleXMLElement($description);
      $FmoduleID = $movies->Fmodule[0]->FmoduleID;
      $FmoduleName = $movies->Fmodule[0]->FmoduleName;
      return $FmoduleName;
      //return $FmoduleID;
  }else{
      return "";
  }

}




function takeSecondMOduleFromXMLMap($MapID)
{
    global $log, $adb;
   if (isset($MapID)) {
       $SecmoduleID = "";
       $SecmoduleName = "";
       $query = $adb->query("select description from vtiger_crmentity where crmid=$MapID");
       $description = $adb->query_result($query, 0, "description");
       $movies = new SimpleXMLElement($description);
       $SecmoduleID = $movies->Secmodule[0]->SecmoduleID;
       $SecmoduleName = $movies->Secmodule[0]->SecmoduleName;
       return $SecmoduleName;
       //return $FmoduleID;
   }
   else{
       return "";
   }

}

function takeAllFileds($MapID)
{
    global $log, $adb;
    if (isset($MapID)) {
        $query = $adb->query("select description from vtiger_crmentity where crmid=$MapID");
        $description = $adb->query_result($query, 0, "description");
        $movies = new SimpleXMLElement($description);
        $Fields=array();
        foreach($movies->fields->field as $field => $value) {
            $Fields[]=  $value;//  $field->fieldname;


        }
        return $Fields;
    }else{
        return "";
    }

}


//$theme_path="themes/".$theme."/";
//$image_path=$theme_path."images/";
//$smarty = new vtigerCRM_Smarty();
//$smarty->assign("MOD", $mod_strings);
//$smarty->assign("APP", $app_strings);
//$smarty->assign("FirstSecModule", $FirstSecondModule);
//$smarty->assign("Fields", $Fields);
//$smarty->assign("MapID", $mapid);
//$output = $smarty->fetch('modules/MVCreator/createJoinCondition.tpl');
//echo $output;
?>

