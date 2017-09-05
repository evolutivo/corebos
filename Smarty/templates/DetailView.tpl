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
<script type="text/javascript" src="include/js/dtlviewajax.js"></script>
<span id="crmspanid" style="display:none;position:absolute;" onmouseover="show('crmspanid');">
	<a class="link" href="javascript:;" style="padding:10px 5px 0 0;">{$APP.LBL_EDIT_BUTTON}</a>
</span>
<div id="convertleaddiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
{include file='DetailViewFieldDependency.tpl'}
<script>
	var gVTModule = '{$smarty.request.module|@vtlib_purify}';
	{literal}
	function callConvertLeadDiv(id) {
		jQuery.ajax({
			method: "POST",
			url: 'index.php?module=Leads&action=LeadsAjax&file=ConvertLead&record=' + id,
		}).done(function (response) {
				jQuery("#convertleaddiv").html(response);
				jQuery("#conv_leadcal").html();
			}
		);
	}
	function showHideStatus(sId, anchorImgId, sImagePath) {
		oObj = document.getElementById(sId);
		if (oObj.style.display == 'block') {
			oObj.style.display = 'none';
			if (anchorImgId != null) {
				{/literal}
				document.getElementById(anchorImgId).src = 'themes/images/chevronright_60.png';
				document.getElementById(anchorImgId).alt = '{'LBL_Show'|@getTranslatedString:'Settings'}';
				document.getElementById(anchorImgId).title = '{'LBL_Show'|@getTranslatedString:'Settings'}';
				document.getElementById(anchorImgId).parentElement.className = 'exp_coll_block activate';
				{literal}
			}
		}
		else {
			oObj.style.display = 'block';
			if (anchorImgId != null) {
				{/literal}
				document.getElementById(anchorImgId).src = 'themes/images/chevrondown_60.png';
				document.getElementById(anchorImgId).alt = '{'LBL_Hide'|@getTranslatedString:'Settings'}';
				document.getElementById(anchorImgId).title = '{'LBL_Hide'|@getTranslatedString:'Settings'}';
				document.getElementById(anchorImgId).parentElement.className = 'exp_coll_block inactivate';
				{literal}
			}
		}
	}
	function setCoOrdinate(elemId) {
		oBtnObj = document.getElementById(elemId);
		var tagName = document.getElementById('lstRecordLayout');
		leftpos = 0;
		toppos = 0;
		aTag = oBtnObj;
		do {
			leftpos += aTag.offsetLeft;
			toppos += aTag.offsetTop;
		} while (aTag = aTag.offsetParent);
		tagName.style.top = toppos + 20 + 'px';
		tagName.style.left = leftpos - 276 + 'px';
	}

	function getListOfRecords(obj, sModule, iId, sParentTab) {
		jQuery.ajax({
			method: "POST",
			url: 'index.php?module=Users&action=getListOfRecords&ajax=true&CurModule=' + sModule + '&CurRecordId=' + iId + '&CurParentTab=' + sParentTab,
		}).done(function (response) {
				sResponse = response;
				jQuery("#lstRecordLayout").html(sResponse);
				Lay = 'lstRecordLayout';
				var tagName = document.getElementById(Lay);
				var leftSide = findPosX(obj);
				var topSide = findPosY(obj);
				var maxW = tagName.style.width;
				var widthM = maxW.substring(0, maxW.length - 2);
				var getVal = parseInt(leftSide) + parseInt(widthM);
				if (getVal > document.body.clientWidth) {
					leftSide = parseInt(leftSide) - parseInt(widthM);
					tagName.style.left = leftSide + 230 + 'px';
					tagName.style.top = topSide + 20 + 'px';
				} else {
					tagName.style.left = leftSide + 230 + 'px';
				}
				setCoOrdinate(obj.id);

				tagName.style.display = 'block';
				tagName.style.visibility = "visible";
			}
		);
	}
	{/literal}
	function tagvalidate() {ldelim}
		if (trim(document.getElementById('txtbox_tagfields').value) != '')
			SaveTag('txtbox_tagfields', '{$ID}', '{$MODULE}');
		else {ldelim}
			alert("{$APP.PLEASE_ENTER_TAG}");
			return false;
			{rdelim}
		{rdelim}
	function DeleteTag(id, recordid) {ldelim}
		document.getElementById("vtbusy_info").style.display = "inline";
		jQuery('#tag_' + id).fadeOut();
		jQuery.ajax({ldelim}
			method: "POST",
			url: "index.php?file=TagCloud&module={$MODULE}&action={$MODULE}Ajax&ajxaction=DELETETAG&recordid=" + recordid + "&tagid=" + id,
			{rdelim}).done(function (response) {ldelim}
			getTagCloud();
			jQuery("#vtbusy_info").hide();
			{rdelim}
		);
		{rdelim}

	//Added to send a file, in Documents module, as an attachment in an email
	function sendfile_email() {ldelim}
		filename = document.getElementById('dldfilename').value;
		document.DetailView.submit();
		OpenCompose(filename, 'Documents');
		{rdelim}

</script>

<!-- Change tabs -->
<script type="text/javascript">
	function openCity(evt, tabName) {
	var i, tabcontent, tablinks;
	tabcontent = document.getElementsByClassName("slds-tabs--scoped__content");
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("slds-tabs--scoped__item");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" active", "");

	}
	document.getElementById(tabName).style.display = "block";
	evt.currentTarget.className += " active";
	}
</script>

<div id="lstRecordLayout" class="layerPopup" style="display:none;width:325px;height:300px;"></div>


{if $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads'}
	{if $MODULE eq 'Accounts'}
		{assign var=address1 value='$MOD.LBL_BILLING_ADDRESS'}
		{assign var=address2 value='$MOD.LBL_SHIPPING_ADDRESS'}
	{/if}
	{if $MODULE eq 'Contacts'}
		{assign var=address1 value='$MOD.LBL_PRIMARY_ADDRESS'}
		{assign var=address2 value='$MOD.LBL_ALTERNATE_ADDRESS'}
	{/if}
	<div id="locateMap" onMouseOut="fninvsh('locateMap')" onMouseOver="fnvshNrm('locateMap')">
		<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					{if $MODULE eq 'Accounts'}
						<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );"
						   class="calMnu">{$MOD.LBL_BILLING_ADDRESS}</a>
						<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );"
						   class="calMnu">{$MOD.LBL_SHIPPING_ADDRESS}</a>
					{/if}

					{if $MODULE eq 'Contacts'}
						<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Main' );"
						   class="calMnu">{$MOD.LBL_PRIMARY_ADDRESS}</a>
						<a href="javascript:;" onClick="fninvsh('locateMap'); searchMapLocation( 'Other' );"
						   class="calMnu">{$MOD.LBL_ALTERNATE_ADDRESS}</a>
					{/if}

				</td>
			</tr>
		</table>
	</div>
{/if}


<table width="100%" cellpadding="2" cellspacing="0" border="0" class="detailview_wrapper_table">
    <tr>
        <td class="detailview_wrapper_cell">

            {include file='Buttons_List.tpl'}

            <!-- Contents -->
            <table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
                <tr>
                    {*<td valign=top><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>*}
                    <td>
                        <!-- PUBLIC CONTENTS STARTS-->
                        <div class="small" onclick="hndCancelOutsideClick();">
                            <table class="slds-table slds-no-row-hover slds-table--cell-buffer slds-table-moz">
                                <tr class="slds-text-title--caps">
                                    <td style="padding: 0;">
                                        {* <!--Module Record numbering, used MOD_SEQ_ID instead of ID--> *}
                                        {assign var="USE_ID_VALUE" value=$MOD_SEQ_ID}
                                        {if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}

                                        <div class="slds-page-header s1FixedFullWidth s1FixedTop forceHighlightsStencilDesktop" style="height: 70px;">
                                            <div class="slds-grid primaryFieldRow"
                                                 style="transform: translate3d(0, -8.65823px, 0);">
                                                <div class="slds-grid slds-col slds-has-flexi-truncate slds-media--center">
                                                    <div class="profilePicWrapper slds-media slds-no-space"
                                                         style="transform: scale3d(0.864715, 0.864715, 1) translate3d(4.32911px, 2.16456px, 0);">
                                                        <div class="slds-media__figure slds-icon forceEntityIcon">
                                                            <span class="photoContainer forceSocialPhoto">
                                                                <div class="small roundedSquare forceEntityIcon"
                                                                     style="background-color: #A094ED">
                                                                    <span class="uiImage">
                                                                        <img src="https://playful-raccoon-70441-dev-ed.my.salesforce.com/img/icon/t4v35/standard/contact_120.png"
                                                                             class="icon " alt="Contact"
                                                                             title="Contact">
                                                                    </span>
                                                                </div>
                                                            </span>
                                                        </div>
                                                    </div>
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
                                                <div class="slds-col slds-no-flex slds-grid slds-align-middle actionsContainer" 
                                                     id="detailview_utils_thirdfiller">
                                                    <p class="slds-text-heading--label slds-line-height--reset" style="text-align: right;">
                                                        {if $privrecord neq ''}
                                                            <span class="detailview_utils_prev"
                                                                  onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'"
                                                                  title="{$APP.LNK_LIST_PREVIOUS}">
                                                                    <img align="absmiddle"
                                                                         accessKey="{$APP.LNK_LIST_PREVIOUS}"
                                                                         name="privrecord"
                                                                         value="{$APP.LNK_LIST_PREVIOUS}"
                                                                         src="{'rec_prev.gif'|@vtiger_imageurl:$THEME}"/>
                                                            </span>&nbsp;
                                                        {else}
                                                            <span class="detailview_utils_prev"
                                                                  title="{$APP.LNK_LIST_PREVIOUS}">
                                                                    <img align="absmiddle" width="23"
                                                                         src="{'rec_prev_disabled.gif'|@vtiger_imageurl:$THEME}">
                                                            </span>&nbsp;
                                                        {/if}
                                                        {if $privrecord neq '' || $nextrecord neq ''}
                                                            <span class="detailview_utils_jumpto" id="jumpBtnIdTop"
                                                                  onclick="
                                                                          var obj = this;
                                                                          var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');"
                                                                  title="{$APP.LBL_JUMP_BTN}">
                                                                <img align="absmiddle" title="{$APP.LBL_JUMP_BTN}"
                                                                     accessKey="{$APP.LBL_JUMP_BTN}" name="jumpBtnIdTop"
                                                                     src="{'replace_60.png'|@vtiger_imageurl:$THEME}" width="18"
                                                                     id="jumpBtnIdTop"  />
                                                                     <!-- src="{'rec_jump.gif'|@vtiger_imageurl:$THEME}" -->
                                                            </span>&nbsp;
                                                        {/if}
                                                        {if $nextrecord neq ''}
                                                            <span class="detailview_utils_next"
                                                                  onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'"
                                                                  title="{$APP.LNK_LIST_NEXT}">
                                                                <img align="absmiddle"
                                                                     accessKey="{$APP.LNK_LIST_NEXT}"
                                                                     name="nextrecord"
                                                                     src="{'rec_next.gif'|@vtiger_imageurl:$THEME}">
                                                            </span>&nbsp;
                                                        {else}
                                                            <span class="detailview_utils_next" title="{$APP.LNK_LIST_NEXT}">
                                                                <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}"
                                                                    width="23"
                                                                    src="{'rec_next_disabled.gif'|@vtiger_imageurl:$THEME}"/>
                                                            </span>&nbsp;
                                                        {/if}
                                                    </p>
                                                    <div class="slds-grid forceActionsContainer">
                                                        {if $EDIT_PERMISSION eq 'yes'}
                                                            <input class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                   aria-live="assertive" type="button" name="Edit"
                                                                   title="{$APP.LBL_EDIT_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_EDIT_BUTTON_KEY}"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');"
                                                                   value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;"/>&nbsp;
                                                        {/if}
                                                        {if ((isset($CREATE_PERMISSION) && $CREATE_PERMISSION eq 'permitted') || (isset($EDIT_PERMISSION) && $EDIT_PERMISSION eq 'yes')) && $MODULE neq 'Documents'}
                                                            <input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}"
                                                                   {*class="slds-button slds-button--small slds-button--brand assideBtn"*}
                                                                   class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');"
                                                                   type="button" name="Duplicate"
                                                                   value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}"/>&nbsp;
                                                        {/if}
                                                        {if $DELETE eq 'permitted'}
                                                            <input title="{$APP.LBL_DELETE_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_DELETE_BUTTON_KEY}"
                                                                   {*class="slds-button slds-button--small slds-button--destructive assideBtn"*}
                                                                   class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);"
                                                                   type="button" name="Delete"
                                                                   value="{$APP.LBL_DELETE_BUTTON_LABEL}"/>&nbsp;
                                                        {/if}
                                                        
                                                        {*
                                                        <span class="detailview_utils_toggleactions">
                                                            <img
                                                              align="absmiddle" title="{$APP.TOGGLE_ACTIONS}"
                                                              src="{'list_60.png'|@vtiger_imageurl:$THEME}"
                                                              onclick="
                                                              {literal}
                                                                  if (document.getElementById('actioncolumn').style.display=='none') {
                                                                      document.getElementById('actioncolumn').style.display='table-cell';
                                                                  } else {
                                                                      document.getElementById('actioncolumn').style.display='none';
                                                                  }
                                                                  window.dispatchEvent(new Event('resize'));
                                                              {/literal}">
                                                        </span>&nbsp;
                                                        *}
                                                    </div> {*/forceActionsContainer*}
                                                </div> {*/detailview_utils_thirdfiller*}
                                            </div> {*/primaryFieldRow*}
                                        </div> {*/forceHighlightsStencilDesktop*}
                                    </td>
                                </tr>
                            </table>
                            <br>
                            {include file='applicationmessage.tpl'}
                            <!-- Entity and More information tabs -->

                            <table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
                                <tr style="display: none;">
                                    <td>
                                        <table class="small {if $theme eq 'mltheme'}detailview_utils_table_top{/if}">
                                            <tr>
                                                <td class="dvtTabCache" align="right">
                                                    <div class="slds-tabs--default">
                                                        <ul class="slds-tabs--default__nav tabMenuTop" role="tablist" style="margin-bottom:0; border-bottom:none;">
                                                            <li class="slds-tabs--default__item slds-active" role="presentation"
                                                              title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}"> 
                                                                <a class="slds-tabs--default__link" href="javascript:void(0);" 
                                                                 role="tab" tabindex="0" aria-selected="true" aria-haspopup="true" aria-controls="tab-default-1">
                                                                  <span class="slds-truncate">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span>
                                                                </a>
                                                            </li>
                                                            {if $SinglePane_View eq 'false' && $IS_REL_LIST neq false && $IS_REL_LIST|@count > 0}
                                                            <li class="tabMenuTop slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open slds-tabs--default__item slds-tabs__item_overflow"
                                                                role="presentation" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}">
                                                                <a class="slds-tabs--default__link" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" 
                                                                 role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                                                    <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                                                    <svg class="slds-button__icon slds-button__icon_x-small" aria-hidden="true"> 
                                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#chevrondown">
                                                                            <svg viewBox="0 0 24 24" id="chevrondown" width="100%" height="100%"><path d="M22 8.2l-9.5 9.6c-.3.2-.7.2-1 0L2 8.2c-.2-.3-.2-.7 0-1l1-1c.3-.3.8-.3 1.1 0l7.4 7.5c.3.3.7.3 1 0l7.4-7.5c.3-.2.8-.2 1.1 0l1 1c.2.3.2.7 0 1z"></path></svg>
                                                                        </use>
                                                                    </svg>    
                                                                </a>
                                                                <div class="slds-dropdown slds-dropdown--left ">
                                                                    <ul class="slds-dropdown__list slds-dropdown--length-5" role="menu">
                                                                    {foreach key=_RELATION_ID item=_RELATED_MODULE from=$IS_REL_LIST}
                                                                        <li class="slds-dropdown__item" role="presentation">
                                                                            <a role="menuitem" tabindex="-1" class="drop_down"
                                                                               href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE}&relation_id={$_RELATION_ID}#tbl_{$MODULE}_{$_RELATED_MODULE}">
                                                                               {$_RELATED_MODULE|@getTranslatedString:$_RELATED_MODULE}</a>
                                                                        </li>
                                                                    {/foreach}
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                             {/if}
                                                        </ul>
                                                    </div>
                                                </td>
                                                
                                                {*{include file='RelatedListNg.tpl' SOURCE='DV'}*}

                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="slds-truncate">

                                            <table class="slds-table slds-no-row-hover dvtContentSpace">
                                                <tr>
                                                    <td valign="top" style="padding: 0;">

                                                    <div class="slds-tabs--scoped">
                                                        <ul class="slds-tabs--scoped__nav" role="tablist" style="margin-bottom: 0;">
                                                          <li class="slds-tabs--scoped__item active" onclick="openCity(event, 'tab--scoped-1')"  title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}" role="presentation">
                                                            <a class="slds-tabs--scoped__link "  href="javascript:void(0);"  role="tab" tabindex="0" aria-selected="true" 
                                                          aria-controls="tab--scoped-1" id="tab--scoped--1__item">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</a>
                                                          </li>
                                                          {if $SinglePane_View eq 'false' && $IS_REL_LIST neq false && $IS_REL_LIST|@count > 0}
                                                          <li class="slds-tabs--scoped__item slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}" role="presentation">
                                                            <a class="slds-tabs--scoped__link" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}"  role="tab" tabindex="-1" aria-selected="false" 
                                                          aria-controls="tab--scoped-2" id="tab--scoped-2__item">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a>
                                                            <div class="slds-dropdown slds-dropdown--left" style="margin-top: 0;">
                                                                <ul class="slds-dropdown__list slds-dropdown--length-7" role="menu">
                                                                {foreach key=_RELATION_ID item=_RELATED_MODULE from=$IS_REL_LIST}
                                                                    <li class="slds-dropdown__item" role="presentation">
                                                                        <a role="menuitem" tabindex="-1" class="drop_down"
                                                                           href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE}&relation_id={$_RELATION_ID}#tbl_{$MODULE}_{$_RELATED_MODULE}">
                                                                           {$_RELATED_MODULE|@getTranslatedString:$_RELATED_MODULE}</a>
                                                                    </li>
                                                                {/foreach}
                                                                </ul>
                                                            </div>
                                                          </li>
                                                          {/if}
                                                         </ul>
                                                
                                                        <!-- content cache -->
                                                        <!-- Command Buttons -->
                                                        <div id="tab--scoped-1" role="tabpanel" aria-labelledby="tab--scoped-1__item" class="slds-tabs--scoped__content slds-truncate">
                                                            <table class="slds-table slds-no-row-hover slds-table-moz"
                                                                   ng-controller="detailViewng" style="border-collapse:separate; border-spacing: 1rem 2rem;">
                                                                <form action="index.php" method="post"
                                                                      name="DetailView" id="formDetailView">
                                                                    <input type="hidden" id="hdtxt_IsAdmin"
                                                                           value="{if isset($hdtxt_IsAdmin)}{$hdtxt_IsAdmin}{else}0{/if}">
                                                                    {include file='DetailViewHidden.tpl'}

                                                                    {foreach key=header item=detail from=$BLOCKS name=BLOCKS}
                                                                        <tr class="blockStyleCss">
                                                                            <td class="detailViewContainer" valign="top">
                                                                              <!-- Detailed View Code starts here-->  
                                                                                <!-- This is added to display the existing comments -->
                                                                                {if $header eq $APP.LBL_COMMENTS || (isset($MOD.LBL_COMMENTS) && $header eq $MOD.LBL_COMMENTS) || (isset($MOD.LBL_COMMENT_INFORMATION) && $header eq $MOD.LBL_COMMENT_INFORMATION)}
                                                                                <div class="flexipageComponent" style="background-color: #fff;">
                                                                                    <article class="slds-card container MEDIUM forceBaseCard
                                                                                     runtime_sales_mergeMergeCandidatesPreviewCard"
                                                                                             aria-describedby="header" style="margin: 0;">
                                                                                            <div class="slds-card__header slds-grid">
                                                                                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                                                    <div class="slds-media__figure" data-aura-rendered-by="1215:0">
                                                                                                        <div class="extraSmall forceEntityIcon" style="background-color: #A094ED" 
                                                                                                        data-aura-rendered-by="3:1782;a" data-aura-class="forceEntityIcon">
                                                                                                        <span data-aura-rendered-by="6:1782;a" class="uiImage" data-aura-class="uiImage">
                                                                                                            <a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$THEME}');">
                                                                                                                {if isset($BLOCKINITIALSTATUS[$header]) && $BLOCKINITIALSTATUS[$header] eq 1}
                                                                                                                    <span class="exp_coll_block inactivate">
                                                                                                                <img id="aid{$header|replace:' ':''}"
                                                                                                                     src="{'activate.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                     style="border: 0px solid #000000;"
                                                                                                                     alt="{'LBL_Hide'|@getTranslatedString:'Settings'}"
                                                                                                                     title="{'LBL_Hide'|@getTranslatedString:'Settings'}"/>
                                                                                                                </span>
                                                                                                                {else}
                                                                                                                    <span class="exp_coll_block activate">
                                                                                                                <img id="aid{$header|replace:' ':''}"
                                                                                                                     src="{'inactivate.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                     style="border: 0px solid #000000;"
                                                                                                                     alt="{'LBL_Show'|@getTranslatedString:'Settings'}"
                                                                                                                     title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
                                                                                                                </span>
                                                                                                                {/if}
                                                                                                            </a>
                                                                                                        </span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="slds-media__body">
                                                                                                        <h2 class="header-title-container" >
                                                                                                            <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                                                                <b>{if isset($MOD.LBL_COMMENT_INFORMATION)}{$MOD.LBL_COMMENT_INFORMATION}{else}{$APP.LBL_COMMENTS}{/if}</b>
                                                                                                            </span>
                                                                                                        </h2>
                                                                                                    </div>
                                                                                                </header>
                                                                                            </div>
                                                                                            <div class="slds-card__body slds-card__body--inner">
                                                                                                <div class="commentData">
                                                                                                        {$COMMENT_BLOCK}
                                                                                                </div>
                                                                                            </div>
                                                                                        
                                                                                    </article>
                                                                                </div>
                                                                                 {/if}

                                                                                {if $header neq 'Comments' && (!isset($BLOCKS.$header.relatedlist) || $BLOCKS.$header.relatedlist eq 0)}
                                                                                        {strip}

                                                                                                <div class="forceRelatedListSingleContainer" data-aura-rendered-by="5:1654;a" 
                                                                                                    data-aura-class="forceRelatedListSingleContainer">
                                                                                                    <article class="slds-card forceRelatedListCardDesktop" aria-describedby="header" 
                                                                                                    data-aura-rendered-by="1212:0" data-aura-class="forceRelatedListCardDesktop">
                                                                                                        <div class="slds-card__header slds-grid" data-aura-rendered-by="1213:0">
                                                                                                            <header class="slds-media slds-media--center slds-has-flexi-truncate" 
                                                                                                            data-aura-rendered-by="1214:0">
                                                                                                                <div class="slds-media__figure" data-aura-rendered-by="1215:0">
                                                                                                                    <div class="extraSmall forceEntityIcon" style="background-color: #A094ED" 
                                                                                                                    data-aura-rendered-by="3:1782;a" data-aura-class="forceEntityIcon">
                                                                                                                    <span data-aura-rendered-by="6:1782;a" class="uiImage" data-aura-class="uiImage">
                                                                                                                        <a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$THEME}');">
                                                                                                                            {if isset($BLOCKINITIALSTATUS[$header]) && $BLOCKINITIALSTATUS[$header] eq 1}
                                                                                                                                <span class="exp_coll_block inactivate">
                                                                                                                            <img id="aid{$header|replace:' ':''}"
                                                                                                                                 src="{'activate.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                 style="border: 0px solid #000000;"
                                                                                                                                 alt="{'LBL_Hide'|@getTranslatedString:'Settings'}"
                                                                                                                                 title="{'LBL_Hide'|@getTranslatedString:'Settings'}"/>
                                                                                                                            </span>
                                                                                                                            {else}
                                                                                                                                <span class="exp_coll_block activate">
                                                                                                                            <img id="aid{$header|replace:' ':''}"
                                                                                                                                 src="{'inactivate.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                 style="border: 0px solid #000000;"
                                                                                                                                 alt="{'LBL_Show'|@getTranslatedString:'Settings'}"
                                                                                                                                 title="{'LBL_Show'|@getTranslatedString:'Settings'}"/>
                                                                                                                            </span>
                                                                                                                            {/if}
                                                                                                                        </a>
                                                                                                                    </span>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="slds-media__body" data-aura-rendered-by="1216:0">
                                                                                                                    <h2 data-aura-rendered-by="1217:0">
                                                                                                                        <span class="slds-text-title--caps slds-truncate slds-m-right--xx-small" 
                                                                                                                            title="{$header}" data-aura-rendered-by="1219:0">
                                                                                                                            <b>{$header}</b>
                                                                                                                        </span>
                                                                                                                   </h2>
                                                                                                                </div>
                                                                                                            </header>
                                                                                                            <div class="slds-no-flex" data-aura-rendered-by="1224:0">
                                                                                                                <div class="actionsContainer mapButton" data-aura-rendered-by="1225:0">
                                                                                                                    {if isset($MOD.LBL_ADDRESS_INFORMATION) && $header eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads') }
                                                                                                                        {if $MODULE eq 'Leads'}
                                                                                                                            <input name="mapbutton" type="button"
                                                                                                                                   value="{$APP.LBL_LOCATE_MAP}"
                                                                                                                                   class="slds-button slds-button--small slds-button--brand"
                                                                                                                                   onClick="searchMapLocation( 'Main' )"
                                                                                                                                   title="{$APP.LBL_LOCATE_MAP}">
                                                                                                                        {else}
                                                                                                                            <input name="mapbutton"
                                                                                                                                   value="{$APP.LBL_LOCATE_MAP}"
                                                                                                                                   class="slds-button slds-button--small slds-button--brand"
                                                                                                                                   type="button"
                                                                                                                                   onClick="fnvshobj(this,'locateMap');"
                                                                                                                                   onMouseOut="fninvsh('locateMap');"
                                                                                                                                   title="{$APP.LBL_LOCATE_MAP}">
                                                                                                                        {/if}
                                                                                                                    {/if}
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </article>
                                                                                                </div>

                                                                                    {/strip}
                                                                                {/if}

                                                                                {if $header neq 'Comments'}

                                                                                {if (isset($BLOCKINITIALSTATUS[$header]) && $BLOCKINITIALSTATUS[$header] eq 1) || !empty($BLOCKS.$header.relatedlist)}
                                                                                    <div class="slds-truncate" id="tbl{$header|replace:' ':''}" style="display: block;">
                                                                                {else}
                                                                                    <div class="slds-truncate" id="tbl{$header|replace:' ':''}" style="display: block;">
                                                                                {/if}
                                                                                        <table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
                                                                                            <tbody>
                                                                                            {if !empty($CUSTOMBLOCKS.$header.custom)}
                                                                                                {include file=$CUSTOMBLOCKS.$header.tpl}
                                                                                            {elseif isset($BLOCKS.$header.relatedlist) && $IS_REL_LIST|@count > 0}
                                                                                                {assign var='RELBINDEX' value=$BLOCKS.$header.relatedlist}
                                                                                                {include file='RelatedListNew.tpl' RELATEDLISTS=$RELATEDLISTBLOCK.$RELBINDEX RELLISTID=$RELBINDEX}
                                                                                            {else}
                                                                                                {foreach item=detailInfo from=$detail}
                                                                                                    <tr class="slds-line-height--reset">
                                                                                                        {foreach key=label item=data from=$detailInfo}
                                                                                                            {assign var=keyid value=$data.ui}
                                                                                                            {assign var=keyval value=$data.value}
                                                                                                            {assign var=keytblname value=$data.tablename}
                                                                                                            {assign var=keyfldname value=$data.fldname}
                                                                                                            {assign var=keyfldid value=$data.fldid}
                                                                                                            {assign var=keyoptions value=$data.options}
                                                                                                            {assign var=keysecid value=$data.secid}
                                                                                                            {assign var=keyseclink value=$data.link}
                                                                                                            {assign var=keycursymb value=$data.cursymb}
                                                                                                            {assign var=keysalut value=$data.salut}
                                                                                                            {assign var=keyaccess value=$data.notaccess}
                                                                                                            {assign var=keycntimage value=$data.cntimage}
                                                                                                            {assign var=keyadmin value=$data.isadmin}
                                                                                                            {assign var=display_type value=$data.displaytype}
                                                                                                            {assign var=_readonly value=$data.readonly}

                                                                                                            {if $label ne ''}
                                                                                                                <td class="dvtCellLabel" width=25%>
                                                                                                                    {if $keycntimage ne ''}
                                                                                                                        {$keycntimage}
                                                                                                                    {elseif $keyid eq '71' || $keyid eq '72'}<!-- Currency symbol -->
                                                                                                                        {$label} ({$keycursymb})
                                                                                                                    {elseif $keyid eq '9'}
                                                                                                                        {$label} {$APP.COVERED_PERCENTAGE}
                                                                                                                    {elseif $keyid eq '14'}
                                                                                                                        {$label} {"LBL_TIMEFIELD"|@getTranslatedString}
                                                                                                                    {else}
                                                                                                                        {$label}
                                                                                                                    {/if}
                                                                                                                </td>
                                                                                                                {if $EDIT_PERMISSION eq 'yes' && $display_type neq '2' && $_readonly eq '0'}
                                                                                                                    {* Performance Optimization Control *}
                                                                                                                    {if !empty($DETAILVIEW_AJAX_EDIT) }
                                                                                                                        {include file="DetailViewUI.tpl"}
                                                                                                                    {else}
                                                                                                                        {include file="DetailViewFields.tpl"}
                                                                                                                    {/if}
                                                                                                                    {* END *}
                                                                                                                {else}
                                                                                                                    {include file="DetailViewFields.tpl"}
                                                                                                                {/if}
                                                                                                            {/if}
                                                                                                        {/foreach}
                                                                                                    </tr>
                                                                                                {/foreach}
                                                                                            {/if}
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                {/if}
                                                                            </td>
                                                                        </tr>
                                                                        {* vtlib Customization: Embed DetailViewWidget block:// type if any *}
                                                                        {if $CUSTOM_LINKS && !empty($CUSTOM_LINKS.DETAILVIEWWIDGET)}
                                                                            {foreach item=CUSTOM_LINK_DETAILVIEWWIDGET from=$CUSTOM_LINKS.DETAILVIEWWIDGET}
                                                                                {if preg_match("/^block:\/\/.*/", $CUSTOM_LINK_DETAILVIEWWIDGET->linkurl) && $CUSTOM_LINK_DETAILVIEWWIDGET->linklabel neq 'DetailViewBlockCommentWidget' && $CUSTOM_LINK_DETAILVIEWWIDGET->top_widget neq '1'}
                                                                                    {if ($smarty.foreach.BLOCKS.first && $CUSTOM_LINK_DETAILVIEWWIDGET->sequence <= 1)
                                                                                    || ($CUSTOM_LINK_DETAILVIEWWIDGET->sequence == $smarty.foreach.BLOCKS.iteration+1)
                                                                                    || ($smarty.foreach.BLOCKS.last && $CUSTOM_LINK_DETAILVIEWWIDGET->sequence >= $smarty.foreach.BLOCKS.iteration+1)}
                                                                                        <tr>
                                                                                            <td style="padding:5px;">{dvwidget widgetLinkInfo=$CUSTOM_LINK_DETAILVIEWWIDGET}</td>
                                                                                        </tr>
                                                                                    {/if}
                                                                                {/if}
                                                                            {/foreach}
                                                                        {/if}
                                                                    {/foreach}
                                                                    {* END *}

                                                                    {*-- End of Blocks--*}

                                                                    <!-- Inventory - Product Details informations -->
                                                                    {if isset($ASSOCIATED_PRODUCTS)}
                                                                        <tr>
                                                                            {$ASSOCIATED_PRODUCTS}
                                                                        </tr>
                                                                    {/if}
                                                                    {if $SinglePane_View eq 'true' && $IS_REL_LIST|@count > 0}
                                                                        {include file= 'RelatedListNew.tpl'}
                                                                    {/if}
                                                            </table>
                                                        </div>
                                                        <div id="tab--scoped-2" role="tabpanel" aria-labelledby="tab--scoped-2__item" class="slds-tabs--scoped__content slds-hide">
                                                            tab2 content test
                                                        </div>

                                                    </div><!-- /.slds-tabs--scoped -->

                                                    </td>
                                                    <td class="noprint action-block" style="{$DEFAULT_ACTION_PANEL_STATUS}"
                                                                                     id="actioncolumn">
                                                                                <div class="flexipageComponent">
                                                                                    <!-- right side relevant info -->
                                                                                    <!-- Action links for Event & Todo START-by Minnie -->
                                                                                    <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
                                                                                             aria-describedby="header" style="margin: 0;">
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
                                                                                            <div class="actionData">

                                                                                                    {if $MODULE eq 'HelpDesk'}
                                                                                                        {if $CONVERTASFAQ eq 'permitted'}
                                                                                                                    <a class="webMnu"
                                                                                                                       href="index.php?return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}&module={$MODULE}&action=ConvertAsFAQ">
                                                                                                                       <img src="{'convert.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                        hspace="5" align="absmiddle" border="0"/>
                                                                                                                    </a>
                                                                                                                    <a class="webMnu"
                                                                                                                       href="index.php?return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}&module={$MODULE}&action=ConvertAsFAQ">
                                                                                                                       {$MOD.LBL_CONVERT_AS_FAQ_BUTTON_LABEL}</a>
                                                                                                        {/if}

                                                                                                    {elseif $TODO_PERMISSION eq 'true' || $EVENT_PERMISSION eq 'true' || $CONTACT_PERMISSION eq 'true'|| $MODULE eq 'Contacts' || $MODULE eq 'Leads' || ($MODULE eq 'Documents')}

                                                                                                
                                                                                                        {if $MODULE eq 'Contacts'}
                                                                                                            {assign var=subst value="contact_id"}
                                                                                                            {assign var=acc value="&account_id=$accountid"}
                                                                                                        {else}
                                                                                                            {assign var=subst value="parent_id"}
                                                                                                            {assign var=acc value=""}
                                                                                                        {/if}

                                                                                                        {if $MODULE eq 'Leads' || $MODULE eq 'Contacts' || $MODULE eq 'Accounts'}
                                                                                                            {if $SENDMAILBUTTON eq 'permitted'}
                                                                                                                
                                                                                                                        {foreach key=index item=email from=$EMAILS}
                                                                                                                            <input type="hidden"
                                                                                                                                   name="email_{$index}"
                                                                                                                                   value="{$email}"/>
                                                                                                                        {/foreach}
                                                                                                                        <a href="javascript:void(0);"
                                                                                                                           class="webMnu"
                                                                                                                           onclick="{$JS}"><img
                                                                                                                                    src="{'sendmail.png'|@vtiger_imageurl:$THEME}"
                                                                                                                                    hspace="5"
                                                                                                                                    align="absmiddle"
                                                                                                                                    border="0"/></a>
                                                                                                                        <a href="javascript:void(0);"
                                                                                                                           class="webMnu"
                                                                                                                           onclick="{$JS}">{$APP.LBL_SENDMAIL_BUTTON_LABEL}</a>
                                                                                                                  
                                                                                                            {/if}
                                                                                                        {/if}
                                                                                                        {if $MODULE eq 'Leads'}
                                                                                                            {if $CONVERTLEAD eq 'permitted'}
                                                                                                           
                                                                                                                        <a href="javascript:void(0);"
                                                                                                                           class="webMnu"
                                                                                                                           onclick="callConvertLeadDiv('{$ID}');"><img
                                                                                                                                    src="{'Leads.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                    hspace="5"
                                                                                                                                    align="absmiddle"
                                                                                                                                    border="0"/></a>
                                                                                                                        <a href="javascript:void(0);"
                                                                                                                           class="webMnu"
                                                                                                                           onclick="callConvertLeadDiv('{$ID}');">{$APP.LBL_CONVERT_BUTTON_LABEL}</a>
                                                                                                           
                                                                                                            {/if}
                                                                                                        {/if}
                                                                                                        {if $MODULE eq 'Perspectives'}
                                                                                                            <a href="javascript:;" class="webMnu" onClick="composer_install('{$MODULE}','{$ID}');">
                                                                                                                <img hspace="5" align="absmiddle" border="0" src="themes/images/no_icon.png">
                                                                                                            </a>
                                                                                                            <a href="javascript:;" class="webMnu" onClick="composer_install('{$MODULE}','{$ID}');">{$MOD.LBL_CMPINSTALL_BUTTON}</a>
                                                                                                        {/if}
                                                                                                        <!-- Start: Actions for Documents Module -->
                                                                                                        {if $MODULE eq 'Documents'}
                                                                                                               
                                                                                                                {if $DLD_TYPE eq 'I' && $FILE_STATUS eq '1' && $FILE_EXIST eq 'yes'}
                                                                                                                   <div class="actionData">
                                                                                                                    <a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}"
                                                                                                                       onclick="javascript:dldCntIncrease({$NOTESID});"
                                                                                                                       class="webMnu"><img
                                                                                                                                src="{'fbDownload.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                hspace="5" style="margin-right: 5px;"
                                                                                                                                align="absmiddle"
                                                                                                                                title="{$MOD.LNK_DOWNLOAD}"
                                                                                                                                border="0"/></a>
                                                                                                                    <a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}"
                                                                                                                       style="text-decoration: underline;" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>
                                                                                                                    </div>
                                                                                                                {elseif $DLD_TYPE eq 'E' && $FILE_STATUS eq '1'}
                                                                                                                    <div class="actionData">
                                                                                                                    <a target="_blank"
                                                                                                                       href="{$DLD_PATH}"
                                                                                                                       onclick="javascript:dldCntIncrease({$NOTESID});"><img
                                                                                                                                src="{'fbDownload.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                align="absmiddle" style="margin-right: 5px;"
                                                                                                                                title="{$MOD.LNK_DOWNLOAD}"
                                                                                                                                border="0"></a>
                                                                                                                    <a target="_blank" style="text-decoration: underline;"
                                                                                                                       href="{$DLD_PATH}"
                                                                                                                       onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>       
                                                                                                                    </div>
                                                                                                                    
                                                                                                                {/if}
                                                                                                           
                                                                                                                {if $CHECK_INTEGRITY_PERMISSION eq 'yes'}
                                                                                                                    <div class="actionData">
                                                                                                                    <a href="javascript:;"
                                                                                                                           onClick="checkFileIntegrityDetailView({$NOTESID});"><img
                                                                                                                                id="CheckIntegrity_img_id"
                                                                                                                                src="{'yes.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                alt="Check integrity of this file"
                                                                                                                                title="Check integrity of this file"
                                                                                                                                hspace="5" style="margin: 0 5px;"
                                                                                                                                align="absmiddle"
                                                                                                                                border="0"/></a>
                                                                                                                    <a href="javascript:;" style="text-decoration: underline;"
                                                                                                                       onClick="checkFileIntegrityDetailView({$NOTESID});">{$MOD.LBL_CHECK_INTEGRITY}</a>&nbsp;
                                                                                                                    <input type="hidden"
                                                                                                                           id="dldfilename"
                                                                                                                           name="dldfilename"
                                                                                                                           value="{$FILEID}-{$FILENAME}">
                                                                                                                    <span id="vtbusy_integrity_info"
                                                                                                                          style="display:none;">
                                                                                                                        <img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                             border="0"></span>
                                                                                                                    <span id="integrity_result"
                                                                                                                          style="display:none"></span>
                                                                                                                          </div>
                                                                                                                {/if}
                                                                                                       
                                                                                                                {if $DLD_TYPE eq 'I' &&  $FILE_STATUS eq '1' && $FILE_EXIST eq 'yes'}
                                                                                                                    <div class="actionData">
                                                                                                                        <input type="hidden"
                                                                                                                               id="dldfilename"
                                                                                                                               name="dldfilename"
                                                                                                                               value="{$FILEID}-{$FILENAME}">
                                                                                                                        <br>
                                                                                                                        <a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();"
                                                                                                                           class="webMnu" ><img
                                                                                                                                    src="{'attachment.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                                    hspace="5" style="margin: 0 5px;"
                                                                                                                                    align="absmiddle"
                                                                                                                                    border="0"/></a>
                                                                                                                       <a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();"
                                                                                                                       style="text-decoration: underline;">{$MOD.LBL_EMAIL_FILE}</a>
                                                                                                                    </div>
                                                                                                                {/if}
                                                                                                           
                                                                                                          
                                                                                                        {/if}

                                                                                                    {/if}
                                                                                               
                                                                                            </div>
                                                                                        </div>
                                                                                    </article>
                                                                                </div>


                                                                                    {* vtlib customization: Avoid line break if custom links are present *}
                                                                                    {if !isset($CUSTOM_LINKS) || empty($CUSTOM_LINKS)}
                                                                                        <br>
                                                                                    {/if}

                                                                                    {* vtlib customization: Custom links on the Detail view basic links *}
                                                                                    {if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEWBASIC}
                                                                                       

                                                                                            {foreach item=ACTIONBLOCK key=key from=$CUSTOM_LINKS.ActionBlock}
                                                                                                {assign var="blockName" value=$ACTIONBLOCK}
                                                                                                   
                                                                                                <div  class="flexipageComponent" style="background-color: #fff;">
                                                                                                    <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
                                                                                                            aria-describedby="header">
                                                                                                            <div id="{$blockName}" class="slds-card__header slds-grid">
                                                                                                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                                                                    <div class="slds-media__body">
                                                                                                                        <h2 class="header-title-container">
                                                                                                                            <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                                                                             <b>{$blockName}</b>
                                                                                                                           </span>
                                                                                                                        </h2>
                                                                                                                    </div>
                                                                                                                </header>
                                                                                                            </div>
                                                                                                       
                                                                                        
                                                                                                    <div class="slds-card__body slds-card__body--inner">

                                                                                                    {foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
                                                                                                        <div class="mailMergeData">
                                                                                                            {if $CUSTOMLINK->actions_block eq $blockName}
                                                                                                      
                                                                                                                        {assign var="customlink_href" value=$CUSTOMLINK->linkurl}
                                                                                                                        {assign var="customlink_label" value=$CUSTOMLINK->linklabel}
                                                                                                                        {if $customlink_label eq ''}
                                                                                                                            {assign var="customlink_label" value=$customlink_href}
                                                                                                                        {else}
                                                                                                                            {* Pickup the translated label provided by the module *}
                                                                                                                            {assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
                                                                                                                        {/if}
                                                                                                                        {if $CUSTOMLINK->linkicon}
                                                                                                                            <a class="webMnu"
                                                                                                                               href="{$customlink_href}"><img
                                                                                                                                hspace=5
                                                                                                                                align="absmiddle"
                                                                                                                                width="18"
                                                                                                                                height="18"
                                                                                                                                border=0
                                                                                                                                src="{$CUSTOMLINK->linkicon}"></a>
                                                                                                                        {/if}
                                                                                                                        <a class="webMnu"
                                                                                                                           href="{$customlink_href}">{$CUSTOMLINK->linklabel}</a>
                                                                                                                   
                                                                                                            {/if}
                                                                                                        </div>
                                                                                                    {/foreach}
                                                                                                  
                                                                                                </div>
                                                                                                    </article>
                                                                                                </div>
                                                                                            {/foreach}
                                                                                        <br>
                                                                                    {*Provide Block for Actions with empty Block*}
                                                                                    <div class="flexipageComponent" style="background-color: #fff;">
                                                                                        <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
                                                                                             aria-describedby="header">
                                                                                            <div class="slds-card__header slds-grid">
                                                                                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                                                    <div class="slds-media__body">
                                                                                                        <h2 class="header-title-container">
                                                                                                            <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                                                                <b>{$APP.GENERAL}</b>
                                                                                                            </span>
                                                                                                        </h2>
                                                                                                    </div>
                                                                                                </header>
                                                                                            </div>
                                                                                            <div class="slds-card__body slds-card__body--inner">

                                                                                                    {foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
                                                                                                    <div class="generalData">
                                                                                                        {if $CUSTOMLINK->actions_block eq ''}
                                                                                                            

                                                                                                                {assign var="customlink_href" value=$CUSTOMLINK->linkurl}
                                                                                                                {assign var="customlink_label" value=$CUSTOMLINK->linklabel}
                                                                                                                {if $customlink_label eq ''}
                                                                                                                    {assign var="customlink_label" value=$customlink_href}
                                                                                                                {else}
                                                                                                                    {* Pickup the translated label provided by the module *}
                                                                                                                    {assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
                                                                                                                {/if}
                                                                                                                {if $customlink_href eq ''}
                                                                                                                    {assign var="customlink_href" value='javascript:runAction("$RECORD$","$RECORD$","$RECORD$")'}
                                                                                                                {/if}
                                                                                                                {if $CUSTOMLINK->linkicon}
                                                                                                                    <a class="webMnu"
                                                                                                                       href="{$customlink_href}"><img
                                                                                                                                hspace=5
                                                                                                                                align="absmiddle"
                                                                                                                                width="18"
                                                                                                                                height="18"
                                                                                                                                border=0
                                                                                                                                src="{$CUSTOMLINK->linkicon}"></a>
                                                                                                                {/if}
                                                                                                                <a class="webMnu"
                                                                                                                   href="{$customlink_href}">{$CUSTOMLINK->linklabel|@getTranslatedString:$CUSTOMLINK->module()}</a>


                                                                                                           
                                                                                                        {/if}
                                                                                                    </div>
                                                                                                    {/foreach}


                                                                                            </div>
                                                                                    </article>
                                                                                    </div>
                                                                                      
                                                                                    {/if}
                                                                                    {* vtlib customization: Custom links on the Detail view *}
                                                                                    {if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEW}
                                                                                        <br>
                                                                                        {if !empty($CUSTOM_LINKS.DETAILVIEW)}
                                                                                            <table width="100%" border="0" cellpadding="5"
                                                                                                   cellspacing="0">
                                                                                                <tr>
                                                                                                    <td align="left"
                                                                                                        class="dvtUnSelectedCell dvtCellLabel">
                                                                                                        <a href="javascript:;"
                                                                                                           onmouseover="fnvshobj(this,'vtlib_customLinksLay');"
                                                                                                           onclick="fnvshobj(this,'vtlib_customLinksLay');"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS}
                                                                                                                &#187;</b></a>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                            <br>
                                                                                            <div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;"
                                                                                                 id="vtlib_customLinksLay"
                                                                                                 onmouseout="fninvsh('vtlib_customLinksLay')"
                                                                                                 onmouseover="fnvshNrm('vtlib_customLinksLay')">
                                                                                                <table bgcolor="#ffffff" border="0" cellpadding="0"
                                                                                                       cellspacing="0" width="100%">
                                                                                                    <tr>
                                                                                                        <td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;">
                                                                                                            <b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS}
                                                                                                                &#187;</b></td>
                                                                                                    </tr>
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
                                                                                                                <a href="{$customlink_href}"
                                                                                                                   class="drop_down">{$customlink_label}</a>
                                                                                                            {/foreach}
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                </table>
                                                                                            </div>
                                                                                        {/if}
                                                                                    {/if}
                                                                                    {* END *}
                                                                                    <!-- Action links END -->

                                                                                    {if $TAG_CLOUD_DISPLAY eq 'true'}
                                                                                        <!-- Tag cloud display -->
                                                                                        <table border=0 cellspacing=0 cellpadding=0 width=100%
                                                                                               class="tagCloud">
                                                                                            <tr>
                                                                                                <td class="tagCloudTopBg"><img
                                                                                                            src="{$IMAGE_PATH}tagCloudName.gif"
                                                                                                            border=0></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div id="tagdiv" style="display:visible;">
                                                                                                        <form method="POST" action="javascript:void(0);"
                                                                                                              onsubmit="return tagvalidate();"><input
                                                                                                                    class="textbox" type="text"
                                                                                                                    id="txtbox_tagfields"
                                                                                                                    name="textbox_First Name" value=""
                                                                                                                    style="width:100px;margin-left:5px;"></input>&nbsp;&nbsp;<input
                                                                                                                    name="button_tagfileds"
                                                                                                                    type="submit"
                                                                                                                    class="crmbutton small save"
                                                                                                                    value="{$APP.LBL_TAG_IT}"/></form>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td class="tagCloudDisplay" valign=top><span
                                                                                                            id="tagfields"></span></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                        <!-- End Tag cloud display -->
                                                                                    {/if}
                                                                                    <!-- Mail Merge-->
                                                                                    <br>
                                                                                    {if isset($MERGEBUTTON) && $MERGEBUTTON eq 'permitted'}


                                                                                        <form action="index.php" method="post" name="TemplateMerge"
                                                                                              id="form">
                                                                                            <input type="hidden" name="module" value="{$MODULE}">
                                                                                            <input type="hidden" name="parenttab" value="{$CATEGORY}">
                                                                                            <input type="hidden" name="record" value="{$ID}">
                                                                                            <input type="hidden" name="action">
                                                                                            <div class="flexipageComponent" style="background-color: #fff;">
                                                                                            <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
                                                                                                     aria-describedby="header">
                                                                                                <div class="slds-card__header slds-grid">

                                                                                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                                                <div class="slds-media__body">
                                                                                                    <h4 class="header-title-container">
                                                                                                        <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                                                            <b>{$WORDTEMPLATEOPTIONS}</b>
                                                                                                        </span>
                                                                                                    </h4>
                                                                                                </div>
                                                                                            </header>
                                                                                        </div>
                                                                                        <div class="slds-card__body slds-card__body--inner">
                                                                                            <div class="rightMailMergeData">
                                                                                                        {if $TEMPLATECOUNT neq 0}
                                                                                                            <select name="mergefile">{foreach key=templid item=tempflname from=$TOPTIONS}
                                                                                                                    <option
                                                                                                                    value="{$templid}">{$tempflname}</option>{/foreach}
                                                                                                            </select>
                                                                                                            <input class="crmbutton small create"
                                                                                                                   value="{$APP.LBL_MERGE_BUTTON_LABEL}"
                                                                                                                   onclick="this.form.action.value='Merge';"
                                                                                                                   type="submit"></input>
                                                                                                        {else}
                                                                                                            <a href=index.php?module=Settings&action=upload&tempModule={$MODULE}&parenttab=Settings>{$APP.LBL_CREATE_MERGE_TEMPLATE}</a>
                                                                                                        {/if}
                                                                                            </div>
                                                                                        </div>
                                                                                    </article>
                                                                                            </div>
                                                                                        </form>

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
                                                                                                <table border=0 cellspacing=0 cellpadding=0 width=100%
                                                                                                       class="rightMailMerge"
                                                                                                       id="{$CUSTOMLINK->linklabel}">
                                                                                                    <tr>
                                                                                                        <td class="rightMailMergeHeader">
                                                                                                            <b>{$customlink_label}</b>
                                                                                                            <img id="detailview_block_{$CUSTOMLINK_NO}_indicator"
                                                                                                                 style="display:none;"
                                                                                                                 src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}"
                                                                                                                 border="0" align="absmiddle"/>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <tr style="height:25px">
                                                                                                        <td class="rightMailMergeContent">
                                                                                                            <div id="detailview_block_{$CUSTOMLINK_NO}"></div>
                                                                                                        </td>
                                                                                                    </tr>
                                                                                                    <script type="text/javascript">
                                                                                                        vtlib_loadDetailViewWidget("{$customlink_href}", "detailview_block_{$CUSTOMLINK_NO}", "detailview_block_{$CUSTOMLINK_NO}_indicator");
                                                                                                    </script>
                                                                                                </table>
                                                                                            {/if}
                                                                                        {/foreach}
                                                                                    {/if}
                                                                            </td>
                                                </tr>
                                            </table>
                                            <!-- PUBLIC CONTENTS STOPS-->
                                        </div>
                                    </td>
                                </tr>
                                <tr style="display: none;">
                                    <td>
                                        <table border=0 cellspacing=0 cellpadding=3 width=100% class="small" >
                                            <tr>
                                                <td class="dvtTabCacheBottom " style="padding: 1px 0;">
                                                    <div class="slds-tabs--default">
                                                        <ul class="slds-tabs--default__nav" role="tablist" style="margin-bottom:0; border-bottom:none;">
                                                            <li class="tabMenuBottom slds-tabs--default__item-b slds-active" role="presentation"
                                                              title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}"> 
                                                                <a class="slds-tabs--default__link slds-tabs--default__link_mod" 
                                                                 role="tab" tabindex="0" aria-selected="true" aria-haspopup="true" aria-controls="tab-default-1">
                                                                  <span class="slds-truncate">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span>
                                                                </a>
                                                            </li>
                                                            {if $SinglePane_View eq 'false' && $IS_REL_LIST neq false && $IS_REL_LIST|@count > 0}
                                                            <li class="tabMenuBottom slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open slds-tabs--default__item-b slds-tabs__item_overflow"
                                                                role="presentation" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}">
                                                                <a class="slds-tabs--default__link slds-tabs--default__link_mod" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" 
                                                                 role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2">
                                                                    <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                                                    <svg class="slds-button__icon slds-button__icon_x-small" aria-hidden="true"> 
                                                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#chevronup">
                                                                            <svg viewBox="0 0 24 24" id="chevronup" width="100%" height="100%"><path d="M22 8.2l-9.5 9.6c-.3.2-.7.2-1 0L2 8.2c-.2-.3-.2-.7 0-1l1-1c.3-.3.8-.3 1.1 0l7.4 7.5c.3.3.7.3 1 0l7.4-7.5c.3-.2.8-.2 1.1 0l1 1c.2.3.2.7 0 1z"></path></svg>
                                                                        </use>
                                                                    </svg>    
                                                                </a>
                                                                <div class="slds-dropdown-b slds-dropdown--left">
                                                                    <ul class="slds-dropdown__list slds-dropdown--length-5" role="menu">
                                                                    {foreach key=_RELATION_ID item=_RELATED_MODULE from=$IS_REL_LIST}
                                                                        <li class="slds-dropdown__item" role="presentation">
                                                                            <a role="menuitem" tabindex="-1" class="drop_down"
                                                                               href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE}&relation_id={$_RELATION_ID}#tbl_{$MODULE}_{$_RELATED_MODULE}">
                                                                               {$_RELATED_MODULE|@getTranslatedString:$_RELATED_MODULE}</a>
                                                                        </li>
                                                                    {/foreach}
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                            {/if}
                                                        </ul>
                                                    </div>
                                                </td>
                                                {*{include file='RelatedListNg.tpl' SOURCE='DV'}*}
                                                                                                                                                                                            
                                                <td class="dvtTabCacheBottom dvtBottomButtons">

                                                    <div class="slds-col slds-no-flex slds-grid slds-align-middle actionsContainer pull-right"
                                                         id="detailview_utils_thirdfiller">
                                                        <div class="slds-grid forceActionsContainer">
                                                            {if $EDIT_PERMISSION eq 'yes'}
                                                                <input class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                        {*class="slds-button slds-button--small slds-button_success assideBtn"*}
                                                                       aria-live="assertive" type="button" name="Edit"
                                                                       title="{$APP.LBL_EDIT_BUTTON_TITLE}"
                                                                       accessKey="{$APP.LBL_EDIT_BUTTON_KEY}"
                                                                       onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');"
                                                                       value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;"/>&nbsp;
                                                            {/if}

                                                            {if ((isset($CREATE_PERMISSION) && $CREATE_PERMISSION eq 'permitted') || (isset($EDIT_PERMISSION) && $EDIT_PERMISSION eq 'yes')) && $MODULE neq 'Documents'}
                                                                <input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}"
                                                                       accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}"
                                                                        {*class="slds-button slds-button--small slds-button--brand assideBtn"*}
                                                                       class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                       onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');"
                                                                       type="button" name="Duplicate"
                                                                       value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}"/>&nbsp;
                                                            {/if}

                                                            {if $DELETE eq 'permitted'}
                                                                <input title="{$APP.LBL_DELETE_BUTTON_TITLE}"
                                                                       accessKey="{$APP.LBL_DELETE_BUTTON_KEY}"
                                                                        {*class="slds-button slds-button--small slds-button--destructive assideBtn"*}
                                                                       class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                       onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);"
                                                                       type="button" name="Delete"
                                                                       value="{$APP.LBL_DELETE_BUTTON_LABEL}"/>&nbsp;
                                                            {/if}

                                                            


                                                        </div> {*/forceActionsContainer*}
                                                    </div> {*/detailview_utils_thirdfiller*}

                                                </td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td class="pull-right dvtControlsButtons">
                                                {if $privrecord neq ''}
                                                    <span class="detailview_utils_prev"
                                                          onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'"
                                                          title="{$APP.LNK_LIST_PREVIOUS}">
                                                        <img align="absmiddle"
                                                             accessKey="{$APP.LNK_LIST_PREVIOUS}"
                                                             name="privrecord"
                                                             value="{$APP.LNK_LIST_PREVIOUS}"
                                                             src="{'rec_prev.gif'|@vtiger_imageurl:$THEME}"
                                                             style="padding-top: 6px;"/>
                                                </span>&nbsp;
                                                {else}
                                                    <span class="detailview_utils_prev"
                                                          title="{$APP.LNK_LIST_PREVIOUS}">
                                                        <img align="absmiddle" width="23"
                                                             style="padding-top: 6px;"
                                                             src="{'rec_prev_disabled.gif'|@vtiger_imageurl:$THEME}">
                                                </span>&nbsp;
                                                {/if}

                                                {if $privrecord neq '' || $nextrecord neq ''}
                                                            <span class="detailview_utils_jumpto" id="jumpBtnIdTop"
                                                                  onclick="
                                                                          var obj = this;
                                                                          var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');"
                                                                  title="{$APP.LBL_JUMP_BTN}">
                                                            <img align="absmiddle" title="{$APP.LBL_JUMP_BTN}"
                                                                 accessKey="{$APP.LBL_JUMP_BTN}" name="jumpBtnIdTop"
                                                                 src="{'replace_60.png'|@vtiger_imageurl:$THEME}"
                                                                 id="jumpBtnIdTop" width="18"/>
                                                                 <!-- src="{'rec_jump.gif'|@vtiger_imageurl:$THEME}" -->
                                                                 
                                                        </span>&nbsp;
                                                        {/if}
                                                        
                                                        {if $nextrecord neq ''}
                                                            <span class="detailview_utils_next"
                                                                  onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'"
                                                                  title="{$APP.LNK_LIST_NEXT}">
                                                            <img align="absmiddle"
                                                                 accessKey="{$APP.LNK_LIST_NEXT}"
                                                                 name="nextrecord"
                                                                 src="{'rec_next.gif'|@vtiger_imageurl:$THEME}"
                                                                 style="padding-top: 6px;">
                                                        </span>&nbsp;
                                                        {else}
                                                            <span class="detailview_utils_next" title="{$APP.LNK_LIST_NEXT}">
                                                            <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}"
                                                                 width="23" style="padding-top: 6px;"
                                                                 src="{'rec_next_disabled.gif'|@vtiger_imageurl:$THEME}"/>
                                                        </span>&nbsp;
                                                        {/if}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <script>

                                function getTagCloud() {ldelim}
                                    var obj = document.getElementById("tagfields");
                                    if (obj != null && typeof(obj) != undefined) {ldelim}
                                        jQuery.ajax({ldelim}
                                            method: "POST",
                                            url: 'index.php?module={$MODULE}&action={$MODULE}Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid={$ID}',
                                            {rdelim}).done(function (response) {ldelim}
                                            jQuery("#tagfields").html(response);
                                            jQuery("#txtbox_tagfields").val('');
                                            {rdelim}
                                        );
                                        {rdelim}
                                    {rdelim}
                                getTagCloud();
                            </script>
                            <!-- added for validation -->
                            <script>
                                var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
                                var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
                                var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
                            </script>
                    </td>

                    <!-- {*<td align=right valign=top><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>*} -->
                </tr>
            </table>

	<tr>
		<td class="detailview_wrapper_cell">
			{include file='Buttons_List.tpl'}
				<!-- Contents -->
			<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
				<tr>
					<td>
						<!-- PUBLIC CONTENTS STARTS-->
						<div class="small" onclick="hndCancelOutsideClick();">
							<table class="slds-table slds-no-row-hover slds-table--cell-buffer slds-table-moz" style="background-color: #f7f9fb;">
								<tr class="slds-text-title--caps">
									<td style="padding: 0;">
										{* <!--Module Record numbering, used MOD_SEQ_ID instead of ID--> *}
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
																		{if $MODULE eq 'Contacts'}
																			<img src="{'contact_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Contact" title="Contact">
																		{elseif $MODULE eq 'Accounts'}
																			<img src="{'account_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Organization" title="Organization">
																		{elseif $MODULE eq 'Leads'}
																			<img src="{'lead_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Leads" title="Leads">
																		{elseif $MODULE eq 'Campaigns'}
																			<img src="{'campaign_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Campaigns" title="Campaigns">
																		{elseif $MODULE eq 'Potentials'}
																			<img src="{'opportunity_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Opportunity" title="Opportunity">
																		{elseif $MODULE eq 'Documents'}
																			<img src="{'document_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Documents" title="Documents">
																		{elseif $MODULE eq 'HelpDesk'}
																			<img src="{'help_desk_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="HelpDesk" title="HelpDesk">
																		{elseif $MODULE eq 'Faq'}
																			<img src="{'faq_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Faq" title="Faq">
																		{elseif $MODULE eq 'ServiceContracts'}
																			<img src="{'service_contract_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="ServiceContracts" title="ServiceContracts">
																		{elseif $MODULE eq 'ModComments'}
																			<img src="{'quick_text_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Comments" title="Comments">
																		{elseif $MODULE eq 'InventoryDetails'}
																			<img src="{'inventory_details_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="InventoryDetails" title="InventoryDetails">
																		{elseif $MODULE eq 'GlobalVariable'}
																			<img src="{'global_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="GlobalVariable" title="GlobalVariable">
																		{elseif $MODULE eq 'cbCalendar'}
																			<img src="{'todo_120.png'|@vtiger_imageurl:$THEME}" class="icon " alt="Todo" title="Todo">
																		{/if}
																	</span>
																</div>
															</span>
														</div>
													</div>
													<div class="slds-media__body">
														<p class="slds-text-heading--label slds-line-height--reset" style="opacity: 1;">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</p>
														<h1 class="slds-page-header__title slds-m-right--small slds-truncate slds-align-middle">
														<span class="uiOutputText"><font color="purple">[ {$USE_ID_VALUE} ]</font> {$NAME}</span>
															<span class="small" style="text-transform: capitalize;">{$UPDATEINFO}</span>
															<span id="vtbusy_info" style="display:none; text-transform: capitalize;" valign="bottom">
																<img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0">
															</span>
														</h1>
													</div>
												</div>
												<div class="slds-col slds-no-flex slds-grid slds-align-middle actionsContainer" id="detailview_utils_thirdfiller">
													<div class="slds-grid forceActionsContainer">
														{if $EDIT_PERMISSION eq 'yes'}
															<input class="slds-button slds-button--neutral not-selected slds-not-selected uiButton" aria-live="assertive" type="button" name="Edit" title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');" value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;" />&nbsp;
														{/if}

														{if ((isset($CREATE_PERMISSION) && $CREATE_PERMISSION eq 'permitted') || (isset($EDIT_PERMISSION) && $EDIT_PERMISSION eq 'yes')) && $MODULE neq 'Documents'}
														<input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}" accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}" class="slds-button slds-button--neutral not-selected slds-not-selected uiButton" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');" type="button" name="Duplicate" value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}" />&nbsp;
														{/if}

														{if $DELETE eq 'permitted'}
															<input title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" 
														class="slds-button slds-button--neutral not-selected slds-not-selected uiButton" onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);" type="button" name="Delete" value="{$APP.LBL_DELETE_BUTTON_LABEL}" />&nbsp; {/if} 
														
														{*
														<span class="detailview_utils_toggleactions">
															<img align="absmiddle" title="{$APP.TOGGLE_ACTIONS}" src="{'list_60.png'|@vtiger_imageurl:$THEME}"
															onclick="
															{literal} 
															if (document.getElementById('actioncolumn').style.display=='none')
															{
																document.getElementById('actioncolumn').style.display='table-cell';
															}
															else
															{
																document.getElementById('actioncolumn').style.display='none';
															}
															window.dispatchEvent(new Event('resize'));
															{/literal}">
														</span>
														*}

														<p class="slds-text-heading--label slds-line-height--reset" style="text-align: right; margin: 7px 0 0 5px ;">
															{if $privrecord neq ''}
																<span class="detailview_utils_prev" onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'" title="{$APP.LNK_LIST_PREVIOUS}">
																	<img align="absmiddle" accessKey="{$APP.LNK_LIST_PREVIOUS}" name="privrecord" value="{$APP.LNK_LIST_PREVIOUS}" src="{'rec_prev.gif'|@vtiger_imageurl:$THEME}"/>
																</span>&nbsp;
															{else}
																<span class="detailview_utils_prev" title="{$APP.LNK_LIST_PREVIOUS}">
																	<img align="absmiddle" width="23" src="{'rec_prev_disabled.gif'|@vtiger_imageurl:$THEME}">
																</span>&nbsp;
															{/if} 
															{if $privrecord neq '' || $nextrecord neq ''}
															<span class="detailview_utils_jumpto" id="jumpBtnIdTop"
															onclick="
															var obj = this;
															var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');"
															title="{$APP.LBL_JUMP_BTN}">
																<img align="absmiddle" title="{$APP.LBL_JUMP_BTN}" accessKey="{$APP.LBL_JUMP_BTN}" name="jumpBtnIdTop"
																src="{'replace_60.png'|@vtiger_imageurl:$THEME}" width="18" id="jumpBtnIdTop"  />
															</span>&nbsp;
															{/if}
															{if $nextrecord neq ''}
															<span class="detailview_utils_next" onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'" title="{$APP.LNK_LIST_NEXT}">
																<img align="absmiddle" accessKey="{$APP.LNK_LIST_NEXT}" name="nextrecord" src="{'rec_next.gif'|@vtiger_imageurl:$THEME}">
															</span>&nbsp;
															{else}
															<span class="detailview_utils_next" title="{$APP.LNK_LIST_NEXT}">
																<img align="absmiddle" title="{$APP.LNK_LIST_NEXT}"
																width="23" src="{'rec_next_disabled.gif'|@vtiger_imageurl:$THEME}"/>
															</span>&nbsp;
															{/if}
														</p>
													</div> {*/.forceActionsContainer*}
												</div> {*/#detailview_utils_thirdfiller*}
											</div> {*/primaryFieldRow*}
										</div> {*/forceHighlightsStencilDesktop*}
									</td>
								</tr>
							</table>
							<br> {include file='applicationmessage.tpl'}
							<!-- Entity and More information tabs -->

							<table border=0 cellspacing=0 cellpadding=0 width=100% align=center>
								<tr>
									<td>
										<div class="slds-truncate">

											<table class="slds-table slds-no-row-hover dvtContentSpace">
												<tr>
													<td valign="top" style="padding: 0;">
														<div class="slds-tabs--scoped">
															<ul class="slds-tabs--scoped__nav" role="tablist" style="margin-bottom: 0;">
																<li class="slds-tabs--scoped__item active" title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}" role="presentation">
																	<a class="slds-tabs--scoped__link " href="javascript:void(0);" role="tab" tabindex="0" aria-selected="true" aria-controls="tab--scoped-1" id="tab--scoped--1__item">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</a>
																</li>
																{if $SinglePane_View eq 'false' && $IS_REL_LIST neq false && $IS_REL_LIST|@count > 0}
																<li class="slds-tabs--scoped__item slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}" role="presentation">
																	<a class="slds-tabs--scoped__link" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}" role="tab" tabindex="-1" aria-selected="false" aria-controls="tab--scoped-2" id="tab--scoped-2__item">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</a>
																		<div class="slds-dropdown slds-dropdown--left" style="margin-top: 0;">
																			<ul class="slds-dropdown__list slds-dropdown--length-7" role="menu">
																				{foreach key=_RELATION_ID item=_RELATED_MODULE from=$IS_REL_LIST}
																					<li class="slds-dropdown__item" role="presentation">
																						<a role="menuitem" tabindex="-1" class="drop_down" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE}&relation_id={$_RELATION_ID}#tbl_{$MODULE}_{$_RELATED_MODULE}">
																							{$_RELATED_MODULE|@getTranslatedString:$_RELATED_MODULE}</a>
																					</li>
																				{/foreach}
																			</ul>
																		</div>
																</li>
															{/if}
															</ul>

															<!-- content cache -->
															<!-- Command Buttons -->
															<div id="tab--scoped-1" role="tabpanel" aria-labelledby="tab--scoped-1__item" class="slds-tabs--scoped__content slds-truncate">
																<table class="slds-table slds-no-row-hover slds-table-moz"
																	   ng-controller="detailViewng" style="border-collapse:separate; border-spacing: 1rem;">
																	<form action="index.php" method="post"
																		  name="DetailView" id="formDetailView">
																		<input type="hidden" id="hdtxt_IsAdmin"
																			   value="{if isset($hdtxt_IsAdmin)}{$hdtxt_IsAdmin}{else}0{/if}">
																		{include file='DetailViewHidden.tpl'}

																		{foreach key=header item=detail from=$BLOCKS name=BLOCKS}
																			<tr class="blockStyleCss">
																				<td class="detailViewContainer" valign="top">
																				  <!-- Detailed View Code starts here-->  
																					<!-- This is added to display the existing comments -->
																					{if $header eq $APP.LBL_COMMENTS || (isset($MOD.LBL_COMMENTS) && $header eq $MOD.LBL_COMMENTS) || (isset($MOD.LBL_COMMENT_INFORMATION) && $header eq $MOD.LBL_COMMENT_INFORMATION)}
																					<div class="forceRelatedListSingleContainer">
																						<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
																							<div class="slds-card__header slds-grid">
																								<header class="slds-media slds-media--center slds-has-flexi-truncate">
																									<div class="slds-media__body">
																										<h2>
																											<span class="slds-text-title--caps slds-truncate slds-m-right--xx-small actionLabel">
																												<b>{if isset($MOD.LBL_COMMENT_INFORMATION)}{$MOD.LBL_COMMENT_INFORMATION}{else}{$APP.LBL_COMMENTS}{/if}</b>
																											</span>
																										</h2>
																									</div>
																								</header>
																							</div>
																							<div class="slds-card__body slds-card__body--inner">
																								<div class="commentData">{$COMMENT_BLOCK}</div>
																							</div>
																						</article>
																					</div>
																					<br/>
																					<br/>
																					{/if}

																					{if $header neq 'Comments' && (!isset($BLOCKS.$header.relatedlist) || $BLOCKS.$header.relatedlist eq 0)}
																							{strip}

																							<div class="forceRelatedListSingleContainer">
																								<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
																									<div class="slds-card__header slds-grid">
																										<header class="slds-media slds-media--center slds-has-flexi-truncate">
																											<div class="slds-media__figure">
																												<div class="extraSmall forceEntityIcon" data-aura-rendered-by="3:1782;a" data-aura-class="forceEntityIcon">
																													<span data-aura-rendered-by="6:1782;a" class="uiImage" data-aura-class="uiImage">
																														<a href="javascript:showHideStatus('tbl{$header|replace:' ':''}','aid{$header|replace:' ':''}','{$IMAGE_PATH}');">
																															{if isset($BLOCKINITIALSTATUS[$header]) || $BLOCKINITIALSTATUS[$header] eq 1}
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
																														<b>{$header}</b>
																													</span>
																												</h2>
																											</div>
																										</header>
																										<div class="slds-no-flex" data-aura-rendered-by="1224:0">
																											<div class="actionsContainer mapButton">
																												{if isset($MOD.LBL_ADDRESS_INFORMATION) && $header eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Leads') }
																													{if $MODULE eq 'Leads'}
																														<input name="mapbutton" type="button" value="{$APP.LBL_LOCATE_MAP}" class="slds-button slds-button--small slds-button--brand" onClick="searchMapLocation( 'Main' )" title="{$APP.LBL_LOCATE_MAP}">
																													{else}
																														<input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="slds-button slds-button--small slds-button--brand" type="button" onClick="fnvshobj(this,'locateMap');" onMouseOut="fninvsh('locateMap');" title="{$APP.LBL_LOCATE_MAP}">
																													{/if}
																												{/if}
																											</div>
																										</div>
																									</div>
																								</article>
																							</div>

																						{/strip}
																					{/if}

																					{if $header neq 'Comments'}

																					{if (isset($BLOCKINITIALSTATUS[$header]) && $BLOCKINITIALSTATUS[$header] eq 1) || !empty($BLOCKS.$header.relatedlist)}
																						<div class="slds-truncate" id="tbl{$header|replace:' ':''}" style="display: block;">
																					{else}
																						<div class="slds-truncate" id="tbl{$header|replace:' ':''}" style="display: block;">
																					{/if}
																							<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
																								<tbody>
																								{if !empty($CUSTOMBLOCKS.$header.custom)}
																									{include file=$CUSTOMBLOCKS.$header.tpl}
																								{elseif isset($BLOCKS.$header.relatedlist) && $IS_REL_LIST|@count > 0}
																									{assign var='RELBINDEX' value=$BLOCKS.$header.relatedlist}
																									{include file='RelatedListNew.tpl' RELATEDLISTS=$RELATEDLISTBLOCK.$RELBINDEX RELLISTID=$RELBINDEX}
																								{else}
																									{foreach item=detailInfo from=$detail}
																										<tr class="slds-line-height--reset">
																											{foreach key=label item=data from=$detailInfo}
																												{assign var=keyid value=$data.ui}
																												{assign var=keyval value=$data.value}
																												{assign var=keytblname value=$data.tablename}
																												{assign var=keyfldname value=$data.fldname}
																												{assign var=keyfldid value=$data.fldid}
																												{assign var=keyoptions value=$data.options}
																												{assign var=keysecid value=$data.secid}
																												{assign var=keyseclink value=$data.link}
																												{assign var=keycursymb value=$data.cursymb}
																												{assign var=keysalut value=$data.salut}
																												{assign var=keyaccess value=$data.notaccess}
																												{assign var=keycntimage value=$data.cntimage}
																												{assign var=keyadmin value=$data.isadmin}
																												{assign var=display_type value=$data.displaytype}
																												{assign var=_readonly value=$data.readonly}

																												{if $label ne ''}
																													<td class="dvtCellLabel" width=25%>
																														{if $keycntimage ne ''}
																															{$keycntimage}
																														{elseif $keyid eq '71' || $keyid eq '72'}<!-- Currency symbol -->
																															{$label} ({$keycursymb})
																														{elseif $keyid eq '9'}
																															{$label} {$APP.COVERED_PERCENTAGE}
																														{elseif $keyid eq '14'}
																															{$label} {"LBL_TIMEFIELD"|@getTranslatedString}
																														{else}
																															{$label}
																														{/if}
																													</td>
																													{if $EDIT_PERMISSION eq 'yes' && $display_type neq '2' && $_readonly eq '0'}
																														{* Performance Optimization Control *}
																														{if !empty($DETAILVIEW_AJAX_EDIT) }
																															{include file="DetailViewUI.tpl"}
																														{else}
																															{include file="DetailViewFields.tpl"}
																														{/if}
																														{* END *}
																													{else}
																														{include file="DetailViewFields.tpl"}
																													{/if}
																												{/if}
																											{/foreach}
																										</tr>
																									{/foreach}
																								{/if}
																								</tbody>
																							</table>
																						</div>
																					{/if}
																				</td>
																			</tr>
																			{* vtlib Customization: Embed DetailViewWidget block:// type if any *}
																			{if $CUSTOM_LINKS && !empty($CUSTOM_LINKS.DETAILVIEWWIDGET)}
																				{foreach item=CUSTOM_LINK_DETAILVIEWWIDGET from=$CUSTOM_LINKS.DETAILVIEWWIDGET}
																					{if preg_match("/^block:\/\/.*/", $CUSTOM_LINK_DETAILVIEWWIDGET->linkurl) && $CUSTOM_LINK_DETAILVIEWWIDGET->linklabel neq 'DetailViewBlockCommentWidget' && $CUSTOM_LINK_DETAILVIEWWIDGET->top_widget neq '1'}
																						{if ($smarty.foreach.BLOCKS.first && $CUSTOM_LINK_DETAILVIEWWIDGET->sequence <= 1)
																						|| ($CUSTOM_LINK_DETAILVIEWWIDGET->sequence == $smarty.foreach.BLOCKS.iteration+1)
																						|| ($smarty.foreach.BLOCKS.last && $CUSTOM_LINK_DETAILVIEWWIDGET->sequence >= $smarty.foreach.BLOCKS.iteration+1)}
																							<tr class="blockStyleCss">
																								<td class="detailViewContainer">{dvwidget widgetLinkInfo=$CUSTOM_LINK_DETAILVIEWWIDGET}</td>
																							</tr>
																						{/if}
																					{/if}
																				{/foreach}
																			{/if}
																		{/foreach}
																		{* END *}

																		{*-- End of Blocks--*}

																		<!-- Inventory - Product Details informations -->
																		{if isset($ASSOCIATED_PRODUCTS)}
																			<tr class="blockStyleCss">
																				<td class="detailViewContainer">
																					{$ASSOCIATED_PRODUCTS}
																				</td>
																			</tr>
																		{/if}
																		{if $SinglePane_View eq 'true' && $IS_REL_LIST|@count > 0}
																			{include file= 'RelatedListNew.tpl'}
																		{/if}
																</table>
															</div>
														</div><!-- /.slds-tabs--scoped -->
													</td>

													<td class="noprint action-block" style="{$DEFAULT_ACTION_PANEL_STATUS}" id="actioncolumn">
														<div class="flexipageComponent">
															<!-- right side relevant info -->
															<!-- Action links for Event & Todo START-by Minnie -->
															<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header" style="margin: 0;">
																<div class="slds-card__header slds-grid">
																	<header class="slds-media slds-media--center slds-has-flexi-truncate">
																		<div class="slds-media__body">
																			<h2 class="header-title-container">
																				<span class="slds-text-heading--small slds-truncate actionLabel"><b>{$APP.LBL_ACTIONS}</b></span>
																			</h2>
																		</div>
																	</header>
																</div>
																<div class="slds-card__body slds-card__body--inner">
																{if $MODULE eq 'HelpDesk'}
																	{if $CONVERTASFAQ eq 'permitted'}
																		<div class="actionData actionlink_converttofaq">
																			<a class="webMnu" href="index.php?return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}&module={$MODULE}&action=ConvertAsFAQ"><img src="{'convert.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
																			<a class="webMnu" href="index.php?return_module={$MODULE}&return_action=DetailView&record={$ID}&return_id={$ID}&module={$MODULE}&action=ConvertAsFAQ">{$MOD.LBL_CONVERT_AS_FAQ_BUTTON_LABEL}</a>
																		</div>
																	{/if}
																{elseif $TODO_PERMISSION eq 'true' || $EVENT_PERMISSION eq 'true' || $CONTACT_PERMISSION eq 'true'|| $MODULE eq 'Contacts' || $MODULE eq 'Leads' || ($MODULE eq 'Documents')}
																	{if $MODULE eq 'Contacts'}
																		{assign var=subst value="contact_id"}
																		{assign var=acc value="&account_id=$accountid"}
																	{else}
																		{assign var=subst value="parent_id"}
																		{assign var=acc value=""}
																	{/if}

																	{if $MODULE eq 'Leads' || $MODULE eq 'Contacts' || $MODULE eq 'Accounts'}
																		{if $SENDMAILBUTTON eq 'permitted'}
																			{foreach key=index item=email from=$EMAILS}
																			<div class="actionData actionlink_sendemail">
																				<input type="hidden" name="email_{$index}" value="{$email}"/>
																			{/foreach}
																			<a href="javascript:void(0);" class="webMnu" onclick="{$JS}"><img src="{'sendmail.png'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
																			<a href="javascript:void(0);" class="webMnu" onclick="{$JS}">{$APP.LBL_SENDMAIL_BUTTON_LABEL}</a>
																			</div>
																		{/if}
																	{/if}
																	{if $MODULE eq 'Leads'}
																		{if $CONVERTLEAD eq 'permitted'}
																		<div class="actionData actionlink_convertlead">
																		<a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('{$ID}');"><img src="{'Leads.gif'|@vtiger_imageurl:$THEME}" hspace="5" align="absmiddle" border="0"/></a>
																		<a href="javascript:void(0);" class="webMnu" onclick="callConvertLeadDiv('{$ID}');">{$APP.LBL_CONVERT_BUTTON_LABEL}</a>
																		</div>
																		{/if}
																	{/if}
																	<!-- Start: Actions for Documents Module -->
																	{if $MODULE eq 'Documents'}
																		{if $DLD_TYPE eq 'I' && $FILE_STATUS eq '1' && $FILE_EXIST eq 'yes'}
																			<div class="actionData actionlink_downloaddocument">
																			<a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}" onclick="javascript:dldCntIncrease({$NOTESID});" class="webMnu"><img src="{'fbDownload.gif'|@vtiger_imageurl:$THEME}" hspace="5" style="margin-right: 5px;" align="absmiddle" title="{$MOD.LNK_DOWNLOAD}" border="0"/></a>
																			<a href="index.php?module=uploads&action=downloadfile&fileid={$FILEID}&entityid={$NOTESID}" style="text-decoration: underline;" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>
																			</div>
																		{elseif $DLD_TYPE eq 'E' && $FILE_STATUS eq '1'}
																			<div class="actionData actionlink_downloaddocument">
																			<a target="_blank" class="webMnu" href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});"><img src="{'fbDownload.gif'|@vtiger_imageurl:$THEME}" align="absmiddle" style="margin-right: 5px;" title="{$MOD.LNK_DOWNLOAD}" border="0"></a>
																			<a target="_blank" style="text-decoration: underline;" href="{$DLD_PATH}" onclick="javascript:dldCntIncrease({$NOTESID});">{$MOD.LBL_DOWNLOAD_FILE}</a>	   
																			</div>
																		{/if}
																		{if $CHECK_INTEGRITY_PERMISSION eq 'yes'}
																			<div class="actionData actionlink_checkdocinteg">
																				<a class="webMnu" href="javascript:;" onClick="checkFileIntegrityDetailView({$NOTESID});"><img id="CheckIntegrity_img_id" src="{'yes.gif'|@vtiger_imageurl:$THEME}" alt="Check integrity of this file" title="Check integrity of this file" hspace="5" style="margin: 0 5px;" align="absmiddle" border="0"/></a>
																				<a href="javascript:;" style="text-decoration: underline;" onClick="checkFileIntegrityDetailView({$NOTESID});">{$MOD.LBL_CHECK_INTEGRITY}</a>&nbsp;
																				<input type="hidden" id="dldfilename" name="dldfilename" value="{$FILEID}-{$FILENAME}">
																				<span id="vtbusy_integrity_info" style="display:none;">
																					<img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0"></span>
																				<span id="integrity_result" style="display:none"></span>
																			</div>
																		{/if}
																		<div class="actionData actionlink_emaildocument">
																		{if $DLD_TYPE eq 'I' &&  $FILE_STATUS eq '1' && $FILE_EXIST eq 'yes'}
																			<input type="hidden" id="dldfilename" name="dldfilename" value="{$FILEID}-{$FILENAME}">
																			<a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();" class="webMnu" ><img src="{'attachment.gif'|@vtiger_imageurl:$THEME}" hspace="5" style="margin: 0 5px;" align="absmiddle" border="0"/></a>
																			<a href="javascript: document.DetailView.return_module.value='Documents'; document.DetailView.return_action.value='DetailView'; document.DetailView.module.value='Documents'; document.DetailView.action.value='EmailFile'; document.DetailView.record.value={$NOTESID}; document.DetailView.return_id.value={$NOTESID}; sendfile_email();" style="text-decoration: underline;">{$MOD.LBL_EMAIL_FILE}</a>
																		{/if}
																		</div>
																	{/if}
																{/if}

																{* vtlib customization: Avoid line break if custom links are present *}
																{if !isset($CUSTOM_LINKS) || empty($CUSTOM_LINKS)}
																	<br>
																{/if}
																
																</div>
															</div>
															</article>
														</div>

														{* vtlib customization: Custom links on the Detail view basic links *}
														{if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEWBASIC}
															{foreach item=ACTIONBLOCK key=key from=$CUSTOM_LINKS.ActionBlock}
																{assign var="blockName" value=$ACTIONBLOCK}
																<div  class="flexipageComponent" style="background-color: #fff;">
																	<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
																			<div id="{$blockName}" class="slds-card__header slds-grid">
																				<header class="slds-media slds-media--center slds-has-flexi-truncate">
																					<div class="slds-media__body">
																						<h2 class="header-title-container">
																							<span class="slds-text-heading--small slds-truncate actionLabel">
																							 <b>{$blockName}</b>
																						   </span>
																						</h2>
																					</div>
																				</header>
																			</div>
																			<div class="slds-card__body slds-card__body--inner">
																				{foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
																					<div class="mailMergeData">
																						{if $CUSTOMLINK->actions_block eq $blockName}
																							{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
																							{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
																							{if $customlink_label eq ''}
																								{assign var="customlink_label" value=$customlink_href}
																							{else}
																								{* Pickup the translated label provided by the module *}
																								{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
																							{/if}
																							{if $CUSTOMLINK->linkicon}
																								<a class="webMnu" href="{$customlink_href}"><img hspace=5 align="absmiddle" width="18" height="18" border=0 src="{$CUSTOMLINK->linkicon}"></a>
																							{/if}
																							<a class="webMnu" href="{$customlink_href}">{$CUSTOMLINK->linklabel}</a>
																						{/if}
																					</div>
																				{/foreach}
																			</div>
																	</article>
																</div>
															{/foreach}
															<br>
															{*Provide Block for Actions with empty Block*}
															<div class="flexipageComponent" style="background-color: #fff;">
																<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard"
																	 aria-describedby="header">
																	<div class="slds-card__header slds-grid">
																		<header class="slds-media slds-media--center slds-has-flexi-truncate">
																			<div class="slds-media__body">
																				<h2 class="header-title-container">
																					<span class="slds-text-heading--small slds-truncate actionLabel">
																						<b>{$APP.GENERAL}</b>
																					</span>
																				</h2>
																			</div>
																		</header>
																	</div>
																	<div class="slds-card__body slds-card__body--inner">
																		{foreach item=CUSTOMLINK from=$CUSTOM_LINKS.DETAILVIEWBASIC}
																			<div class="generalData actionlink_addtodo">
																				{if $CUSTOMLINK->actions_block eq ''}
																					{assign var="customlink_href" value=$CUSTOMLINK->linkurl}
																					{assign var="customlink_label" value=$CUSTOMLINK->linklabel}
																					{if $customlink_label eq ''}
																						{assign var="customlink_label" value=$customlink_href}
																					{else}
																						{* Pickup the translated label provided by the module *}
																						{assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
																					{/if}
																					{if $customlink_href eq ''}
																						{assign var="customlink_href" value='javascript:runAction("$RECORD$","$RECORD$","$RECORD$")'}
																					{/if}
																					{if $CUSTOMLINK->linkicon}
																						<a class="webMnu" href="{$customlink_href}"><img hspace=5 align="absmiddle" width="18" height="18" border=0 src="{$CUSTOMLINK->linkicon}"></a>
																					{/if}
																						<a class="webMnu" href="{$customlink_href}">{$CUSTOMLINK->linklabel|@getTranslatedString:$CUSTOMLINK->module()}</a>
																				{/if}
																			</div>
																		{/foreach}
																	</div>
																</article>
															</div>
														{/if}
														{* vtlib customization: Custom links on the Detail view *}
														{if $CUSTOM_LINKS && $CUSTOM_LINKS.DETAILVIEW}
															<br>
															{if !empty($CUSTOM_LINKS.DETAILVIEW)}
																<table width="100%" border="0" cellpadding="5" cellspacing="0">
																	<tr>
																		<td align="left" class="dvtUnSelectedCell dvtCellLabel">
																			<a href="javascript:;" onmouseover="fnvshobj(this,'vtlib_customLinksLay');" onclick="fnvshobj(this,'vtlib_customLinksLay');"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></a>
																		</td>
																	</tr>
																</table>
																<br>
																<div style="display: none; left: 193px; top: 106px;width:155px; position:absolute;" id="vtlib_customLinksLay" onmouseout="fninvsh('vtlib_customLinksLay')" onmouseover="fnvshNrm('vtlib_customLinksLay')">
																	<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
																		<tr>
																			<td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b>{$APP.LBL_MORE} {$APP.LBL_ACTIONS} &#187;</b></td>
																		</tr>
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
														{/if}
														{* END *}
														<!-- Action links END -->

														{if $TAG_CLOUD_DISPLAY eq 'true'}
															<!-- Tag cloud display -->
															<br>
															<div class="flexipageComponent tagCloud">
																<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
																	<div class="slds-card__header slds-grid">
																		<header class="slds-media slds-media--center slds-has-flexi-truncate">
																			<div class="slds-media__body">
																				<img src="{$IMAGE_PATH}tagCloudName.gif" border=0>
																			</div>
																		</header>
																	</div>
																	<div class="slds-card__body slds-card__body--inner">
																		<div id="tagdiv" style="display:visible;">
																			<form method="POST" action="javascript:void(0);" onsubmit="return tagvalidate();">
																				<input class="textbox slds-input" type="text" id="txtbox_tagfields" name="textbox_First Name" value="" style="width:150px;margin-left:5px;"></input>&nbsp;&nbsp;
																				<input name="button_tagfileds" type="submit" class="slds-button slds-button_success slds-button--small" value="{$APP.LBL_TAG_IT}" />
																			</form>
																		</div>
																		<div class="tagCloudDisplay actionData">
																			<span id="tagfields"></span>
																		</div>
																	</div>
																</article>
															</div>
															<!-- End Tag cloud display -->
														{/if}
														<!-- Mail Merge-->
														<br>
														{if isset($MERGEBUTTON) && $MERGEBUTTON eq 'permitted'}
															<form action="index.php" method="post" name="TemplateMerge" id="form">
																<input type="hidden" name="module" value="{$MODULE}">
																<input type="hidden" name="parenttab" value="{$CATEGORY}">
																<input type="hidden" name="record" value="{$ID}">
																<input type="hidden" name="action">
																<div class="flexipageComponent">
																	<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
																		<div class="slds-card__header slds-grid">
																			<header class="slds-media slds-media--center slds-has-flexi-truncate">
																				<div class="slds-media__body">
																					<h4 class="header-title-container">
																						<span class="slds-text-heading--small slds-truncate actionLabel"><b>{$WORDTEMPLATEOPTIONS}</b></span>
																					</h4>
																				</div>
																			</header>
																		</div>
																		<div class="slds-card__body slds-card__body--inner">
																			<div class="actionData">
																				{if $TEMPLATECOUNT neq 0}
																					<select name="mergefile" class="slds-select">
																						{foreach key=templid item=tempflname from=$TOPTIONS}
																							<option value="{$templid}">{$tempflname}</option>
																						{/foreach}
																					</select>
																					<input class="slds-button slds-button--small slds-button_success" value="{$APP.LBL_MERGE_BUTTON_LABEL}" onclick="this.form.action.value='Merge';" type="submit"/>
																				{else}
																					<a href=index.php?module=Settings&action=upload&tempModule={$MODULE}&parenttab=Settings>{$APP.LBL_CREATE_MERGE_TEMPLATE}</a>
																				{/if}
																			</div>
																		</div>
																	</article>
																</div>
															</form>
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
																	<div class="flexipageComponent" id="{$CUSTOMLINK->linklabel}">
																		<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
																			<div class="slds-card__header slds-grid">
																				<header class="slds-media slds-media--center slds-has-flexi-truncate">
																					<div class="slds-media__body">
																						<b>{$customlink_label}</b>
																						<img id="detailview_block_{$CUSTOMLINK_NO}_indicator" style="display:none;" src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0" align="absmiddle" />
																					</div>
																				</header>
																			</div>
																			<div class="slds-card__body slds-card__body--inner">
																				<div id="detailview_block_{$CUSTOMLINK_NO}"></div>
																				<script type="text/javascript">
																					vtlib_loadDetailViewWidget("{$customlink_href}", "detailview_block_{$CUSTOMLINK_NO}", "detailview_block_{$CUSTOMLINK_NO}_indicator");
																				</script>
																			</div>
																		</article>
																	</div>
																{/if}
															{/foreach}
														{/if}
													</td>
												</tr>
											</table>
										</div>
									</td>
								</tr>
							</table>
						</div>
						<script>
							function getTagCloud()
							{ldelim}
								var obj = document.getElementById("tagfields");
								if (obj != null && typeof(obj) != undefined) {ldelim}
									jQuery.ajax({ldelim}
										method: "POST",
										url: 'index.php?module={$MODULE}&action={$MODULE}Ajax&file=TagCloud&ajxaction=GETTAGCLOUD&recordid={$ID}',
										{rdelim}).done(function (response) {ldelim}
										jQuery("#tagfields").html(response);
										jQuery("#txtbox_tagfields").val('');
										{rdelim}
									);
									{rdelim}
								{rdelim}
							getTagCloud();
						</script>
						<!-- added for validation -->
						<script>
							var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
							var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
							var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
						</script>
					</td>
				</tr>
			</table>
			{if $MODULE eq 'Leads' or $MODULE eq 'Contacts' or $MODULE eq 'Accounts' or $MODULE eq 'Campaigns' or $MODULE eq 'Vendors' or $MODULE eq 'Project' or $MODULE eq 'Potentials' or $MODULE eq 'ProjectTask' or $MODULE eq 'HelpDesk'}
				<form name="SendMail">
					<div id="sendmail_cont" style="z-index:100001;position:absolute;"></div>
				</form>
			{/if}