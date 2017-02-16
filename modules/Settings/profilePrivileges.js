/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
var Imagid_array = ['img_2','img_4','img_6','img_7','img_8','img_9','img_10','img_13','img_14','img_18','img_19','img_20','img_21','img_22','img_23','img_26'];

function fnToggleVIew(obj) {
	obj = "#"+obj;
	if(jQuery(obj).hasClass('hideTable')) {
		jQuery(obj).removeClass('hideTable');
	} else {
		jQuery(obj).addClass('hideTable');
	}
}
function invokeview_all()
{
	if(document.getElementById('view_all_chk').checked == true)
	{
		for(var i = 0;i < document.profileform.elements.length;i++)
		{
			if(document.profileform.elements[i].type == 'checkbox')
			{
				if(document.profileform.elements[i].id.indexOf('tab_chk_com_') != -1 || document.profileform.elements[i].id.indexOf('tab_chk_4') != -1 || document.profileform.elements[i].id.indexOf('_field_') != -1)
					document.profileform.elements[i].checked = true;
			}
		}
		showAllImages();
	}
}
function showAllImages()
{
	for(var j=0;j < Imagid_array.length;j++)
	{
		if(typeof(document.getElementById(Imagid_array[j])) != 'undefined')
			document.getElementById(Imagid_array[j]).style.display = 'block';
	}
}
function invokeedit_all()
{
	if(document.getElementById('edit_all_chk').checked == true)
	{
		document.getElementById('view_all_chk').checked = true;
		for(var i = 0;i < document.profileform.elements.length;i++)
		{
			if(document.profileform.elements[i].type == 'checkbox')
			{
				if(document.profileform.elements[i].id.indexOf('tab_chk_com_') != -1 || document.profileform.elements[i].id.indexOf('tab_chk_4') != -1 || document.profileform.elements[i].id.indexOf('tab_chk_1') != -1 || document.profileform.elements[i].id.indexOf('_field_') != -1)
					document.profileform.elements[i].checked = true;
			}
		}
		showAllImages();
	}
}
function unselect_edit_all()
{
	document.getElementById('edit_all_chk').checked = false;
}
function unselect_view_all()
{
	document.getElementById('view_all_chk').checked = false;
}
function unSelectView(id)
{
	var createid = 'tab_chk_1_'+id;
	var deleteid = 'tab_chk_2_'+id;
	var tab_id = 'tab_chk_com_'+id;
	if(document.getElementById('tab_chk_4_'+id).checked == false)
	{
		unselect_view_all();
		unselect_edit_all();
		document.getElementById(createid).checked = false;
		document.getElementById(deleteid).checked = false;
		document.getElementById(tab_id).checked = false;
	}else
	{
		var imageid = 'img_'+id;
		var viewid = 'tab_chk_4_'+id;
		if(typeof(document.getElementById(imageid)) != 'undefined')
			document.getElementById(imageid).style.display = 'block';
		document.getElementById('tab_chk_com_'+id).checked = true;
	}
}
function unSelectCreate(id)
{
	var viewid = 'tab_chk_4_'+id;
	if(document.getElementById('tab_chk_1_'+id).checked == false)
	{
		unselect_edit_all();
	}else
	{
		var imageid = 'img_'+id;
		var viewid = 'tab_chk_4_'+id;
		if(typeof(document.getElementById(imageid)) != 'undefined')
			document.getElementById(imageid).style.display = 'block';
		document.getElementById('tab_chk_com_'+id).checked = true;
		document.getElementById(viewid).checked = true;
	}
}
function unSelectDelete(id)
{
	var contid = id+'_view';
	if(document.getElementById('tab_chk_2_'+id).checked == false)
	{
	}else
	{
		var imageid = 'img_'+id;
		var viewid = 'tab_chk_4_'+id;
		if(typeof(document.getElementById(imageid)) != 'undefined')
			document.getElementById(imageid).style.display = 'block';
		document.getElementById('tab_chk_com_'+id).checked = true;
		document.getElementById(viewid).checked = true;
	}

}
function hideTab(id)
{
	var createid = 'tab_chk_1_'+id;
	var viewid = 'tab_chk_4_'+id;
	var deleteid = 'tab_chk_2_'+id;
	var imageid = 'img_'+id;
	var contid = id+'_view';
	if(document.getElementById('tab_chk_com_'+id).checked == false)
	{
		unselect_view_all();
		unselect_edit_all();
		if(typeof(document.getElementById(imageid)) != 'undefined')
			document.getElementById(imageid).style.display = 'none';
		document.getElementById(contid).className = 'hideTable';
		if(typeof(document.getElementById(createid)) != 'undefined')
			document.getElementById(createid).checked = false;
		if(typeof(document.getElementById(deleteid)) != 'undefined')
			document.getElementById(deleteid).checked = false;
		if(typeof(document.getElementById(viewid)) != 'undefined')
			document.getElementById(viewid).checked = false;
	}else
	{
		if(typeof(document.getElementById(imageid)) != 'undefined')
			document.getElementById(imageid).style.display = 'block';
		if(typeof(document.getElementById(createid)) != 'undefined')
			document.getElementById(createid).checked = true;
		if(typeof(document.getElementById(deleteid)) != 'undefined')
			document.getElementById(deleteid).checked = true;
		if(typeof(document.getElementById(viewid)) != 'undefined')
			document.getElementById(viewid).checked = true;
		var fieldid = id +'_field_';
		for (var i = 0;i < document.profileform.elements.length;i++) {
			if (document.profileform.elements[i].type == 'checkbox' && document.profileform.elements[i].id.indexOf(fieldid) != -1) {
				document.profileform.elements[i].checked = true;
			}
		}
	}
}
function selectUnselect(oCheckbox)
{
	if(oCheckbox.checked == false)
	{
		unselect_view_all();
		unselect_edit_all();
	}
}
function initialiseprofile()
{
	var module_array = Array(1,2,4,6,7,8,9,10,13,14,15,17,18,19,20,21,22,23,24,25,26,27);
	for (var i=0;i < module_array.length;i++)
	{
		hideTab(module_array[i]);
	}
}
//initialiseprofile();

function toogleAccess(elementId) {
	var element = document.getElementById(elementId);
	if(element == null || typeof(element) == 'undefined') return;

	if(element.value == 0) {
		element.value = 1;
	} else {
		element.value = 0;
	}

	var lockedImage = document.getElementById(elementId+'_locked');
	if(lockedImage != null && typeof(lockedImage) != 'undefined') {
		if(lockedImage.style.display == 'none')
			lockedImage.style.display = 'inline';
		else
			lockedImage.style.display = 'none';
	}

	var unlockedImage = document.getElementById(elementId+'_unlocked');
	if(unlockedImage != null && typeof(unlockedImage) != 'undefined') {
		if(unlockedImage.style.display == 'none')
			unlockedImage.style.display = 'inline';
		else
			unlockedImage.style.display = 'none';
	}
}
