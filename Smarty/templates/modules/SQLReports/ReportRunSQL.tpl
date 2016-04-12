{*<!--

	/*********************************************************************************
	 ** AGC
	 ********************************************************************************/

-->*}
<br>
<script language="JavaScript" type="text/javascript" src="modules/Reports/Reports.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
{$BLOCKJS}


<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
    <td valign="top"><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	
	
	
<table class="small reportGenHdr mailClient mailClientBg" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<form name="NewReport" action="index.php" method="POST" onsubmit="VtigerJS_DialogBox.block();">
    <input type="hidden" name="booleanoperator" value="5"/>
    <input type="hidden" name="record" value="{$REPORTID}"/>
    <input type="hidden" name="reload" value=""/>    
    <input type="hidden" name="module" value="SQLReports"/>
    <input type="hidden" name="action" value="SaveAndRun"/>
    <input type="hidden" name="dlgType" value="saveAs"/>
    <input type="hidden" name="reportName"/>
    <input type="hidden" name="folderid" value="{$FOLDERID}"/>
    <input type="hidden" name="reportDesc"/>
    <input type="hidden" name="folder"/>

	<tbody>
	<tr>
	<td style="padding: 10px; text-align: left;" width="70%">
		<span class="moduleName">
		{if $MOD.$REPORTNAME neq ''}
			{$MOD.$REPORTNAME}
		{else}
			{$REPORTNAME}
		{/if}
		</span>&nbsp;&nbsp;
		{if $IS_EDITABLE eq 'true'}
		<input type="button" name="custReport" value="{$MOD.LBL_CUSTOMIZE_REPORT}" class="crmButton small edit" onClick="editSQLReport('{$REPORTID}');">
		{/if}
		<br>
		<a href="index.php?module=SQLReports&action=DetailView&record={$REPORTID}" class="reportMnu" style="border-bottom: 0px solid rgb(0, 0, 0);">&lt;{$MOD.LBL_BACK_TO_REPORTS}</a>
	</td>
	<td style="border-left: 2px dotted rgb(109, 109, 109); padding: 10px;" width="30%">
                <!--
		<b>{$MOD.LBL_SELECT_ANOTHER_REPORT} : </b><br>
		<select name="another_report" class="detailedViewTextBox" onChange="selectSQLReport()">
		{foreach key=report_in_fld_id item=report_in_fld_name from=$REPINFOLDER}
		{if $MOD.$report_in_fld_name neq ''} 
			{if $report_in_fld_id neq $REPORTID}
				<option value={$report_in_fld_id}>{$MOD.$report_in_fld_name}</option>
			{else}	
				<option value={$report_in_fld_id} selected>{$MOD.$report_in_fld_name}</option>
			{/if}
		{else}
			{if $report_in_fld_id neq $REPORTID}
				<option value={$report_in_fld_id}>{$report_in_fld_name}</option>
			{else}	
				<option value={$report_in_fld_id} selected>{$report_in_fld_name}</option>
			{/if}
		{/if}
		{/foreach}
		</select>&nbsp;&nbsp;
                -->
	</td>
	</tr>
	</tbody>
</table>


<div style="display: block;" id="Generate" align="center">
	{include file="modules/SQLReports/ReportRunContentsSQL.tpl"}
</div>
<br>

</td>
<td valign="top"><img src="{'showPanelTopRight.gif'|@vtiger_imageurl:$THEME}"></td>
</tr>
</table>



{literal}

<SCRIPT LANGUAGE=JavaScript>
function CrearEnlace(tipo,id){
	

	return "index.php?module=SQLReports&action="+tipo+"&record="+id;

}
function goToURL( url )
{
	document.location.href = url;
}
					
var filter = getObj('stdDateFilter').options[document.NewReport.stdDateFilter.selectedIndex].value
    if( filter != "custom" )
    {
        showDateRange( filter );
    }

// If current user has no access to date fields, we should disable selection
// Fix for: #4670
standardFilterDisplay();

function generateReport(id)
{
	var stdDateFilterFieldvalue = '';
	if(document.NewReport.stdDateFilterField.selectedIndex != -1)
		stdDateFilterFieldvalue = document.NewReport.stdDateFilterField.options  [document.NewReport.stdDateFilterField.selectedIndex].value;

	var stdDateFiltervalue = '';
	if(document.NewReport.stdDateFilter.selectedIndex != -1)
		stdDateFiltervalue = document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].value;
	var startdatevalue = document.NewReport.startdate.value;
	var enddatevalue = document.NewReport.enddate.value;

	var date1=getObj("startdate")
        var date2=getObj("enddate")
	
if ((date1.value != '') || (date2.value != ''))
{

        if(!dateValidate("startdate","Start Date","D"))
                return false

        if(!dateValidate("enddate","End Date","D"))
                return false

	if(!dateComparison("startdate",'Start Date',"enddate",'End Date','LE'))
                return false;
}


	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=ReportsAjax&file=SaveAndRun&mode=ajax&module=Reports&record='+id+'&stdDateFilterField='+stdDateFilterFieldvalue+'&stdDateFilter='+stdDateFiltervalue+'&startdate='+startdatevalue+'&enddate='+enddatevalue,
                        onComplete: function(response) {
				getObj('Generate').innerHTML = response.responseText;
				// Performance Optimization: To update record count of the report result 
				var __reportrun_directoutput_recordcount_scriptnode = $('__reportrun_directoutput_recordcount_script');
				if(__reportrun_directoutput_recordcount_scriptnode) { eval(__reportrun_directoutput_recordcount_scriptnode.innerHTML); }
				// END
				setTimeout("ReportInfor()",1);
                        }
                }
        );
}
function selectSQLReport()
{
	var id = document.NewReport.another_report.options  [document.NewReport.another_report.selectedIndex].value;
	var folderid = getObj('folderid').value;
	url ='index.php?action=SaveAndRunSQL&module=Reports&record='+id+'&folderid='+folderid;
	goToURL(url);
}
function ReportInfor()
{
	var stdDateFilterFieldvalue = '';
	if(document.NewReport.stdDateFilterField.selectedIndex != -1)
		stdDateFilterFieldvalue = document.NewReport.stdDateFilterField.options  [document.NewReport.stdDateFilterField.selectedIndex].text;

	var stdDateFiltervalue = '';
	if(document.NewReport.stdDateFilter.selectedIndex != -1)
		stdDateFiltervalue = document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].text;

	var startdatevalue = document.NewReport.startdate.value;
	var enddatevalue = document.NewReport.enddate.value;

	if(startdatevalue != '' && enddatevalue=='')
	{
		var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"   (from  '+startdatevalue+' )';
	}else if(startdatevalue == '' && enddatevalue !='')
	{
		var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"   (  till  '+enddatevalue+')';
	}else if(startdatevalue == '' && enddatevalue =='')
	{
		{/literal}
                var reportinfr = "{$MOD.NO_FILTER_SELECTED}";
                {literal}
	}else if(startdatevalue != '' && enddatevalue !='')
	{
	var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"  of  "'+stdDateFiltervalue+'"  ( '+startdatevalue+'  to  '+enddatevalue+' )';
	}
	getObj('report_info').innerHTML = reportinfr;
}
ReportInfor();

{/literal}
function goToPrintReport(id) 
{ldelim}
	window.open("index.php?module=SQLReports&action=SQLReportsAjax&file=PrintReportSQL&record="+id);

{rdelim}
</SCRIPT>
