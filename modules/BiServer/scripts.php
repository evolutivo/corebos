 <?php
  ini_set('display_errors','on');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
//maurizio.tesa@gmail.com
global $adb,$date_var,$current_user,$log,$root_directory;
$current_user_id=$_SESSION['authenticated_user_id'] ;
$role_q=$adb->pquery("Select roleid "
        . " from vtiger_user2role"
        . " where userid = ?",array($current_user_id));
$current_role=$adb->query_result($role_q,0,'roleid');

$is_superadmin=($current_user->user_name== 'superadmin' ? true : false);
$kaction=$_REQUEST['kaction'];
$content=array();

if($kaction=='retrieve'){
 
    $srcfile=$root_directory.'modules/BiServer/';
    
    $files = scandir($srcfile);
    $i=0;
    foreach($files as $folder) {
    
        if($folder == '.' || $folder == '..' || $folder == '.svn' ||
                $folder == 'language' || $folder == 'Deleted_scripts' || $folder == 'CSV_files') continue;
        
        if(is_dir($root_directory.'modules/BiServer/'.$folder))
                {
                     $fol=$root_directory.'modules/BiServer/'.$folder.'/';
                     $folder_files = scandir($fol);
                     
                     foreach($folder_files as $file) {
                         if($file == '.' || $file == '..' || $file == '.svn') continue;
                         
                         $query=$adb->pquery("SELECT *
                                                  from  vtiger_scripts
                                                  left join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid
                                                  where name = ?
                                                  ",array($file));
                          $nr_file=$adb->num_rows($query);
                          
                          if($nr_file!=0){  //if the script exists then control its rights according to role
                          $active=$adb->query_result($query,0,'execution_cron');
                          $period=$adb->query_result($query,0,'period');
                          $last_exec=$adb->query_result($query,0,'last_exec');
                          $deleted_script=$adb->query_result($query,0,'deleted_script');
                          $id=$adb->query_result($query,0,'id');
                          $query_sec=$adb->pquery("SELECT *
                                                  from  vtiger_scripts
                                                  join vtiger_scripts_security on id=scriptid
                                                  where name = ? and roleid = ?
                                                  ",array($file,$current_role));
                          $nr_roles=$adb->num_rows($query_sec);
                          if($nr_roles>0 || $is_superadmin){
                              $roleid=$adb->query_result($query_sec,0,'roleid');
                              $export_scr=$adb->query_result($query_sec,0,'export_scr');
                              $delete_scr=$adb->query_result($query_sec,0,'delete_scr');
                              $execute_scr=$adb->query_result($query_sec,0,'execute_scr');
                              if(  ($roleid!=$current_role &&  !$is_superadmin) || 
                                      ($deleted_script==1 && !$is_superadmin)  )
                                  continue;
                              $content[$i]['name']=$file;
                              $content[$i]['id']=$id;
                              $content[$i]['period']=$period;
                              $content[$i]['last_exec']=$last_exec;
                              $content[$i]['folder']=$folder;
                              $content[$i]['active']=($active ==1 ? true : false);
                              $content[$i]['export_scr']=($export_scr ==1 ? true : false);
                              $content[$i]['delete_scr']=($delete_scr ==1 ? true : false);
                              $content[$i]['execute_scr']=($execute_scr ==1 ? true : false);
                              $i=$i+1;
                          }
                          
                          }
                          else{
                          $query=$adb->pquery("
                             Insert into vtiger_scripts
                              (name,period,active,deleted_script,folder)
                              values (?,?,?,?,?)",array($file,'','','',$folder)); 
//                          $content[$i]['name']=$file;
//                          $content[$i]['period']='';
//                          $content[$i]['folder']=$folder;
//                          $content[$i]['active']='';
                          }
                    }
                  
                }
    

        }
        echo json_encode($content);
}
//elseif($kaction=='save'){
// 
//global $log,$adb;
//$models=$_REQUEST['models'];
//$model_values=array();
//$model_values=json_decode($models);
//
//$nr=count($model_values);
//$mv=$model_values[$nr-1];
//$query="Update vtiger_scripts"
//     . " set period='".$mv->period."'"
//     . " where name=?";
//     
//$query=$adb->pquery($query,array($mv->name));
//}
elseif($kaction=='delete'){
    
global $log,$adb,$current_user;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];
$is_superadmin=($current_user->user_name== 'superadmin' ? true : false);
    
$query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          join vtiger_scripts_security on vtiger_scripts.id=vtiger_scripts_security.scriptid
                          join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid
                          where name = ?",array($mv->name));
$nr_qry=$adb->num_rows($query);
$active=$adb->query_result($query,0,'execution_cron');
$delete_scr=$adb->query_result($query,0,'delete_scr');

if($is_superadmin)
{
 $log->debug('alb3 mv '.$root_directory.'modules/BiServer/'.$mv->folder.'/'.$mv->name.' '.
         $root_directory.'modules/BiServer/Deleted_scripts/'.$mv->name);
 shell_exec('mv '.$root_directory.'modules/BiServer/'.$mv->folder.'/'.$mv->name.' '.
         $root_directory.'modules/BiServer/Deleted_scripts/'.$mv->name);
 $query="Update vtiger_scripts"
         . " set deleted_script='1'"
         . " where name=?";

    $query=$adb->pquery($query,array($mv->name));
    echo 'Successfully  deleted';
}  
elseif($delete_scr!='1' && !$is_superadmin)
    {echo 'You don\'t have permission to delete this script';} //script can not be deleted
elseif($active=='1')
    {echo 'Script is in cron,you can not delete without removing from cron !';} //script is in cron
else{
    $query="Update vtiger_scripts"
         . " set deleted_script='1'"
         . " where name=?";

    $query=$adb->pquery($query,array($mv->name));
    echo 'Successfully deleted';
}
}
//elseif($kaction=='add'){
// $log->debug('klm3 '.$kaction);
//    $srcfile=$root_directory.'TbCompanion/';
//
//    $files = scandir($srcfile);
//    $i=0;
//    foreach($files as $folder) {
//
//        if($folder == '.' || $folder == '..' || $folder == '.svn' || $folder == 'language') continue;
//
//                if(is_dir($folder))
//                {
//                    foreach($folder as $file) {
//
//                      $content[$i]['name']=$file;
//                      $content[$i]['period']='1';
//                      $content[$i]['active']=false;
//                      $i=$i+1;
//                    }
//                  
//                }
//    echo json_encode($content);
//
//        }
//}
elseif($kaction=='execute'){
 
    $filename=$_REQUEST['filename'];
    $folder=$_REQUEST['folder'];
    $srcfile=$root_directory.'modules/BiServer/'.$folder.'/'.$filename;
    shell_exec("php  $srcfile");
}
elseif($kaction=='export'){
 
    $filename=$_REQUEST['filename'];
    $folder=$_REQUEST['folder'];
    $query1=$adb->pquery("SELECT fieldlabel from  vtiger_scripts
          where name = ? ",array($filename));
    $fieldlabel=$adb->query_result($query1,0,'fieldlabel');
//    if($folder !='Reports')
//    {
//        $f=substr($filename,0,strrpos($filename,'.'));
//        $tbl=strtolower($folder).'_'.$f;    
//    }
//    else
//    {
    $f=substr($filename,0,strrpos($filename,'.'));
    $arr=explode('_',$f);
    for($t=2;$t<sizeof($arr);$t++){
    $name.=$arr[$t].'_';
    }
    $log->debug('name '.$name);
    $tbl='mv_'.substr($name,0,strlen($name)-1);
        
    $csv_filename=$root_directory.'modules/BiServer/CSV_files/'.$tbl.'.csv';
    $sql="SELECT * FROM $tbl";   
    shell_exec("rm -rf   $csv_filename");
    $fp = fopen($csv_filename, 'w');
    $query=$adb->pquery($sql,array());
    if($query){

    if($fieldlabel!='')
    {
        $m=explode(',',$fieldlabel);
    }
    else{
        $row1 = $adb->fetch_array($query);
        $m=array_keys($row1);  
    }  
    for($i=0;$i<sizeof($m);$i++){
        if(is_string($m[$i]))
        {$r[]=$m[$i];}
    }
    fputcsv($fp, $r,';');
    
    while ($row = $adb->fetch_array($query) ) {
    $r=array();
    for($i=0;$i<sizeof($row);$i++){
      $r[]=$row[$i];
    }  
      fputcsv($fp, $r,';');
    }
    fclose($fp);
    $csv_file='modules/BiServer/CSV_files/'.$tbl.'.csv';
    $host  = $_SERVER['HTTP_HOST'];
    $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    echo "http://$host$uri/$csv_file"; 
    }
    else{
        echo 'ERROR';
    }
    
}
elseif($kaction=='cron'){
 
    global $log;
    $fileid=$_REQUEST['fileid'];
    $time=$_REQUEST['time'];
    $period=$_REQUEST['period'];
    $folder=$_REQUEST['folder'];
    $type=$_REQUEST['type'];           
    $query=$adb->pquery("SELECT execution_cron
                          from  vtiger_scripts
                          join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid
                          where scriptid = ?",array($fileid));
    $nr=$adb->num_rows($query);
    if($type=='add'){    
        if($nr>0){
            $query1="Update vtiger_script_actions"
            . " set execution_cron='1',"
            . " script_action='execute',"
            . " frecuency=?,"
            . " time=?"
            . " where scriptid=?";     
            $query=$adb->pquery($query1,array($period,$time,$fileid));
        }
        else{
            $query="Insert into vtiger_script_actions (scriptid,script_action,frecuency,time,execution_cron)"
             . " values(?,?,?,?,?) ";     
            $query=$adb->pquery($query,array($fileid,'execute',$period,$time,'1'));
        }
    }
    elseif($type=='remove') {
        $query="Update vtiger_script_actions"
         . " set execution_cron='0' "
         . " where scriptid=?";     
        $query=$adb->pquery($query,array($fileid));
    }
}
elseif($kaction=='import_script_file'){
    header("Location: http://localhost/mondial/index.php?module=BiServer&action=index");
    $folder=$_REQUEST['folder'];
    $fol=$folder;
    $folder2=$_REQUEST['txt_script_file'];$log->debug('alb3 '.$folder2);
    if($folder2!='')
    {
        mkdir('var/www/mondial/modules/BiServer/'.$folder2);
        $fol=$folder2;

    }
    $file_zip=$_FILES["btn_import_script_file"]['tmp_name'];
    move_uploaded_file($file_zip, '/var/www/mondial/modules/BiServer/'.$fol.'/'.$_FILES["btn_import_script_file"]['name']);
    $host  = $_SERVER['HTTP_HOST'];

    header("Location: http://localhost/mondial/index.php?module=BiServer&action=index");
}