<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.silver.min.css" rel="stylesheet" />
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>
<script src="kendoui/js/console.js"></script>
<script type="text/javascript">var ju=jQuery.noConflict();</script>
<script language="JavaScript" type="text/javascript">
function getPick()
{
    var first=$('allpick').value;
    var url='first='+first;
new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=DynamicBlocks&action=DynamicBlocksAjax&file=PickDependencies&'+url,
			onComplete: function(response){
				var responseVal=response.responseText;
				if(!response.responseText){
					alert("Unable to get result! Please try again");


					return false;
				}else{

$('allpick2').innerHTML=response.responseText;

				}
			}
		}
	);
}

function showDetails()
{   var el=$('allpick2');
    var picklistvalueid=el.options[el.selectedIndex].value;
    var picklistid=$('allpick').value;
    var moduleid=$('pickmodule').value;
    var md;


    var m = window.location.search.substring(1);
    var parms = m.split('&');
    for (var i=0; i<parms.length; i++) {
var pos = parms[i].indexOf('=');
if (pos > 0) {
var key = parms[i].substring(0,pos);
if(key=='mode') md=parms[i].substring(pos+1);

}
    }

if(md in {'undefined':'','':''}) md='view';
 var url='picklistvalueid='+picklistvalueid+'&picklistid='+picklistid+'&moduleid='+moduleid+'&mode='+md;

   new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=DynamicBlocks&action=DynamicBlocksAjax&file=renderblock&'+url,
			onComplete: function(response){
				var responseVal=response.responseText;
				if(!response.responseText){
					alert("Unable to get result! Please try again");


					return false;
				}else{
               $('dynamicblock').innerHTML=response.responseText;

				}
			}
                }

	);
}
function deleteDependency()
{
  var reply=confirm("Are you sure you want to delete this dependency? ");
  if(reply==true)
      {
      var module=$('pickmodule').value;
      var pick=$('allpick').value;
      var pickvalue=$('allpick2').value;
      var url="picklist="+pick+"&picklistvalueid="+pickvalue+"&moduleName="+module;
new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody:'module=DynamicBlocks&action=DynamicBlocksAjax&file=PickDependencyDelete&'+url,
			onComplete: function(response){
				var responseVal=response.responseText;
				if(!response.responseText){
					alert("Unable to delete dependency! Please try again");


					return false;
				}else{
 
getPick();

				}
			}
		}
	);
        }
}

function showediturl()
{
    var first=$('allpick').value;
    var second=$('allpick2').value;
     var oModulePick = $('pickmodule');
     var module=oModulePick.options[oModulePick.selectedIndex].value;

    window.location="index.php?module=DynamicBlocks&action=index&mode=edit&parenttab=Tools&moduleName="+encodeURIComponent(module)+"&first="+first+"&second="+second;

}
//function changeModule1(){
//	jQuery("#status").css={"display":"inline"};
//	var oModulePick = jQuery('#pickmodule');
//        dataSource.read();
//	var module=oModulePick.options[oModulePick.selectedIndex].value;
//           var md;
//
//
//    var m = window.location.search.substring(1);
//    var parms = m.split('&');
//    for (var i=0; i<parms.length; i++) {
//    var pos = parms[i].indexOf('=');
//    if (pos > 0) {
//    var key = parms[i].substring(0,pos);
//    if(key=='mode') md=parms[i].substring(pos+1);
//
//    }
//        }
//
//if(md in {'undefined':'','':''}) md='view';
//	var role='H2';
//	new Ajax.Request(
//		'index.php',
//		{queue: {position: 'end', scope: 'command'},
//			method: 'post',
//			postBody: 'module=DynamicBlocks&action=DynamicBlocksAjax&file=Pick&moduleName='+encodeURIComponent(module)+'&roleid='+role+'&mode='+md,
//			onComplete: function(response) {
//                            if(!response.responseText)
//				{	alert("Unable to select module! Please try again");}
//else{
//				jQuery("#status").style.display="none";
//                                jQuery('#dynamicblock').innerHTML=" ";
//                                jQuery("#picklist_datas").innerHTML=response.responseText;
// 				getPick();
//			}}
//		}
//	);

//}


function serialize (txt) {
	switch(typeof(txt)){
	case 'string':
		return 's:'+txt.length+':"'+txt+'";';
	case 'number':
		if(txt>=0 && String(txt).indexOf('.') == -1 && txt < 65536) return 'i:'+txt+';';
		return 'd:'+txt+';';
	case 'boolean':
		return 'b:'+( (txt)?'1':'0' )+';';
	case 'object':
		var i=0,k,ret='';
		for(k in txt){

			if(!isNaN(k)) k = Number(k);
			ret += serialize(k)+serialize(txt[k]);
			i++;
		}
		return 'a:'+i+':{'+ret+'}';
	default:
		return 'N;';
		alert('var undefined: '+typeof(txt));return undefined;
	}
}

function unserialize(txt){
	var level=0,arrlen=new Array(),del=0,fin=new Array(),key=new Array(),save=txt;
	while(1){
		switch(txt.substr(0,1)){
		case 'N':
			del = 2;
			ret = null;
		break;
		case 'b':
			del = txt.indexOf(';')+1;
			ret = (txt.substring(2,del-1) == '1')?true:false;
		break;
		case 'i':
			del = txt.indexOf(';')+1;
			ret = Number(txt.substring(2,del-1));
		break;
		case 'd':
			del = txt.indexOf(';')+1;
			ret = Number(txt.substring(2,del-1));
		break;
		case 's':
			del = txt.substr(2,txt.substr(2).indexOf(':'));
			ret = txt.substr( 1+txt.indexOf('"'),del);
			del = txt.indexOf('"')+ 1 + ret.length + 2;
		break;
		case 'a':
			del = txt.indexOf(':{')+2;
			ret = new Array();
			arrlen[level+1] = Number(txt.substring(txt.indexOf(':')+1, del-2))*2;
		break;
		case 'O':
			txt = txt.substr(2);
			var tmp = txt.indexOf(':"')+2;
			var nlen = Number(txt.substring(0, txt.indexOf(':')));
			name = txt.substring(tmp, tmp+nlen );
			//alert(name);
			txt = txt.substring(tmp+nlen+2);
			del = txt.indexOf(':{')+2;
			ret = new Object();
			arrlen[level+1] = Number(txt.substring(0, del-2))*2;
		break;
		case '}':
			txt = txt.substr(1);
			if(arrlen[level] != 0){alert('var missed : '+save); return undefined;};

			level--;
		continue;
		default:
			if(level==0) return fin;
			alert('syntax invalid(1) : '+save+"\nat\n"+txt+"level is at "+level);
			return undefined;
		}
		if(arrlen[level]%2 == 0){
			if(typeof(ret) == 'object'){alert('array index object no accepted : '+save);return undefined;}
			if(ret == undefined){alert('syntax invalid(2) : '+save);return undefined;}
			key[level] = ret;
		} else {
			var ev = '';
			for(var i=1;i<=level;i++){
				if(typeof(key[i]) == 'number'){
					ev += '['+key[i]+']';
				}else{
					ev += '["'+key[i]+'"]';
				}
			}
			eval('final'+ev+'= ret;');
		}
		arrlen[level]--;//alert(arrlen[level]-1);
		if(typeof(ret) == 'object') level++;
		txt = txt.substr(del);
		continue;
	}
}

function saveit(){
	$("status").style.display="inline";
	var moduleid=$('pickmodule').value;
        var picklistid=$('allpick').value;
        var picklistvalueid=$('allpick2').value;
        var rownr=$('rownr').value;
        var columnnr=3;
        var i,j;
        var values=[];
        for(i=0;i<rownr;i++)
            {
                      values[i]=new Array(columnnr);
                     
            
         for(j=0;j<columnnr;j++)
             { 
                      var str='a'+i+'.'+j;
                      if (j==0)  values[i][j]=$(str).value;
                      else       values[i][j]=$(str).checked?1:0;
              
            }
            }
        var url='moduleid='+moduleid+'&picklistid='+picklistid+'&picklistvalueid='+picklistvalueid+'&blockdata='+serialize(values);
	    url+='&blocknr='+rownr;
        new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=DynamicBlocks&action=DynamicBlocksAjax&file=Pickadd&'+url,
			onComplete: function(response) {
                            if(!response.responseText)
				{	alert("Unable to save dependency! Please try again");}
else{
				$("status").style.display="none";
                                
			}}
		}
	);
 
}
</script>
<?php
ini_set('display_errors', 'on');
ini_set('error_reporting', 'E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE');
global $adb;
global $app_strings;
global $list_max_entries_per_page;
global $currentModule, $current_user;
global $mod_strings,$adb;
global $theme;
global $current_language;

require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
require_once 'include/utils/CommonUtils.php';

require_once 'modules/PickList/PickListUtils.php';

global $app_strings, $app_list_strings, $current_language, $currentModule, $theme;
$modules = getAllRoleDetails();
if(!empty($_REQUEST['pickRole'])){
	$fld_module = vtlib_purify($_REQUEST['pickRole']);
}else{
	$module = array_keys($modules);
	$fld_module = $module[0];
}

if(!empty($_REQUEST['pickRole'])){
	$fld_module = vtlib_purify($_REQUEST['pickRole']);
         $users = getRoleUsers($_REQUEST['pickRole']);
}else{
        $users = getRoleUsers($current_user->roleid);
	$user = array_keys($users);
	$fld_users = $user[0];
}
$smarty = new vtigerCRM_Smarty;

if((sizeof($picklists_entries) %3) != 0){
	$value = (sizeof($picklists_entries) + 3 - (sizeof($picklists_entries))%3);
}else{
	$value = sizeof($picklists_entries);
}

if($fld_module == 'Events'){
	$temp_module_strings = return_module_language($current_language, 'Calendar');
}else{
	$temp_module_strings = return_module_language($current_language, $fld_module);
}
$picklists_entries = getUserFldArray($fld_module,$roleid);
$available_module_picklist = array();
$picklist_fields = array();
if(!empty($picklists_entries)){
	$available_module_picklist = get_available_module_picklist($picklists_entries);
	 
}
$f=$_REQUEST['first'];
$s=$_REQUEST['second'];

if ($_REQUEST['mode']=='view') $smarty->assign("sv","FALSE");

elseif ($_REQUEST['mode']=='edit') $smarty->assign("sv","TRUE");
$smarty->assign("MODULE_LISTS",$modules);
$smarty->assign("ALL_LISTS",$available_module_picklist);

$smarty->assign("APP", $app_strings);		//the include language files
$smarty->assign("MOD", return_module_language($current_language,'Settings'));	//the settings module language file
$smarty->assign("MOD_PICKLIST", return_module_language($current_language,'PickList'));	//the picklist module language files
$smarty->assign("TEMP_MOD", $temp_module_strings);	//the selected modules' language file

$smarty->assign("MODULE",$fld_module);
$smarty->assign("USERS_LISTS",$users);
$smarty->assign("current_role",$current_user->roleid);
$smarty->assign("current_user",$current_user->id);
$smarty->assign("PICKLIST_VALUES",$picklist_fields);
$smarty->assign("THEME",$theme);
$smarty->assign("NOGIF",vtiger_imageurl('no.gif', $theme));
$smarty->assign("PRVPRFSELECTED",vtiger_imageurl('prvPrfSelectedTick.gif', $theme));
$smarty->assign("UITYPE", $uitype);
$smarty->assign("SEL_ROLEID",$roleid);
$smarty->assign("DELETE",getTranslatedString('LBL_DEL'));
$smarty->assign("SAVE",getTranslatedString('LBL_SAVE'));
$smarty->assign("CANCEL",getTranslatedString('LBL_CANCEL'));
$smarty->assign("EDIT",getTranslatedString('LBL_EDIT_BUTTON'));
$smarty->assign("LBL_PARENT",getTranslatedString('LBL_PARENT'));
$smarty->assign("LBL_VALUES",getTranslatedString('LBL_VALUES'));
if($_REQUEST['directmode'] != 'ajax'){
	$smarty->display("modules/FilterManagement/rolesPanel.tpl");
}else{
	$smarty->display("modules/FilterManagement/configPanel.tpl");
}
?>

