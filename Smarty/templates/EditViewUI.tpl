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
		{if isset($maindata[0][0])}
			{assign var="uitype" value=$maindata[0][0]}
		{else}
			{assign var="uitype" value=''}
		{/if}
		{if isset($maindata[1][0])}
			{assign var="fldlabel" value=$maindata[1][0]}
		{else}
			{assign var="fldlabel" value=''}
		{/if}
		{if isset($maindata[1][1])}
			{assign var="fldlabel_sel" value=$maindata[1][1]}
		{else}
			{assign var="fldlabel_sel" value=''}
		{/if}
		{if isset($maindata[1][2])}
			{assign var="fldlabel_combo" value=$maindata[1][2]}
		{else}
			{assign var="fldlabel_combo" value=''}
		{/if}
		{if isset($maindata[1][3])}
			{assign var="fldlabel_other" value=$maindata[1][3]}
		{else}
			{assign var="fldlabel_other" value=''}
		{/if}
		{if isset($maindata[2][0])}
			{assign var="fldname" value=$maindata[2][0]}
		{else}
			{assign var="fldname" value=''}
		{/if}
		{if isset($maindata[3][0])}
			{assign var="fldvalue" value=$maindata[3][0]}
		{else}
			{assign var="fldvalue" value=''}
		{/if}
		{if isset($maindata[3][1])}
			{assign var="secondvalue" value=$maindata[3][1]}
		{else}
			{assign var="secondvalue" value=''}
		{/if}
		{if isset($maindata[3][2])}
			{assign var="thirdvalue" value=$maindata[3][2]}
		{else}
			{assign var="thirdvalue" value=''}
		{/if}
		{if isset($maindata[4])}
			{assign var="typeofdata" value=$maindata[4]}
		{else}
			{assign var="typeofdata" value=''}
		{/if}
		{if isset($maindata[5][0])}
			{assign var="vt_tab" value=$maindata[5][0]}
		{else}
			{assign var="vt_tab" value=''}
		{/if}
		{if $typeofdata eq 'M'}
			{assign var="mandatory_field" value="*"}
		{else}
			{assign var="mandatory_field" value=""}
		{/if}

		{* vtlib customization: Help information for the fields *}
		{assign var="usefldlabel" value=$fldlabel}
		{assign var="fldhelplink" value=""}
		{if isset($FIELDHELPINFO) && isset($FIELDHELPINFO.$fldname)}
			{assign var="fldhelplinkimg" value='help_icon.gif'|@vtiger_imageurl:$THEME}
			{assign var="fldhelplink" value="<img style='cursor:pointer' onclick='vtlib_field_help_show(this, \"$fldname\");' border=0 src='$fldhelplinkimg'>"}
			{if $uitype neq '10'}
				{assign var="usefldlabel" value="$fldlabel $fldhelplink"}
			{/if}
		{/if}

		{if $uitype eq '10'}
			<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
			<font color="red">{$mandatory_field}</font>
			{$fldlabel.displaylabel}

			{assign var="use_parentmodule" value=$fldlabel.options.0}
			{assign var=vtui10func value=$use_parentmodule|getvtlib_open_popup_window_function:$fldname:$MODULE}
			{if count($fldlabel.options) eq 1}
				<input type='hidden' class='small' name="{$fldname}_type" id="{$fldname}_type" value="{$use_parentmodule}">
			{else}
				<br>
				{if $fromlink eq 'qcreate'}
				<select id="{$fldname}_type" class="slds-select" style="width: 75%;" name="{$fldname}_type" onChange='document.QcEditView.{$fldname}_display.value=""; document.QcEditView.{$fldname}.value="";'>
				{else}
				<select id="{$fldname}_type" class="slds-select" style="width: 75%;" name="{$fldname}_type" onChange='document.EditView.{$fldname}_display.value=""; document.EditView.{$fldname}.value="";document.getElementById("qcform").innerHTML=""'>
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

			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$fldvalue.entityid}">
				<input id="{$fldname}_display" name="{$fldname}_display" readonly type="text" class="slds-input" value="{$fldvalue.displayvalue}">&nbsp;
				<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" tabindex="{$vt_tab}"
alt="{'LBL_SELECT'|@getTranslatedString}" title="{'LBL_SELECT'|@getTranslatedString}" onclick='return {$vtui10func}("{$fromlink}","{$fldname}","{$MODULE}","{if isset($ID)}{$ID}{/if}");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;
				<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}"

alt="{'LBL_CLEAR'|@getTranslatedString}" title="{'LBL_CLEAR'|@getTranslatedString}" onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
				{if ( isset($maindata['extendedfieldinfo']) && isset($maindata['extendedfieldinfo']['searchfields']) )}
					<div id="listbox-unique-id" role="listbox" class="">
						<ul class="slds-listbox slds-listbox_vertical slds-dropdown slds-dropdown_fluid relation-autocomplete__target" style="opacity: 0; width: 100%; list-style-type: none; width: 90%; left: 0; transform: translateX(0); max-width: none;" role="presentation"></ul>
					</div>
				{/if}
				</div>
			</td>

		{elseif $uitype eq 2}
			<td ng-show ="show_logic('{$fldname}')" width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width=30% align=left class="dvtCellInfo">
				<input type="text" name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" 
                                       value="{$fldvalue}" tabindex="{$vt_tab}" 
                                       class="slds-input" 
                                       ng-disabled ="!editable_logic('{$fldname}','{$usefldlabel}')" 
                                       ng-model="{$fldname}" ><br/>
			</td>
		{elseif $uitype eq 3 || $uitype eq 4}<!-- Non Editable field, only configured value will be loaded -->
				<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}</td>
				<td width=30% align=left class="dvtCellInfo"><input readonly type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" {if $MODE eq 'edit'} value="{$fldvalue}" {else} value="{$MOD_SEQ_ID}" {/if} class="slds-input" ></td>
		{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7}
			<td ng-show ="show_logic('{$fldname}')" width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}</td>

			{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}
				<td ng-show ="show_logic('{$fldname}')" width=30% align=left class="dvtCellInfo">
					<input type="text" name="{$fldname}" tabindex="{$vt_tab}" id ="{$fldname}" value="{$fldvalue}" class="slds-input" {if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}sensex_info(){/if}">
					<span id="vtbusy_info" style="display:none;">
						<img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0"></span>
				</td>

			{else}
				<td ng-show ="show_logic('{$fldname}')" width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class="slds-input" ></td>
			{/if}
		{elseif $uitype eq 9}
			<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right><font color="red">{$mandatory_field}</font>{$usefldlabel} {$APP.COVERED_PERCENTAGE} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}</td>
			<td width=30% align=left class="dvtCellInfo"><input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class="slds-input" ></td>
		{elseif $uitype eq 19 || $uitype eq 20}
			<!-- In Add Comment we should not display anything -->
			{assign var="i18nAddComment" value='LBL_ADD_COMMENT'|@getTranslatedString:$MODULE}
			{if $fldlabel eq $i18nAddComment}
				{assign var=fldvalue value=""}
			{/if}
			<td ng-show ="show_logic('{$fldname}')" width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
					<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" colspan=3 class="dvtCellInfo">
				<textarea class="slds-textarea" tabindex="{$vt_tab}" name="{$fldname}" id="{$fldname}" cols="90" rows="8">{$fldvalue}</textarea>
				{assign var="i18nSolution" value='Solution'|@getTranslatedString:$MODULE}
				{if $fldlabel eq $i18nSolution}
				<input type="hidden" name="helpdesk_solution" id="helpdesk_solution" value='{$fldvalue}'>
				{/if}
				{if ($fldname eq 'notecontent') || (isset($maindata['extendedfieldinfo']) && isset($maindata['extendedfieldinfo']['RTE']) && $maindata['extendedfieldinfo']['RTE'] && vt_hasRTE())}
				<script>
					CKEDITOR.replace('{$fldname}',
					{ldelim}
						extraPlugins : 'uicolor',
						uiColor: '#dfdff1',
							on : {ldelim}
								instanceReady : function( ev ) {ldelim}
									 this.dataProcessor.writer.setRules( 'p',  {ldelim}
										indent : false,
										breakBeforeOpen : false,
										breakAfterOpen : false,
										breakBeforeClose : false,
										breakAfterClose : false
								{rdelim});
							{rdelim}
						{rdelim}
					{rdelim});
					var oCKeditor{$fldname} = CKEDITOR.instances[{$fldname}];
				</script>
				{/if}
			</td>
		{elseif $uitype eq 21 || $uitype eq 24}
			<td ng-show ="show_logic('{$fldname}')" width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
					<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="slds-input" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width=30% align=left class="dvtCellInfo">
				<textarea value="{$fldvalue}" name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-textarea" style="width:75%;" rows=2>{$fldvalue}</textarea>
			</td>
		{elseif ($uitype eq 15 && $fldname neq 'gvname' ) || $uitype eq 16  || $uitype eq '31' || $uitype eq '32' || $uitype eq '1613'}
			<td ng-show ="show_logic('{$fldname}')" width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Calendar'}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{else}
					<input name="{$fldname}" type="hidden"  value="{literal}{{{/literal}{$fldname}{literal}}}{/literal}" />
                    <select   tabindex="{$vt_tab}" class="slds-select" style="z-index:1000000;"
                            ng-disabled="!editable_logic('{$fldname}')"
                            ng-model="{$fldname}"
                            ng-options="s.value as s.text for s in {$fldname}_values | {$fldname}_filter :this">
				{/if}
			</td>
                {elseif $uitype eq 1614}
                <td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Calendar'}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:160px;">
				{else}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:280px;">
				{/if}
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
				{foreachelse}
					<option value=""></option>
					<option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
				{/foreach}
				</select>
			</td>
                {elseif $uitype eq 15}
                        <td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Calendar'}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{else}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{/if}
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
				{foreachelse}
					<option value=""></option>
					<option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
				{/foreach}
				</select>
			</td>
                {elseif $uitype eq 44}
                        <td width="20%" class="dvtCellLabel" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Calendar'}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{else}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{/if}
				{foreach item=arr from=$fldvalue}
					{if $arr[0] eq $APP.LBL_NOT_ACCESSIBLE}
					<option value="{$arr[0]}" {$arr[2]}>{$arr[0]}</option>
					{else}
					<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
					{/if}
				{foreachelse}
					<option value=""></option>
					<option value="" style='color: #777777' disabled>{$APP.LBL_NONE}</option>
				{/foreach}
				</select>
			</td>
		{elseif $uitype eq '1616'}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>
				{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="small" style="width:280px;">
				<option value="">{$APP.LBL_NONE}</option>
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[3]}>{$arr[2]}</option>
				{/foreach}
				</select>
			</td>
		{elseif $uitype eq 33 || $uitype eq 3313 || $uitype eq 3314 || $uitype eq 1024}
			<td ng-show ="show_logic('{$fldname}')" width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
				<select multiple="" name="{$fldname}[]" id="{$fldname}" size="4" tabindex="{$vt_tab}" class="small slds-select" style="height: auto;">
				{foreach item=arr from=$fldvalue}
					<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
				{/foreach}
				</select>
			</td>
                        
		{elseif $uitype eq 53}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
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

				{assign var=select_user value=''}
				{assign var=select_group value=''}
				{if $check eq 0}
					{assign var=select_user value='checked'}
					{assign var=style_user value='display:block'}
					{assign var=style_group value='display:none'}
				{else}
					{assign var=select_group value='checked'}
					{assign var=style_user value='display:none'}
					{assign var=style_group value='display:block'}
				{/if}

				<span class="slds-radio" style="margin-bottom: .1rem;">
					<input type="radio" tabindex="{$vt_tab}" id="radio-1" name="assigntype" {$select_user} value="U" onclick="toggleAssignType(this.value)" >
					<label class="slds-radio__label" for="radio-1">
						<span class="slds-radio--faux"></span>
						<span class="slds-form-element__label">{$APP.LBL_USER}</span>
					</label>
				</span>

				{if $secondvalue neq ''}
					<span class="slds-radio" style="margin-bottom: .1rem;">
						<input type="radio" id="radio-2" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">
						<label class="slds-radio__label" for="radio-2">
							<span class="slds-radio--faux"></span>
							<span class="slds-form-element__label">{$APP.LBL_GROUP}</span>
						</label>
					</span>
				{/if}

				<span id="assign_user" style="{$style_user}">
					<select name="{$fldname}" id="{$fldname}" class="slds-select">
						{foreach key=key_one item=arr from=$fldvalue}
							{foreach key=sel_value item=value from=$arr}
								<option value="{$key_one}" {$value}>{$sel_value}</option>
							{/foreach}
						{/foreach}
					</select>
				</span>

				{if $secondvalue neq ''}
					<span id="assign_team" style="{$style_group}">
						<select name="assigned_group_id" id="assigned_group_id" class="slds-select">
							{foreach key=key_one item=arr from=$secondvalue}
								{foreach key=sel_value item=value from=$arr}
									<option value="{$key_one}" {$value}>{$sel_value}</option>
								{/foreach}
							{/foreach}
						</select>
					</span>
				{/if}
			</td>
		{elseif $uitype eq 52 || $uitype eq 77}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $uitype eq 52}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{elseif $uitype eq 77}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{else}
					<select name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
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
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" class="slds-input" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype={$popuptype}&form=TasksEditView&form_submit=false&fromlink={$fromlink}&recordid={if isset($ID)}{$ID}{/if}","test{if $fromlink eq 'qcreate'}qc{/if}","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>
		{elseif $uitype eq 73}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input readonly name="account_name" class="slds-input" id="single_accountid" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='width:18px;cursor:pointer'>
				<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>

		{elseif $uitype eq 75 || $uitype eq 81}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				{if $uitype eq 81}
					{assign var="pop_type" value="specific_vendor_address"}
					{else}{assign var="pop_type" value="specific"}
				{/if}
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="vendor_name" id="vendor_name" class="slds-input" readonly type="text"  value="{$fldvalue}"><input name="{$fldname}" id="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0");' align="absmiddle" style='width:18px;cursor:pointer'>
				<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>
		{elseif $uitype eq 57}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $fromlink eq 'qcreate'}
					<input name="contact_name" readonly type="text" class="slds-input" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='selectContact("false","general",document.QcEditView)' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{else}
					<input name="contact_name" readonly type="text" class="slds-input" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='selectContact("false","general",document.EditView)' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{/if}
			</td>

		{elseif $uitype eq 80}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="salesorder_name" readonly type="text" class="slds-input" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='selectSalesOrder();' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>

		{elseif $uitype eq 78}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small">{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="quote_name" readonly type="text" class="slds-input" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}" >&nbsp;<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='selectQuote()' align="absmiddle" style='width:18px;cursor:pointer' >&nbsp;<input type="image" tabindex="{$vt_tab}" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>

		{elseif $uitype eq 76}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="potential_name" readonly type="text" class="slds-input" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='selectPotential()' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>

		{elseif $uitype eq 17}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			<input class = "slds-input" type="text" tabindex="{$vt_tab}" name="{$fldname}"  size="27"  onkeyup="validateUrl('{$fldname}');" value="{$fldvalue}">
			</td>

		{elseif $uitype eq 85}
            <td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
                <font color="red">{$mandatory_field}</font>
                {$usefldlabel}
                {if $MASS_EDIT eq '1'}
                	<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >
                {/if}
            </td>
            <td width="30%" align=left class="dvtCellInfo">
				<img src="{'skype.gif'|@vtiger_imageurl:$THEME}" alt="Skype" title="Skype" align="absmiddle"></img>
				<input class="slds-input" type="text" tabindex="{$vt_tab}" name="{$fldname}"  size="27"  value="{$fldvalue}">
            </td>

		{elseif $uitype eq 71 || $uitype eq 72}
			<td ng-show ="show_logic('{$fldname}')" width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
				{if $fldname eq "unit_price"}
					<span id="multiple_currencies">
						<input name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" type="text" class="slds-input" updateUnitPrice('unit_price', '{$BASE_CURRENCY}');"  value="{$fldvalue}" style="width:60%;">
					{if $MASS_EDIT neq 1}
						<br/><a href="javascript:void(0);" onclick="updateUnitPrice('unit_price', '{$BASE_CURRENCY}'); toggleShowHide('currency_class','multiple_currencies');">{$APP.LBL_MORE_CURRENCIES} &raquo;</a>
					{/if}
					</span>
					{if $MASS_EDIT neq 1}
					<div id="currency_class" class="multiCurrencyEditUI">
						<input type="hidden" name="base_currency" id="base_currency" value="{$BASE_CURRENCY}" />
						<input type="hidden" name="base_conversion_rate" id="base_currency" value="{$BASE_CURRENCY}" />
						<table width="100%" height="100%" class="small" cellpadding="5">
						<tr class="detailedViewHeader">
							<th colspan="4">
								<b>{'LBL_PRODUCT_PRICES'|@getTranslatedString:'Products'}</b>
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
									<input {$disable_value} type="text" size="10" class="slds-input" name="{$price.curname}" id="{$price.curname}" value="{$price.curvalue}" onBlur="{$call_js_update_func} fnpriceValidation('{$price.curname}');">
								</td>
								<td class="dvtCellInfo" align="left">
									<input disabled=true type="text" size="10" class="slds-input" name="cur_conv_rate{$price.curid}" value="{$price.conversionrate}">
								</td>
								<td class="dvtCellInfo" align="center">
									<input {$disable_value} type="button" class="slds-button slds-button--small slds-button_success" id="cur_reset{$price.curid}"  onclick="updateCurrencyValue(this,'{$price.curname}','{$BASE_CURRENCY}','{$price.conversionrate}');" value="{$APP.LBL_RESET}"/>
								</td>
								<td class="dvtCellInfo">
									<input {$disable_value} type="radio" id="base_currency{$price.curid}" name="base_currency_input" value="{$price.curname}" {$base_cur_check} onchange="updateBaseCurrencyValue()" />
								</td>
							</tr>
						{/foreach}
						</table>
					</div>
					{/if}
				{else}
					<input name="{$fldname}" tabindex="{$vt_tab}" type="text" class="slds-input"   value="{$fldvalue}">
				{/if}
			</td>

		{elseif $uitype eq 56}
			<td ng-show ="show_logic('{$fldname}')" width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>

			{if $fldname eq 'notime' && $ACTIVITY_MODE eq 'Events'}
				{if $fldvalue eq 1}
					<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" onclick="toggleTime()" checked>
					</td>
				{else}
					<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" onclick="toggleTime()" >
					</td>
				{/if}
			<!-- For Portal Information we need a hidden field existing_portal with the current portal value -->
			{elseif $fldname eq 'portal'}
				<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
					<input type="hidden" name="existing_portal" value="{$fldvalue}">
					<input name="{$fldname}" id="{$fldname}" type="checkbox" tabindex="{$vt_tab}" {if $fldvalue eq 1}checked{/if}>
				</td>
			{else}
				{if $fldvalue eq 1}
					<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" ng-model="{$fldname}" checked>
					</td>
				{elseif $fldname eq 'filestatus'&& $MODE eq 'create'}
					<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" type="checkbox" tabindex="{$vt_tab}" checked>
					</td>
				{else}
					<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
						<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" ng-model="{$fldname}" {if $USER_MODE eq 'create'}checked{/if}>
					</td>
				{/if}
			{/if}
		{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
			<td ng-show ="show_logic('{$fldname}')" width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td ng-show ="show_logic('{$fldname}')" width="30%" align=left class="dvtCellInfo">
				{foreach key=date_value item=time_value from=$fldvalue}
					{assign var=date_val value="$date_value"}
					{assign var=time_val value="$time_value"}
				{/foreach}

				<input name="{$fldname}" tabindex="{$vt_tab}" id="jscal_field_{$fldname}" type="text" class="slds-input" size="11" maxlength="10" value="{$date_val}">
				<img src="{'btnL3Calendar.gif'|@vtiger_imageurl:$THEME}" id="jscal_trigger_{$fldname}" style="width:18px;vertical-align: inherit;">

				{if $uitype eq 6}
					<input name="time_start" tabindex="{$vt_tab}"  size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}

				{if $uitype eq 6 && isset($QCMODULE) && $QCMODULE eq 'Event'}
					<input name="dateFormat" type="hidden" value="{$dateFormat}">
				{/if}
				{if $uitype eq 23  && isset($QCMODULE) && $QCMODULE eq 'Event'}
					<input name="time_end"  size="5" maxlength="5" type="text" value="{$time_val}">
				{/if}

				{foreach key=date_format item=date_str from=$secondvalue}
					{assign var=dateFormat value="$date_format"}
					{assign var=dateStr value="$date_str"}
				{/foreach}

				{if $uitype eq 5 || $uitype eq 23}
					<br>&nbsp;&nbsp;&nbsp;<font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
				{else}
					<br>&nbsp;&nbsp;&nbsp;<font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
				{/if}

				<script type="text/javascript" id='massedit_calendar_{$fldname}'>
					Calendar.setup ({ldelim}
						inputField : "jscal_field_{$fldname}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
					{rdelim})
				</script>
			</td>
		{elseif $uitype eq 50}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{foreach key=date_value item=date12_value from=$fldvalue}
					{assign var=date_val value="$date_value"}
					{assign var=date12_val value="$date12_value"}
				{/foreach}
				<input name="{$fldname}" tabindex="{$vt_tab}" class="slds-input" id="jscal_field_{$fldname}" type="text"  size="16" maxlength="16" value="{$date_val}">
				<img src="{'btnL3Calendar.gif'|@vtiger_imageurl:$THEME}" id="jscal_trigger_{$fldname}" style="width:18px;vertical-align: inherit;">

				{foreach key=date_format item=date_str from=$secondvalue}
					{assign var=dateFormat value="$date_format"}
					{assign var=dateStr value="$date_str"}
				{/foreach}

				<br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})&nbsp;<span id="timefmt_{$fldname}">{if $userFormat neq "24"}{$fieldFormat}{/if}</span></em></font>

				<script type="text/javascript" id='massedit_calendar_{$fldname}'>
					Calendar.setup ({ldelim}
						inputField : "jscal_field_{$fldname}", ifFormat : "{$dateFormat}", inputTimeFormat : "{$fieldFormat}",
						{if $userFormat neq "24"}displayArea : "timefmt_{$fldname}", daFormat : "%p",{/if}
						showsTime : true, timeFormat : "{$userFormat}",
						button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
					{rdelim});
				</script>
			</td>

		{elseif $uitype eq 63}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
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
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>
				{if $fromlink eq 'qcreate'}
					<select class="small" name="parent_type" onChange='document.QcEditView.parent_name.value=""; document.QcEditView.parent_id.value=""'>
				{else}
					<select class="small" name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
				{/if}
					{section name=combo loop=$fldlabel}
						<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]} </option>
					{/section}
				</select>
				{if $MASS_EDIT eq '1'}<input type="checkbox" name="parent_id_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<input name="parent_name" readonly id="parentid" type="text" class="slds-input" value="{$fldvalue}">
				&nbsp;
				{if $fromlink eq 'qcreate'}
					<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{else}
					<img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" tabindex="{$vt_tab}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{/if}
			</td>

		{elseif $uitype eq 357}
			<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
			<td width="90%" colspan="3">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<textarea readonly class="slds-textarea" name="parent_name" cols="70" rows="2" >{$fldvalue}</textarea>&nbsp;
				<select name="parent_type" class="slds-select">
					{foreach key=labelval item=selectval from=$fldlabel}
						<option value="{$labelval}" {$selectval}>{$labelval}</option>
					{/foreach}
				</select>
				&nbsp;
				{if $fromlink eq 'qcreate'}
					<img tabindex="{$vt_tab}" src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module="+ document.QcEditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{else}
					<img tabindex="{$vt_tab}" src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				{/if}
			</td>
		   <tr style="height:25px">
			<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="ccmail" type="text" class="slds-input"   value="">
			</td>
			<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="bccmail" type="text" class="slds-input"   value="">
			</td>
		   </tr>

		{elseif $uitype eq 59}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<input name="product_name" readonly class="slds-input" type="text" value="{$fldvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink={$fromlink}","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='width:18px;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
			</td>
		{elseif $uitype eq 55 || $uitype eq 255}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
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
			<select name="salutationtype" class="slds-select">
				{foreach item=arr from=$fldvalue}
				<option value="{$arr[1]}" {$arr[2]}>
				{$arr[0]}
				</option>
				{/foreach}
			</select><br/>
			{if $MASS_EDIT eq '1'}<br />{/if}
			{/if}

			{if (isset($maindata['extendedfieldinfo']) && isset($maindata['extendedfieldinfo']['searchfields']))}
				{assign var="autocomp" value=$maindata['extendedfieldinfo'] }
				<div style="position: relative;">
				<input
					type="text"
					name="{$fldname}"
					id="{$fldname}"
					tabindex="{$vt_tab}"
					value="{$secondvalue}"
					tabindex="{$vt_tab}"
					autocomplete="off"
					class="autocomplete-input detailedViewTextBox"
					data-autocomp='{$maindata["extendedfieldinfo"]|@json_encode}' />
					<div id="listbox-unique-id" role="listbox" class="">
						<ul class="slds-listbox slds-listbox_vertical slds-dropdown slds-dropdown_fluid relation-autocomplete__target" style="opacity: 0; width: 100%; list-style-type: none; width: 90%; left: 0; transform: translateX(0); max-width: none;" role="presentation"></ul>
					</div>
				</div>
			{else}
				<input type="text" name="{$fldname}" id="{$fldname}" tabindex="{$vt_tab}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" style="width:58%;" value= "{$secondvalue}" >
			{/if}
			</td>

		{elseif $uitype eq 22}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				<textarea name="{$fldname}" cols="30" class="slds-textarea" tabindex="{$vt_tab}" rows="2" style="width:75%;">{$fldvalue}</textarea>
			</td>
		{elseif $uitype eq 14}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {"LBL_TIMEFIELD"|@getTranslatedString}{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=10% align=left class="dvtCellInfo">
				<input type="text" tabindex="{$vt_tab}" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class="slds-input" >
			</td>
		{elseif $uitype eq '69m'}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>
			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				{if $MODULE eq 'Products'}
					<input name="del_file_list" type="hidden" value="">
					<div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">
						<span id="limitmsg" style= "color:red;"> {'LBL_MAX_SIZE'|@getTranslatedString:$MODULE} {$UPLOADSIZE}{'LBL_FILESIZEIN_MB'|@getTranslatedString:$MODULE}, {$APP.Files_Maximum}{$Product_Maximum_Number_Images}</span>
						<input id="my_file_element" type="file" name="file_1" tabindex="{$vt_tab}"  onchange="validateFilename(this)"/>
						<!--input type="hidden" name="file_1_hidden" value=""/-->
						{assign var=image_count value=0}
						{if isset($maindata[3].0.name) && $maindata[3].0.name neq '' && $DUPLICATE neq 'true'}
							{foreach name=image_loop key=num item=image_details from=$maindata[3]}
							<div align="center">
								<img src="{$image_details.path}{$image_details.name}" height="50">&nbsp;&nbsp;[{$image_details.orgname}]<input id="file_{$num}" value="{'LBL_DELETE_BUTTON'|@getTranslatedString}" type="button" class="slds-button slds-button--small slds-button--destructive" onclick='this.parentNode.parentNode.removeChild(this.parentNode);delRowEmt("{$image_details.orgname}")'>
							</div>
							{assign var=image_count value=$smarty.foreach.image_loop.iteration}
							{/foreach}
						{/if}
					</div>

					<script>
						{*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
						var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), {$Product_Maximum_Number_Images} );
						multi_selector.count = {$image_count};
						{*<!-- Pass in the file element -->*}
						multi_selector.addElement( document.getElementById( 'my_file_element' ) );
					</script>
				{/if}
			</td>
		{elseif $uitype eq 69}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel}
				{if $MASS_EDIT eq '1'}
					<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  >
				{/if}
			</td>
			<td colspan="1" width="30%" align=left class="dvtCellInfo">
				<div style="display: flex;flex-direction: row; width:100%">
				<div width="80%">
				<span id="limitmsg" style= "color:red;"> {'LBL_MAX_SIZE'|@getTranslatedString:$MODULE} {$UPLOADSIZE}{'LBL_FILESIZEIN_MB'|@getTranslatedString:$MODULE}<br /></span>
				{if isset($maindata[3].0.name) && $maindata[3].0.name != ''}
					{assign var=imagevalueexists value=true}
				{else}
					{assign var=imagevalueexists value=false}
				{/if}
				<input name="{$fldname}" id="{$fldname}" type="file" value="{if $imagevalueexists}{$maindata[3].0.name}{/if}" tabindex="{$vt_tab}" onchange="validateFilename(this);" />
				<input name="{$fldname}_hidden" id="{$fldname}_hidden" type="hidden" value="{if $imagevalueexists}{$maindata[3].0.name}{/if}" />
				{if $imagevalueexists}
					<div id="{$fldname}_replaceimage">[{$maindata[3].0.orgname}] <input id="{$fldname}_attach" value="{'LBL_DELETE_BUTTON'|@getTranslatedString}" type="button" class="slds-button slds-button--small slds-button--destructive" onclick='delimage({$ID},"{$fldname}","{$maindata[3].0.orgname}");'></div>
				{/if}
				<div id="displaySize"></div>
				</div>
				<div style="width:50px;height:50px;overflow: hidden;border:1px solid grey;">
					<canvas id="{$fldname}_canvas" tabindex="{$vt_tab}1}"></canvas>
					<input name="{$fldname}_canvas_image" id="{$fldname}_canvas_image" type="hidden" value="" />
					<input name="{$fldname}_canvas_image_set" id="{$fldname}_canvas_image_set" type="hidden" value="0" />
					<script>var {$fldname}_CLIPBOARD = new CLIPBOARD_CLASS("{$fldname}_canvas", true);</script>
				</div>
				</div>
			</td>

		{elseif $uitype eq 61}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
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
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
				{if $fldvalue eq 'on'}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && isset($smarty.request.record) && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox" checked>
						{else}
							<input name="{$fldname}" type="hidden" value="on">
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox" checked>
						{/if}
					</td>
				{else}
					<td width="30%" align=left class="dvtCellInfo">
						{if ($secondvalue eq 1 && isset($smarty.request.record) && $CURRENT_USERID != $smarty.request.record) || ($MODE == 'create')}
							<input name="{$fldname}" tabindex="{$vt_tab}" type="checkbox">
						{else}
							<input name="{$fldname}" disabled tabindex="{$vt_tab}" type="checkbox">
						{/if}
					</td>
				{/if}
		{elseif $uitype eq 98}<!-- Role Selection Popup -->
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			{if $thirdvalue eq 1}
				<input name="role_name" id="role_name" readonly class="slds-input" tabindex="{$vt_tab}" value="{$secondvalue}" type="text">&nbsp;
				<a href="javascript:openPopup();"><img src="{'btnL3Add.gif'|@vtiger_imageurl:$THEME}" align="absmiddle" border="0"></a>
			{else}
				<input name="role_name" id="role_name" tabindex="{$vt_tab}" class="slds-input" readonly value="{$secondvalue}" type="text">&nbsp;
			{/if}
			<input name="user_role" id="user_role" value="{$fldvalue}" type="hidden">
			</td>
		{elseif $uitype eq 104}<!-- Mandatory Email Fields -->
			<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
			<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class="slds-input" ></td>
			{elseif $uitype eq 115}<!-- for Status field Disabled for nonadmin -->
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   {if $secondvalue eq 1 && isset($smarty.request.record) && $CURRENT_USERID != $smarty.request.record}
			   	<select id="user_status" name="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
			   {else}
			   	<select id="user_status" disabled name="{$fldname}" class="slds-select">
			   {/if}
				{foreach item=arr from=$fldvalue}
	                <option value="{$arr[1]}" {$arr[2]} >
	                        {$arr[0]}
	                </option>
				{/foreach}
			   </select>
			</td>
			{elseif $uitype eq 105}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
				{if $MODE eq 'edit' && $IMAGENAME neq ''}
					<input name="{$fldname}" type="file" value="{$maindata[3].0.name}" tabindex="{$vt_tab}" onchange="validateFilename(this);" /><div id="replaceimage">[{$IMAGENAME}]&nbsp;<a href="javascript:;" onClick="delUserImage({$ID})">Del</a></div>
					<br>{'LBL_IMG_FORMATS'|@getTranslatedString:$MODULE}
					<input name="{$fldname}_hidden"  type="hidden" value="{$maindata[3].0.name}" />
				{else}
					<input name="{$fldname}" type="file" value="" tabindex="{$vt_tab}" onchange="validateFilename(this);" /><br>{'LBL_IMG_FORMATS'|@getTranslatedString:$MODULE}
					<input name="{$fldname}_hidden"  type="hidden" value="" />
				{/if}
					<div id="displaySize"></div>
					<input type="hidden" name="id" value=""/>
					{if isset($maindata[3].0.name) }
					{$maindata[3].0.name}
					{/if}
			</td>
			{elseif $uitype eq 103}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" colspan="3" align=left class="dvtCellInfo">
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class="slds-input" >
			</td>
			{elseif $uitype eq 101}<!-- for reportsto field USERS POPUP -->
				<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width="30%" align=left class="dvtCellInfo">
					<input id="{$fldname}_display" name="{$fldname}_display" readonly type="text"  value="{$fldvalue}" class="slds-input" />&nbsp;
					<input id="{$fldname}" name="{$fldname}" type="hidden" value="{$secondvalue}" id="{$fldname}" />
					&nbsp;<input title="{$APP.LBL_CHANGE_TITLE}" accessKey="C" type="button" class="slds-button slds-button--x-small slds-button--brand" value='{$APP.LBL_CHANGE}' style="padding-left: .5rem;padding-right: .5rem;" name="btn1" onclick='return window.open("index.php?module=Users&action=Popup&html=Popup_picker&form=vtlibPopupView&form_submit=false&fromlink={$fromlink}&recordid={if isset($ID)}{$ID}{/if}&forfield={$fldname}","test","width=640,height=603,resizable=0,scrollbars=0");'>
					&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" onClick="this.form.{$fldname}.value=''; this.form.{$fldname}_display.value=''; return false;" align="absmiddle" style='width:18px;cursor:pointer'>
				</td>
			{elseif $uitype eq 117}<!-- for currency in users details-->
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width="30%" align=left class="dvtCellInfo">
			   {if $secondvalue eq 1 || $uitype eq 117}
				<select name="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
			   {else}
				<select disabled name="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
			   {/if}
				{assign var="curr_stat" value=""}
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
			</td>
			{elseif $uitype eq 106}
			<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td width=30% align=left class="dvtCellInfo">
				{if $MODE eq 'edit'}
				<input type="text" readonly name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class="slds-input" >
				{else}
				<input type="text" name="{$fldname}" value="{$fldvalue}" tabindex="{$vt_tab}" class="slds-input" >
				{/if}
			</td>
			{elseif $uitype eq 99}
				{if $MODE eq 'create'}
				<td width=20% class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
					<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
				</td>
				<td width=30% align=left class="dvtCellInfo">
					<input type="password" name="{$fldname}" tabindex="{$vt_tab}" value="{$fldvalue}" class="slds-input" >
				</td>
				{/if}
		{elseif $uitype eq 30}
			<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
				<font color="red">{$mandatory_field}</font>{$usefldlabel} {if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
			</td>
			<td colspan="3" width="30%" align=left class="dvtCellInfo">
				{assign var=check value=$secondvalue[0]}
				{assign var=yes_val value=$secondvalue[1]}
				{assign var=no_val value=$secondvalue[2]}

				<input type="radio" name="set_reminder" tabindex="{$vt_tab}" value="Yes" {$check}>&nbsp;{$yes_val}&nbsp;
				<input type="radio" name="set_reminder" value="No">&nbsp;{$no_val}&nbsp;

				{foreach item=val_arr from=$fldvalue}
					{assign var=start value=$val_arr[0]}
					{assign var=end value=$val_arr[1]}
					{assign var=sendname value=$val_arr[2]}
					{assign var=disp_text value=$val_arr[3]}
					{assign var=sel_val value=$val_arr[4]}
					<select name="{$sendname}" class="slds-select">
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
		<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
		<font color="red">{$mandatory_field}</font>{$fldlabel}
		{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
		</td>
		<td width="30%" align=left class="dvtCellInfo">
			<select name="{$fldname}" tabindex="{$vt_tab}" class="slds-select">
				{foreach item=v key=k from=$fldvalue}
				<option value="{$k}">{$v}</option>
				{/foreach}
			</select>
		</td>
		{elseif $uitype eq 27}
		<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align="right" >
			<font color="red">{$mandatory_field}</font>{$fldlabel_other}&nbsp;
			{if $MASS_EDIT eq '1'}<input type="checkbox" name="{$fldname}" id="{$fldname}_mass_edit_check" class="small" >{/if}
        </td>
        <td width="30%" align=left class="dvtCellInfo">
			<select class="slds-select" name="{$fldname}" onchange="changeDldType((this.value=='I')? 'file': 'text');">
				{section name=combo loop=$fldlabel}
					<option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]} >{$fldlabel[combo]} </option>
				{/section}
			</select>
			<script>
				function vtiger_{$fldname}Init(){ldelim}
					var d = document.getElementsByName('{$fldname}')[0];
					var type = (d.value=='I')? 'file': 'text';

					changeDldType(type, true);
				{rdelim}
				if(typeof window.onload =='function'){ldelim}
					var oldOnLoad = window.onload;
					document.body.onload = function(){ldelim}
						vtiger_{$fldname}Init();
						oldOnLoad();
					{rdelim}
				{rdelim}else{ldelim}
					window.onload = function(){ldelim}
						vtiger_{$fldname}Init();
					{rdelim}
				{rdelim}

			</script>
		</td>
		{elseif $uitype eq 28}
		<td width="20%" class="dvtCellLabel{if $mandatory_field == '*'} mandatory_field_label{/if}" align=right>
			<font color="red">{$mandatory_field}</font>{$usefldlabel}
			{if $MASS_EDIT eq '1'}
				<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small"  disabled >
			{/if}
		</td>

		<td colspan="1" width="30%" align="left" class="dvtCellInfo">
		<script type="text/javascript">
			function changeDldType(type, onInit){ldelim}
				var fieldname = '{$fldname}';
				if(!onInit){ldelim}
					var dh = getObj('{$fldname}_hidden');
					if(dh) dh.value = '';
				{rdelim}

				var v1 = document.getElementById(fieldname+'_E__');
				var v2 = document.getElementById(fieldname+'_I__');
				var msg = document.getElementById('limitmsg');

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
					msg.style.display = '';
				}else{
					// Avoid sending two form parameters with same key to server
					text.name = fieldname;
					file.name = '_' + fieldname;

					file.style.display = 'none';
					text.style.display = '';
					file.value = '';
					filename.style.display = 'none';
					filename.innerHTML="";
					msg.style.display = 'none';
				}
				{/literal}
			{rdelim}
		</script>
		<div>
			<input name="{$fldname}" id="{$fldname}_I__" type="file" value="{$secondvalue}" tabindex="{$vt_tab}" onchange="validateFilename(this);validateFileSize(this,'{$UPLOAD_MAXSIZE}');" style="display: none;"/>
			<input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}"/>
			<input type="hidden" name="id" value=""/>
			<input type="text" id="{$fldname}_E__" name="{$fldname}" class="slds-input"  value="{$secondvalue}" /><br>
			<div id="displaySize"></div>
			<span id="{$fldname}_value" style="display:none;">
			{if $secondvalue neq ''}
				[{$secondvalue}]
			{/if}
			</span>
		</div>
		<span id="limitmsg" style= "color:red; display:none;"> {'LBL_MAX_SIZE'|@getTranslatedString:$MODULE} {$UPLOADSIZE}{'LBL_FILESIZEIN_MB'|@getTranslatedString:$MODULE}</span>
		</td>
            {elseif $uitype eq 1022}
		    <td width="20%" class="dvtCellLabel" align=right >
                    {$fldlabel}                                  
                    </td>
                    <td width="30%" align=left class="dvtCellInfo"  >
                    <div ng-controller="mainCtrl_{$fldname}"> 
                        <input name="{$fldname}" id="{$fldname}" value="{$fldvalue}" type="hidden"  >  
                        <input type="text" ng-model="{$fldname}" id="{$fldname}_display"
                               placeholder="Select..." 
                               typeahead="entity as entity.crmname for entity in loadTags($viewValue)" 
                               typeahead-on-select="functionClick($item, $model, $label)"
                               class="form-control" autocomplete="off" autocorrect="off" autocapitalize="off">  
                    </div>
                    </td>
                    <script>
                        {literal}
                        document.getElementById('{/literal}{$fldname}{literal}_display').setAttribute("autocomplete","off");
                        angular.module('demoApp')
                       .controller('mainCtrl_{/literal}{$fldname}{literal}', function ($scope, $http) {
                            $scope.new_entry=false;
                            $scope.loadTags = function(val) {
                                return $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoReferenceAutocomplete&field={/literal}{$fldname}{literal}&term='+val)
                                        .then(function(response){
                                            if(response.data.length===0)
                                                $scope.new_entry=true;
                                            else 
                                                $scope.new_entry=false;
                                          return response.data;
                                        });
                            };
                            $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoActualAutocomplete&field={/literal}{$fldname}{literal}&sel_values='+encodeURIComponent("{/literal}{$fldvalue}{literal}")).
                                    success(function(data, status) {
                                        $scope.{/literal}{$fldname}{literal}='{/literal}{$fldvalue}{literal}';
                            }); 
                            $scope.functionClick= function($item, $model, $label) {
                                document.getElementsByName('{/literal}{$fldname}{literal}').item(0).value=$item.crmname;
                                for(var c=0;c<$item.source_fld.length;c++){
                                    var dst_fld=$item.dest_fld[c];
                                    var src_fld=$item.source_fld[c];
                                    if(document.getElementsByName(dst_fld['fldname']).item(0)!=undefined){
                                        console.log(dst_fld);
                                        if(dst_fld.ui=='1021'){
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['id'];
                                            document.getElementById(dst_fld['fldname']+'_display').value=src_fld['val'];
                                        }
                                        else if(dst_fld.ui=='1022'){
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['val'];
                                            document.getElementById(dst_fld['fldname']+'_display').value=src_fld['val'];
                                        }
                                        else{
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['val'];
                                        }
                                    }
                                }
                            };
                                               
                        });
                          
                        {/literal}
                    </script>
                    </td>
                {elseif $uitype eq 1021 }
		    <td width="20%" class="dvtCellLabel" align=right >
                    {$fldlabel}                                  
                    </td>
                    <td width="30%" align=left class="dvtCellInfo"  >
                    <div ng-controller="mainCtrl_{$fldname}"> 
                        <input name="{$fldname}" id="{$fldname}" value="{$fldvalue}" type="hidden"  >  
                        <input type="text" ng-model="{$fldname}" id="{$fldname}_display"
                               placeholder="Select..." 
                               typeahead="entity as entity.crmname for entity in loadTags($viewValue)" 
                               typeahead-on-select="functionClick($item, $model, $label)"
                               class="form-control">   
                        <span ng-show="new_entry">Create New?</span>
                        <input type="checkbox" ng-show="new_entry" ng-model="create_new" ng-change="functionNewEntry()"/>
                    </div>
                    </td>
                    <script>
                        {literal}
                        angular.module('demoApp')
                       .controller('mainCtrl_{/literal}{$fldname}{literal}', function ($scope, $http) {
                            $scope.new_entry=false;
                            $scope.loadTags = function(val) {
                                return $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoReferenceAutocomplete&field={/literal}{$fldname}{literal}&term='+val)
                                        .then(function(response){
                                            if(response.data.length===0)
                                                $scope.new_entry=true;
                                            else 
                                                $scope.new_entry=false;
                                          return response.data;
                                        });
                            };
                            $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoActualAutocomplete&field={/literal}{$fldname}{literal}&sel_values='+encodeURIComponent("{/literal}{$fldvalue}{literal}")).
                                    success(function(data, status) {
                                        $scope.{/literal}{$fldname}{literal}=data[0];
                            }); 
                            $scope.functionClick= function($item, $model, $label) {
                                var val=$item.crmid.split('x');
                                document.getElementsByName('{/literal}{$fldname}{literal}').item(0).value=val[1];
                                for(var c=0;c<$item.source_fld.length;c++){
                                    var dst_fld=$item.dest_fld[c];
                                    var src_fld=$item.source_fld[c];
                                    if(document.getElementsByName(dst_fld['fldname']).item(0)!=undefined){
                                        console.log(dst_fld);
                                        if(dst_fld.ui=='1021'){
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['id'];
                                            document.getElementById(dst_fld['fldname']+'_display').value=src_fld['val'];
                                        } else if(dst_fld.ui=='1022'){
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['val'];
                                            document.getElementById(dst_fld['fldname']+'_display').value=src_fld['val'];
                                        }
                                        else{
                                            document.getElementsByName(dst_fld['fldname']).item(0).value=src_fld['val'];
                                        }
                                    }
                                }
                            };
                            $scope.functionNewEntry= function() {
                                var val=$scope.{/literal}{$fldname}{literal};
                                console.log($scope.create_new);
                                if($scope.new_entry==true){
                                $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=newEvoAutocomplete&field={/literal}{$fldname}{literal}&new_value='+encodeURIComponent(val)).
                                    success(function(data, status) {
                                        document.getElementsByName('{/literal}{$fldname}{literal}').item(0).value=data;
                                        $scope.new_entry=false;
                                        $scope.create_new=false;
                                    }); 
                                }
                            };
                                               
                        });
                          
                        {/literal}
                    </script>
                    </td>
                {elseif $uitype eq 1025}
		    <td width="20%" class="dvtCellLabel" align=right >
                    {$fldlabel}                                  
                    </td>
                    <td width="30%" align=left class="dvtCellInfo"  >
                    <div ng-controller="mainCtrl_{$fldname}">
                        <input name="{$fldname}" id="{$fldname}" value="{$fldvalue}" type="hidden"  >  
                        <tags-input ng-model="{$fldname}" 
                                    display-property="crmname" 
                                    on-tag-added="functionClick($tag)"
                                    on-tag-removed="functionClick($tag)"
                                    {if $uitype eq 1021}max-tags="1"{/if}
                                    placeholder="Select " >
                          <auto-complete source="loadTags($query)"
                                         min-length="2"
                                         max-results-to-show="20"
                                         ></auto-complete>
                        </tags-input>
                    </div>
                    </td>
                    <script>
                        {literal}
                        angular.module('demoApp')
                       .controller('mainCtrl_{/literal}{$fldname}{literal}', function ($scope, $http) {
                            $scope.{/literal}{$fldname}{literal}=[];
                            $scope.loadTags = function(query) {
                                return $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoReferenceAutocomplete&field={/literal}{$fldname}{literal}&term='+query).
                                    success(function(data, status) {
                                });
                            };
                            $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getEvoActualAutocomplete&field={/literal}{$fldname}{literal}&sel_values='+encodeURIComponent("{/literal}{$fldvalue}{literal}")).
                                    success(function(data, status) {console.log('cccccc');console.log(data);
                                        $scope.{/literal}{$fldname}{literal}=data;
                            });                            
                            $scope.functionClick= function(tag) {
                                var arr = new Array();
                                for(i=0;i<$scope.{/literal}{$fldname}{literal}.length;i++)
                                    {
                                       var val=$scope.{/literal}{$fldname}{literal}[i]['crmid'].split('x');
                                       arr[i]=val[1];
                                    }
                                document.getElementsByName('{/literal}{$fldname}{literal}').item(0).value=arr.join(',');
                            };
                                               
                        });
                          
                        {/literal}
                    </script>
                    </td>
                    {elseif $uitype eq 1040}
		    <td width="20%" class="dvtCellLabel" align=right >
                    {$fldlabel}                                  
                    </td>
                    <td width="30%" align=left class="dvtCellInfo"  >
                    <div ng-controller="mainCtrl_{$fldname}"> 
                        <input name="{$fldname}" id="{$fldname}" value="{$fldvalue}" type="hidden"  >  
                        <tags-input ng-model="{$fldname}" 
                                    display-property="crmname" 
                                    on-tag-added="functionClick($tag)"
                                    on-tag-removed="functionClick($tag)"
                                    {if $uitype eq 1021}max-tags="1"{/if}
                                    placeholder="Select " >
                          <auto-complete source="loadTags($query)"
                                         min-length="2"
                                         max-results-to-show="30"
                                         ></auto-complete>
                        </tags-input>
                    </div>
                    </td>
                    <script>
                        {literal}
                        angular.module('demoApp')
                       .controller('mainCtrl_{/literal}{$fldname}{literal}', function ($scope, $http) {
                            $scope.{/literal}{$fldname}{literal}=[];
                            $scope.loadTags = function(query) {
                                return $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getModuleAutocomplete&field={/literal}{$fldname}{literal}&term='+query).
                                    success(function(data, status) {
                                });
                            };
                            $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=ExecuteFunctions&functiontocall=getSavedModule&field={/literal}{$fldname}{literal}&sel_values='+encodeURIComponent("{/literal}{$fldvalue}{literal}")).
                                    success(function(data, status) {
                                        console.log(data);
                                        if(data.length==1 && data[0]['crmname']=='')data='';
                                        $scope.{/literal}{$fldname}{literal}=data;
                            });           
                            
                            $scope.functionClick= function(tag) {
                                var arr = new Array();
                                for(i=0;i<$scope.{/literal}{$fldname}{literal}.length;i++)
                                    {
                                       var val=$scope.{/literal}{$fldname}{literal}[i]['crmname'];
                                       console.log(val);
                                       arr[i]=val;
                                    };
                                    
                                document.getElementsByName('{/literal}{$fldname}{literal}').item(0).value=arr.join(',');
                            };
                                               
                        });
                          
                        {/literal}
                    </script>
                    </td>      
		{elseif $uitype eq 83} <!-- Handle the Tax in Inventory -->
			{foreach item=tax key=count from=$TAX_DETAILS}
				{if $tax.check_value eq 1}
					{assign var=check_value value="checked"}
					{assign var=show_value value="visible"}
				{else}
					{assign var=check_value value=""}
					{assign var=show_value value="hidden"}
				{/if}
				<td align="right" class="dvtCellLabel" style="border:0px solid red;">
					{$tax.taxlabel} {$APP.COVERED_PERCENTAGE}
					<input type="checkbox" name="{$tax.check_name}" id="{$tax.check_name}" class="small" onclick="fnshowHide(this,'{$tax.taxname}')" {$check_value}>
				</td>
				<td class="dvtCellInfo" align="left" style="border:0px solid red;">
					<input type="text" class="slds-input" name="{$tax.taxname}" id="{$tax.taxname}" value="{$tax.percentage}" style="visibility:{$show_value};" onBlur="fntaxValidation('{$tax.taxname}')">
				</td>
			   </tr>
			{/foreach}

			<td colspan="2" class="dvtCellInfo">&nbsp;</td>
		{else}
			{* just show field on screen *}
			<td width=20% class="dvtCellLabel" align=right>{$fldlabel}</td>
			<td width=30% align=left class="dvtCellInfo">
				{if $fldname neq ''}<input type="hidden" name="{$fldname}" id="{$fldname}" value="{$fldvalue.fieldsavevalue}">{/if}{if isset($fldvalue.fieldshowvalue)}{$fldvalue.fieldshowvalue}{/if}
			</td>
		{/if}
