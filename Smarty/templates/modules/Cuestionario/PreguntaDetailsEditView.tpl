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

<script type="text/javascript">
if(!e)
	window.captureEvents(Event.MOUSEMOVE);

//  window.onmousemove= displayCoords;
//  window.onclick = fnRevert;
  
	function doNothing(){ldelim}
	{rdelim}
	
	function fnHidePopDiv(obj){ldelim}
		document.getElementById(obj).style.display = 'none';
	{rdelim}
</script>


<tr><td colspan="4" align="left">

<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
   <tr>
		<td colspan="7" class="dvInnerHeader">
		<b>{$APP.LBL_ITEM_DETAILS}</b>
	</td>
   </tr>

   <!-- Header for the Pregunta Details Edit View -->
   <tr valign="top">
	<td width=6% valign="top" class="lvtCol" align="right"><b>{$APP.LBL_TOOLS}</b></td>
	<td width=44% class="lvtCol"><b>{$MOD.LBL_PREGUNTA}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_CATEGORIA}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_SUBCATEGORIA}</b></td>
	<td width=5% class="lvtCol"><b>{$MOD.LBL_YES_POINTS}</b></td>
	<td width=5% class="lvtCol"><b>{$MOD.LBL_NO_POINTS}</b></td>
   </tr>

   {foreach key=row_no item=data from=$ASSOCIATEDCUESTIONES name=outer1}
	{assign var="preguntasid" value="preguntasid_"|cat:$row_no}
	{assign var="preguntaid" value="preguntasid"|cat:$row_no}
	{assign var="deleted" value="deleted_"|cat:$row_no}
	{assign var="pregunta" value="pregunta"|cat:$row_no}
	{assign var="category" value="category"|cat:$row_no}
	{assign var="subcategory" value="subcategory"|cat:$row_no}
	{assign var="yes_points" value="yes_points"|cat:$row_no}
	{assign var="no_points" value="no_points"|cat:$row_no}

   <tr id="row{$row_no}" valign="top">

	<!-- column 1 - delete link - starts -->
	<td  class="crmTableRow small lineOnTop">
		{if $row_no neq 1}
			<img src="{'delete.gif'|@vtiger_imageurl:$THEME}" border="0" onclick="fnDelJLine({$row_no})">
		{/if}<br/><br/>
        {if $row_no neq 1}
			&nbsp;<a href="javascript:moveUpDown('UP',{$row_no})" title="Move Upward"><img src="{'up_layout.gif'|@vtiger_imageurl:$THEME}" border="0"></a>
		{/if}
        {if not $smarty.foreach.outer1.last}
			&nbsp;<a href="javascript:moveUpDown('DOWN',{$row_no})" title="Move Downward"><img src="{'down_layout.gif'|@vtiger_imageurl:$THEME}" border="0" ></a>
		{/if}
		<input type="hidden" id="{$deleted}" name="{$deleted}" value="0">
		<input type="hidden" id="{$preguntasid}" name="{$preguntasid}" value="{$data.$preguntaid}">
	</td>

	<!-- column 2 -Inicio Selecionar Pregunta-->
	<td class="crmTableRow small lineOnTop">

	  <!--><input name="cuestiones_id" value="" type="hidden"> Solo-->



      <input name="{$pregunta}" id="{$pregunta}" value="{$data.$pregunta}" readonly="readonly" type="text" style="width:430px">&nbsp;<img id="select1" tabindex="" src="themes/softed/images/select.gif" alt="Seleccionar" title="Seleccionar" language="javascript" onclick='return window.open("index.php?module=Preguntas&count={$row_no}&action=Popup&popuptype=set_preguntas&html=Popup_picker&select=enable&fromlink=","test","width=640,height=602,resizable=1,scrollbars=1,top=150,left=200");' style="cursor: pointer;" align="absmiddle">
	  </td>
    <!-- column 2 - Fin Selecionar Pregunta -->

	<!-- column 3 - Categoria read only - starts -->
      <td class="crmTableRow small lineOnTop">
      <input name="{$category}" id="{$category}" value="{$data.$category}"  type="text">
	  </td>
    <!-- column 3 - Categoria read only - ends -->


      <!-- column 4 - Subategoria read only - starts -->
      <td class="crmTableRow small lineOnTop">
      <input name="{$subcategory}" id="{$subcategory}" value="{$data.$subcategory}" type="text">
	  </td>
      <!-- column 4 - Subategoria read only - ends -->

      <!-- column 5 - Puntos Si editable - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="{$yes_points}" id="{$yes_points}" value="{$data.$yes_points}" type="text" style="width:30px">
	  </td>
      <!-- column 5 - Puntos Si editable - ends -->

      <!-- column 6 - Puntos No editable - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="{$no_points}" id="{$no_points}" value="{$data.$no_points}" type="text" style="width:30px">
	  </td>
      <!-- column 6 - Puntos No editable - ends -->
   </tr>
   {/foreach}
</table>

<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
   <!-- Add Product Button -->
   <tr>
	<td colspan="3">
		<input type="button" name="Button" class="crmbutton small create" value="{$MOD.LBL_ADD_PREGUNTA}" onclick="fnAddJLine('{$IMAGE_PATH}');" />
	</td>
   </tr>
</table>
<input type="hidden" name="totalRows" id="totalRows" value="{$row_no}">
</td></tr>
<!-- Upto this Added to display the Product Details -->
