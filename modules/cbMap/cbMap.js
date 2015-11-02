/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
var filter ='';
var filterdata;
var orgmodule =0 ,targetmodule= 0;
var node1;
var node2;
var node1Result, node2Result;
var advft_group_index_count = 0;
var column_index_array = [];
var group_index_array = [];
var advft_column_index_count =0;
function showMapWindow(mapid){
var url = "index.php?module=cbMap&action=cbMapAjax&file=getMap";
var stringData = "mapid="+mapid;
jQuery.ajax
    ({
    type: "POST",
    url: url,
    data: stringData,
    cache: false,
    async:false,
    success: function(text)
    {jQuery("#lstRecordLayout").append('<div id="dialog" title="Create Map"></div>');
     jQuery( "#dialog" ).html(text);
     jQuery( "#dialog" ).dialog( "open" );
    }
   });
}

function getModuleFields(moduleId,ref){
var module_list = jQuery("#module_list").val();
var related_modules = jQuery("#related_modules").val();
var rel_fields= jQuery("#rel_fields").val();
var columnIndex = advft_column_index_count+1;
jQuery.post('index.php', {
module:'cbMap',
action:'cbMapAjax',
async:false,
file:'getFields', ajax:'true',pmodule: moduleId,modtype:ref,module_list:module_list,related_modules:related_modules,rel_fields:rel_fields,
},
function(relresult){
    row = '';
    i=1;
    if(ref === "origin") {
        orgmodule = moduleId;
        node1Result = relresult;
        node1.innerHTML = "<select id='"+ref+"keyconfig"+i+"' name='"+ref+"keyconfig[]'"+ "class='small singlecombofilter'>"
                                +"</select>";
        jQuery(".singlecombofilter").multiselect({
        multiple: false,
        header: true,
        noneSelectedText: "Select an Option",
        selectedList: 1
        }).multiselectfilter();
    }
    else  {
        targetmodule = moduleId;
        node2Result = relresult;
        node2.innerHTML = "<select id='"+ref+"keyconfig"+i+"' name='"+ref+"keyconfig[]'"+ "multiple class='small multicombo'>"+
                                +"</select>";
        jQuery(".multicombo").multiselect().multiselectfilter();
        jQuery("#add").show();
        jQuery(".singlecombodefault").multiselect({
           multiple: false,
           header: true,
           noneSelectedText: "Select an Option",
           selectedList: 1
          });
    }
    jQuery('#'+ref+'keyconfig'+i).html(relresult);
    jQuery('#'+ref+'keyconfig'+i).multiselect('refresh');
    });
}

function getModuleBlocks(moduleId,ref){
var module_list = jQuery("#module_list").val();
var blocks = jQuery("#blocks").val();
var rel_fields= jQuery("#rel_fields").val();
var columnIndex = advft_column_index_count+1;
jQuery("#orgVal").val(moduleId);
jQuery.post('index.php', {
module:'cbMap',
action:'cbMapAjax',
file:'getBlocks', ajax:'true',pmodule: moduleId,modtype:ref,module_list:module_list,
},
function(relresult){
    row = '';
    i=1;

        targetmodule = moduleId;
        node2Result = relresult;
        document.getElementById('targetblocks').innerHTML = "<select id='"+ref+"keyconfig"+i+"' name='"+ref+"keyconfig[]'"+ "multiple class='small multicombo'>"+
                                +"</select>";
        jQuery(".multicombo").multiselect().multiselectfilter();
        jQuery("#add").show();
        jQuery(".singlecombodefault").multiselect({
           multiple: false,
           header: true,
           noneSelectedText: "Select an Option",
           selectedList: 1
          });
         // operator="<option value='=' > equal </option><option value='like'>contain </option>";
    jQuery('#'+ref+'keyconfig'+i).html(relresult);
    jQuery('#'+ref+'keyconfig'+i).multiselect('refresh');
    });
}
function block_access(){
 var box = new ajaxLoader(jQuery( "#dialog" ));
jQuery.ajax({
        type:"POST",
        url:"index.php?module=cbMap&action=cbMapAjax&file=BlockAccess",
        data:jQuery('#theForm').serialize(),
        success: function(response){
        if (box) box.remove();
        jQuery("#dialog" ).dialog( "close" );
        location.reload(true);
        }
    });
}
function show_multiselect(moduleId,ref){i=1;
  var  relresult=document.getElementById('targetblock').options;
 document.getElementById('targetblocks').innerHTML = "<select id='"+ref+"keyconfig"+i+"' name='"+ref+"keyconfig[]'"+ "multiple class='small multicombo'>"+
                                +"</select>";
        jQuery(".multicombo").multiselect().multiselectfilter();
        jQuery("#add").show();
        jQuery(".singlecombodefault").multiselect({
           multiple: false,
           header: true,
           noneSelectedText: "Select an Option",
           selectedList: 1
          });
         // operator="<option value='=' > equal </option><option value='like'>contain </option>";
    jQuery('#'+ref+'keyconfig'+i).html(relresult);
    jQuery('#'+ref+'keyconfig'+i).multiselect('refresh');
}
function find_order(){
var orId=new Array();
var origin = new Array();
var target = new Array();
var delimiter = new Array();
jQuery("#conditiongrouptable_1  tr").each(function(){
     id = jQuery(this).attr('id');
     res = id.split("_");
     if(typeof res[2] != "undefined"){
     origin.push(jQuery('#originkeyconfig'+res[2]).val());
     target.push(jQuery('#targetkeyconfig'+res[2]).val());
     delimiter.push(jQuery('#delimiter'+res[2]).val());
     }
});
   jQuery("#orgVal").val(origin);
   jQuery("#delimiterVal").val(delimiter.join("@"));
   jQuery("#targetVal").val(target.join("::"));
}

function generate() {
var box = new ajaxLoader(jQuery( "#dialog" ));
jQuery.ajax({
        type:"POST",
        url:"index.php?module=cbMap&action=cbMapAjax&file=generateXML",
        data:jQuery('#theForm').serialize(),
        success: function(response){
        if (box) box.remove();
        jQuery("#dialog" ).dialog( "close" );
        location.reload(true);
        }
    });
}

function addColumnConditionGlue(columnIndex) {

	var columnConditionGlueElement = document.getElementById('columnconditionglue_'+columnIndex);

	if(columnConditionGlueElement) {
		columnConditionGlueElement.innerHTML = "<select name='fcon"+columnIndex+"' id='fcon"+columnIndex+"' class='detailedViewTextBox'>"+
													"<option value='and'>{'LBL_CRITERIA_AND'|@getTranslatedString:$MODULE}</option>"+
													"<option value='or'>{'LBL_CRITERIA_OR'|@getTranslatedString:$MODULE}</option>"+
												"</select>";
	}
}

function addConditionRow(groupIndex,mode) {

	var groupColumns = column_index_array[groupIndex];
	if(typeof(groupColumns) != 'undefined') {
		for(var i=groupColumns.length - 1; i>=0; --i) {
			var prevColumnIndex = groupColumns[i];
			if(document.getElementById('conditioncolumn_'+groupIndex+'_'+prevColumnIndex)) {
				addColumnConditionGlue(prevColumnIndex);
				break;
			}
		}
	}

	var columnIndex = advft_column_index_count+1;
	var nextNode = document.getElementById('groupfooter_'+groupIndex);

	var newNode = document.createElement('tr');
	newNodeId = 'conditioncolumn_'+groupIndex+'_'+columnIndex;
	newNode.setAttribute('id',newNodeId);
	newNode.setAttribute('name','conditionColumn');
	nextNode.parentNode.insertBefore(newNode, nextNode);

	node1 = document.createElement('td');
	node1.setAttribute('class', 'dvtCellLabel');
	node1.setAttribute('width', '30%');
	newNode.appendChild(node1);
       if(columnIndex != 0 || mode == "create")
       node1.innerHTML = '<select name="originkeyconfig[]" id="originkeyconfig'+columnIndex+'"  class="small singlecombofilter">'
                        +node1Result +"</select>";
	node2 = document.createElement('td');
	node2.setAttribute('class', 'dvtCellLabel');
	node2.setAttribute('width', '30%');
	newNode.appendChild(node2);
        jQuery('#originkeyconfig'+columnIndex).selectedIndex = -1;
        if(columnIndex != 0 || mode == "create")
	node2.innerHTML = '<select name="targetkeyconfig[]" id="targetkeyconfig'+columnIndex+'" multiple class="small multicombo">'+
							node2Result+
						'</select>';

	node3 = document.createElement('td');
	node3.setAttribute('class', 'dvtCellLabel');
        node3.setAttribute('width', '30%');
	newNode.appendChild(node3);
        node3.innerHTML = '<select name="delimiter'+columnIndex+'" id="delimiter'+columnIndex+'" style="display:none;" class="small singlecombodefault">'+
							'<option value="--None--">--None--</option>'+
							'<option value=",">,</option>'+
                                                        '<option value="_">_</option>'+
                                                        '<option value=";">;</option>'+
						'</select>';

	node5 = document.createElement('td');
	node5.setAttribute('class', 'dvtCellLabel');
	node5.setAttribute('width', '20px');
	newNode.appendChild(node5);
	node5.innerHTML = '<a onclick="deleteColumnRow('+groupIndex+','+columnIndex+');" href="javascript:;">'+
							'<img src="themes/images/delete.gif" align="absmiddle" title="{$MOD.LBL_DELETE}..." border="0">'+
						'</a>';

	if(document.getElementById('fcol'+columnIndex)) updatefOptions(document.getElementById('fcol'+columnIndex), 'fop'+columnIndex);
	if(typeof(column_index_array[groupIndex]) == 'undefined') column_index_array[groupIndex] = [];
	column_index_array[groupIndex].push(columnIndex);
	advft_column_index_count++;
        jQuery(".singlecombofilter").multiselect({
        multiple: false,
        header: true,
        noneSelectedText: "Select an Option",
        selectedList: 1
        }).multiselectfilter();

        jQuery(".multicombo").multiselect().multiselectfilter();
        jQuery(".singlecombodefault").multiselect({
           multiple: false,
           header: true,
           noneSelectedText: "Select an Option",
           selectedList: 1
          });
}


function addGroupConditionGlue(groupIndex) {

	var groupConditionGlueElement = document.getElementById('groupconditionglue_'+groupIndex);
	if(groupConditionGlueElement) {
		groupConditionGlueElement.innerHTML = "<select name='gpcon"+groupIndex+"' id='gpcon"+groupIndex+"' class='small'>"+
												"<option value='and'>{'LBL_CRITERIA_AND'|@getTranslatedString:$MODULE}</option>"+
												"<option value='or'>{'LBL_CRITERIA_OR'|@getTranslatedString:$MODULE}</option>"+
											"</select>";
	}
}

function addConditionGroup(parentNodeId) {
	var groupIndex = advft_group_index_count +1;
	var parentNode = document.getElementById(parentNodeId);
	var newNode = document.createElement('div');
	newNodeId = 'conditiongroup_'+groupIndex;
	newNode.setAttribute('id',newNodeId);
	newNode.setAttribute('name','conditionGroup');
	newNode.innerHTML = "<table class='small crmTable' border='0' cellpadding='5' cellspacing='1' width='100%' valign='top' id='conditiongrouptable_"+groupIndex+"'>"+
							"<tr id='groupfooter_"+groupIndex+"'>"+
								"<td colspan='5' align='left'>"+
									"<input type='button' class='crmbutton edit small' value='ADD' onclick='addConditionRow(\""+groupIndex+"\")' />"+
								"</td>"+
							"</tr>"+
						"</table>"+
						"<table class='small' border='0' cellpadding='5' cellspacing='1' width='100%' valign='top'>"+
							"<tr><td align='center' id='groupconditionglue_"+groupIndex+"'>"+
							"</td></tr>"+
						"</table>";

	parentNode.appendChild(newNode);

	group_index_array.push(groupIndex);
	advft_group_index_count++;
}

function addNewConditionGroup(parentNodeId,mode,nrFields,origin,target,originFieldsArr,targetFieldsArr,selDelimiter) {
	addConditionGroup(parentNodeId);
        if(mode == "create")
	addConditionRow(advft_group_index_count,mode);
        else {
        var i;
        var module_list = jQuery("#module_list").val();
        var related_modules = jQuery("#related_modules").val();
        var rel_fields= jQuery("#rel_fields").val();
        var columnIndex = advft_column_index_count+1;

        jQuery.post('index.php', {
        module:'cbMap',
        action:'cbMapAjax',
        file:'getFields', ajax:'true',pmodule: origin,modtype:"origin",module_list:module_list,related_modules:related_modules,rel_fields:rel_fields,
        async:false
        },
        function(relresult){
                node1Result = relresult;
       jQuery.post('index.php', {
        module:'cbMap',
        action:'cbMapAjax',
        file:'getFields', ajax:'true',pmodule: target,modtype:"target",module_list:module_list,related_modules:related_modules,rel_fields:rel_fields,
        async:false
        },
        function(relresult){
        node2Result = relresult;
        for(i=0;i<nrFields;i++)
        addConditionRow(advft_group_index_count,mode);
        setSelectedFields(originFieldsArr,targetFieldsArr,nrFields,selDelimiter);
        });
        });

        }
}

function setSelectedFields(originFieldsArr,targetFields,nrFields,selDelimiter){
   orgFieldsArr = Array();
   orgFieldsArr = originFieldsArr.split(",");
   targetFieldsArr = Array();
   targetFieldsArr = targetFields.split("::");
   delimiterArr = Array();
   delimiterArr =  selDelimiter.split("@");
   var counter = 0;
  jQuery("#conditiongrouptable_1  tr").each(function(){
     id = jQuery(this).attr('id');
     res = id.split("_");
     if(typeof res[2] != "undefined"){
        jQuery('#originkeyconfig'+res[2]).val(orgFieldsArr[counter]);
        jQuery('#originkeyconfig'+res[2]).multiselect('refresh');
        var array = targetFieldsArr[counter].split(',');
        jQuery('#targetkeyconfig'+res[2]).val(array);
        jQuery('#targetkeyconfig'+res[2]).multiselect('refresh');
        jQuery('#delimiter'+res[2]).val(delimiterArr[counter]);
        jQuery('#delimiter'+res[2]).multiselect('refresh');
       counter++;
     }
});
}

function deleteColumnRow(groupIndex, columnIndex) {
	removeElement('conditioncolumn_'+groupIndex+'_'+columnIndex);

	var groupColumns = column_index_array[groupIndex];
	var keyOfTheColumn = groupColumns.indexOf(columnIndex);
	var isLastElement = true;

	for(var i=keyOfTheColumn; i<groupColumns.length; ++i) {
		var nextColumnIndex = groupColumns[i];
		var nextColumnRowId = 'conditioncolumn_'+groupIndex+'_'+nextColumnIndex;
		if(document.getElementById(nextColumnRowId)) {
			isLastElement = false;
			break;
		}
	}

	if(isLastElement) {
		for(var i=keyOfTheColumn-1; i>=0; --i) {
			var prevColumnIndex = groupColumns[i];
			var prevColumnGlueId = "fcon"+prevColumnIndex;
			if(document.getElementById(prevColumnGlueId)) {
				removeElement(prevColumnGlueId);
				break;
			}
		}
	}
}

function removeElement(elementId) {
	var element = document.getElementById(elementId);
	if(element) {
		var parent = element.parentNode;
		if(parent) {
			parent.removeChild(element);
		} else {
			element.remove();
		}
	}
}