<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
$functiontocall=$_REQUEST['functiontocall'];
$fldname=$_REQUEST['fldname'];
$srcmodule=$_REQUEST['checkmodule'];
$currentFieldVal=$_REQUEST['currentFieldVal'];
$autocompleteVal=$_REQUEST['autocompleteVal'];
$func= new Func();
$result=$func->$functiontocall($fldname,$srcmodule,$currentFieldVal,$autocompleteVal);
echo json_encode($result);


class Func
{
    function getReferenceAutocomplete($fldname,$srcmodule,$currentFieldVal,$autocompleteVal){
        global $adb;
        $find_config=$adb->pquery("Select * "
                . " from vtiger_field"
                . " join vtiger_ng_fields on vtiger_field.fieldid=vtiger_ng_fields.field_id"
                . " where vtiger_field.fieldname=? and vtiger_field.tabid=?",array($fldname,getTabid($srcmodule)));
        $moduleid=$adb->query_result($find_config,0,'moduleid');
        $modulename=  getTabname($moduleid);
        require_once ("modules/$modulename/$modulename.php");
        $moduleFocus=  CRMEntity::getInstance($modulename);
        $table_name=$moduleFocus->table_name;
        $table_index=$moduleFocus->table_index;
        $br_id=$adb->query_result($find_config,0,'br_id');
        $fld_shown=$adb->query_result($find_config,0,'fld_shown');
        $field_to_show=explode(',',$fld_shown);
        $fld_search=$adb->query_result($find_config,0,'fld_search');  
        $field_to_search=explode(',',$fld_search);
        $content=array();

        if($br_id!='')
        {
            $businessrulesid = $br_id;
            $res_buss = $adb->pquery("select * from vtiger_businessrules where businessrulesid=?", array($businessrulesid));
            $isRecordDeleted = $adb->query_result($res_buss, 0, "deleted");
            if ($isRecordDeleted == 0 || $isRecordDeleted == '0') {
                require_once "modules/BusinessRules/BusinessRules.php";
                require_once "modules/cbMap/cbMap.php";
                $br_focus = CRMEntity::getInstance("BusinessRules");
                $br_focus->retrieve_entity_info($businessrulesid, "BusinessRules");
                $mapid=$br_focus->column_fields['linktomap'];
                $mapfocus=  CRMEntity::getInstance("cbMap");
                $mapfocus->retrieve_entity_info($mapid,"cbMap");
                $mapfocus->id=$mapid;
                $businessrules_qyery=$mapfocus->getMapSQL(); 
            }
        }
        if($autocompleteVal!=='')
        {
            $sql=$businessrules_qyery;
            for($count=0;$count<sizeof($field_to_search);$count++){
                   $array_to_search[]=$field_to_search[$count]. " like '$autocompleteVal%' ";
            }
            $string_to_search=implode(' OR ',$array_to_search);
            $sql.="  and ($string_to_search) ";
            $result=$adb->pquery($sql,array());
            for($i=0;$i<$adb->num_rows($result);$i++)
            {
               $string_to_show='';
               $entityid=$adb->query_result($result,$i,"$table_index");
               for($count=0;$count<sizeof($field_to_show);$count++){
                   $string_to_show.=$adb->query_result($result,$i,"$field_to_show[$count]").' ';
               }               
               $content[]=array('id'=>"$entityid",
                   'name'=>$string_to_show);
            }
            
        }
        return $content;
    } 
    function fillCurrentAutocomplete($fldname,$srcmodule,$currentFieldVal,$autocompleteVal){
        global $adb;
        $find_config=$adb->pquery("Select * "
                . " from vtiger_field"
                . " join vtiger_ng_fields on vtiger_field.fieldid=vtiger_ng_fields.field_id"
                . " where vtiger_field.fieldname=? and vtiger_field.tabid=?",array($fldname,getTabid($srcmodule)));
        $moduleid=$adb->query_result($find_config,0,'moduleid');
        $modulename=  getTabname($moduleid);
        require_once "modules/$modulename/$modulename.php";
        $moduleFocus=  CRMEntity::getInstance("$modulename");
        $table_name=$moduleFocus->table_name;
        $table_index=$moduleFocus->table_index;
        $br_id=$adb->query_result($find_config,0,'br_id');
        $fld_shown=$adb->query_result($find_config,0,'fld_shown');
        $field_to_show=explode(',',$fld_shown);
        $fld_search=$adb->query_result($find_config,0,'fld_search');  
        $field_to_search=explode(',',$fld_search);
        $content=array();

        if($br_id!='')
        {
            $businessrulesid = $br_id;
            $res_buss = $adb->pquery("select * from vtiger_businessrules where businessrulesid=?", array($businessrulesid));
            $isRecordDeleted = $adb->query_result($res_buss, 0, "deleted");
            if ($isRecordDeleted == 0 || $isRecordDeleted == '0') {
                require_once "modules/BusinessRules/BusinessRules.php";
                require_once "modules/cbMap/cbMap.php";
                $br_focus = CRMEntity::getInstance("BusinessRules");
                $br_focus->retrieve_entity_info($businessrulesid, "BusinessRules");
                $mapid=$br_focus->column_fields['linktomap'];
                $mapfocus=  CRMEntity::getInstance("cbMap");
                $mapfocus->retrieve_entity_info($mapid,"cbMap");
                $mapfocus->id=$mapid;
                $businessrules_qyery=$mapfocus->getMapSQL(); 
            }
        }
        if($currentFieldVal!='')
        { 
            $arr_currentFieldVal=explode(',',$currentFieldVal);
            $sql=$businessrules_qyery;
            $sql.=" and $table_index in (".  generateQuestionMarks($arr_currentFieldVal).")
                    ORDER BY FIELD( $table_index, $currentFieldVal) 
                ";  
            $result=$adb->pquery($sql,array($arr_currentFieldVal));
            for($i=0;$i<$adb->num_rows($result);$i++)
            {
               $entityid=$adb->query_result($result,$i,"$table_index");
               $content[$i]['id']=$entityid;
               for($count=0;$count<sizeof($field_to_show);$count++){
                   $string_to_show.=$adb->query_result($result,$i,"$field_to_show[$count]").' ';
               } 
               $content[$i]['name']=$string_to_show;
            }
        }
        return $content;
    }
}

?>
