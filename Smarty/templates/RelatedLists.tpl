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
<script type="text/javascript" src="modules/PriceBooks/PriceBooks.js"></script>
<script type="text/javascript" src="include/js/ListView.js"></script>
{literal}
<script>

function editProductListPrice(id,pbid,price) {
		document.getElementById("status").style.display="inline";
		jQuery.ajax({
			method: 'POST',
			url: 'index.php?action=ProductsAjax&file=EditListPrice&return_action=CallRelatedList&return_module=PriceBooks&module=Products&record='+id+'&pricebook_id='+pbid+'&listprice='+price,
		}).done(function (response) {
					document.getElementById("status").style.display="none";
					document.getElementById("editlistprice").innerHTML= response;
				}
		);
}

function gotoUpdateListPrice(id,pbid,proid) {
		document.getElementById("status").style.display="inline";
		document.getElementById("roleLay").style.display = "none";
		var listprice=document.getElementById("list_price").value;
				jQuery.ajax({
						method: 'POST',
						url: 'index.php?module=Products&action=ProductsAjax&file=UpdateListPrice&ajax=true&return_action=CallRelatedList&return_module=PriceBooks&record='+id+'&pricebook_id='+pbid+'&product_id='+proid+'&list_price='+listprice,
				}).done(function (response) {
							document.getElementById("status").style.display="none";
							document.getElementById("RLContents").innerHTML=response;
						}
				);
}
function showHideStatus(sId,anchorImgId,sImagePath)
{
	oObj = eval(document.getElementById(sId));
	if(oObj.style.display == 'block')
	{
		oObj.style.display = 'none';
		if(anchorImgId !=null){
			eval(document.getElementById(anchorImgId)).src =  'themes/images/inactivate.gif';
			eval(document.getElementById(anchorImgId)).alt = 'Display';
			eval(document.getElementById(anchorImgId)).title = 'Display';
		}
	}
	else
	{
		oObj.style.display = 'block';
		if(anchorImgId !=null){
			eval(document.getElementById(anchorImgId)).src = 'themes/images/activate.gif';
			eval(document.getElementById(anchorImgId)).alt = 'Hide';
			eval(document.getElementById(anchorImgId)).title = 'Hide';
		}
	}
}
{/literal}
</script>
	{include file='Buttons_List.tpl'}
<!-- Contents -->
{ngtab2}
<div id="editlistprice" style="position:absolute;width:300px;"></div>
<!-- Contents -->
<div id="editlistprice" style="position:absolute;width:300px;"></div>
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
	<tr>
		<td>
			<!-- PUBLIC CONTENTS STARTS-->
			<div class="slds-truncate">
				<table class="slds-table slds-no-row-hover slds-table--cell-buffer slds-table-moz" style="background-color: #f7f9fb;">
					<tr class="slds-text-title--caps">
						<td style="padding: 0;">
						{* Module Record numbering, used MOD_SEQ_ID instead of ID *}
						{assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
						{if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
							<div class="slds-page-header s1FixedFullWidth s1FixedTop forceHighlightsStencilDesktop" style="height: 70px;">
								<div class="slds-grid primaryFieldRow" style="transform: translate3d(0, -8.65823px, 0);">
									<div class="slds-grid slds-col slds-has-flexi-truncate slds-media--center">
										<div class="profilePicWrapper slds-media slds-no-space" style="transform: scale3d(0.864715, 0.864715, 1) translate3d(4.32911px, 2.16456px, 0);">
											<div class="slds-media__figure slds-icon forceEntityIcon">
												<span class="photoContainer forceSocialPhoto">
													<div class="small roundedSquare forceEntityIcon img-background">
														<span class="uiImage">
															<img src="{$MODULEICON|@vtiger_imageurl:$THEME}" class="icon " alt="{$MODULE}" title="{$MODULE}">
														</span>
													</div>
												</span>
											</div>
										</div>
										<div class="slds-media__body">
											<p class="slds-text-heading--label slds-line-height--reset" style="opacity: 1;">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</p>
											<h1 class="slds-page-header__title slds-m-right--small slds-truncate slds-align-middle">
												<span class="uiOutputText"><font color="purple">[ {$USE_ID_VALUE} ] </font>{$NAME}</span>
												<span class="small" style="text-transform: capitalize;">{$UPDATEINFO}</span>
												<span id="vtbusy_info" style="display:none; text-transform: capitalize;" valign="bottom">
													<img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0">
												</span>
											</h1>
										</div>
									</div>
								</div>
							</div>
						</td>
					</tr>
				</table>
				<br>

				<!-- Account details tabs -->
				<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
					<tr>
						<td valign=top align=left>
							<div class="slds-truncate">
								<table class="slds-table slds-no-row-hover slds-table-moz dvtContentSpace">
									<tr>
										<td valign="top" style="padding: 0;">
											<!-- content cache -->
											<div class="slds-table--scoped">
												{if isset($OP_MODE) && $OP_MODE eq 'edit_view'}
													{assign var="action" value="EditView"}
												{else}
													{assign var="action" value="DetailView"}
												{/if}
												<ul class="slds-tabs--scoped__nav" role="tablist" style="margin-bottom: 0;">
													<li class="slds-tabs--scoped__item" title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}" role="presentation">
														<a class="slds-tabs--scoped__link " href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" role="tab" tabindex="0" aria-selected="true" aria-controls="tab--scoped-1" id="tab--scoped--1__item">{$SINGLE_MOD} {$APP.LBL_INFORMATION}</a>
													</li>
													{if $HASRELATEDPANES eq 'true'}
														{include file='RelatedPanes.tpl' tabposition='bottom'}
													{else}
														<li class="slds-tabs--scoped__item active" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}" role="presentation">
															{if $smarty.request.ng_tab neq ''}
																<a class="slds-tabs--scoped__link" href="javascript:void(0);" role="tab" tabindex="-1" aria-selected="false" aria-controls="tab--scoped-relatedList2" id="tab--scoped-relatedList2__item">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a>
															{else}
																<a class="slds-tabs--scoped__link" role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab--scoped-relatedList2" id="tab--scoped-relatedList2__item">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a>
															{/if}
														</li>
													{/if}
													{include file='RelatedListNg.tpl' SOURCE='RL'}
												</ul>
											<!-- content cache -->
												<div id="tab--scoped-1" role="tabpanel" aria-labelledby="tab--scoped-1__item" class="slds-tabs--scoped__content slds-truncate" style="padding-top: 0;">
													<table class="slds-table slds-no-row-hover slds-table-moz" style="border-collapse:separate; border-spacing: 1rem 2rem;">
														<tr>
															<td style="padding:0;">
															<!-- General details -->
															{include file='RelatedListsHidden.tpl'}
																{if empty($ng_tab)}
																	<div id="RLContents">{include file='RelatedListContents.tpl'}</div>
																{/if}
																{if !empty($ng_tab)}
																	<div id="NGRLContents">
																		{if !empty($CUSTOM_LINKS.RELATEDVIEWWIDGET)}
																			{foreach item=CUSTOM_LINK_DETAILVIEWWIDGET from=$CUSTOM_LINKS.RELATEDVIEWWIDGET}
																				{if preg_match("/^block:\/\/.*/", $CUSTOM_LINK_DETAILVIEWWIDGET->linkurl)&& $CUSTOM_LINK_DETAILVIEWWIDGET->related_tab eq $ng_tab}
																					<tr>
																						<td style="padding:5px;" >
																							{dvwidget}
																							<br/>
																						</td>
																					</tr>
																				{/if}
																			{/foreach}
																		{/if}
																	</div>
																{/if}
															</form>
														<!-- End of Blocks-->
												</div>
											</div><!-- /.slds-tabs--scoped -->
										</td>
										{if isset($HASRELATEDPANESACTIONS) && $HASRELATEDPANESACTIONS eq 'true'}
											{include file='RelatedPaneActions.tpl'}
										{/if}
									</tr>
								</table>
							</div>
						</td>
					</tr>
				</table>
			</div>
			<!-- PUBLIC CONTENTS STOPS-->
		</td>
	</tr>
</table>

{if $MODULE eq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts' or $MODULE eq 'Campaigns' or $MODULE eq 'Vendors' or $MODULE eq 'Project' or $MODULE eq 'Potentials' or $MODULE eq 'ProjectTask' or $MODULE eq 'HelpDesk'}
<form name="SendMail" onsubmit="VtigerJS_DialogBox.block();"><div id="sendmail_cont" style="z-index:100001;position:absolute;width:300px;"></div></form>
{/if}

<script>
function OpenWindow(url)
{ldelim}
	openPopUp('xAttachFile',this,url,'attachfileWin',380,375,'menubar=no,toolbar=no,location=no,status=no,resizable=no');
{rdelim}
</script>
