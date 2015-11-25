{*<!--
 *************************************************************************************************
 * Copyright 2015 OpenCubed -- This file is a part of OpenCubed coreBOS customizations.
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : BusinessActions
 *  Version      : 5.5.0
 *  Author       : OpenCubed
 *************************************************************************************************/
-->*}
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>

<script language="JavaScript" type="text/javascript">
    var allOptions = null;

    function setAllOptions(inputOptions) 
    {ldelim}
        allOptions = inputOptions;
    {rdelim}

    function modifyMergeFieldSelect(cause, effect) 
    {ldelim}
        var selected = cause.options[cause.selectedIndex].value;  id="mergeFieldValue"
        var s = allOptions[cause.selectedIndex];
        effect.length = s;
        for (var i = 0; i < s; i++) 
	{ldelim}
            effect.options[i] = s[i];
        {rdelim}
        document.getElementById('mergeFieldValue').value = '';
    {rdelim}
{literal}
    function init() 
    {
        var blankOption = new Option('--None--', '--None--');
        var options = null;
{/literal}

		var allOpts = new Object({$ALL_VARIABLES|@count}+1);
		{assign var="alloptioncount" value="0"}
		{foreach key=index item=module from=$ALL_VARIABLES}
	    	options = new Object({$module|@count}+1);
	    	{assign var="optioncount" value="0"}
            options[{$optioncount}] = blankOption;
            {foreach key=header item=detail from=$module}
             {assign var="optioncount" value=$optioncount+1}
				options[{$optioncount}] = new Option('{$detail.0}', '{$detail.1}');
			{/foreach}      
			 {assign var="alloptioncount" value=$alloptioncount+1}     
             allOpts[{$alloptioncount}] = options;
	    {/foreach}
        setAllOptions(allOpts);	    
    }
	
</script>

				
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					
					<tr>
					  <td colspan="2" valign=top class="cellText small"><table width="100%"  border="0" cellspacing="0" cellpadding="0" class="thickBorder">
                        <tr>
                          <td valign=top><table width="100%"  border="0" cellspacing="0" cellpadding="5" >
                              <tr>
                                <td colspan="3" valign="top" class="small" style="background-color:#cccccc"><strong>{$MOD.LBL_EMAIL_TEMPLATE}</strong></td>
                                </tr>
                              <tr>
                                <td width="15%" valign="top" class="cellLabel small"><font color='red'>*</font>{$MOD.LBL_SUBJECT}</td>
                                <td width="85%" colspan="2" class="cellText small"><span class="small cellText">
                                  <input name="subject" type="text" value="{$TEMPLATE_SUBJECT}" class="detailedViewTextBox" tabindex="4">
                                </span></td>
                              </tr> 
                             <tr>
                              
                                <td width="15%"  class="cellLabel small" valign="center">{$MOD.LBL_SELECT_FIELD_TYPE}</td>
                                <td width="85%" colspan="2" class="cellText small">

		<table>
			<tr>
				<td>{$MOD.LBL_STEP} 1
				<td>
			
				<td style="border-left:2px dotted #cccccc;">{$MOD.LBL_STEP} 2
				<td>

				<td style="border-left:2px dotted #cccccc;">{$MOD.LBL_STEP} 3
				<td>
			</tr>
			
			<tr>
				<td>

					<select style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" id="entityType" ONCHANGE="modifyMergeFieldSelect(this, document.getElementById('mergeFieldSelect'));" tabindex="6">
                                        <OPTION VALUE="0" selected>{$APP.LBL_NONE}
                                        <OPTION VALUE="1">{$MOD.LBL_ACCOUNT_FIELDS}                           
                                        <OPTION VALUE="2">{$MOD.LBL_CONTACT_FIELDS}
                                        <OPTION VALUE="3" >{$MOD.LBL_LEAD_FIELDS}
                                        <OPTION VALUE="4" >{$MOD.LBL_USER_FIELDS}
                                        <OPTION VALUE="5" >{$MOD.LBL_GENERAL_FIELDS}
                                        </select>
				<td>
			
				<td style="border-left:2px dotted #cccccc;">
					<select style="font-family: Arial, Helvetica, sans-serif;font-size: 11p
x;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffff;" id="mergeFieldSelect" onchange="document.getElementById('mergeFieldValue').value=this.options[this.selectedIndex].value;" tabindex="7"><option value="0" selected>{$APP.LBL_NONE}</select>	
				<td>

				<td style="border-left:2px dotted #cccccc;">	

					<input type="text"  id="mergeFieldValue" name="variable" value="variable" style="font-family: Arial, Helvetica, sans-serif;font-size: 11px;color: #000000;border:1px solid #bababa;padding-left:5px;background-color:#ffffdd;" tabindex="8"/>
				<td>
			</tr>

		</table>

				</td>
                              </tr>
                              <tr>
                                <td valign="top" width=10% class="cellLabel small">{$MOD.LBL_MESSAGE}</td>
                                 <td valign="top" colspan="2" width=60% class="cellText small"><p><textarea name="template" style="width:90%;height:200px" class=small tabindex="5">{$TEMPLATE_BODY}</textarea></p>
                              </tr>
                              <tr>
                                <td valign="top" width=10% class="cellLabel small">{$MOD.LBL_MESSAGE_ONLYTEXT}</td>
                                 <td valign="top" colspan="2" width=60% class="cellText small"><p><textarea name="templateonlytext" style="width:90%;height:200px" class=small tabindex="5">{$TEMPLATE_BODY_ONLYTEXT}</textarea></p>
                              </tr>
                          </table></td>
                          
                        </tr>
                      </table></td>
					  </tr>
					</table>
					<br>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr>
					  <td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>	

<script type="text/javascript" src="include/ckeditor/ckeditor.js"></script>
<script type="text/javascript" defer="1">var textAreaName = null;
	var textAreaName = 'template';
	CKEDITOR.replace( textAreaName,	{ldelim}
		extraPlugins : 'uicolor',
		uiColor: '#dfdff1'
	{rdelim} ) ;
	var oCKeditor = CKEDITOR.instances[textAreaName];
	init();
</script>