<?php

ini_set('display_errors','on');
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
require_once("modules/Emails/mail.php");
require_once("modules/BiServer/BiServer.php");

$fb = fopen('bi_server.log', "a") or die("can't open file");
      
global $log,$adb,$current_user,$root_directory;

$focusBiServer=new BiServer();
$sql_count = "SELECT * "
        . " from vtiger_scripts"
        . " join vtiger_script_actions on vtiger_scripts.id = vtiger_script_actions.scriptid";
$res = $adb->pquery($sql_count,Array());
$nr=$adb->num_rows($res);
fwrite($fb,'
---------------------------------------------------------------------------------------------------------------------
'.$sql_count.' '.$nr.'
');
for($i=0;$i<$nr;$i++)
{
    $actionid=$adb->query_result($res,$i,'actionid');
    $name=$adb->query_result($res,$i,'name');
    $folder=$adb->query_result($res,$i,'folder');
    $script_action=$adb->query_result($res,$i,'script_action');
    $frecuency=$adb->query_result($res,$i,'frecuency');
    $last_execute=$adb->query_result($res,$i,'last_execute');
    $emails=$adb->query_result($res,$i,'emails');
    $subject=$adb->query_result($res,$i,'subject');
    $content=$adb->query_result($res,$i,'content');
    $time=$adb->query_result($res,$i,'time');
    $execution_cron=($adb->query_result($res,$i,'execution_cron')==1 ? true : false);
    $zipped=($adb->query_result($res,$i,'zipped')==1 ? true : false);
    fwrite($fb,'

'.$name.' '.$script_action.'
');
    $runnable=$focusBiServer->isRunnable($last_execute,$frecuency,$execution_cron,$time);
     
    if(!$runnable) continue;
    
    if($script_action =='execute'){
        $focusBiServer->execute_script($name,$folder); 
    }
    else
    {
        $f=substr($name,0,strrpos($name,'.'));
        $name2='';
        $arr=explode('_',$f);
        for($t=2;$t<sizeof($arr);$t++){
        $name2.=$arr[$t].'_';
        }
        $log->debug('name '.$name2);
        $tbl='mv_'.substr($name2,0,strlen($name2)-1);

        fwrite($fb,$tbl.'<br/> '.'
'); 
        $focusBiServer->execute_script($name,$folder);
        $sql="SELECT * FROM $tbl";   
        $query=$adb->pquery($sql,array());
        $nr_rec=$adb->num_rows($query);
        fwrite($fb,'Nr Rec of table '.$nr_rec.'
');  
        if($query && $nr_rec >0){

            $csv_filename=$root_directory.'modules/BiServer/CSV_files/'.$tbl.'.csv';
            $ret_array=$focusBiServer->generate_file_csv($query,$csv_filename,$name);
            $acc=$ret_array[0];
            $columns=$ret_array[1];
            fwrite($fb,'Account column  '.$acc.' '.$columns.'
');  fwrite($fb,$columns.'
');  
            if ($script_action=='send_email_FULL'){
                $focusBiServer->send_email_FULL_emails($emails,$tbl,$csv_filename,$subject,$content,$zipped);
            }
            else if($script_action=='send_email_SLICE'){
                $focusBiServer->send_email_SLICE($acc,$tbl,$columns,$subject,$content);
            }
          }
    }
    $sql_update="update vtiger_script_actions "
            . " set last_execute=?"
            . " where actionid=?";   
    fwrite($fb,$sql_update.' '.strtotime($time).'
');  
    $adb->pquery($sql_update,array(strtotime($time),$actionid));
}
fclose($fb);