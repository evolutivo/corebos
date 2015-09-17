<?php
global $current_language,$current_user,$adb,$current_user,$adb;
$mypath="modules/$currentModule";
include_once "$mypath/processConfig.php";
include_once "$mypath/vtapps/baseapp/vtapp.php";
include "$mypath/language/$current_language.lang.php";

include "config.inc.php";
global $root_directory;
$patt=$_REQUEST["appname"];
$g=shell_exec("grep -H -r '$patt' $root_directory/modules/evvtApps/vtapps");
stream_set_blocking($g, false);
$b=explode(",",$g);
$p=$b[0];
$path=explode(":",$p);
$path=explode("/",$path[0]);
$nr=substr($path[8],3);

require('user_privileges/user_privileges_'.$current_user->id.'.php');
require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
//$a=$adb->query("select * from vtiger_evvtapps where evvtappsid=$nr");
//$aname=$adb->query_result($a,0,"appname");

include "$mypath/vtapps/app$nr/language/$current_language.lang.php";
$tit=$vtapps_strings['Title'];
    $desc=$vtapps_strings['TooltipDescription'];
if($tit=='') {
    include "$mypath/vtapps/app$nr/language/en_us.lang.php";
    $tit=$vtapps_strings['Title'];
    $desc=$vtapps_strings['TooltipDescription'];}

$nn=shell_exec("grep -i 'class' $root_directory/modules/evvtApps/vtapps/app$nr/vtapp.php");
$v=explode(" ",trim($nn));
$aname=$v[1];

$acc_array=array();
$result_acc=$adb->query("Select accountname,accountid
    from vtiger_account
    join vtiger_projecttask on vtiger_projecttask.account=vtiger_account.accountid
    join vtiger_crmentity on crmid= vtiger_account.accountid
    where deleted=0");
for($i=0;$i<$adb->num_rows($result_acc);$i++)
{
 $acc_array[$i]['name']=$adb->query_result($result_acc,$i,'accountname');
 $acc_array[$i]['id']=$adb->query_result($result_acc,$i,'accountid');
}
$acc_array2=json_encode($acc_array);
echo '
    <link href="kendoui/styles/kendo.common.min.css" rel="stylesheet">
<link href="kendoui/styles/kendo.default.css" rel="stymodifiedtimelesheet">
<script src="kendoui/js/kendo.web.min.js"></script>
<script src="kendoui/js/consolemodified.js"></script>';
echo '<div style="overflow: hidden; height:10%">';
if(($nr==10000) && $is_admin===true)  {
echo '<img src="themes/images/edit.png" style="padding-left:20px;" alt="Edit" onclick="javascript:window.location.href=\'index.php?action=editvtapp&appname='.$tit.'&parenttab=Home&module=evvtApps&vtappid='.$nr.'\'" title="Edit">';
echo '<form method="post" action="include/uploadify/uploadify.php" id="uploadfiles"><input id="file_upload" name="file_upload" type="file" multiple="true"><div id="queue"></div></form>
	<script type="text/javascript">
        jQuery(function() {
			jQuery("#file_upload").uploadify({                                
                                "method"   : "post",				                                
				"swf"      : "modules/evvtApps/uploadify.swf",
				"uploader" : "modules/evvtApps/uploadify.php"
			});                        
		});                
	</script>';
}
echo '</div>';
if($nr!=10000 && $nr!=10000){
echo'<center><form method="POST" action=""></form></center>';
if(isset($_POST['submit'])){
    $_SESSION['dates']=$_POST['dates'];
    $_SESSION['datee']=$_POST['datee'];
  if($nr==40)
    $_SESSION['acc']=$_POST['acc_id'];
    
}}
?>

<link href="<?php echo $mypath; ?>/styles/evvtapps.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/kendo.common.css" rel="stylesheet" type="text/css" />
 <link href="kendoui/styles/kendo.default.css" rel="stylesheet" />
<link href="<?php echo $mypath; ?>/styles/tipsy.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/codebase/dhtmlxgrid.css" rel="stylesheet" type="text/css">
<link href="<?php echo $mypath; ?>/codebase/skins/dhtmlxgrid_dhx_skyblue.css" rel="stylesheet" type="text/css">

<script src="<?php echo $mypath; ?>/js/jquery.min.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/jquery.tipsy.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/kendo.all.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/evvtapps.js" type="text/javascript"></script>
<script src="<?php echo $mypath; ?>/js/kendo.web.js" type="text/javascript"></script>
<script  src="<?php echo $mypath; ?>/codebase/dhtmlxcommon.js"></script>
<script  src="<?php echo $mypath; ?>/codebase/dhtmlxgrid.js"></script>
<script  src="<?php echo $mypath; ?>/codebase/dhtmlxgridcell.js"></script>
<script  src="<?php echo $mypath; ?>/codebase/ext/dhtmlxgrid_srnd.js"></script>
<script  src="<?php echo $mypath; ?>/codebase/ext/dhtmlxgrid_group.js"></script>
<script  src="<?php echo $mypath; ?>/dhtmlxDataProcessor/codebase/dhtmlxdataprocessor.js"></script>
<?php if($nr==10000){
echo '
<script src="include/uploadify/jquery.uploadify.min.js" type="text/javascript"></script>';
}
?>
<link rel="stylesheet" type="text/css" href="include/uploadify/uploadify.css">
<script type="text/javascript">
   function makeGrid(){
       	makePosition1('<?php echo $nr ?>','<?php echo $aname ?>');

    };
    function makePosition1(appId,appName){
        //jQuery("#position1").html("<img src='modules/evvtApps/images/ajax-loader.gif'>");
        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: "POST",
            data: dataString,
            url:  "index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition",
            success:function(makePosition1){
                jQuery("#position1").html(makePosition1);

            }
        })
        return false;
    };

    makeGrid();
</script>
<?php if ($tit!='Sun Life') {
if(($nr==10000) && $is_admin===true)
$marg='margin-top:-50px';

echo '<div style="font-size:16px;" align="center" ><b>'.$tit.'</b>'; echo '<div style="font-size:12px">'.$desc."</div></div>";}?>

<div style="margin-top:2%;background-color:#F9F9F9; border-top-style:ridge ;border-top-color:#000099;">
<?php    
if(($nr==10000) && $is_admin===true)   
echo '<div id="position1" align="center" style="margin-top:10px;overflow: hidden;"></div>';
elseif(($nr==10000)  && $is_admin!==true)
echo '<div id="position1" align="center" style="margin-top:10px;overflow: hidden;"></div>';  
else 
echo '<div id="position1" align="center" ></div>';      
?>    
</div>
