{*<!--

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

-->*}
{assign var="fromlink" value=""}
<div class="createview_field_row">
	<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
		<!-- Added this file to display the fields in Create Entity page based on ui types  -->
		{foreach key=label item=subdata from=$data}
			{if $header eq 'Product Details'}
				<tr name="tbl{$header|replace:' ':''}Content" class="slds-line-height--reset">
			{else}
				<tr name="tbl{$header|replace:' ':''}Content" class="slds-line-height--reset">
			{/if}
				{foreach key=mainlabel item=maindata from=$subdata}
					{include file='EditViewUIMass.tpl'}
				{/foreach}
			</tr>
		{/foreach}
	</table>
</div>