<div data-role="panel" id="panelmenu" data-position="left" data-display="overlay">
	<div data-role="header" data-theme="{$COLOR_HEADER_FOOTER}" data-position="fixed" class="ui-grid-b ui-responsive">
		<a href="index.php?_operation=logout" class="ui-btn ui-corner-all ui-icon-power ui-btn-icon-notext" >Logout</a>
		<h4>{$MOD.LBL_MOD_LIST}</h4>
		<a href="?_operation=configCRMTOGO" class="ui-btn ui-corner-all ui-icon-gear ui-btn-icon-notext" data-iconpos="right" data-transition="slidedown"></a>
	</div><!-- /header -->
	<div  data-role="fieldcontain" data-mini="true">
		{literal}
		<script type="text/javascript">
			function fn_submit() {
				document.form.submit();
			}
		</script>
		{/literal}
		<form  name="form"  method="post" action="?_operation=globalsearch&module={$_MODULES[0]->name()}" target="_self">
			<input type="hidden" name="parenttab" value="{$CATEGORY}" style="margin:0px">
			<input type="hidden" name="search_onlyin" value="{$SEARCHIN}" style="margin:0px">
			<table style="width:100%;padding-top:5px;">
				<tr >
					<td>
						<input type="text" data-inline="true" name="query_string" value="{$QUERY_STRING}">
					</td>
					<td>
						<a href="#"  onclick="fn_submit();" target="_self"  class="ui-btn ui-btn-inline ui-icon-search ui-btn-icon-notext ui-corner-all ui-shadow"></a>
					</td>
				</tr>
 			</table>
		</form>
	</div>
	<div data-role="collapsible-set" data-mini="true">
		<ul data-role="listview" data-theme="c" id="homesortable">
		{foreach item=_MODULE from=$_MODULES}
			{if $_MODULE->active() && $_MODULE->name() neq 'Events'}
			<li id={$_MODULE->name()}><a href="index.php?_operation=listModuleRecords&module={$_MODULE->name()}" class="ui-btn ui-btn-icon-right ui-icon-carat-r" target="_self">{$_MODULE->label()}</a></li>
			{/if}
		{/foreach}
		</ul>
	</div>
</div>
