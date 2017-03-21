<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getMenuStructureWS($use) {
require_once('modules/cbMap/cbMap.php');
	global $adb;
	global $current_user;
        $rows=array('modules'=>'','profile'=>'');
        $u=explode('x',$use);
        $us=$u[1];
        require_once 'include/utils/UserInfoUtil.php';
        $userProfileArr = getUserProfile($current_user->id);
        $sql = 'SELECT * FROM vtiger_businessrules'
        . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
        . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
        . ' where ce.deleted=0  '
        . ' and maptype =? ';
        $result=$adb->pquery($sql,array('MENUSTRUCTURE'));
        for($c=0;$c<$adb->num_rows($result);$c++){
            $map=$adb->query_result($result,$c,'linktomap');
            if(!empty($map)){
                        $mapfocus=new cbMap();
                        $mapfocus->retrieve_entity_info($map, 'cbMap');
                        $rows=$mapfocus->getMapMenuStructure();
                        if(in_array($rows['profile'],$userProfileArr)){
                            break;
                        }
                    }
        }
        return $rows['modules'];
}

function customerModule($module,$fields,$pickpay,$limit,$filter,$orderby){
            global $adb, $log;
            $moduleRel=array();
            $arr_ret=array();
            $columnsJson=array();
            $pickpay1=explode('x',$pickpay);
            $pickpay=$pickpay1[1];
            $flds=implode(',',$fields);
            $flds=str_replace('assigned_user_id', 'smownerid', $flds);
            $sql = 'SELECT linktomap,businessrules_name'
                    . ' FROM vtiger_businessrules'
                    . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
                    . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
                    . ' where ce.deleted=0  '
                    . ' and maptype =? ';
            $result=$adb->pquery($sql,array('CUSTOMERTYPES'));
            $count=$adb->num_rows($result);
            if($count>0){
                    $map=$adb->query_result($result,0,'linktomap');
                    if(!empty($map)){
                        include_once 'modules/cbMap/cbMap.php';
                        $mapfocus=new cbMap();
                        $mapfocus->retrieve_entity_info($map, 'cbMap');
                        $rows=$mapfocus->getMapCustomerTypes();
                        $respmod=$rows['respmodule'];
                        include_once "modules/$respmod/$respmod.php";
                        $respfocus=new $respmod();
                        $resptable=$respfocus->table_name;
                        $resptableid=$respfocus->table_index;
                        $rel_mod=$rows['relmodule'];
                        $key=$module;
                        $value=$rel_mod[$key];
                        if($key=='KnowledgeBase2') $key='KnowledgeBase';
//                        foreach($rel_mod as $key=>$value){
                            include_once "modules/$key/$key.php";
                            require_once("modules/$key/language/it_it.lang.php");
                            $relfocus=new $key();
                            $reltable=$relfocus->table_name;
                            $reltableid=$relfocus->table_index;
                            $reltablecf=$relfocus->customFieldTable[0];
                            $reltablecfid=$relfocus->customFieldTable[1];
                            $result_ws = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = '$key'");
                            $ctowsid = $adb->query_result($result_ws,0,'id');
                            $rel_field=$value['field'];
                            $data_rel_module=$value['throughmodule'];
                            $step2=$value['throughmodule2'];
                            $throughfield2='';
                            $join_cf='';
                            if(Vtiger_Utils::CheckTable($reltablecf)) {
                                $join_cf=" left join $reltablecf on $reltablecf.$reltablecfid=$reltable.$reltableid";
                            }
                            if(sizeof($data_rel_module)>0){
                                foreach($data_rel_module as $keydata=>$valdata){
                                   $throughmodule=$keydata;
                                   include_once "modules/$throughmodule/$throughmodule.php";
                                   $steprelfocus=new $throughmodule();
                                   $stepreltable=$steprelfocus->table_name;
                                   $steprelid=$steprelfocus->table_index;
                                   $throughfield=$valdata; 
                                }
                                foreach($step2 as $keydata=>$valdata){
                                   $throughmodule=$keydata;
                                   include_once "modules/$throughmodule/$throughmodule.php";
                                   $steprelfocus=new $throughmodule();
                                   $stepreltable2=$steprelfocus->table_name;
                                   $steprelid2=$steprelfocus->table_index;
                                   $throughfield2=$valdata; 
                                }
                                if($key=='Documents'){
                                    $query=" from vtiger_notes"
                                            . " left join vtiger_notescf ON vtiger_notescf.notesid= vtiger_notes.notesid"			
                                            . " join vtiger_seattachmentsrel on vtiger_seattachmentsrel.crmid = vtiger_notes.notesid"
                                            . " join vtiger_attachments on vtiger_seattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid"
                                            . " join $stepreltable on $stepreltable.$steprelid=$reltable.$rel_field"
                                            . " join $resptable on $stepreltable.$throughfield=$resptable.$resptableid"
                                            . " join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_notes.notesid"
                                            . " join vtiger_crmentity c2 on c2.crmid=$stepreltable.$steprelid"
                                            . " where vtiger_crmentity.deleted=0 and c2.deleted=0 and $resptable.$resptableid=?"
                                            . " order by vtiger_crmentity.createdtime Desc";
                                    $selectNormal='Select vtiger_attachments.attachmentsid,title,vtiger_notes.notesid,vtiger_crmentity.* ';
                                    $selectCount='SELECT count(*) as total ';
                                    $queryCount=$selectCount.' '.$query;
                                    $query=$selectNormal.' '.$query;
                                    //$query.=' order by createdtime Desc';
                                    $result_set=$adb->pquery($query,array($pickpay));
                                }else{
                                    
                                    $condition=$value['condition'];
                                    foreach($condition as $keydata=>$valdata){
                                       $conditionfield=$keydata;
                                       $other_cond=$valdata;
                                       foreach($other_cond as $keydataOther=>$valdataOther){
                                           $conditionvalue=$valdataOther;
                                           $conditioncomparison=$keydataOther;
                                        }
                                    }
                                    if($throughfield2==''){
                                        $query=" from $reltable"
                                        . " join $stepreltable on $stepreltable.$steprelid=$reltable.$rel_field"
                                        . " join $resptable on $stepreltable.$throughfield=$resptable.$resptableid"
                                        . " join vtiger_crmentity on crmid=$reltable.$reltableid"
                                        . " join vtiger_crmentity c2 on c2.crmid=$stepreltable.$steprelid"
                                        . " where vtiger_crmentity.deleted=0 and c2.deleted=0 and $resptable.$resptableid=?"; 
                                    }
                                    else{
                                        $query=" from $reltable"
                                        . " join $stepreltable on $stepreltable.$steprelid=$reltable.$rel_field"
                                        . " join $stepreltable2 on $stepreltable2.$steprelid2=$stepreltable.$throughfield"
                                        . " join $resptable on $stepreltable2.$throughfield2=$resptable.$resptableid"
                                        . " join vtiger_crmentity on crmid=$reltable.$reltableid"
                                        . " join vtiger_crmentity c2 on c2.crmid=$stepreltable.$steprelid"
                                        . " where vtiger_crmentity.deleted=0 and c2.deleted=0 and $resptable.$resptableid=?";  
                                    }
                                    if($conditionfield!=''){
                                        if($conditioncomparison=='notempty'){
                                            $query=$query." and ( $conditionfield  <> '' AND  $conditionfield  is not null)  ";
                                        }
                                        else if($conditioncomparison=='empty'){
                                            $query=$query." and ( $conditionfield  = '' OR $conditionfield  is null )";
                                        }
                                        else{
                                            $query=$query." and $conditionfield  $conditioncomparison '$conditionvalue' ";
                                        }
                                    }
                                    $selectNormal="Select $reltable.*,vtiger_crmentity.*  ";
                                    $selectCount='SELECT count(*) as total ';
                                    $queryCount=$selectCount.' '.$query;
                                    $query=$selectNormal.' '.$query;
                                }
                            }
                            else{
                                $str_cf='';
                                if($key=='Cases'){
                                    $str_cf=' join vtiger_casescf on vtiger_cases.casesid=vtiger_casescf.casesid '
                                            . ' left join vtiger_products on vtiger_cases.productcase=vtiger_products.productid'
                                            . ' left join vtiger_productcf on vtiger_productcf.productid=vtiger_products.productid'
                                        ;
                                }
                                
                                if($key=='ServiceVehicle' ||  $key=='KnowledgeBase'){
//                                    $query="Select * from $reltable"
//                                            . " join $resptable on $reltable.$rel_field=$resptable.$resptableid"
//                                            .   $str_cf
//                                            . " join vtiger_crmentity on crmid=$reltable.$reltableid"
//                                            . " where deleted=0 and $resptable.$resptableid=?";
                                    $account = $adb->pquery('select *
                                            from vtiger_account 
                                            INNER JOIN vtiger_accountscf ON vtiger_accountscf.accountid = vtiger_account.accountid
                                            where vtiger_account.accountid=?',array($pickpay));
                                    $market='';
                                    if ($account) {
                                       $market = $adb->query_result($account,0,'cf_812');
                                    }
                                    if($key=='ServiceVehicle'){
                                        $query=" from $reltable"
                                                . " join vtiger_crmentity on crmid=$reltable.$reltableid"
                                                . " join vtiger_knowledgebase on vtiger_servicevehicle.coderecall=vtiger_knowledgebase.knowledgebaseid"
                                                . " join vtiger_knowledgebasecf on vtiger_knowledgebasecf.knowledgebaseid=vtiger_knowledgebase.knowledgebaseid"
                                                .   $join_cf   
                                                . " where deleted=0 and $pickpay=? and cf_812 like '%".$market."%'";
                                    }
                                    else{
                                        $query=" from $reltable"
                                                . " join vtiger_crmentity on crmid=$reltable.$reltableid"
                                                .   $join_cf   
                                                . " where deleted=0 and $pickpay=? and cf_812 like '%".$market."%'";                                        
                                    }
                                } 
                                else{
                                    $query=" from $reltable"
                                            . " join $resptable on $reltable.$rel_field=$resptable.$resptableid"
                                            .   $str_cf
                                            . " join vtiger_crmentity on crmid=$reltable.$reltableid"
                                            . " where deleted=0 and $resptable.$resptableid=?";
                                }
                                $condition=$value['condition'];
                                foreach($condition as $keydata=>$valdata){
                                   $conditionfield=$keydata;
                                   $other_cond=$valdata;
                                   foreach($other_cond as $keydataOther=>$valdataOther){
                                       $conditionvalue=$valdataOther;
                                       $conditioncomparison=$keydataOther;
                                    }
                                }
                                if($conditionfield!=''){
                                    if($key=='ServiceVehicle' && $where!='' && $conditioncomparison=='notempty'){
                                          $query.=" and proddetname like '$where' ";
//                                          $query.=" and $reltable.$reltableid in (Select coderecall "
//                                                  . " from  vtiger_servicevehicle "
//                                                  . " where proddetname like '%$where%')";
                                    } 
                                    else if($conditioncomparison=='notempty'){
                                        $query=$query." and ( $conditionfield  <> '' AND  $conditionfield  is not null)  ";
                                    }
                                    else if($conditioncomparison=='empty'){
                                        $query=$query." and ( $conditionfield  = '' OR $conditionfield  is null )";
                                    }
                                    else{
                                        $query=$query." and $conditionfield  $conditioncomparison '$conditionvalue' ";
                                }
                                }
                                if($key=='ServiceVehicle' ||  $key=='KnowledgeBase'){
                                    $query.=" and statuskb ='Publish' ";
                                }
                                $selectNormal="Select *  ";
                                $selectCount='SELECT count(*) as total ';
                                $queryCount=$selectCount.' '.$query;
                                $query=$selectNormal.' '.$query;
                            }
                            
                            $queryFilter='  ';
                            foreach($filter as $filterCol=>$filterValue){
                                if($filterValue!='')
                                $queryFilter.=" AND ".$filterCol ." like '%".$filterValue."%' ";
                            }
                            $queryOrder='  ';
                            if($orderby!=''){
                                $first_char=  substr($orderby, 0,1);
                                $order_column=  substr($orderby, 1);
                                $direction=($first_char=='-' ? 'DESC' : 'ASC');
                                if($order_column=='createdtime' || $order_column=='modifiedtime' || $order_column=='description'){
                                    $order_column="vtiger_crmentity.$order_column";
                                }
                                $queryOrder=" order by $order_column $direction";
                            }
                            else{
                                $queryOrder=($key=='Cases' ? ' ORDER BY FIELD( cf_987,"Waiting for an answer","Assigned","To be assigned","Resolved","Closed","FAQ" ) ' : " ");
                            }
                            
                            $query.=' '.$queryFilter.' ';
                            $queryCount.=' '.$queryFilter.' '.$queryOrder;
                            if($key=='Cases'){
                                $query.=$queryOrder;
                                $query.=' '.$limit.' ';
                                $result_set=$adb->pquery($query,array($pickpay));
                            }
                            else if($key!='Documents'){
                                $query.=($orderby!='' ? ' '.$queryOrder : ' order by vtiger_crmentity.createdtime Desc');
                                $query.=' '.$limit.' ';
                                $result_set=$adb->pquery($query,array($pickpay));
                            }
                            $queryTotal=$adb->pquery($queryCount,array($pickpay)); 
                            $total=$adb->query_result($queryTotal,0,'total');
                            $nr_rows=$adb->num_rows($result_set);
                            for($ret_id=0;$ret_id<$nr_rows;$ret_id++){
                                $relfocus->retrieve_entity_info($adb->query_result($result_set,$ret_id,$reltableid),$key);
                                $relfocus->id = $adb->query_result($result_set,$ret_id,$reltableid);
                                
                                for($col_id=0;$col_id<sizeof($fields);$col_id++){
                                    if($fields[$col_id]=='cf_936' || $fields[$col_id]=='knowledgebasename' || $fields[$col_id]=='startdatekb'){
                                        $arr_ret[$ret_id][$fields[$col_id]]=$adb->query_result($result_set,$ret_id,$fields[$col_id]);
                                    }
                                    elseif($fields[$col_id]=='createdtime' || $fields[$col_id]=='modifiedtime'){
                                        $arr_ret[$ret_id][$fields[$col_id]]=$adb->query_result($result_set,$ret_id,$fields[$col_id]);
                                    }
                                    else
                                    {
                                        $get_fld="Select fieldlabel,uitype "
                                        . " from vtiger_field"
                                        . " where fieldname=? or columnname=? ";
                                        $fld_res=$adb->pquery($get_fld,array($fields[$col_id],$fields[$col_id]));
                                        $fld_label=$adb->query_result($fld_res,0,'fieldlabel');
                                        $uitype=$adb->query_result($fld_res,0,'uitype');                                       
                                        if($key=='Documents'){
                                            $relfocus->name=$relfocus->column_fields['notes_title'];
                                        }
                                        $col_fields=$relfocus->column_fields;
//                                        $col_fields[$fields[$col_id]]=$adb->query_result($result_set,$ret_id,$fields[$col_id]);
                                        $block_info=getDetailViewOutputHtml($uitype,$fields[$col_id],'',$col_fields,'',getTabid($key),$key);
                                        $ret_val=$block_info[1];
                                        if(strpos($ret_val,'href')!==false) //if contains link remmove it because ng can't interpret it
                                        {
                                          $pos1=strpos($ret_val,'>');
                                          $first_sub=substr($ret_val,$pos1+1);
                                          $pos2=strpos($first_sub,'<');
                                          $log->debug('ret_val'.$first_sub.' '.$pos2);
                                          $sec_sub=substr($first_sub,0,$pos2);
                                          $ret_val=$sec_sub;
                                        }
                                        $originVal=$fields[$col_id].'_real';
                                        $arr_ret[$ret_id][$originVal]=$col_fields[$fields[$col_id]];
                                        $arr_ret[$ret_id][$fields[$col_id]]=html_entity_decode($ret_val);
                                    }
                                }
                                if($key=='Documents'){
                                    $attachmentid = $adb->query_result($result_set,$ret_id,'attachmentsid');
                                    $arr_ret[$ret_id]['fileid']=$attachmentid; 
                                    $arr_ret[$ret_id]['entityid']=$adb->query_result($result_set,$ret_id,$reltableid); 
                                    $arr_ret[$ret_id]['name']=$adb->query_result($result_set,$ret_id,'title');  
                                    $arr_ret[$ret_id]['id']=$ctowsid.'x'.$adb->query_result($result_set,$ret_id,$reltableid); 
                                }
                                else{
                                    if($key=='Cases'){
                                        $arr_ret[$ret_id]['cf_902']=$adb->query_result($result_set,$ret_id,'cf_902');
                                    }
                                    if($key=='InvoiceDetail'){
                                        $arr_ret[$ret_id]['selected_edocs_display'] =$adb->query_result($result_set,$ret_id,'selected_edocs');
                                        $arr_ret[$ret_id]['selected_eparts_display']=$adb->query_result($result_set,$ret_id,'selected_eparts');
                                        $arr_ret[$ret_id]['invoice_academy_display']=$adb->query_result($result_set,$ret_id,'invoice_academy');
                                        $arr_ret[$ret_id]['invoicetoservice_display']=$adb->query_result($result_set,$ret_id,'invoicetoservice');
                                    }
                                    elseif($key=='ServiceVehicle'){
                                        $result_wsKB = $adb->query("SELECT id FROM vtiger_ws_entity WHERE name = 'KnowledgeBase'");
                                        $ctowsidKB = $adb->query_result($result_wsKB,0,'id');
                                        $arr_ret[$ret_id]["coderecall_display"]=$ctowsidKB.'x'.$adb->query_result($result_set,$ret_id,'coderecall');
                                    }
                                    $arr_ret[$ret_id]['id']=$ctowsid.'x'.$adb->query_result($result_set,$ret_id,$reltableid); 
                                    $arr_ret[$ret_id]['name']=$adb->query_result($result_set,$ret_id,$fields[0]);  
                                }
                            }
                            for($col_id=0;$col_id<sizeof($fields);$col_id++){
                                $get_fld="Select fieldlabel,uitype "
                                . " from vtiger_field"
                                . " where fieldname=? or columnname=? ";
                                $fld_res=$adb->pquery($get_fld,array($fields[$col_id],$fields[$col_id]));
                                $fld_label=$adb->query_result($fld_res,0,'fieldlabel');
                                $columnsJson[]=array('headerName'=> $fld_label, 'field'=> $fields[$col_id],
                                                'width'=> 40);
                            }
                            $moduleRel[$key]=array('query'=>$query,
                                "record_set"=>$arr_ret,"columnsJson"=>$columnsJson
                                    ,"labels"=>array(),"total"=>$total);
//                        }
                    }
                }  
                return $moduleRel;
    }
        
function getMenuStructureWS1() {

	global $adb;
	global $current_user;
	$selectedModule = Array();
	$moreModule = Array();
	$tempModule = Array();
	$tempSelected = Array();
	$tempMore = Array();
	$resultant_array = Array();
	$notallowed = -1;
	$query = 'select tabid,name,tablabel,tabsequence,parent '
                . ' from vtiger_tab '
                . ' where parent is not null '
                . ' and parent!=" " and presence in (0,2) '
                . ' order by tabsequence';
	$result = $adb->pquery($query, array());
	require('user_privileges/user_privileges_' . $current_user->id . '.php');

	for ($i = 0; $i < $adb->num_rows($result); $i++) {
		$moduleName = $adb->query_result($result, $i, 'name');
		$moduleLabel = $adb->query_result($result, $i, 'tablabel');
		$tabid = $adb->query_result($result, $i, 'tabid');
		$parent = $adb->query_result($result, $i, 'parent');
		$tabsequence = $adb->query_result($result, $i, 'tabsequence');
		if (is_admin($current_user) || $profileGlobalPermission[2] == 0 || $profileGlobalPermission[1] == 0 || $profileTabsPermission[$tabid] === 0) {
			if ($tabsequence != $notallowed && count($selectedModule) <= 9) {
				if (!(in_array($moduleName, $tempModule))) {
					$selectedModule[] = Array($moduleName, $moduleLabel, $tabid, $parent);
					$tempSelected[] = $moduleName;
					$tempModule[] = $moduleName;
				}
			}

			if (!(in_array($moduleName, $tempModule))) {
				$moreModule[$parent][] = Array($moduleName, $moduleLabel, $tabid, $parent);
				$tempModule[] = $moduleName;
				$tempMore[] = $moduleName;
			}
		}
	}

	if (!in_array($selectModule, $tempSelected)) {
		if ((in_array($selectModule, $tempMore))) {
			array_push($selectedModule, Array($selectModule, $selectModule));
		}
	}

	$resultant_array['top'] = $selectedModule;
	$resultant_array['more'] = $moreModule;
	//return $resultant_array;
        return array("selectedmodule"=>$selectedModule,"moremodule"=>$moreModule);
}

function getMenuJSON() {
    global $adb,$log,$current_user;
        $marray=getMenuArray(0);
	$menubranch = '[';
	foreach ($marray as $item) {
		switch ($item['mtype']) {
			case 'menu':
				$menubranch.= '{text:"'.decode_html($item['mlabel']).'"';
				break;
			case 'module':
				$menubranch.= '{text:"'.decode_html($item['mlabel']).'",';
				$menubranch.= 'url:"index.php?action=index&module='.$item['mvalue'].'"';
				break;
			case 'url':
				$menubranch.= '{text:"'.decode_html($item['mlabel']).'",';
				$menubranch.= 'url:"'.$item['mvalue'].'"';
				break;
			case 'sep':
				$menubranch.= '{text:"<hr/>",encoded:false';
				break;
		}
		if (!empty($item['submenu']) and count($item['submenu'])>0) {
			$menubranch.= ',items:';
			$menubranch.= getMenuJSON($item['submenu']);
		}
		$menubranch.= '},';
	}
	return rtrim($menubranch,',').']';
}

function getMenuArray($mparent) {
	global $adb,$log,$current_user;
        //echo "erieri";
        //var_dump($mparent);
	require('user_privileges/user_privileges_'.$current_user->id.'.php');
	$menustructure = array();
	$menurs = $adb->query("select * from vtiger_evvtmenu where mparent = $mparent and mvisible=1 order by mseq");
	if ($menurs and $adb->num_rows($menurs)>0) {
		while ($menu = $adb->fetch_array($menurs)) {
			if (empty($menu['mpermission']) and $menu['mtype']=='module') {
				// apply vtiger CRM permissions
				if (isPermitted($menu['mvalue'],'index')=='no') continue;
			} elseif (!empty($menu['mpermission'])) {
				// apply evvtMenu permissions
				$usrprf = getUserProfile($current_user->id);
                                //echo "userprofile".$usrprf;
				$mperm = explode(',',$menu['mpermission']);
                                //echo 'hereherehere';
				if (!$is_admin and count(array_intersect($usrprf,$mperm))==0) continue;
			}
			switch ($menu['mtype']) {
				case 'menu':
				case 'url':
					$label = getTranslatedString($menu['mlabel'],'evvtMenu');
					break;
				case 'separator':
					$label = '';
					break;
				case 'module':
					$label = getTranslatedString($menu['mvalue'],$menu['mvalue']);
					break;
			}
			$menustructure[] = array(
				'evvtmenuid' => $menu['evvtmenuid'],
				'mtype' => $menu['mtype'],
				'mvalue' => $menu['mvalue'],
				'mlabel' => $label,
				'mseq' => $menu['mseq'],
				'mvisible' => $menu['mvisible'],
				'submenu' => getMenuArray($menu['evvtmenuid']),
			);
		}
	}
	return $menustructure;
}

?>

