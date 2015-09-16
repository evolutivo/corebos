 <?php

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

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
                                                  where name = ?
                                                  ",array($file));
                          $nr_file=$adb->num_rows($query);
                          
                          if($nr_file!=0){  //if the script exists then control its rights according to role
                          $active=$adb->query_result($query,0,'active');
                          $period=$adb->query_result($query,0,'period');
                          $last_exec=$adb->query_result($query,0,'last_exec');
                          $deleted_script=$adb->query_result($query,0,'deleted_script');
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
                              $log->debug('gil4 actual'.$roleid.' currentuserrole'.$current_role.' '.$current_user->id);
                              if(  ($roleid!=$current_role &&  !$is_superadmin) || 
                                      ($deleted_script==1 && !$is_superadmin)  )
                                  continue;
                              $content[$i]['name']=$file;
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
                          join vtiger_scripts_security on id=scriptid
                          where name = ?",array($mv->name));
$nr_qry=$adb->num_rows($query);
$active=$adb->query_result($query,0,'active');
$delete_scr=$adb->query_result($query,0,'delete_scr');
$log->debug('klm3 '.$active);
if($is_superadmin)
{
 $log->debug('alb3 mv  "'.$root_directory.'modules/BiServer/'.$mv->folder.'/'.$mv->name.'"   "'.
         $root_directory.'modules/BiServer/Deleted_scripts/'.$mv->name.'"');
 shell_exec('mv  "'.$root_directory.'modules/BiServer/'.$mv->folder.'/'.$mv->name.'"   "'.
         $root_directory.'modules/BiServer/Deleted_scripts/'.$mv->name.'"');
 $query="Delete from vtiger_scripts "
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
elseif($kaction=='execute'){
 
    global $log;
    $filename=$_REQUEST['filename'];
    $folder=$_REQUEST['folder'];
    $srcfile=$root_directory.'modules/BiServer/'.$folder.'/'.$filename;
    
    $query1=$adb->pquery("SELECT fieldlabel,is_executing from  vtiger_scripts
          where name = ? ",array($filename));
    $fieldlabel=$adb->query_result($query1,0,'fieldlabel');
    $is_executing=$adb->query_result($query1,0,'is_executing');
    $log->debug('is_exec '.$is_executing);
    if($is_executing==1)
    {
        echo 'EXEC_OTHER';
    }
    elseif($is_executing==2)
    {
        echo 'EXEC_MAIL'; 
    }
    else
    {   
        $query1=$adb->pquery("Update vtiger_scripts
          set is_executing=1,last_exec='Exec Now User'
          where name = ?",array($filename));
        shell_exec("php  '$srcfile'");
        $query1=$adb->pquery("Update vtiger_scripts
          set is_executing=0,last_exec='".date('Y-m-d H:i')."'
          where name = ?",array($filename));
    }
}
elseif($kaction=='export'){
 
    global $adb,$log;
    
    $filename=$_REQUEST['filename'];
    $query1=$adb->pquery("SELECT fieldlabel,is_executing from  vtiger_scripts
          where name = ? ",array($filename));
    $fieldlabel=$adb->query_result($query1,0,'fieldlabel');
    $is_executing=$adb->query_result($query1,0,'is_executing');
    if($is_executing==1)
    {
        echo 'EXEC_OTHER';exit;
    }
    elseif($is_executing==2)
    {
        echo 'EXEC_MAIL';exit;
    }
    $name='';
    
    $folder=$_REQUEST['folder'];    
    if($folder !='Reports')
    {
        $f=substr($filename,0,strrpos($filename,'.'));
        $tbl=strtolower($folder).'_'.$f;   
    }
    else
    {
        $f=substr($filename,0,strrpos($filename,'.'));
        $arr=explode('_',$f);
        for($t=2;$t<sizeof($arr);$t++){
        $name.=$arr[$t].'_';
        }
        $log->debug('name '.$name);
        $tbl='mv_'.substr($name,0,strlen($name)-1);
    }
    $log->debug('name2 '.$tbl);
    
    $csv_filename=$root_directory.'modules/BiServer/CSV_files/'.$tbl.'.csv';
    $sql="SELECT * FROM $tbl ";   
    shell_exec("rm -rf   $csv_filename");
    $fp = fopen($csv_filename, 'w');
    $query=$adb->pquery($sql,array());
    if($query){

        if($fieldlabel!='')
        {
            $ex=explode(',',$fieldlabel);
        }
        else{
        $row1 = $adb->fetch_array($query);
        $ex=array_keys($row1);  
        }
        for($i=0;$i<sizeof($ex);$i++){
            if($ex[$i]!='' && is_string($ex[$i]))
            {$r[]=$ex[$i];$log->debug('name3 '.$ex[$i]);}
        }
        fputcsv($fp, $r,';');

        while ($row = $adb->fetch_array($query) ) {
            $r=array();
            for($i=0;$i<sizeof($row);$i++){
              $r[]=$row[$i];
              $log->debug('name4 '.$row[$i]);
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
    $filename=$_REQUEST['filename'];
    $folder=$_REQUEST['folder'];
    $type=$_REQUEST['type'];
    $srcfile=$root_directory.'modules/BiServer/'.$folder.'/'.$filename;
        
    $log->debug('albana2 '.$srcfile);
       
    $query=$adb->pquery("SELECT *
                          from  vtiger_scripts
                          where name = ?",array($filename));
    $nr=$adb->num_rows($query);
    $id=$adb->query_result($query,0,'id');
    
    if($type=='add'){ 
        
        $query="Update vtiger_scripts"
         . " set active='1' "
         . " where name=?";     
        $adb->pquery($query,array($filename));
        
        $query="Insert into vtiger_script_actions (scriptid,script_action,frecuency,execution_cron)"
                . " values (?,?,?,?)";     
        $adb->pquery($query,array($id,'execute','daily','Si'));
       
    }
    elseif($type=='remove') {
       $query="Update vtiger_scripts"
         . " set active='0' "
         . " where name=?";     
        $adb->pquery($query,array($filename));
        
        $query="delete from vtiger_script_actions "
                . "  where vtiger_script_actions.scriptid in "
                    . " (select id from vtiger_scripts where name =?)";     
        $adb->pquery($query,array($filename));
    }
}