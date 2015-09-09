<?php
$_SESSION['valcol']=$_REQUEST['fields'];
$_SESSION['labcol']=$_REQUEST['lab'];
global $adb,$current_user;
$adb->pquery("update vtiger_users set valcol=?, labcol=? where id=?",array($_REQUEST['fields'],$_REQUEST['lab'],$current_user->id));
//echo $_SESSION['valcol'].' : '.$_SESSION['labcol'];
?>
