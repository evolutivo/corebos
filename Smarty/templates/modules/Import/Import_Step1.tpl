{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--fixed-layout small detailview_table">
	<tr class="slds-line-height--reset">
		<td class="dvtCellLabel text-left heading2" width=20%>{'LBL_IMPORT_STEP_1'|@getTranslatedString:$MODULE}:</td>
		<td class="dvtCellInfo" width=80%>{'LBL_IMPORT_STEP_1_DESCRIPTION'|@getTranslatedString:$MODULE}
			<input type="hidden" name="type" value="csv" />
			<input type="hidden" name="is_scheduled" value="1" />
			<input type="file" name="import_file" id="import_file" class="small" size="60" onchange="ImportJs.checkFileType()"/>
			<!-- input type="hidden" name="userfile_hidden" value=""/ -->
		</td>
	</tr>
	<tr class="slds-line-height--reset">
		<td>&nbsp;</td>
		<td class="dvtCellInfo">{'LBL_IMPORT_SUPPORTED_FILE_TYPES'|@getTranslatedString:$MODULE}</td>
	</tr>
</table>