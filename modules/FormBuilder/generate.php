<?php

global $log, $adb;
$result=array();

$models=file_get_contents('php://input');
$mv=json_decode($models);
$kaction=$_REQUEST['kaction'];
  

$moduleName = preg_replace('/[^a-z0-9]/', '',strtolower($mv->name));
$parentName = $mv->parenttab;
$extensionType = 'FormExtension';
$baseFolder = "modules/FormBuilder/structure/$extensionType/DashboardStructure";
if (!file_exists("modules/$moduleName/$moduleName.php")) {
    
    InsertFormExt($mv); 
    
    shell_exec("mkdir modules/$moduleName");
    shell_exec("cp -r $baseFolder/*  modules/$moduleName");
    shell_exec('mv  modules/' . $moduleName . '/DashboardStructure.php modules/' . $moduleName . '/' . $moduleName . '.php');
    shell_exec('mv  modules/' . $moduleName . '/DashboardStructureAjax.php modules/' . $moduleName . '/' . $moduleName . 'Ajax.php');
    shell_exec('mv  modules/' . $moduleName . '/DashboardStructure.js modules/' . $moduleName . '/' . $moduleName . '.js');
    shell_exec('mv  modules/' . $moduleName . '/addNewExtensionDashboardStructure.php modules/' . $moduleName . '/addNewExtension' . $moduleName . '.php');
    shell_exec("chmod 777 -R modules/$moduleName/");    

    $str = file_get_contents("modules/$moduleName/$moduleName.php");
    $str = str_replace("DashboardStructure", "$moduleName", $str);
    file_put_contents("modules/$moduleName/$moduleName.php", $str);
    $str = file_get_contents("modules/$moduleName/$moduleName.js");
    $str = str_replace("DashboardStructure", "$moduleName", $str);
    file_put_contents("modules/$moduleName/$moduleName.js", $str);
    $str = file_get_contents("modules/$moduleName/addNewExtension$moduleName.php");
    $str = str_replace("DASHBOARD_NAME", "$moduleName", $str);
    $str = str_replace("PARENT_NAME", "$parentName", $str);
    file_put_contents("modules/$moduleName/addNewExtension$moduleName.php", $str);
    shell_exec("cp modules/$moduleName/addNewExtension$moduleName.php  addNewExtension$moduleName.php");

    $output=shell_exec("mkdir Smarty/templates/modules/$moduleName 2>&1");
    shell_exec("cp -r modules/FormBuilder/structure/$extensionType/template/DashboardStructure/*  Smarty/templates/modules/$moduleName");

    shell_exec("php addNewExtension$moduleName.php");
    $result['message']="The Form Extension is generated successfully";
} 
else {
    UpdateFormExt($mv);
    $result['message']="The Form Extension is updated successfully";
}
if(empty($result['message'])){
    $result['message']="An error occurred processing your request";
}
echo json_encode($result['message'],true);

function InsertFormExt($mv){       
    global $adb;
    $default=($mv->default_form=='true' ? 1 : 0);
    $name=preg_replace('/[^a-z0-9]/', '',strtolower($mv->name));
    $adb->pquery("INSERT INTO dashboardbuilder_extensions(name,label,parenttab,type,generated_form,default_form,onopen) "
            . " VALUES(?,?,?,?,?,?,?)", 
            array( $name, $mv->name,$mv->parenttab, $mv->type,1,$default,$mv->onopen));
    $last_id=$adb->query_result($adb->query("select id from dashboardbuilder_extensions order by id desc"),0,'id');
    
    if($default){
        $adb->pquery("Update dashboardbuilder_extensions"
                . " set default_form=0"
                . " where name <> ?", 
                array($mv->name));
    }
    
    if($mv->type=='TypeForm' || $mv->type=='Kibi'){ 
        $adb->pquery("INSERT INTO dashboardbuilder_entities(name,entity,entityname,index_type) "
            . " VALUES(?,?,?,?)", 
            array($name,$mv->type, $mv->elastic_name,'denorm'));
    }
    else{
        for($i=0;$i<sizeof($mv->modules);$i++){
            $parent='';
            if($mv->modules[$i]->parent) $parent=$mv->modules[$i]->parent;
            $pointingfield='';
            if($mv->modules[$i]->pointingfield) $pointingfield=$mv->modules[$i]->pointingfield;
            $adb->pquery("INSERT INTO dashboardbuilder_entities(name,entity,entityname,parentname,"
                    . "level,pointing_field,savedid) "
                . " VALUES(?,?,?,?,?,?,?)", 
                array( $name,$mv->type,getTabModuleName($mv->modules[$i]->moduleid),$parent,
                    $mv->modules[$i]->level,$pointingfield,$mv->modules[$i]->savedid));
        }
    }
}

function UpdateFormExt($mv){       
    global $adb;
    $default=($mv->default_form=='true' ? 1 : 0);
    $name=preg_replace('/[^a-z0-9]/', '',strtolower($mv->name));
    $adb->pquery("Update dashboardbuilder_extensions"
            . " set type =?,"
            . " default_form=?,"
            . " last_modifiedtime=?,"
            . " onopen=?,"
            . " onsave=?"
            . " where name=?", 
            array( $mv->type,$default,date('Y-m-d H:m:i'),$mv->onopen,$mv->onsave, $mv->name));
    if($default){
        $adb->pquery("Update dashboardbuilder_extensions"
                . " set default_form=0"
                . " where name <> ?", 
                array($mv->name));
    }
    if($mv->type=='TypeForm'){ 
        if(empty($mv->modules[0]->dsid)){
            $adb->pquery("INSERT INTO dashboardbuilder_entities(name,entity,entityname,index_type) "
                . " VALUES(?,?,?,?)", 
                array($name,$mv->type, $mv->modules[0]->elastic_name,'denorm'));            }
        else{
            $adb->pquery("Update dashboardbuilder_entities"
                    . " set entity=?,"
                    . " entityname=?,"
                    . " index_type=? "
                . " where dsid=?", 
                array($mv->type, $mv->modules[0]->elastic_name,'denorm',$mv->modules[0]->dsid));
        }
    }
    elseif($mv->type=='Kibi'){ 
            $adb->pquery("Update dashboardbuilder_entities"
                    . " set entity=?,"
                    . " entityname=?,"
                    . " index_type=? "
                . " where dsid=?", 
                array($mv->type, $mv->elastic_name,'denorm',$mv->modules[0]->dsid));
    }
    else{
        for($i=0;$i<sizeof($mv->modules);$i++){
            $parent='';
            if($mv->modules[$i]->parent) $parent=$mv->modules[$i]->parent;
            $pointingfield='';
            if($mv->modules[$i]->pointingfield) $pointingfield=$mv->modules[$i]->pointingfield;
                $res=$adb->pquery("Update dashboardbuilder_entities"
                        . " set entity=?,"
                        . " entityname=?,"
                        . " parentname=?,"
                        . " level=?,"
                        . " pointing_field=?,"
                        . " savedid=? "
                    . " where dsid=?", 
                    array( $mv->type,getTabModuleName($mv->modules[$i]->moduleid),$parent,
                        $mv->modules[$i]->level,$pointingfield,$mv->modules[$i]->savedid,
                        $mv->modules[$i]->dsid));

                if(empty($mv->modules[$i]->dsid)){
                    $adb->pquery("INSERT INTO dashboardbuilder_entities(name,entity,entityname,parentname,"
                            . "level,pointing_field,savedid) "
                    . " VALUES(?,?,?,?,?,?,?)", 
                    array( $name,$mv->type,getTabModuleName($mv->modules[$i]->moduleid),$parent,
                        $mv->modules[$i]->level,$pointingfield,$mv->modules[$i]->savedid));

                }
            }
        }
    }
?>