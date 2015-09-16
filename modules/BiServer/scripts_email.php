 <?php
  ini_set('display_errors','on');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

//maurizio.tesa@gmail.com
global $adb,$date_var,$current_user,$log,$root_directory;
 
$kaction=$_REQUEST['kaction'];
$content=array();

if($kaction=='retrieve'){ 
 
 $query=$adb->query("SELECT *  "
         . " from vtiger_scripts"
         . " join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid");
  $nr_file=$adb->num_rows($query);
  
for($i=0;$i<$nr_file;$i++){
            
    $id=$adb->query_result($query,$i,'actionid');
    $script_action=$adb->query_result($query,$i,'script_action');
    $name=$adb->query_result($query,$i,'name');
    $scriptid=$adb->query_result($query,$i,'scriptid');
    $folder=$adb->query_result($query,$i,'folder');
    $frecuency=$adb->query_result($query,$i,'frecuency');
    $time=$adb->query_result($query,$i,'time');
    $last_execute=$adb->query_result($query,$i,'last_execute');
    $emails=str_replace(',','
            ',$adb->query_result($query,$i,'emails'));
    $subject=$adb->query_result($query,$i,'subject');
    $cont=$adb->query_result($query,$i,'content');
    $execution_cron=($adb->query_result($query,$i,'execution_cron')=='1' ? true : false);
    $zipped=($adb->query_result($query,$i,'zipped')=='1' ? true : false);
    
  
  $content[$i]['id']=$id;
  $content[$i]['script_action']=$script_action;
  $content[$i]['name']=$name;
  $content[$i]['scriptid']=$scriptid;
  $content[$i]['folder']=$folder;
  $content[$i]['frecuency']=$frecuency;
  $content[$i]['time']=$time;
  $content[$i]['last_execute']=date("d-m-Y H:m:i",$last_execute);
  $content[$i]['execution_cron']=$execution_cron;
  $content[$i]['emails_temp']=$emails;
  $content[$i]['emails']=$adb->query_result($query,$i,'emails');
  $content[$i]['subject']=$subject;
  $content[$i]['cont']=$cont;
  $content[$i]['zipped']=$zipped;
   
}

echo json_encode($content);
}
elseif($kaction=='save'){
 
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];
$query="Update vtiger_script_actions"
     . " set scriptid=?,"
        . "  execution_cron=?,"
        . "  emails=?,"
        . "  subject=?, "
        . "  content=?, "
        . "  time=?,"
        . "  zipped=?"
     . "  where actionid=?";
$log->debug('klm6 '.$query);     
$query=$adb->pquery($query,array($mv->scriptid,($mv->execution_cron== true ? 1 : 0),$mv->emails,
    $mv->subject,$mv->cont,$mv->time,$mv->zipped,$mv->id));
}
elseif($kaction=='delete'){
    
global $log,$adb;
$models=$_REQUEST['models'];
$model_values=array();
$model_values=json_decode($models);

$nr=count($model_values);
$mv=$model_values[$nr-1];

$query=$adb->pquery("Delete  from  vtiger_script_actions
                          where actionid = ?",array($mv->id));

}
elseif($kaction=='add'){
     global $log,$adb;
    $models=$_REQUEST['models'];
    $model_values=array();
    $model_values=json_decode($models);

    $nr=count($model_values);
    $mv=$model_values[$nr-1];
    $query="insert into  vtiger_script_actions (scriptid,execution_cron,emails,subject,content,time,script_action,frecuency,zipped) "
         . " values (?,?,?,?,?,?,?,?,?) ";

    $query=$adb->pquery($query,array($mv->scriptid,($mv->execution_cron== true ? 1 : 0),$mv->emails,
        $mv->subject,$mv->cont,$mv->time,'send_email_FULL','daily',$mv->zipped));
}

elseif($kaction=='sendmail_now'){ 
 
    $fb = fopen('bi_server.log', "a") or die("can't open file");
    global $log,$adb,$current_user,$root_directory;

$sql_count = "SELECT * "
        . " from vtiger_scripts"
        . " join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid"
        . " where actionid = ? ";
$res = $adb->pquery($sql_count,Array($_REQUEST['actionid']));
$nr=$adb->num_rows($res);
fwrite($fb,'
---------------------------------------------------------------------------------------------------------------------
Manually sent '.$sql_count.' '.$nr.'
');

   $actionid=$adb->query_result($res,0,'actionid');
    $name=$adb->query_result($res,0,'name');
    $folder=$adb->query_result($res,0,'folder');
    $script_action=$adb->query_result($res,0,'script_action');
    $frecuency=$adb->query_result($res,0,'frecuency');
    $last_execute=$adb->query_result($res,0,'last_execute');
    $emails=$adb->query_result($res,0,'emails');
    $subject=$adb->query_result($res,0,'subject');
    $content=$adb->query_result($res,0,'content');
    $time=$adb->query_result($res,0,'time');
    $execution_cron=($adb->query_result($res,0,'execution_cron')=='Si' ? true : false);
    fwrite($fb,'

'.$name.' '.$script_action.'
');
//    $runnable=isRunnable($last_execute,$frecuency,$execution_cron);
//     
//    if(!$runnable) {echo 'NORUN'; exit;}
    if(!$execution_cron) {echo 'Email not active'; exit;}
    
         $arr=explode('_',$name);
        $f=substr($arr[2],0,strrpos($arr[2],'.'));
        $tbl='mv_'.$f;
        fwrite($fb,$tbl.'<br/> '.'
'); 
        $sql="SELECT * FROM $tbl";   
        $query=$adb->pquery($sql,array());
        $nr_rec=$adb->num_rows($query);
        fwrite($fb,'Nr Rec of table '.$nr_rec.'
');  

        if($query && $nr_rec >0){

            $csv_filename=$root_directory.'modules/TbCompanion/CSV_files/'.$tbl.'.csv';
            $ret_array=generate_file_csv($query,$csv_filename,$name);
            $acc=$ret_array[0];
            $columns=$ret_array[1];
            fwrite($fb,'Account column  '.$acc.' '.$columns.'
');  fwrite($fb,$columns.'
');  

            if ($script_action=='send_email_FULL'){
                
            send_email_FULL_emails($emails,$tbl,$csv_filename,$subject,$content);
            //send_email_FULL($acc,$tbl,$csv_filename,$subject,$content); 
            }
            else if($script_action=='send_email_SLICE'){
            send_email_SLICE($acc,$tbl,$columns,$subject,$content);
            }
            
                $sql_update="update vtiger_script_actions "
            . " set last_execute=?"
            . " where actionid=?";   
    fwrite($fb,$sql_update.' '.time().'
');  
    $adb->pquery($sql_update,array(time(),$actionid));

          }
          else {echo 'Report Empty'; exit;}
    
    echo 'Report sent successfully';
}
elseif($kaction=='list_role'){ 
 
     $query=$adb->pquery("SELECT *
                          from  vtiger_role
                          ",array());
      $nr_file=$adb->num_rows($query);

    for($i=0;$i<$nr_file;$i++){

      $rolename=$adb->query_result($query,$i,'rolename');
      $roleid=$adb->query_result($query,$i,'roleid');

      $content[$i]['id']=$roleid;
      $content[$i]['name']=$rolename;
    }

    echo json_encode($content);
}