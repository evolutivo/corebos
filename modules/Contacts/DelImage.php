<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

function DelImage($id)
{
	global $adb;
	$imgmod = vtlib_purify($_REQUEST['ImageModule']);
	$fname = vtlib_purify($_REQUEST['fieldname']);
	if (empty($imgmod)) $imgmod = 'Contacts';
	if ($imgmod == 'Contacts' and $fname=='imagename') {
		$imageattachment = 'Image';
	} else {
		$imageattachment = 'Attachment';
	}
	$aname = vtlib_purify($_REQUEST['attachmentname']);
	$query= "select vtiger_seattachmentsrel.attachmentsid
	 from vtiger_seattachmentsrel
	 inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_seattachmentsrel.attachmentsid
	 inner join vtiger_attachments on vtiger_crmentity.crmid=vtiger_attachments.attachmentsid
	 where vtiger_crmentity.setype='$imgmod $imageattachment'
	  and vtiger_attachments.name=?
	  and vtiger_seattachmentsrel.crmid=?";
	$result = $adb->pquery($query, array($aname,$id));
	if ($result and $adb->num_rows($result)==1) {
		$attachmentsid = $adb->query_result($result,0,"attachmentsid");

		$rel_delquery='delete from vtiger_seattachmentsrel where crmid=? and attachmentsid=?';
		$adb->pquery($rel_delquery, array($id, $attachmentsid));
		$crm_delquery="delete from vtiger_crmentity where crmid=?";
		$adb->pquery($crm_delquery, array($attachmentsid));

		$sql = 'SELECT tablename,columnname,fieldname FROM vtiger_field
		 WHERE uitype=69 and vtiger_field.tabid = ? and fieldname = ?';
		$tabid = getTabid($imgmod);
		$result = $adb->pquery($sql, array($tabid,$fname));
		if ($result and $adb->num_rows($result)==1) {
			include_once "modules/$imgmod/$imgmod.php";
			$crmmod = new $imgmod();
			$tblname = $adb->query_result($result, 0, 'tablename');
			$colname = $adb->query_result($result, 0, 'columnname');
			$upd = "update $tblname set $colname='' where ".$crmmod->tab_name_index[$tblname].'=?';
			$adb->pquery($upd, array($id));
		}
	}
}

function DelAttachment($id) {
	global $adb;
	$selresult = $adb->pquery('select name,path from vtiger_attachments where attachmentsid=?', array($id));
	if ($selresult and $adb->num_rows($selresult)==1) {
		unlink($adb->query_result($selresult,0,'path').$id."_".$adb->query_result($selresult,0,'name'));
		$query="delete from vtiger_seattachmentsrel where attachmentsid=?";
		$adb->pquery($query, array($id));
		$query="delete from vtiger_attachments where attachmentsid=?";
		$adb->pquery($query, array($id));
	}
}
$id = vtlib_purify($_REQUEST['recordid']);
$id = preg_replace('/[^0-9]/', '', $id);
if(isset($_REQUEST["attachmodule"]) && $_REQUEST["attachmodule"]=='Emails')
{
	DelAttachment($id);
}
else
{
	DelImage($id);
}
echo 'SUCCESS';
?>
