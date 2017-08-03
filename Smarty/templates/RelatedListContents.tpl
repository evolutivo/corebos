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
	<table class="small lvt rel_mod_table slds-table slds-no-row-hover slds-table_cell-buffer rel_mod_table" style="margin: 30px 0;">
		<tr class="detailview_block_header">
			{strip}
			<td colspan=4 class="dvInnerHeader">
				<div style="float:left;font-weight:bold;">
					<div style="float:left;">
						<a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
							<span class="exp_coll_block inactivate">
					<img id="aid{$header|replace:' ':''}" src="{'activate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;" alt="{'LBL_Hide'|@getTranslatedString:'Settings'}" title="{'LBL_Hide'|@getTranslatedString:'Settings'}"/>
					</span>
						</a>
					</div>
					<b>&nbsp;{$detail.label}</b>
				</div>
			</td>
			{/strip}
		</tr>
		<tr>
			<td class="rel_mod_content_wrapper">
				<div id="tbl{$header|replace:' ':''}" class="rel_mod_content" style="display:block;">{include file=$detail.loadfrom}</div>
			</td>
		</tr>
	</table>
	{elseif $detail.type eq 'CodeWithoutHeader'}
		<div id="tbl{$header|replace:' ':''}" class="rel_mod_content" style="display:block;">{include file=$detail.loadfrom}</div>
	{elseif $detail.type eq 'Widget'}
		{process_widget widgetLinkInfo=$detail.instance}
	{/if}
{else}
	{assign var=rel_mod value=$header}
	{assign var="HEADERLABEL" value=$header|@getTranslatedString:$rel_mod}
	
	<!-- Lighting design  -->

	<div class="flexipageComponent" style="background-color: #fff;">
	    <article class="slds-card container MEDIUM forceBaseCard
	     runtime_sales_mergeMergeCandidatesPreviewCard"
	             aria-describedby="header" style="margin: 0;">
	            <div class="slds-card__header slds-grid">
	                <header class="slds-media slds-media--center slds-has-flexi-truncate">
	                    <div class="profilePicWrapper slds-media slds-no-space" style="transform: scale3d(0.864715, 0.864715, 1) translate3d(4.32911px, 2.16456px, 0);">
	                        <div class="slds-media__figure slds-icon forceEntityIcon">
	                            <span class="photoContainer forceSocialPhoto" style="padding: .4rem;">
	                                <div class="small"
	                                     style="background-color: #A094ED">
	                                    <!-- icon here -->
	                                    <span class="toggle_rel_mod_table">
										{strip}
											<a href="javascript:loadRelatedListBlock(
												'module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&record={$ID}&ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$detail.relationId}&actions={$detail.actions}&parenttab={$CATEGORY}',
												'tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
												<span class="exp_coll_block activate">
													<img id="show_{$MODULE}_{$header|replace:' ':''}" src="{'inactivate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
												</span>
											</a>
											<a href="javascript:hideRelatedListBlock('tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
												<span class="exp_coll_block inactivate" style="display: none"><img id="hide_{$MODULE}_{$header|replace:' ':''}" src="{'activate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;display:none;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/></span>
											</a>
										{/strip}
										</span>

	                                </div>
	                            </span>
	                        </div>
	                    </div>
	                    <div class="slds-media__body" style="display: flex;">
	                        <h2 class="header-title-container" style="width:100%">
	                            <span class="slds-text-heading--small slds-truncate actionLabel">
	                                <b>{$HEADERLABEL}</b>
	                            </span>
	                        </h2>
	                        <img id="indicator_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
							<div style="float:right; width: 2em;" class="disable_rel_mod_table">
								<a href="javascript:disableRelatedListBlock(
									'module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&ajxaction=DISABLEMODULE&relation_id={$detail.relationId}&header={$header}',
									'tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
									<img id="delete_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'windowMinMax.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
								</a>
							</div>
							{if $MODULE eq 'Campaigns'}
							<input id="{$MODULE}_{$header|replace:' ':''}_numOfRows" type="hidden" value="">
							<input id="{$MODULE}_{$header|replace:' ':''}_excludedRecords" type="hidden" value="">
							<input id="{$MODULE}_{$header|replace:' ':''}_selectallActivate" type="hidden" value="false">
							{/if}
	                    </div>
	                </header>
	            </div>
	            <div class="slds-card__body slds-card__body--inner">
	                <div class="commentData">
	                    <div id="tbl_{$MODULE}_{$header|replace:' ':''}" class="rel_mod_content"></div>
	                </div>
	            </div>
	        
	    </article>
	</div>

	<!-- Lighting design  -->



	<!-- Related lists tables -->
	<table class="small lvt rel_mod_table slds-table slds-no-row-hover slds-table_cell-buffer" style="margin: 30px 0; background-color: #ddf;">
		
		<!-- table header -->
		<head>
		<tr class="slds-text-title--caps" style="display: none;">
			<td class="dvInnerHeader rel_mod_header_wrapper">

				<div style="font-weight: bold;height: 1.75em;" class="rel_mod_header">
					<span class="toggle_rel_mod_table">
					{strip}
						<a href="javascript:loadRelatedListBlock(
							'module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&record={$ID}&ajxaction=LOADRELATEDLIST&header={$header}&relation_id={$detail.relationId}&actions={$detail.actions}&parenttab={$CATEGORY}',
							'tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
							<span class="exp_coll_block activate">
								<img id="show_{$MODULE}_{$header|replace:' ':''}" src="{'inactivate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
							</span>
						</a>
						<a href="javascript:hideRelatedListBlock('tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
							<span class="exp_coll_block inactivate" style="display: none"><img id="hide_{$MODULE}_{$header|replace:' ':''}" src="{'activate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;display:none;" alt="{'LBL_Show'|@getTranslatedString:'Settings'}" title="{'LBL_Show'|@getTranslatedString:'Settings'}"/></span>
						</a>
					{/strip}
					</span>
					&nbsp;{$HEADERLABEL}&nbsp;
					<img id="indicator_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
					<div style="float: right;width: 2em;" class="disable_rel_mod_table">
						<a href="javascript:disableRelatedListBlock(
							'module={$MODULE}&action={$MODULE}Ajax&file=DetailViewAjax&ajxaction=DISABLEMODULE&relation_id={$detail.relationId}&header={$header}',
							'tbl_{$MODULE}_{$header|replace:' ':''}','{$MODULE}_{$header|replace:' ':''}');">
							<img id="delete_{$MODULE}_{$header|replace:' ':''}" style="display:none;" src="{'windowMinMax.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
						</a>
					</div>
					{if $MODULE eq 'Campaigns'}
					<input id="{$MODULE}_{$header|replace:' ':''}_numOfRows" type="hidden" value="">
					<input id="{$MODULE}_{$header|replace:' ':''}_excludedRecords" type="hidden" value="">
					<input id="{$MODULE}_{$header|replace:' ':''}_selectallActivate" type="hidden" value="false">
					{/if}
				</div>
			</td>
		</tr>
		</head>
		<!-- end table header -->
		
		<body>
		<!-- table content -->
		<tr style="display: none;">
			<td class="rel_mod_content_wrapper">
				<div id="tbl_{$MODULE}_{$header|replace:' ':''}" class="rel_mod_content"></div>
			</td>
		</tr>
		</body>
		<!-- end table content -->
	
	</table>
	
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