<?php
global $current_language,$current_user;
$mypath="modules/$currentModule";
include_once "$mypath/processConfig.php";
include_once "$mypath/vtapps/baseapp/vtapp.php";
include "$mypath/language/$current_language.lang.php";
?>
<link href="<?php echo $mypath; ?>/styles/style.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo $mypath; ?>/styles/evvtapps.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/kendo.common.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $mypath; ?>/styles/kendo.default.css" rel="stylesheet" type="text/css" />
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
<script  src="<?php echo $mypath; ?>/codebase/ext/dhtmlxgrid_filter.js"></script>

<script type="text/javascript">
    window.onload = function makeDefaultContent(){
        makeContent('showCase');
    };

    function makeContent(contentName){
        //alert(contentName);
        //jQuery("#content").html("<img src='modules/evvtApps/images/ajax-loader.gif'>");
        //alert(contentName);
        jQuery.ajax({
            url:  "index.php?module=evvtApps&action=evvtAppsAjax&file="+contentName,
            success:function(makeContent){
                 //jQuery("#content").hide();
              
                jQuery("#content").html(makeContent);
                if(contentName==='showCase'){
                    jQuery("#leftButton").html('');
                    jQuery("#rightButton").html('<input type="button" value=">" onClick="makeContent(\'gridView\')">');
                    jQuery("#footerCenter").html('<a href="#" id="active" onClick="makeContent(\'showCase\')">Più visitati</a><a href="#" onClick="makeContent(\'gridView\')">Cruscotto</a><a href="#" onClick="makeContent(\'allApps\')">Applicazioni</a>');
                }
                if(contentName==='gridView'){
                    jQuery("#leftButton").html('<input type="button" value="<" onClick="makeContent(\'showCase\')">');
                    jQuery("#rightButton").html('<input type="button" value=">" onClick="makeContent(\'allApps\')">');
                    jQuery("#footerCenter").html('<a href="#" onClick="makeContent(\'showCase\')">Più visitati</a><a href="#" id="active" onClick="makeContent(\'gridView\')">Cruscotto</a><a href="#" onClick="makeContent(\'allApps\')">Applicazioni</a>');
                }
                if(contentName==='allApps'){
                    jQuery("#leftButton").html('<input type="button" value="<" onClick="makeContent(\'gridView\')">');
                    jQuery("#rightButton").html('');
                    jQuery("#footerCenter").html('<a href="#" onClick="makeContent(\'showCase\')">Più visitati</a><a href="#" onClick="makeContent(\'gridView\')">Cruscotto</a><a href="#" id="active" onClick="makeContent(\'allApps\')">Applicazioni</a>');

                }
                 //jQuery("#content").slideDown("fast");
            }
        })
        return false;
    };
    function makeFooter(appId,appName){
        //jQuery("#position1").html("<img src='modules/evvtApps/images/ajax-loader.gif'>");
        var dataString = 'appId='+ appId+ '&appName='+ appName;
        jQuery.ajax({
            type: "POST",
            data: dataString,
            url:  "index.php?module=evvtApps&action=evvtAppsAjax&file=makePosition",
            success:function(makeShop){
                jQuery("#content").html(makeShop);
                jQuery("#leftButton").html('');
                jQuery("#rightButton").html('');
                jQuery("#footerCenter").html('<a href="#" onClick="makeContent(\'showCase\')">Più visitati</a><a href="#" onClick="makeContent(\'gridView\')">Cruscotto</a><a href="#" onClick="makeContent(\'allApps\')">Applicazioni</a>');
            }
        })
        return false;
    };
    
</script>
<div id="leftButton"></div>
<div id="rightButton"></div>
<div id="content" style='overflow:scroll;max-height:90%'></div>
<div id="footer">
    <div id="footerLeft"></div>
    <div id="footerRight">
        <?php $numdel=$adb->getone("select count(*) from vtiger_evvtappsuser where appid=2 and wenabled=1 and userid=".$current_user->id);

if (is_admin($current_user) || $numdel>0) {

?>
        <a href="#" id="configuration" onClick="makeFooter('2','vtAppcomTSolucioConfiguration')">Configurazione</a> 
      <?php }
      $numdel1=$adb->getone("select count(*) from vtiger_evvtappsuser where appid=3 and wenabled=1 and userid=".$current_user->id);

if (is_admin($current_user) || $numdel1>0) {
      ?>
        <a href="#" id="webStore" onClick="makeFooter('3','vtAppcomTSolucioAppStore')">web Store</a>
      <?php }?></div>
    <div id="footerCenter"></div>
</div>
