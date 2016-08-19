<?php

/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */

require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('modules/cbMap/cbMap.php');
require_once 'include/logging.php';
require_once('include/utils/VTCacheUtils.php');
require_once('vtlib/Vtiger/Module.php');
require_once 'include/QueryGenerator/QueryGenerator.php';
require_once('include/utils/CommonUtils.php');
global $adb, $log, $current_user, $default_charset;

function getTypeName($type) {
    $types[1] = 'number';
    $types[2] = 'number';
    $types[3] = 'number';
    $types[4] = 'number';
    $types[5] = 'number';
    $types[5] = 'number';
    $types[8] = 'number';
    $types[9] = 'number';
    $types[246] = 'number';
    $types[7] = 'datetime';
    $types[10] = 'datetime';
    $types[11] = 'datetime';
    $types[12] = 'datetime';

    if (!isset($types[$type]))
        return 'string';
    else
        return $types[$type];
}

function renderDataTable($recordSet) {
    $ret = array();
    $cols = array();
    global $log, $adb;

    $columns = $recordSet->fields;
    foreach ($columns as $key => $value) {

        if (gettype($key) == 'string') {
            $columnDescr = array();
            $columnDescr['id'] = $key;
            $columnDescr['label'] = $key;
            $columnDescr['type'] = getTypeName($key);
            $ret['cols'][] = $columnDescr;
            $cols[] = $key;
        }
    }

    for ($i = 0; $i < $adb->num_rows($recordSet); $i++) {
        $array = $adb->query_result_rowdata($recordSet, $i);
        foreach ($cols as $key => $ColumnName)
            $values[$ColumnName] = $array[$ColumnName];

        $rowData = array();
        $rowData['c'] = array();
        $col_index = 0;

        foreach ($values as $value) {
            if ($ret['cols'][$col_index]['type'] == 'number') {
                $rowData['c'][] = array("v" => $value * 1);
            } else {
                $rowData['c'][] = array("v" => $value);
            }

            $col_index++;
        }

        $ret['rows'][] = $rowData;
    }
    return $ret;
}

function renderRequestedList($recordSet) {
    $ret = array();

    while ($array = $recordSet->fetch_array(MYSQLI_NUM)) {
        if (!isset($array[1]))
            $array[1] = $array[0];
        $ret[] = array('id' => $array[0], 'text' => $array[1]);
    }
    $ret_json = json_encode($ret);
    return $ret_json;
}


function createModel($sql) {
    global $adb;
    $DataType = array("VM" => array('defaultOperator' => 'StartsWith', 'operators' => array(0 => 'StartsWith', 1 => 'Contains', 2 => 'NotStartsWith', 3 => 'NotContains', 4 => 'InList', 5 => 'Equal', 6 => 'NotEqual', 7 => 'IsNull', 8 => 'InSubQuery',9=>'IsNotNull')),
                     "Date" => array('defaultOperator' => 'DateEqualSpecial', 'operators' => array(0 => 'DateEqualSpecial', 1 => 'DateEqualPrecise', 2 => 'DateNotEqualSpecial', 3 => 'DateNotEqualPrecise', 4 => 'DateBeforeSpecial', 5 => 'DateBeforePrecise', 6 => 'DateAfterSpecial', 7 => 'DateAfterPrecise', 8 => 'DatePeriodPrecise', 9 => 'MaximumOfAttr', 10 => 'IsNull',11=>'IsNotNull')),
                     "Int" => array('defaultOperator' => 'Equal', 'operators' => array(0 => 'Equal', 1 => 'Between', 2 => 'LessThan', 3 => 'LessOrEqual', 4 => 'GreaterThan', 5 => 'GreaterOrEqual', 6 => 'NotBetween', 7 => 'NotEqual', 8 => 'MaximumOfAttr', 9 => 'InSubQuery', 10 => 'IsNull',11=>'IsNotNull')),
                     "Currency" => array('defaultOperator' => 'DateEqualSpecial', 'operators' => array(0 => 'DateEqualSpecial', 1 => 'DateEqualPrecise', 2 => 'DateNotEqualSpecial', 3 => 'DateNotEqualPrecise', 4 => 'DateBeforeSpecial', 5 => 'DateBeforePrecise', 6 => 'DateAfterSpecial', 7 => 'DateAfterPrecise', 8 => 'DatePeriodPrecise', 9 => 'MaximumOfAttr', 10 => 'IsNull',11=>'IsNotNull')),
                     "Bool" => array('dataType' => 'Bool', 'size' => 2, 'UIC' => true, 'UIR' => true, 'UIS' => true, 'defaultOperator' => 'IsTrue', 'operators' => array(0 => 'IsTrue', 1 => 'NotTrue')),
                     "uitype10" => array('defaultOperator' => 'StartsWith', 'operators' => array(0 => 'StartsWith', 1 => 'Contains', 2 => 'NotStartsWith', 3 => 'NotContains', 4 => 'InList', 5 => 'Equal', 6 => 'NotEqual', 7 => 'IsNull', 8 => 'InSubQuery',9=>'IsNotNull')),
    );
    $query = $adb->pquery($sql, array());

    while ($atr_row = $adb->fetch_array($query)) {
        $typeofdata = $atr_row['type'];
        $uitype = $atr_row['uitype'];
        if ($uitype == 10) {
            $operator = $DataType['uitype10'];
        } else
        if ($uitype == 56) {
            $operator = $DataType['Bool'];
        } else
        if ($typeofdata == 'V')
            $operator = $DataType['VM'];
        else
        if ($typeofdata == 'D') {
            $operator = $DataType['Date'];
        } else
        if ($typeofdata == 'N')
            $operator = $DataType['Int'];

        $attributes[] = array('id' => $atr_row['fieldid'],
            'caption' => $atr_row['columnname'],
            'dataType' => $atr_row['typeofdata'],
            'size' => $atr_row['maximumlength'],
            'ConjOp'=>'',
            'module' => $atr_row['name'],
            'UIC' => true,
            'UIR' => true,
            'UIS' => true,
            'defaultOperator' => $operator['defaultOperator'],
            'operators' => $operator['operators']
        );
    }

    return $attributes;
}

function createQuery($selected_columns) {
    global $current_user, $log;
    foreach ($selected_columns as $module => $columns) {
        $modules[] = $module;
        $module_info = getEntityField($module);

        $log->Debug( json_encode($columns));
    }
    return ($a);
}


function find_related_modules($modules,$action,$uitype10_fieldids='',$other_uitypes=''){
    global $adb,$log;
    $modules_list=array();
   
    include_once 'include/utils/utils.php';
    
    if($action == 'getModel' && !empty($other_uitypes)){
        
    }
    else
    if (!empty($other_uitypes)) {
        foreach ($other_uitypes as $field_other => $other_field_data) {
            if (!in_array($other_field_data['module'], $modules))
                array_push($modules, $other_field_data['module']);
            $ui = $other_field_data['uitype'];
            if ($ui == 51 || $ui == 50 || $ui == 73 || $ui == 68) {
                 $relModule ="Accounts";
               /* if ($ui == 68)
                $relModule = "Contacts"; */
            }
            else if ($ui == 57) {
                $relModule = "Contacts";
               
            } else if ($ui == 59) {
                $relModule = "Products";
               
            } else if ($ui == 58) {
                $relModule = "Campaigns";
               
            } else if ($ui == 76) {
                $relModule = "Potentials";
               
            } else if ($ui == 75 || $ui == 81) {
                $relModule = "Vendors";
               
            } else if ($ui == 78) {
                $relModule = "Quotes";
               
            } else if ($ui == 80) {
                $relModule = "SalesOrder";
            }
             if (!in_array($relModule, $modules))
                 array_push($modules,$relModule); 
            VTCacheUtils::updateModulesList($other_field_data['fieldid'], $other_field_data['module'], $relModule);
 
        }
        
    }
    
     if ($action == 'getModel') {
        $modules_list = $modules;
        $query = $adb->pquery("SELECT distinct(relmodule) as module FROM  vtiger_fieldmodulerel WHERE  module   IN (" . generateQuestionMarks($modules) . ")  ",array($modules));
                           //  UNION SELECT distinct(module) as module FROM  vtiger_fieldmodulerel WHERE  relmodule   IN (" . generateQuestionMarks($modules) . ") ", array($modules, $modules));

        for ($i = 0; $i < $adb->num_rows($query); $i++) {
            array_push($modules_list, $adb->query_result($query, $i, 'module'));
        }
        return $modules_list;
    } 
    else {
        if(!empty($uitype10_fieldids))
            $cond=' and fieldid IN ('.implode(',',$uitype10_fieldids).' ) ';
        
        $query = $adb->pquery("SELECT * FROM  vtiger_fieldmodulerel WHERE module IN (" . generateQuestionMarks($modules[0]) . ") 
                and relmodule IN (" . generateQuestionMarks($modules) . ") $cond  ORDER BY module IN (" . generateQuestionMarks($modules) . ")  ", array($modules[0], $modules,$modules));
        
        if ($adb->num_rows($query) == 0)
            return $modules;
     
        for ($i = 0; $i < $adb->num_rows($query); $i++) {
            $modules_list[] = array('fieldid' => $adb->query_result($query, $i, 'fieldid'),
                'module' => $adb->query_result($query, $i, 'module'),
                'relmodule' => $adb->query_result($query, $i, 'relmodule')
            );
            VTCacheUtils::updateModulesList($adb->query_result($query, $i, 'fieldid'), $adb->query_result($query, $i, 'module'), $adb->query_result($query, $i, 'relmodule'));
        }
 }


    // $fieldid_name_query=$adb->pquery("select fieldname from vtiger_field where fieldid=?",array($fieldid));
     //       $search_module_info=getEntityFieldNames($search_module);
}

function getSelectFieldsSQL($fields) {
    VTCacheUtils::updateSelectedFields( $fields);
}

function getWhereConditionsSQL($WhereConditions) {
    VTCacheUtils::updateWhereFields($WhereConditions);
}

function buildSql($related_modules,$linkType) {

    global $current_user, $adb, $log;
    set_time_limit(0);
    ini_set('memory_limit', '1024M');
    $current_user = Users::getActiveAdminUser();
    $query = '';
    // $addCondittions='';
    $CachedModules = VTCacheUtils::lookupModulesInfo();
    $unique = VTCacheUtils::lookupModulesInfo();
    $fields = VTCacheUtils::lookupSelectedFields();
    $WhereConditions = VTCacheUtils::lookupWhereFields();
    $log->Debug('fields');
    $log->debug($fields);
    $log->Debug('related mod');
    $log->Debug($related_modules);
    $log->Debug('cached mod');
    $log->debug($CachedModules);
    $unique=array_map("unserialize", array_unique(array_map("serialize", $unique)));
    $log->Debug('conditions ');
    $log->Debug($WhereConditions);
    
    if (sizeof($related_modules) == 1 && empty($CachedModules)) {
        $moduleName = $related_modules[0];
        $queryGenerator = new QueryGenerator($moduleName, $current_user);
        if (in_array('smownerid', $fields[$moduleName])) {
            $fields[$moduleName][array_search('smownerid', $fields[$moduleName])]='assigned_user_id';
            $entityOwner[] = $moduleName;
        }
        $queryGenerator->setFields($fields[$moduleName]);
          if($linkType=='All')
           $Operator=$queryGenerator::$AND;
        else
        if($linkType=='Any')
           $Operator=$queryGenerator::$OR;
        foreach ($WhereConditions as $module => $fields_list) {
            foreach ($fields_list as $fieldid => $fieldinfo) {
                for ($f = 0; $f < count($fieldinfo); $f++) {
                    if($fieldinfo[$f]['ConjOp']!=''){
                       $ConjOp =  strtoupper($fieldinfo[$f]['ConjOp']);
                        $Operator=$ConjOp;
                    }
                    $queryGenerator->addCondition($fieldinfo[$f]['fieldname'], $fieldinfo[$f]['value'], $fieldinfo[$f]['operator'],$Operator);
                }
            }
        }  
        /*if(!empty($entityOwner)){
                $queryGenerator->addReferenceModuleFieldCondition('Users', 'assigned_user_id', 'email1', '@', 'c');
            }*/
        $query = $queryGenerator->getQuery();
    } else if(!empty($CachedModules)){
        $selectedfields = array();
        foreach ($CachedModules as $RelatedField => $RelatedModules) {   
        
            $field_query = $adb->pquery("select columnname,uitype,fieldname from vtiger_field where fieldid=?", array($RelatedField));
            $uitype = $adb->query_result($field_query, 0, 'uitype');
            $fieldname = $adb->query_result($field_query, 0, 'fieldname');
            $removeFlds[]=$fieldname;
            $RelatedFieldName = $adb->query_result($field_query, 0, 'columnname');
            $BaseModule = $RelatedModules[0]['module'];

            for ($m = 0; $m < sizeof($RelatedModules); $m++) {
                foreach ($fields[$RelatedModules[$m]['module']] as $k => $value)
                    if (!in_array($value, $selectedfields))
                        $selectedfields[] = $value;
                foreach ($fields[$RelatedModules[$m]['related_module']] as $k => $value)
                    if (!in_array($value, $selectedfields))
                        $selectedfields[] = $RelatedModules[$m]['related_module'] . '.' . $value;

                $RelatedModuleInfo = getEntityField($RelatedModules[$m]['related_module']);
 //if(array_key_exists($RelatedField,$unique)){
                if ($uitype != 10) {
                    if ($fieldname != $RelatedFieldName)
                        $RelatedFieldName = $fieldname;
                    else
                        $RelatedFieldName = $RelatedModuleInfo['entityid'];
                    $selectedfields[] = $RelatedFieldName;
                    $fields[$RelatedModules[$m]['module']][] = $RelatedFieldName;
                    $selectedfields[] = $RelatedModules[$m]['related_module'] . '.' . $RelatedModuleInfo['fieldname'];
                    $fields[$RelatedModules[$m]['related_module']][] = $RelatedModuleInfo['fieldname'];
                     VTCacheUtils::setRelatedFieldName($RelatedModules[$m]['related_module'] . '.' . $RelatedFieldName);
                }else {  
                    $selectedfields[] = $RelatedModules[$m]['related_module'] . '.' . $RelatedModuleInfo['fieldname'];
                    $fields[$RelatedModules[$m]['related_module']][] = $RelatedModuleInfo['fieldname'];

                    VTCacheUtils::setRelatedFieldName($RelatedModules[$m]['related_module'] . '.' . $RelatedFieldName);
                }
            
                $RelatedModule[] = array('related_module' => $RelatedModules[$m]['related_module'],
                    'fieldname' => $RelatedModuleInfo['fieldname'],
                    'related_fieldname' => $RelatedFieldName,
                    'related_fieldid'=>$RelatedField,
                    'module' => $RelatedModules[$m]['module']);
               // } else {
                 //   $WhereConditions[$RelatedModules[$m]['module']] = array($RelatedField => array(array('fieldname' => $RelatedFieldName,
                     //           'fieldid' => $RelatedField, 'value' => '', 'operator' => 'n', 'ConjOp' => '', 'tablename' => '')));
                ///}
            }
        } 
        foreach($removeFlds as $keyr=>$removefld){
             array_splice($selectedfields, array_search($removefld, $selectedfields),1);
        }
        $queryGenerator = new QueryGenerator($BaseModule, $current_user);
        if (in_array('smownerid', $selectedfields)) {
            $selectedfields[array_search('smownerid', $selectedfields)]='assigned_user_id';
            $entityOwner[] = $BaseModule;
        }

        $queryGenerator->setFields($selectedfields);
 /*if(!empty($entityOwner)){
     $queryGenerator->addReferenceModuleFieldCondition('Users', 'assigned_user_id', 'id', '', 'n');
 }*/
        if (!empty($RelatedModule))
            $queryGenerator->startGroup();

        $conditions = array();
$log->Debug('dioni ');$log->DEbug($RelatedModule);
        for ($r = 0; $r < sizeof($RelatedModule); $r++) {
           // if (!empty($WhereConditions))
            //    $conditions = $WhereConditions[$RelatedModule[$r]['module']][$RelatedModule[$r]['related_fieldid']];

            if ($r > 0)
                $queryGenerator->startGroup('or');

            if (!empty($conditions)) {
                $queryGenerator->startGroup();

                for ($cond = 0; $cond < sizeof($conditions); $cond++) {
                    if ($cond > 0) {
                        if ($conditions[$cond]['ConjOp'] != '') {
                            $ConjOp = strtoupper($conditions[$cond]['ConjOp']);
                            $Operator = $ConjOp;
                        } else {
                            $Operator = 'AND';
                        }
                        $queryGenerator->startGroup($Operator);
                    }
                   if($cond>0)
                   $queryGenerator->addCondition( $RelatedModule[$r]['related_fieldname'], $conditions[$cond]['value'], $conditions[$cond]['operator']);
                    else
                    $queryGenerator->addReferenceModuleFieldCondition($RelatedModule[$r]['related_module'], $RelatedModule[$r]['related_fieldname'], $RelatedModule[$r]['fieldname'], $conditions[$cond]['value'], $conditions[$cond]['operator']);
                    if ($cond > 0)
                        $queryGenerator->endGroup();
                }
                
                $queryGenerator->endGroup();
            }
            else  {
                if (stristr($RelatedModule[$r]['fieldname'], 'concat') != '') {
                    $twofields = str_replace(array('concat', "'", "(", ")"), '', $RelatedModule[$r]['fieldname']);
                    $field_arr = explode(", ,", $twofields);
                    $queryGenerator->addReferenceModuleFieldCondition($RelatedModule[$r]['related_module'], $RelatedModule[$r]['related_fieldname'], $field_arr[0], '', 'n');
                }
                else
                    $queryGenerator->addReferenceModuleFieldCondition($RelatedModule[$r]['related_module'], $RelatedModule[$r]['related_fieldname'], $RelatedModule[$r]['fieldname'], '', 'n');
            }
            if ($r > 0)
                $queryGenerator->endGroup();
            
             unset($WhereConditions[$RelatedModule[$r]['module']][$RelatedModule[$r]['related_fieldid']]);
            //if (!in_array($RelatedField, $RelatedModuleFieldname))
               // $RelatedModuleFieldname[] = $RelatedField;
        }
       if($linkType=='All')
           $Operator=$queryGenerator::$AND;
        else
        if($linkType=='Any')
           $Operator=$queryGenerator::$OR;

        if (!empty($RelatedModule))
            $queryGenerator->endGroup();
    $log->debug('where cond');$log->Debug($WhereConditions);
    foreach($queryGenerator->referenceModuleField as $idindex=>$relmodinfo){
         $refModules[]=$relmodinfo['relatedModule'];
    }    
   // VTCacheUtils::updateRefenceModules($refModules);
        foreach ($WhereConditions as $module => $fields_list) {
          foreach ($fields_list as $fieldid => $fieldinfo) {
                for ($f = 0; $f < count($fieldinfo); $f++) {
                     if($fieldinfo[$f]['ConjOp']!=''){
                       $ConjOp =  strtoupper($fieldinfo[$f]['ConjOp']);
                        $Operator=$ConjOp;
                    }

                    if ($module == $RelatedModules[0]['module'])
                        $queryGenerator->addCondition($fieldinfo[$f]['fieldname'], $fieldinfo[$f]['value'], $fieldinfo[$f]['operator'], $Operator);
                    else {   
                        for ($rel = 0; $rel < count($RelatedModule); $rel++)
                            if ($module == $RelatedModule[$rel]['related_module']) {
                                if(in_array($module,$refModules) ){
                                  $log->Debug('alda');  $log->debug($fieldinfo);
                                  /*if($module != $RelatedModules[0]['module']){
                                      switch($fieldinfo[$f]['operator']) {
					case 'e': $sqlOperator = "=";
						break;
					case 'n': $sqlOperator = "<>";
						break;
					case 'l': $sqlOperator = "<";
						break;
					case 'g': $sqlOperator = ">";
						break;
					case 'm': $sqlOperator = "<=";
						break;
					case 'h': $sqlOperator = ">=";
						break;
                                        case 'c':$sqlOperator = "like";
                                              break;
					default: $sqlOperator = "=";
                                      }
                                   //   $queryGenerator->addConditionGlue($Operator);
                                      $queryGenerator->addRelatedModuleCondition($module, $fieldinfo[$f]['fieldname'], $fieldinfo[$f]['value'],$sqlOperator);
                                    } else {*/
                                        $queryGenerator->addCondition($fieldinfo[$f]['fieldname'], $fieldinfo[$f]['value'], $fieldinfo[$f]['operator'], $Operator);
                                   // }
                                }
                                else
                                $queryGenerator->addReferenceModuleFieldCondition($module, $RelatedModule[$rel]['related_fieldname'], $fieldinfo[$f]['fieldname'], $fieldinfo[$f]['value'], $fieldinfo[$f]['operator'], $Operator);
                            }
                    }
                }
                }
        }
             
        $query = $queryGenerator->getQuery();
        
      
    }else return ;

    if ($query != '') {
        return array('query' => $query, 'selected_fields' => $fields,'remove_fields'=>$removeFlds);
    }
    else
        return 'ERROR';
}


$action = $_REQUEST['query_action'];
$log->debug('model select ' );

if ($action == 'getModel') {

    $modules = array();
    $fields = array();
    $explode_elements = array();
    $related_modules = array();
    $selected_elements = json_decode(vtlib_purify($_POST['selected_elements']));

    if (empty($selected_elements)) {
        $result = $adb->query('select entity.tabid,entity.modulename,entity.tablename from vtiger_entityname entity join vtiger_tab tab on tab.tabid=entity.tabid where tab.presence in (0,2) ');

        while ($row = $adb->fetch_array($result)) {
            $attributes = array();
            $sql = "select field.fieldid,field.columnname,field.tablename,field.typeofdata,SUBSTRING( field.typeofdata, 1, 1 ) as type,field.maximumlength,field.uitype,tab.name 
                from vtiger_field field join vtiger_tab tab on tab.tabid=field.tabid  where field.tabid='" . $row['tabid'] . "' and (field.tablename='" . $row['tablename'] . "' or  field.fieldname in('createdtime','modifiedtime','assigned_user_id')) and field.displaytype in (0,1,2) ";

            $attributes = createModel($sql);


            $model[] = array('name' => $row['modulename'],
                'caption' => $row['modulename'],
                'UIC' => 'true',
                'UIS' => 'true',
                'UIR' => 'true',
                'attributes' => $attributes,
                'description' => ''
            );
        }
    } else {
        for ($s = 0; $s < count($selected_elements); $s++) {
            $explode_elements = explode(' ', $selected_elements[$s]);
            if (!in_array($explode_elements[0], $modules))
                $modules[] = $explode_elements[0];

            $fields[] = $explode_elements[1];
        }
        $related_modules = find_related_modules($modules, $action, '', $fields);
        if (empty($related_modules)) {
            $related_modules = $modules;
        }
        $result = $adb->pquery('select entity.tabid,entity.modulename,entity.tablename from vtiger_entityname entity join vtiger_tab tab on tab.tabid=entity.tabid where tab.presence in (0,2) and entity.modulename in (' . generateQuestionMarks($related_modules) . ') ', array($related_modules));

        while ($row = $adb->fetch_array($result)) {
            $attributes = array();
            $sql = "select field.fieldid,field.columnname,field.tablename,field.typeofdata,SUBSTRING( field.typeofdata, 1, 1 ) as type,field.maximumlength,field.uitype,tab.name 
                from vtiger_field field join vtiger_tab tab on tab.tabid=field.tabid  where field.tabid='" . $row['tabid'] . "' and (field.tablename='" . $row['tablename'] . "'  or field.fieldname in('createdtime','modifiedtime','assigned_user_id')) and  field.displaytype in (0,1,2) ";

            $attributes = createModel($sql);

            $model[] = array('name' => $row['modulename'],
                'caption' => $row['modulename'],
                'UIC' => 'true',
                'UIS' => 'true',
                'UIR' => 'true',
                'attributes' => $attributes,
                'description' => ''
            );
        }
    }

    $model1 = array("operators" => array(array("id" => "Equal", "caption" => "", "displayFormat" => "{expr1} [[is equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "NotEqual", "caption" => "is not equal to", "displayFormat" => "{expr1} [[is not equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "LessThan", "caption" => "is less than", "displayFormat" => "{expr1} [[is less than]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "LessOrEqual", "caption" => "is less than or equal to", "displayFormat" => "{expr1} [[is less than or equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "GreaterThan", "caption" => "is greater than", "displayFormat" => "{expr1} [[is greater than]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "GreaterOrEqual", "caption" => "is greater than or equal to", "displayFormat" => "{expr1} [[is greater than or equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "IsNull", "caption" => "is null", "displayFormat" => "{expr1} [[is null]]", "paramCount" => 1, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "NotNull", "caption" => "is not null", "displayFormat" => "{expr1} [[is not null]] ", "paramCount" => 1, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "InList", "caption" => "is in list", "displayFormat" => "{expr1} [[is in list]] {expr2}", "paramCount" => 2, "valueKind" => "List", "exprType" => "Unknown"),
            array("id" => "NotInList", "caption" => "is not in list", "displayFormat" => "{expr1} [[is not in list]] {expr2}", "paramCount" => 2, "valueKind" => "List", "exprType" => "Unknown"),
            array("id" => "StartsWith", "caption" => "starts with", "displayFormat" => "{expr1} [[starts with]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "Text value editor", "restype" => "String", "type" => "EDIT", "value" => array("text" => ""))),
            array("id" => "NotStartsWith", "caption" => "does not start with", "displayFormat" => "{expr1} [[does not start with]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "Text value editor", "restype" => "String", "type" => "EDIT", "value" => array("text" => ""))),
            array("id" => "Contains", "caption" => "contains", "displayFormat" => "{expr1}  [[contains]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "Text value editor", "restype" => "String", "type" => "EDIT", "value" => array("text" => ""))),
            array("id" => "NotContains", "caption" => "does not contain", "displayFormat" => "{expr1} [[does not contain]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "Text value editor", "restype" => "String", "type" => "EDIT", "value" => array("text" => ""))),
            array("id" => "Between", "caption" => "is between", "displayFormat" => "{expr1} [[is between]] {expr2} and {expr3}", "paramCount" => 3, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "NotBetween", "caption" => "is not between", "displayFormat" => "{expr1} [[is not between]] {expr2} and {expr3}", "paramCount" => 3, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "InSubQuery", "caption" => "in sub query", "displayFormat" => "{expr1} [[in sub query]] {expr2}", "paramCount" => 2, "valueKind" => "Query", "exprType" => "Byte"),
            array("id" => "DateEqualSpecial", "caption" => "is (special date)", "displayFormat" => "{expr1} [[is equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecDateValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "DateEqualPrecise", "caption" => "is (precise date)", "displayFormat" => "{expr1} [[is equal to]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "DateNotEqualSpecial", "caption" => "is not", "displayFormat" => "{expr1} [[is not]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecDateValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "DateNotEqualPrecise", "caption" => "is not", "displayFormat" => "{expr1} [[is not]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "DateBeforeSpecial", "caption" => "is before (special date)", "displayFormat" => "{expr1} [[is before]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecDateValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "DateBeforePrecise", "caption" => "is before (precise date)", "displayFormat" => "{expr1} [[is before]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "DateAfterSpecial", "caption" => "is after (special date)", "displayFormat" => "{expr1} [[is after]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecDateValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "DateAfterPrecise", "caption" => "is after (precise date)", "displayFormat" => "{expr1} [[is after]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "DatePeriodPrecise", "caption" => "is between", "displayFormat" => "{expr1} [[is between]] {expr2} and {expr3}", "paramCount" => 3, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "TimeBeforeSpecial", "caption" => "is before (special time)", "displayFormat" => "{expr1} [[is before]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecTimeValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "TimeBeforePrecise", "caption" => "is before (precise time)", "displayFormat" => "{expr1} [[is before]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "TimeAfterSpecial", "caption" => "is after (special time)", "displayFormat" => "{expr1} [[is after]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "CustomList value editor", "name" => "SpecTimeValues", "restype" => "Unknown", "type" => "CUSTOMLIST")),
            array("id" => "TimeAfterPrecise", "caption" => "is after (precise time)", "displayFormat" => "{expr1} [[is after]] {expr2}", "paramCount" => 2, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "TimePeriodPrecise", "caption" => "is between", "displayFormat" => "{expr1} [[is between]] {expr2} and {expr3}", "paramCount" => 3, "valueKind" => "Scalar", "exprType" => "Unknown", "defaultEditor" => array("id" => "DateTime value editor", "restype" => "Unknown", "type" => "DATETIME")),
            array("id" => "MaximumOfAttr", "caption" => "is maximum of", "displayFormat" => "{expr1} [[is maximum of]] {expr2}", "paramCount" => 2, "valueKind" => "Attribute", "exprType" => "Unknown"),
            array("id" => "GreaterThanAvg", "caption" => "greater than average", "displayFormat" => "{expr1} [[is greater than average of]] {expr2}", "paramCount" => 2, "valueKind" => "Attribute", "exprType" => "Unknown"),
            array("id" => "IsNotNull", "caption" => "is not null", "displayFormat" => "{expr1} [[is not null]]", "paramCount" => 1, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "IsTrue", "caption" => "is true", "displayFormat" => "{expr1} [[is true]]", "paramCount" => 1, "valueKind" => "Scalar", "exprType" => "Unknown"),
            array("id" => "NotTrue", "caption" => "is not true", "displayFormat" => "{expr1} [[is not true]]", "paramCount" => 1, "valueKind" => "Scalar", "exprType" => "Unknown")),
        'rootEntity' => array('name' => '', 'caption' => '', 'UIC' => true, 'UIS' => true, 'UIR' => true, 'subEntities' => $model),
        "aggrFunctions" => array("id" => "SUM", "caption" => "Sum", "displayFormat" => "[[Sum]] of {attr1}"),
        array("id" => "COUNT", "caption" => "Count", "displayFormat" => "[[Count]] of {attr1}"),
        array("id" => "AVG", "caption" => "Average", "displayFormat" => "[[Average]] of {attr1}"),
        array("id" => "MIN", "caption" => "Minimum", "displayFormat" => "[[Minimum]] of {attr1}"),
        array("id" => "MAX", "caption" => "Maximum", "displayFormat" => "[[Maximum]] of {attr1}")
    );
    
    echo json_encode($model1);
} else if ($action == 'loadQuery') {
    global $adb;
    $query_name = $_POST['queryName'];

    $getMaps = $adb->pquery("Select mapname,vtiger_crmentity.description as query,cbmapid from vtiger_cbmap join vtiger_crmentity on crmid=cbmapid where deleted=0 and maptype='SQL' ", array());
    for ($q = 0; $q < $adb->num_rows($getMaps); $q++) {
        $description=explode(':::',$adb->query_result($getMaps, $q, 'query'));
        
        
        $query_json[$adb->query_result($getMaps, $q, 'mapid')] = array('MapName' => $adb->query_result($getMaps, $q, 'mapname'),
            'queryJson' =>json_decode($description[0]),
            'selectedOperators'=>json_decode($description[1]));
    }

    echo json_encode($query_json);
} else if ($action == 'saveQuery') {

    $query_name = $_POST['queryName'];

    $query_json = $_POST['queryJson'];
    $query_SQL = $_POST['querySQL'];
    $selected_fields = $_POST['selected_fields'];
    $selectedOperators = $_POST['selectedOperators'];

    $mapName = $_POST['mapName'];
    $map_focus = new cbMap();
    $map_focus->column_fields['mapname'] = $mapName;
    $map_focus->column_fields['description'] = $query_json.':::'.$selectedOperators; //' SQl created from queryBuilder';
    $map_focus->column_fields['maptype'] = 'SQL';
    $map_focus->column_fields['content'] = $query_SQL;
    $map_focus->column_fields['selected_fields'] = $selected_fields;
    $map_focus->column_fields['assigned_user_id'] = 1;
    $map_focus->save("cbMap");
    // $query_file_name = $query_name . ".json";    
    //file_put_contents($query_file_name, $query_json);

    echo '{"result"=>"OK"}';
} else if ($action == 'updateQuery') {
    
    $query_json = $_POST['queryJson'];
    $query_SQL = $_POST['querySQL'];
    $selected_fields = $_POST['selected_fields'];
    $selectedOperators = $_POST['selectedOperators'];
    $mapid=$_POST['mapid'];
    
    $focus=CRMEntity::getInstance('cbMap');
    $focus->retrieve_entity_info($mapid,'cbMap');

    $focus->column_fields['description'] = $query_json.':::'.$selectedOperators; //' SQl created from queryBuilder';
    $focus->column_fields['content'] = $query_SQL;
    $focus->column_fields['selected_fields'] = $selected_fields;
    $focus->mode = 'edit';
    $focus->id = $mapid;
    $focus->save("cbMap");
    echo '{"result"=>"OK"}';
    
} else if ($action == 'buildQuery') {

    $queryJson = $_POST['queryJson'];
    $sql = buildSql($queryJson);

    $result = json_encode(array('statement' => $sql));
    echo $result;
}
else if ($action == 'syncQuery') {
    $queryJson = json_decode(vtlib_purify($_POST['queryJson']));
    $modules=array();
    $WhereConditions=array();
   /* $operators=array( 
        'StartsWith' => "like '" . generateQuestionMarks($value) . "%'",
        'Contains'=>" like '%" . generateQuestionMarks($value) . "%'",
        'NotStartsWith'=> " not like '" . generateQuestionMarks($value) . "%' ",
        'NotContains'=>"not like '%" . generateQuestionMarks($value) . "%' ",
        'InList'=>"  IN (" . generateQuestionMarks($value) . ")",
        'Equal'=>" = ". generateQuestionMarks($value) ." ", 
        'NotEqual'=>" <> ". generateQuestionMarks($value) . " ",
        'IsNull'=> " IS NULL ",
        'InSubQuery'=> '' ,  
        'DateEqualSpecial'=>" = ". generateQuestionMarks($value) ." ", 
        'DateEqualPrecise'=>" = ". generateQuestionMarks($value) ." ", 
        'DateNotEqualSpecial'=>" <> ". generateQuestionMarks($value) ." ", 
        'DateNotEqualPrecise'=>" = ". generateQuestionMarks($value) ." ", 
        'DateBeforeSpecial'=>" < ". generateQuestionMarks($value) ." ", 
        'DateBeforePrecise'=>" < ". generateQuestionMarks($value) ." ", 
        'DateAfterSpecial'=>" > ". generateQuestionMarks($value) ." ",
        'DateAfterPrecise'=>" > ". generateQuestionMarks($value) ." ",
        'DatePeriodPrecise'=>" > ". generateQuestionMarks($value) ."   ",
        'MaximumOfAttr'=>'',
        'Between'=>" = ". generateQuestionMarks($value) ."   ",
        'LessThan'=>" <". generateQuestionMarks($value) ."   ",
        'LessOrEqual'=>" =<". generateQuestionMarks($value) ."   ",
        'GreaterThan'=>" > " . generateQuestionMarks($value) ."   ",
        'GreaterOrEqual'=>" > " . generateQuestionMarks($value) ."   ",
        'NotBetween'=>'', 
        'NotEqual'=>" <> ". generateQuestionMarks($value) ." ", 
        'IsTrue'=>" = 1", 
        'NotTrue'=>'= 0');*/
    
        $operators=array( 
        'StartsWith' => "s",
        'Contains'=>"c",
        'NotStartsWith'=> "k",
        'NotContains'=>"k",
        'InList'=>"IN",
        'Equal'=>"e", 
        'NotEqual'=>"n",
        'IsNull'=> "e",
        'IsNotNull'=>"n",
        'InSubQuery'=> '' ,  
        'DateEqualSpecial'=>"e", 
        'DateEqualPrecise'=>"e", 
        'DateNotEqualSpecial'=>"n", 
        'DateNotEqualPrecise'=>"e", 
        'DateBeforeSpecial'=>"l", 
        'DateBeforePrecise'=>"m", 
        'DateAfterSpecial'=>"g",
        'DateAfterPrecise'=>"h",
        'DatePeriodPrecise'=>"bw",
        'MaximumOfAttr'=>'',
        'Between'=>"bw",
        'LessThan'=>"l",
        'LessOrEqual'=>"m",
        'GreaterThan'=>"g",
        'GreaterOrEqual'=>"h",
        'NotBetween'=>'not in', 
        'IsTrue'=>"y", 
        'NotTrue'=>'ny');
    
    
    $columns = $queryJson->columns;

    for ($c = 0; $c < count($columns); $c++) {
        $selected_elements[] = $columns[$c]->caption;
        $selected_ids[]=$columns[$c]->expr->id;
    }
    $reference_query=$adb->query("SELECT uitype FROM  vtiger_ws_fieldtype WHERE  fieldtype='reference' and uitype<>10 ");
    $reference=array();
    for($ref=0;$ref<$adb->num_rows($reference_query);$ref++){
        $reference[]=$adb->query_result($reference_query,$ref,'uitype');
    }
    

    for ($s = 0; $s < count($selected_elements); $s++) {
        $explode_elements = explode(' ', $selected_elements[$s]);
       if($s==0)
          $fields[$explode_elements[0]][]='id';
        if (!in_array($explode_elements[0], $modules))
            $modules[] = $explode_elements[0];
        $id=$selected_ids[$s];
        $fieldid_query=$adb->pquery("select fieldname,uitype from vtiger_field where fieldid=?",array($id));
        $fieldname=$adb->query_result($fieldid_query,0,'fieldname');
        $uitype=$adb->query_result($fieldid_query,0,'uitype');
        if($uitype==10){
        $entityname_query=$adb->pquery("SELECT relmodule,fieldname FROM  vtiger_fieldmodulerel join vtiger_entityname on modulename=relmodule where fieldid=? and module=? ",array($id,$explode_elements[0]));
        for($e=0;$e<$adb->num_rows($entityname_query);$e++){
            
                $relmod=$adb->query_result($entityname_query,$e,'relmodule');
                if(!in_array($relmod,$modules))
                     $modules[]=$relmod;
                $uitype10_fields[]=$id;
             }  
            
        }else if(in_array($uitype,$reference)){
            $other_uitype[$id] = array('fieldname' => $fieldname,
                'fieldid' => $id,
                'uitype' =>$uitype,
                'module'=>$explode_elements[0]);
        }

        $fields[$explode_elements[0]][] = $fieldname;//$explode_elements[1];
    }
    
    
        
    $conditions=$queryJson->root->conditions;
    $linkType=$queryJson->root->linkType;
    
    for ($cond = 0; $cond < count($conditions); $cond++) {

        $fieldid = $conditions[$cond]->expressions[0]->id;
        $fieldid_query = $adb->pquery("select fieldname,uitype,tablename from vtiger_field where fieldid=?", array($fieldid));
        $fieldname = $adb->query_result($fieldid_query, 0, 'fieldname');
        $tablename = $adb->query_result($fieldid_query, 0, 'tablename');
        $uitype = $adb->query_result($fieldid_query, 0, 'uitype');
        if ($uitype == 10) {
            $entityname_query = $adb->pquery("SELECT relmodule,fieldname FROM  vtiger_fieldmodulerel join vtiger_entityname on modulename=relmodule where fieldid=? and module=? ", array($fieldid, $conditions[$cond]->expressions[0]->module));
            $uitype10_fields[] = $fieldid;
        } else if (in_array($uitype, $reference)) {
            $other_uitype[$fieldid] = array('fieldname' => $fieldname,
                'fieldid' => $fieldid,
                'uitype' => $uitype,
                'module' => $conditions[$cond]->expressions[0]->module);
        }
        $opId = $operators[$conditions[$cond]->operatorID];
        if($opId=='bw')
            $conval=array( $conditions[$cond]->expressions[1]->value, $conditions[$cond]->expressions[2]->value);
        else
            $conval=$conditions[$cond]->expressions[1]->value;
        $WhereConditions[$conditions[$cond]->expressions[0]->module][$fieldid][] = array('fieldname' => $fieldname,
            'fieldid' => $fieldid,
            'value' => $conval,
            'operator' => $opId,
            'ConjOp' => $conditions[$cond]->expressions[0]->ConjOp,
            'tablename' => $tablename);

        if (!in_array($conditions[$cond]->expressions[0]->module, $modules))
            $modules[] = $conditions[$cond]->expressions[0]->module;
    }

    $related_modules = find_related_modules($modules, $action, $uitype10_fields, $other_uitype);

    getSelectFieldsSQL($fields);
    getWhereConditionsSQL($WhereConditions);
    $fieldsInfo = array();
    $array = buildSql($related_modules,$linkType);
    $log->debug($array);
    if ($array != 'ERROR') {
        $sql = $array['query'];
        $remove_fields=$array['remove_fields'];
        foreach ($array['selected_fields'] as $module => $modflds) {
            $modQuery = $adb->pquery("SELECT tablename,entityidfield FROM  vtiger_entityname where modulename=? ", array($module));
            for ($f = 0; $f < count($modflds); $f++)
             if(!in_array($modflds[$f],$remove_fields))
                if($modflds[$f]=='id')
                 $fieldsInfo[] = $adb->query_result($modQuery, 0, 'tablename') . '.' .$adb->query_result($modQuery, 0, 'entityidfield');
                else if($modflds[$f]=='createdtime' || $modflds[$f]=='modifiedtime' || $modflds[$f]=='assigned_user_id')
                $fieldsInfo[] =  'vtiger_crmentity.' . $modflds[$f];
                     else
                $fieldsInfo[] = $adb->query_result($modQuery, 0, 'tablename') . '.' . $modflds[$f];
        }


        $result = json_encode(array('statement' => $sql, 'selected_fields' => implode(',', $fieldsInfo)));
        echo $result;
    }
    else
        echo $array;
}else if ($action == 'executeQuery') {

    $SqlQuery = str_replace('"', '', ($_POST['SqlQuery']));
    $recordSet = $adb->pquery($SqlQuery . ' limit 200 ', array());
    $result = '{}';
    if ($recordSet) {
        $resultSet = renderDataTable($recordSet);
        $ret = array('statement' => $SqlQuery, 'resultSet' => $resultSet);

        $result = json_encode($ret);
    } else {
        $ret['statement'] = "DATABASE CONNECTION ERROR!!!";
        $result = json_encode($ret);
    }

    echo $result;
} else if ($action == 'listRequest') {
    //here  we need to assemble the requested list based on its name and return it as JSON array
    //each item in that array is an object with two properties: "id" and "text"
    //get the name of requested list
    $list_name = $_POST['listName'];


    if ($list_name == "SQL") {
        //if this is a SQL list request - we need to execute SQL statement and return the result set as a list of of {id, text} items

        if ($recordSet = executeSql($_POST['sql'])) {
            $result = renderRequestedList($recordSet);
            echo $result;
        }
        //$sql =  $_POST['sql'];
        //echo '[array("id"=>"SQL1","text"=>"SQL List Text 1"}',' {"id:"SQL2","text"=>"SQL List Text 1"}',' {"id:"SQL3","text"=>"SQL List Text 3"}',' {"id:"SQL4","text"=>"SQL List Text 4"}]';
    } else {
        //otherwise we return some list based on list name
        if ($list_name == "RegionsList") {
            echo '[array("id"=>"11","text"=>"AAAA"}', ' {"id:"22","text"=>"BBBB"}', ' {"id:"33","text"=>"CCCC"}', ' {"id:"44","text"=>"DDDD"}]';
        } else {
            echo '[]';
        }
    }
}
else
    echo '{"result": "OK"}';
?>
