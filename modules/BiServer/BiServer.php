<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Accounts/Accounts.php,v 1.53 2005/04/28 08:06:45 rank Exp $
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('modules/Emails/Emails.php');
require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');

class BiServer extends CRMEntity {
	
    
function create_zip1($files = array(),$destination = '',$overwrite = true) {
      //if the zip file already exists and overwrite is false, return false
      if(file_exists($destination) && !$overwrite) { return false; }
      //vars
      $valid_files = array();
      //if files were passed in...
      if(is_array($files)) {
        //cycle through each file
        foreach($files as $file) {
          //make sure the file exists
          if(file_exists($file)) {
            $valid_files[] = $file;
          }
        }
      }
      //if we have good files...
      if(count($valid_files)) {
        //create the archive
        $zip = new ZipArchive();
        if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
          return false;
        }
        //add the files
        foreach($valid_files as $file) {
          $zip->addFile($file,$file);

        }
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

        //close the zip -- done!
        $zip->close();

        //check to make sure the file exists
        return file_exists($destination);
      }
      else
      {
        return false;
      }
}

function generate_file_csv($query,$csv_filename,$name){
    
    global $adb,$log;
    $acc='';
    $columns=Array();
    shell_exec("rm -rf   $csv_filename");
    $fp = fopen($csv_filename, 'w');
    
    $que=$adb->pquery("SELECT fieldlabel from  vtiger_scripts
          where name = ? ",array($name));
    $fieldlabel=$adb->query_result($que,0,'fieldlabel');
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
        {
            if(strpos($m[$i],'account') || strpos($m[$i],'lblgroupbuyer'))
                {$acc= $m[$i]; }   
            $columns[]=$m[$i];
            
        }
    }
    //var_dump($columns);var_dump('<br/>'.$acc.'<br/>');
    fputcsv($fp, $columns,';');
    
    while ($row = $adb->fetch_array($query) ) {
    $r=array();
    for($i=0;$i<sizeof($row);$i++){
      $r[]=$row[$i];
    }  
      fputcsv($fp, $r,';');
    }
    fclose($fp);
    $arr[0]=$acc;
    $arr[1]=$columns;
    
    return $arr;
}


function generate_file_csv_SLICE($query_mv_acc_rec,$csv_filename,$columns){
    
    global $adb,$log;
    $acc='';
    shell_exec("rm -rf   $csv_filename");
    $fp = fopen($csv_filename, 'w');
    
    var_dump($columns);var_dump('<br/>'.$csv_filename.'<br/>');
    fputcsv($fp, $columns,';');
    
    while ($row = $adb->fetch_array($query_mv_acc_rec) ) {
    $r=array();
    for($i=0;$i<sizeof($row);$i++){
      $r[]=$row[$i];
    }  
      fputcsv($fp, $r,';');
    }
    fclose($fp);
    
}

function isRunnable($last_execute,$frecuency,$execution_cron,$time) {
    $fb = fopen('bi_server.log', "a") or die("can't open file");
        $runnable = false;

        if($frecuency=='daily')
        {
          $frecuency=24*3600;  // 60;//
        }
        else if($frecuency=='monthly')
        {
          $frecuency=24*3600*30;  
        }
        fwrite($fb,'Execution parameters '.$execution_cron.' '.$last_execute.' '.date('d-m-Y H:i',$last_execute).' '.$frecuency.'<br/>'.'
');   
        fwrite($fb,'Now '.date('d-m-Y H:i').' $time '.$time.' 
'); 
        if ($execution_cron){
            
            $elapsedTime = time() - $last_execute;
            fwrite($fb,' Elapsed time '.$elapsedTime. '>= Frecuency '.$frecuency.' '. strtotime("now").' >= '.strtotime($time) .' '. strtotime("now") .' < '. strtotime('+30 min',strtotime($time)).'
');   
            $runnable = ($elapsedTime >= $frecuency  && strtotime("now") >= strtotime($time) && strtotime("now") < strtotime('+30 min',strtotime($time)));
        }
        return $runnable;
    }
    
function send_email_FULL($acc,$tbl,$csv_filename,$subject,$content){
$fb = fopen('bi_server.log', "a") or die("can't open file");

    global $adb,$log,$current_user;
    $sql_mv_distinct="SELECT Distinct($acc) as acc FROM $tbl";   
    $query_mv_distinct=$adb->pquery($sql_mv_distinct,array());
    $nr_rec_mv_distinct=$adb->num_rows($query_mv_distinct);
    fwrite($fb,'Nr Mv record distinct Full '.$nr_rec_mv_distinct.'<br/> '.'
');  
    for($j=0;$j<$nr_rec_mv_distinct;$j++){

        $curr_acc=$adb->query_result($query_mv_distinct,$j,'acc');
        $sql_acc="SELECT email1 FROM vtiger_account"
                . " where accountid=?";   
        $query_acc=$adb->pquery($sql_acc,array($curr_acc));
        $email1=$adb->query_result($query_acc,0,'email1');
        fwrite($fb,'Nr each account recordsFull '.$curr_acc.' '.$email1.'<br/>'.'
');  
        if($email1!='')
        {
          send_mail('Bi_Mail_Sender',$email1,$current_user->user_name,'',$subject,$content,'','',$csv_filename); 
        }
        
    } 

}

function send_email_FULL_emails($emails,$tbl,$csv_filename,$subject,$content,$zipped){
$fb = fopen('bi_server.log', "a") or die("can't open file");

    global $adb,$log,$current_user,$root_directory;
    fwrite($fb,'Emails List  Full '.$emails.'<br/> '.'
');  
    $email_arr=explode(',',$emails);
    for($j=0;$j<sizeof($email_arr);$j++){
        
        if($email_arr[$j]=='') continue;        
        fwrite($fb,'Email sending to  '.$email_arr[$j].'<br/>'.'
');  
        $zip_file="$tbl.zip";
        shell_exec("rm -rf $zip_file $tbl.csv"); 
        $files_to_zip=array();
        shell_exec("cp $csv_filename $tbl.csv"); 
        $files_to_zip[]="$tbl.csv";                
        $result = create_zip1($files_to_zip,$zip_file);
        if($zipped)
        {
            send_mail('Bi_Mail_Sender',$email_arr[$j],$current_user->user_name,'',$subject,$content,'','',$zip_file); 
        }
        else
        {
            send_mail('Bi_Mail_Sender',$email_arr[$j],$current_user->user_name,'',$subject,$content,'','',$csv_filename); 
        }   
              
    } 

}

function send_email_SLICE($acc,$tbl,$columns,$subject,$content){

    $fb = fopen('bi_server.log', "a") or die("can't open file");
    
    global $adb,$log,$root_directory;
    $sql_mv_distinct="SELECT Distinct($acc) as acc FROM $tbl";   
    $query_mv_distinct=$adb->pquery($sql_mv_distinct,array());
    $nr_rec_mv_distinct=$adb->num_rows($query_mv_distinct);
    fwrite($fb,'Nr Mv record distinct Slice '.$acc.' '.$nr_rec_mv_distinct.'<br/> '.'
');  
    for($j=0;$j<$nr_rec_mv_distinct;$j++){
        
        $curr_acc=$adb->query_result($query_mv_distinct,$j,'acc');
        $sql_mv_acc_rec="SELECT *  FROM $tbl where $acc =?";   
        $query_mv_acc_rec=$adb->pquery($sql_mv_acc_rec,array($curr_acc));
        $nr_mv_acc_rec=$adb->num_rows($query_mv_acc_rec);
        $csv_filename=$root_directory.'modules/TbCompanion/CSV_files/'.$tbl.'_'.$curr_acc.'.csv';
        generate_file_csv_SLICE($query_mv_acc_rec,$csv_filename,$columns);
        
        $sql_acc="SELECT email1 FROM vtiger_account"
                . " where accountid=?";   
        $query_acc=$adb->pquery($sql_acc,array($curr_acc));
        $email1=$adb->query_result($query_acc,0,'email1');
        fwrite($fb,'Nr each account records Slice '.$nr_mv_acc_rec.' '.$curr_acc.' '.$email1.'<br/>'.'
');   
        if($email1!='')
        {
          send_mail('Bi_Mail_Sender',$email1,$current_user->user_name,'',$subject,$content,'','',$csv_filename); 
        }
        
    } 
}

function execute_script($filename,$folder)
    {
        $fb = fopen('bi_server.log', "a") or die("can't open file");
        global $adb,$root_directory;
        $srcfile=$root_directory.'modules/TbCompanion/'.$folder.'/'.$filename;
        fwrite($fb,'Executing script '.$srcfile.'
    ');  
        $query1=$adb->pquery("Update vtiger_scripts
              set is_executing=2,last_exec='Exec Now Automatic' 
              where name =?",array($filename));
        shell_exec("php  $srcfile");
        $query1=$adb->pquery("Update vtiger_scripts
              set is_executing=0,last_exec='".date('Y-m-d H:i')."'
              where name =?",array($filename));
    }
}

?>
