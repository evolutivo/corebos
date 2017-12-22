<?php

include_once('config.php');
require_once('include/logging.php');
require_once('include/utils/utils.php');

// Account is used to store vtiger_account information.
class DashboardStructure extends CRMEntity {

    var $log;
    var $db;
    var $table = 'dashboardbuilder_';
    var $type = '';
    var $label = '';
    var $indexname = '';
    var $index_type = '';
    var $index_columns = array();
    var $master_dealer = array();
    var $record = '';
    var $masterModule = '';
    
function __construct($module) {
        global $log, $currentModule;
        $currentModule=$module;
        $this->db = PearDatabase::getInstance();
        $this->log = $log;
        $this->getExtType();
    }

    function getExtType() {    
        global $currentModule;
        $type = '';$label = '';$generalInfo=array();
        $query = $this->db->pquery("SELECT * from $this->table" . "extensions extensions
                                        WHERE extensions.name=? 
                                        ", array($currentModule));
        while ($query && $row = $this->db->fetch_array($query)) {
            $type = $row['type'];
            $label = $row['label'];
            $masterModule=$row['onsave'];
            $generalInfo['onopen']=($row['onopen']=='Show' ?  'detail' :'create');
            $generalInfo['onsave']=$row['onsave'];
            $generalInfo['type']=$type;
            $generalInfo['label']=$row['label'];
        }
        $this->type=$type;
        $this->label=$label;
        $this->masterModule=$masterModule;
        return $generalInfo;
    }
    
    function getEntities($view) {
        global $currentModule;
        $entityType = $this->type;
        $info=array();
        if($this->type=='TypeForm'){
            $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                             INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                             WHERE ext.name=? AND entities.entity=?
                                             ", array($currentModule,$entityType));        
            while ($query && $row=$this->db->fetch_array($query)){            
                    $info=$this->retrieveEntitiesParamsIndex($row);            
            }
        }
        elseif($this->type=='Kibi'){
            $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                             INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                             WHERE ext.name=? AND entities.entity=?
                                             ", array($currentModule,$entityType));        
            while ($query && $row=$this->db->fetch_array($query)){    
                    $entityname= $row['entityname'];
                    $settings=$this->getExtType();
                    $info['settings']=$settings;  
                    $info['ConfigEntities']=array('iframeurl'=> html_entity_decode($entityname,ENT_QUOTES)); 
                    coreBOS_Session::set('ConfigEntities',$info['ConfigEntities']);
            }
        }
        else{
            $info=$this->retrieveEntitiesParamsModule($view);  
        }
        return $info;
    }
    
    function retrieveEntitiesParamsIndex($row){
        $info=array();
        $indexname= $row['entityname'];
        $index_type= $row['index_type'];
        $columnsElastic=$this->retrieveElasticColumns($indexname,$index_type);
        $this->indexname=$indexname;
        $this->index_type=$index_type;
        $info[]=array('name'=>$indexname,'columns'=>$columnsElastic,'type'=>$this->type);
        return $info;
    }
    
    function retrieveEntitiesParamsModule($view){
        
        global $currentModule;
        if($view=='initial'){
            $settings=$this->getExtType();
            $info['settings']=$settings;
        }
        else{
            $settings=$this->getExtType();
            $settings['outsideid']=$this->record;
            $info['settings']=$settings;  
            $modulesEnvolved=$this->getModulesEnvolved();        
            $info['ConfigEntities']=$modulesEnvolved; 
            coreBOS_Session::set('ConfigEntities',$info['ConfigEntities']);
            //$_SESSION['ConfigEntities']=$info['ConfigEntities'];
//            if($view=='create' || $view=='session'){
            $blockFields=$this->getModuleBlocks();
            $info['steps']=$blockFields;
            coreBOS_Session::set('steps',$info['steps']);
            //$_SESSION['steps']=$info['steps'];
//            }
//            if($view=='detail' || $view=='session'){
            $info['DetailViewBlocks']=$this->getModuleDetailViewBlocks($currentModule);
            coreBOS_Session::set('DetailViewBlocks',$info['DetailViewBlocks']);
            //$_SESSION['DetailViewBlocks']=$info['DetailViewBlocks'];
//            } 
        }
        return $info;                
    }       
    
    function getEntityRelation($module) {
        global $currentModule;
        $info=array();
        $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         WHERE ext.name=? AND entities.entityname=? 
                                         ", array($currentModule,$module));        
       while ($query && $row=$this->db->fetch_array($query)){
            $entityname=$row['entityname'];
            $level=$row['level'];
            $parentname=$row['parentname'];
            $pointing_field=$row['pointing_field'];
            $savedid=$row['savedid'];
            if($this->record!=''){
               $savedid= $this->record;
               $outsideid=$this->record;
            }
            elseif($entityname==$this->masterModule){
                $this->record=$savedid;
            }
            $info=array('entityname'=>$entityname,'level'=>$level,
                    'parentname'=>  getTabModuleName($parentname),'pointing_field'=>$pointing_field,
                "savedid"=>$savedid,'outsideid'=>$outsideid);
        }        
        return $info;
    }
    
    function getModulesEnvolved() {
        global $currentModule;
        $entityRelation=array();
        $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         WHERE ext.name=? AND entities.entity=? ORDER BY FIELD(level,'Master','Detail')
                                         ", array($currentModule,'Modules'));        
       while ($query && $row=$this->db->fetch_array($query)){
            $entityname=$row['entityname'];
            $entityRelation[$entityname]=$this->getEntityRelation($entityname);//$this->getModuleSingleBlock($entityname);
        }
        $this->master_dealer=$entityRelation;
        return $entityRelation;
    }
    
    /*
     * get all Blocks  to create the step by step wizard 
     * 
     */
    function getModuleBlocks() {
        global $currentModule;
        $info=array();
        $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         INNER JOIN $this->table". "blocks blocks ON entities.dsid=blocks.id
                                         WHERE ext.name=? AND entities.entity=? order by block_sequence Asc
                                         ", array($currentModule,'Blocks'));        
       while ($query && $row=$this->db->fetch_array($query)){
            $blockid=$row['id'];
            $block_module=$row['block_module'];
            $block_label=$row['block_label']; //step name
            $blockBR=$row['brid'];
            $doc_widget=($row['doc_widget']==1 ? true : false);
            $fields=$this->getBlockFields($blockid,$block_module,$blockBR);
            $entityRelation=$this->getEntityRelation(getTabModuleName($block_module));
            if(sizeof($fields)>0){
                $info[]=array('fields'=>$fields,'block_label'=>$block_label,'blockid'=>$blockid,//step name
                    'entityname'=>$entityRelation["entityname"],'level'=>$entityRelation["level"],
                    'parentname'=>$entityRelation["parentname"],'pointing_field'=>$entityRelation["pointing_field"],
                    "savedid"=>$entityRelation["savedid"],"doc_widget"=>$doc_widget);
            }
        }
        return $info;
    }
    
    /*
     * get Blocks/Fields of specific module
     * 
     */
    function getModuleSingleBlock($module) {
        global $currentModule;
        $info=array();
        $query = $this->db->pquery("SELECT * from $this->table" . "entities entities
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         INNER JOIN $this->table". "blocks blocks ON entities.dsid=blocks.id
                                         WHERE ext.name=? AND blocks.block_module=?
                                         ", array($currentModule,  getTabid($module)));        
       while ($query && $row=$this->db->fetch_array($query)){
            $blockid=$row['id'];
            $block_module=$row['block_module'];
            $block_label=$row['block_label'];
            $blockBR=$row['brid'];echo $module;
            //$fields=$this->getBlockFields($blockid,$block_module,$blockBR);
            $entityRelation=$this->getEntityRelation(getTabModuleName($block_module));
            if(sizeof($fields)>0){
                $info=array('block_label'=>$block_label,
                    'entityname'=>$entityRelation["entityname"],'level'=>$entityRelation["level"],
                    'parentname'=>$entityRelation["parentname"],'pointing_field'=>$entityRelation["pointing_field"],
                    "savedid"=>$entityRelation["savedid"]);
            }
        }
        return $info;
    }
    
    function getBlockFields($blockid,$moduleid,$blockBR) {
        global $currentModule;
        $info=array();$rows=array();$generalinfo=array();
        $i=0;
        $blockBR=$this->pickBr2Role($blockBR);
        if(empty($blockBR)){
            $query = $this->db->pquery("SELECT * from $this->table". "fields  fields
                                            join vtiger_field on vtiger_field.fieldname=fields.fieldname
                                            WHERE fields.block=? and tabid =? order by field_sequence
                                            ", array($blockid,$moduleid));        
            while ($query && $row=$this->db->fetch_array($query)){
                $columns=array();
                $fieldname=$row['fieldname'];
                $uitype=$row['uitype'];
                $fieldlabel=$row['fieldlabel'];
                $tabid=$row['tabid'];
                $columns[]=$fieldname;
                $rows[$i]=  $columns; 
                $generalinfo[]=array('fieldname'=>$fieldname,'uitype'=>$uitype,'fieldlabel'=>getTranslatedString($fieldlabel,  getTabModuleName($moduleid)),
                    'field_module'=>  getTabModuleName($tabid));
                $i++;
            }
            $info['blocks'][]= array('rows'=>$rows,'block_label'=>'Default'); 
            $info['info']= $generalinfo;
        }else{
            $returnInfo=$this->readBraaS($blockBR);
            for($c=0;$c<sizeof($returnInfo['blocks']);$c++){
                $blockData=$returnInfo['rows'][$c];
                $info['blocks'][]= array('rows'=>$returnInfo['rows'][$c],'block_label'=>$returnInfo['blocks'][$c]); 
                $generalinfo=$this->fieldInfo($returnInfo['rows'][$c],$moduleid,'');
                if(sizeof($info['info'])>0){
                    $info['info']= array_merge($info['info'],$generalinfo);
                }
                else{
                    $info['info']= $generalinfo;
                }
            }
        }
        return $info;
    }
    
    function retrieveElasticColumns($indexname,$index_type){
        $content=array();
        $search_host="193.182.16.34";
        $search_port="9200";
        $method='GET';
        $indextosearch=$indexname;
        $doctosearch=$index_type;
        $url = 'http://'.$search_host.':'.$search_port.'/'.$indextosearch.'/'.$doctosearch.'/'.'_mapping';
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PORT, 9200);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        $result = curl_exec($ch);
        curl_close($ch);
        $ary = json_decode($result,true);
        $i=0;
        foreach ( $ary as $key=>$value){
            foreach($value as $key2=>$value2){
                foreach($value2 as $key3=>$value3){
                    foreach($value3["properties"]  as $key4=>$value4){
                        $i++;
                        foreach($value4  as $key5=>$value5){
                            $content[$i]['name']=$key4;
                            $content[$i]['type']=$value5;
                        }
                    }
                }
            }     
        }
        return $content;        
    }
    
    function handleMultiInsert($answers,$config,$settings,$steps,$documents)
    {
        $response='';
        if($settings['type']=='TypeForm'){
            $this->insertToIndex($answers);
        }
        else{
            $this->master_dealer=$config;
            $response=$this->insertEntities($answers,$config,$steps,$documents);
        }
//        $modulesEnvolved=$this->getModulesEnvolved();        
//        $temp=json_decode($_SESSION['getEntities']);
//        $temp['ConfigEntities']=$modulesEnvolved;
//        $_SESSION['getEntities']=json_encode($temp);
        echo json_encode($response);
    }
    
    function handleInsert($answers,$config,$settings)
    {
        if($settings['type']=='TypeForm'){
            $this->insertToIndex($answers);
        }
        else{
            $resp=$this->insertEntity($answers,$config);
        }
        return $resp;
    }
    
    function handleUpdate($answers,$config,$settings)
    {
        $resp=$this->updateEntity($answers,$config);
        return $resp;
    }
    
    function insertToIndex($answers)
    {
        $ip=GlobalVariable::getVariable('ip_elastic_server', '');
        $prefix=GlobalVariable::getVariable('ip_elastic_indexprefix', '');
        $indexname=$this->indexname;
        $index_type=$this->index_type;
        if($answers){
		$endpointUrl2 = "http://$ip:9200/$indexname/$index_type";
                $this->exeCurlPost($answers, $endpointUrl2);
	}
        echo 'Successfull saved info';
    }
    
    function insertEntity($data,$config,$steps)
    {
        global $current_user;
        $entityname=$config['entityname'];
        $fields=array();
        for($step=0;$step<sizeof($steps);$step++){
            if($entityname!=$steps[$step]['entityname']) continue;
            $fields_block=$steps[$step]['fields']['blocks'];            
            for($c=0;$c<sizeof($fields_block);$c++){
                $fld_rows=$fields_block[$c]['rows'];
                foreach($fld_rows as $k=>$v){
                    foreach($v as $col=>$val){
                        $fields[]=$val;
                    }
                }
            }
        }
        require_once("modules/$entityname/$entityname.php");
        $focus= CRMEntity::getInstance($entityname);
        $focus->id='';
        $focus->column_fields['assigned_user_id']=$current_user->id;
        for($i=0;$i<sizeof($fields);$i++){
            $fieldname=$fields[$i];
            $fieldvalue=$data["$fieldname"];
            if(!empty($fieldvalue))
                $focus->column_fields["$fieldname"]=$fieldvalue;
            
            if($fieldname=='assigned_user_id'){
                $focus->column_fields['assigned_user_id']=$data['assigned_user_id'.'_'.$entityname];
            }
            if($fieldname=='description'){
                $focus->column_fields['description']=$data['description'.'_'.$entityname];
            }
        }
        if($config['level']=='Detail'){
            $pointing_field=$config['pointing_field'];
            $parentname=$config['parentname'];
            $parent_info=$this->getMasterDealerData($parentname);
            $focus->column_fields["$pointing_field"]=$parent_info['savedid'];
        }                
        $focus->save("$entityname");
        $this->executeEntityActions($entityname,$focus->id,$data);
        $this->saveMasterDealerData($entityname,$focus->id);
        if($entityname=='Users'){
            $this->sendMailUser($data);
            $parentname=$config['parentname'];
            $parent_info=$this->getMasterDealerData($parentname);
            $focusF= CRMEntity::getInstance($parentname);
            $focusF->id=$parent_info['savedid'];
            $focusF->mode='edit';
            $focusF->retrieve_entity_info($parent_info['savedid'],"$parentname");
            $focusF->column_fields['assigned_user_id']=$focus->id;
            $focusF->save("$parentname");
        }
        return $focus->id;        
    }
    
    function updateEntity($data,$config)
    {
        global $current_user,$currentModule,$log;
        $entityname=$config['entityname'];
        
        require_once("modules/$entityname/$entityname.php");
        $focus= CRMEntity::getInstance($entityname);
        $focus->id=$data["id"];
        $focus->mode='edit';
        $focus->retrieve_entity_info($data["id"],"$entityname");
        $focus->column_fields['assigned_user_id']=$focus->column_fields['assigned_user_id'];
        foreach($data as $key=>$value){
            $fieldname=$key;
            $fieldvalue=$value;
            $focus->column_fields["$fieldname"]=$fieldvalue;  
            if($fieldname=='assigned_user_id'){
                $focus->column_fields['assigned_user_id']=$data['assigned_user_id'.'_'.$entityname];
            }
        }  
        $focus->save("$entityname");
        return $focus->id;        
    }   
                
    function updateFather($data)
    {
        $fatherModule=  getSalesEntityType($data["id"]);
        global $current_user,$currentModule,$log;
        $entityname=$fatherModule;
        
        require_once("modules/$entityname/$entityname.php");
        $focus= CRMEntity::getInstance($entityname);
        $focus->id=$data["id"];
        $focus->mode='edit';
        $focus->retrieve_entity_info($data["id"],"$entityname");
        $focus->column_fields['assigned_user_id']=$focus->column_fields['assigned_user_id'];
        foreach($data as $key=>$value){
            $fieldname=$key;
            $fieldvalue=$value;
            $focus->column_fields["$fieldname"]=$fieldvalue;
        }       
        $focus->save("$entityname");
        return $focus->id;        
    }
    
    function saveDocument($name)
    {
        global $current_user,$currentModule,$log;
        $entityname='Documents';
        
        require_once("modules/$entityname/$entityname.php");
        $focus= CRMEntity::getInstance($entityname);
        $focus->id='';
        $focus->column_fields['assigned_user_id']=$current_user->id;
        $focus->column_fields['notes_title']=$name;
        $focus->column_fields['filelocationtype']='I';
        $focus->column_fields['filestatus']='1';
         $focus->column_fields['filename']='sdf';
        
        $focus->save("$entityname");
        return $focus->id;        
    }
    
    function sendMailUser($data){
        global $app_strings, $mod_strings, $default_charset,$current_user,$site_URL;
	require_once('modules/Emails/mail.php');
        require_once('modules/Users/language/it_it.lang.php');
        $user_emailid = $data['email1'];
	// send email on Create user only if NOTIFY_OWNER_EMAILS is set to true

	$subject = $mod_strings['User Login Details'];
	$email_body = $app_strings['MSG_DEAR']." ". $data['last_name'] .",<br><br>";
	$email_body .= $app_strings['LBL_PLEASE_CLICK'] . " <a href='" . $site_URL . "' target='_blank'>"
				. $app_strings['LBL_HERE'] . "</a> " . $mod_strings['LBL_TO_LOGIN'] . "<br><br>";
	$email_body .= $mod_strings['LBL_USER_NAME'] . " : " . $data['user_name'] . "<br>";
	$email_body .= $mod_strings['LBL_PASSWORD'] . " : " . $data['user_password'] . "<br>";
	$email_body .= $mod_strings['LBL_ROLE_NAME'] . " : " . getRoleName($data['roleid']) . "<br>";
	$email_body .= "<br>" . $app_strings['MSG_THANKS'] . "<br>" . $current_user->user_name;
	//$email_body = htmlentities($email_body, ENT_QUOTES, $default_charset);  // not needed anymore, PHPMailer takes care of it

	//$mail_status = send_mail('Users',$user_emailid,$HELPDESK_SUPPORT_NAME,$HELPDESK_SUPPORT_EMAIL_ID,$subject,$email_body);
	$mail_status=sendGridMail(array($user_emailid),$email_body,$subject,'local@subito.it','','','',array(),array(),'Subito');
        if($mail_status != 1) {
		$mail_status_str = $user_emailid."=".$mail_status."&&&";
		$error_str = getMailErrorString($mail_status_str);
	}
    }
    
    function insertEntities($data,$config,$steps,$documents)
    {
        $masterId=0;
        $savedIDS=array();
        foreach($config as $key=>$value){
            $entityname=$value['entityname'];
            if($data['actiontype']==='update' && $data['return_id']!==''){
                $salesEntity= getSalesEntityType($data['return_id']);
                if($entityname===$salesEntity){
                    if($value['level']==='Detail'){
                        $pointing_field=$value['pointing_field'];
                        $parentname=$value['parentname'];
                        $parent_info=$this->getMasterDealerData($parentname);
                        $data["$pointing_field"]=$parent_info['savedid'];
                    } 
                    $data["id"]=$data['return_id'];
                    $resp=$this->updateEntity($data,$value);
                    $this->executeEntityActions($entityname,$resp,$data);
                    $this->saveMasterDealerData($entityname,$resp);
                }
                else{
                    $resp=$this->insertEntity($data,$value,$steps); 
                }
            }
            else
                $resp=$this->insertEntity($data,$value,$steps); 
            $savedIDS[$entityname]=$resp;
            $this->relateDocuments($resp,$documents[$entityname]);
        }        
        return $savedIDS;
    }
    
    function relateDocuments($id,$docs){
        global $adb;
        for($i=0;$i<sizeof($docs);$i++){
            $adb->pquery("Insert into vtiger_senotesrel
                    values (?,?)",array($id,$docs[$i]['id']));
        }
    }
    
    function executeEntityActions($module,$id,$data)
    {
        include_once "modules/Actions/runJSONAction.php";    
        global $current_user;
        $allActions=$this->getActionsOfModule($module);
        for($i=0;$i<sizeof($allActions);$i++){
            $actionid=$allActions[$i]['actionid'];
            $data['recordid']=$id;
            $request = json_encode($data, true);
            $runResult= runJSONAction($actionid,$request);
        }
    }
    
    function saveMasterDealerData($entityname,$savedid)
    {
        foreach($this->master_dealer as $key=>$value){
            if($key==$entityname){
                $this->master_dealer[$entityname]['savedid']=$savedid;
                $ConfigEntities=coreBOS_Session::get('ConfigEntities');
                $ConfigEntities["$entityname"]["savedid"]=$savedid;
                coreBOS_Session::set('ConfigEntities',$ConfigEntities);
                //$_SESSION['ConfigEntities']["$entityname"]["savedid"]=$savedid;
                global $log,$adb,$currentModule;
                $adb->pquery("Update dashboardbuilder_entities"
                        . " set savedid=? "
                        . " where name=? and entityname=?", 
                    array( $savedid,$currentModule,$entityname));
                break;
            }            
        }
        
    }
    
    function getMasterDealerData($entityname)
    {
        foreach($this->master_dealer as $key=>$value){
            if($key==$entityname){                   
                return $this->master_dealer[$entityname];
            }            
        }        
    }
    
    function retrieveInfo($id)
    {
        $module=  getSalesEntityType($id);
        require_once("modules/$module/$module.php");
        $focus=  CRMEntity::getInstance("$module");
        if(isPermitted($module, 'DetailView', $id) !=='no'){
            $focus->retrieve_entity_info($id,"$module");
            $keyname=$focus->list_link_field;
            $list_link_field=$focus->column_fields["$keyname"];
            $focus->column_fields["list_link_field"]=$list_link_field;
            return $focus->column_fields;  
        }
        else{
            return array('denied'=>'denied');
        }
    }
    
    function retrieveDetailRecords($masterid)
    {
        global $log,$adb,$currentModule;
        $recordsSet=array();
        $module=  getSalesEntityType($masterid);
        $mod_id=  getTabid($module);
        require_once("modules/$module/$module.php");
        $focus_moduleParent= CRMEntity::getInstance($module);
        $moduleParent_table=$focus_moduleParent->table_name;
        $moduleParent_id=$focus_moduleParent->table_index;
        $parent_info=$this->getMasterDealerData($module);        
        $result=$adb->pquery("Select * from dashboardbuilder_entities"
                . " where name=? and parentname=?", 
                array( $currentModule,$mod_id));
        for($i=0;$i<$adb->num_rows($result);$i++){
            $moduleChild    =$adb->query_result($result,$i,'entityname');
            $pointing_field =$adb->query_result($result,$i,'pointing_field');
            require_once("modules/$moduleChild/$moduleChild.php");
            $focus_moduleChild= CRMEntity::getInstance($moduleChild);
            $moduleChild_table=$focus_moduleChild->table_name;
            $moduleChild_id=$focus_moduleChild->table_index;
            $moduleChild_list_link_field=$focus_moduleChild->list_link_field;
            $qry=" 
                  SELECT $moduleChild_table.$moduleChild_id
                  FROM $moduleParent_table t1
                  join $moduleChild_table on t1.$moduleParent_id = $moduleChild_table.$pointing_field
                  join vtiger_crmentity on crmid = $moduleChild_table.$moduleChild_id
                  where deleted = 0 and t1.$moduleParent_id=? ";
            $query=$adb->pquery($qry,array($masterid));
            $count=$adb->num_rows($query);
            for($i=0;$i<$count;$i++){
                $recordsSet[$i]['id']=$adb->query_result($query,$i,$moduleChild_id);
                $recordsSet[$i]['href']='index.php?module='.$moduleChild.'&action=DetailView&record='.$recordsSet[$i]['id'];
                $focus_pointing= CRMEntity::getInstance($moduleChild);
                $focus_pointing->id=$adb->query_result($query,$i,$moduleChild_id);
                $focus_pointing->mode = 'edit';
                $focus_pointing->retrieve_entity_info($adb->query_result($query,$i,$moduleChild_id), $moduleChild);
                $recordsSet[$i]['name']=$focus_pointing->column_fields["$moduleChild_list_link_field"];
                $subSet=$this->retrieveDetailRecords($recordsSet[$i]['id']);
                $recordsSet[$i]['subSet']=$subSet;
            }
        }
        return $recordsSet;
    }
    
    function RetrieveFormDataListView($fromwhere,$size,$where)
    {
        $fromwhere=0;
        $ip=GlobalVariable::getVariable('ip_elastic_server', '');
        $prefix=GlobalVariable::getVariable('ip_elastic_indexprefix', '');
        $indexname=$this->indexname;
        $index_type=$this->index_type;
        $fields =array('query'=>array("match"=>$where));
        $queryData = array('from' => $fromwhere,'size'=> $size) ;
        $endpointUrl2 = "http://$ip:9200/$indexname/$index_type".'/_search?'.http_build_query($queryData) ;
        $response=$this->exeCurlPost($fields, $endpointUrl2);
        $total=$response->hits->total;
        $records=array();
        foreach ($response->hits->hits as $hit) {
            $records[$hit->_id] = $hit->_source;               
	}
        return array('total'=>$total,'records'=>$records);
    }
    
    function RetrieveFormDataDetailView($where)
    {
        $ip=GlobalVariable::getVariable('ip_elastic_server', '');
        $prefix=GlobalVariable::getVariable('ip_elastic_indexprefix', '');
        $indexname=$this->indexname;
        $index_type=$this->index_type;
        $endpointUrl2 = "http://$ip:9200/$indexname/$index_type".'/'.$where;
        $response=$this->exeCurlGet( $endpointUrl2);
        $records=array();$i=0;
        foreach ($response->_source as $k=>$v) {
            $records[$i]['name'] = $k;  
            $records[$i]['value'] = $v;  
            $i++;
	}
        return $records;
    }
    
    function exeCurlPost($fld, $endpointUrl2)
    {
	$channel11 = curl_init();
	curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
	curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($channel11, CURLOPT_POST, true);
	//curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fld));
	curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
	curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
	$response = curl_exec($channel11);
        return json_decode($response);
    }
    
    function exeCurlGet($endpointUrl2)
    {
	$channel11 = curl_init();
	curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
	curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($channel11, CURLOPT_POST, false);
	curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
	curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
	$response = curl_exec($channel11);
        return json_decode($response);
    }
    
    function vtws_getFieldDep(){
	global $log,$adb,$default_language,$currentModule;
	$log->debug("Entering function vtws_getFieldDep");

        $resp_f=array();
        $resp_fields=array();
        $target_picklist=array();
        $conditions=array();
        $mapFieldDependecy=array();
        $type='FieldDependency';
        $sql = 'SELECT * FROM vtiger_businessrules'
                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
                . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
                . ' INNER JOIN vtiger_crmentity c2 ON c2.crmid=vtiger_cbmap.cbmapid'
                . ' where ce.deleted=0 and c2.deleted=0 and module_rules =? and maptype=?';
        $res_business_rule=$adb->pquery($sql,array($currentModule,$type));
        for ($m=0;$m<$adb->num_rows($res_business_rule);$m++)
        {
            $businessrule=$adb->query_result($res_business_rule,$m,'businessrule'); 
            $linktomap=$adb->query_result($res_business_rule,$m,'linktomap');  
            if(empty($linktomap)) continue;
            $mapfocus=  CRMEntity::getInstance("cbMap");
            $mapfocus->retrieve_entity_info($linktomap,"cbMap");
            $mapFieldDependecy[$m]=$mapfocus->getMapFieldDependecyForm();
            if($m==0){
                $resp_fields=$mapFieldDependecy[$m]['respfield'];
                $target_picklist=$mapFieldDependecy[$m]['target_picklist'];
            }
            else{
                $resp_fields=array_merge($mapFieldDependecy[$m]['respfield'],$resp_fields); 
                $target_picklist=array_merge($mapFieldDependecy[$m]['target_picklist'],$target_picklist); 
            }
            if($this->record!=''){
               $savedid= $this->record; 
               $focus_data=$this->retrieveInfo($savedid);
               foreach($mapFieldDependecy[$m]['automatic_fieldname'] as $key=>$value){
                   if($value!=''){
                       $mapFieldDependecy[$m]['automatic_fieldname']["$key"]=$focus_data["$value"];
                   }
               }
            }
        } 
	return array('all_field_dep'=>$mapFieldDependecy,
                        'MAP_RESPONSIBILE_FIELDS'=>$resp_fields,
                        'MAP_PCKLIST_TARGET'=>$target_picklist);
}

    function vtws_getFieldDepFather($moduleFather){
            global $log,$adb,$default_language;
            $log->debug("Entering function vtws_getFieldDep");

            $resp_f=array();
            $resp_fields=array();
            $target_picklist=array();
            $conditions=array();
            $mapFieldDependecy=array();
            $type='FieldDependency';
            $sql = 'SELECT * FROM vtiger_businessrules'
                    . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
                    . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
                    . ' INNER JOIN vtiger_crmentity c2 ON c2.crmid=vtiger_cbmap.cbmapid'
                    . ' where ce.deleted=0 and c2.deleted=0 and module_rules =? and maptype=?';
            $res_business_rule=$adb->pquery($sql,array($moduleFather,$type));
            for ($m=0;$m<$adb->num_rows($res_business_rule);$m++)
            {
                $businessrule=$adb->query_result($res_business_rule,$m,'businessrule'); 
                $linktomap=$adb->query_result($res_business_rule,$m,'linktomap');  
                if(empty($linktomap)) continue;
                $mapfocus=  CRMEntity::getInstance("cbMap");
                $mapfocus->retrieve_entity_info($linktomap,"cbMap");
                $mapFieldDependecy[$m]=$mapfocus->getMapFieldDependecyForm();
                if($m==0){
                    $resp_fields=$mapFieldDependecy[$m]['respfield'];
                    $target_picklist=$mapFieldDependecy[$m]['target_picklist'];
                }
                else{
                    $resp_fields=array_merge($mapFieldDependecy[$m]['respfield'],$resp_fields); 
                    $target_picklist=array_merge($mapFieldDependecy[$m]['target_picklist'],$target_picklist); 
                }
    //            if($this->record!=''){
    //               $savedid= $this->record; 
    //               $focus_data=$this->retrieveInfo($savedid);
    //               foreach($mapFieldDependecy[$m]['automatic_fieldname'] as $key=>$value){
    //                   if($value!=''){
    //                       $mapFieldDependecy[$m]['automatic_fieldname']["$key"]=$focus_data["$value"];
    //                   }
    //               }
    //            }
            } 
            return array('all_field_dep'=>$mapFieldDependecy,
                            'MAP_RESPONSIBILE_FIELDS'=>$resp_fields,
                            'MAP_PCKLIST_TARGET'=>$target_picklist);
    }

    function readBraaS($brid){
	global $log,$adb,$default_language,$currentModule;
	$log->debug("Entering function vtws_getFieldDep");

        $mapInfo=array();
        $sql = 'SELECT * FROM vtiger_businessrules'
                . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
                . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
                . ' INNER JOIN vtiger_crmentity c2 ON c2.crmid=vtiger_cbmap.cbmapid'
                . ' where ce.deleted=0 and c2.deleted=0 and businessrulesid=?';
        $res_business_rule=$adb->pquery($sql,array($brid));        
        $linktomap=$adb->query_result($res_business_rule,0,'linktomap');  
        $maptype=$adb->query_result($res_business_rule,0,'maptype');  
        $mapfocus=  CRMEntity::getInstance("cbMap");
        $mapfocus->retrieve_entity_info($linktomap,"cbMap");
        
        if($maptype=='CREATEVIEWPORTAL' || $maptype=='DETAILVIEWBLOCKPORTAL'){
            $mapInfo=$mapfocus->getMapPortalDvBlocks();
        }  
        
	return $mapInfo;
    }
    
    function getRelatedModules($module,$recordId,$widgetType)
    {
        global $adb;
        $customlink_params = Array('MODULE'=>$module, 'RECORD'=>$recordId);
        $CUSTOM_LINKS=Vtiger_Link::getAllByType(getTabid($module), $widgetType, $customlink_params);    
        $ngblocks=$CUSTOM_LINKS['DETAILVIEWWIDGETFORM'];
        for($i=0;$i<sizeof($ngblocks);$i++){
            $id=$ngblocks[$i]->linklabel;
            $tabid=$ngblocks[$i]->tabid;
            $result=$adb->pquery("Select name,type from vtiger_ng_block "
                    . " where id=?",array($id));
            $name=$adb->query_result($result,0,'name');
            $type=$adb->query_result($result,0,'type');
            $CUSTOM_LINKS['DETAILVIEWWIDGETFORM'][$i]->name=$name;
            $CUSTOM_LINKS['DETAILVIEWWIDGETFORM'][$i]->type=($type=='' ? 'Table' : $type);
            $CUSTOM_LINKS['DETAILVIEWWIDGETFORM'][$i]->module=  getTabModuleName($tabid);
        }
        return $CUSTOM_LINKS;
    }
    
    function doGetRelatedRecords($ngblockid,$limit,$where)
    {
        require_once 'modules/NgBlock/NgBlock.php';
        global $adb;
        $ngblock=new NgBlock();
        $records=$ngblock->doGetRelatedRecords($ngblockid,$this->record,$limit,$where);
        $fields=$records['fields'];
        $pointingModule=$records['pointingModule'];
        $fieldInfo=$this->fieldNgBlockInfo($fields,  getTabid($pointingModule));
        $records['fields']=$fieldInfo;
        
        return $records;
    }
    
    function fieldInfo($fieldsGroups,$moduleid,$recordid=''){
        require_once 'modules/PickList/PickListUtils.php';
        global $adb,$current_user;
        $generalinfo=array();
        $ui10FormName='';$ui10ActionName='';
        $focus_pointing= CRMEntity::getInstance(getTabModuleName($moduleid));
        if($recordid!=''){
            $resultDeleted = $adb->pquery("select * from vtiger_crmentity where crmid=?", array($recordid));
            $isRecordDeleted = $adb->query_result($resultDeleted, 0, "deleted");
            if ($isRecordDeleted === 0 || $isRecordDeleted === '0') {
                    $focus_pointing->retrieve_entity_info($recordid,getTabModuleName($moduleid));
            }
        }
        foreach($fieldsGroups as $fields){
            $query = $this->db->pquery("SELECT * from vtiger_field
                                            WHERE tabid='$moduleid' AND fieldname in (".generateQuestionMarks($fields).")
                                            ", $fields);   
            while ($query && $row=$this->db->fetch_array($query)){
                $fieldname=$row['fieldname'];
                $uitype=$row['uitype'];
                $fieldlabel=$row['fieldlabel'];
                $tabid=$row['tabid'];  
                $tabname=getTabModuleName($tabid);
                $roleid=$current_user->roleid;
                $col_fields[$fieldname]=$focus_pointing->column_fields["$fieldname"];
                $block_info=getDetailViewOutputHtml($uitype,$fieldname,'',$col_fields,'','',getTabModuleName($moduleid));
                if($uitype=='15' || $uitype=='16' || $uitype=='33' || $uitype=='111' || $uitype=='115'){
                    $picklistValues = $block_info['options'];
                    
                    if($uitype=='111'){
                        $exists=false;
                        for($count=0;$count<sizeof($picklistValues);$count++){
                            if($focus_pointing->column_fields["$fieldname"]==$picklistValues[$count][0])
                                $exists=true;break;
                        }
                        if(!$exists){
                            $name=  $focus_pointing->column_fields["$fieldname"];
                            $id=$focus_pointing->column_fields["$fieldname"];
                            $picklistValues[]=array("$name","$name","");
                        }
                    }
                    else if($uitype=='115'){
                        $picklistValuesTemp=$picklistValues;
                        $picklistValues=array();
                        for($count=0;$count<sizeof($picklistValuesTemp);$count++){
                            $val=$picklistValuesTemp[$count];
                            foreach ($val as $key => $value) {
                                $picklistValues[]=array("$key","$key","");
                            }
                        }
                    }
                }
                elseif($uitype=='53'){
                    $picklistValues = $block_info['options'];
                    $exists=array_key_exists($focus_pointing->column_fields["assigned_user_id"], $picklistValues[1]);
                    if(!$exists){
                        $name=  getUserFullName($focus_pointing->column_fields["assigned_user_id"]);
                        $id=$focus_pointing->column_fields["assigned_user_id"];
                        $picklistValues[1]["$id"]=array("$name"=>"");
                    }
                }
                elseif($uitype=='10'){
                $block_info=getOutputHtml($uitype,$fieldname,'','',$col_fields,'',getTabModuleName($moduleid));
                    $picklistValues = $block_info[1][0]['options'];
                    $result_form=$adb->pquery("Select * "
                            . " from dashboardbuilder_extensions"
                            . " join dashboardbuilder_entities on dashboardbuilder_extensions.name=dashboardbuilder_entities.name"
                            . " where type in ('Modules') "
                            . " AND dashboardbuilder_entities.entityname=? "
                            . " AND dashboardbuilder_extensions.onsave=?",array($picklistValues[0],$picklistValues[0]));
                    $ui10FormName=$picklistValues[0];
                    $ui10ActionName='DetailView';
                    if($adb->num_rows($result_form)>0){
                        $ui10FormName=$adb->query_result($result_form,0,'name');
                        $ui10ActionName='index';
                    }
                }
                $generalinfo[]=array('fieldname'=>$fieldname,'uitype'=>$uitype,'fieldlabel'=>getTranslatedString($fieldlabel, getTabModuleName($moduleid)),
                    'field_module'=>  getTabModuleName($tabid),'options'=>$picklistValues,
                    'ui10FormName'=>$ui10FormName,'ui10ActionName'=>$ui10ActionName);
            }
        }
        return $generalinfo;
    }

    function fieldNgBlockInfo($fieldsGroups,$moduleid){
        require_once 'modules/PickList/PickListUtils.php';
        global $adb,$current_user;
        $generalinfo=array();
        $unorderedF=implode("','",$fieldsGroups);
        $focus_pointing= CRMEntity::getInstance(getTabModuleName($moduleid));
        $query = $this->db->pquery("SELECT * from vtiger_field
                                        WHERE tabid='$moduleid' AND fieldname in (".generateQuestionMarks($fieldsGroups).")
                                         ORDER BY FIELD(fieldname,'".$unorderedF."')
                                        ", $fieldsGroups); 
        
        $generalinfo[]=array('field'=>'expand','title'=>'');
        while ($query && $row=$this->db->fetch_array($query)){
            $fieldname=$row['fieldname'];
            $uitype=$row['uitype'];
            $fieldlabel=$row['fieldlabel'];
            $tabid=$row['tabid'];  
            $tabname=getTabModuleName($tabid);
            $roleid=$current_user->roleid;
            $col_fields[$fieldname]=$focus_pointing->column_fields["$fieldname"];
            $block_info=getDetailViewOutputHtml($uitype,$fieldname,'',$col_fields,'','',getTabModuleName($moduleid));
            if($uitype=='15' || $uitype=='16' || $uitype=='33')
                $picklistValues = $block_info['options'];
            if($uitype=='53')
                $picklistValues = $block_info['options'];
            $type=( ($uitype==2 || $uitype==1 || $uitype==15) ? "text" : "number");
            $generalinfo[]=array('field'=>$fieldname,'title'=>  getTranslatedString($fieldlabel,getTabModuleName($moduleid)),
                'filter'=>  array("$fieldname"=>$type));
        }
            $generalinfo[]=array('field'=>'','title'=>'');
        return $generalinfo;
    }
    
    function pickBr2Role($blockBR){
        require_once('modules/BusinessRules/BusinessRules.php');
        global $current_user;
        $roleid=$current_user->roleid;
        $brid='';
        $arr_br=explode(',',$blockBR);
        for($i=0;$i<sizeof($arr_br);$i++){
            $brid=$arr_br[$i];
            $brfocus=  CRMEntity::getInstance("BusinessRules");
            $brfocus->retrieve_entity_info($brid,"BusinessRules");
            $br_allowedroles=$brfocus->column_fields["br_allowedroles"]; 
            $br_allowedroles_arr=explode(',',$br_allowedroles);
            if(in_array($roleid, $br_allowedroles_arr)){                
                return $brid;
            }
        }
        return $brid;
    }
    
    /*
     * get  Blocks  of Detail/Edit View
     * 
     */
    function getModuleDetailViewBlocks($currentModule) {
        
        $info=array();
        $blockBR=$this->pickBrDetailView($currentModule);
        $returnInfo=$this->readBraaS($blockBR);
        $masterModule=  getSalesEntityType($this->record);//$getModulesEnvolved[0]['entityname'];//
        $moduleid= getTabid($masterModule);
        for($c=0;$c<sizeof($returnInfo['blocks']);$c++){
            $blockData=$returnInfo['rows'][$c];
            $info['blocks'][]= array('rows'=>$returnInfo['rows'][$c],'block_label'=>$returnInfo['blocks'][$c]); 
            $generalinfo=$this->fieldInfo($returnInfo['rows'][$c],$moduleid,$this->record);
            if(sizeof($info['info'])>0){
                $info['info']= array_merge($info['info'],$generalinfo);
            }
            else{
                $info['info']= $generalinfo;
            }
        }
        return $info;
    }
    
    function getModuleDetailViewBlocksFather($currentModule,$record) {
        
        $info=array();
        $blockBR=$this->pickBrDetailView($currentModule);
        $returnInfo=$this->readBraaS($blockBR);
        //$getModulesEnvolved=$this->getModulesEnvolved();//echo $this->record.' '.getSalesEntityType($this->record);
        $masterModule=  getSalesEntityType($record);//$getModulesEnvolved[0]['entityname'];//
        $moduleid= getTabid($masterModule);
        for($c=0;$c<sizeof($returnInfo['blocks']);$c++){
            $blockData=$returnInfo['rows'][$c];
            $info['blocks'][]= array('rows'=>$returnInfo['rows'][$c],'block_label'=>$returnInfo['blocks'][$c]); 
            $generalinfo=$this->fieldInfo($returnInfo['rows'][$c],$moduleid,$record);
            if(sizeof($info['info'])>0){
                $info['info']= array_merge($info['info'],$generalinfo);
            }
            else{
                $info['info']= $generalinfo;
            }
        }
        return $info;
    }
    
    function pickBrDetailView($mod){
        require_once('modules/BusinessRules/BusinessRules.php');
        global $current_user,$adb;
        $roleid=$current_user->roleid;
        $brid='';
        $qry='SELECT * FROM vtiger_businessrules'
        . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
        . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
        . ' INNER JOIN vtiger_crmentity c2 ON c2.crmid=vtiger_cbmap.cbmapid'
        . ' where ce.deleted=0 and c2.deleted=0 and module_rules =? and maptype=?';
        $result=$adb->pquery($qry,array($mod,'DETAILVIEWBLOCKPORTAL'));
        $nr=$adb->num_rows($result);
        for($i=0;$i<$nr;$i++){
            $brid=$adb->query_result($result,$i,'businessrulesid');
            $brfocus=  CRMEntity::getInstance("BusinessRules");
            $brfocus->retrieve_entity_info($brid,"BusinessRules");
            $br_allowedroles=$brfocus->column_fields["br_allowedroles"]; 
            $br_allowedroles_arr=explode(',',$br_allowedroles);
            if(in_array($roleid, $br_allowedroles_arr)){                
                return $brid;
            }
        }
        return $brid;
    }
    
    /*
     * get Actions of specific module on specific step of type InCreateWidget
     * 
     */
    function getActionsOfStep($steps,$step,$action_type) {
        global $currentModule;
        $info=array();
        $idStep=$steps[$step]['blockid'];
        $query = $this->db->pquery("SELECT *,actions.name as actionsid,actions.label as actionlabel"
                                    . "  from $this->table" . "entities entities
                                         INNER JOIN $this->table". "actions actions ON entities.dsid=actions.id
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         Left JOIN vtiger_actions ON vtiger_actions.actionsid=actions.name
                                         WHERE ext.name=? AND deleted=0 AND actions.block=? AND action_type=?
                                         ", array($currentModule,$idStep,$action_type));  
       while ($query && $row=$this->db->fetch_array($query)){
            $actionid=$row['actionsid'];//the real id of the action
            
            $label=$row['actionlabel'];
            $output_type=$row['output_type'];
            $mandatory_action=$row['mandatory_action'];
            $iconpath=$this->retrieveAttachment($actionid);
            $info[]=array('actionid'=>$actionid,'label'=>$label,'iconpath'=>$iconpath,
                'output_type'=>$output_type,'mandatory_action'=>$mandatory_action);
        }
        return $info;
    }
   
    /*
     * get Actions of specific module of type 
     * 
     */
    function getActionsOfModule($module) {
        global $currentModule;
        $res_logic=false;
        include_once('modules/Actions/Actions.php');
        $info=array();
        $moduleid=  getTabid($module);
        $query = $this->db->pquery("SELECT *,actions.name as actionsid,actions.label as actionlabel"
                                    . "  from $this->table" . "entities entities
                                         INNER JOIN $this->table". "actions actions ON entities.dsid=actions.id
                                         INNER JOIN $this->table". "extensions ext ON ext.name=entities.name
                                         Left JOIN vtiger_actions ON vtiger_actions.actionsid=actions.name
                                         WHERE ext.name=? AND deleted=0 AND actions.action_module=? AND actions.action_type=?
                                         ", array($currentModule,$moduleid,'OnSaveEntity'));  
       while ($query && $row=$this->db->fetch_array($query)){
            $actionid=$row['actionsid'];//the real id of the action
            $actionfocus=CRMEntity::getInstance("Actions");
            $actionfocus->retrieve_entity_info($actionid,"Actions");                                
            if($actionfocus->column_fields['linktobrules']!=''){
                $res_logic=$actionfocus->runBusinessLogic($this->record); 
            }
            if($res_logic || $actionfocus->column_fields['linktobrules']==''){
                $label=$row['actionlabel'];
                $output_type=$row['output_type'];
                $mandatory_action=$row['mandatory_action'];
                $iconpath=$this->retrieveAttachment($actionid);
                $info[]=array('actionid'=>$actionid,'label'=>$label,'iconpath'=>$iconpath,
                    'output_type'=>$output_type,'mandatory_action'=>$mandatory_action);
            }
        }
        return $info;
    }
    
    function retrieveAttachment($id) {
        global $adb;
        $newLinkicon='';
        $attachments = $adb->pquery('select vtiger_crmentity.crmid,vtiger_attachments.path,name
                        from vtiger_seattachmentsrel
                        inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seattachmentsrel.attachmentsid
                        inner join vtiger_attachments on vtiger_crmentity.crmid=vtiger_attachments.attachmentsid
                        where vtiger_seattachmentsrel.crmid=? ', array($id));
        if($attachments && $adb->num_rows($attachments)>0){
            $attachmentid = $adb->query_result($attachments,0,'crmid');
            $path = $adb->query_result($attachments,0,'path');
            $name = $adb->query_result($attachments,0,'name');
            $newLinkicon =$path.$attachmentid."_".$name;
        }
        return $newLinkicon;
}

    function getFatherDetailViewBlocks($config,$ngblockid){
        
        global $current_user,$adb;
        $fatherId='';$DetailViewBlocks='';
        $currentRecordMasterModule=  getSalesEntityType($this->record);
        $result=$adb->pquery("Select * from vtiger_ng_block "
                . " where id=?",array($ngblockid));
        $parentname          =$adb->query_result($result,0,'pointing_module_name');
        $pointing_field      =$adb->query_result($result,0,'pointing_field_name');
        require_once("modules/$currentRecordMasterModule/$currentRecordMasterModule.php");
        $focus= CRMEntity::getInstance($currentRecordMasterModule);
        $focus->id=$this->record;
        $focus->retrieve_entity_info($this->record,"$currentRecordMasterModule");
        $fatherId=$focus->column_fields["$pointing_field"];
        $DetailViewBlocks=$this->getModuleDetailViewBlocksFather($parentname,$fatherId);
//        foreach($config as $key=>$value){
//            if($key==$currentRecordMasterModule){
//                $parentname     =$value['parentname'];
//                $pointing_field =$value['pointing_field'];
//                require_once("modules/$currentRecordMasterModule/$currentRecordMasterModule.php");
//                $focus= CRMEntity::getInstance($currentRecordMasterModule);
//                $focus->id=$this->record;
//                $focus->retrieve_entity_info($this->record,"$currentRecordMasterModule");
//                $fatherId=$focus->column_fields["$pointing_field"];
//                $DetailViewBlocks=$this->getModuleDetailViewBlocksFather($parentname,$fatherId);                
//                break;
//            }
//        }        
        return array('FatherId'=>$fatherId,'FatherDetailViewBlocks'=>$DetailViewBlocks);
    }

function retrieveAutoCompleteData($val,$fld,$cachedmap){
        global $current_user,$adb,$currentModule;

        $result=array();
        $resArray=array();
        $cmapout = array();
      if(!empty($val)){
        $originflds=array();
        $targetflds=array();
        $targetflds2=array();
        $automatic=array();
        if($cachedmap!="undefined"){
            $cmaparr=json_decode($cachedmap,true);
            
            $originflds=$cmaparr['originflds'];
            $targetflds=$cmaparr['targetflds'];
            $targetflds2=$cmaparr['targetflds2'];
            $automatic=$cmaparr['automatic'];
            $tbl_name = $cmaparr['tbl_name'];
            $tbl_index = $cmaparr['tbl_index'];
            $sfld=$cmaparr['lookup_field'];
        }

        else{
        $mapname='map_mapping_'.$fld.'_'.$currentModule; 
        $mapqry="SELECT cbmapid FROM vtiger_cbmap INNER JOIN vtiger_crmentity ON vtiger_cbmap.cbmapid=vtiger_crmentity.crmid WHERE maptype='Mapping' AND deleted=0 AND mapname=?";
        $mapqryres=$adb->pquery($mapqry,array($mapname));
        if($adb->num_rows($mapqryres)>0){
         $mapid=$adb->query_result($mapqryres,0,0);
        }

        $focus1 = CRMEntity::getInstance("cbMap");
        $focus1->retrieve_entity_info($mapid, "cbMap");

        $target_fields = $focus1->readTableMappingType();
        $origin_module = $focus1->getMapOriginModule();
        $target_module = $focus1->getMapTargetModule();
        require_once("modules/$target_module/$target_module.php");
        $target_focus = CRMEntity::getInstance($target_module);
        $tbl_name = $target_focus->table_name;
        $tbl_index = $target_focus->table_index;
        $flds='';
        foreach ($target_fields['target'] as $key => $value) {
           if (!empty($value['value'])) {
            if(empty($flds)){
                $flds=$value['value'];
            }
            else{
               $flds=$flds.','.$value['value']; 
            }
            if(strpos($value['value'], $fld)!==false){
                  $sfld=$key;
            }
              $targetflds[$key]=$value['value'];
              $automatic[$key]=$value['automatic'];
              $targetflds2[$key]=explode(",",$value['value']);
           }
          }
        $cmapout['originflds']=$originflds;
        $cmapout['targetflds']=$targetflds;
        $cmapout['targetflds2']=$targetflds2;
        $cmapout['automatic']=$automatic;
        $cmapout['tbl_name']=$tbl_name;
        $cmapout['tbl_index']=$tbl_index;
        $cmapout['lookup_field']=$sfld;
        $cmapout['fieldlist']=explode(",",$flds);
        $result['cachedmap']=$cmapout;
        }
        
        $sqlflds=array_keys($targetflds);
        $qry="SELECT ".implode(",",$sqlflds)." FROM ".$tbl_name." INNER JOIN vtiger_crmentity ON vtiger_crmentity.crmid=".$tbl_name.'.'.$tbl_index." WHERE vtiger_crmentity.deleted=0 and ".$sfld." LIKE CONCAT( ?, '%') GROUP BY ".$sfld;
        $qryres =$adb->pquery($qry,array($val));

        while ($rec = $adb -> fetch_array($qryres)) {
            $tempRec=array();
            foreach ($rec as $key => $value) {
                foreach($targetflds2[$key] as $k => $v){
                    if(!empty($automatic[$key])){
                        $tempRec[$v]=$automatic[$key];
                    }
                    elseif(!empty($v)){
                        $tempRec[$v]=$value;
                    }
               }
            }
            $resArray[]=$tempRec;
         }
        $result['resdata']=$resArray;
      }
      return $result;
    }
    
    function retrieveRole($id)
    {
        $rolename= getRoleName($id);
        return $rolename;   
    }
    function retrieveUserName($type)
    {
        $prefix='AL';
        switch($type){
            case '1':$prefix='AG'; break;
            case '2':$prefix='AN'; break;
            case '3':$prefix='RS'; break;
            case '4':$prefix='TS'; break;
            case '5':$prefix='AL'; break;
            case '6':$prefix='SE'; break;
            case '7':$prefix='TE'; break;
        }
        global $adb;
        $query = "select *
             from vtiger_users 
             where user_name like ? ";
        $params = array($prefix.'%');  
        $result=$adb->pquery($query,$params);
        $nr=$adb->num_rows($result);
        $prefixExisting=0;
        for($i=0;$i<$nr;$i++){
            $user_name=$adb->query_result($result,$i,'user_name');
            $numbers= intval(substr($user_name, 2, 4));
            if(is_numeric($numbers) && $numbers > 0){
                $prefixExisting++;
            }
        }
        $prefixExisting++;
        $prefixExisting= str_pad($prefixExisting, 4,"0",STR_PAD_LEFT);
        return array('name'=>$prefix.$prefixExisting);
    }
    
}

?>
