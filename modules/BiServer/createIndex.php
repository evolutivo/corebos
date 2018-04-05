<?php
 $actionTodo = $_REQUEST['submitid'];
 $ip = GlobalVariable::getVariable('ip_elastic_server', '');
 $mvtype = $_REQUEST['mvtype'];
 $tab = $_REQUEST['nometab'];
 global $adb,$log,$mod_strings;
 include_once("modules/Reports/Reports.php");
 include_once("modules/Reports/ReportRun.php");
$indexlLog="modules/BiServer/BiIndexLog.log";

 $nr = $_REQUEST['nr'];
if($actionTodo != "createindex")
$val = $_REQUEST['count'];
 else $val = $_REQUEST['countlogg'];
if($mvtype == "report"){
 $reportId = $_REQUEST['reportId'];
 $sqlFields=Array();
 $join=Array();
 $where=Array();
 //create connection
  $pref = GlobalVariable::getVariable('ip_elastic_indexprefix', '');
  $index = $pref."_".$reportId."_".$tab;
  echo "INDEXNAME=".$index;
 $endpointUrl = "http://$ip:9200/$index/denorm/_mapping";
 $channel = curl_init();
   $handler = createBiServerScript("script_report_denorm_".$tab,"INDEXES");
   $data = "<?php \r\n";
   $data .= 'include_once("include/utils/CommonUtils.php");';
   $data .= ' include_once("modules/Reports/Reports.php");';
   $data .= ' include("modules/Reports/ReportRun.php");';
   $data .= "\n\r".'global $adb,$current_user;'."\r\n";
   $data .= "\n\r".'$current_user->id = 1;';
   $data .="\r\n".'$reportIndexFields = array();';
  curl_setopt($channel, CURLOPT_URL, $endpointUrl);
  curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($channel, CURLOPT_POST, false);
  curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
  curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
  $response = json_decode(curl_exec($channel));

  //get report selected fields
    $selectedReportColumns = array();
    $selectedReportColumnsLabel = array();
    $replaceColumnsContainingDots = array();
    $replacedColumnsContainingDots = array();
    $reportFieldsType = array();
    $k = 0;
    for($i=0;$i<$val;$i++)
    {
        //get selected fields
         $j= $i+1;
        if(isset($_REQUEST['checkf'.$j])){
            $selectedReportColumns[$i] = preg_replace('/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/','',$_REQUEST['field'.$j]);
            if(strpos($selectedReportColumns[$i],".") || strpos($selectedReportColumns[$i],"-"))
            {
              $replaceColumnsContainingDots[] = $selectedReportColumns[$i];
              $selectedReportColumns[$i] = str_replace('.', '', $_REQUEST['field'.$j]);
              $selectedReportColumns[$i] = str_replace('-', '', $selectedReportColumns[$i]);
              $replacedColumnsContainingDots[] = $selectedReportColumns[$i] ;
            }
            $colname[$i] = $_REQUEST['colname'.$j];
           $analyzedChecked = $_REQUEST['checkanalyzed'.$j];
           if($analyzedChecked == "on" || $analyzedChecked==1)
                 $analyzed = "analyzed";
            else $analyzed = "not_analyzed";
            $fldtabname = explode('.',$_REQUEST['colname'.$j]);
            $tablename = $fldtabname[0];
            $columnname = $fldtabname[1];
            $selectedReportColumnsLabel[$k]= preg_replace('/[#*. <,>]/','',$_REQUEST['modulfieldlabel'.$j]);
            $fldtype = $adb->query_result($adb->pquery("Select typeofdata from vtiger_field
                                          where columnname=? and tablename=?",array($columnname,$tablename)),0,'typeofdata');

            if(substr($fldtype,0,1) == 'N')
            {
                $coltype = 'double';
                $reportIndexFields[$selectedReportColumnsLabel[$k]]=array("type"=>$coltype);
                $data .="\r\n".'$reportIndexFields["'.$selectedReportColumnsLabel[$k].'"]=array("type"=>"'.$coltype.'");';
            }
            else if(substr($fldtype,0,1) == 'D')
            {
                $coltype='date';
                if(substr($fldtype,0,2) == 'DT') $format = 'yyyy-MM-dd HH:mm:ss';
                else $format = 'yyyy-MM-dd';
                $reportIndexFields[$selectedReportColumnsLabel[$k]]=array("type"=>$coltype,"format"=>"$format");
                $data .="\r\n".'$reportIndexFields["'.$selectedReportColumnsLabel[$k].'"]=array("type"=>"'.$coltype.'","format"=>"'.$format.'");';

            }
            else {
            $coltype = 'string';
            $reportIndexFields[$selectedReportColumnsLabel[$k]]=array("type"=>$coltype,"index"=>"$analyzed");
            $data.="\r\n".'$reportIndexFields["'.$selectedReportColumnsLabel[$k].'"]=array("type"=>"'.$coltype.'","index"=>"'.$analyzed.'");';
            }
            $table = $tablename;
            $sqlFields[$k] = $table.'.'.$columnname.' as '.$selectedReportColumnsLabel[$k];
            $k++;
            $modname[$i] = $_REQUEST['modul'.$i];
          }
    }
   $mvtableColumns = implode(",", $selectedReportColumns);
    //check if index exist
        if(count($response->denorm->mappings->$index)!=0)
        {
          $fields1=array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']'));
          $writeFields1 = '$fields1=array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" =>"[\'yyyy-MM-dd HH:mm:ss\', \'yyyy-MM-dd\',\'dd-MM-yyyy\',\'date_optional_time\', \'epoch_millis\']"));';

          //DELETE INDEX

//        $endpointUrl2 = "http://$ip:9200/$index/";
//        $channel11 = curl_init();
//        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
//        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
//        //curl_setopt($channel11, CURLOPT_POST, true);
//        curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "DELETE");
//        curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($fields1));
//        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
//        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
//        $response = json_decode(curl_exec($channel11));
          $endpointUrl = "http://$ip:9200/$index/denorm/_mapping?ignore_conflicts=true";
          $channel = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($channel, CURLOPT_URL, $endpointUrl);
            curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($channel, CURLOPT_POST, true);
            curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
            curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
            $response = json_decode(curl_exec($channel));
        }
        else
        {
        $fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']')));
        $writeFields1 = '$fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => "[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']")));';
        $endpointUrl = "http://$ip:9200/$index";
         $channel = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel, CURLOPT_URL, $endpointUrl);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($channel, CURLOPT_POST, true);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
        $response = json_decode(curl_exec($channel));
        }
        if($response->acknowledged==true)   $create=1;
        else $create=0;
      //CREATE BISERVER SCRIPT
      $data .= "\r\n".$writeFields1 ;
      $data .= writeIndexFieldsToFile(implode(",",$selectedReportColumnsLabel));
       //add data in elastic

        $endpointUrl2 = "http://$ip:9200/$index/denorm";
        $focus1=new ReportRun($reportId);
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow["tabid"])." ".$currencyfieldrow["fieldlabel"];
				$modprefixedlabel = str_replace(" ","_",$modprefixedlabel);

				if($currencyfieldrow["uitype"]!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
//Replace special characters on columns of report query
        $SQLforReport = preg_replace("/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/","",$focus1->sGetSQLforReport($reportId,$nu));
        $SQLforReport = str_replace($replaceColumnsContainingDots, $replacedColumnsContainingDots, $SQLforReport);
       //write report query for index creation
        $data .= writeIndexToFile($reportId,$ip,$index,1);
        $data .= writeCreateUpdateToFIle();
        fwrite($handler, $data);
//CREATING MV INDEX
       $fields1 = $adb->pquery("Select $mvtableColumns From ($SQLforReport) AS reportTable",array());
       $data = array();
       for($i=0; $i< $adb->num_rows($fields1); $i++){
           for($j=0;$j< count($selectedReportColumnsLabel);$j++)
           {
               $data[$selectedReportColumnsLabel[$j]] = $adb->query_result($fields1,$i,$j) ;
           }
        $channel11 = curl_init();
        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel11, CURLOPT_POST, true);
        //curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
        $response2 = curl_exec($channel11);
        }
 }
 else{
     //Create index from mv
     //create index -for logging index creation
    error_log("".date('Y-m-d H:i:s')." Create Index started\n ",3,$indexlLog);
    if($actionTodo == "createindex")
     $mapid = $_REQUEST['maploggingsql'];
    else $mapid = $_REQUEST['mapsql'];
    $SQLforMap = $adb->pquery("Select content,mapname,selected_fields from vtiger_cbmap where cbmapid = ?",array($mapid));
    $mapFields = str_replace('"','',html_entity_decode($adb->query_result($SQLforMap,0,"selected_fields"),ENT_QUOTES));
    //get fields from map SQL
    $mapSql = str_replace('"','',html_entity_decode($adb->query_result($SQLforMap,0,"content"),ENT_QUOTES));
    //function to get tabid

    $mapname = $adb->query_result($SQLforMap,0,"mapname");
    $sqlQueryfields = explode(",",$mapFields);

    $sqlFields=array();
    $selectedMapColumns = array();
    $elasticFields = array();
    $allFields = array();
    $pref = GlobalVariable::getVariable('ip_elastic_indexprefix', '');
    error_log("".date('Y-m-d H:i:s')."Action to do =".$actionTodo."\n ",3,$indexlLog);
    if($actionTodo == "createindex")
    $index = strtolower($pref.'_'.preg_replace('/[^A-Za-z0-9\-]/', '', $mapname));
    else     $index = strtolower($pref.'_'.preg_replace('/[^A-Za-z0-9\-]/', '',$tab ));
    error_log("".date('Y-m-d H:i:s')."Index name =".$index."\n ",3,$indexlLog);
    $k1=0;
    //Query to check for index existence
   $checkIndex = $adb->pquery("Select * from vtiger_elastic_indexes where elasticname = ?",array($index));
   $indexExist = $adb->num_rows($checkIndex);
   $handler = createBiServerScript("script_map_denorm_".$index,"INDEXES");
   $data = "<?php \r\n";
   $data .= 'include_once("include/utils/CommonUtils.php");';
   $data .= "\n\r".'global $adb;'."\r\n";
   $data .="\r\n".'$reportIndexFields = array();';
   $formatarr =array();
      //Write fileds structture to php file
     for($i=0;$i<$val;$i++)
     {
        //get selected fields
        $j= $i+1;
        if($actionTodo == "createindex") $checked = isset($_REQUEST['checkflogg'.$j]);
            else $checked = isset($_REQUEST['checkf'.$j]);
        if($checked){
            $selectedReportColumns[$k1] = preg_replace('/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/','',$_REQUEST['mapfield'.$j]);
            if(strpos($selectedReportColumns[$k1],".") || strpos($selectedReportColumns[$k1],"-"))
            {
              $replaceColumnsContainingDots[] = $selectedReportColumns[$i];
              $selectedReportColumns[$k1] = str_replace('.', '', $_REQUEST['mapfield'.$j]);
              $selectedReportColumns[$k1] = str_replace('-', '', $selectedReportColumns[$k1]);
              $replacedColumnsContainingDots[$k1] = $selectedReportColumns[$k1] ;
            }
            $colname[$k1] = $_REQUEST['colname'.$j];
            $analyzedChecked = $_REQUEST['checkanalyzed'.$j];
            //get Field type
            if($analyzedChecked == "on" || $analyzedChecked==1)
                $analyzed = "analyzed";
            else $analyzed = "not_analyzed";
            //get Field type
            $fldtabname = explode('.',$_REQUEST['colname'.$j]);
            $tablename = $fldtabname[0];
            $columnname = $fldtabname[1];
        $fldArr = explode(".",$colname[$k1]);
        $clname = preg_replace("/\s+/", "",$fldArr[1]);
        $log->debug("brisi".$clname."index==".$i);
        $selectedMapColumns[]= preg_replace('/[#*. <,>]/','',$_REQUEST['modulfieldlabel'.$j]);
        $tabname = trim(str_replace(range(0,9),'',$fldArr[0]));
        if($i == 0) $entityfield = $clname;
        $col = getColumnname('',$clname,$tabname);
        $fldtype =  $_REQUEST['modulfieldtype'.$j];
        if($fldtype != "") $col[1] = $fldtype;
        if(substr($col[1],0,1)=='N')
        {
            $coltype='double';
            $reportIndexFields[$selectedMapColumns[$k1]]=array("type"=>$coltype);
            $data .="\r\n".'$reportIndexFields["'.$selectedMapColumns[$k1].'"]=array("type"=>"'.$coltype.'");';
        }
        else if(substr($col[1],0,1)=='D')
        {
            $coltype='date';
            if(substr($col[1],0,2)=='DT') $format='yyyy-MM-dd HH:mm:ss';
            else $format='yyyy-MM-dd';
            $reportIndexFields[$selectedMapColumns[$k1]]=array("type"=>$coltype,"format"=>"$format");
            $data .="\r\n".'$reportIndexFields["'.$selectedMapColumns[$k1].'"]=array("type"=>"'.$coltype.'","format"=>"'.$format.'");';
            }
        else {
            $coltype='string';
            $reportIndexFields[$selectedMapColumns[$k1]]=array("type"=>$coltype,"index"=>"$analyzed");
            $data.="\r\n".'$reportIndexFields["'.$selectedMapColumns[$k1].'"]=array("type"=>"'.$coltype.'","index"=>"'.$analyzed.'");';
        }
            $sqlFields[$k1]=trim($fldArr[0]).'.'.$clname.' AS '.trim($fldArr[0]).'_'.$clname;
            $k1++;
     }
        $fieldtypearr[] = $coltype;
        $allFields[] = preg_replace('/[#*. <,>]/','',$_REQUEST['modulfieldlabel'.$j]);

     }

     for($i=0;$i<$val;$i++){
       if (isset($_REQUEST['checkf'.$i])){
         $check=1;
       }
     }


  /*  if($actionTodo == "createindex2" && $check==1) {
        $reportIndexFields["roles"]=array("type"=>"string");
           $data.="\r\n".'$reportIndexFields["roles"]=array("type"=>"string");';
    }*/



 function getDbTables($colname)
  {
   for ($k=0; $k<count($colname); $k++) {
      $fldtabname1[] = explode('.',$colname[$k]);
      $db[]=  $fldtabname1[$k][0];
      $db1[]=$fldtabname1[$k][1];
      $dbtables[]=substr($db[$k],0,-2);

      if(substr($dbtables[$k],0,4)=="CRM_") {
        $dbtables[$k]="vtiger_crmentity";
      }
      if($dbtables[$k]=="CR"){
        $dbtables[$k]="vtiger_crmentity";
      }
   }

return $dbtables;

}


function getRoles($id) {
    global $adb,$current_user;
    require_once("modules/Users/CreateUserPrivilegeFile.php");
    require_once("include/utils/GetUserGroups.php");
  //if($defaultOrgSharingPermission[getTabid("$moduleName")] == 3){
    $q=$adb->pquery("select smownerid from vtiger_crmentity  where crmid=?",array($id));
    $owner=$adb->query_result($q,0,"smownerid");
    $role=$adb->query("select parentrole,vtiger_role.roleid from vtiger_user2role join vtiger_role on vtiger_role.roleid=vtiger_user2role.roleid  where vtiger_user2role.userid=$owner");
    $current_user_roles=$adb->query_result($role,0,"roleid");
  //$roleid=$adb->query_result($role,0,"parentrole");
    $parrol=getParentRole($current_user_roles);
    $roleid=implode("::",$parrol);
    $userGroupFocus=new GetUserGroups();
    $userGroupFocus->getAllUserGroups($owner);
    $current_user_groups= $userGroupFocus->user_groups;
    if(count($current_user_groups)!=0)
    $grpid='::'.implode("::",$current_user_groups);
    $def_org_share=getAllDefaultSharingAction();
    $arr=getUserModuleSharingRoles($moduleName,$owner,$def_org_share ,$current_user_roles,$parrol,$current_user_groups);
    $gr=$adb->pquery("select * from vtiger_groups where groupid=?",array($owner));
    if($adb->num_rows($gr)==0){
    if(count(array_keys($arr['read']['ROLE']))!=0)
    $roleid.='::'.implode('::',array_keys($arr['read']['ROLE']));}
    else $roleid.=implode('::',array_keys($arr['read']['GROUP']));
    $roleid.='::'.$owner;

    return $roleid;
}


    $log->debug("ketu".$entityfield);

     //Get tabid
    $elasticTabid = getTabIdFromQuery($entityfield);
    $lanbelsToInsert = implode(",", $allFields);
    if($indexExist == 0){
    //Create connection
    if($actionTodo == "createindex")
    $endpointUrl2 = "http://$ip:9200/$index/_mapping/norm";
    else $endpointUrl2 = "http://$ip:9200/$index/_mapping/denorm";
    error_log("New index endpoint url = ".$endpointUrl2." \n",3,$indexlLog);
    $channel11 = curl_init();
    curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
    curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($channel11, CURLOPT_POST, false);
    curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
    curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
    $response = json_decode(curl_exec($channel11));
        if(count($response->$index->mappings->norm)!=0)
        {
          if($actionTodo == "createindex"){
          $fields1=array("norm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'yyyy-MM-dd\',\'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']'));
          $fields2=array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']'));
          }
          else
          $fields2=array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'yyyy-MM-dd\',\'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']'));
          if($actionTodo == "createindex"){
          $endpointUrl = "http://$ip:9200/$index/norm/_mapping?ignore_conflicts=true";
          $channel = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($channel, CURLOPT_URL, $endpointUrl);
            curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
            //curl_setopt($channel, CURLOPT_POST, true);
            curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
            curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
            $response = json_decode(curl_exec($channel));
          }
          $endpointUrl = "http://$ip:9200/$index/denorm/_mapping?ignore_conflicts=true";
          $channel = curl_init();
            curl_setopt($channel, CURLOPT_URL, $endpointUrl);
            curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields2));
            curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
            $response = json_decode(curl_exec($channel));
        }
       else
        {
          if($actionTodo == "createindex"){
        $fields1=array("mappings"=>array("norm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']'),"denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\', \'dd-MM-yyyy\', \'date_optional_time\']')));
        $writeFields1 = '$fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => "[\'yyyy-MM-dd HH:mm:ss\', \'yyyy-MM-dd\',\'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']")));';
        $endpointUrl = "http://$ip:9200/$index";
        $channel = curl_init();
        $typreofindex = "norm,denorm";
        curl_setopt($channel, CURLOPT_URL, $endpointUrl);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
        $response = json_decode(curl_exec($channel));
        }
        else{
        $fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => '[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']')));
        $writeFields1 = '$fields1=array("mappings"=>array("denorm"=>array("properties"=>$reportIndexFields, "dynamic_date_formats" => "[\'yyyy-MM-dd HH:mm:ss\',\'yyyy-MM-dd\', \'dd-MM-yyyy\', \'date_optional_time\', \'epoch_millis\']")));';
        $typreofindex = "denorm";
        $endpointUrl = "http://$ip:9200/$index";
        error_log("New index endpoint url  for index structure = ".$endpointUrl." \n",3,$indexlLog);
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $endpointUrl);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));
        curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel, CURLOPT_TIMEOUT, 1000);
        $response = json_decode(curl_exec($channel));
        }
      }
      //CREATE BISERVER SCRIPT

      $data .= "\r\n".$writeFields1 ;
      $data .= writeIndexFieldsToFile(implode(",",$selectedMapColumns));
      $data .= writeIndexToFile($mapSql,$ip,$index,0,$entityfield);
      $data .= writeCreateUpdateToFIle("map");
      fwrite($handler, $data);
      //END CREATE BISERVER SCRIPT
      //POPULATE INDEX WITH DATA

      $fields1 = $adb->pquery($mapSql,array());
      $data = array();


      $radioid=$_REQUEST['main'];
      $idrad=$radioid-1;
      $selcolno=count($colname);
      $allcolno=count($allFields);
      for($i=0;$i<$selcolno;$i++){
          $fld[]= explode('.',$colname[$i]);
          $columns[]=$fld[$i][1];
      }
      for($i=0;$i<$allcolno;$i++){
        $j=$i+1;
        $allcol[]=$_REQUEST['colname'.$j];
        $allfld[]= explode('.',$allcol[$i]);
        $allcolumns[]=$allfld[$i][1];
      }

      $alltables=getDbTables($allcol);
      $vtigertbls=getDbTables($colname);

      for($i=0; $i< $adb->num_rows($fields1); $i++){
          if($actionTodo == "createindex2" && $check==1) {
              if($idrad==0){
                  $recordid = $adb->query_result($fields1,$i,$idrad);
                   $data["roles"] = getRoles($recordid);
              } else {
                $tabdata = $adb->query_result($fields1,$i,$idrad);

                $res=array_intersect($allFields,$selectedMapColumns);
                $keys=array_keys($res);
                $keyno=count($keys);
                for($m=0;$m<$keyno;$m++){
                  if($idrad==$keys[$m]){
                    $idradd=$m;
                  } else {
                    $bool=0;
                  }
                }
                if($bool==0){
                    $select="SELECT* FROM $alltables[$idrad] WHERE $allcolumns[$idrad]='".$tabdata."'";
                    $que = $adb->pquery("show columns from $alltables[$idrad] where `Key` = 'PRI'",array());
                } else {
                  $select="SELECT* FROM $vtigertbls[$idradd] WHERE $columns[$idradd]='".$tabdata."'";
                  $que = $adb->pquery("show columns from $vtigertbls[$idradd] where `Key` = 'PRI'",array());
                }

                $result=$adb->query($select);
                $key=$adb->query_result($que);
                $recordid=$adb->query_result($result,0,$key);
                $data["roles"] = getRoles($recordid);
            }
}
           for($j=0;$j< count($selectedMapColumns);$j++)
           {
               $resultdata = $adb->query_result($fields1,$i,$j);
               if($fieldtypearr[$j] == 'date' && $resultdata=='')
                     $data[$selectedMapColumns[$j]] = null;
               else if($allFields[$j]!=$selectedMapColumns[$j]){
                   $res=array_intersect($allFields,$selectedMapColumns);
                   $keys=array_keys($res);
                   $resultdata = $adb->query_result($fields1,$i,$keys[$j]);
                   $data[$selectedMapColumns[$j]] = $resultdata;
               } else {
                   $data[$selectedMapColumns[$j]] = $resultdata ;
               }

           }

           $all[]=$data;
           $bulkSize=1000;
           if(!function_exists('jsonData')){
               function jsonData($dataarray){
                   $in[0]='{"index":{}}';
                   $json=json_encode($dataarray);
                   $json_nlines1=str_replace('\n','',$json);
                   $json_rep=str_replace('},','}\n',$json_nlines1);
                   $json_rep1=str_replace('\r','',$json_rep);
                   $json_rep2=str_replace('\"}\n','\"},',$json_rep1);
                   $finJson=substr($json_rep2, 1, -1);
                   $json_array=explode('\n',$finJson);
                   $no=count($json_array);
                   for($z=0;$z<$no;$z++){
                       array_splice($json_array,2*$z,0,$in);
                   }
                   $json_array[(2*$no)]='\n';
                   $JSON=join("\n",$json_array);

              return $JSON;
              }
           }


if(($i+1)%$bulkSize==0){

    if($actionTodo == "createindex"){
        $endpointUrl2 = "http://$ip:9200/$index/norm/_bulk?pretty";
      } else {
            $endpointUrl2 = "http://$ip:9200/$index/denorm/_bulk?pretty";
      }

        $bulkdata=jsonData($all);

        $channel11 = curl_init();
        //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
        curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($channel11, CURLOPT_POST, true);
        curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel11, CURLOPT_POSTFIELDS,$bulkdata);
        curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
        curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
        curl_setopt($channel11, CURLOPT_VERBOSE, true);
        $response2 = curl_exec($channel11);
        //echo ($response2);
        $all=array();
    }
    if($i==$adb->num_rows($fields1)-1){
      $x=($adb->num_rows($fields1))%$bulkSize;
      if($x==0){
        //do nothing
      } else {
        $bulkdata=jsonData($all);

      }

      if($actionTodo == "createindex"){
      $endpointUrl2 = "http://$ip:9200/$index/norm/_bulk?pretty";
      } else {
        $endpointUrl2 = "http://$ip:9200/$index/denorm/_bulk?pretty";
    }
      $channel11 = curl_init();
      //curl_setopt($channel1, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);
      curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($channel11, CURLOPT_POST, true);
      curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($channel11, CURLOPT_POSTFIELDS,$bulkdata);
      curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);
      curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);
      curl_setopt($channel11, CURLOPT_VERBOSE, true);
      $response2 = curl_exec($channel11);
    //  echo ($response2);

    }



        }
        insertElasticIndex($index,$lanbelsToInsert,$typreofindex);
        if($actionTodo == "createindex"){
        updateLoggingConf($index,$mapSql,$elasticTabid,$mapFields,$mapid);
        }
  }
  else{
      //index exist
      if($actionTodo == "createindex"){
          //Update Elastic Tables
          updateElasticLabels($index,$lanbelsToInsert);
          updateLoggingConf($index,$mapSql,$elasticTabid,$mapFields,$mapid);

          //Update Elastic Mapping
      }
  }
}
 function getColumnname($fieldid,$colname=null,$tablename=null)
 {
     global $adb;
    if($fieldid!=''){
    $q=$adb->pquery("select columnname,typeofdata,tablename from vtiger_field where fieldid=?",array($fieldid));
    }
    else
    $q=$adb->pquery("select columnname,typeofdata,tablename from vtiger_field where columnname=? and tablename=?",array($colname,$tablename));
    $arr[0]=$adb->query_result($q,0,0);
    $arr[1]=$adb->query_result($q,0,1);
    $arr[2]=$adb->query_result($q,0,2);
    return $arr;
  }

  function insertElasticIndex($indexname,$fields,$type){
      global $adb;
      $adb->pquery("Insert into vtiger_elastic_indexes(elasticname,status,fieldlabel,type) values(?,?,?,?)",array($indexname,"Open",$fields,$type));
  }

  function updateElasticLabels($indexname,$fields){
      global $adb;
      $sql = $adb->pquery("Select id from vtiger_elastic_indexes where elasticname = ?",array($indexname));
      $elasticId = $adb->query_result($sql,0,'id');
      $adb->pquery("Update vtiger_elastic_indexes set fieldlabel = ? where id = ?",array($fields,$elasticId));
  }

  function updateLoggingConf($index,$query,$elasticTabid,$mapFields,$mapid){
      global $adb;
      $adb->pquery("Update vtiger_loggingconfiguration set indextype=?,queryelastic=?, mapid =? where  tabid =?",array($index,$query,$mapid,$elasticTabid));
  }

  function getTabIdFromQuery($idfield){
      global $adb;
      //get entity field from query
      $sql = $adb->pquery("Select tabid from vtiger_entityname where entityidfield Like ?",array($idfield));
      $tabid = $adb->query_result($sql,0,'tabid');
      return $tabid;
  }

  function createBiServerScript($filename,$fileDir){
    global $root_directory;
    $my_file = $root_directory."modules/BiServer/$fileDir/$filename".".php";
    $handle = fopen($my_file, 'w') or die('Cannot open file:  '.$my_file);
    return $handle;
  }

  function writeIndexFieldsToFile($indexFieldsStructure){
      $data .= "\r\n".'$selectedMapColumns = explode(",","'.$indexFieldsStructure.'");';
      return $data;
  }

  function writeIndexToFile($sql,$ip,$index,$type,$bulkSize,$entityfield=''){
      $bulkSize=1000;
      $data .= "\r\n".'$ip= "'.$ip.'";';
      $data .= "\r\n".'$index= "'.$index.'";';
      $data .= "\r\n".'$entityfield= "'.$entityfield.'";';
      $data .= "\r\n".'$bulkSize= "'.$bulkSize.'";';
      if($type == 1){
        $data.='
$focus1=new ReportRun('.$sql.');
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow["tabid"])." ".$currencyfieldrow["fieldlabel"];
				$modprefixedlabel = str_replace(" ","_",$modprefixedlabel);

				if($currencyfieldrow["uitype"]!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}';
        $data .= "\r\n".'$SQLforReport = preg_replace("/[^A-Za-z0-9_=\s,<>%\'\'()!.:-]/","",$focus1->sGetSQLforReport('.$sql.',$nu));';
        $data .= "\r\n".'$SQLforReport = str_replace($replaceColumnsContainingDots, $replacedColumnsContainingDots, $SQLforReport);';
        $data .= "\r\n".'$mapSql= $SQLforReport;';
      }
    else $data .= "\r\n".'$mapSql= "'.$sql.'";';
                  //Delete index
    $data .= "\r\n".'$endpointUrl = "http://$ip:9200/$index";';
    $data .="\n\r".'$channel = curl_init();';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_URL, $endpointUrl);';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "DELETE");';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);';
    $data .= "\r\n".'curl_setopt($channel, CURLOPT_TIMEOUT, 1000);';
    $data .= "\r\n".'$response = json_decode(curl_exec($channel));';
   $data .="\n\r".'/**';
   $data .="\n\r".'*Index fields Structure ';
   $data .="\n\r".'*/';
   $data .="\n\r".'$endpointUrl = "http://$ip:9200/$index";';
   $data .="\n\r".'$channel = curl_init();';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_URL, $endpointUrl);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields1));';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_TIMEOUT, 1000);';
   $data .="\n\r".'$response = json_decode(curl_exec($channel));';
  $data .= "\r\n".'$fields = $adb->pquery($mapSql,array());';
  $data .= "\r\n".' for($i=0; $i< $adb->num_rows($fields); $i++)';
  $data .= "\r\n".'{';
  $data .= "\r\n".'for($j=0;$j< count($selectedMapColumns);$j++)';
  $data .= "\r\n".'{';
  $data .= "\r\n".'$type = $reportIndexFields[$selectedMapColumns[$j]][\'type\'];';
  $data .= "\r\n".'$format = $reportIndexFields[$selectedMapColumns[$j]][\'format\'];';
  $data .= "\r\n".'if($type == "date" && ($adb->query_result($fields,$i,$j) == "" || $adb->query_result($fields,$i,$j) == null))';
  $data .= "\r\n".'if($format == "yyyy-MM-dd HH:mm:ss") $data[$selectedMapColumns[$j]] = "1970-01-01 00:00:00"; ';
  $data .= "\r\n".' else $data[$selectedMapColumns[$j]] = "1970-01-01"; ';
  $data .= "\r\n else";
  $data .= "\r\n".'$data[$selectedMapColumns[$j]] = $adb->query_result($fields,$i,$j) ;';
  $data .= "\r\n".'}';
  $data.="\r\n".'$all[]=$data;';

  $data.="\r\n".'if(($i+1)%'.$bulkSize.'==0){';
  $data.="\r\n".'$bulkdata=jsonData($all);';
  $data.= "\n\r".'generateBiServerScript($ip,$index,$entityfield,$adb->query_result($fields,$i,0),$bulkdata);';
  $data.="\n\r".' $all=array();}';
  $data.="\n\r".'if($i==$adb->num_rows($fields)-1){';
  $data.="\n\r".'$x=($adb->num_rows($fields)-1)%'.$bulkSize.';';
  $data.="\n\r".'if($x==0){';
  $data.="\n\r".'//do nothing';
  $data.="\n\r".'} else {';
  $data.="\n\r".'$bulkdata=jsonData($all);';
  $data.="\n\r".'}';
  $data.="\n\r".'generateBiServerScript($ip,$index,$entityfield,$adb->query_result($fields,$i,0),$bulkdata);';
  $data .= "\r\n".'}';
  $data .= "\r\n".'}';
      return $data;
  }

  function writeCreateUpdateToFIle($inputtype=''){
   $data .="\n\r".'/**';
   $data .="\n\r".'* @param type $filename';
   $data .="\n\r".'* @param type $ip';
   $data .="\n\r".'* @param type $indextype';
   $data .="\n\r".'* @param type $entityfield';
   $data .="\n\r".'* @param type $recordId - Id of entity';
   $data .="\n\r".'*/';
   $data .="\n\r".'function generateBiServerScript($ip,$indextype,$entityfield,$recordId,$bulkdata){';
   $data .="\n\r".'global $adb;';
   if($inputtype == "map"){
   $data .="\n\r".'$endpointUrl = "http://$ip:9200/$indextype/denorm/_search?pretty";';
   $data .="\n\r".'$fields =array("query"=>array("term"=>array("$entityfield"=>"$recordId")));';
   $data .="\n\r".'$channel = curl_init();';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_URL, $endpointUrl);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_RETURNTRANSFER, true);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_POST, true);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($fields));';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_CONNECTTIMEOUT, 100);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_SSL_VERIFYPEER, false);';
   $data .="\n\r".'curl_setopt($channel, CURLOPT_TIMEOUT, 1000);';
   $data .="\n\r".'$response = json_decode(curl_exec($channel));';
   }
//   $data .="\n\r".'$retrievedId = $response->hits->hits[0]->_id;';
//   $data .="\n\r".'if($retrievedId != "" && $retrievedId != null && $response->hits->total!=0 ){';
//   //Record exists Update it passing Id as parameter
//    $data .="\n\r".'$endpointUrl2 = "http://$ip:9200/$indextype/denorm/$retrievedId";';
//    $data .="\n\r".'$channel11 = curl_init();';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_URL, $endpointUrl2);';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_CUSTOMREQUEST, "PUT");';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_POSTFIELDS, json_encode($data));';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);';
//    $data .="\n\r".'curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);';
//    $data .="\n\r".'$response2 = curl_exec($channel11);';
//    $data .="\n\r".'}';
//    $data .="\n\r".'else {';
    $data .="\n\r".'$endpointUrl = "http://$ip:9200/$indextype/denorm/_bulk";';
    $data .="\n\r".'$channel11 = curl_init();';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_URL, $endpointUrl);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_RETURNTRANSFER, true);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_POST, true);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_POSTFIELDS,$bulkdata);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_CONNECTTIMEOUT, 100);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_SSL_VERIFYPEER, false);';
    $data .="\n\r".'curl_setopt($channel11, CURLOPT_TIMEOUT, 1000);';
    $data .="\n\r".'$response = curl_exec($channel11);';
    $data .="\n\r".'}';
    $data .="\n\r".'function jsonData($datarray){';
    $data .="\n\r".'$in[0]=\'{"index":{}}\';';
    $data .="\n\r".'$json=json_encode($datarray);';
    $data .="\n\r".'$json_nlines1=str_replace(\'\n\',\'\',$json);';
    $data .="\n\r".'$json_rep=str_replace(\'},\',\'}\n\',$json_nlines1);';
    $data .="\n\r".'$json_rep1=str_replace(\'\r\',\'\',$json_rep);';
    $data .="\n\r".'$json_rep2=str_replace(\'\"}\n\',\'\"},\',$json_rep1);';
    $data .="\n\r".'$finJson=substr($json_rep2, 1, -1);';
    $data .="\n\r".' $json_array=explode(\'\n\',$finJson);';
    $data .="\n\r".'$no=count($json_array);';
    $data .="\n\r".'for($z=0;$z<$no;$z++){';
    $data .="\n\r".'array_splice($json_array,2*$z,0,$in);}';
    $data .="\n\r".'$json_array[(2*$no)]=\'\n\';';
    $data .="\n\r".' $JSON=join("\n",$json_array);';
    $data .="\n\r".'return $JSON;}';
    return $data;
  }
