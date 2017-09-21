{*
<!--
/*+********************************************************************************
	* The contents of this file are subject to the vtiger CRM Public License Version 1.0
	* ("License"); You may not use this file except in compliance with the License
	* The Original Code is:	vtiger CRM Open Source
	* The Initial Developer of the Original Code is vtiger.
	* Portions created by vtiger are Copyright (C) vtiger.
	* All Rights Reserved.
	*********************************************************************************/
-->*}
<table border=0 cellspacing=0 cellpadding=0 width=100% class="small rel_mod_data_paging" style="border-bottom:1px solid #999999;padding:5px; background-color: #eeefff;">
	<tr>
		<td align="left">
			{$RELATEDLISTDATA.navigation.0} {if $MODULE eq 'Campaigns' && ($RELATED_MODULE eq 'Contacts' || $RELATED_MODULE eq 'Leads' || $RELATED_MODULE eq 'Accounts') && $RELATEDLISTDATA.entries|@count > 0} {/if}
		</td>
		<td align="center">{$RELATEDLISTDATA.navigation.1} </td>
		<td align="right">
			{if isset($RELATEDLISTDATA.CUSTOM_BUTTON)}{$RELATEDLISTDATA.CUSTOM_BUTTON}{/if}
			{if $HEADER eq 'Contacts' && $MODULE neq 'Campaigns' && $MODULE neq 'Accounts' && $MODULE neq 'Potentials' && $MODULE neq 'Products' && $MODULE neq 'Vendors'}
				{if $MODULE eq 'Calendar'}
					<input alt="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" title="{$APP.LBL_SELECT_CONTACT_BUTTON_LABEL}" accessKey="" class="slds-button slds-button--small slds-button--brand" value="{$APP.LBL_SELECT_BUTTON_LABEL} {$APP.Contacts}" onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}{$search_string}","test","width=640,height=602,resizable=0,scrollbars=0");' type="button" name="button">
				{elseif $MODULE neq 'Services'}
					<input type='hidden' name='createmode' value='link' />
					<input title="{$APP.LBL_ADD_NEW} {$APP.Contact}" accessyKey="F" class="slds-button slds-button--small slds-button_success" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="{$APP.LBL_ADD_NEW} {$APP.Contact}">
				{/if} 
			{elseif $HEADER eq 'Users' && $MODULE eq 'Calendar'}
				<input title="Change" accessKey="" tabindex="2" type="button" class="slds-button slds-button--small slds-button--brand" value="{$APP.LBL_SELECT_USER_BUTTON_LABEL}" name="button" onclick='return window.open("index.php?module=Users&return_module=Calendar&return_action={$return_modname}&activity_mode=Events&action=Popup&popuptype=detailview&form=EditView&form_submit=true&select=enable&return_id={$ID}&recordid={$ID}","test","width=640,height=525,resizable=0,scrollbars=0")' ;>
			{/if}
		</td>
	</tr>
</table>

<!-- Lighting Design -->
<table class="slds-table slds-table--bordered slds-table--resizable-cols slds-no-row-hover slds-table--fixed-layout ld-font">
	<!-- Table Headers -->
	<thead>
		<tr class="slds-line-height--reset">
			{if $MODULE eq 'Campaigns' && ($RELATED_MODULE eq 'Contacts' || $RELATED_MODULE eq 'Leads' || $RELATED_MODULE eq 'Accounts') && $RELATEDLISTDATA.entries|@count > 0}
			<th scope="col" class="slds-text-align--center" style="width: 3.25rem;">
				<div class="slds-th__action slds-th__action_form">
					<span class="slds-checkbox">
					<input name="{$RELATED_MODULE}_selectall" value="on" id="{$MODULE}_{$RELATED_MODULE}_selectCurrentPageRec" onclick="rel_toggleSelect(this.checked,'{$MODULE}_{$RELATED_MODULE}_selected_id','{$RELATED_MODULE}');" type="checkbox">
					<label class="slds-checkbox__label" for="{$MODULE}_{$RELATED_MODULE}_selectCurrentPageRec">
						<span class="slds-checkbox_faux"></span>
						<span class="slds-form-element__label slds-assistive-text">Select All</span>
					</label>
					</span>
				</div>
			</th>

			{/if} {foreach key=index item=_HEADER_FIELD from=$RELATEDLISTDATA.header}
			<th class="slds-is-sortable slds-text-title" scope="col">
				<span class="slds-truncate slds-text-link--reset slds-th__action">
					{$_HEADER_FIELD}
				</span>
			</th>
			{/foreach}
		</tr>
	</thead>
	<tbody>
		{if $MODULE eq 'Campaigns'}
		<tr class="rel_mod_data_campaigns">
			<th role="gridcell" id="{$MODULE}_{$RELATED_MODULE}_linkForSelectAll" class="linkForSelectAll" style="display:none;" colspan=10>
				<div class="slds-truncate">
					<span id="{$MODULE}_{$RELATED_MODULE}_selectAllRec" class="selectall" style="display:inline;" onClick="rel_toggleSelectAll_Records('{$MODULE}','{$RELATED_MODULE}',true,'{$MODULE}_{$RELATED_MODULE}_selected_id')">{$APP.LBL_SELECT_ALL} <span id={$RELATED_MODULE}_count class="folder"> </span> {$APP.LBL_RECORDS_IN} {$RELATED_MODULE|@getTranslatedString:$RELATED_MODULE} {$APP.LBL_RELATED_TO_THIS} {$APP.SINGLE_Campaigns}</span>
					<span id="{$MODULE}_{$RELATED_MODULE}_deSelectAllRec" class="selectall" style="display:none;" onClick="rel_toggleSelectAll_Records('{$MODULE}','{$RELATED_MODULE}',false,'{$MODULE}_{$RELATED_MODULE}_selected_id')">{$APP.LBL_DESELECT_ALL} {$RELATED_MODULE|@getTranslatedString:$RELATED_MODULE} {$APP.LBL_RELATED_TO_THIS} {$APP.SINGLE_Campaigns}</span>
				</div>
			</th>
		</tr>
		{/if} {foreach key=_RECORD_ID item=_RECORD from=$RELATEDLISTDATA.entries}
		<tr bgcolor=white class="rel_mod_data_row" id="row_{$_RECORD_ID}">
			{if $MODULE eq 'Campaigns' && ($RELATED_MODULE eq 'Contacts' || $RELATED_MODULE eq 'Leads' || $RELATED_MODULE eq 'Accounts')}
			<th scope="gridcell">
				<div class="slds-truncate">
					<input name="{$MODULE}_{$RELATED_MODULE}_selected_id" id="{$_RECORD_ID}" value="{$_RECORD_ID}" onclick="rel_check_object(this,'{$RELATED_MODULE}');" type="checkbox" {if isset($RELATEDLISTDATA.checked)}{$RELATEDLISTDATA.checked.$_RECORD_ID}{/if}>
				</div>
			</th>
			{/if} {foreach key=index item=_RECORD_DATA from=$_RECORD} {* vtlib customization: Trigger events on listview cell *}
			<th scope="gridcell" class="slds-text-align--left" onmouseover="vtlib_listview.trigger('cell.onmouseover', this)" onmouseout="vtlib_listview.trigger('cell.onmouseout', this)">{$_RECORD_DATA}</th>
			{/foreach}
		</tr>
		{foreachelse}
		<tr class="slds-hint-parent">
			<th scope="row" style="padding: .5rem;">
				<i>{$APP.LBL_NONE_INCLUDED}</i>
			</th>
		</tr>
		{/foreach}
	</tbody>
</table>
<!-- Lighitng Design -->


{if $MODULE eq 'Campaigns' && ($RELATED_MODULE eq 'Contacts' || $RELATED_MODULE eq 'Leads' || $RELATED_MODULE eq 'Accounts') && $RELATEDLISTDATA.entries|@count > 0 && $RESET_COOKIE eq 'true'}
<script type='text/javascript'>
set_cookie('{$RELATED_MODULE}_all', '');
</script>
{/if}