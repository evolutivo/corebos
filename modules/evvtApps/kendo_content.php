
<?php

global $adb,$current_language,$current_user,$root_directory,$log,
        $app_strings, $mod_strings, $current_language, $currentModule, $theme;

$kaction=$_REQUEST['kaction'];
$profile=$_REQUEST['profile'];
$search_str=$_REQUEST['search_str'];
$id=$_REQUEST['id'];

$app=$_REQUEST['appid'];
if($kaction=='moveStock'){
$productid=$_REQUEST['product'];
$stock_local=$adb->pquery("SELECT stockid
		           FROM vtiger_stock stock
                           INNER JOIN vtiger_crmentity ce ON ce.crmid=stock.stockid
                           INNER JOIN vtiger_location loc ON loc.locationid=stock.locationid
                           WHERE ce.deleted=0 AND loc.tipolocation='Locale' AND stock.productsid=?",array($productid));
if($adb->num_rows($stock_local)>0)
$localstockid=$adb->query_result($stock_local,0,'stockid');
$stock_kit=$adb->pquery("SELECT stockid
                           FROM vtiger_stock stock
                           INNER JOIN vtiger_crmentity ce ON ce.crmid=stock.stockid
                           INNER JOIN vtiger_location loc ON loc.locationid=stock.locationid
                           WHERE ce.deleted=0 AND loc.tipolocation='Car Kit' AND stock.productsid=?",array($productid));
if($adb->num_rows($stock_kit)>0)
$kitstockid=$adb->query_result($stock_kit,0,'stockid');
if(!empty($localstockid) && !empty($kitstockid)){
$adb->pquery("UPDATE vtiger_stock set qty=qty-1 WHERE stockid=?",array($localstockid));
$adb->pquery("UPDATE vtiger_stock set qty=qty+1 WHERE stockid=?",array($kitstockid));
echo "Stock recharged successfully";
}
else{
echo "Local stock or Car Kit stock is missing.";
}
}           
if($kaction=='display')
{
require_once('Smarty_setup.php');
$smarty = new vtigerCRM_Smarty;
$smarty->template_dir = 'modules/evvtApps/vtapps/'.$app;
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
 
$fields=getBlocks_vtapp_form($module_selected,"detail_view",'',$focus->column_fields,'',$_REQUEST['profile']);
$smarty->assign("BLOCKS", getBlocks_vtapp_form($module_selected,"detail_view",'',$focus->column_fields,'',$_REQUEST['profile']));
$smarty->assign('ID', $_REQUEST['id']);

          foreach($fields as $key_first => $value_first){
              
              foreach($value_first as $key_second => $value_second){
                  foreach($value_second as $key_third => $value_third){
                      
                      $fldname=$value_third['fldname'];
                      if($fldname!='' && $fldname!=null)
                      {
                      
                      $fldvalue=$value_third['value'];
                      $fieldlabel=$key_third;
                      $uitype=$value_third['ui'];
                      $content2[]=array("fieldlabel"=>getTranslatedString($fieldlabel, $module),
                                   "fieldname"=>$fldname, 
                                  // "value"=>$fldvalue, 
                                   "uitype"=>$uitype,
                                   );   

                      }
                  }
              }              
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
$smarty->assign("onsave_primary",$_REQUEST['onsave_primary']);
$smarty->assign("onsave_secondary",$_REQUEST['onsave_secondary']);
$smarty->assign("add_document",$_REQUEST['add_document']);
$smarty->assign("edit_this",$_REQUEST['edit_this']);
$smarty->assign("go_to_dtlview",$_REQUEST['go_to_dtlview']);
$smarty->assign("Profile",json_encode($_REQUEST['profile']));
$smarty->assign("appid",$_REQUEST['appid']);
$smarty->assign('MODULE', $module_selected);
$smarty->assign('MODULE_RELATED', $module_related);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);

$smarty->display('vtapp_hostess.tpl');
}

elseif($kaction=='retrieve' && $profile == 'first')
{
 $query=$adb->query(" 
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

$value_sent=$_GET["StartsWith"];
$module_selected=$_GET["module_selected"];
$log->debug('bakc '.$value_sent);
$val='';
$extra_field='';

$focus = CRMEntity::getInstance($module_selected);
$table=$focus->table_name;
if($focus->list_link_field!='')
{
    $extra_field=" ,$focus->list_link_field ";
    $extra_field2 =" OR  $focus->list_link_field like '$value_sent%'";
}
    
$fields="$focus->default_order_by,$focus->table_index $extra_field";
$key=$focus->table_index;

if($value_sent!='')
{
    $val=" and ($focus->default_order_by like '$value_sent%'  $extra_field2)";
    
}

 $sql="Select $fields
        from  $table
        join vtiger_crmentity on crmid=$key
        where deleted=0   $val  ";
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
$smarty->template_dir = 'modules/evvtApps/vtapps/'.$app;
$module_selected = $_REQUEST['module_selected'];
$module_related = $_REQUEST['module_related'];
$tabid = getTabid($module_selected);
$focus = CRMEntity::getInstance($module_selected);
$record = $_REQUEST['id'];

if($record && $record!='undefined') {
	$focus->id = $record;
	$focus->mode = 'edit';
	$focus->retrieve_entity_info($record, $module_selected);
}

if(empty($_REQUEST['id']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
}
if($module_selected=='Products')
{
//Tax handling (get the available taxes only) - starts
if($focus->mode == 'edit')
{
	$retrieve_taxes = true;
	$productid = $focus->id;
	$tax_details = getTaxDetailsForProduct($productid,'available_associated');
}

else
	$tax_details = getAllTaxes('available');

for($i=0;$i<count($tax_details);$i++)
{
	$tax_details[$i]['check_name'] = $tax_details[$i]['taxname'].'_check';
	$tax_details[$i]['check_value'] = 0;
}

//For Edit and Duplicate we have to retrieve the product associated taxes and show them
if($retrieve_taxes)
{
	for($i=0;$i<count($tax_details);$i++)
	{
		$tax_value = getProductTaxPercentage($tax_details[$i]['taxname'],$productid);
		$tax_details[$i]['percentage'] = $tax_value;
		$tax_details[$i]['check_value'] = 1;
		//if the tax is not associated with the product then we should get the default value and unchecked
		if($tax_value == '')
		{
			$tax_details[$i]['check_value'] = 0;
			$tax_details[$i]['percentage'] = getTaxPercentage($tax_details[$i]['taxname']);
		}
	}
}

$smarty->assign("TAX_DETAILS", $tax_details);
//Tax handling - ends
}
$disp_view = getView($focus->mode);

$fields=getBlocks_vtapp_form($module_selected, $disp_view, $focus->mode, $focus->column_fields, 'BAS',$_REQUEST['profile']);
$smarty->assign('BASBLOCKS', getBlocks_vtapp_form($module_selected, $disp_view, $focus->mode, $focus->column_fields, 'BAS',$_REQUEST['profile']));
$smarty->assign('ID', $_REQUEST['id']);
$validationData = getDBValidationData($focus->tab_name,$tabid);
$data = split_validationdataArray($validationData);

 $smarty->assign("VALIDATION_DATA_FIELDNAME",$data['fieldname']);
 $smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$data['datatype']);
 $smarty->assign("VALIDATION_DATA_FIELDLABEL",$data['fieldlabel']);
 
foreach($fields as $key_first => $value_first){
              
              foreach($value_first as $key_second => $value_second){
                  foreach($value_second as $key_third => $value_third){
                      
                      $fldname=$value_third['2']['0'];
                      
                      if($fldname!='' && $fldname!=null)
                      {
                      
                      $fldvalue=$value_third['3']['0'];
                      $fieldlabel=$value_third['1']['0'];
                      $uitype=$value_third['0']['0'];
                      $content2[]=array("fieldlabel"=>getTranslatedString($fieldlabel, $module),
                                   "fieldname"=>$fldname, 
                                   //"value"=>$fldvalue, 
                                   "uitype"=>$uitype,
                                   );   

                      }
                  }
              }              
             }
$smarty->assign("columns2",json_encode($content2));
$smarty->assign("id",$id);
$smarty->assign("related_id",$_REQUEST['related_id']);
$smarty->assign("index",$_REQUEST['index']);
$smarty->assign("onsave_primary",$_REQUEST['onsave_primary']);
$smarty->assign("onsave_secondary",$_REQUEST['onsave_secondary']);
$smarty->assign("add_document",$_REQUEST['add_document']);
$smarty->assign("edit_this",$_REQUEST['edit_this']);
$smarty->assign("go_to_dtlview",$_REQUEST['go_to_dtlview']);
$smarty->assign("Profile",json_encode($_REQUEST['profile']));
$smarty->assign("appid",$_REQUEST['appid']);
$smarty->assign('MODULE', $module_selected);
$smarty->assign('MODULE_RELATED', $module_related);
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->display('vtapp_add_new.tpl');
    
}
elseif($kaction=='save' )
{
    global $log,$adb;
    $module_selected=$_REQUEST['module_selected'];
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
      $req[$i]=str_replace(' ','',$req[$i]);
      
      $focus->column_fields[$req[$i]] = $val[$i];
      $log->debug('klm_alb1 '.$req[$i].' '.$val[$i]);
    }
      
      $focus->save("$module_selected");
      
      if(isset($_REQUEST['related_id']) && $_REQUEST['related_id']!='') {
          $log->debug('klm_alb3 '."update vtiger_project "
              . " set linktoaccountscontacts=?"
              . " where projectid=?".$focus->id.$_REQUEST['related_id']);
         $query_field=$adb->pquery("update vtiger_project "
              . " set linktoaccountscontacts=?"
              . " where projectid=?",array($focus->id,$_REQUEST['related_id'])); 
      }
}
elseif($kaction=='upload' )
{
    global $log,$adb;
    $_SESSION['file_upload_kendo']=$file=$_FILES['file_1'];
    $_SESSION['upload_status']='true';
    
    $current_id = $adb->getUniqueID("vtiger_crmentity");
    $current_id=$current_id+1;

    $filename = ltrim(basename(" ".$_FILES['file_1']['name'])); //allowed filename like UTF-8 characters 
    $filetype= $_FILES['file_1']['type'];
    $filesize = $_FILES['file_1']['size'];
    $filetmp_name = $_FILES['file_1']['tmp_name'];

    //get the file path inwhich folder we want to upload the file
    $upload_file_path = decideFilePath();
    $upload_status = move_uploaded_file($filetmp_name,$upload_file_path.$current_id."_".$filename);
                    
    echo true;
}


function getBlocks_vtapp_form($module,$disp_view,$mode,$col_fields='',$info_type='',$profileList)
{
	global $log;$log->debug('test_alb ');$log->debug($profileList);
	$log->debug("Entering getBlocks(".$module.",".$disp_view.",".$mode.",".$col_fields.",".$info_type.") method ...");
        global $adb,$current_user;
        global $mod_strings;
        $tabid = getTabid($module);
        $block_detail = Array();
        $getBlockinfo = "";
        $query="select blockid,blocklabel,show_title,display_status from vtiger_blocks where tabid=? and $disp_view=0 and visible = 0 order by sequence";
        $result = $adb->pquery($query, array($tabid));
        $noofrows = $adb->num_rows($result);
        $prev_header = "";
	$blockid_list = array();
	for($i=0; $i<$noofrows; $i++)
	{
		$blockid = $adb->query_result($result,$i,"blockid");
		array_push($blockid_list,$blockid);
		$block_label[$blockid] = $adb->query_result($result,$i,"blocklabel");
		
		$sLabelVal = getTranslatedString($block_label[$blockid], $module);
		$aBlockStatus[$sLabelVal] = $adb->query_result($result,$i,"display_status");
	}
	if($mode == 'edit')	
	{
		$display_type_check = 'vtiger_field.displaytype = 1';
	}elseif($mode == 'mass_edit')	
	{
		$display_type_check = 'vtiger_field.displaytype = 1 AND vtiger_field.masseditable NOT IN (0,2)';
	}else
	{
		$display_type_check = 'vtiger_field.displaytype in (1,4)';
	}
	
	/*if($non_mass_edit_fields!='' && sizeof($non_mass_edit_fields)!=0){
		$mass_edit_query = "AND vtiger_field.fieldname NOT IN (". generateQuestionMarks($non_mass_edit_fields) .")";
	}*/
	
	//retreive the vtiger_profileList from database
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	if($disp_view == "detail_view")
	{
//		if($is_admin == true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == "Users" || $module == "Emails")
//  		{
// 			$sql = "SELECT vtiger_field.*, '0' as readonly "
//                                . " FROM vtiger_field "
//                                . " WHERE vtiger_field.tabid=?"
//                                . "  AND vtiger_field.block "
//                                . "IN (" . generateQuestionMarks($blockid_list) . ") "
//                                . "AND vtiger_field.displaytype IN (1,2,4) "
//                                . "and vtiger_field.presence in (0,2) ORDER BY block,sequence";
//                            $params = array($tabid, $blockid_list);
//		}
//  		else
//  		{
  			
			//NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS	
			/*
 			$sql = "SELECT vtiger_field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid WHERE vtiger_field.tabid=? AND vtiger_field.block IN (". generateQuestionMarks($blockid_list) .") AND vtiger_field.displaytype IN (1,2,4) and vtiger_field.presence in (0,2) AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList) .") GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
			*/
                        $sql = "SELECT vtiger_field.*, vtiger_profile2field.readonly "
                                . " FROM vtiger_field "
                                . " INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid "
                                . " INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid "
                                . " WHERE vtiger_field.tabid=? AND vtiger_field.block"
                                . " IN (" . generateQuestionMarks($blockid_list) . ") "
                                . " AND vtiger_field.displaytype IN (1,2,4) and vtiger_field.presence in (0,2) "
                                . " AND vtiger_profile2field.visible=2 AND vtiger_def_org_field.visible=0 "
                                . " AND vtiger_profile2field.profileid IN (" . generateQuestionMarks($profileList) . ") "
                                . " GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
                            $params = array($tabid, $blockid_list, $profileList);		
			
			//Postgres 8 fixes
 			if( $adb->dbType == "pgsql")
 			    $sql = fixPostgresQuery( $sql, $log, 0);
//  		}
		$result = $adb->pquery($sql, $params);

		// Added to unset the previous record's related listview session values
		if(isset($_SESSION['rlvs']))
			unset($_SESSION['rlvs']);

		$getBlockInfo=getDetailBlockInformation($module,$result,$col_fields,$tabid,$block_label);
	}
	else
	{
//		if ($info_type != '')
//		{
//			if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2]== 0 || $module == 'Users' || $module == "Emails")
//  			{
//                        $sql = "SELECT vtiger_field.* "
//                                        . " FROM vtiger_field "
//                                        . " WHERE vtiger_field.tabid=? "
//                                        . " AND vtiger_field.block "
//                                        . " IN (" . generateQuestionMarks($blockid_list) . ") "
//                                        . " AND $display_type_check AND info_type = ? "
//                                        . " and vtiger_field.presence in (0,2) "
//                                        . " ORDER BY block,sequence";
//                            $params = array($tabid, $blockid_list, $info_type);
//			}
//  			else
//  			{
//  				$profileList = getCurrentUserProfileList();
//			//NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS	
//			/*
// 				$sql = "SELECT vtiger_field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  WHERE vtiger_field.tabid=? AND vtiger_field.block IN (". generateQuestionMarks($blockid_list) .") AND $display_type_check AND info_type = ? AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList) .") and vtiger_field.presence in (0,2) GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
//			*/
//                            $sql = "SELECT vtiger_field.*  "
//                                        . " FROM vtiger_field "
//                                        . " INNER JOIN vtiger_profile2field "
//                                        . " ON vtiger_profile2field.fieldid=vtiger_field.fieldid "
//                                        . " INNER JOIN vtiger_def_org_field "
//                                        . " ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  "
//                                        . " WHERE vtiger_field.tabid=? "
//                                        . " AND vtiger_field.block "
//                                        . " IN (" . generateQuestionMarks($blockid_list) . ") "
//                                        . " AND $display_type_check AND info_type = ? "
//                                        . " AND vtiger_profile2field.visible=0 "
//                                        . " AND vtiger_profile2field.readonly = 0 "
//                                        . " AND vtiger_def_org_field.visible=0 "
//                                        . " AND vtiger_profile2field.profileid "
//                                        . " IN (" . generateQuestionMarks($profileList) . ") "
//                                        . " and vtiger_field.presence in (0,2) "
//                                        . " GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
//				$params = array($tabid, $blockid_list, $info_type, $profileList);
//				//Postgres 8 fixes
// 				if( $adb->dbType == "pgsql")
// 				    $sql = fixPostgresQuery( $sql, $log, 0);
//  			}
//		}
//		else
//		{
//			if($is_admin==true || $profileGlobalPermission[1] == 0 || $profileGlobalPermission[2] == 0 || $module == 'Users' || $module == "Emails")
//  			{
//                            $sql = "SELECT vtiger_field.* FROM vtiger_field "
//                                        . "WHERE vtiger_field.tabid=? "
//                                        . "AND vtiger_field.block "
//                                        . "IN (" . generateQuestionMarks($blockid_list) . ") "
//                                        . "AND $display_type_check  "
//                                        . "and vtiger_field.presence in (0,2) "
//                                        . "ORDER BY block,sequence";
//				$params = array($tabid, $blockid_list);
//			}
//  			else
//  			{
  				
			//NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS	
			/*
 				$sql = "SELECT vtiger_field.* FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  WHERE vtiger_field.tabid=? AND vtiger_field.block IN (". generateQuestionMarks($blockid_list).") AND $display_type_check AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList).") and vtiger_field.presence in (0,2) GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
			*/
                        $sql = "SELECT vtiger_field.* "
                                        . " FROM vtiger_field "
                                        . " INNER JOIN vtiger_profile2field "
                                        . " ON vtiger_profile2field.fieldid=vtiger_field.fieldid "
                                        . " INNER JOIN vtiger_def_org_field "
                                        . " ON vtiger_def_org_field.fieldid=vtiger_field.fieldid  "
                                        . " WHERE vtiger_field.tabid=? AND vtiger_field.block "
                                        . " IN (" . generateQuestionMarks($blockid_list) . ") "
                                        . " AND $display_type_check AND vtiger_profile2field.visible=0 "
                                        . "  "
                                        . " AND vtiger_def_org_field.visible=0 "
                                        . " AND vtiger_profile2field.profileid "
                                        . " IN (" . generateQuestionMarks($profileList) . ") "
                                        . " and vtiger_field.presence in (0,2) "
                                        . " GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
				$params = array($tabid, $blockid_list, $profileList);
 				//Postgres 8 fixes
 				if( $adb->dbType == "pgsql")
 				    $sql = fixPostgresQuery( $sql, $log, 0);
//  			}	
//		}
		$result = $adb->pquery($sql, $params);
        $getBlockInfo=getBlockInformation($module,$result,$col_fields,$tabid,$block_label,$mode);	
	}
	$log->debug("Exiting getBlocks method ...");
	if(count($getBlockInfo) > 0)
	{
		foreach($getBlockInfo as $label=>$contents)
		{
			if(empty($getBlockInfo[$label]))
			{
				unset($getBlockInfo[$label]);
			}
		}
	}
	$_SESSION['BLOCKINITIALSTATUS'] = $aBlockStatus;
	return $getBlockInfo;
}

