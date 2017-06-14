<?php

global $adb, $log,$currentModule;
$kaction = $_REQUEST['kaction'];
$dashboardName = $_REQUEST['dsName'];
$entityName = $_REQUEST['entityName'];
$records = array();

function findRoleid($rolname) {
    global $adb;
    $sql = "SELECT roleid FROM vtiger_role WHERE rolename=?";
    $result = $adb->pquery($sql, array($rolname));
    $roleid = $adb->query_result($result, 0, "roleid");
    return $roleid;
}

function listElastic() {
    $method = "GET";
    $search_host="193.182.16.34";
    $search_port="9200";
    $url = 'http://'.$search_host.':'.$search_port.'/_stats/_indices';
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_PORT, $search_port);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    $result = curl_exec($ch);
    curl_close($ch);
    $ary = json_decode($result,true);
    $content=array();
    $i=0;
    foreach ( $ary["indices"] as $key=>$value){        
        $content[]=$key;
    }
    return $content;
}

//TO DO:
function addNewEntity($dashboardbuilder,$entityname) {
    global $adb;
    $entityQuery = $adb->pquery("INSERT INTO dashboardbuilder_entities(name,entity) VALUES (?,?)", array($entityname,$dashboardbuilder));
    $lastID = $adb->getLastInsertID();
    return $lastID;
}
function findBlockName($blockid) {
    global $adb,$dashboardName;
    $blockquery = $adb->pquery("SELECT block_label FROM dashboardbuilder_blocks dsblock
                                INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsblock.id
                                WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0 AND dsblock.id=?", array($dashboardName,"Blocks",$blockid));
    $blockname = $adb->query_result($blockquery, 0, 'block_label');
    return $blockname;
}
function findBlockId($blockname) {
    global $adb,$dashboardName;
    $blockquery = $adb->pquery("SELECT id FROM dashboardbuilder_blocks dsblock
                               INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsblock.id
                               WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0 AND dsblock.block_label=?", array($dashboardName,"Blocks",$blockname));
    $blockid = $adb->query_result($blockquery, 0, 'id');
    return $blockid;
}
function findBusinessActionName($actionid){
    global $adb;
    $actionQuery = $adb->pquery("SELECT reference FROM  vtiger_actions 
                    INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_actions.actionsid
                    WHERE ce.deleted=0 AND actionsid=? ",array($actionid));
    $actioname=$adb->query_result($actionQuery,0, 'reference');
    return $actioname;
}
/*
 * Utilities for config Form Extension
 */
if($kaction == 'retrieve_module') {
   $sql=$adb->query("SELECT tabid,tablabel FROM vtiger_tab where isentitytype=1");
   $nr=$adb->num_rows($sql);
   for($i=0 ;$i < $nr ;$i++)
   {
       $content[$i]['tabid']=$adb->query_result($sql,$i,'tabid');
       $content[$i]['tablabel']=$adb->query_result($sql,$i,'tablabel');
       $content[$i]['tabtrans']=  getTranslatedString($adb->query_result($sql,$i,'tablabel'));
   }
   echo json_encode($content);
} 
elseif($kaction == 'retrieve_elastic'){
    
    $content=listElastic();
    echo json_encode($content);
}
elseif ($kaction == 'parenttablist') {
    $sql = $adb->query("SELECT * FROM vtiger_parenttab WHERE visible=0 ORDER BY sequence");
    $count = $adb->num_rows($sql);
    $parenttablist = array();
    for ($i = 0; $i < $count; $i++) {
        $parenttablist[] = $adb->query_result($sql, $i, 'parenttab_label');
    }
    echo json_encode($parenttablist);
}
elseif($kaction == 'pointingfields'){
    
    $qu1="Select fieldid,columnname,fieldlabel,fieldname,tabid from vtiger_field";
    $qu1=$qu1." where uitype in (10,1025) ";   
    $query=$adb->query($qu1);
    $count=$adb->num_rows($query);
   for($i=0;$i<$count;$i++){
      $content[$i]['fieldid']=$adb->query_result($query,$i,'fieldid');
      $content[$i]['columnname']=$adb->query_result($query,$i,'columnname');
      $content[$i]['fieldlabel']=  getTranslatedString($adb->query_result($query,$i,'fieldlabel'),$pointing_module);
      $content[$i]['fieldname']=$adb->query_result($query,$i,'fieldname');
      $content[$i]['tabid']=$adb->query_result($query,$i,'tabid');
      }
    echo json_encode($content);    
}
elseif($kaction == 'getfields'){
    
    $kmodules=$_REQUEST['kmodules'];
    $qu1="Select fieldid,columnname,fieldlabel,fieldname,tabid from vtiger_field";
    $qu1=$qu1." where tabid in ($kmodules) "; 
    $query=$adb->query($qu1);
    $count=$adb->num_rows($query);
   for($i=0;$i<$count;$i++){
      $content[$i]['fieldid']=$adb->query_result($query,$i,'fieldid');
      $content[$i]['columnname']=$adb->query_result($query,$i,'columnname');
      $content[$i]['fieldlabel']=  getTranslatedString($adb->query_result($query,$i,'fieldlabel'),$pointing_module);
      $content[$i]['fieldname']=$adb->query_result($query,$i,'fieldname');
      $content[$i]['tabid']=$adb->query_result($query,$i,'tabid');
      }
    echo json_encode($content);    
}
/*
 * CRUD EXTENSION
 */
if ($kaction == 'retrieveextension') {
    $allblocks = $adb->query("SELECT * FROM dashboardbuilder_extensions where type in ('Modules','TypeForm','Kibi')");
    while ($allblocks && $row = $adb->fetch_array($allblocks)) {
        $content['id'] = $row['id'];
        $content['name']=$row['name'];
        $content['label'] = $row['label'];
        $content['type'] = $row['type'];
        $content['parenttab'] = $row['parenttab'];
        $content['default_form'] = $row['default_form']==1 ? true : false;
        $content['default_form_str'] = $row['default_form']==1 ? 'Default' : '';
        $content['onopen'] = $row['onopen'];
        $content['onsave'] = $row['onsave'];
        $records[] = $content;
   }
   echo json_encode($records);
} elseif ($kaction == 'deleteextension') {
    $models = $_REQUEST['models'];
    $model_values = array();
    $model_values = json_decode($models);
    $mv = $model_values[0];
    $id = $mv->id;

    $adb->pquery("DELETE FROM dashboardbuilder_extensions  WHERE id=?", array($id));
    
} elseif ($kaction == 'createextension') { 
    $models=file_get_contents('php://input');
    $mv=json_decode($models);
    $name=preg_replace('/[^a-z0-9]/', '',strtolower($mv->name));
    $res=$adb->pquery("INSERT INTO dashboardbuilder_extensions(name,label,parenttab,type) "
            . " VALUES(?,?,?,?)", 
            array( $name, $mv->name,$mv->parenttab, $mv->type));
} 
/*
 * CRUD BLOCK
 */
elseif ($kaction == 'retrieveblock') {
	
    $allblocks = $adb->pquery("SELECT * from dashboardbuilder_blocks dsblock
                            INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsblock.id
                            WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0",array($dashboardName,$entityName));
    while ($allblocks && $row = $adb->fetch_array($allblocks)) {
        $content['id'] = $row['id'];
        $content['block_label'] = $row['block_label'];
        $content['block_sequence'] = intval($row['block_sequence']);
        $content['block_module'] = $row['block_module']."";
        $content['block_module_label'] = getTabname($row['block_module']);
        $content['doc_widget'] = $row['doc_widget']==1 ? true : false;
        $content['brid'] = $row['brid'];
        $content['brid_display'] = $row['brid'];
//        $content['block_active'] = $row['block_active'] == 1 ? true : false;
//        $blockRole = findRoleNames($row['block_roles']);
//        $content['block_roles'] = $blockRole ? $blockRole : "";
//        $blockUser = findUserNames($row['block_users']);
//        $content['block_users'] = $blockUser ? $blockUser : "";
//        $tabName = findTabName($row['block_tab']);
//        $content['block_tab'] = $tabName ? $tabName : "";
////        $content['block_action'] = findActionName($row['block_action']);		
        $records[] = $content;
    }
    echo json_encode($records);
} 
elseif ($kaction == 'updateblock') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $adb->pquery("UPDATE dashboardbuilder_blocks "
            . " SET block_label=?,block_sequence=?,block_module=?,brid=?,doc_widget=? "
            . " WHERE id=?", 
            array($mv->block_label, $mv->block_sequence, $mv->block_module, $mv->brid, $mv->doc_widget, $mv->id));
} 
elseif ($kaction == 'deleteblock') {
    $models = $_REQUEST['models'];
    $model_values = array();
    $model_values = json_decode($models);
    $mv = $model_values[0];
    $id = $mv->id;
    $adb->pquery("UPDATE dashboardbuilder_entities SET deleted=1 WHERE dsid=?", array($id));
    echo true;
} 
elseif ($kaction == 'createblock') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $newID=addNewEntity("Blocks",$dashboardName);
    $adb->pquery("INSERT INTO dashboardbuilder_blocks(id,block_label,block_sequence,block_module,brid,doc_widget) "
            . " values(?,?,?,?,?,?)", 
            array($newID, $mv->block_label, $mv->block_sequence, $mv->block_module, $mv->brid, $mv->doc_widget));
    echo true;
} 
elseif ($kaction == 'tablist') {
    $tabsarray = array();
    $alltabs = $adb->pquery("SELECT * from dashboardbuilder_tabs dstab
                            INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dstab.id
                            WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0",array($dashboardName,"Tabs"));
    $count = $adb->num_rows($alltabs);

    for ($i = 0; $i < $count; $i++) {
        $tabsarray[$i]['id'] = $adb->query_result($alltabs, $i, 'id');
        $tabsarray[$i]['tabname'] = $adb->query_result($alltabs, $i, 'tab_label');
    }
    echo json_encode($tabsarray);
} 
/*
 * CRUD FIELDS
 */
elseif ($kaction == 'retrievefield') {
    $allblocks = $adb->pquery("SELECT * FROM dashboardbuilder_fields dsfield
                              INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsfield.id
                              WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0",array($dashboardName,'Fields'));
    while ($allblocks && $row = $adb->fetch_array($allblocks)) {
        $content['id'] = $row['id'];
        $content['fieldname'] = $row['fieldname'];
        $content['blockid'] = $row['block']."";
        $blockName = findBlockName($row['block']);
        $content['blockname'] = $blockName ? $blockName : "";
        $content['field_sequence'] = intval($row['field_sequence']);
        $records[] = $content;
    }
    echo json_encode($records);
} 
elseif ($kaction == 'updatefield') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $blockId = $mv->blockid;
    $tabId = $mv->module;
    $adb->pquery("UPDATE dashboardbuilder_fields "
            . " SET fieldname=?,"
            . " block=?, "
            . " field_sequence=?"
            . " WHERE id=", 
            array($mv->fieldname,$blockId, $mv->field_sequence, $mv->id));
    echo true;
} 
elseif ($kaction == 'deletefield') {
    $models = $_REQUEST['models'];
    $model_values = array();
    $model_values = json_decode($models);
    $mv = $model_values[0];
    $id = $mv->id;
    $adb->pquery("UPDATE dashboardbuilder_entities SET deleted=1 WHERE dsid=?", array($id));
    echo true;
} 
elseif ($kaction == 'createfield') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $newID=addNewEntity("Fields",$dashboardName);
    $adb->pquery("INSERT INTO dashboardbuilder_fields"
            . " (id,fieldname,block,field_sequence) "
            . " VALUES(?,?,?,?)", 
            array($newID, $mv->fieldname, $mv->blockid,$mv->field_sequence));
} 
elseif ($kaction == 'blocklist') {
    $blocksarray = array();
    $allblocks = $adb->pquery("SELECT dsblock.* FROM dashboardbuilder_blocks dsblock
                              INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsblock.id
                              WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0",array($dashboardName,"Blocks"));
    $count = $adb->num_rows($allblocks);

    for ($i = 0; $i < $count; $i++) {
        $blocksarray[$i]['id'] = $adb->query_result($allblocks, $i, 'id');
        $blocksarray[$i]['blockname'] = $adb->query_result($allblocks, $i, 'block_label');
    }
    echo json_encode($blocksarray);
}
/*
 * CRUD Modules Entities
 */
elseif ($kaction == 'retrievemodulesentities') {
	
    $allblocks = $adb->pquery("SELECT * from dashboardbuilder_entities ds_en 
                            WHERE ds_en.name=? AND ds_en.entity=? ",array($dashboardName,$entityName));
    while ($allblocks && $row = $adb->fetch_array($allblocks)) {
        $content['moduleid'] = getTabid($row['entityname'])."";
        $content['modulename'] = $row['entityname'];
        $content['modulename_trans'] = getTranslatedString($row['entityname']);
        $content['parent'] = $row['parentname']."";
        $content['level'] = $row['level'];
        $content['pointingfield'] = $row['pointing_field'];
        $content['dsid'] = $row['dsid'];
        $content['elastic_name'] = $row['entityname'];
        $content['savedid'] = $row['savedid'];
        $records[] = $content;
    }
    echo json_encode($records);
}
elseif ($kaction == 'delmoduleentity') {
	
    $models=file_get_contents('php://input');
    $mv=json_decode($models);
    $adb->pquery("Delete from dashboardbuilder_entities  
                        WHERE dsid=? ",array($mv->dsid));    
}
/*
 * CRUD ACTIONS
 */
elseif ($kaction == 'retrieveactions') {	
    $allblocks = $adb->pquery("SELECT dsaction.* FROM dashboardbuilder_actions dsaction
                              INNER JOIN dashboardbuilder_entities ds_en ON ds_en.dsid=dsaction.id
                              WHERE ds_en.name=? AND ds_en.entity=? AND ds_en.deleted=0",array($dashboardName,$entityName));
    while ($allblocks && $row = $adb->fetch_array($allblocks)) {
        $content['id'] = $row['id'];
        $name=$row['name'];
        $content['reference'] = $name;
        $name=findBusinessActionName($row['name']);
        $content['name'] = $name?$name:"";
        $content['label'] = $row['label'];
        $content['blockname'] = findBlockName($row['block']);
        $content['block'] = $row['block'];
        $content['autoload'] = $row['autoload']== 1 ? true : false;;
        $content['sequence'] = intval($row['sequence']);
        $content['action_module'] = $row['action_module'];
        $content['action_module_label'] = getTabModuleName($row['action_module']);
        $content['action_type'] = $row['action_type'];
        $content['mandatory_action'] = ($row['mandatory_action']== 1 ? true : false);
        $records[] = $content;
    }
    echo json_encode($records);
} 
elseif ($kaction == 'updateactions') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $adb->pquery("UPDATE dashboardbuilder_actions "
            . " SET name=?,label=?,block=?,sequence=?,action_module=?,action_type=?,mandatory_action=? "
            . " WHERE id=?", 
            array($mv->reference, $mv->label, $mv->block, $mv->sequence, $mv->action_module,
                $mv->action_type,$mv->mandatory_action,
                $mv->id));
} 
elseif ($kaction == 'deleteactions') {
    $models = $_REQUEST['models'];
    $model_values = array();
    $model_values = json_decode($models);
    $mv = $model_values[0];
    $id = $mv->id;
    $adb->pquery("UPDATE dashboardbuilder_entities SET deleted=1 WHERE dsid=?", array($id));
    echo true;
} 
elseif ($kaction == 'createactions') {
    $models = $_REQUEST['models'];
    $mv = json_decode($models);
    $newID=addNewEntity("Actions",$dashboardName);
    $adb->pquery("INSERT INTO dashboardbuilder_actions(id,name,label,block,sequence,action_module,"
            . " action_type,mandatory_action) "
            . " values(?,?,?,?,?,?,?,?)", 
            array($newID, $mv->reference, $mv->label, $mv->block, $mv->sequence, $mv->action_module, 
                $mv->action_type,$mv->mandatory_action));
    echo true;
}
if ($kaction == 'businessactionslist') {
    $actionarray=array();
    $actionQuery = "SELECT * FROM  vtiger_actions 
                    INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_actions.actionsid
                    WHERE ce.deleted=0 AND moduleactions=? AND actions_status=? ";
    $res = $adb->pquery($actionQuery, array($currentModule, 'Active'));
    for ($i = 0; $i < $adb->num_rows($res); $i++) {
        $actionarray[$i]['id'] = $adb->query_result($res, $i, 'actionsid');
        $actionarray[$i]['name'] =  $adb->query_result($res, $i, 'reference');
    }
    echo json_encode($actionarray);
}
?>