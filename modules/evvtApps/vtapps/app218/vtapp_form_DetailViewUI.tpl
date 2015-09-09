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
  {php}
    require_once('include/utils/UserInfoUtil.php');
    require_once('include/utils/utils.php');
    global $current_user;
    if((strpos($current_user->user_name,"superadmin") !== false && $current_user->is_admin == 'on') || $current_user->roleid=='H63' )
    $this->assign("isSuperadmin",1);
    $isTemplate = istmpl($this->_tpl_vars['ID']);
    $this->assign("isTemplate" ,$isTemplate); 
  {/php}
<!-- This file is used to display the fields based on the ui type in detailview -->
{if ($keyid eq '1' && $keyfldname neq 'vat') || $keyid eq 2 || $keyid eq '11' || $keyid eq '7' || $keyid eq '9' || $keyid eq '55' || $keyid eq '71' || $keyid eq '72' || $keyid eq '103' || $keyid eq '255'} <!--TextBox-->
         <div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>
           
        </ul>
        <p>{$keyval}
        </p>
          
    </div>
</div>
        {elseif $keyid eq '13' || $keyid eq '104'} <!--Email-->
<div {$class} >
<span class="alert-icon"><i {$image_class}></i></span>
<div class="notification-info" style="height:50px;">
    <ul class="clearfix notification-meta">
        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label}</li>

    </ul>
    <p>{$keyval}
    </p>

</div>
</div> 
	{elseif ($keyid eq '15' || $keyid eq '16')&& !(in_array($keyfldname,$dependencyarray)) } <!--ComboBox-->
               <div {$class} >
                    <span class="alert-icon"><i {$image_class}></i></span>
                    <div class="notification-info" style="height:50px;">
                        <ul class="clearfix notification-meta">
                            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label}</li>

                        </ul>
                        <p>{$keyval}
                        </p>

                    </div>
                </div> 
        {elseif $keyid eq '33'}<!--Multi Select Combo box-->
						<!--code given by Neil start Ref:http://forums.vtiger.com/viewtopic.php?p=31096#31096-->
						<!--{assign var="MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING" value=", "}  {* Separates Multi-Select Combo Box items *}
						{assign var="DETAILVIEW_WORDWRAP_WIDTH" value="70"} {* No. of chars for word wrapping long lines of Multi-Select Combo Box items *}-->
                    
                                    {foreach item=sel_val from=$keyoptions }
						{if $sel_val[2] eq 'selected'}
							{if $selected_val neq ''}
							{assign var=selected_val value=$selected_val|cat:', '}
							{/if}
							{assign var=selected_val value=$selected_val|cat:$sel_val[0]}
						{/if}
					{/foreach}
						
                                           <div {$class} >
                                                <span class="alert-icon"><i {$image_class}></i></span>
                                                <div class="notification-info" style="height:50px;">
                                                    <ul class="clearfix notification-meta">
                                                        <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label}</li>

                                                    </ul>
                                                    <p>{$selected_val|replace:"\n":"<br>&nbsp;&nbsp;"}
                                                                        <!-- commented to fix ticket4631 -using wordwrap will affect Not Accessible font color -->
                                                                        <!--{$selected_val|replace:$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING:"\x1"|replace:" ":"\x0"|replace:"\x1":$MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING|wordwrap:$DETAILVIEW_WORDWRAP_WIDTH:"<br>&nbsp;"|replace:"\x0":"&nbsp;"}-->

                                                    </p>

                                                </div>
                                                </div> 
						{elseif $keyid eq '115'} <!--ComboBox Status edit only for admin Users-->
               							<td width=13% class="dvtCellInfo" align="left">{$keyval}</td>
						{elseif $keyid eq '116' || $keyid eq '117'} <!--ComboBox currency id edit only for admin Users-->
								{if $keyadmin eq 1 || $keyid eq '117'}
                                                                {if $MODULE neq 'Project' || $isTemplate neq 1 || $isSuperadmin eq 1}
               							<td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
								{else}
                                                                <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
                                                                {/if}
                                                                &nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                                                <div id="editarea_{$label}" style="display:none;">
                    							   <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
									{foreach item=arr key=uivalueid from=$keyoptions}
									{foreach key=sel_value item=value from=$arr}
										<option value="{$uivalueid}" {$value}>{$sel_value|@getTranslatedCurrencyString}</option>	
									{/foreach}
									{/foreach}
                    							   </select>
                                                                    <br>{if $MODULE eq 'ProjectTask' && $status eq 'Closed'}
                                                                       {elseif $mod==1 &&  $MODULE eq 'Potentials'}
                                                {else}<input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		  {/if} <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                    							</div>
								{else}
               							<td width=13% class="dvtCellInfo" align="left">{$keyval}
								{/if}	

                                        		
               							</td>
                                             {elseif $keyid eq '17'} <!--WebSite-->
                                                  <div {$class}>
                                                        <span class="alert-icon"><i {$image_class}></i></span>
                                                        <div class="notification-info" style="height:50px;">
                                                            <ul class="clearfix notification-meta">
                                                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                            </ul>
                                                            <p>
                                                        <a href="http://{$keyval}" target="_blank">{$keyval}</a>
                                                        </p>         
                                                    </div>
                                                </div> 
                                                      
					     {elseif $keyid eq '85'}<!--Skype-->
                                             {if $MODULE neq 'Project' || $isTemplate neq 1 || $isSuperadmin eq 1}
                                                <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<img src="{'skype.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SKYPE}" title="{$APP.LBL_SKYPE}" LANGUAGE=javascript align="absmiddle"></img><span id="dtlview_{$label}"><a href="skype:{$keyval}?call">{$keyval}</a></span>
                                             {else}   
                                             <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<img src="{'skype.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SKYPE}" title="{$APP.LBL_SKYPE}" LANGUAGE=javascript align="absmiddle"></img><span id="dtlview_{$label}"><a href="skype:{$keyval}?call">{$keyval}</a></span>
                                             {/if}        <div id="editarea_{$label}" style="display:none;">
                                                          <input class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" type="text" id="txtbox_{$label}" name="{$keyfldname}" maxlength='100' value="{$keyval}"></input>
                                                          <br>{if $MODULE eq 'ProjectTask' && $status eq 'Closed'}
                                                             {elseif $mod==1 &&  $MODULE eq 'Potentials'}
                                                {else}<input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                                          {/if}<a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>	
                                             {elseif $keyid eq '19' || $keyid eq '20'} <!--TextArea/Description-->
						<div {$class}>
                                                        <span class="alert-icon"><i {$image_class}></i></span>
                                                        <div class="notification-info" style="height:50px;">
                                                            <ul class="clearfix notification-meta">
                                                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                            </ul>
                                                            <p>
                                                        
								{$keyval|regex_replace:"/(^|[\n ])([\w]+?:\/\/.*?[^ \"\n\r\t<]*)/":"\\1<a href=\"\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])((www|ftp)\.[\w\-]+\.[\w\-.\~]+(?:\/[^ \"\t\n\r<]*)?)/":"\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>"|regex_replace:"/(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/i":"\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>"|regex_replace:"/,\"|\.\"|\)\"|\)\.\"|\.\)\"/":"\""|replace:"\n":"<br>&nbsp;"}
							 </p>         
                                                    </div>
                                                </div> 
                                             {elseif $keyid eq '21' || $keyid eq '24' || $keyid eq '22'} <!--TextArea/Street-->
                                             <div {$class}>
                                                        <span class="alert-icon"><i {$image_class}></i></span>
                                                        <div class="notification-info" style="height:50px;">
                                                            <ul class="clearfix notification-meta">
                                                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                            </ul>
                                                            <p>
                                                        {$keyval}
                                                        </p>         
                                                    </div>
                                                </div> 
                                             {elseif $keyid eq '50' || $keyid eq '73' || $keyid eq '51'} <!--AccountPopup-->
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq '57'} <!--ContactPopup-->
						<!-- Ajax edit link not provided for contact - Reports To -->
                                                  	<td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a></td>
                                             {elseif $keyid eq '59'} <!--ProductPopup-->
                                                 {if $MODULE neq 'Project' || $isTemplate neq 1 || $isSuperadmin eq 1}
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              	  {else}
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<span id="dtlview_{$label}"><a href="{$keyseclink}">{$keyval}</a></span>
                                              	  {/if}	<div id="editarea_{$label}" style="display:none;">                                              		  
                                                         <input id="popuptxt_{$label}" name="product_name" readonly type="text" value="{$keyval}"><input id="txtbox_{$label}" name="{$keyfldname}" type="hidden" value="{$keysecid}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=602,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
                                                         <br>{if $MODULE eq 'ProjectTask' && $status eq 'Closed'}
                                                            {elseif $mod==1 &&  $MODULE eq 'Potentials'}
                                                {else}<input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                              		{/if}  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                       </div>
                                                  </td>
                                             {elseif $keyid eq '75' || $keyid eq '81'} <!--VendorPopup-->
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 76} <!--PotentialPopup-->
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 78} <!--QuotePopup-->
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
                                             {elseif $keyid eq 82} <!--Email Body-->
                                                  <td colspan="3" width=100% class="dvtCellInfo" align="left"><div id="dtlview_{$label}" style="width:100%;height:200px;overflow:hidden;border:1px solid gray" class="detailedViewTextBox" onmouseover="this.className='detailedViewTextBoxOn'" onmouseout="this.className='detailedViewTextBox'">{$keyval}</div>
                                                  </td>
                                             {elseif $keyid eq 80} <!--SalesOrderPopup-->
                                                  <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<a href="{$keyseclink}">{$keyval}</a>
                                                  </td>
					     {elseif $keyid eq '52' || $keyid eq '77'} 
                                                  {if $MODULE neq 'Project' || $isTemplate neq 1 || $isSuperadmin eq 1}
                                                           <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onmouseover="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>
                                                  {else}
                                                            <td width=25% class="dvtCellInfo" align="left" id="mouseArea_{$label}">&nbsp;<span id="dtlview_{$label}">{$keyval}</span>                                                  
                                                  {/if}  <div id="editarea_{$label}" style="display:none;">
                                                                           <select id="txtbox_{$label}" name="{$keyfldname}" class="small">
                                                                                {foreach item=arr key=uid from=$keyoptions}
                                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                                <option value="{$uid}" {$value}>{if $APP.$sel_value}{$APP.$sel_value}{else}{$sel_value}{/if}</option>

                                                                                        {/foreach}
                                                                                {/foreach}
                                                                           </select>
                                                            <br>{if $MODULE eq 'ProjectTask' && $status eq 'Closed'}
                                                               {elseif $mod==1 &&  $MODULE eq 'Potentials'}
                                                {else}<input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');fnhide('crmspanid');"/> {$APP.LBL_OR}
                                                          {/if} <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                                                                        </div>
                                                                </td>	
						{elseif $keyid eq '53'} <!--Assigned To-->
							<div {$class}>
                                                        <span class="alert-icon"><i {$image_class}></i></span>
                                                        <div class="notification-info" style="height:50px;">
                                                            <ul class="clearfix notification-meta">
                                                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                            </ul>
                                                            <p>
                                                        {if $keyadmin eq 1}
								<a href="{$keyseclink.0}">{$keyval}</a>         
							{else}	
								{$keyval}
							{/if}
                                                        </p>         
                                                    </div>
                                                </div> 
						{elseif $keyid eq '99'}<!-- Password Field-->
						<td width=13% class="dvtCellInfo" align="left">{$CHANGE_PW_BUTTON}</td>
					    {elseif $keyid eq '56'} <!--CheckBox--> 
                       <div {$class} >
    <span class="alert-icon"><i {$image_class}></i></span>
    <div class="notification-info" style="height:50px;">
        <ul class="clearfix notification-meta">
            <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>
           
        </ul>
        <p>{$keyval}
        </p>
          
    </div>
</div>  
			{elseif $keyid eq '156'} <!--CheckBox for is admin-->
			{if $smarty.request.record neq $CURRENT_USERID && $keyadmin eq 1} 
                      {if $MODULE neq 'Project' || $isTemplate neq 1 || $isSuperadmin eq 1}
                      <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}" onMouseOver="hndMouseOver({$keyid},'{$label}');" onmouseout="fnhide('crmspanid');">
                      {else}
                       <td width=13% class="dvtCellInfo" align="left" id="mouseArea_{$label}">
                      {/if}
                     &nbsp;<span id="dtlview_{$label}">{if $APP.$keyval!=''}{$APP.$keyval}{elseif $MOD.$keyval!=''}{$MOD.$keyval}{else}{$keyval}{/if}&nbsp;</span>     
                     <div id="editarea_{$label}" style="display:none;">
                        {if $keyval eq 'on'}                                              		  
                            <input id="txtbox_{$label}" name="{$keyfldname}" type="checkbox" style="border:1px solid #bababa;" checked value="1">
                        {else}
                          <input id="txtbox_{$label}" type="checkbox" name="{$keyfldname}" style="border:1px solid #bababa;" value="0">
                       	{/if}
                          <br>{if $MODULE eq 'ProjectTask' && $status eq 'Closed'}
                             {elseif $mod==1 &&  $MODULE eq 'Potentials'}
                                                {else}<input name="button_{$label}" type="button" class="crmbutton small save" value="{$APP.LBL_SAVE_LABEL}" onclick="dtlViewAjaxSave('{$label}','{$MODULE}',{$keyid},'{$keytblname}','{$keyfldname}','{$ID}');"/> {$APP.LBL_OR}
                        {/if}  <a href="javascript:;" onclick="hndCancel('dtlview_{$label}','editarea_{$label}','{$label}')" class="link">{$APP.LBL_CANCEL_BUTTON_LABEL}</a>
                        </div>
			{else}
				 <td width=13% class="dvtCellInfo" align="left">{$keyval}
			{/if}
                        </td>    
			 
						{elseif $keyid eq 83}<!-- Handle the Tax in Inventory -->
							
                                                    <div {$class} >
                                                        <span class="alert-icon"><i {$image_class}></i></span>
                                                        <div class="notification-info" style="height:50px;">
                                                            <ul class="clearfix notification-meta">
                                                                <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label}</li>

                                                            </ul>
                                                            <p>
                                                                {foreach item=tax key=count from=$TAX_DETAILS}
								{$tax.taxlabel} {$APP.COVERED_PERCENTAGE} &nbsp;&nbsp;
							
									{$tax.percentage}
								
                                                                {/foreach}
                                                            </p>

                                                        </div>
                                                    </div> 
                        

				{elseif $keyid eq 5}
					 <div {$class} >
                                            <span class="alert-icon"><i {$image_class}></i></span>
                                            <div class="notification-info" style="height:50px;">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                </ul>
                                                <p>{$keyval}
                                                </p>

                                            </div>
                                        </div>
				{elseif $keyid eq 69}<!-- for Image Reflection -->
     				<div {$class} >
                                            <span class="alert-icon"><i {$image_class}></i></span>
                                            <div class="notification-info" style="height:50px;">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                </ul>
                                                <p>{$keyval}
                                                </p>

                                            </div>
                                        </div>
				{else}	
                                     {if $keyid eq '10'}
                                          <div {$class} >
                                            <span class="alert-icon"><i {$image_class}></i></span>
                                            <div class="notification-info" style="height:50px;">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                </ul>
                                                <p>{$keyval}
                                                </p>

                                            </div>
                                        </div>
                                     {else}
                                         <div {$class} >
                                            <span class="alert-icon"><i {$image_class}></i></span>
                                            <div class="notification-info" style="height:50px;">
                                                <ul class="clearfix notification-meta">
                                                    <li class="pull-left notification-sender" style="font-size:12px;"><span><a></a></span> {$label} </li>

                                                </ul>
                                                <p>{$keyval}
                                                </p>

                                            </div>
                                        </div> 
                                     {/if}
 											
				{/if}
