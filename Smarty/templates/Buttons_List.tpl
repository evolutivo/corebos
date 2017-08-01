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
<script type="text/javascript" src="modules/{$MODULE}/{$MODULE}.js"></script>
{if empty($Module_Popup_Edit)}
<TABLE id="LB_buttonlist" border=0 cellspacing=0 cellpadding=0 width=98% align=center class=small>
<thead>
<tr class="slds-text-title--caps">
	{if empty($CATEGORY)}
		{assign var="CATEGORY" value=""}
	{/if}
	{if $CATEGORY eq 'Settings' || $MODULE eq 'Calendar4You'}
	{assign var="action" value="index"}
	{else}
	{assign var="action" value="ListView"}
	{/if}
	{assign var="MODULELABEL" value=$MODULE|@getTranslatedString:$MODULE}
	<th scope="col" style="padding: 1rem 1.5rem 1rem 1rem;">
		<div class="slds-truncate moduleName" title="{$MODULELABEL}">
			<a class="hdrLink" href="index.php?action={$action}&module={$MODULE}&parenttab={$CATEGORY}">{$MODULELABEL}</a>
		</div>
	</th>
	<td width=100% nowrap>
		<table border="0" cellspacing="0" cellpadding="0" class="slds-table-buttons">
            <tr>
                <!-- <td class="sep1" style="width:1px;"></td> -->
                <td class=small >
                    <!-- Add and Search -->
                    <table class="slds-table slds-no-row-hover">
                        <tr class="LD_buttonList">
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                   <div class="forceHeaderMenuTrigger">
                                        {if $CHECK.CreateView eq 'yes' && ($MODULE eq 'Calendar' || $MODULE eq 'Calendar4You')}
                                            <div id="LB_AddButton" class="LB_Button slds-truncate">
                                                <span alt="{$MOD.LBL_ADD_EVENT}" title="{$MOD.LBL_ADD_EVENT}" {$ADD_ONMOUSEOVER} class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#new"></use>
                                                    </svg>
                                                </span>
                                                <!-- <img src="{$IMAGE_PATH}utility/add_60.png" alt="{$MOD.LBL_ADD_EVENT}" title="{$MOD.LBL_ADD_EVENT}" border=0 {$ADD_ONMOUSEOVER}> -->
                                            </div>
                                        {elseif $CHECK.CreateView eq 'yes' && $MODULE neq 'Emails' && $MODULE neq 'Webmails'}
                                            <div class="slds-truncate LB_Button" id="LB_AddButton">
                                                
                                                <a class="globalCreateTrigger" href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button"  data-aura-rendered-by="328:0;p" alt="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD|getTranslatedString:$MODULE}..." title="{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD|getTranslatedString:$MODULE}...">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                        <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                            <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#new"></use>
                                                        </svg>
                                                    </span>
                                                </a>

                                            </div>
                                        {else}
                                            <div id="LB_AddButtonFaded" class="slds-truncate LB_Button">

                                                <!-- <img src="{'btnL3Add-Faded.gif'|@vtiger_imageurl:$THEME}" border=0> -->
                                                <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#add"></use>
                                                    </svg>
                                                </span>
                                            </div>
                                        {/if}
                                  </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                        <div class="orceHeaderMenuTrigger">
                                        {if $CHECK.index eq 'yes' && ($smarty.request.action eq 'ListView' || $smarty.request.action eq 'index') && $MODULE neq 'Emails' && $MODULE neq 'Webmails' && $MODULE neq 'Calendar4You'}
                                            <div id="LB_SearchButton" class="slds-truncate LB_Button">
                                                <a class="globalCreateTrigger" href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')" alt="{$APP.LBL_SEARCH_ALT}{$MODULE|getTranslatedString:$MODULE}..." title="{$APP.LBL_SEARCH_TITLE}{$MODULE|getTranslatedString:$MODULE}..." aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton">
                                                        <img class="slds-icon--x-small" src="{$IMAGE_PATH}utility/search_61.png" border=0 >
                                                    </span>
                                                </a>
                                               <!--  <a href="javascript:;" onClick="moveMe('searchAcc');searchshowhide('searchAcc','advSearch');mergehide('mergeDup')" >
                                                    <img src="{$IMAGE_PATH}/utility/search.png" alt="{$APP.LBL_SEARCH_ALT}{$MODULE|getTranslatedString:$MODULE}..." title="{$APP.LBL_SEARCH_TITLE}{$MODULE|getTranslatedString:$MODULE}..." border=0>
                                                </a> -->
                                            </div>
                                        {else}
                                            <div id="LB_SearchButtonFaded" class="LB_Button slds-truncate">
                                                <!-- <img src="{'btnL3Search-Faded.gif'|@vtiger_imageurl:$THEME}" border=0> -->
                                                <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <img class="slds-icon--x-small" src="{$IMAGE_PATH}utility/search_61.png" border=0 >
                                                </span>
                                            </div>
                                        {/if}
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </table>
                </td>
                <td style="width:20px;" class="LB_Divider">&nbsp;&nbsp;</td>
                <td class="small">
                    <!-- Calendar, Clock and Calculator -->
                    <table class="slds-table slds-no-row-hover">
                        <tr class="LD_buttonList">
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $CALENDAR_DISPLAY eq 'true'}

                                        {if $CATEGORY eq 'Settings' || $CATEGORY eq 'Tools' || $CATEGORY eq 'Analytics'}
                                            {assign var="PTCATEGORY" value='My Home Page'}
                                        {else}
                                            {assign var="PTCATEGORY" value=$CATEGORY}
                                        {/if}

                                        {if $CHECK.Calendar eq 'yes'}
                                            <div id="LB_CalButton" class="LB_Button slds-truncate">

                                                <!-- <a href="javascript:;" onclick="fnvshobj(this,'miniCal');getITSMiniCal('');">
                                                    <img src="{'btnL3Calendar.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" border=0>
                                                </a> -->
                                                <a class="globalCreateTrigger" href="javascript:;" onclick="fnvshobj(this,'miniCal');getITSMiniCal('');" alt="{$APP.LBL_CALENDAR_ALT}" title="{$APP.LBL_CALENDAR_TITLE}" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                        <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                            <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#monthlyview"></use>
                                                        </svg>
                                                    </span>
                                                </a>

                                            </div>
                                        {else}
                                            <div id="LB_CalButtonFaded" class="LB_Button slds-truncate">

                                                <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#monthlyview"></use>
                                                    </svg>
                                                </span>
                                                <!-- <img src="{'btnL3Calendar-Faded.gif'|@vtiger_imageurl:$THEME}"> -->

                                            </div>

                                        {/if}

                                    {/if}
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $WORLD_CLOCK_DISPLAY eq 'true'}
                                        <div id="LB_ClockButton" class="LB_Button slds-truncate">
                                           <!--  <a href="javascript:;">
                                                <img src="{$IMAGE_PATH}btnL3Clock.gif" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" border=0 onClick="fnvshobj(this,'wclock');">
                                            </a> -->
                                            <a class="globalCreateTrigger" href="javascript:;" alt="{$APP.LBL_CLOCK_ALT}" title="{$APP.LBL_CLOCK_TITLE}" onClick="fnvshobj(this,'wclock');" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#clock"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $CALCULATOR_DISPLAY eq 'true'}
                                        <div id="LB_CalcButton" class="LB_Button slds-truncate">
                                            <a class="globalCreateTrigger" href="javascript:;" alt="{$APP.LBL_CALCULATOR_ALT}" title="{$APP.LBL_CALCULATOR_TITLE}" onClick="fnvshobj(this,'calculator_cont');fetch_calc();" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#table"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                        <div id="LB_TrackButton" class="LB_Button slds-truncate">
                                            <a class="globalCreateTrigger" href="javascript:;" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" onClick="fnvshobj(this,'tracker');" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" data-aura-rendered-by="310:0;p">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#preview"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                            <!-- <img src="{$IMAGE_PATH}btnL3Tracker.gif" alt="{$APP.LBL_LAST_VIEWED}" title="{$APP.LBL_LAST_VIEWED}" border=0 onClick="fnvshobj(this,'tracker');"> -->
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </table>
                </td>
                <td style="width:20px;" class="LB_Divider">&nbsp;</td>
                <td class="small">
                    <!-- Import / Export / DuplicatesHandling-->
                    <table class="slds-table slds-no-row-hover">
                        <tr class="LD_buttonList">
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $CHECK.Import eq 'yes' && $MODULE neq 'Documents' && $MODULE neq 'Calendar' && $MODULE neq 'Calendar4You'}
                                        <div id="LB_ImportButton" class="LB_Button slds-truncate">
                                            <!-- <a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}">
                                            <img src="{$IMAGE_PATH}tbarImport.gif" alt="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" title="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" border="0">
                                            </a> -->
                                            <a class="globalCreateTrigger" href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}" alt="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" title="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton">        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#upload"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {elseif $CHECK.Import eq 'yes' && $MODULE eq 'Calendar'}
                                        <div id="LB_ImportButton" class="LB_Button slds-truncate">
                                        
                                        <!-- <a name='import_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalImport');" >
                                        <img src="{$IMAGE_PATH}tbarImport.gif" alt="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" 
                                        title="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" border="0"></a> -->
                                            <a class="globalCreateTrigger" name='import_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalImport');" alt="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" title="{$APP.LBL_IMPORT} {$MODULE|getTranslatedString:$MODULE}" aria-disabled="false" aria-haspopup="true" tabindex="0" role="button">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#upload"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {else}
                                        <div id="LB_ImportButtonFaded" class="LB_Button slds-truncate">
                                            <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton">        
                                                <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                    <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#upload"></use>
                                                </svg>
                                            </span>

                                            <!-- <img src="{'tbarImport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"> -->
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $CHECK.Export eq 'yes' && $MODULE neq 'Calendar' && $MODULE neq 'Calendar4You'}
                                        <div id="LB_ExportButton" class="LB_Button slds-truncate">
                                            <a class="globalCreateTrigger" name='export_link' href="javascript:void(0)" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')" alt="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}" title="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#download"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {elseif $CHECK.Export eq 'yes' && $MODULE eq 'Calendar'}
                                        <div id="LB_ExportButton" class="LB_Button slds-truncate">
                                            <!-- <a name='export_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalExport');" >
                                            <img src="{$IMAGE_PATH}tbarExport.gif" alt="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}" 
                                            title="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}" border="0">
                                            </a> -->

                                            <a class="globalCreateTrigger" name='export_link' href="javascript:void(0);" onclick="fnvshobj(this,'CalExport');" alt="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}" title="{$APP.LBL_EXPORT} {$MODULE|getTranslatedString:$MODULE}">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#download"></use>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    {else}
                                        <div id="LB_ExportButtonFaded" class="LB_Button slds-truncate">
                                            <!-- <img src="{'tbarExport-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"> -->
                                            <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton">        
                                                <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                    <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#download"></use>
                                                </svg>
                                            </span>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </th>
                            <th scope="col">
                                <div class="globalCreateContainer oneGlobalCreate">
                                    <div class="orceHeaderMenuTrigger">
                                    {if $CHECK.DuplicatesHandling eq 'yes' && ($smarty.request.action eq 'ListView' || $smarty.request.action eq 'index')}
                                        <div id="LB_FindDuplButton" class="LB_Button slds-truncate">
                                            <!-- <a href="javascript:;" 
                                            onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');">
                                            <img src="{'findduplicates.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_FIND_DUPLICATES}" 
                                            title="{$APP.LBL_FIND_DUPLICATES}" border="0">
                                            </a> -->

                                            <a class="globalCreateTrigger" href="javascript:;" onClick="moveMe('mergeDup');mergeshowhide('mergeDup');searchhide('searchAcc','advSearch');" alt="{$APP.LBL_FIND_DUPLICATES}" title="{$APP.LBL_FIND_DUPLICATES}">
                                                <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                    <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                        <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#cases"></use>
                                                    </svg>
                                                </span>
                                            </a>

                                        </div>
                                    {else}
                                        <div id="LB_FindDuplButtonFaded" class="LB_Button slds-truncate">
                                            <!-- <img src="{'FindDuplicates-Faded.gif'|@vtiger_imageurl:$THEME}" border="0"> -->
                                            <span class="disabled slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                    <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#cases"></use>
                                                </svg>
                                            </span>
                                        </div>
                                    {/if}
                                    </div>
                                </div>
                            </th>
                        </tr>
                    </table>
                    <td style="width:20px;" class="LB_Divider">&nbsp;</td>
                    <td class="small">
                        <table class="slds-table slds-no-row-hover">
                            <tr class="LD_buttonList">
                                <th scope="col">
                                    <div class="globalCreateContainer oneGlobalCreate">
                                        <div class="orceHeaderMenuTrigger">
                                        {if $MODULE eq 'Calendar4You'}
                                            {if $MODE neq 'DetailView' && $MODE neq 'EditView' && $MODE neq 'RelatedList'}
                                            <div id="LB_ITSCalSettings" class="LB_Button slds-truncate" style="padding-left:50px;">
                                                <!-- <a href="javascript:;" onclick="fnvshobj(this,'calSettings'); getITSCalSettings();">
                                                    <img src="themes/softed/images/tbarSettings.gif" alt="Settings" title="Settings" 
                                                    align="absmiddle" border="0">
                                                </a> -->
                                                
                                                <a class="globalCreateTrigger" href="javascript:;" onclick="fnvshobj(this,'calSettings'); getITSCalSettings();" alt="Settings" title="Settings">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                        <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                            <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#settings"></use>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                            {/if}
                                            <div id="LB_TaskIcon" class="LB_Button slds-truncate">
                                               <!--  <a href='index.php?module=Calendar&action=index'>
                                                    <img src="themes/images/tasks-icon.png" alt="{'Tasks'|getTranslatedString:$MODULE}" 
                                                    title="{'Tasks'|getTranslatedString:$MODULE}" border="0">
                                                </a> -->

                                                <a class="globalCreateTrigger" href='index.php?module=Calendar&action=index' alt="{'Tasks'|getTranslatedString:$MODULE}" title="{'Tasks'|getTranslatedString:$MODULE}">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                        <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                            <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#settings"></use>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        {/if}
                                        </div>
                                    </div>
                                </th>
                                <th scope="col">
                                    <div class="globalCreateContainer oneGlobalCreate">
                                        <div class="orceHeaderMenuTrigger">
                                        {if $CHECK.moduleSettings eq 'yes'}
                                            <div id="LB_ModSettingsButton" class="LB_Button slds-truncate">

                                                <!-- <a href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule={$MODULE}&parenttab=Settings'>
                                                    <img src="{'settingsBox.png'|@vtiger_imageurl:$THEME}" alt="{$MODULE|getTranslatedString:$MODULE} {$APP.LBL_SETTINGS}" 
                                                    title="{$MODULE|getTranslatedString:$MODULE} {$APP.LBL_SETTINGS}" border="0">
                                                </a>
 -->
                                                <a class="globalCreateTrigger" href='index.php?module=Settings&action=ModuleManager&module_settings=true&formodule={$MODULE}&parenttab=Settings' alt="{$MODULE|getTranslatedString:$MODULE} {$APP.LBL_SETTINGS}" title="{$MODULE|getTranslatedString:$MODULE} {$APP.LBL_SETTINGS}">
                                                    <span class="slds-icon_container slds-icon-utility-add slds-button slds-button--icon slds-button--icon-small slds-button--icon-container slds-button--icon-x-small slds-global-header__button--icon-actions slds-m-horizontal--xx-small globalCreateButton" >        
                                                        <svg class="slds-icon slds-icon--x-small slds-icon" focusable="false" data-key="add" aria-hidden="true" >
                                                            <use xlink:href="{$IMAGE_PATH}utility-sprite/svg/symbols.svg#settings"></use>
                                                        </svg>
                                                    </span>
                                                </a>

                                            </div>
                                        {/if}
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </td>
            </tr>
		</table>
	</td>
</tr>
<tr><td style="height:2px"></td></tr>
</thead>
</TABLE>
{/if}