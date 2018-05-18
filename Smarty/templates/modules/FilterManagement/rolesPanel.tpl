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

<script src="kendoui/js/jquery.min.js"></script>
<script type="text/javascript" charset="utf-8">
        jQuery.noConflict();
</script>
<script src="kendoui/js/kendo.web.min.js"></script>
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JAVASCRIPT" src="modules/Home/Homestuff.js" type="text/javascript"></script>
<script language="JAVASCRIPT" src="include/js/json.js" type="text/javascript"></script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
	<td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>
	<div align=center>
		<div id="exampleForm" class="k-content"> 
		<!-- DISPLAY -->
		
			<table border=0 cellspacing=0 cellpadding=5 width=80% class="tableHeading">
			<tr><td valign=top><table ROWS="1" width=100%><tr><td width=30%>
				Gestione permessi
				<div id="picklist_datas">	
					{include file='modules/FilterManagement/configPanel.tpl'}
				</div>                                
				
			</tr>
			</table>
                                <table border=0 cellspacing=0 cellpadding=10 width=80% >
		<tr>
			<br/><br/><br/><br/>
                         
                       Impostazione dei filtri di default     
                       <div id="firstgrid" ></div>
                        </tr></table><br/>
</div>
			<table border=0 cellspacing=0 cellpadding=5 width=100% >
			<tr>
				<td class="small" nowrap align=right>
					<a href="#top">
						{$MOD.LBL_SCROLL}
					</a>
				</td>
			</tr>
			</table>
			 
		</tr>
		</table>
	</div>
	</td>
</tr>
</tbody>
</table>
                                       