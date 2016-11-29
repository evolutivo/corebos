<!DOCTYPE html>
<head>
	<title>{$MOD.LBL_SEARCH_RESULTS}</title> 
	<link REL="SHORTCUT ICON" HREF="../../themes/images/crm-now_icon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<script src="Js/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" href="Css/jquery.mobile.structure-1.4.5.min.css" />
	<script src="Js/jquery.mobile-1.4.5.min.js"></script>
	<link rel="stylesheet" href="Css/jquery.mobile.icons.min.css" />
	<link rel="stylesheet" href="Css/theme.css" />
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
</head>
<body>
<div data-role="page" data-theme="b">
	<!-- header -->
	<div data-role="header"  data-theme="b">
		<h4>{$MOD.LBL_SEARCH_RESULTS}</h4>
		<div style="position: absolute;top: 0;right: 35px;text-align: right;">
			<a href="#panelmenu" data-mini='true' data-role='button' class="ui-btn ui-btn-right ui-btn-icon-notext ui-icon-grid ui-corner-all ui-icon-bars"></a>
		</div>
	</div>
	<!-- /header -->
	{foreach item=module key=modulename from=$LISTENTITY}
		<h4>{$MODLABEL.$modulename}</h4>
		<div data-role="collapsible-set"   data-mini="true">	
			<ul data-role="listview" data-theme="c" >
			{foreach item=reco from=$module }
							{assign var="output1" value=$reco.firstname}
							{assign var="output2" value=$reco.lastname}
							{assign var="RECORD" value=$reco.id}
				<li><a href="?_operation=fetchRecordWithGrouping&record={$RECORD}" target="_self">{$output1} {$output2}</a></li>
			{/foreach}	
			</ul>
		</div>
	{/foreach}
	{include file="modules/Mobile/generic/PanelMenu.tpl"}
</div>		

</body>					
		