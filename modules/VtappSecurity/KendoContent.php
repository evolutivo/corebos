<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set("max_execution_time","36000");

global $adb,$current_language,$log;
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');
include_once("include/language/$current_language.lang.php");
$kaction=$_REQUEST['kaction'];
$content=array();
if($kaction=='retrieve'){

$query=$adb->pquery("Select * from vtiger_evvtapps join vtiger_evvtappsuser e on e.appid=evvtappsid join vtiger_user2role r on r.userid=e.userid group by appid,roleid ",array());
$count=$adb->num_rows($query);
  $i=0;
for($j=0;$j<$count;$j++){
    $role=$adb->query_result($query,$j,'roleid');
    $id=$adb->query_result($query,$j,'evvtappsid');
    $r=$adb->query("select * from vtiger_evvtappsuser e join vtiger_user2role r on r.userid=e.userid join vtiger_users on id=r.userid where deleted=0 and e.appid=$id and r.roleid='$role'");
    $r1=$adb->query("select * from vtiger_user2role join vtiger_users on id=userid where deleted=0 and roleid='$role'");
    $count1=$adb->num_rows($r);
    $count2=$adb->num_rows($r1);
 
    include_once("modules/evvtApps/vtapps/app$id/language/en_us.lang.php");
   $w=Array(0,1,2,3,4,5,6);
    if(!in_array($adb->query_result($query,$j,'widget'),$w))
            $widg=0;
    else $widg=$adb->query_result($query,$j,'widget');
    if($count1>=$count2){

    $content[$i]['id']=$id;

//    if(!isset($vtapps_strings['appName'])){
  //       include_once "modules/evvtApps/vtapps/app$id/language/en_us.lang.php";
    $name=$vtapps_strings['appName'];
  //  }
//    else  $name=$vtapps_strings['appName'];
// if($id==2) $name='vtApps Config';  
//else if($id==3) $name='vtApps Store';
      $content[$i]['label']=$name;
       // $content[$i]['name']=$name;
   $content[$i]['permissions']=getRoleName($adb->query_result($query,$j,'roleid'));
      $content[$i]['visible']=$adb->query_result($query,$j,'wenabled')==1?true:false;
     $content[$i]['deleted']=$adb->query_result($query,$j,'candelete')==1?true:false;
      $content[$i]['widget']=$widg;
    $i++;
   //$vtapps_strings['appName']='';
$log->debug('dioni2'. $kaction);

    }
    else{
        for($c=0;$c<$count1;$c++){ $w=Array(0,1,2,3,4,5,6);
    if(!in_array($adb->query_result($r,$c,'widget'),$w))
            $widg=0;
    else $widg=$adb->query_result($r,$c,'widget');
             $content[$i]['id']=$id;
//    if($vtapps_strings['appName']==null){
  //       include_once "modules/evvtApps/vtapps/app$id/language/en_us.lang.php";
    $name=$vtapps_strings['appName'];
   // }
   // else  $name=$vtapps_strings['appName'];
// if($id==2) $name='vtApps Config';
//else if($id==3) $name='vtApps Store';

      $content[$i]['label']=$name;
       // $content[$i]['name']=$name;
   $content[$i]['permissions']=getUserName($adb->query_result($r,$c,'userid'));
      $content[$i]['visible']=$adb->query_result($r,$c,'wenabled')==1?true:false;
     $content[$i]['deleted']=$adb->query_result($r,$c,'candelete')==1?true:false;
        $content[$i]['widget']=$widg;
    $i++;
 
        }
 //  $vtapps_strings['appName']='';

    }
  //$vtapps_strings['appName']='';
}
echo json_encode($content);
}
if($kaction=='roles'){
    
    $roles = getAllRoleDetails();
    $i=0;
    foreach($roles as $roleid=>$roleDetails){
        $content[$i]['rolename']=$roleDetails[0];
        $content[$i]['selected']="false";
        $i++;
    }
    $us=$adb->pquery('select * from vtiger_users where deleted=0');
    for($j=0;$j<$adb->num_rows($us);$j++){
        $content[$i]['rolename']=$adb->query_result($us,$j,'user_name');
        $content[$i]['selected']="false";
        $i++;
    }
    echo json_encode($content);
}
elseif($kaction=='ev'){

   $query=$adb->pquery("Select * from vtiger_evvtapps order by evvtappsid",array());
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
    $id=$adb->query_result($query,$i,'evvtappsid');
      include_once("modules/evvtApps/vtapps/app$id/language/$current_language.lang.php");

    if($vtapps_strings['appName']==null){
         include_once "modules/evvtApps/vtapps/app$id/language/en_us.lang.php";
    $name=$vtapps_strings['appName'];
    }
    else  $name=$vtapps_strings['appName'];
        $content[$i]['appname']=$name;
        $content[$i]['appid']=$id;
        $content[$i]['selected']="false";
$vtapps_strings['appName']='';
    }
    echo json_encode($content);
}
elseif($kaction=='widg'){


for($i=0;$i<=6;$i++){

        $content[$i]['widget']=$i;

        $content[$i]['selected']="false";

    }
    echo json_encode($content);
}
elseif($kaction=='update'){
 
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    global $current_user;
       $id=$mv->id;
        $widg=$mv->widget;
        $r11=$mv->permissions;
   $query1=$adb->pquery("Select id as userid from vtiger_users where user_name=?",array($r11));
   if($adb->num_rows($query1)==0){ 
   $r1=$adb->pquery('select * from vtiger_role where rolename=?',array($mv->permissions));
    $r=$adb->query_result($r1,0,'roleid');
   $query1=$adb->pquery("Select userid from vtiger_user2role join vtiger_users on id=userid where deleted=0 and roleid=?",array($r));}
  
$count=$adb->num_rows($query1);
if($mv->visible==false) $vis=0;
else $vis=1;
if($mv->deleted==false) $del=0;
else $del=1;


for($i=0;$i<$count;$i++){
 
      $us=$adb->query_result($query1,$i,0);
   
      $s=$adb->pquery("Select * from vtiger_evvtappsuser where appid=? and userid=? ",array($id,$us));
if($adb->num_rows($s)!=0){
    $sort=$adb->query_result($s,0,'sortorder');
    $wtop=$adb->query_result($s,0,'wtop');
    $wleft=$adb->query_result($s,0,'wleft');
    $width=$adb->query_result($s,0,'wwidth');
    $wheight=$adb->query_result($s,0,'wheight');
    $wvisible=$adb->query_result($s,0,'wvisible');
    $canwrite=$adb->query_result($s,0,'canwrite');
    $canhide=$adb->query_result($s,0,'canhide');
    $canshow=$adb->query_result($s,0,'canshow');
    $visit=$adb->query_result($s,0,'visits');
    $query=$adb->pquery("update vtiger_evvtappsuser set wenabled=?, candelete=?,widget=? where appid=? and userid=?",array($vis,$del,$widg,$mv->id,$us));

}
else {     $query111=$adb->pquery("Delete from vtiger_evvtappsuser where appid=? and userid=$us",array($id));

        $query222=$adb->query("Insert into  vtiger_evvtappsuser  set appid=$mv->id,userid=$us,sortorder='0',wtop='100',wleft='40',wwidth='800',wheight='400',wvisible='0',wenabled=$vis,canwrite='1',candelete=$del,canhide='1',canshow='1',visits='0',widget='$widg'");

      }
    
}

}
elseif($kaction=="destroy"){
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];     
    $id=$mv->id;
    $us=array();
$r1=$adb->pquery('select * from vtiger_role where rolename=?',array($mv->permissions));
if($adb->num_rows($r1)!=0){
$r=$adb->query_result($r1,0,'roleid');
   $query1=$adb->pquery("Select userid from vtiger_user2role  where roleid=?",array($r));
}
else {
    $r11=$adb->pquery('select * from vtiger_users where user_name=?',array($mv->permissions));
$r=$adb->query_result($r11,0,'id');
   $query1=$adb->pquery("Select userid from vtiger_user2role  where userid=?",array($r));

}

$count=$adb->num_rows($query1);
for($i=0;$i<$count;$i++){
    $us[$i]=$adb->query_result($query1,$i,'userid');
      
  }
  $us1=implode(',',$us);
    $query=$adb->pquery("Delete from vtiger_evvtappsuser where appid=? and userid in ($us1)",array($id));
    echo $query;
}

elseif($kaction=="create"){
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);
    $mv=$model_values[0];
    $widg=$mv->widget;
    global $current_user;

       $r1=$adb->pquery('select * from vtiger_role where rolename=?',array($mv->permissions));
      
       if($adb->num_rows($r1)!=0){
$r=$adb->query_result($r1,0,'roleid');
   $query1=$adb->pquery("Select  userid from vtiger_user2role where roleid='$r'",array());}
   else {$r=$mv->permissions;
   $query1=$adb->pquery("Select  id as userid from vtiger_users where user_name='$r'",array());
   }
$count=$adb->num_rows($query1);
if($mv->visible==false) $vis=0;
else $vis=1;
if($mv->deleted==false) $del=0;
else $del=1;
for($i=0;$i<$count;$i++){
    $us=$adb->query_result($query1,$i,'userid');
    $s=$adb->pquery("Select * from vtiger_evvtappsuser where appid=? and userid=? ",array($mv->label,$us));
if($adb->num_rows($s)==0)
    $query111=$adb->pquery("insert into  vtiger_evvtappsuser (appid, userid, sortorder, wtop, wleft, wwidth, wheight, wvisible, wenabled, canwrite, candelete, canhide, canshow, visits,widget) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",array($mv->label,$us,'0', '100', '40', '800', '400', '0', $vis, '1', $del, '1', '1', '0',$widg));
$log->debug('dioni');

}
Vtiger_Menu::syncfile();
    Vtiger_Module::syncfile();
}

?>
