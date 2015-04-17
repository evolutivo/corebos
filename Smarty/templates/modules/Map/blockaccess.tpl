<link type="text/css" href="modules/Map/styles/jquery-ui-1.9.2.custom.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="modules/Map/styles/jquery-multiselect.css"/>
<script type="text/javascript" src="include/js/jquery-ui-1.9.2.custom.js"></script>
<script type="text/javascript" src="include/js/jquery-multiselect.js"></script>
<script type="text/javascript" src="include/js/jquery-multiselect-filter.js"></script>
<script type="text/javascript" src="modules/Map/Map.js"></script>
<script type="text/javascript" src="modules/Map/js/script.js"></script>
<style>
{literal}
.ajax_loader {background: url("modules/Map/styles/images/spinner_squares_circle.gif") no-repeat center center transparent;width:100%;height:100%;}
.blue-loader .ajax_loader {background: url("modules/MVCreator/styles/images/ajax-loader_blue.gif") no-repeat center center transparent;}
{/literal}
</style>
<script>
{literal}
jQuery( document ).ready(function() {
    jQuery( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      },
       height: 380,
       width: 892
    });
    jQuery(".combofilter").multiselect({
            multiple: false,
            header: true,
            noneSelectedText: "Select an Option",
            selectedList: 1
    }).multiselectfilter();
    //if({/literal}"{$mode}"{literal} == "create")
   // addNewConditionGroup('where_filter_div',{/literal}"{$mode}",{$nrFields},{$originID},{$targetID},"{$originFieldsArr}","{$targetFieldsArr}","{$seldelimiter}"{literal});
});
{/literal}
 </script>
<div id="map">
<form method="post" id="theForm" name="theForm">
            <input type="hidden" id="module_list"  value='{$module_list}'>
            <input type="hidden" id="blocks"  value='{$blocks}'>
            <input type="hidden" id="rel_fields"  value='{$rel_fields}'>
 <div style="overflow:auto;" id='where_filter_div' name='where_filter_div'>
	<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr>
			<td class="detailedViewHeader" align="left"><b>{'LBL_CREATEMAP'|@getTranslatedString:$MODULE}</b></td>
		</tr>
		<tr>
		<td colspan="2" align="right">
                      <div id="orgmodd" style="float:left; margin-right: 10%;">
                         <label class="font-style">{$MOD.LBL_SELECT_MODULE}: </label>
                        <select id="orgmod" name="orgmod"  class="small combofilter" onChange="getModuleBlocks(this.value,'target');" >
                         <option value="none">{$MOD.none}</option>
                        {foreach from=$module_id key=k item=v}
                          {if $k eq $originID}
                              <option value="{$k}" selected >{$v|@getTranslatedString:$c}</option>
                          {else}
                              <option value="{$k}">{$v|@getTranslatedString:$c}</option>
                          {/if}
                        {/foreach}
                         </select>
                      </div>
                          <div id="targetblocks" style="margin-right: 10%;">
                        <label class="font-style">{$MOD.LBL_SELECT_BLOCK}: </label>
                       <select id="targetblock" name="targetblock"  class="small combofilter"  onchange="show_multiselect(this.value,'target');">
                         <option value="none">{$MOD.none}</option>
                       {foreach from=$blockid key=k item=v}
                         {if $k eq $targetID}
                               <option value="{$k}" selected >{$v|@getTranslatedString:$c}</option>
                          {else}
                              <option value="{$k}">{$v|@getTranslatedString:$c}</option>
                          {/if}
                        {/foreach}
                         </select>
                      </div>
		</td>
		</tr>
	</table>
        <div>
</div>
</div>
 <center>
 <input type="button" style="margin-top:3%;" name="save" id='savebutton' value="{$MOD.LBL_CREATE}" onclick=" block_access(); return false;" class="ui-button ui-widget ui-state-default ui-corner-all button">
</center>
                <input type="hidden" id="mapid" name="mapid" value="{$mapid}">
        </form>
</div>