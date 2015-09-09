<?php

global $adb,$current_language,$current_user,$root_directory,$log,
        $app_strings, $mod_strings, $current_language, $currentModule, $theme;

$kaction=$_REQUEST['kaction'];
$profile=$_REQUEST['profile'];
$search_str=$_REQUEST['search_str'];
$id=$_REQUEST['id'];
$app='app172';//$_REQUEST['app'];

if($kaction=='display')
{
require_once('Smarty_setup.php');
$smarty = new vtigerCRM_Smarty;
$module_selected = $_REQUEST['module_selected'];
$module_related = $_REQUEST['module_related'];
$tabid = getTabid($module_selected);
$focus = CRMEntity::getInstance($module_selected);

if(isset($_REQUEST['id']) && $_REQUEST['id']!='') {
    $focus->retrieve_entity_info($_REQUEST['id'],$module_selected);
}


$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
 
$smarty->assign("BLOCKS", getBlocks_vtapp_form_Project($module_selected,"detail_view",'',$focus->column_fields));
$smarty->assign('ID', $_REQUEST['id']);
$value_ui10='';
$ui10_arr_fields=array('progetto'=>'projectname',
        'linktocondition'=>'linktosymptom',
   'linkntosymptom'=>'de_symptom',
    'linktobuyer'=>'accountname',);
$query_field=$adb->query("SELECT vtiger_field.fieldname,vtiger_field.fieldlabel,vtiger_field.uitype,vtiger_fieldmodulerel.module,vtiger_fieldmodulerel.relmodule
                          from vtiger_field
                          LEFT JOIN vtiger_fieldmodulerel ON vtiger_field.fieldid = vtiger_fieldmodulerel.fieldid
                          where fieldname in ('serial_number','model_number','progetto','projectname','customer_purchase_data',
     'logistictype','tiposerviceevent','linktocondition','linktosymptom','linktobuyer' )
                          and tabid='$tabid'");
      $count_field=$adb->num_rows($query_field);
      
          for($j=0;$j<$count_field;$j++){
              
              $select='';
              if($adb->query_result($query_field,$j,'fieldname')=='assigned_user_id' || $adb->query_result($query_field,$j,'fieldname')=='createdtime'
                      || $adb->query_result($query_field,$j,'fieldname')=='campaign')
                      continue;
              $mod=abs($nr-4)%4;
              if($mod==0) 
                  {$class='class="alert alert-info"';
                  $image_class='class="fa fa-envelope-o"';
                  }
              elseif($mod==1) 
                  {$class='class="alert alert-danger"';
                  $image_class='class="fa fa-facebook"';
                  }
              elseif($mod==2) 
                  {$class='class="alert alert-success"';
                  $image_class='class="fa fa-comments-o"';
                  }
              elseif($mod==3) 
                  {$class='class="alert alert-warning"';
                  $image_class='class="fa fa-bell-o"';
                  }
              $fldname=$adb->query_result($query_field,$j,'fieldname');
              $fieldlabel=$adb->query_result($query_field,$j,'fieldlabel');
              $uitype=$adb->query_result($query_field,$j,'uitype');
              $relmodule=$adb->query_result($query_field,$j,'relmodule');
              $module=$adb->query_result($query_field,$j,'module');
              if(in_array($fldname, array('progetto','linktocondition','linktosymptom','linktobuyer')))
              {
                  $value_ui10=$fldname;
                  $fldname=$fldname.'_display';
              }
                            
               $content2[]=array("field"=>getTranslatedString($fieldlabel, $module),
                           "fieldname"=>$fldname, 
                           "value_ui10"=>$value_ui10, 
                           "class1"=>$class,
                           "uitype"=>$uitype,
                           "select"=>$select,
                          "image_class"=>$image_class,"nr"=>$mod);   
              
              $nr++;
          
      }
$role_to_edit=Array('H2','H19','H4','H5','H62','H63');
$curr_role=$current_user->roleid;
if(in_array($curr_role, $role_to_edit))
{
   $smarty->assign("edit",true); 
}
$smarty->assign("columns2",json_encode($content2));    
$smarty->assign("id",$id);
$smarty->assign("index",$_REQUEST['index']);
$smarty->assign('MODULE', $module_selected);
$smarty->assign('MODULE_RELATED', $module_related);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->display('modules/evvtApps/vtapp_hostess.tpl');
}

elseif($kaction=='retrieve' && $profile == 'first')
{
 $query=$adb->query(" arr[i]['fieldname']=='phone'
          SELECT DISTINCT *
              from vtiger_account 
              join vtiger_accountbillads on accountaddressid=accountid
              join vtiger_crmentity on crmid=accountid
              where deleted = 0 
              and ( accountname like '%$search_str%' )
          ");
      $count=$adb->num_rows($query);
    
      $query_field=$adb->query("SELECT vtiger_field.fieldname,vtiger_field.fieldlabel,visible
                          from vtiger_field
                          join vtiger_profile2field on vtiger_field.fieldid=vtiger_profile2field.fieldid
                          where vtiger_profile2field.tabid=6 and vtiger_profile2field.profileid=6 
                          and visible= 1");
      $count_field=$adb->num_rows($query_field);
      for($i=0;$i<$count;$i++){
          for($j=0;$j<$count_field;$j++){
              $fldname=$adb->query_result($query_field,$j,'fieldname');
              $content[]=array("field"=>$fldname,
                               "value"=>$adb->query_result($query,$i,$fldname) );
                  } 
      }
      
      
      echo json_encode($content);   
}

elseif($kaction=='retrieve' && $profile == 'second')
{
 $query=$adb->query(" 
          SELECT DISTINCT *
              from vtiger_account 
              join vtiger_accountbillads on accountaddressid=accountid
              join vtiger_crmentity on crmid=accountid
              where deleted = 0
              and (bill_street like '%$search_str%' or description like '%$search_str%' accountname like '%$search_str%')
          ");
      $count=$adb->num_rows($query);
    
      $query_field=$adb->query("SELECT vtiger_field.fieldname,vtiger_field.fieldlabel,visible
                          from vtiger_field
                          join vtiger_profile2field on vtiger_field.fieldid=vtiger_profile2field.fieldid
                          where vtiger_profile2field.tabid=6 and vtiger_profile2field.profileid=6 
                          and visible= 2");
      $count_field=$adb->num_rows($query_field);
      for($i=0;$i<$count;$i++){
          for($j=0;$j<$count_field;$j++){
              $fldname=$adb->query_result($query_field,$j,'fieldname');
              $content[$i][$fldname]=$adb->query_result($query,$i,$fldname);
                  } 
                  $content[$i]['id']=$adb->query_result($query,$i,'accountid');
      }
      
      
      echo json_encode($content);   
}
elseif($kaction=='autosuggest' )
{

$curr_group_qry="SELECT * FROM vtiger_users2group
join vtiger_users on vtiger_users2group.userid=vtiger_users.id
join vtiger_groups on vtiger_users2group.groupid=vtiger_groups.groupid
where vtiger_users.id=?";
$res=$adb->pquery($curr_group_qry,array($current_user->id));
$curr_group=Array();
for($j=0;$j<$adb->num_rows($res);$j++){
    $curr_group[$j]=$adb->query_result($res,$j,'groupid');
}
$val_uss='';
if(in_array('17977', $curr_group))
{
    $val_uss=" and smownerid = '$current_user->id' ";
}
$curr_role=$current_user->roleid;

$value_sent=$_GET["StartsWith"];
$module_selected=$_GET["module_selected"];
$log->debug('bakc '.$value_sent);
if($value_sent!='' && $module_selected=='Project')
{
    $val=" and brand= 'Sony' "
        . " and (seriale like '$value_sent%')";
    $table='vtiger_project';
    $fields='projectname,projectid,serial_number,model_number';
    $key='projectid';
}
if($value_sent!='' && $module_selected=='Accounts')
{
    $val=" and mbrand= 'Sony' "
            . " and (accountname like '$value_sent%' ) ";
    $table='vtiger_account';
    $fields='accountname,accountid';
    $key='accountid';
}

 $sql="Select $fields
        from  $table
        join vtiger_crmentity on crmid=$key
        where deleted=0   $val  $val_uss";
 $log->debug('bak3 '.$sql);
 $result=$adb->pquery($sql,array()); 
    for($i=0;$i<$adb->num_rows($result);$i++)
    {
       $name =$adb->query_result($result,$i,0);
       $id =$adb->query_result($result,$i,1);
       $serial_number =$adb->query_result($result,$i,2);
       $model_number =$adb->query_result($result,$i,3);
       
       $str=$name.' '.$serial_number.' '.$model_number;
       $c_val[]=array("str"=>$str,"name"=>$name,"id"=>$id);
    }
    if($adb->num_rows($result)==0)
        $c_val=array();
    echo json_encode($c_val);
}

elseif($kaction=='add_new' )
{
require_once('Smarty_setup.php');
$smarty = new vtigerCRM_Smarty;
$module_selected = $_REQUEST['module_selected'];
$module_related = $_REQUEST['module_related'];
$tabid = getTabid($module_selected);
$focus = CRMEntity::getInstance($module_selected);
$record = $_REQUEST['id'];
if($record!='') {
	$focus->id = $record;
	$focus->mode = 'edit';
	$focus->retrieve_entity_info($record, $module_selected);
}

if(empty($_REQUEST['id']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
        $acc_qry=$adb->pquery("Select vtiger_account.accountid,vtiger_account.accountname "
                . "from vtiger_users "
                . " join vtiger_account on vtiger_account.accountid=vtiger_users.account"
                . " where vtiger_users.id =?",array($current_user->id));
        $acc_id=$adb->query_result($acc_qry,0,'accountid');
        $acc_name=$adb->query_result($acc_qry,0,'accountname');
        $focus->column_fields['linktobuyer']=$acc_id;
        $focus->column_fields['linktobuyer_display']=$acc_name;
        $focus->column_fields['tiposerviceevent']='DEPREP';
        $focus->column_fields['vaioasc']='TEKNEMA';
        $focus->column_fields['level']='1';
}
$disp_view = getView($focus->mode);
if($_REQUEST['related_id']!='' && $_REQUEST['notes_title']=='compiled'){
    $focus_project = CRMEntity::getInstance($module_related);
    $focus_project->id = $_REQUEST['related_id'];
    $focus_project->retrieve_entity_info($_REQUEST['related_id'], $module_related);
    $focus->column_fields['notes_title']=$focus_project->column_fields['projectname'];
}


$smarty->assign('BASBLOCKS', getBlocks_vtapp_form_Project($module_selected, $disp_view, $focus->mode, $focus->column_fields, 'BAS'));
$smarty->assign('ID', $_REQUEST['id']);
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
 
$query_field=$adb->query("SELECT vtiger_field.fieldname,vtiger_field.fieldlabel,vtiger_field.uitype,vtiger_fieldmodulerel.module,vtiger_fieldmodulerel.relmodule
                          from vtiger_field
                          LEFT JOIN vtiger_fieldmodulerel ON vtiger_field.fieldid = vtiger_fieldmodulerel.fieldid
                          where ( 
      fieldname in ('serial_number','model_number','progetto','projectname','customer_purchase_data','logistictype','claimtype','tiposerviceevent','linktocondition','linktosymptom','linktobuyer','linktoaccountscontacts' ,'email1','nosurvey','noadverts','vaioasc','level','accountname','assigned_user_id','bill_street','bill_region','bill_state','bill_city','bill_code','phone','cellulare','nome','cognome')
                          and tabid='$tabid' )"
        . " OR ( tabid='$tabid'  and fieldname in ('notes_title','folderid','note_no','assigned_user_id','filelocationtype','filestatus','filename', 'filesize','filetype','fileversion','filedownloadcount','notecontent') )");
      $count_field=$adb->num_rows($query_field);
      $value_ui10='';
          for($j=0;$j<$count_field;$j++){
              
              if($adb->query_result($query_field,$j,'fieldname')=='createdtime'
                      || $adb->query_result($query_field,$j,'fieldname')=='campaign')
                      continue;
              
              $fldname=$adb->query_result($query_field,$j,'fieldname');
              $uitype=$adb->query_result($query_field,$j,'uitype');
              $fieldlabel=$adb->query_result($query_field,$j,'fieldlabel');
              if(in_array($fldname, array('progetto','linktocondition','linktosymptom','linktobuyer','linktoaccountscontacts','bill_region','bill_state','bill_city' )))
              {
                  $value_ui10=$fldname;
                  $fldname=$fldname.'_display';
                  }                 
              $content2[]=array(
                           "fieldname"=>$fldname, 
                           "uitype"=>$uitype,
                           "value_ui10"=>$value_ui10, 
                           );   
              $nr++;         
      }
$smarty->assign("columns2",json_encode($content2));
$smarty->assign("id",$id);
$smarty->assign("related_id",$_REQUEST['related_id']);
$smarty->assign("index",$_REQUEST['index']);
$smarty->assign('MODULE', $module_selected);
$smarty->assign('MODULE_RELATED', $module_related);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->display('modules/evvtApps/vtapp_add_new.tpl');
    
}
elseif($kaction=='save' )
{
    global $log,$adb,$file;
    $module_selected=$_REQUEST['module_selected'];
    $log->debug('klm_alb4 '.$module_selected);
    require_once "modules/$module_selected/$module_selected.php";
    $req1=$_REQUEST['req'];
    $val1=$_REQUEST['val'];
    $req=explode(';',$req1);
    $val=explode(';',$val1);

    if(isset($_REQUEST['id']) && $_REQUEST['id']!='') {
        $focus = CRMEntity::getInstance($module_selected);
        $focus->retrieve_entity_info($_REQUEST['id'],$module_selected);
        $focus->id = $_REQUEST['id'];
        $focus->mode = "edit";
    }
    else
    $focus = new $module_selected();
    
    for($i=0;$i<sizeof($req);$i++)
    {
      $up='';
      if($req[$i]==''  ) 
          continue; 
      $focus->column_fields[$req[$i]] = $val[$i];
      if($req[$i]=='accountname')
      $focus->column_fields['ragione_sociale'] = $val[$i];
      if($req[$i]=='assigned_user_id')
      $focus->column_fields['smownerid'] = $val[$i]; 
      $log->debug('klm_alb5 '.$req[$i].' '.$val[$i]);
      if($req[$i]=='linktoaccountscontacts'){
      $focus->column_fields['linktoaccountscontacts'] = $val[$i]; 
      $query = "SELECT vtiger_account.accountname, vtiger_account.email1,
			vtiger_account.email2, vtiger_account.website, vtiger_account.phone,
			vtiger_accountbillads.bill_city,c2.cityname,vtiger_account.codcat,vtiger_accountbillads.bill_code,
                        vtiger_accountbillads.bill_state,c3.countyname,vtiger_accountbillads.bill_street,
			vtiger_accountscf.*
			FROM vtiger_account
			INNER JOIN vtiger_crmentity
				ON vtiger_crmentity.crmid = vtiger_account.accountid
			INNER JOIN vtiger_accountbillads
				ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
                                inner join vtiger_cities  on citiesid=bill_city
			INNER JOIN vtiger_accountshipads
				ON vtiger_account.accountid = vtiger_accountshipads.accountaddressid
                             			INNER JOIN vtiger_accountscf
				ON vtiger_account.accountid = vtiger_accountscf.accountid
			LEFT JOIN vtiger_cities AS c2 on c2.citiesid=bill_city 
                        LEFT JOIN vtiger_counties AS c3 on c3.countiesid=bill_state 
                        where vtiger_account.accountid in (?)";
        $billcity1=$adb->pquery($query,array($focus->column_fields['linktoaccountscontacts']));
        if($adb->num_rows($billcity1)>0){
        $billcity2=$adb->query_result($billcity1,0,'cityname');
        $email1=$adb->query_result($billcity1,0,'email1');
        $mphone=$adb->query_result($billcity1,0,'phone');
        $codicecap=$adb->query_result($billcity1,0,'bill_code');
        $provincia=$adb->query_result($billcity1,0,'countyname');
        $indirizzo=$adb->query_result($billcity1,0,'bill_street');
        $focus->column_fields['ship_citypr'] = $billcity2; 
        $focus->column_fields['emailcontact'] = $email1; 
        $focus->column_fields['mphone'] = $mphone; 
        $focus->column_fields['ship_codepr'] = $codicecap; 
        $focus->column_fields['provincia'] = $provincia; 
        $focus->column_fields['ship_streetpr'] = $indirizzo; 
        }
      }
     
    }
    //saving document
    if(isset($_REQUEST['related_id']) && $_REQUEST['related_id'] != '')
    {
      $focus->parentid = $_REQUEST['related_id'];
      $_FILES['filename']=$_SESSION['file_upload_kendo'];
      $focus->column_fields['brand'] = 'Sony';
      $document->column_fields["filename"] =$_SESSION['file_upload_kendo']['name'];
      $document->column_fields["filelocationtype"]="I";
      $document->column_fields["fileversion"] = 1;
      $document->column_fields["filestatus"] = 1;
      $document->column_fields["filesize"]=169758;
      $document->column_fields["folderid"]=1;
    }
      
      $focus->column_fields['brand'] = 'Sony';
      $focus->column_fields['mbrand'] = 'Sony';
      $focus->column_fields['bill_country'] = 1009;
      $focus->column_fields['newsletter'] = 1;  
      $focus->column_fields['accounttype'] = 'Cliente finale';  
      $focus->column_fields['startdate'] = date("Y-m-d"); 
      if($focus->column_fields['progetto']!='')
      { 
        $query_iw=$adb->pquery("select iwyes"
              . " from vtiger_project "
              . " where projectid=?",array($focus->column_fields['progetto'])); 
      $iwyes=$adb->query_result($query_iw,0,'iwyes');
      if($iwyes==1)
          $focus->column_fields['claimtype'] = 'WARRANTY';   
      }

      $log->debug('klm_alb9 ');
//      var_dump($focus->saveentity("$module_selected"));
      $focus->save("$module_selected");
      $nr_id=$focus->id;$log->debug('klm_alb6 '.$nr_id);
      if(isset($_REQUEST['related_id']) && $_REQUEST['related_id']!='') {
          $log->debug('klm_alb3 '."update vtiger_project "
              . " set linktoaccountscontacts=?"
              . " where projectid=?".$focus->id.$_REQUEST['related_id']);
         $query_field=$adb->pquery("update vtiger_project "
              . " set linktoaccountscontacts=?"
              . " where projectid=?",array($focus->id,$_REQUEST['related_id'])); 
      }
      $ret='';
      if($module_selected=='Project')
      {
        $focus1 = CRMEntity::getInstance($module_selected);
        $focus1->retrieve_entity_info($nr_id,$module_selected);
        $focus1->id = $nr_id;
        $focus1->mode = 'edit';
        
        if(strtotime($focus1->column_fields['customer_purchase_data']) < strtotime($focus1->column_fields['warrantyEndDate']))
        {
            $ret= ';date_error;'.date('d-m-Y',strtotime($focus1->column_fields['warrantyEndDate'] .' -2 years'));  
        }
        elseif( strtotime($focus1->column_fields['warrantyEndDate']) < strtotime($focus1->column_fields['startdate']) )
        {
            $ret= ';warranty_error;';  
            //$focus1->column_fields['ripetitiva_chiamata']='Alert'; 
            //$focus1->column_fields['motivi']=$focus1->column_fields['motivi'].'<br/> In attesa conferma garanzia. Manca la POP per fare la verifica'; 
            //$focus1->save("$module_selected");
        }
        //mass inizia
        $idlist=$nr_id;$log->debug('klm_alb3 '.$idlist);
        include('modules/Project/MassIniziaSony.php');
        shell_exec("cd $root_directory");
        $res = shell_exec("php cron/modules/sonywf/registerServiceEvent.php $nr_id");
        //echo $res;
        
      }  
      echo  $focus->id.$ret;
}
elseif($kaction=='upload' )
{
    global $log,$adb;
    $_SESSION['file_upload_kendo']=$file=$_FILES['filename'];
    $_SESSION['upload_status']=$file='true';
    
    $current_id = $adb->getUniqueID("vtiger_crmentity");
    $current_id=$current_id+2;

    $filename = ltrim(basename(" ".$_FILES['filename']['name'])); //allowed filename like UTF-8 characters 
    $filetype= $_FILES['filename']['type'];
    $filesize = $_FILES['filename']['size'];
    $filetmp_name = $_FILES['filename']['tmp_name'];

    //get the file path inwhich folder we want to upload the file
    $upload_file_path = decideFilePath();
    $upload_status = move_uploaded_file($filetmp_name,$upload_file_path.$current_id."_".$filename);
                    
    echo true;
}

