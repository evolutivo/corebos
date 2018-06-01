<?php
function createrealEntity($request) {
include_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/cbMap/cbMap.php');
require_once("modules/Users/Users.php");
global $adb, $log, $current_user;

$response=array();
    $recordid = $request['recordid'];
    $mapid = $request['mapid'];
    $origin_module=$request['origin_module']; //It is not empty for Emails in Issues, Can be empty in all other cases
    $target_module=$request['target_module']; //It is not empty for Emails in Issues, Can be empty in all other cases
    $origin_form=$request['origin_form'];//necessary for Form Extensions 
    $allparameters = array();
    $fixedparams=""; 
    $actionfocus=CRMEntity::getInstance("BusinessActions");
    $actionfocus->retrieve_entity_info($request['accid'],"BusinessActions");
    $res_logic=$actionfocus->runBusinessLogic2($recordid);
    if($res_logic){
        if(!empty($mapid)){
        $focus1 = CRMEntity::getInstance("cbMap");
        $focus1->retrieve_entity_info($mapid, "cbMap");

        $origin_module = $focus1->getMapOriginModule();

        $target_module = $focus1->getMapTargetModule();

        $target_fields = $focus1->readMappingType();

        $pointingField = $focus1->readPointingField();

        include_once ("modules/$target_module/$target_module.php");
        include_once ("modules/$origin_module/$origin_module.php");

        $focusToBeUpdate=CRMEntity::getInstance($target_module);
        if(empty($pointingField) || $pointingField==''){
            $focusToBeUpdate->mode='';
            $focusToBeUpdate->id='';
            $focusToBeUpdate->column_fields["assigned_user_id"]=$current_user->id;
            $valuePointing=$recordid;
        }
        else if($pointingField=='actualRecord'){
            $focusToBeUpdate->retrieve_entity_info($recordid, $target_module);
            $focusToBeUpdate->mode='edit';
            $focusToBeUpdate->id=$recordid;
            $valuePointing=$recordid;
        }else{
            $focusToBeUpdate->retrieve_entity_info($recordid, $target_module);
            $focusToBeUpdate->mode='edit';
            $focusToBeUpdate->id=$recordid;
            $valuePointing=$focusToBeUpdate->column_fields["$pointingField"];
        }
        //$focus = new $target_module();

        $focus2 = CRMEntity::getInstance($origin_module);
        $focus2->retrieve_entity_info($valuePointing, $origin_module);
        foreach ($target_fields as $key => $value) {
            $foundValues = array();
            $fieldval="";
            if (!empty($value['value'])) {
                $fieldval = $value['value'];
            } else {
                if (isset($value['listFields']) && !empty($value['listFields'])) {
                    for ($i = 0; $i < count($value['listFields']); $i++) {
                        if($value['listFields'][$i]=='CURRENT_USER'){
                          $foundValues[] = $current_user->id;  
                        }
                        else{
                          $foundValues[] = $focus2->column_fields[$value['listFields'][$i]];
                        }
                    }
                } elseif (isset($value['relatedFields']) && !empty($value['relatedFields'])) {
                    for ($i = 0; $i < count($value['relatedFields']); $i++) {
                        $relInformation = $value['relatedFields'][$i];
                        $relModule = $relInformation['relmodule'];
                        $linkField = $relInformation['linkfield'];
                        $fieldName = $relInformation['fieldname'];
                        $otherid = $focus2->column_fields[$linkField];
                         if(isRecordExists($otherid)) {
                            include_once "modules/$relModule/$relModule.php";
                            $otherModule = CRMEntity::getInstance($relModule);
                            $otherModule->retrieve_entity_info($otherid, $relModule);
                            if($fieldName=='CURRENT_USER'){
                                $foundValues[] = $current_user->id;  
                            }
                            else{
                                $foundValues[] = $otherModule->column_fields[$fieldName];
                            }
                        }
                    }
                }
               $fieldval = implode($value['delimiter'], $foundValues);
            }
            $focusToBeUpdate->column_fields["$key"]=$fieldval;
            $allparameters[] = $key."=".htmlspecialchars($fieldval,ENT_QUOTES);

        }
        $focusToBeUpdate->save("$target_module");
        $allparameters[] = "return_action=DetailView";
        $allparameters[] = "return_module=".$origin_module;
        $allparameters[] = "return_id=$recordid";
    }
    //Only for emails
        if($target_module=="Emails"){
            $allparameters[]="pmodule=$origin_module";
            $allparameters[]="sendmail=true";
            $allparameters[]="idlist=$recordid";
        }
    $allparameters[]="module=$target_module";
    $result=$adb->pquery('Select * '
            . ' from  vtiger_tab '
            . ' WHERE isentitytype=? AND name=?',Array(4, $target_module));
    if($result && $adb->num_rows($result)>0)
    {
        $allparameters[]="action=index";//the target module is Form Extension
        $allparameters[]="origin_form=$origin_form";
    }
    else{
        $allparameters[]="action=EditView";
    }
}
$fixedparams=implode("&",$allparameters);

$url="index.php?$fixedparams";
$log->debug($url);
$response['linkurl']=$url;
return $response;
}
?>
