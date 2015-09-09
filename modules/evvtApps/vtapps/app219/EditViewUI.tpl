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
<!--<link href="Smarty/templates/kendoui/styles/kendo.common.min.css" rel="stylesheet">
<link href="Smarty/templates/kendoui/styles/kendo.default.min.css" rel="stylesheet">
<script src="Smarty/templates/kendoui/js/jquery.min.js"></script>
<script src="Smarty/templates/kendoui/js/kendo.web.min.js"></script>
<script src="Smarty/templates/kendoui/js/console.js"></script>-->

{assign var="uitype" value="$maindata[0][0]"}
		{assign var="fldlabel" value="$maindata[1][0]"}
		{assign var="fldlabel_sel" value="$maindata[1][1]"}
		{assign var="fldlabel_combo" value="$maindata[1][2]"}
		{assign var="fldlabel_other" value="$maindata[1][3]"}
		{assign var="fldname" value="$maindata[2][0]"}
		{assign var="fldvalue" value="$maindata[3][0]"}
		{assign var="secondvalue" value="$maindata[3][1]"}
		{assign var="thirdvalue" value="$maindata[3][2]"}
		{assign var="typeofdata" value="$maindata[4]"} 
	 	{assign var="vt_tab" value="$maindata[5][0]"}
                {php}
                require_once('include/utils/UserInfoUtil.php');
                global $current_user;
                $this->assign("user_cat", getRoleName($current_user->roleid));
                if((strpos($current_user->user_name,"superadmin") !== false && $current_user->is_admin == 'on') || $current_user->roleid=='H63')
                $this->assign("isSuperadmin",1);
                {/php}
		{if $typeofdata eq 'M' ||($MODULE eq 'PCDetails' && $fldname eq 'linktoproduct' && $fromlink eq 'qcreate')}
			{assign var="mandatory_field" value="*"}
                {else}
			{assign var="mandatory_field" value=""}
		{/if}

		{* vtlib customization: Help information for the fields *}
		{assign var="usefldlabel" value=$fldlabel}
		{assign var="fldhelplink" value=""}
		{if $FIELDHELPINFO && $FIELDHELPINFO.$fldname}
			{assign var="fldhelplinkimg" value='help_icon.gif'|@vtiger_imageurl:$THEME}
			{assign var="fldhelplink" value="<img style='cursor:pointer' onclick='vtlib_field_help_show(this, \"$fldname\");' border=0 src='$fldhelplinkimg'>"}
			{if $uitype neq '10'}
				{assign var="usefldlabel" value="$fldlabel $fldhelplink"}
			{/if}
		{/if}
		{* END *}
{*<!-- //NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS	 -->*}
{assign var="add_property_to_field" value="$maindata[100]"}    
   
{if $add_property_to_field eq 'readonly'}
	{include file="NotEditableFields.tpl"}
{else}  
{*<!-- //NABLACOM END ADVANCED UI WITH VIEW ONLY FIELDS	 -->*}          
		{* vtlib customization *}
{if $uitype eq '10'}

			
			
			{if count($fldlabel.options) eq 1}
				{assign var="use_parentmodule" value=$fldlabel.options.0}
				<input type='hidden' class='small' name="{$fldname}_type" value="{$use_parentmodule}">
			{else}
			<br>
			{if $fromlink eq 'qcreate'}
		       <select id="{$fldname}_type" class="small" name="{$fldname}_type" onChange='document.QcEditView.{$fldname}_display.value=""; document.QcEditView.{$fldname}.value="";'>
			{else}
			<select id="{$fldname}_type" class="small" name="{$fldname}_type" onChange='document.EditView.{$fldname}_display.value=""; document.EditView.{$fldname}.value="";$("qcform").innerHTML=""'>
			{/if}
			{foreach item=option from=$fldlabel.options}
				<option value="{$option}" 
				{if $fldlabel.selected == $option}selected{/if}>
				{$option|@getTranslatedString:$option}
				</option> 
			{/foreach}
			</select>
			{/if}
			{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			{$fldhelplink}

			
			
<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$fldlabel.displaylabel} </li>
           
        </ul>
        <p>
        <input id="{$fldname}" name="{$fldname}" type="hidden" value=" {if $fldvalue.entityid eq '' && $fldname eq 'progetto'}1001614 {else}{$fldvalue.entityid}{/if}" id="{$fldname}">
       <input id="{$fldname}_display" name="{$fldname}_display" id="edit_{$fldname}_display" 
       readonly type="text" {$class} style="height:2px;border-style:solid;border-width:1px;"
       value="{if $fldvalue.entityid eq '' && $fldname eq 'progetto'}SNY-IW-[CAT]-Carry In {else}{$fldvalue.displayvalue}{/if}">&nbsp;
       <img src="themes/teknema/images/select.gif" tabindex="{$vt_tab}"
alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module="+ document.EditView.{$fldname}_type.value +"&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield={$fldname}&srcmodule={$MODULE}&forrecord={$ID}&vtapp=sony","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;

<input type="image" src="themes/images/clear_field.gif"
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;

        </p>
          
    </div>
</div> 
                  
			
		{* END *}
		{elseif $uitype eq 2}
<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
       <input type="text" name="{$fldname}" tabindex="{$vt_tab}" value="{$fldvalue}" 
              tabindex="{$vt_tab}" {$class} style="height:2px;border-style:solid;border-width:1px;">
      </p>         
    </div>
</div> 
                      
		{elseif $uitype eq 3 || $uitype eq 4}<!-- Non Editable field, only configured value will be loaded -->
                    <div {$class} >
                    <span class="alert-icon"><i {$image_class}></i></span>
                    <div class="notification-info" style="height:50px;">
                        <ul class="clearfix notification-meta">
                            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                        </ul>
                        <p>
                       <input type="text" name="{$fldname}" tabindex="{$vt_tab}" {if $MODE eq 'edit'} value="{$fldvalue}" {else} value="{$MOD_SEQ_ID}" {/if}
                              tabindex="{$vt_tab}" {$class} style="height:2px;border-style:solid;border-width:1px;">
                      </p>         
                    </div>
                </div> 
           {elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}
<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
       <input type="text" name="{$fldname}" tabindex="{$vt_tab}" value="{$fldvalue}" 
              tabindex="{$vt_tab}" {$class} style="height:2px;border-style:solid;border-width:1px;">
      </p>         
    </div>
</div> 
 {elseif $uitype eq 19 || $uitype eq 20}
			<!-- In Add Comment are we should not display anything -->
			{if $fldlabel eq $MOD.LBL_ADD_COMMENT}
				{assign var=fldvalue value=""}
			{/if}
                        {if $fldname eq 'description' && $MODULE eq 'Project'}
                           <td class="dvtCellLabel" align=left width=25%>{$usefldlabel}<span style="color:#A8A8A8;"> ( Fare click sul bottone "INSERISCI COMMENTO")</span>
                            <br>
                            <textarea class="detailedViewTextBox" type="text" name="{$fldname}_input" id="{$fldname}_input" ></textarea>
                            <br>
                            <input name="Add Comment" type="button" class="crmbutton small save" value="INSERISCI COMMENTO"
         onclick="document.getElementById('opt_desc').style.display='block';
         posLay(this,'opt_desc');
         {if $OP_MODE eq 'create_view' }
         document.getElementById('opt_desc').style.left='25px';
         document.getElementById('opt_desc').style.top='600px';
         {/if}"/>
                            <div id="opt_desc" style="position:absolute;display:none;z-index:100000000000;">
                                <table bgcolor="#D8D8D8 " border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr><td><a href="javascript:;" {if $OP_MODE eq 'create_view' } onclick="add_comment_project('','{$fldname}','{$usefldlabel}','{$MODULE}','Next');" {else} onclick="add_comment_project({$ID},'{$fldname}','{$usefldlabel}','{$MODULE}','Next');" {/if} class="calMnu">Next</a></td></tr>
                                <tr><td><a href="javascript:;" {if $OP_MODE eq 'create_view' } onclick="add_comment_project('','{$fldname}','{$usefldlabel}','{$MODULE}','Waiting On');" {else} onclick="add_comment_project({$ID},'{$fldname}','{$usefldlabel}','{$MODULE}','Waiting On');" {/if} class="calMnu">Waiting On</a></td></tr>
                                <tr><td><a href="javascript:;" {if $OP_MODE eq 'create_view' } onclick="add_comment_project('','{$fldname}','{$usefldlabel}','{$MODULE}','Info');" {else} onclick="add_comment_project({$ID},'{$fldname}','{$usefldlabel}','{$MODULE}','Info');" {/if} class="calMnu">Info</a></td></tr>
                                </table>
                            </div>
                            </td>
                            <td colspan=3 width=25% class="dvtCellInfo" align="left" >{*&nbsp;<span id="dtlview_{$usefldlabel}">{$fldvalue|nl2br}</span>*}
                               <textarea {$readonly_field}  value="{$fldvalue}" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea></td>

                        {else}
                         <div {$class} >
                            <span class="alert-icon"><i {$image_class}></i></span>
                            <div class="notification-info" style="height:50px;">
                                <ul class="clearfix notification-meta">
                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                                </ul>
                                <p>

                                <textarea {$class} style="height:2px;border-style:solid;border-width:1px;" tabindex="{$vt_tab}" name="{$fldname}"  cols="90" rows="8">{$fldvalue}</textarea>

                                        {if $fldlabel eq $MOD.Solution}
                                        <input type = "hidden" name="helpdesk_solution" value = '{$fldvalue}'>
                                        {/if}
                                </p>         
                            </div>
                        </div> 
                        {/if}
		{elseif $uitype eq 21 || $uitype eq 24}
			<div {$class} style="height:100px;">
                            <span class="alert-icon"><i {$image_class}></i></span>
                            <div class="notification-info" style="height:50px;">
                                <ul class="clearfix notification-meta">
                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                                </ul>
                                <p>
                <textarea value="{$fldvalue}" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea>
                                </p>         
                            </div>
                        </div> 
			
		{elseif $uitype eq 15 || $uitype eq 16} <!-- uitype 111 added for noneditable existing picklist values - ahmed -->
		<div {$class}>
                <span class="alert-icon"><i {$image_class}></i></span>
                <div class="notification-info" style="height:50px;">
                    <ul class="clearfix notification-meta">
                        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                    </ul>
                    <p>
                  {if $MODULE eq 'Project' && $fldname eq 'substatusproj' && $isSuperadmin neq 1}
                                <select disabled  
                                        name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}"  >
                                {else}
                                <select name="{$fldname}"  id="{$fldname}" tabindex="{$vt_tab}"  >
			        {/if}
				{foreach item=arr from=$fldvalue}
					{if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
					<option value="{$arr[0]}" {$arr[2]}>
						{$arr[0]}
					</option>
					{elseif $fldname eq 'pcdetailsstatus' && $arr[1] eq 'REQUESTED BY LSP/CAT' && $stato eq 'REQUESTED BY LSP/CAT' && $fromlink eq 'qcreate' }
					<option value="{$arr[1]}" selected>
                                                {$arr[0]}
                                        </option>
                                        {else}
					<option value="{$arr[1]}" {$arr[2]}>
                                                {$arr[0]}
                                        </option>
					{/if}
				{foreachelse}
					<option value=""></option>
					<option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
				{/foreach}
			   </select>
                              </p>         
                </div>
            </div> 
		{elseif $uitype eq 33}
			<div {$class}>
                <span class="alert-icon"><i {$image_class}></i></span>
                <div class="notification-info" style="height:50px;">
                    <ul class="clearfix notification-meta">
                        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                    </ul>
                    <p>
			   <select MULTIPLE name="{$fldname}" size="4" style="width:160px;" tabindex="{$vt_tab}" class="small">
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[2]}>
                                                {$arr[0]}
                                        </option>
				{/foreach}
			   </select>
			</p>         
                </div>
            </div> 
	{elseif $uitype eq 53}
			<div {$class}>
                <span class="alert-icon"><i {$image_class}></i></span>
                <div class="notification-info" style="height:50px;">
                    <ul class="clearfix notification-meta">
                        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                    </ul>
                    <p>
				{assign var=check value=1}
				{foreach key=key_one item=arr from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						{if $value ne ''}
							{assign var=check value=$check*0}
						{else}
							{assign var=check value=$check*1}
						{/if}
					{/foreach}
				{/foreach}

				{if $check eq 0}
					{assign var=select_user value='checked'}
					{assign var=style_user value='display:block'}
					{assign var=style_group value='display:none'}
				{else}
					{assign var=select_group value='checked'}
					{assign var=style_user value='display:none'}
					{assign var=style_group value='display:block'}
				{/if}

				<input type="radio" tabindex="{$vt_tab}" name="assigntype" {$select_user} value="U" onclick="toggleAssignType(this.value)" >&nbsp;{$APP.LBL_USER}

				{if $secondvalue neq ''}
					<input type="radio" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">&nbsp;{$APP.LBL_GROUP}
				{/if}

				<span id="assign_user" style="{$style_user}">
					<select name="{$fldname}" class="small">
						{foreach key=key_one item=arr from=$fldvalue}
							{foreach key=sel_value item=value from=$arr}
								<option value="{$key_one}" {$value}>{$sel_value}</option>
							{/foreach}
						{/foreach}
					</select>
				</span>

				{if $secondvalue neq ''}
					<span id="assign_team" style="{$style_group}">
						<select name="assigned_group_id" class="small">';
							{foreach key=key_one item=arr from=$secondvalue}
								{foreach key=sel_value item=value from=$arr}
									<option value="{$key_one}" {$value}>{$sel_value}</option>
								{/foreach}
							{/foreach}
						</select>
					</span>
				{/if}
			</p>         
                </div>
            </div> 
		{elseif $uitype eq 52 || $uitype eq 77}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $uitype eq 52}
					<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
				{elseif $uitype eq 77}
					<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
				{else}
					<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
				{/if}

				{foreach key=key_one item=arr from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						<option value="{$key_one}" {$value}>{$sel_value}</option>
					{/foreach}
				{/foreach}
				</select>
			</td>
		{elseif $uitype eq 51}
			{if $MODULE eq 'Accounts'}
				{assign var='popuptype' value = 'specific_account_address'}
			{else}
				{assign var='popuptype' value = 'specific_contact_account_address'}
			{/if}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" style="border:1px solid #bababa;" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype={$popuptype}&form=TasksEditView&form_submit=false&fromlink={$fromlink}&recordid={$ID}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		{elseif $uitype eq 50}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		{elseif $uitype eq 73}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
								<input readonly name="account_name" id = "single_accountid" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
			{elseif $uitype eq 75 || $uitype eq 81}
			<td width="20%" class="dvtCellLabel" align=right>
				{if $uitype eq 81}
					{assign var="pop_type" value="specific_vendor_address"}
					{else}{assign var="pop_type" value="specific"}
				{/if}
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>
				<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		{elseif $uitype eq 57}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $fromlink eq 'qcreate'}
					<input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact("false","general",document.QcEditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{else}
					<input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectContact("false","general",document.EditView)' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{/if}
			</td>
		
		{elseif $uitype eq 58}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="campaignname" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Campaigns&action=Popup&html=Popup_picker&popuptype=specific_campaign&form=EditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.campaignid.value=''; this.form.campaignname.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 80}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectSalesOrder();' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 78}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}" >&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectQuote()' align="absmiddle" style='cursor:hand;cursor:pointer' >&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 76}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='selectPotential()' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 17}
                <div {$class}>
                    <span class="alert-icon"><i {$image_class}></i></span>
                    <div class="notification-info" style="height:50px;">
                        <ul class="clearfix notification-meta">
                            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                        </ul>
                        <p>
                                    &nbsp;&nbsp;http://
                            <input style="width:74%;" class = 'detailedViewTextBox' type="text" tabindex="{$vt_tab}" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" onkeyup="validateUrl('{$fldname}');" value="{$fldvalue}">
                        </p>
                    </div>
                </div>

		{elseif $uitype eq 85}
            <td width="20%" class="dvtCellLabel" align=right>
                <font color="red">{$mandatory_field}</font>
                {$usefldlabel}
                {if $MASS_EDIT eq '1'}
                	<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >
                {/if}
            </td>
            <td width="30%" align=left class="dvtCellInfo">
				<img src="{'skype.gif'|@vtiger_imageurl:$THEME}" alt="Skype" title="Skype" LANGUAGE=javascript align="absmiddle"></img>
				<input class='detailedViewTextBox' type="text" tabindex="{$vt_tab}" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}">
            </td>

		{elseif $uitype eq 71 || $uitype eq 72}
			<div {$class} >
                            <span class="alert-icon"><i {$image_class}></i></span>
                            <div class="notification-info" style="height:50px;">
                                <ul class="clearfix notification-meta">
                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                                </ul>
                                <p>
                		
				{if $fldname eq "unit_price" && $fromlink neq 'qcreate'}
					<span id="multiple_currencies">
						<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'; updateUnitPrice('unit_price', '{$BASE_CURRENCY}');"  value="{$fldvalue}" style="width:60%;">
					{if $MASS_EDIT neq 1}
						&nbsp;<a href="javascript:void(0);" onclick="updateUnitPrice('unit_price', '{$BASE_CURRENCY}'); toggleShowHide('currency_class','multiple_currencies');">{$APP.LBL_MORE_CURRENCIES} &raquo;</a>
					{/if}
					</span>
					{if $MASS_EDIT neq 1}
					<div id="currency_class" class="multiCurrencyEditUI" width="350">
						<input type="hidden" name="base_currency" id="base_currency" value="{$BASE_CURRENCY}" />
						<input type="hidden" name="base_conversion_rate" id="base_currency" value="{$BASE_CURRENCY}" />
						<table width="100%" height="100%" class="small" cellpadding="5">
						<tr class="detailedViewHeader">
							<th colspan="4">
								<b>{$MOD.LBL_PRODUCT_PRICES}</b>
							</th>
							<th align="right">
								<img border="0" style="cursor: pointer;" onclick="toggleShowHide('multiple_currencies','currency_class');" src="{'close.gif'|@vtiger_imageurl:$THEME}"/>
							</th>
						</tr>
						<tr class="detailedViewHeader">
							<th>{$APP.LBL_CURRENCY}</th>
							<th>{$APP.LBL_PRICE}</th>
							<th>{$APP.LBL_CONVERSION_RATE}</th>
							<th>{$APP.LBL_RESET_PRICE}</th>							
							<th>{$APP.LBL_BASE_CURRENCY}</th>
						</tr>
						{foreach item=price key=count from=$PRICE_DETAILS}
							<tr>
								{if $price.check_value eq 1 || $price.is_basecurrency eq 1}
									{assign var=check_value value="checked"}
									{assign var=disable_value value=""}
								{else}
									{assign var=check_value value=""}
									{assign var=disable_value value="disabled=true"}
								{/if}
								
								{if $price.is_basecurrency eq 1}
									{assign var=base_cur_check value="checked"}
								{else}
									{assign var=base_cur_check value=""}
								{/if}
								
								{if $price.curname eq $BASE_CURRENCY}
									{assign var=call_js_update_func value="updateUnitPrice('$BASE_CURRENCY', 'unit_price');"}
								{else}
									{assign var=call_js_update_func value=""}
								{/if}
								
								<td align="right" class="dvtCellLabel">
									{$price.currencylabel|@getTranslatedCurrencyString} ({$price.currencysymbol})
									<input type="checkbox" name="cur_{$price.curid}_check" id="cur_{$price.curid}_check" class="small" onclick="fnenableDisable(this,'{$price.curid}'); updateCurrencyValue(this,'{$price.curname}','{$BASE_CURRENCY}','{$price.conversionrate}');" {$check_value}>
								</td>
								<td class="dvtCellInfo" align="left">
									<input {$disable_value} type="text" size="10" class="small" name="{$price.curname}" id="{$price.curname}" value="{$price.curvalue}" onBlur="{$call_js_update_func} fnpriceValidation('{$price.curname}');">
								</td>
								<td class="dvtCellInfo" align="left">
									<input disabled=true type="text" size="10" class="small" name="cur_conv_rate{$price.curid}" value="{$price.conversionrate}">
								</td>
								<td class="dvtCellInfo" align="center">
									<input {$disable_value} type="button" class="crmbutton small edit" id="cur_reset{$price.curid}"  onclick="updateCurrencyValue(this,'{$price.curname}','{$BASE_CURRENCY}','{$price.conversionrate}');" value="{$APP.LBL_RESET}"/>
								</td>
								<td class="dvtCellInfo">
									<input {$disable_value} type="radio" class="detailedViewTextBox" id="base_currency{$price.curid}" name="base_currency_input" value="{$price.curname}" {$base_cur_check} onchange="updateBaseCurrencyValue()" />
								</td>
							</tr>
						{/foreach}
						</table>
					</div>
					{/if}
				{else}
					<input name="{$fldname}" tabindex="{$vt_tab}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$fldvalue}">
				{/if}
			</p>         
                            </div>
                        </div> 	

		{elseif $uitype eq 56}
                    	<div {$class}>
                <span class="alert-icon"><i {$image_class}></i></span>
                <div class="notification-info" style="height:50px;">
                    <ul class="clearfix notification-meta">
                        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                    </ul>
                    <p>
                        {if $fldvalue eq 1}
					
                            <input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked>
			{else}	
                              <input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" >
                        {/if}
			</p>         
                </div>
            </div> 
		{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
			
				{foreach key=date_value item=time_value from=$fldvalue}
					{assign var=date_val value="$date_value"}
					{assign var=time_val value="$time_value"}
				{/foreach}
<script type="text/javascript" id='massedit_calendar_{$fldname}'>
					Calendar.setup ({ldelim}
						inputField : "jscal_field_{$fldname}", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
					{rdelim})
				</script>
                                <div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
 <input name="{$fldname}" tabindex="{$vt_tab}" id="jscal_field_{$fldname}" 
        type="text"  size="11" maxlength="10" 
        {$class} style="height:1px;border-style:solid;border-width:1px;" value="{$date_val}">

<img src="themes/teknema/images/btnL3Calendar.gif" id="jscal_trigger_{$fldname}">
				
				{if $uitype eq 6}
					<input name="time_start" tabindex="{$vt_tab}" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}
				
				{if $uitype eq 6 && $QCMODULE eq 'Event'}
					<input name="dateFormat" type="hidden" value="{$dateFormat}">
				{/if}
				{if $uitype eq 23 && ($QCMODULE eq 'Event' || $MODULE eq 'Timecontrol')}
					<input name="time_end" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}
				
				{foreach key=date_format item=date_str from=$secondvalue}
					{assign var=dateFormat value="$date_format"}
					{assign var=dateStr value="$date_str"}
				{/foreach}

				{if $uitype eq 5 || $uitype eq 23}
					<font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
 
{else}
					<font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
				{/if}
</p>         
    </div>
</div> 
				<script type="text/javascript" id='massedit_calendar_{$fldname}'>
					Calendar.setup ({ldelim}
						inputField : "jscal_field_{$fldname}", ifFormat : "%d-%m-%Y", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
					{rdelim})
				</script>



		{elseif $uitype eq 63}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="text" size="2" value="{$fldvalue}" tabindex="{$vt_tab}" >&nbsp;
				<select name="duration_minutes" tabindex="{$vt_tab}" class="small">
					{foreach key=labelval item=selectval from=$secondvalue}
						<option value="{$labelval}" {$selectval}>{$labelval}</option>
					{/foreach}
				</select>

		{elseif $uitype eq 68 || $uitype eq 66 || $uitype eq 62}
			<td width="20%" class="dvtCellLabel" align=right>
				{if $fromlink eq 'qcreate'}
                                       <select class="small" name="parent_type" onChange='document.QcEditView.parent_name.value=""; document.QcEditView.parent_id.value=""'>
                                {else}
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
				{/if}
					{section name=combo loop=$fldlabel}{$fldlabel[combo]}{$fldlabel_sel[combo]}
                                       {if $MODULE eq 'HelpDesk' && $fldname eq 'parent_id'}
                                       {if $fldlabel_combo[combo] eq 'Accounts'} 
                                        <option value="{$fldlabel_combo[combo]}" selected>{$fldlabel[combo]} </option>
                                       {else} 
                                       <option value="{$fldlabel_combo[combo]}">{$fldlabel[combo]} </option>{/if}
                                        {else}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
					{/if}
                                       {/section}
				</select>
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}			
			</td>
			     <td width="30%" align=left class="dvtCellInfo">
                                {if $MODULE eq 'HelpDesk' && $fldname eq 'parent_id'}
                                <input name="{$fldname}" type="hidden" value="{$accID}">
				<input name="parent_name" readonly id = "parentid" type="text" style="border:1px solid #bababa;" value="{$accName}">
				&nbsp;
                                {else}
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<input name="parent_name" readonly id = "parentid" type="text" style="border:1px solid #bababa;" value="{$fldvalue}">
				&nbsp;
                                 {/if}
				{if $fromlink eq 'qcreate'}
					<img src="{'select.gif'|@vtiger_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{else}
					<img src="{'select.gif'|@vtiger_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
					{/if}
			</td>

		{elseif $uitype eq 357}
			<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
			<td width="90%" colspan="3">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<textarea readonly name="parent_name" cols="70" rows="2">{$fldvalue}</textarea>&nbsp;
				<select name="parent_type" class="small">
					{foreach key=labelval item=selectval from=$fldlabel}
						<option value="{$labelval}" {$selectval}>{$labelval}</option>
					{/foreach}
				</select>
				&nbsp;
				{if $fromlink eq 'qcreate'}
					<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{else}
					<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
				{/if}
			</td>
		   <tr style="height:25px">
			<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>	
			<td width="30%" align=left class="dvtCellInfo">
				<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
			<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="">
			</td>
		   </tr>

		{elseif $uitype eq 59}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<input name="product_name" readonly type="text" value="{$fldvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>

		{elseif $uitype eq 55 || $uitype eq 255} 
			<td width="20%" class="dvtCellLabel" align=right>
			{if $MASS_EDIT eq '1' && $fldvalue neq ''}
				{$APP.Salutation}<input type="checkbox" name="salutationtype_mass_edit_check" id="salutationtype_mass_edit_check" class="small" ><br />
			{/if}
			{if $uitype eq 55}
				{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			{elseif $uitype eq 255}
				<font color="red">{$mandatory_field}</font>{$usefldlabel}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			{/if}
			</td>
			
			<td width="30%" align=left class="dvtCellInfo">
			{if $fldvalue neq ''}
			<select name="salutationtype" class="small">
				{foreach item=arr from=$fldvalue}
				<option value="{$arr[1]}" {$arr[2]}>
				{$arr[0]}
				</option>
				{/foreach}
			</select>
			{if $MASS_EDIT eq '1'}<br />{/if}
			{/if}
			<input type="text" name="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "{$secondvalue}" >
			</td>

		{elseif $uitype eq 22}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<textarea name="{$fldname}" cols="30" tabindex="{$vt_tab}" rows="2">{$fldvalue}</textarea>
			</td>

		{elseif $uitype eq 69}
			<div {$class}  style="height:100px;">
                        <span class="alert-icon"><i {$image_class}></i></span>
                        <div class="notification-info" style="height:50px;">
                            <ul class="clearfix notification-meta">
                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                            </ul>
                            <p>
				{if $MODULE eq 'Products'  }
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>
						<!--input type="hidden" name="file_1_hidden" value=""/-->
						{assign var=image_count value=0}
                                                
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						   {foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="Delete" type="button" class="crmbutton small delete" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
					   	   {assign var=image_count value=$smarty.foreach.image_loop.iteration}
					   	   {/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
						multi_selector.count = {$image_count}
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>
				{elseif  $MODULE eq 'Assets'}
                                 {php}
                                    global $adb;
 
$id1=$this->get_template_vars('ID');
                                    $sql1 = "SELECT `fotoreception`, `fotomagazzino`, `fotolaboratorio` FROM `vtiger_assets` WHERE `assetsid` ='$id1'";
                                    
                                    $result1 = $adb->query($sql1);
                                    $row = $adb->fetch_array($result1);
                                    $this->assign("foto1",$row["fotoreception"]);
                                    $this->assign("foto2",$row["fotomagazzino"]);
                                    $this->assign("foto3",$row["fotolaboratorio"]);
                                    
                                {/php}
                                   
                                        <input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>
                                                
						{if $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
						
							<div align="center">
                                                     {if $usefldlabel eq 'Foto Reception'}
                                                             {if $foto1 neq ''}
								<div name="replaceimage" id="replaceimage">[{$foto1}] <a href="javascript:;" onClick="delimageAssets1('{$ID}','{$foto1}')">Del</a></div>
                                                                {/if}
                                                                 {elseif $usefldlabel eq 'Foto Magazzino'}
                                                                   {if $foto2 neq ''}
                                                                <div id="replaceimage1">[{$foto2}] <a href="javascript:;" onClick="delimageAssets2('{$ID}','{$foto2}')">Del</a></div>
                                                               {/if}
                                                                  {elseif $usefldlabel eq 'Foto Laboratorio'}
                                                            {if $foto3 neq ''}
                                                                <div id="replaceimage2">[{$foto3}] <a href="javascript:;" onClick="delimageAssets3('{$ID}','{$foto3}')">Del</a></div>
							{/if}
                                                            {/if}
                                                                  </div>
					   	   
						{/if}
					</div>

					
                                    {else}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
					<input type="hidden" name="id" value=""/>
					{if $maindata[3].0.name != "" && $DUPLICATE neq 'true'}
						<div id="replaceimage">[{$maindata[3].0.orgname}] <a href="javascript:;" onClick="delimage({$ID})">Del</a></div>
					
                                   
                                        {/if}
				{/if}
			</p>         
                        </div>
                    </div> 

		{elseif $uitype eq 61}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  disabled >
				{/if}
			</td>

			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}"  type="file" value="{$secondvalue}" tabindex="{$vt_tab}" onchange="validateFilename(this)"/>
				<input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}"/>
				<input type="hidden" name="id" value=""/>{$fldvalue}
			</td>
		{elseif $uitype eq 156}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
				{if $fldvalue eq 'on'}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" checked>
						{else}
							<input name="{$fldname}" type="hidden" value="on">
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox" checked>
						{/if}	
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox">
						{else}
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox">
						{/if}	
					</td>
				{/if}
		{elseif $uitype eq 98}<!-- Role Selection Popup -->		
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			{if $thirdvalue eq 1}
				<input name="role_name" id="role_name" readonly class="txtBox" tabindex="{$vt_tab}" value="{$secondvalue}" type="text">&nbsp;
				<a href="javascript:openPopup();"><img src="{'select.gif'|@vtiger_imageurl:$THEME}" align="absmiddle" border="0"></a>
			{else}	
				<input name="role_name" id="role_name" tabindex="{$vt_tab}" class="txtBox" readonly value="{$secondvalue}" type="text">&nbsp;
			{/if}	
			<input name="user_role" id="user_role" value="{$fldvalue}" type="hidden">
			</td>
		{elseif $uitype eq 104}<!-- Mandatory Email Fields -->			
			 <td width=20% class="dvtCellLabel" align=right>
			<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			 </td>
    	     <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
			{elseif $uitype eq 115}<!-- for Status field Disabled for nonadmin -->
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   {if $secondvalue eq 1 && $CURRENT_USERID != $smarty.request.record}
			   	<select id="user_status" name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {else}
			   	<select id="user_status" disabled name="{$fldname}" class="small">
			   {/if} 
				{foreach item=arr from=$fldvalue}
                                        <option value="{$arr[1]}" {$arr[2]} >
                                                {$arr[0]}
                                        </option>
				{/foreach}
			   </select>
			</td>
			{elseif $uitype eq 105}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODE eq 'edit' && $IMAGENAME neq ''}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" /><div id="replaceimage">[{$IMAGENAME}]&nbsp;<a href="javascript:;" onClick="delUserImage({$ID})">Del</a></div>
					<br>{'LBL_IMG_FORMATS'|@getTranslatedString:$MODULE}
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
				{else}
					<input name="{$fldname}"  type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" /><br>{'LBL_IMG_FORMATS'|@getTranslatedString:$MODULE}
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
				{/if}
					<input type="hidden" name="id" value=""/>
					{$maindata[3].0.name}
			</td>
			{elseif $uitype eq 103}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" colspan="3" align=left class="dvtCellInfo">
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
			</td>	
			{elseif $uitype eq 101}<!-- for reportsto field USERS POPUP -->
				<td width="20%" class="dvtCellLabel" align=right>
			      <font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
	            </td>
				<td width="30%" align=left class="dvtCellInfo">
					<input readonly name='reports_to_name' class="small" type="text" value='{$fldvalue}' tabindex="{$vt_tab}" >
					<input name='reports_to_id' type="hidden" value='{$secondvalue}'>&nbsp;<input title="Change [Alt+C]" accessKey="C" type="button" class="small" value='{$UMOD.LBL_CHANGE}' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false&fromlink={$fromlink}&recordid={$ID}","test","width=640,height=603,resizable=0,scrollbars=0");'>
	            	&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.reports_to_id.value=''; this.form.reports_to_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
	            </td>
			{elseif $uitype eq 116 || $uitype eq 117}<!-- for currency in users details-->	
			<div {$class} >
                        <span class="alert-icon"><i {$image_class}></i></span>
                        <div class="notification-info" style="height:50px;">
                            <ul class="clearfix notification-meta">
                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                            </ul>
                            <p>
			   {if $secondvalue eq 1 || $uitype eq 117}
			   	<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {else}
			   	<select disabled name="{$fldname}" tabindex="{$vt_tab}" class="small">
			   {/if} 

				{foreach item=arr key=uivalueid from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						<option value="{$uivalueid}" {$value}>{$sel_value|@getTranslatedCurrencyString}</option>
						<!-- code added to pass Currency field value, if Disabled for nonadmin -->
						{if $value eq 'selected' && $secondvalue neq 1}
							{assign var="curr_stat" value="$uivalueid"}
						{/if}
						<!--code ends -->
					{/foreach}
				{/foreach}
			   </select>
			<!-- code added to pass Currency field value, if Disabled for nonadmin -->
			{if $curr_stat neq '' && $uitype neq 117}
				<input name="{$fldname}" type="hidden" value="{$curr_stat}">
			{/if}
			<!--code ends -->
			
                                    </p>         
                        </div>
                    </div> 

			{elseif $uitype eq 106}
			<td width=20% class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo">
				{if $MODE eq 'edit'}
				<input type="text" readonly name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				{else}
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				{/if}
			</td>


			{elseif $uitype eq 99}
				{if $MODE eq 'create'}
				<td width=20% class="dvtCellLabel" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width=30% align=left class="dvtCellInfo">
					<input type="password" name="{$fldname}" tabindex="{$vt_tab}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
				</td>
				{/if}

		{elseif $uitype eq 30}
			<td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				{assign var=check value=$secondvalue[0]}
				{assign var=yes_val value=$secondvalue[1]}
				{assign var=no_val value=$secondvalue[2]}

				<input type="radio" name="set_reminder" tabindex="{$vt_tab}" value="Yes" {$check}>&nbsp;{$yes_val}&nbsp;
				<input type="radio" name="set_reminder" value="No">&nbsp;{$no_val}&nbsp;

				{foreach item=val_arr from=$fldvalue}
					{assign var=start value="$val_arr[0]"}
					{assign var=end value="$val_arr[1]"}
					{assign var=sendname value="$val_arr[2]"}
					{assign var=disp_text value="$val_arr[3]"}
					{assign var=sel_val value="$val_arr[4]"}
					<select name="{$sendname}" class="small">
						{section name=reminder start=$start max=$end loop=$end step=1 }
							{if $smarty.section.reminder.index eq $sel_val}
								{assign var=sel_value value="SELECTED"}
							{else}
								{assign var=sel_value value=""}
							{/if}
							<OPTION VALUE="{$smarty.section.reminder.index}" "{$sel_value}">{$smarty.section.reminder.index}</OPTION>
						{/section}
					</select>
					&nbsp;{$disp_text}
				{/foreach}
			</td>

		{elseif $uitype eq 26}
		<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
			<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
				{foreach item=v key=k from=$fldvalue}	 
				<option value="{$k}">{$v}</option> 
				{/foreach}
			</select>
		</p>         
    </div>
</div> 

		{elseif $uitype eq 27}
		<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
	{if  $RETURN_MODULE eq 'Project' && $MODULE eq 'Documents'}
        <select class="small" name="{$fldname}" onchange="changeDldType((this.value=='I')? 'file': 'text');">
				{section name=combo loop=$fldlabel}
					<option value="{$fldlabel_combo[combo]}"  >{$fldlabel[combo]} </option>
				{/section}
	</select>
	{else}
       <select class="small" name="{$fldname}" onchange="changeDldType((this.value=='I')? 'file': 'text');">
            {section name=combo loop=$fldlabel}
                    <option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]} >{$fldlabel[combo]} </option>
            {/section}
       </select>
       {/if}

{literal}
			<script>
				function vtiger_{/literal}{$fldname}{literal}Init(){
					var d = document.getElementsByName('{/literal}{$fldname}{literal}')[0];
					var type = (d.value=='I')? 'file': 'text';

					changeDldType(type, true);
				}
				if(typeof window.onload =='function'){
					var oldOnLoad = window.onload;
					document.body.onload = function(){
						vtiger_{/literal}{$fldname}{literal}Init();
                                            	oldOnLoad();
					}
				}else{
					window.onload = function(){
						vtiger_{/literal}{$fldname}{literal}Init();
                                    }
				}
				
			</script>
                       {/literal}
		</p>         
    </div>
</div> 

		{elseif $uitype eq 28}
<div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>
           
        </ul>
        <p>
		<script type="text/javascript">
			function changeDldType(type, onInit){ldelim}
				var fieldname = '{$fldname}';
				if(!onInit){ldelim}
					var dh = getObj('{$fldname}_hidden');
					if(dh) dh.value = '';
				{rdelim}
				
				var v1 = document.getElementById(fieldname+'_E__');
				var v2 = document.getElementById(fieldname+'_I__');
				
				var text = v1.type =="text"? v1: v2;
				var file = v1.type =="file"? v1: v2;
				var filename = document.getElementById(fieldname+'_value');
				{literal}
				if(type == 'file'){
					// Avoid sending two form parameters with same key to server
					file.name = fieldname;
					text.name = '_' + fieldname;
					
					file.style.display = '';
					text.style.display = 'none';	
					text.value = '';
					filename.style.display = '';
				}else{
					// Avoid sending two form parameters with same key to server
					text.name = fieldname;
					file.name = '_' + fieldname;
					
					file.style.display = 'none';
					text.style.display = '';		
					file.value = '';
					filename.style.display = 'none';
					filename.innerHTML="";
				}
				{/literal}
			{rdelim}
		</script>
		<div>
			<input name="{$fldname}" id="{$fldname}_I__" type="file" value="{$secondvalue}" tabindex="{$vt_tab}" onchange="validateFilename(this)" style="display: none;"/>
			<input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}"/>
			<input type="hidden" name="id" value=""/>
			<input type="text" id="{$fldname}_E__" name="{$fldname}" {$class} style="height:2px;border-style:solid;border-width:1px;" value="{$secondvalue}" /><br>
			<span id="{$fldname}_value" style="display:none;">
				{if $secondvalue neq ''}
					[{$secondvalue}]
				{/if}
			</span>
		</div>	
            </p>         
            </div>
        </div> 
            {elseif $uitype eq 1022}
            <td width="20%" class="dvtCellLabel" align=right>
                {$fldlabel}
            </td>
            <td width="30%" align=left class="dvtCellInfo" >
             <textarea   style="width:300px;"  tabindex="{$vt_tab}" 
                    name="{$fldname}" id ="{$fldname}" value="{$fldvalue}"  
                    onchange="if(this.value=='') document.getElementById('selected_field').value='';">{$fldvalue}</textarea>
             <input type="hidden"  name="selected_field" id ="selected_field"   >
             <input type="hidden"  name="field_value" id ="field_value"   >
             <input type="hidden"  name="noval" id ="noval"   >
            </td>
            <script>{literal}
                     var value='';
                     jQuery(document).ready(function () {
                         jQuery("#{/literal}{$fldname}{literal}").kendoAutoComplete({
                            minLength: 1,
                            dataTextField:'name',
                            template: '${ data.str } ',
                            filter: "startswith",
                            separator:':',
                            select: function(e) {
                            var dataItem = this.dataItem(e.item.index());
                            var first = dataItem.first;
                            var second = dataItem.second;
                            //alert(first+' '+second);
                            if(first!=''){
                            document.getElementById('selected_field').value=first;
                            document.getElementById('field_value').value='';
                            var third = dataItem.third;
                            document.getElementById('noval').value=third;
                            }
                            else if(second!=''){                            
                            document.getElementById('field_value').value=second;
                            document.getElementById('selected_field').value='';
                            }
                            
                            },
                            dataSource:{
                                serverFiltering:true,
                                transport:{
                                    read:{
                                        url:'index.php?module=Project&action=ProjectAjax&file=uitype_evo_multitag',
                                        serverPaging:true,
                                        pageSize:20,
                                        contentType:'application/json; charset=utf-8',
                                        type:'GET',
                                        dataType:'json',
                                    },
                                parameterMap: function(options, operation) {
                                    return {
                                        StartsWith: options.filter.filters[0].value,
                                        selected_field: document.getElementById('selected_field').value,
                                        field_value: document.getElementById('field_value').value,
                                        noval: document.getElementById('noval').value
                                    }
                                   }
                                }
                            }
                            })
                });
             {/literal}                           
             </script>	
             <style scoped>
                  {literal} 
                .k-autocomplete
                {
                    width: 250px;
                    height: 150px;
                    vertical-align: middle;
                }
                #{/literal}{$fldname}{literal}{height: 140px;}
                 {/literal} 
            </style>
		{elseif $uitype eq 83} <!-- Handle the Tax in Inventory -->
			<div {$class} >
                            <span class="alert-icon"><i {$image_class}></i></span>
                            <div class="notification-info" style="height:50px;">
                                <ul class="clearfix notification-meta">
                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$usefldlabel} </li>

                                </ul>
                                <p>
                		{foreach item=tax key=count from=$TAX_DETAILS}
				{if $tax.check_value eq 1}
					{assign var=check_value value="checked"}
					{assign var=show_value value="visible"}
				{else}
					{assign var=check_value value=""}
					{assign var=show_value value="hidden"}
				{/if}
				{$tax.taxlabel} {$APP.COVERED_PERCENTAGE} &nbsp;
					<input type="checkbox" name="{$tax.check_name}" id="{$tax.check_name}" class="small" onclick="fnshowHide(this,'{$tax.taxname}')" {$check_value}>
				 &nbsp;<input type="text" class="detailedViewTextBox" name="{$tax.taxname}" id="{$tax.taxname}" value="{$tax.percentage}" style="visibility:{$show_value};" onBlur="fntaxValidation('{$tax.taxname}')">
				
			{/foreach}
       
                                    </p>         
                                </div>
                            </div> 
{elseif $MODULE eq 'Setting' && $header eq $MOD.LBL_SETTING_INFORMATION }
<tr> 
                      <td align="right" class="dvtCellLabel" style="border:0px solid red;">
		       Scadenze</td>
                         <td width="30%" align=left class="dvtCellInfo">
                         <select id="projectMilestone" name="projectMilestone" class="small">{$pMilestoneFields}</select>
                      </td>
	              </tr>
 {elseif $MODULE eq 'Timecontrol' && $header eq $MOD.LBL_TIMECONTROL_INFORMATION} 
                         <td align="right" class="dvtCellLabel" style="border:0px solid red;">
				Block</td>
                         <td width="30%" align=left class="dvtCellInfo">
                         <select id="block" name="block" class="small">{$opt}</select></td>
			<tr> 
                      <td align="right" class="dvtCellLabel" style="border:0px solid red;">
		            {$MOD.ProjectMilestones}</td>
                         <td width="30%" align=left class="dvtCellInfo">
                         <select id="projectMilestone" name="projectMilestone" class="small">{$pMilestoneFields}</select>
                      </td>
	              </tr>

                         {elseif $MODULE eq 'Preguntas' && $header eq $MOD.LBL_PREGUNTAS_INFORMATION}
                         <td align="right" class="dvtCellLabel" style="border:0px solid red;">
				Tipo domanda</td>
                         <td width="30%" align=left class="dvtCellInfo">
                                <select multiple id="tipodomanda" name="tipodomanda[]" class="small">{$tipo}</select></td>

		{/if}    
 {*<!-- //NABLACOM BEGIN ADVANCED UI WITH VIEW ONLY FIELDS	 -->*}
 {/if}    
