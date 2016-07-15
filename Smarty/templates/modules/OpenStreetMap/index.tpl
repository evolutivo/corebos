<script src="modules/OpenStreetMap/js/en_us.lang.js"></script>
<link rel="stylesheet" href="modules/OpenStreetMap/css/ui-lightness/jquery-ui.min.css" />
<script src="modules/OpenStreetMap/js/jquery-ui.min.js"></script>
<script type='text/javascript' src='http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.js?2'></script>
<link rel="stylesheet" href="modules/OpenStreetMap/js/routing/leaflet-routing-machine-2.6.2/dist/leaflet-routing-machine.css" />
<script src="modules/OpenStreetMap/js/routing/leaflet-routing-machine-2.6.2/dist/leaflet-routing-machine.js"></script>
<link rel="stylesheet" type="text/css" href="http://cdn.leafletjs.com/leaflet-0.7.2/leaflet.css" />
<script src="modules/OpenStreetMap/js/l.control.geosearch.js"></script>
<script src="modules/OpenStreetMap/js/l.geosearch.provider.openstreetmap.js"></script>
<link rel="stylesheet" href="modules/OpenStreetMap/css/l.geosearch.css" />
<link rel="stylesheet" href="{$mypath}/slickgrid/slick.grid.css" type="text/css"/>
<style>
    {literal}
    .evvt-cell-title {
      font-weight: bold;
    }

    .evvt-cell-centereinid {
      text-align: center;
    }
        .slick-viewport {
      overflow-x: hidden !important;
	}
    {/literal}
</style>
<link rel="stylesheet" href="{$mypath}/slickgrid/controls/slick.pager.css" type="text/css"/>
<script src="{$mypath}/slickgrid/lib/firebugx.js"></script>
<script src="{$mypath}/slickgrid/lib/jquery.event.drag-2.0.min.js"></script>
<script src="{$mypath}/slickgrid/slick.core.js"></script>
<script src="{$mypath}/slickgrid/slick.formatters.js"></script>
<script src="{$mypath}/slickgrid/slick.grid.js"></script>
<script src="{$mypath}/slickgrid/slick.groupitemmetadataprovider.js"></script>
<script src="{$mypath}/slickgrid/slick.dataview.js"></script>
<script src="{$mypath}/slickgrid/controls/slick.pager.js"></script>
<script type='text/javascript'>
{literal}
jQuery(document).ready(function(){
jQuery("#tabs").tabs({
		beforeActivate: function(event, ui) {
			switch (ui.newTab.index()) {
			  case 0:
				  jQuery("#show").val(jQuery('#filtermodule').val());
                                  map.off('click', newClient);
				  break;
			  case 1:
				  jQuery("#show").val('HelpDesk');
                                  map.off('click', newClient);
				  break;
			  case 2:
				  jQuery("#show").val('Events');
                                  map.off('click', newClient);
				  break;
			  case 3:
				  jQuery("#show").val('Radius');
                                  map.off('click', newClient);
				  break;
			  case 4:
				  jQuery("#show").val('Config');
                                  map.off('click', newClient);
				  break;
                         case 5:
                                //attach listener to map 
                             map.on('click', newClient);
		             break;
			}
		}
	});
        
	jQuery("#rdotabs").tabs();
        jQuery('#tabs').tabs('option', 'active',{/literal}{$seltab}{literal});
	updateFilterCombo($('filtermodule'));       
        });
 {/literal}
</script>
<div class='moduleName' style='padding: 10px'> {$APP.Tools} > {$MOD.OpenStreetMap} >
<a href='index.php?parenttab=Tools&action=index&module=OpenStreetMap&show={$inputshow}'>{$inputshow}</a></div>
<div style='padding: 5px; margin: 10px; margin-top: 0px; padding-top: 0px;'>
<form action='index.php' method='POST' name="EditView" onsubmit="return setupResultMarkers();">
<input type='hidden' name='module' value='{$MODULE}'/>
<input type='hidden' name='action' value='index'/>
<input type='hidden' name='parenttab' value='Analytics'/>
<input type="hidden" name="user" value="{$defaultUser}">
<input type="hidden" name="centerAddress"  id="centerAddress" value="{$centerAddress}">
<input type="hidden" name="entitymarkers"  id="entitymarkers" value='{$entitymarkers}'>
<input type="hidden" name="selview" id="selview" value="{$viewid}">
<input type="hidden" name="iconcustomview" id="iconcustomview" value='{$iconcustomview}'>
<input type='hidden' name='show' id='show' value='{$inputshow}'/>
<input type='hidden' name='defaultRadius' id='defaultRadius' value="{$defaultRadius}"/>
<input type='hidden' name='defaultCity' id='defaultCity' value=""/>
<input type='hidden' name='defaultCountry' id='defaultCountry' value=""/>
<input type='hidden' name='fullUserName' id='fullUserName' value="{$fullUserName}"/>
<input type='hidden' name='userOrgName' id='userOrgName' value="{$userOrgName}"/>
<input type='hidden' name='baseCity' id='baseCity' value="{$baseCity}"/>
<input type='hidden' name='baseAddress' id='baseAddress' value="{$baseAddress}"/>
<input type="hidden" name="orginputshow" id="orginputshow" value="{$orginputshow}">
<div id="tabs">
<ul>
<li><a href="#tabs-1">{$MOD.Filters}</a></li>
<li><a href="#tabs-2">{$MOD.Tickets}</a></li>
<li><a href="#tabs-3">{$MOD.Visitas}</a></li>
<li><a href="#tabs-4">{$MOD.Radius}</a></li>
<li><a href="#tabs-5">{$MOD.Defaults}</a></li>
<li><a href="#tabs-6">{$MOD.NewClient}</a></li>
</ul>
<div id="tabs-1">
<span style='float:left;'><span style='font-weight: bold ; font-size: 110%; margin-left: 6px;'>{$MOD.Show}&nbsp;{$APP.LBL_MODULE}:</span><br/>
<select onchange='updateFilterCombo(this)' name='filtermodule' id='filtermodule'>
<option value='Accounts' {if $inputshow eq 'Accounts'} selected="selected" {/if}>{$APP.Accounts}</option>
<option value='Contacts' {if $inputshow eq 'Contacts'} selected="selected"{/if}>{$APP.Contacts}</option>
<option value='Leads' {if $inputshow eq 'Leads'} selected="selected" {/if}>{$APP.Leads}</option>
</select>
</span>
<span id="userspantpl" style="display: none">
<span id="userspanfilter" style="margin-left:10px;border-left: solid 1px;padding-left: 3px;">{$APP.LBL_USER}:
<select name="user_select2" onchange="this.form.user.value=this.value;" style="margin-left:6px;">{$users_options}</select>
</span>
</span>
<span id='filterContainer' class='searchUIBasic small' style='margin-left: 8px;padding-top: 8px;padding-bottom: 8px;width: 88%;display:block;float:left;'></span>
</div>
<div id="tabs-2">
<span class='searchUIBasic small' style='margin-left: 8px;padding-top: 8px;padding-bottom: 8px;width: 98%;display:block;float:left;'>
&nbsp;{$cvTicket}<br/>
<input type='hidden' name='reports_to_type' id='reports_to_type' value='Users'>
<input type='hidden' name='reports_to_id' id='reports_to_id' value='{$reports_to_id}'/>
&nbsp;&nbsp;{$MOD.RESTRICT_USER}:&nbsp;
<input type="text" value="{$reports_to_id}" style="border: 1px solid rgb(186, 186, 186);" readonly="" name="reports_to_id_display" id="reports_to_id_display"/> 
<img align="absmiddle" style="cursor: pointer;" onclick='window.open("index.php?module="+ document.EditView.reports_to_type.value +"&amp;action=Popup&amp;html=Popup_picker&amp;form=vtlibPopupView&amp;forfield=reports_to_id&amp;srcmodule=Maps&amp;forrecord=","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' title="Select" alt="Select" src="themes/softed/images/select.gif"/> 
<img align="absmiddle" style="cursor: pointer;" onclick="document.EditView.reports_to_id.value='0'; document.EditView.reports_to_id_display.value='';" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
</span>
</div>
<div id="tabs-3">
<span style='font-weight: bold; margin-left: 6px; font-size: 110%; width: 98%;'>
<span id='date' class="searchUIBasic small"  style="margin-left: 10px;padding-top: 8px;padding-bottom: 8px;width: 97%;display:block;">
&nbsp;{$APP.LBL_START_DATE}: <input name="start_date" tabindex="5" id="jscal_field_start_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$start_date_val}">
	<img style="vertical-align:middle;" src="{'btnL3Calendar.gif'|@vtiger_imageurl:$THEME}" id="jscal_trigger_start_date">   
	<script type="text/javascript" id='massedit_calendar_start_date'>
	{literal}	Calendar.setup ({
			inputField : "jscal_field_start_date", showsTime : false, button : "jscal_trigger_start_date", singleClick : true, step : 1
		})
                {/literal}
	</script> 
&nbsp;{$APP.LBL_END_DATE}: <input name="end_date" tabindex="5" id="jscal_field_end_date" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$end_date_val}">
	<img style="vertical-align:middle;" src="{'btnL3Calendar.gif'|@vtiger_imageurl:$THEME}" id="jscal_trigger_end_date">   
	<script type="text/javascript" id='massedit_calendar_end_date'>
	{literal}	Calendar.setup ({
			inputField : "jscal_field_end_date", showsTime : false, button : "jscal_trigger_end_date", singleClick : true, step : 1
		})
                   {/literal}
	</script>
	<span id="userspan" style="margin-left:10px;border-left: solid 1px;padding-left: 3px;"> {$APP.LBL_USER}:<select name="user_select1" onchange="this.form.user.value=this.value;">{$users_options}</select></span>
	&nbsp;&nbsp;&nbsp;&nbsp;    
	{$APP.Related_to}:&nbsp;
	<input type="checkbox" name="showAccounts" {$checkedAccounts}/>&nbsp; {$APP.Accounts}
	&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="showContacts" {$checkedContacts}/>&nbsp;{$APP.Contacts}
	&nbsp;&nbsp;&nbsp;
	<input type="checkbox" name="showLeads" {$checkedLeads}/>&nbsp;{$APP.Leads}
</span>
</span>
</div>
<div id="tabs-4">
<span class='searchUIBasic small' style='padding-top: 8px;padding-bottom: 8px;width: 98%;display:block;float:left;'>
&nbsp;{$APP.LBL_MODULE} <select name="modulefrom" id="moduleselected" class="small" onchange="this.form.parent_id.value=''; this.form.parent_name.value='';">
<option value='Accounts' {if $rsrch_module eq 'Accounts'} selected="selected" {/if}>{$APP.Accounts}</option>
<option value='Contacts' {if $rsrch_module eq 'Contacts'} selected="selected" {/if}>>{$APP.Contacts}</option>
<option value='Leads'    {if $rsrch_module eq 'Leads'} selected="selected" {/if}>{$APP.Leads}</option>
</select>
<input id="parent_id" name="recordval" type="hidden" value="{$rsrch_crmid}">
<input id="parent_name" name="recordval_display" readonly type="text" style="border:1px solid #bababa;" value="{$rsrch_ename}">&nbsp;
<img id="entity"
     src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="Select" title="Select" align="absmiddle" style='cursor:hand;cursor:pointer'
     onClick='window.open("index.php?module="+document.getElementById("moduleselected").value+"&action=Popup&html=Popup_picker&form=vtlibPopupView&srcmodule=OpenStreetMap","test",
"width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");'>
<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}"
alt="Clear" title="Clear" LANGUAGE=javascript	onClick="this.form.recordval.value=''; this.form.recordval_display.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;
<input type="image" src="modules/OpenStreetMap/img/icon-coord.gif" width='20px'
alt="GetCoordinates" title="GetCoordinates" LANGUAGE=javascript	onClick="evvt_GetCoordinates(); return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;&nbsp;&nbsp;
{$MOD.Latitud}: <input type="text" id='radlat' name='radlat' value='{$rsrch_lat}' class="small">&nbsp;&nbsp;
{$MOD.Longitud}: <input type="text" id='radlng' name='radlng' value='{$rsrch_lng}' class="small">&nbsp;&nbsp;
&nbsp;&nbsp;{$MOD.Radius}: <input type="text" id='radrad' name='radrad' value='{$rsrch_radius}' class="small" style="width:35px;">
</span>
</div>
<div id="tabs-5">
<label for="defradius" class="defaultLabel">{$MOD.defaultRadius} :</label> <input type="text" id='defradius' name='defradius' value='{$radius}' class="small" style="width:35px;"><br/>
<label for="defzoom" class="defaultLabel">{$MOD.defaultZoom} :</label> <input type="text" id='defzoom' name='defzoom' value='{$zoom}' class="small" style="width:35px;"><br/>
<label for="defmaptype" class="defaultLabel">{$MOD.defaultMapType} :</label> 
{*<select id='defmaptype' name='defmaptype' class="small">
    <option value="politico" {($maptype=='politico' ? 'selected' : '');?>>{$MOD.politico}</option>
<option value="fisico" {($maptype=='fisico' ? 'selected' : '');?>>{$MOD.fisico}</option></select><br/>*}
<label for="deftab" class="defaultLabel">{$MOD.defaultTab} :</label> <select id='deftab' name='deftab' class="small">
	<option value="0" {if $tab eq 0}  selected="selected" {else} selected=''{/if}>{$MOD.Filters}</option>
	<option value="1" {if $tab eq 1}  selected="selected" {else} selected=''{/if}>{$MOD.Tickets}</option>
	<option value="2" {if $tab eq 2} selected="selected" {else} selected=''{/if}>{$MOD.Visitas}</option>
	<option value="3" {if $tab eq 3} selected="selected" {else} selected=''{/if}>{$MOD.Radius}</option>*}
</select>
</div>
<div id ="tabs-6">
  <div id="findhotels">
    Find hotels in:
  </div>
  <div id="locationField">
    <input id="autocomplete" placeholder="Enter a city" type="text" autocomplete="off">
  </div>
  <div id="controls">
    <select id="country" onchange="setAutocompleteCountry()">
      <option value="all">All</option>
      <option value="au">Australia</option>
      <option value="br">Brazil</option>
      <option value="ca">Canada</option>
      <option value="fr">France</option>
      <option value="de">Germany</option>
      <option value="mx">Mexico</option>
      <option value="nz">New Zealand</option>
      <option value="it">Italy</option>
      <option value="za">South Africa</option>
      <option value="es">Spain</option>
      <option value="pt">Portugal</option>
      <option value="us" selected="">U.S.A.</option>
      <option value="uk">United Kingdom</option>
    </select>
  </div>
</div>
<div style="clear:both;"></div>
<input type='submit' style="margin-left: 10px;margin-top: 4px;margin-bottom: 4px; clear: both;" value='{$APP.LBL_UPDATE}'/>
</div>
</form>
<div id="map" style="margin-left: 10px; margin-right: 10px; width: 69%; height: 1000px;  border: 1px solid black;  padding-bottom:40px; float: left"></div>

<div style="align:right;width:27%;margin-left: 10px;margin-right: 10px;float:right;">         
    {$MOD.Legend}
            <div class="searchUIBasic small" style="display: block;position:relative;">
	{if $inputshow neq 'Events' && $orginputshow neq 'Radius'}
		{foreach from=$customviewname key=icon item=value}
			<br/><img src="modules/OpenStreetMap/img/{$icon}.png">{$value}
                {/foreach}
	{else}
		<br/><img src="modules/OpenStreetMap/img/{$icons[0]}.png">{$APP.Account}
		<br/><img src="modules/OpenStreetMap/img/{$icons[1]}.png">{$APP.Contacts}
		<br/><img src="modules/OpenStreetMap/img/{$icons[2]}.png">{$APP.Leads}
	{/if} 
	{if $inputshow neq 'Events' && $orginputshow neq 'Radius'}
		<br/><img src="modules/OpenStreetMap/img/purple-dot.png">{$MOD.FoundMoreOnce}
        {/if}
	<br/><img src="modules/OpenStreetMap/img/me-dot.png">{$MOD.MyLocation}

              <br>
            </div>
  </div>
        	<div style="align:right;width:27%;margin-left: 10px;margin-right: 10px;float:right;"><br/></div>
	<div id="rdotabs"  style="align:right;width:26%;height:600px; overflow-y: scroll; margin-left: 10px;margin-right: 10px;float:right;">
	<ul>
	<li><a href="#rdotab">{$MOD.Results}</a></li>
	<li><a href="#ruttab">{$MOD.Direction}</a></li>
	<li><a href="#ruttab1">{$MOD.DirectionBetweenMarks}</a></li>
	</ul>
	<div id="rdotab">
	  <div style="margin-left: 10px;font-weight: bold;">{$MOD.RecordsFound}: {*"; echo count($results);?>*}
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type=checkbox id='setGrouping' onchange="setGrouping()">&nbsp;{$MOD.GroupType}</div>
	 <div id="rdoGrid" name="rdoGrid" style="width:98%;height:450px;margin: 15px auto;"></div>
	  <div id="pager" style="width:98%;height:20px;margin: 15px auto;"></div>
	</div>
	<div id="ruttab">
            <div id="desc" name="desc" ></div><br/>
            <input type="button" class="crmbutton small edit"  value="{$MOD.ClearDirections}" onClick="restore();"/><br/>
            <div id="route"></div>
        </div>
	<div id="ruttab1">
         <div id="desc1" name="desc1" ></div><br/><b>From: <i>
         <div id="from"/></div></i></b><br/><b>To: <i>
        <div id="to"/></div></i></b> <br/>
        <input type="button" class="crmbutton small edit"  value="Directions between marks" onClick="showDirections();"/>
        <br/><br/><br/><div id="route1">
        </div></div>

	</div>
</div>
  <script>
jQuery.noConflict();

// Code that uses other library's $ can follow here.
</script>
<script src="modules/OpenStreetMap/js/osm.js"></script>
<script>
 {literal}
  results =  JSON.parse('{/literal}{$results}{literal}'); 
  var grid;
  var sortcol = "entityname";
  var sortdir = 1;
  var searchString = "";
  var dataView;
  var columns = [
    {id: "pinit", name: "", field: "pinit", width: 16, sortable: false, formatter: formatter},
    {id: "entityname", name: "{/literal}{$MOD.EntityName}{literal}", field: "entityname", width: 160, sortable: true, formatter: formatter},
    {id: "entitymodule", name: "{/literal} {$MOD.EntityType} {literal}", field: "entitymodule", width: 160, sortable: false, formatter: formatter},
  ];
  var options = {
    enableCellNavigation: true,
    enableColumnReorder: false,
    multiColumnSort: true
  };

        var evvtmapdata = [];
	nu=0; 
        var str= '';
     if(typeof(results.length) == "undefined" || results.length != 0)
       for(var key in results) {
          var result = results[key];
      var temp = new Object();
            temp["id"] = "evvtid_" + key;
            temp["pinit"] = "<img src='modules/OpenStreetMap/img/target-icon-mn.png' width='16px'  onclick='evvtMap_CenterOn("+result['lat']+","+result['lng']+")';>";
            temp["entityname"] = "<a href='index.php?module="+result['entitytype']+"&action=DetailView&record="+key+"'>"+ result['name']+"</a>";
            temp["ename"] = result['entityname'];
            temp["entitymodule"]=result['entitytype'];
            evvtmapdata[nu] = temp;

   /**  evvtmapdata[nu] = {
        id: "evvtid_" + key,
        pinit: "<img src='modules/OpenStreetMap/img/target-icon-mn.png' width='16px' onclick='evvtMap_CenterOn("+result['lat']+","+result['lng']+")';",
        entityname: "<a href='index.php?module="+result['entitytype']+"&action=DetailView&record="+key+"'>"+ result['name']+"</a>",
        ename: result['entityname'],
        entitymodule: result['entitytype']
      };
         **/
        nu++;
        }
  var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();       
  dataView = new Slick.Data.DataView({groupItemMetadataProvider: groupItemMetadataProvider,inlineFilters: true}); 
  grid = new Slick.Grid("#rdoGrid",dataView, columns, options);
  // register the group item metadata provider to add expand/collapse group handlers
  grid.registerPlugin(groupItemMetadataProvider);
    console.log('brisdi');
  console.log(evvtmapdata);
  var pager = new Slick.Controls.Pager(dataView, grid, jQuery("#pager"));
  
   dataView.onRowCountChanged.subscribe(function (e, args) {
    grid.updateRowCount();
     grid.render();
    });
   
    dataView.onRowsChanged.subscribe(function (e, args) {
    grid.invalidateRows(args.rows);
    grid.render();
    });
 /** 

    grid.onSort.subscribe(function (e, args) {
      var cols = args.sortCols;
      dataView.sort(function (dataRow1, dataRow2) {
        for (var i = 0, l = cols.length; i < l; i++) {
          var field = 'ename';
          var sign = cols[i].sortAsc ? 1 : -1;
          var value1 = dataRow1[field].toLowerCase(), value2 = dataRow2[field].toLowerCase();
          var result = (value1 == value2 ? 0 : (value1 > value2 ? 1 : -1)) * sign;
          if (result != 0) {
            return result;
          }
        }
        return 0;
      });
      grid.invalidate();
      grid.render();
    });
    // wire up model events to drive the grid



**/
    // initialize the model after all the events have been hooked up
   dataView.beginUpdate();
    dataView.setItems(evvtmapdata);
    dataView.collapseGroup(0);
    dataView.endUpdate();

  // Slickgrid config starts here
//
function formatter(row, cell, value, columnDef, dataContext) {
    return value;
}

function collapseAllGroups() {
	  dataView.beginUpdate();
	  for (var i = 0; i < dataView.getGroups().length; i++) {
	    dataView.collapseGroup(dataView.getGroups()[i].value);
	  }
	  dataView.endUpdate();
	}

function expandAllGroups() {
  dataView.beginUpdate();
  for (var i = 0; i < dataView.getGroups().length; i++) {
    dataView.expandGroup(dataView.getGroups()[i].value);
  }
  dataView.endUpdate();
}

function clearGrouping() {
  dataView.groupBy(null);
}

function groupByModule() {
  dataView.groupBy(
      "entitymodule",
      function (g) {
        return "{/literal}{$MOD.LBL_MODULE}:{literal}" + g.value + "<span style='color:green'>(" + g.count + ")</span>";
      },
      function (a, b) {
        return a.value - b.value;
      }
  );
}

function setGrouping() {
	if (jQuery('#setGrouping').is(':checked')) {
		groupByModule();
	} else {
		clearGrouping();
	}
}


 // jQuery(".grid-header .ui-icon").addClass("ui-state-default ui-corner-all").mouseover(function (e) {
 //   jQuery(e.target).addClass("ui-state-hover")
 // }).mouseout(function (e) {
 // jQuery(e.target).removeClass("ui-state-hover")
 // });
  
    jQuery("#gridContainer").resizable();
{/literal}
</script>