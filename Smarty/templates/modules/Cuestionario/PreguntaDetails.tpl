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

<script type="text/javascript" src="modules/Cuestionario/Cuestionario.js"></script>
<script>
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

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

   <tr>
	<td colspan="4" align="left">

<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
   <tr>
	<td colspan="7" class="dvInnerHeader">
		<b>{$MOD.LBL_ITEM_DETAILS}</b>
	</td>
   </tr>

   <!-- Header for the Jornada Details -->
   <tr valign="top">
	<td width=6% valign="top" class="lvtCol" align="right"><b>{$APP.LBL_TOOLS}</b></td>
	<td width=44% class="lvtCol"><b>{$MOD.LBL_PREGUNTA}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_CATEGORIA}</b></td>
	<td width=20% class="lvtCol"><b>{$MOD.LBL_SUBCATEGORIA}</b></td>
	<td width=5% class="lvtCol"><b>{$MOD.LBL_YES_POINTS}</b></td>
	<td width=5% class="lvtCol"><b>{$MOD.LBL_NO_POINTS}</b></td>
   </tr>

<!-- Following code is added for form the first row. Based on these we should form additional rows using script -->

   <!-- Product Details First row - Starts -->
   <tr valign="top" id="row1">

	<!-- column 1 - delete link - starts -->
	<td  class="crmTableRow small lineOnTop">&nbsp;
		<input type="hidden" id="deleted_1" name="deleted1" value="0">
		<input type="hidden" id="preguntasid_1" name="preguntasid_1" value="0">
	</td>
	<!-- column 1 - delete link - ends -->

	<!-- column 2 - Selecionar Pregunta - starts -->
	<td class="crmTableRow small lineOnTop">
	  
	  <!--><input name="cuestiones_id" value="" type="hidden"> Solo-->

      
     <input id="RLreturn_module" name="RLreturn_module" value="Cuestionario" type="hidden">
     <input id="RLparent_id" name="RLparent_id" value="{$ID}" type="hidden">


      <input name="pregunta1" id="pregunta1" value="" readonly="readonly" type="text" style="width:430px">&nbsp;<img id="select1" tabindex="" src="themes/softed/images/select.gif" alt="Seleccionar" title="Seleccionar" language="javascript" onclick='return window.open("index.php?module=Preguntas&count=1&action=Popup&popuptype=set_preguntas&html=Popup_picker&select=enable&fromlink=","test","width=640,height=602,resizable=1,scrollbars=1,top=150,left=200");' style="cursor: pointer;" align="absmiddle">
	  </td>
      <!-- column 3 - Categoria read only - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="category1" id="category1" value=""  type="text">
	  </td>

      <!-- column 4 - Subategoria read only - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="subcategory1" id="subcategory1" value="" type="text">
	  </td>

      <!-- column 5 - Puntos Si editable - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="yes_points1" id="yes_points1" value="" type="text" style="width:30px">
	  </td>

      <!-- column 6 - Puntos No editable - starts -->
      <td class="crmTableRow small lineOnTop">

      <input name="no_points1" id="no_points1" value="" type="text" style="width:30px">
	  </td>
   </tr>
   <!-- Product Details First row - Ends -->
</table>
<!-- Upto this has been added for form the first row. Based on these above we should form additional rows using script -->


<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable">
   <!-- Add Jornada Button -->
   <tr>
	<td colspan="3">
		<input type="button" name="Button" class="crmbutton small create" value="{$MOD.LBL_ADD_PREGUNTA}" onclick="fnAddJLine('{$IMAGE_PATH}');" />
	</td>
   </tr>

</table>
	<input type="hidden" name="totalRows" id="totalRows" value="1">
	</td>
   </tr>
