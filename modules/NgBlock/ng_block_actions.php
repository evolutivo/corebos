<?php  
                require_once('include/utils/utils.php');
                include_once('vtlib/Vtiger/Utils.php');
                require_once('modules/cbMap/cbMap.php');
                require_once('modules/BusinessRules/BusinessRules.php');
                require_once('modules/NgBlock/NgBlock.php');

                global $adb,$db_use,$log; 
                $content=array();
                $kaction=$_REQUEST['kaction'];
                $id=$_REQUEST['id']; 
                $ng_block_id=$_REQUEST['ng_block_id']; 
                
                $a=$adb->pquery("SELECT *
                                  from vtiger_ng_block where 
                                  id =?",array($ng_block_id));
                $columns=$adb->query_result($a,0,'columns');
                //$columns=str_replace('smownerid', 'assigned_user_id', $columns);
                $cond=$adb->query_result($a,0,'br_id');
                $elastic_id=$adb->query_result($a,0,'elastic_id');
                $elastic_type=$adb->query_result($a,0,'elastic_type');
                $sort=$adb->query_result($a,0,'sort');
                $ng_module=$adb->query_result($a,0,'module_name');
                $pointing_module=$adb->query_result($a,0,'pointing_module_name');
                $tabid=  getTabid($pointing_module);
                $pointing_field_name=$adb->query_result($a,0,'pointing_field_name');
                                    
                $col= explode(",",$columns);
                $pointing_module_field=$pointing_field_name;
                require_once("modules/$ng_module/$ng_module.php");
                $ng=  CRMEntity::getInstance($ng_module);
                $ng_module_table=$ng->table_name;
                $ng_module_id=$ng->table_index;
                if($pointing_module!=''){
                    require_once("modules/$pointing_module/$pointing_module.php");
                    $pointing= CRMEntity::getInstance($pointing_module);
                    $pointing_module_table=$pointing->table_name;
                    $pointing_module_tablecf=$pointing->customFieldTable[0];
                    $pointing_module_tablecf_id=$pointing->customFieldTable[1];
                    $pointing_module_id=$pointing->table_index;
                }                
                // retrieve record data     
                if($kaction=='retrieve'){
                    $query_cond='';  
                    if($cond!='')
                     {
                        $businessrulesid = $cond;
                        $res_buss = $adb->pquery("select * from vtiger_businessrules where businessrulesid=?", array($businessrulesid));
                        $isRecordDeleted = $adb->query_result($res_buss, 0, "deleted");
                        if ($isRecordDeleted == 0 || $isRecordDeleted == '0') {
                            $br_focus = CRMEntity::getInstance("BusinessRules");
                            $br_focus->retrieve_entity_info($businessrulesid, "BusinessRules");
                            $mapid=$br_focus->column_fields['linktomap'];
                            $mapfocus=  CRMEntity::getInstance("cbMap");
                            $mapfocus->retrieve_entity_info($mapid,"cbMap");
                            $mapfocus->id=$mapid;
                            $businessrules_action=$mapfocus->getMapSQL(); 
                            $query_cond= " and  $businessrules_action  ";
                        }
                     }
                    if(!empty($sort) && $sort!= null && $sort!= ' '){
                        $so= explode(",",$sort);    
                        $sort_by=$so[0]; 
                        $order=$so[1];
                        $query_sort= " order by $sort_by  $order";
                    } 
                    $join_cf='';
                    if(Vtiger_Utils::CheckTable($pointing_module_tablecf)) {
                        $join_cf=" left join $pointing_module_tablecf on $pointing_module_tablecf.$pointing_module_tablecf_id=$pointing_module_table.$pointing_module_id";
                    }
                      $query=$adb->pquery(" 
                          SELECT $pointing_module_table.$pointing_module_id
                          FROM $ng_module_table t1
                          join $pointing_module_table on t1.$ng_module_id = $pointing_module_table.$pointing_module_field
                          $join_cf
                          join vtiger_crmentity on crmid = $pointing_module_table.$pointing_module_id
                          where deleted = 0 and t1.$ng_module_id=? "
                          . $query_cond ."  $query_sort",array($id));
                      $count=$adb->num_rows($query);
                       // var_dump($col); 

                      for($i=0;$i<$count;$i++){
                          if(!isPermitted($pointing_module, 'DetailView', $adb->query_result($query,$i,$pointing_module_id))) continue;
                          $content[$i]['id']=$adb->query_result($query,$i,$pointing_module_id);
                          $content[$i]['href']='index.php?module='.$pointing_module.'&action=DetailView&record='.$content[$i]['id'];
                          $focus_pointing= CRMEntity::getInstance($pointing_module);
                          $focus_pointing->id=$adb->query_result($query,$i,$pointing_module_id);
                          $focus_pointing->mode = 'edit';
                          $focus_pointing->retrieve_entity_info($adb->query_result($query,$i,$pointing_module_id), $pointing_module);
                          
                          for($j=0;$j<sizeof($col);$j++)
                          {
                              if($col[$j]=='') continue;
                              $a=$adb->query("SELECT *
                                      from vtiger_field
                                      WHERE ( columnname='$col[$j]' OR fieldname='$col[$j]' )"
                                      . " and tabid = '$tabid' ");
                                  $uitype=$adb->query_result($a,0,'uitype');
                                  $fieldname=$adb->query_result($a,0,'fieldname');
                                  $col_fields[$fieldname]=$focus_pointing->column_fields["$col[$j]"];
                                  $block_info=getDetailViewOutputHtml($uitype,$fieldname,'',$col_fields,'','',$pointing_module);
                                      $ret_val=$block_info[1];
                                      
                                  if(in_array($uitype,array(10,51,50,73,68,57,59,58,76,75,81,78,80,5,6,23,53,56)))
                                  {
                                      $content[$i][$col[$j]]=$col_fields[$fieldname]; 
                                      $content[$i][$col[$j].'_display']=$ret_val;
                                  }
                                  elseif(in_array($uitype,array(69,105,28,26)))//image fields & folderid
                                  {
                                      $content[$i]['preview']=retrieveAttachment($focus_pointing->id);
                                      $content[$i][$col[$j]]=$col_fields[$fieldname]; 
                                      $content[$i][$col[$j].'_display']=$ret_val;
                                  }
                                  else{
                                      $content[$i][$col[$j]]=$ret_val;
                                      $content[$i][$col[$j].'_display']=$ret_val;
                                  }
                          }
                      }
                        echo json_encode($content);

                }
                 // retrieve graph record data     
                elseif($kaction=='retrieve_graph'){
                     
                     if($cond!='')
                     {$query_cond= " and  $cond ";}
                     
                    $entity_field_arr=getEntityFieldNames($pointing_module);
                      $entity_field=$entity_field_arr["fieldname"];//var_dump();
                      if (is_array($entity_field)) {
                        $entityname=implode(",$pointing_module_table.",$entity_field);
                      } 
                     else {$entityname=$entity_field;}
                        
                    $query=$adb->pquery(" 
                          SELECT $pointing_module_table.$pointing_module_id
                          FROM $ng_module_table
                          join $pointing_module_table on $ng_module_table.$ng_module_id = $pointing_module_table.$pointing_module_field
                          join vtiger_crmentity on crmid = $pointing_module_table.$pointing_module_id
                          where deleted = 0 and $ng_module_table.$ng_module_id=? "
                          . $query_cond ."  $query_sort",array($id));
                          
                      $count=$adb->num_rows($query);
                      // var_dump($col); 
                    for($i=0;$i<$count;$i++){
                          $entityname_val='';
                          if (is_array($entity_field)) {
                              for($k=0;$k<sizeof($entity_field);$k++){
                                $entityname_val.=' '.$adb->query_result($query,$i,$entity_field[$k]);
                              }
                          } 
                          else{
                              $entityname_val=$adb->query_result($query,$i,$entityname);
                          }
                               
                          for($j=0;$j<sizeof($col);$j++)
                          {
                          if($col[$j]=='')
                               continue;
                            
                          $a=$adb->query("SELECT *
                                  from vtiger_field
                                  WHERE columnname='$col[$j]'"
                                  . " and tabid = '$tabid' ");
                              $uitype=$adb->query_result($a,0,'uitype');
                              $fieldname=$adb->query_result($a,0,'fieldname');
                              $col_fields[$fieldname]=$adb->query_result($query,$i,$col[$j]);
                              
                              $block_info=getDetailViewOutputHtml($uitype,$fieldname,'',$col_fields,'','',$pointing_module);

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
                              
                              if(in_array($uitype,array(10,51,50,73,68,57,59,58,76,75,81,78,80)))
                              {
                                  //$content[$i][$col[$j]]=$col_fields[$fieldname]; 
                                  $arr[$col[$j]][]=array("x"=>$entityname_val,"y"=>$ret_val);
                              }
                              else
                              $arr[$col[$j]][]=array("x"=>$entityname_val,"y"=>$ret_val);
                          }
                      } 
                      for($j=0;$j<sizeof($col);$j++)
                          {
                          if($col[$j]=='')
                               continue;
                          $content[$j]=array("key"=>$col[$j],"values"=>$arr[$col[$j]]);
                          }
                echo json_encode($content);
            }
                elseif($kaction=='retrieve_json'){

                    $ngBlockInst=new NgBlock();
                    $lines=$ngBlockInst->createElastic($elastic_id,$elastic_type);
                    echo json_encode($lines);
            }
                elseif($kaction=='create'){
                    require_once('modules/'.$pointing_module.'/'.$pointing_module.'.php');
                    $models=$_REQUEST['models'];
                    $mv=json_decode($models);
                    $focus = CRMEntity::getInstance("$pointing_module");
                    $focus->id='';
                    for($j=0;$j<sizeof($col);$j++)
                      {
                      if($col[$j]!='')
                         {
                          $a=$adb->query("SELECT fieldname
                              from vtiger_field
                              WHERE (columnname='$col[$j]' or fieldname='$col[$j]')
                              and tabid = '$tabid' ");
                          $fieldname=$adb->query_result($a,0,'fieldname');
                          $focus->column_fields[$fieldname]=$mv->$col[$j];  // all chosen columns
                         }
                      } 
                    $focus->column_fields['filelocationtype']='I';
                    $focus->column_fields['filestatus']='1';
                    $a=$adb->query("SELECT fieldname from vtiger_field
                             WHERE columnname='$pointing_field_name'");
                    $fieldname=$adb->query_result($a,0,"fieldname");
                    $focus->column_fields["$fieldname"]=$id;   //  the pointing field ui10
                    $log->debug('albana2 '.$entityname);
                    $entity_field_arr=getEntityFieldNames($pointing_module);
                    $entity_field=$entity_field_arr["fieldname"];
                    if (is_array($entity_field)) {
			$entityname=$entity_field[0];
		    }
                    else {$entityname=$entity_field;}
                    $focus->column_fields["$entityname"]=$mv->$col[0].' - '.$mv->$col[1];
                    if(empty($focus->column_fields['assigned_user_id']))
                    $focus->column_fields['assigned_user_id']=$current_user->id;
                    
                    $focus->save("$pointing_module"); 
    
                } 
                elseif($kaction=='edit'){
                    require_once('modules/'.$pointing_module.'/'.$pointing_module.'.php');
                    $models=$_REQUEST['models'];
                    $mv=json_decode($models);
                     
                    $focus = CRMEntity::getInstance("$pointing_module");
                    $focus->retrieve_entity_info($mv->id,$pointing_module);
                    $focus->id=$mv->id;
                    
                     $focus->mode='edit';
                     for($j=0;$j<sizeof($col);$j++)
                     {
                     if($col[$j]!='')
                         {
                             $a=$adb->query("SELECT fieldname
                                  from vtiger_field
                                  WHERE (columnname='$col[$j]' or fieldname='$col[$j]')
                                  and tabid = '$tabid' ");
                              $fieldname=$adb->query_result($a,0,'fieldname');
                             $focus->column_fields[$fieldname]=$mv->$col[$j];  // all chosen columns
                         }
                     } 
                     
                     $entity_field_arr=getEntityFieldNames($pointing_module);
                     $entity_field=$entity_field_arr["fieldname"];
                      if (is_array($entity_field)) {
			$entityname=$entity_field[0];
		      }
                      else {$entityname=$entity_field;}
                      $focus->column_fields["$entityname"]=$focus->column_fields["$entityname"];   //  the entityname field 
                     $focus->column_fields["assigned_user_id"]=$focus->column_fields["assigned_user_id"];
                     $focus->save("$pointing_module"); 
                }              
                elseif($kaction=='delete'){
                     require_once('modules/'.$pointing_module.'/'.$pointing_module.'.php');
                     $models=$_REQUEST['models'];
                     $mv=json_decode($models);
                     
                     $focus = CRMEntity::getInstance("$pointing_module");
                     $focus->retrieve_entity_info($mv->id,$pointing_module);
                     $focus->id=$mv->id;
                     $focus->mode='edit';echo $mv->id;
                     $log->debug('klm5 '.$focus->id);
                     $a=$adb->query("SELECT fieldname from vtiger_field
                            WHERE columnname='$pointing_field_name'");
                     $fieldname=$adb->query_result($a,0,"fieldname");
                     $focus->column_fields["$fieldname"]='';   //  the pointing field ui10
                     
                     echo $fieldname;
                     $log->debug('klm6 '.$fieldname);
                     $focus->save("$pointing_module"); 
    
                    }
                elseif($kaction=='select_entity')
                {
                    $entity_field_arr=getEntityFieldNames($pointing_module);
                    $entity_field=$entity_field_arr["fieldname"];//var_dump($entity_field);
                    if (is_array($entity_field)) {
                       $entityname=$entity_field[0];
                    } 
                    else {$entityname=$entity_field;}
                        
                    $query=$adb->pquery(" 
                          SELECT $entityname,$pointing_module_table.$pointing_module_id
                          FROM $pointing_module_table join vtiger_crmentity "
                            . " on crmid = $pointing_module_table.$pointing_module_id
                          where deleted = 0 and $pointing_field_name<> $id "
                            . " $query_cond limit 0,10",array());
                    $count=$adb->num_rows($query);

                    for($i=0;$i<$count;$i++){
                          $content[$i]['id']=$adb->query_result($query,$i,$pointing_module_id);
                          $content[$i]['name']=$adb->query_result($query,$i,$entityname);
                          
                    }
                    echo json_encode($content);
                }
                elseif($kaction=='setRelation')
                {
                    $relid=$_REQUEST['relid'];
                    $arr_ids=explode(',',$relid);
                        
                    $query=$adb->pquery(" 
                          Update $pointing_module_table "
                            . " set $pointing_field_name=$id"
                            . " where $pointing_module_id in (".  generateQuestionMarks($arr_ids).")"
                            ,array($arr_ids));
                    
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
?> 
