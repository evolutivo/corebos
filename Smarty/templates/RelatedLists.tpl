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
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
	<tr>
	<!-- <td valign=top><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td> -->
	<!-- <td class="showPanelBg" valign=top width=100%> -->
	<td>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" >
			<table class="slds-table slds-no-row-hover slds-table--cell-buffer">
				<tr class="slds-text-title--caps">
					<td style="padding: 0;">
                    {* Module Record numbering, used MOD_SEQ_ID instead of ID *}
                    {assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
                    {if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
					<div class="slds-page-header s1FixedFullWidth s1FixedTop forceHighlightsStencilDesktop" style="height: 70px;">
	                    <div class="slds-grid primaryFieldRow"
	                         style="transform: translate3d(0, -8.65823px, 0);">
	                        <div class="slds-grid slds-col slds-has-flexi-truncate slds-media--center">
	                        	<!-- ICON HERE -->
	                            <div class="profilePicWrapper slds-media slds-no-space"
	                                 style="transform: scale3d(0.864715, 0.864715, 1) translate3d(4.32911px, 2.16456px, 0);">
	                                <div class="slds-media__figure slds-icon forceEntityIcon">
	                                    <span class="photoContainer forceSocialPhoto">
	                                        <div class="small roundedSquare forceEntityIcon"
	                                             style="background-color: #A094ED">
	                                            <span class="uiImage">
	                                                <img src="https://brave-badger-117326-dev-ed.my.salesforce.com/img/icon/t4v35/standard/account_120.png"
	                                                     class="icon " alt="Account"
	                                                     title="Account">
	                                            </span>
	                                        </div>
	                                    </span>
	                                </div>
	                            </div>
	                            <!-- END ICON -->

	                            <div class="slds-media__body">
	                                <p class="slds-text-heading--label slds-line-height--reset"
	                                   style="opacity: 1;">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</p>
	                                <h1 class="slds-page-header__title slds-m-right--small slds-truncate slds-align-middle">
	                                    <span class="uiOutputText">[ {$USE_ID_VALUE} ] {$NAME}</span>
	                                    <span class="small" style="text-transform: capitalize;">{$UPDATEINFO}</span>&nbsp;&nbsp;&nbsp;
	                                    <span id="vtbusy_info" style="display:none; text-transform: capitalize;" valign="bottom">
	                                        <img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0">
	                                    </span>
	                                </h1>
	                            </div>

	                        </div>
	                    </div> <!-- /.primaryFieldRow -->
	                </div> <!-- /.forceHighlightsStencilDesktop -->


				    </td>
                </tr>
			</table>
			<!-- Lighting design Header -->

			<br>
			<!-- Account details tabs -->
			<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
			<tr class="slds-text-title" style="display: none;">
				<td class="dvtTabCache">
					{if isset($OP_MODE) && $OP_MODE eq 'edit_view'}
						{assign var="action" value="EditView"}
					{else}
						{assign var="action" value="DetailView"}
					{/if}
                
                    <div class="slds-tabs--default">
                        <ul class="slds-tabs--default__nav tabMenuTop" role="tablist" style="margin-bottom:0; border-bottom:none; display: none;">
                            <li class="slds-tabs--default__item" role="presentation"
                              title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}"> 
                                <a class="slds-tabs--default__link" href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" 
                                 role="tab" tabindex="0" aria-selected="true" aria-haspopup="true" aria-controls="tab-default-1">
                                  <span class="slds-truncate">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span>
                                </a>
                                {if isset($HASRELATEDPANES) && $HASRELATEDPANES eq 'true'}
                                {include file='RelatedPanes.tpl' tabposition='top'}
                                {else}
                            </li>

                            <li class="slds-tabs--default__item slds-active" role="presentation" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}">
                                 {if $smarty.request.ng_tab neq ''}
                                    <a class="slds-tabs--default__link" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" 
                                        role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                    <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                    </a>
                                {else}
                                    <a class="slds-tabs--default__link" role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                        <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                    </a>
                                {/if}
                            </li>
                                {/if}
                        </ul>
                    </div>
                </td>
                    {include file='RelatedListNg.tpl' SOURCE='RL'}
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace" style="border-bottom:0;">
						<tr>
							<td align=left style="padding: 0;">
							{if isset($OP_MODE) && $OP_MODE eq 'edit_view'}
								{assign var="action" value="EditView"}
							{else}
								{assign var="action" value="DetailView"}
							{/if}
								<div class="slds-tabs--scoped">
									<ul class="slds-tabs--scoped__nav" role="tablist" style="margin-bottom: 0;">
		                              	<li class="slds-tabs--scoped__item" onclick="openCity(event, 'tab--scoped-relatedList1')"  title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}" role="presentation">
		                                	<a class="slds-tabs--scoped__link "  href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}"  role="tab" tabindex="0" aria-selected="true" aria-controls="tab--scoped-relatedList1" id="tab--scoped--relatedList1__item">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</a>
		                              	</li>

		                              <li class="slds-tabs--scoped__item active" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}" role="presentation">
		                              	{if $smarty.request.ng_tab neq ''}
		                              	<a class="slds-tabs--scoped__link" href="javascript:void(0);"  role="tab" tabindex="-1" aria-selected="false" aria-controls="tab--scoped-relatedList2" id="tab--scoped-relatedList2__item">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a>
		                              	{else}
		                              	<a class="slds-tabs--scoped__link" role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab--scoped-relatedList2" id="tab--scoped-relatedList2__item">
                                        	{$APP.LBL_MORE} {$APP.LBL_INFORMATION}
                                    	</a>
		                             	
		                              </li>
		                              {/if}
	                             	</ul>
									<!-- content cache -->
                                            <div id="tab--scoped-relatedList1" role="tabpanel" aria-labelledby="tab--scoped-relatedList1__item" class="slds-tabs--scoped__content slds-truncate contains_rel_modules">
										<!-- General details -->
												{include file='RelatedListsHidden.tpl'}
												{if empty($ng_tab)}
													<div id="RLContents">
													{include file='RelatedListContents.tpl'}
													</div>
												{/if}
												{if !empty($ng_tab)}
													<div id="NGRLContents">
													{if !empty($CUSTOM_LINKS.RELATEDVIEWWIDGET)}
														{foreach item=CUSTOM_LINK_DETAILVIEWWIDGET from=$CUSTOM_LINKS.RELATEDVIEWWIDGET}
															{if preg_match("/^block:\/\/.*/", $CUSTOM_LINK_DETAILVIEWWIDGET->linkurl)
															&& $CUSTOM_LINK_DETAILVIEWWIDGET->related_tab eq $ng_tab}
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
				</td>
			</tr>
			<tr style="display: none;">
				<td>
					<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
                        <tr>
                            <td  class="dvtTabCacheBottom" style="padding:0;">
                                    <div class="slds-tabs--default">
                                        <ul class="slds-tabs--default__nav" role="tablist" style="margin-bottom:0; border-bottom:none;">
                                            <li class="tabMenuBottom slds-tabs--default__item-b " role="presentation" title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}"> 
                                                <a class="slds-tabs--default__link slds-tabs--default__link_mod"
                                                   href="index.php?action={$action}&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}"
                                                 role="tab" tabindex="0" aria-selected="true" aria-haspopup="true" aria-controls="tab-default-1">
                                                  <span class="slds-truncate">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span>
                                                </a>
                                            </li>
                                            
                                            {if $HASRELATEDPANES eq 'true'}
                                                {include file='RelatedPanes.tpl' tabposition='bottom'}
                                            {else}

                                            <li class="tabMenuBottom slds-tabs--default__item-b slds-active" role="presentation" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}">
                                                {if $smarty.request.ng_tab neq ''}
                                                <a class="slds-tabs--default__link slds-tabs--default__link_mod"
                                                   href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}"
                                                   role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                                    <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                                </a>
                                                {else}
                                                <a class="slds-tabs--default__link slds-tabs--default__link_mod"
                                                   role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                                    <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                                </a>
                                                {/if}
                                            </li>
                                            {/if}

                                        </ul>
                                    </div>
                                    {include file='RelatedListNg.tpl' SOURCE='RL'}
                                </div>
                            </td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
		</div>
	<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>
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
