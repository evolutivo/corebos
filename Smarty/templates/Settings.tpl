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
<!--    Settings Module -->
<table class="slds-table slds-no-row-hover slds-table--cell-buffer">
    <thead>
    <tr class="slds-text-title--caps">
        {assign var="action" value="WebformsListView"}
        {assign var="MODULELABEL" value=$MODULE|@getTranslatedString:$MODULE}
        <th scope="col" align="left">
            <div class="slds-truncate moduleName" title="{$MODULELABEL}">
                <a class="hdrLink" href="#">{$MODULELABEL}</a>
            </div>
        </th>
    </tr>
    </thead>
</table>
<!--   End Settings Module -->

<!--   First Table -->
<table class="slds-table slds-no-row-hover">
    <tbody>
    <tr>
        <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
            <br>

            <!--   Second Table -->
            <table class="slds-table slds-no-row-hover">
                <tr>
                    <td>

                        <!--    Third Table -->
                        <table class="slds-table slds-no-row-hover settingsUI">
                            <thead>
                            {foreach key=BLOCKID item=BLOCKLABEL from=$BLOCKS} {if $BLOCKLABEL neq 'LBL_MODULE_MANAGER'}
                            <tr>
                                <th scope="col">
                                    <div class="slds-truncate settingsTabHeader slds-text-heading_small-all">
                                        {$MOD.$BLOCKLABEL}
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tr>
                                <td class="settingsIconDisplay small push-bit">
                                    <div class="slds-truncate">

                                        <!--   Fourth Table -->
                                        <table class="slds-table slds-no-row-hover slds-table--fixed-layout"
                                               role="grid">
                                            <tr>
                                                {foreach item=data from=$FIELDS.$BLOCKID name=itr}
                                                <td valign=top>
                                                    {if $data.name eq ''} &nbsp; {else}

                                                        <!--   Fifth Table -->
                                                        <table class="slds-table slds-no-row-hover">
                                                            <tr>
                                                                {assign var=label value=$data.name|@getTranslatedString:$data.module} {if $data.name eq $label} {assign var=label value=$data.name|@getTranslatedString:'Settings'} {/if} {assign var=count value=$smarty.foreach.itr.iteration}
                                                                <td rowspan=2 valign=top style="width: 48px;">
                                                                    <div class="img-container">
                                                                        <a href="{$data.link}">
                                                                            <img src="{$data.icon|@vtiger_imageurl:$THEME}"
                                                                                 alt="{$label}" width="48"
                                                                                 height="48"
                                                                                 border=0 title="{$label}">
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                                <td class="big slds-text-title" valign=top>
                                                                    <a href="{$data.link}">
                                                                        {$label}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                {assign var=description value=$data.description|@getTranslatedString:$data.module} {if $data.description eq $description} {assign var=description value=$data.description|@getTranslatedString:'Settings'} {/if}
                                                                <td class="small slds-cell-wrap" valign=top>
                                                                    {$description}
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        <!--   End Fifth Table -->

                                                    {/if}
                                                </td>
                                                {if $count mod $NUMBER_OF_COLUMNS eq 0}
                                            </tr>
                                            <tr>
                                                {/if} {/foreach}
                                        </table>
                                        <!--   End Fourth Table -->

                                    </div>
                                </td>
                            </tr>
                            {/if} {/foreach}
                        </table>
                        <!--   End Third Table -->

                    </td>
                </tr>
            </table>
            <!--   End Second Table -->

        </td>
    </tr>
</table>
<!--   End First Table -->