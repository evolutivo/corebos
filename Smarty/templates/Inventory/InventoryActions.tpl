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

<!-- Avoid this actions display for PriceBook module-->

<!-- display the Inventory Actions based on the Inventory Modules -->
	<div class="flexipageComponent"> <!-- {if $MODULE eq 'PriceBooks'} margin-top: 5rem; {/if} -->
	 	<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header" style="margin: 0;">
	    	<div class="slds-card__header slds-grid">
		        <header class="slds-media slds-media--center slds-has-flexi-truncate">
		            <div class="slds-media__body">
		                <h2 class="header-title-container" >
		                    <span class="slds-text-heading--small slds-truncate actionLabel">
		                        <b>{$APP.LBL_ACTIONS}</b>
		                    </span>
		                </h2>
		            </div>
		        </header>
	    	</div>
	    	<div class="slds-card__body slds-card__body--inner">
	    		
	    		<!-- Module based actions starts -->
				{if $MODULE eq 'Products' || $MODULE eq 'Services'}
				   <!-- Product/Services Actions starts -->
					{if $MODULE eq 'Products'}
						{assign var='module_id' value='product_id'}
					{else}
						{assign var='module_id' value='parent_id'}
					{/if}
					<div class="actionData">
						<a href="javascript: document.DetailView.module.value='Quotes'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateQuote.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="2" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='Quotes'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.Quote}</a> 
					</div>

					<div class="actionData">
						<a href="javascript: document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateInvoice.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="2" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.Invoice}</a> 
					</div>

					<div class="actionData">
						<a href="javascript: document.DetailView.module.value='SalesOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateSalesOrder.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="2" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='SalesOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.SalesOrder}</a> 
					</div>

					<div class="actionData">
						<a href="javascript: document.DetailView.module.value='PurchaseOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu"><img src="{'actionGenPurchaseOrder.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="2" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='PurchaseOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.{$module_id}.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.PurchaseOrder}</a> 
					</div>

				{elseif $MODULE eq 'Vendors'}
				 <!-- Vendors Actions starts -->
					<div class="actionData">
						<a href="javascript: document.DetailView.module.value='PurchaseOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='Vendors'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.vendor_id.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">	<img src="{'actionGenPurchaseOrder.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='PurchaseOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='Vendors'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.parent_id.value='{$ID}'; document.DetailView.vendor_id.value='{$ID}'; document.DetailView.record.value=''; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.PurchaseOrder}</a> 
					</div>
					<!-- <div class="actionData">
						<img src="{'pointer.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle"/>
						<a href="#" class="webMnu">List PurchaseOrders for this Vendor</a> 
					</div> -->
				<!-- Vendors Actions ends -->
				{elseif $MODULE eq 'PurchaseOrder'}
				<!-- PO Actions starts -->
					<!--  <div class="actionData">
						<img src="{'pointer.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle"/>
						<a href="#" class="webMnu">List Other PurchaseOrders to this Vendor</a> 
					 </div> -->
			    <!-- PO Actions ends -->
			    {elseif $MODULE eq 'SalesOrder'}
	   			<!-- SO Actions starts -->
	   				<div class="actionData">
	   					<a href="javascript: document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='SalesOrder'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.record.value='{$ID}'; document.DetailView.convertmode.value='sotoinvoice'; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateInvoice.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_module.value='SalesOrder'; document.DetailView.return_action.value='DetailView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.record.value='{$ID}'; document.DetailView.convertmode.value='sotoinvoice'; document.DetailView.submit();" class="webMnu">{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.Invoice}</a> 
					</div>
					<!-- <div class="actionData">
						<img src="{'pointer.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle"/>
						<a href="#" class="webMnu">List Linked Quotes</a>
					</div>
					<div class="actionData">
						<img src="{'pointer.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle"/>
						<a href="#" class="webMnu">List Linked Invoices</a> 
					</div> -->
	   			<!-- SO Actions ends -->
	   			{elseif $MODULE eq 'Quotes'}
	   			<!-- Quotes Actions starts -->
		   			<div class="actionData">
		   				<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='{$CONVERTMODE}'; document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateInvoice.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="5" align="absmiddle" border="0"/></a> 
						<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='{$CONVERTMODE}'; document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.submit();" class="webMnu">{$APP.LBL_GENERATE} {$APP.Invoice}</a> 
	   				</div>
	   				<div class="actionData">
		   				<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='quotetoso'; document.DetailView.module.value='SalesOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.submit();" class="webMnu"><img src="{'actionGenerateSalesOrder.gif'|@vtiger_imageurl:$THEME}" class="noSpace" hspace="5" align="absmiddle" border="0"/></a>
						<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='quotetoso'; document.DetailView.module.value='SalesOrder'; document.DetailView.action.value='EditView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.submit();" class="webMnu">{$APP.LBL_GENERATE} {$APP.SalesOrder}</a> 
					</div>
				<!-- Quotes Actions ends -->
				{elseif $MODULE eq 'Invoice'}
			   <!-- Invoice Actions starts -->
				   <!-- <div class="actionData">
						<img src="{'pointer.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle"/>
						<a href="#" class="webMnu">List Linked Quotes</a> 
					</div> -->
			   <!-- Invoice Actions ends -->
				{/if}
	<!-- Module based actions ends -->

{* vtlib customization: Custom links on the Detail view basic links *}
{if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEWBASIC}
	{foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
		<div class="actionData">
			{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
			{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
			{if $customlink_label eq ''}
				{assign var="customlink_label" value=$customlink_href}
			{else}
				{* Pickup the translated label provided by the module *}
				{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
			{/if}
			{if $CUSTOMLINK->linkicon}
			<a class="webMnu" href="{$customlink_href}"><img hspace=5 align="absmiddle" border=0 src="{$CUSTOMLINK->linkicon}"></a>
			{/if}
			<a class="webMnu" href="{$customlink_href}">{$customlink_label}</a>
		</div>
	{/foreach}
{/if}

{* vtlib customization: Custom links on the Detail view *}
{if $CUSTOM_LINKS}

	{if !empty($CUSTOM_LINKS.DETAILVIEW)}
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<tr><td align="left" class="dvtUnSelectedCell dvtCellLabel">
				<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></a>
			</td></tr>
		</table>
		<br>
		<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay" 
			onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
			<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></td></tr>
			<tr>
				<td>
					{foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEW}
						{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
						{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
						{if $customlink_label eq ''}
							{assign var="customlink_label" value=$customlink_href}
						{else}
							{* Pickup the translated label provided by the module *}
							{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
						{/if}
						<a href="{$customlink_href}" class="drop_down">{$customlink_label}</a>
					{/foreach}
				</td>
			</tr>
			</table>
		</div>
	{/if}
	
	{if !empty($CUSTOM_LINKS.DETAILVIEWWIDGET)}
		{foreach key=CUSTOMLINK_NO item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWWIDGET}
			{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
			{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
			{* Ignore block:// type custom links which are handled earlier *}
			{if !preg_match("/^block:\/\/.*/", $customlink_href)}
				{if $customlink_label eq ''}
					{assign var="customlink_label" value=$customlink_href}
				{else}
					{* Pickup the translated label provided by the module *}
					{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
				{/if}
				<br/>
				<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
					<tr>
						<td class="rightMailMergeHeader">
							<b>{$customlink_label}</b>
							<img id="detailview_block_{$CUSTOMLINK_NO}_indicator" style="display:none;" src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
						</td>
					</tr>
					<tr style="height:25px">
						<td class="rightMailMergeContent"><div id="detailview_block_{$CUSTOMLINK_NO}"></div></td>
					</tr>
					<script type="text/javascript">
						vtlib_loadDetailViewWidget("{$customlink_href}", "detailview_block_{$CUSTOMLINK_NO}", "detailview_block_{$CUSTOMLINK_NO}_indicator");
					</script>
				</table>
			{/if}
		{/foreach}
	{/if}

{/if}
{* END *}
<!-- Action links END -->
			</div>
		</article>
	</div><!-- /.flexipageComponent -->
<br>
<!-- Following condition is added to avoid the Tools section in Products and Vendors -->
{if $MODULE neq 'Products' && $MODULE neq 'Services' && $MODULE neq 'Vendors' && $MODULE neq 'PriceBooks'}
	
<div class="flexipageComponent" style="background-color: #fff;">
    <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
         aria-describedby="header" style="margin: 0;">
        <div class="slds-card__header slds-grid">
            <header class="slds-media slds-media--center slds-has-flexi-truncate">
                <div class="slds-media__body">
                    <h2 class="header-title-container">
                        <span class="slds-text-heading--small slds-truncate actionLabel">
                            <b>{$APP.Tools}</b>
                        </span>
                    </h2>
                </div>
            </header>
        </div>
        <div class="slds-card__body slds-card__body--inner">
	

			<!-- To display the Export To PDF link for PO, SO, Quotes and Invoice - starts -->
			{if $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Invoice'}

				{if $MODULE eq 'SalesOrder'}
					{assign var=export_pdf_action value="CreateSOPDF"}
				{else}
					{assign var=export_pdf_action value="CreatePDF"}
				{/if}

			   <div class="actionData">
					<a href="index.php?module={$MODULE}&action={$export_pdf_action}&return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}" class="webMnu"><img src="{'actionGeneratePDF.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
					<a href="index.php?module={$MODULE}&action={$export_pdf_action}&return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}" class="webMnu">{$APP.LBL_EXPORT_TO_PDF}</a>
				</div>
			<!-- Added to give link to  send Invoice PDF through mail -->
			 <div class="actionData">
					<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='{$MODULE}'; document.DetailView.action.value='SendPDFMail'; document.DetailView.record.value='{$ID}'; document.DetailView.return_id.value='{$ID}'; sendpdf_submit();" class="webMnu"><img src="{'PDFMail.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
					<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='{$MODULE}'; document.DetailView.action.value='SendPDFMail'; document.DetailView.record.value='{$ID}'; document.DetailView.return_id.value='{$ID}'; sendpdf_submit();" class="webMnu">{$APP.LBL_SEND_EMAIL_PDF}</a> 
				</div>
			{/if}
			<!-- Above if condition is added to avoid the Tools section in Products and Vendors -->

		</div>
	</article>
</div><!-- /.flexipageComponent -->
{literal}
<script type='text/javascript'>
function sendpdf_submit()
{
	// Submit the form to get the attachment ready for submission
	document.DetailView.submit();
{/literal}
	{if $MODULE eq 'Invoice'}
		OpenCompose('{$INV_NO}','Invoice:{'SINGLE_Invoice'|@getTranslatedString:$MODULE}',{$ID});
	{elseif $MODULE eq 'Quotes'}
		OpenCompose('{$QUO_NO}','Quote:{'SINGLE_Quotes'|@getTranslatedString:$MODULE}',{$ID});
	{elseif $MODULE eq 'PurchaseOrder'}
		OpenCompose('{$PO_NO}','PurchaseOrder:{'SINGLE_PurchaseOrder'|@getTranslatedString:$MODULE}',{$ID});
	{elseif $MODULE eq 'SalesOrder'}
		OpenCompose('{$SO_NO}','SalesOrder:{'SINGLE_SalesOrder'|@getTranslatedString:$MODULE}',{$ID});
	{/if}
{literal}
}
</script>
{/literal}
{else}
</table>
{/if}
