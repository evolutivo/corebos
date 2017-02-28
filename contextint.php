<?php
// include the lib
ini_set('display_errors','On');
include("include/database/PearDatabase.php");
include("include/utils/utils.php");
include_once("modules/Messages/Messages.php");
include_once("CONTEXTIO/class.contextio.php");
global $adb;
// define your API key and secret - find this https://console.context.io/#settings
define('CONSUMER_KEY', 'j3uld0yj');
define('CONSUMER_SECRET', 'rm69G9nQYcMWVZaM');

// instantiate the contextio object
$contextio = new ContextIO(CONSUMER_KEY, CONSUMER_SECRET);

define('USER_ID', '58b581018ba4025f515ff442');

define('LABEL', '0'); 
    
define('FOLDER', 'Inbox');
$params = array('label'=>LABEL, 'folder'=>FOLDER,'include_body'=>1,'flag_seen'=>0,'offset'=>0,'limit'=>100);
$r = $contextio->listMessagesBySourceAndFolder(USER_ID, $params);
$pergjnew=$r->getdecodedResponse();
var_dump($pergjnew);
foreach ($pergjnew as $key=>$value){
$subject=$value["subject"];
$emailid=$value["email_message_id"];
$sender=$value["addresses"]["from"]["email"];
$content=$value["body"][0]["content"];
if(array_key_exists(1,$value["body"])){
$contenthtml=$value["body"][1]["content"];}
else {
        $contenthtml='';
}

$str2=str_replace("\r\n","",$content);
$str1 = str_replace("\r\n", "", $contenthtml);
$focusnew =new Messages();
$focusnew->mode='';
$q=$adb->query("select * from vtiger_account join vtiger_crmentity on crmid=accountid where deleted=0 and email1='$sender'");
if($adb->num_rows($q)==0)
{
$q=$adb->query("select * from vtiger_contactdetails join vtiger_crmentity on crmid=contactid where deleted=0 and email='$sender'");  
if($adb->num_rows($q)>0) $relid=$adb->query_result($q,0,'contactid');
else $relid='';
}
else $relid=$adb->query_result($q,0,'accountid');

$focusnew->column_fields['assigned_user_id']=1;
$focusnew->column_fields['messagesrelatedto']=$relid;
$focusnew->column_fields['description']=mysql_escape_string($str1);
//$focusnew->column_fields['emailtext']=mysql_escape_string($str2);
//$focusnew->column_fields['inpout']='Input';
$focusnew->column_fields['messagesname']=$subject;
$focusnew->column_fields['messagestype']='Email';
$getrightcases=explode("::",$subject);
$sub1=$getrightcases[0];
$focusnew->saveentity("Messages");
$newid=$focusnew->id;

$params2=array('email_message_id'=>$emailid,'seen'=>true);
$r2=$contextio->setMessageFlags(USER_ID,$params2);
}
mysql_close($conn);
echo 'Finished Successfully';


?>
