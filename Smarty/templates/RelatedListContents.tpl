{*<!--
/*+*******************************************************************************
  * The contents of this file are subject to the vtiger CRM Public License Version 1.0
  * ("License"); You may not use this file except in compliance with the License
  * The Original Code is:  vtiger CRM Open Source
  * The Initial Developer of the Original Code is vtiger.
  * Portions created by vtiger are Copyright (C) vtiger.
  * All Rights Reserved.
  *********************************************************************************/
-->*}
<script type='text/javascript' src='include/js/Mail.js'></script>
<script type='text/javascript'>
{literal}

function isRelatedListBlockLoaded(id,urldata){
	var elem = document.getElementById(id);
	if(elem == null || typeof elem == 'undefined' || urldata.indexOf('order_by') != -1 ||
		urldata.indexOf('start') != -1 || urldata.indexOf('withCount') != -1){
		return false;
	}
	var tables = elem.getElementsByTagName('table');
	return tables.length > 0;
}

function loadRelatedListBlock(urldata,target,imagesuffix) {
	if( document.getElementById('return_module').value == 'Campaigns'){
		var selectallActivation = document.getElementById(imagesuffix+'_selectallActivate').value;
		var excludedRecords = document.getElementById(imagesuffix+'_excludedRecords').value = document.getElementById(imagesuffix+'_excludedRecords').value;
		var numofRows = document.getElementById(imagesuffix+'_numOfRows').value;
	}
	var showdata = 'show_'+imagesuffix;
	var showdata_element = document.getElementById(showdata);

	var hidedata = 'hide_'+imagesuffix;
	var hidedata_element = document.getElementById(hidedata);
	if(isRelatedListBlockLoaded(target,urldata) == true){
		jQuery('#'+target).show();
		jQuery(showdata_element).hide();
		showdata_element.parentElement.style.display = "none";
		jQuery(hidedata_element).show();
		hidedata_element.parentElement.style.display = "inline-block";
		jQuery('#delete_'+imagesuffix).show();
		return;
	}
	var indicator = 'indicator_'+imagesuffix;
	var indicator_element = document.getElementById(indicator);
	jQuery(indicator_element).show();
	jQuery('#delete_'+imagesuffix).show();

	var target_element = document.getElementById(target);
	
	jQuery.ajax({
			method: 'POST',
			url: 'index.php?'+urldata,
		}).done(function (response) {
					var responseData = trim(response);
					target_element.innerHTML=responseData;
					jQuery(target_element).show();
					jQuery(showdata_element).hide();
					showdata_element.parentElement.style.display = "none";
					jQuery(hidedata_element).show();
					hidedata_element.parentElement.style.display = "inline-block";
					jQuery(indicator_element).hide();
					if(document.getElementById('return_module').value == 'Campaigns'){
						var obj = document.getElementsByName(imagesuffix+'_selected_id');
						var relatedModule = imagesuffix.replace('Campaigns_',"");
						document.getElementById(relatedModule+'_count').innerHTML = numofRows;
						if(selectallActivation == 'true'){
							document.getElementById(imagesuffix+'_selectallActivate').value='true';
							jQuery('#'+imagesuffix+'_linkForSelectAll').show();
							document.getElementById(imagesuffix+'_selectAllRec').style.display='none';
							document.getElementById(imagesuffix+'_deSelectAllRec').style.display='inline';
							var exculdedArray=excludedRecords.split(';');
							if (obj) {
								var viewForSelectLink = showSelectAllLink(obj,exculdedArray);
								document.getElementById(imagesuffix+'_selectCurrentPageRec').checked = viewForSelectLink;
								document.getElementById(imagesuffix+'_excludedRecords').value = document.getElementById(imagesuffix+'_excludedRecords').value+excludedRecords;
							}
						}else{
							jQuery('#'+imagesuffix+'_linkForSelectAll').hide();
							//rel_toggleSelect(false,imagesuffix+'_selected_id',relatedModule);
						}
						updateParentCheckbox(obj,imagesuffix);
					}
			}
	);
}

function hideRelatedListBlock(target, imagesuffix) {
	var showdata = 'show_'+imagesuffix;
	var showdata_element = document.getElementById(showdata);
	var hidedata = 'hide_'+imagesuffix;
	var hidedata_element = document.getElementById(hidedata);
	jQuery('#'+target).hide();
	jQuery('#hide_'+imagesuffix).hide();
	hidedata_element.parentElement.style.display = 'none';
	jQuery('#show_'+imagesuffix).show();
	showdata_element.parentElement.style.display = 'inline-block';
	jQuery('#delete_'+imagesuffix).hide();
}

function disableRelatedListBlock(urldata,target,imagesuffix){
	jQuery('#indicator_'+imagesuffix).show();
	jQuery.ajax({
			method: 'POST',
			url: 'index.php?'+urldata,
		}).done(function (response) {
			var responseData = trim(response);
			jQuery('#'+target).hide();
			jQuery('#delete_'+imagesuffix).hide();
			jQuery('#hide_'+imagesuffix).hide();
			jQuery('#show_'+imagesuffix).show();
			var showdata_element = document.getElementById('show_'+imagesuffix);
			showdata_element.parentElement.style.display = 'inline-block';
			jQuery('#indicator_'+imagesuffix).hide();
		}
	);
}

{/literal}
</script>
{foreach key=header item=detail from=$RELATEDLISTS}

{if is_numeric($header)}
	{if $detail.type eq 'CodeWithHeader'}
		{strip}
{strip}
		<div class="forceRelatedListSingleContainer">
			<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
				<div class="slds-card__header slds-grid">
					<header class="slds-media slds-media--center slds-has-flexi-truncate">
						<div class="slds-media__figure">
							<div class="extraSmall forceEntityIcon" data-aura-rendered-by="3:1782;a" data-aura-class="forceEntityIcon">
								<span data-aura-rendered-by="6:1782;a" class="uiImage" data-aura-class="uiImage">
									<a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$THEME}');">
										{if isset($BLOCKINITIALSTATUS[$header]) && $BLOCKINITIALSTATUS[$header] eq 1}
										<span class="exp_coll_block inactivate">
											<img id="aid{$header|replace:' ':''}" src="{'chevrondown_60.png'|@vtiger_imageurl:$THEME}" width="16" alt="{'LBL_Hide'|@getTranslatedString:'Settings'}" title="{'LBL_Hide'|@getTranslatedString:'Settings'}"/>
										</span>
										{else}
										<span class="exp_coll_block activate">
											<img id="aid{$header|replace:' ':''}" src="{'chevronright_60.png'|@vtiger_imageurl:$THEME}" width="16" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
										</span>
										{/if}
									</a>
								</span>
							</div>
						</div>
						<div class="slds-media__body">
							<h2>
								<span class="slds-text-title--caps slds-truncate slds-m-right--xx-small" title="{$header}">
									<b>{$detail.label}</b>
								</span>
							</h2>
						</div>
					</header>
				</div>
			</article>
		</div>
		{/strip}
		
		<div id="tbl{$header|replace:' ':''}" class="rel_mod_content" style="display:block;">{include file=$detail.loadfrom}</div>
	{elseif $detail.type eq 'CodeWithoutHeader'}
		<div id="tbl{$header|replace:' ':''}" class="rel_mod_content" style="display:block;">{include file=$detail.loadfrom}</div>
	{elseif $detail.type eq 'Widget'}
		{process_widget widgetLinkInfo=$detail.instance}
	{/if}
{else}
	{assign var=rel_mod value=$header}
	{assign var="HEADERLABEL" value=$header|@getTranslatedString:$rel_mod}

	<!-- Lighting design  -->
		<div class="forceRelatedListSingleContainer">
			<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
				<div class="slds-card__header slds-grid">
					<header class="slds-media slds-media--center slds-has-flexi-truncate">
						<div class="slds-media__figure">
							<div class="extraSmall forceEntityIcon" data-aura-rendered-by="3:1782;a" data-aura-class="forceEntityIcon">
								<span data-aura-rendered-by="6:1782;a" class="uiImage" data-aura-class="uiImage">
									{strip}
										<a href="javascript:loadRelatedListBlock('module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&record={$ID}&ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$detail.relationId}&actions={$detail.actions}&parenttab={$CATEGORY}','tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
											<span class="exp_coll_block activate">
												<img id="show_{$MODULE}_{$header|replace:' ':''}" src="{'chevronright_60.png'|@vtiger_imageurl:$THEME}" style=" width: 16px;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
											</span>
										</a>
										<a href="javascript:hideRelatedListBlock('tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
											<span class="exp_coll_block inactivate" style="display: none">
												<img id="hide_{$MODULE}_{$header|replace:' ':''}" src="{'chevrondown_60.png'|@vtiger_imageurl:$THEME}" style="display:none; width: 16px;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
											</span>
										</a>
									{/strip}
								</span>
							</div>
						</div>
						<div class="slds-media__body">
							<h2>
								<span class="slds-text-title--caps slds-truncate slds-m-right--xx-small"><b>{$HEADERLABEL}</b></span>
							</h2>
						</div>
					</header>
					<div class="slds-no-flex" data-aura-rendered-by="1224:0">
						<img id="indicator_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
						<div style="float: right;width: 2em;" class="disable_rel_mod_table">
							<a href="javascript:disableRelatedListBlock('module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&ajxaction=DISABLEMODULE&relation_id={$detail.relationId}&header={$header}','tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
								<img id="delete_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'windowMinMax.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
							</a>
						</div>
						{if $MODULE eq 'Campaigns'}
						<input id="{$MODULE}_{$header|replace:' ':''}_numOfRows" type="hidden" value="">
						<input id="{$MODULE}_{$header|replace:' ':''}_excludedRecords" type="hidden" value="">
						<input id="{$MODULE}_{$header|replace:' ':''}_selectallActivate" type="hidden" value="false">
						{/if}
					</div>
				</div>
				<div class="slds-card__body slds-card__body--inner">
					<div id="tbl_{$MODULE}_{$header|replace:' ':''}" class="rel_mod_content"></div>
				</div>
			</article>
		</div>
	<!-- Lighting design  -->

	{if $SELECTEDHEADERS neq '' && $header|in_array:$SELECTEDHEADERS}
	<script type='text/javascript'>
	{if empty($smarty.request.ajax) || $smarty.request.ajax neq 'true'}
		jQuery( window ).on('load',function() {ldelim}
			loadRelatedListBlock('module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&record={$ID}&ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$detail.relationId}&actions={$detail.actions}&parenttab={$CATEGORY}&start={if isset($smarty.request.start)}{$smarty.request.start|@vtlib_purify}{/if}','tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');
		{rdelim});
	{else}
		loadRelatedListBlock('module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&record={$ID}&ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$detail.relationId}&actions={$detail.actions}&parenttab={$CATEGORY}&start={if isset($smarty.request.start)}{$smarty.request.start|@vtlib_purify}{/if}','tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');
	{/if}
	</script>
	{/if}
{/if}
<br />
{/foreach}