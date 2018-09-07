<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function getRoleId($rolename)
{
	global $log;
	$log->debug("Entering getRoleId(".$rolename.") method ...");
	global $adb;
	$sql1 = "select roleid from vtiger_role where rolename=?";
	$result = $adb->pquery($sql1, array($rolename));
	$roleid = $adb->query_result($result,0,"roleid");
	$log->debug("Exiting getRoleName method ...");
	return $roleid;
}
$kaction=$_REQUEST['kaction'];
global $adb,$log;
require_once("modules/CustomView/CustomView.php");


if($kaction=='retrieve'){
if(isset($_REQUEST['filter']['filters'][0]['value'])) $role=$_REQUEST['filter']['filters'][0]['value'];

if(isset($_REQUEST['filter']['filters'][1]['value'])) $user=$_REQUEST['filter']['filters'][1]['value'];
   
$listQuery="Select cv.viewname,cv.entitytype,cv.cvid,fm.editable,fm.viewable,fm.deletable,fm.confid,fm.roleid,fm.userid 
            from vtiger_filtermanagement fm
            JOIN vtiger_customview cv on fm.viewid=cv.cvid";
if(isset($user) || isset($role)){            
     $listQuery.=" where fm.roleid=? and fm.userid=?";
     $query=$adb->pquery($listQuery,array($role,$user));
}
else $query=$adb->pquery($listQuery,array());
$count=$adb->num_rows($query);
$content=array();
for($i=0;$i<$count;$i++){
   $content[$i]['confid']=$adb->query_result($query,$i,'confid');
   $roleid=$adb->query_result($query,$i,'roleid');
   $content[$i]['roleid']=$roleid==='0'?'All':getRoleName($roleid);
   $content[$i]['userid']=$adb->query_result($query,$i,'userid')==0?'All':getUserName($adb->query_result($query,$i,'userid'));
   $content[$i]['FilterName']=$adb->query_result($query,$i,'viewname');
   $content[$i]['EntityName']=$adb->query_result($query,$i,'entitytype');
   $content[$i]['EditFilter']=$adb->query_result($query,$i,'editable')==1?true:false;
   $content[$i]['ViewFilter']=$adb->query_result($query,$i,'viewable')==1?true:false;
   $content[$i]['DeleteFilter']=$adb->query_result($query,$i,'deletable')==1?true:false;
   
}
echo json_encode($content);
}
if($kaction=='retrieveFirst'){
if(isset($_REQUEST['filter']['filters'][0]['value'])) $role=$_REQUEST['filter']['filters'][0]['value'];

if(isset($_REQUEST['filter']['filters'][1]['value'])) $user=$_REQUEST['filter']['filters'][1]['value'];
   
$listQuery="Select second_default_cvid,first_default_cvid,cv.viewname,cv.entitytype,cv.cvid,fm.cancreate,fm.configurationid,fm.roleid,fm.userid 
            from vtiger_user_role_filters fm
            JOIN vtiger_customview cv on fm.first_default_cvid=cv.cvid";

if(isset($user) || isset($role)){       
     $listQuery.=" where fm.roleid=? and fm.userid=?";
     $query=$adb->pquery($listQuery,array($role,$user));
}
else $query=$adb->pquery($listQuery,array());
$count=$adb->num_rows($query);
$content=array();
for($i=0;$i<$count;$i++){
   $content[$i]['configurationid']=$adb->query_result($query,$i,'configurationid');
   $roleid=$adb->query_result($query,$i,'roleid');
   $content[$i]['roleid']=$roleid==='0'?'All':getRoleName($roleid);
   $content[$i]['userid']=$adb->query_result($query,$i,'userid')==0?'All':getUserName($adb->query_result($query,$i,'userid'));
   $content[$i]['FilterName']=getCVname($adb->query_result($query,$i,'first_default_cvid'));
   $content[$i]['EntityName']=$adb->query_result($query,$i,'entitytype');
   $content[$i]['FilterNameSecond']=  getCVname($adb->query_result($query,$i,'second_default_cvid'));
   
}
echo json_encode($content);
}
if($kaction=='create'){
$Query="Insert into vtiger_filtermanagement(viewid,editable,viewable,deletable,roleid,userid)
            values(?,?,?,?,?,?)";
    
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    
    $viewname=$mv->FilterName;
    $entitytype=$mv->EntityName;
    $editable=$mv->EditFilter;
    $viewable=$mv->ViewFilter;
    $deletable=$mv->DeleteFilter;
    $roleid=$mv->roleid!=='All'?getRoleId($mv->roleid):0;
    if($mv->userid!='All') $userid= getUserId2_Ol($mv->userid);
    else $userid=0;
     
    $log->debug("loro ".$userid." ".$mv->userid);
    $cv=CustomView::getInstance('CustomView');
    $cvid=$cv->getViewIdByName($viewname,$entitytype);
    $cvq=$adb->query("select * from vtiger_filtermanagement where viewid=".$cvid." and roleid=".$roleid." and userid=".$userid);
    if($adb->num_rows($cvq)==0)
    {
   $log->debug('vlera1 : '.$cvid ." , ".$editable.','.$viewable.','.$deletable.','.$roleid.','.$userid);
    $query=$adb->pquery($Query,array($cvid,$editable,$viewable,$deletable,$roleid,$userid));
   // echo $query;
    }else
         $log->debug('vlera12 : '.$cvid ." , ".$editable.','.$viewable.','.$deletable.','.$roleid.','.$userid);
   
    }
if($kaction=='destroy'){
    
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    
    $confid=$mv->confid; 

    $Query="Delete from vtiger_filtermanagement where confid=?";
    $query=$adb->pquery($Query,array($confid));
    echo $query;
}
if($kaction=='destroyConfiguration'){
    
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    
    $confid=$mv->configurationid; 

    $Query="Delete from vtiger_user_role_filters where configurationid=?";
    $query=$adb->pquery($Query,array($confid));
    echo $query;
}
elseif($kaction=='saveConfiguration'){  
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    
    $viewname=$mv->FilterName;    
    $moduleid=getTabid($mv->EntityName);    
    $confid= $mv->configurationid;
    $roleid=$mv->roleid!=='All'?getRoleId($mv->roleid):0;
    if($mv->userid!='All') $userid= getUserId2_Ol($mv->userid);
    else $userid=0;
    $cv=CustomView::getInstance('CustomView');
    $cvid=$cv->getViewIdByName($viewname,$mv->EntityName);
    $log->debug('gazii '.$mv->FilterNameSecond);
    if($mv->FilterNameSecond=='')
        $cvid2=0;
    else
    $cvid2=$cv->getViewIdByName($mv->FilterNameSecond,$mv->EntityName);
    
    if($confid==null){
    $querySelect="Select configurationid from vtiger_user_role_filters where roleid=? and userid=? and moduleid=?";
    $confid=$adb->query_result($adb->pquery($querySelect,array($roleid,$userid,$moduleid)),0);
    }
    if(is_numeric($confid) && $confid>0)  
    {    
    $Query="Update vtiger_user_role_filters set first_default_cvid=?,second_default_cvid=?,roleid=?,userid=? where configurationid=?";
    $query=$adb->pquery($Query,array($cvid,$cvid2,$roleid,$userid,$confid));
    }
    else
    {
    $Query="Insert into vtiger_user_role_filters(roleid,userid,moduleid,second_default_cvid,first_default_cvid,cancreate) values(?,?,?,?,?,?)";
    $query=$adb->pquery($Query,array($roleid,$userid,$moduleid,$cvid2,$cvid,0));
    }
  echo json_encode(array());
}
elseif($kaction=='update'){
$Query="Update vtiger_filtermanagement
        set viewid=?,editable=?,viewable=?,deletable=?,roleid=?,userid=? where confid=?";
    
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    
    $viewname=$mv->FilterName;
    $entitytype=$mv->EntityName;
    $editable=$mv->EditFilter;
    $viewable=$mv->ViewFilter;
    $deletable=$mv->DeleteFilter;
    $confid=$mv->confid;
    $roleid=$mv->roleid!=='All'?getRoleId($mv->roleid):0;
    if($mv->userid!='All') $userid= getUserId2_Ol($mv->userid);
    else $userid=0;  
    
    $cv=CustomView::getInstance('CustomView');
    $cvid=$cv->getViewIdByName($viewname,$entitytype);
    $query=$adb->pquery($Query,array($cvid,$editable,$viewable,$deletable,$roleid,$userid,$confid));
    echo $query;
}
elseif($kaction=='filters'){
$moduleName=$_REQUEST['filter']['filters'][0]['value'];
if(empty($moduleName)) $moduleName="HelpDesk";
$listQuery="Select cv.viewname,cv.cvid
            from vtiger_customview cv 
            where cv.entitytype=?";
$query=$adb->pquery($listQuery,array($moduleName));
$count=$adb->num_rows($query);
$content=array();
for($i=0;$i<$count;$i++){
   $content[$i]['filterlabel']=$adb->query_result($query,$i,'cvid');
   $content[$i]['viewnamelabel']=$adb->query_result($query,$i,'viewname');

   
} 
echo json_encode($content);
}
elseif($kaction=='modules'){
$listQuery="Select name from vtiger_tab where isentitytype=1 and presence in (0,2) and name not in ('Calendar','Webmails','Emails','Events','ModComments')";
$query=$adb->pquery($listQuery,array());
$count=$adb->num_rows($query);

for($i=0;$i<$count;$i++){
   $content[$i]['filterlabel']=$adb->query_result($query,$i,'name');
   $content[$i]['viewnamelabel']=$adb->query_result($query,$i,'name');

   
} 
echo json_encode($content);
}
elseif($kaction=='roles'){
       $roles = getAllRoleDetails();
    $i=1;
    $content[0]['rolename']="All";
    foreach($roles as $roleid=>$roleDetails){
        $content[$i]['roleid']=$roleid;
        $content[$i]['rolename']=$roleDetails[0];
        $i++;
    }
    echo json_encode($content);
}
elseif($kaction=='users'){
    $filterolename=$_REQUEST['filterole'];
    $roleidq=$adb->query('select roleid from vtiger_role where rolename="'.$filterolename.'"');
    $filterole=$adb->query_result($roleidq,0,'roleid');
  if($filterole!='')
   $roleid=$filterole;
    elseif(isset($_REQUEST['filter']['filters'][0]['value'])) $roleid=getRoleId($_REQUEST['filter']['filters'][0]['value']);
    else   $roleid=$current_user->roleid;
    $users = getRoleUsers($roleid);
       $i=1;
       $content=array();
       $content[0]['username']='All';
    foreach($users as $userid=>$username){
        
//        $content[$i]['userid']=$userid;
        $content[$i]['username']=$username;
        $i++;
    }
    echo json_encode($content);
}
?>
