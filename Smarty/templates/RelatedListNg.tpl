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
<!-- Contents -->
<!-- PUBLIC CONTENTS STARTS-->
{ngtab}
{foreach key=header item=detail from=$ng_tabs}
{if $SOURCE eq 'DV'}
<td class="dvtTabCache" >&nbsp;</td>
<td {if $ng_tab neq $header}class="dvtUnSelectedCell"{else}class="dvtSelectedCell"{/if} align=center onmouseout="fnHideDrop('More_Information_{$header}');" onmouseover="fnDropDown(this,'More_Information_{$header}');" align="center" nowrap>
    <a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&ng_tab={$header}">{$detail.tab_name}</a>
    <div onmouseover="fnShowDrop('More_Information_{$header}')" onmouseout="fnHideDrop('More_Information_{$header}')"
         id="More_Information_{$header}" class="drop_mnu" style="left: 502px; top: 76px; display: none;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            {foreach key=_RELATION_ID item=_RELATED_MODULE from=$detail.items}
                <tr><td><a class="drop_down" href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE.relatedmodule}&relation_id={$_RELATED_MODULE.id}&ng_tab={$header}">{$_RELATED_MODULE.name}</a></td></tr>
            {/foreach}
        </table>
    </div>
</td>{else}
<div class="detailview_utils_table_tab detailview_utils_table_tab_selected detailview_utils_table_tab_selected_top">
{if $smarty.request.ng_tab neq ''}
{$detail.tab_name}
{else}
<a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&ng_tab={$header}">{$detail.tab_name}</a>
{/if}
</div>
{/if}
{/foreach}
<!-- PUBLIC CONTENTS STOPS-->

