<link rel="stylesheet" href="modules/evvtApps/SlickGrid-master/slick.grid.css" type="text/css"/>
  <link rel="stylesheet" href="modules/evvtApps/SlickGrid-master/controls/slick.pager.css" type="text/css"/>
  <link rel="stylesheet" href="modules/evvtApps/SlickGrid-master/css/smoothness/jquery-ui-1.8.16.custom.css" type="text/css"/>
  <link rel="stylesheet" href="modules/evvtApps/SlickGrid-master/examples/examples.css" type="text/css"/>
  <link rel="stylesheet" href="modules/evvtApps/SlickGrid-master/controls/slick.columnpicker.css" type="text/css"/>

  <style>
 .cell-title {
      font-weight: bold;
    }

    .cell-effort-driven {
      text-align: center;
    }

    .cell-selection {
      border-right-color: silver;
      border-right-style: solid;
      background: #f5f5f5;
      color: gray;
      text-align: right;
      font-size: 10px;
    }

    .slick-row.selected .cell-selection {
      background-color: transparent; /* show default selected row background */
    }
  .slick-group-title[level='0'] {
      font-weight: bold;
    }

    .slick-group-title[level='1'] {
      text-decoration: underline;
    }

    .slick-group-title[level='2'] {
      font-style: italic;
    }
  </style>

<?php
    require_once("modules/evvtApps/vtapps/baseapp/vtapp.php");

    class Chiamate_in_ingresso_custom6ad7e2b5238b30de365909dca3aaf054 extends vtApp {

	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = true;
	var $wwidth = 900;
	var $wheight = 600;
	var $haseditsize = true;
	var $ewidth = 0;
	var $eheight = 0;
        public function getQuerypdf($co){
        global $adb;
        $q=$adb->pquery("select * from vtiger_evvtapps where evvtappsid=?",array($this->appid));
        $query=explode("limit",$adb->query_result($q,0,"vtappquery"));
        return $query[0]." ".$co." limit ".$query[1];
}
public function getQuery($co){
include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
if("report"=="none" || "report"=="report"){
    $type="";
      $nu="a";
$reportid="31";
$focus1=new ReportRun($reportid);
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow['tabid'])." ".$currencyfieldrow['fieldlabel'];
				$modprefixedlabel = str_replace(' ','_',$modprefixedlabel);

				if($currencyfieldrow['uitype']!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
        if("0"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

 $group=array();
  $k=0;
  $fld=explode(",","Project_ID,Project_Project_Name,Project_RMA,Project_Serial_Number,Project_Model_Number,Project_Progetto_collegato,Project_Substatus,Project_Account,Project_LBLGROUPBUYER,Project_Contatto,Project_Assigned_To,Project_Brand,Project_Start_Date,Project_Actual_End_Date,Project_Modified_Time,Project_Id_product,Project_Accettazione_Modello_Sostitutivo,Project_Data_di_Acquisto_del_Prodotto,Project_Template,Project_Invio_Box,Project_Attivata,Project_Referenza_ordine,Project_Warranty,Project_Project_No,Project_ID_progetto,Project_total_paid,Project_total_received,Project_total_time,Project_difference_time,Project_Current_level,Project_Estimate_external_Time,Project_Estimate_internal_Time,Project_description,Project_Numero_documento_in_arrivo,Project_Data_documento_in_arrivo,Project_Informazioni_arrivo,Project_Password_OS,Project_Password_BIOS,Project_Descrizione_difetti_esterni,Project_Difetti_esterni,Project_Created_Time,Project_Status,Project_Project_Chiave_Field,Project_Refurbishing,Project_Swap_con_prodotto_nuovo,Project_Spedizione_prodotto_di_cortesia,Project_Target_End_Date,Project_On_Site,Project_linktocondition,Project_linktosymptom");

for($j=0;$j<sizeof($suba);$j++){
 $in=key(preg_grep("/\b$fld[$j]\b/i", $f));


 $ff1=explode('AS',$f[$in]);
 $ff2=explode(' as ',$ff1[0]);
 $ff=$ff2[0];

if($grp[$j]==1) {$group[$k]=$ff;
$k++;}
    if($suba[$j]!='')
        $fields.=$suba[$j].'('.$ff.') as '.$fld[$j].',';
    else $fields.=$ff.' as '.$fld[$j].',';
}


$group2=implode(",",$group);
                $qr=strtolower("select $fields vtiger_project.projectid as primid FROM ".$rq[1]." group by $group2");
}
        else{
        $qr=strtolower($rq[0]." ,vtiger_project.projectid as primid FROM ".$rq[1]);}
            }

          else {
              $query1="show columns from ";


	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;
       $fname1=str_replace("id","",$fname);
       $tab=$adb->query("select * from vtiger_tab where name like \"$fname1\"");
       if($adb->num_rows($tab)!=0)
       $md1=$adb->query_result($tab,0,"name");
}
}}

          $qr="select $fname as primid,.* from  ";
            }
            if(""!="") $lim="Limit 0,";
        else $lim="";

        $query=$qr." ".$co." ".$lim;
        return $query;
}
public function getContent($lang,$condit) {
global $adb,$current_language,$current_user;
include_once('include/database/PearDatabase.php');

$data = '';
$i = 0;
$id=0;
$query2=$adb->query("SELECT vtiger_project.projectid,projectname, vtiger_crmentity.crmid, vtiger_crmentity.createdtime,vtiger_project.serial_number,vtiger_crmentity.smownerid
FROM vtiger_project
INNER JOIN vtiger_crmentity
ON vtiger_project.projectid=vtiger_crmentity.crmid WHERE deleted=0 limit 0,40");

while($rows = $adb->fetch_array($query2)){
    $data .= '
        data['.$i.'] = {
id: "'.$i.'",
        title: "'.$rows['projectname'].'",
            pid: "'.$rows['projectid'].'",
       duration: "'.$rows['serial_number'].'",
smownerid: "'.$rows['smownerid'].'",
createdtime: "'.$rows['createdtime'].'"
        };
    ';

    $i++;
}


?>

<div style="width:400px;position:relative;float:left;  " align="left">
   
           <hr/>
    <div style="">
    
      <label style="width:200px;">And serial number including:</label>
      <input type=text id="txtSearch" style="width:100px;" name="txt" onclick="alert('fsdfds')">
     
      
      <br/><br/>

      <hr/>
      <br/><br/>
      <button onclick="dataView.setGrouping([])">Clear grouping</button>
      <br/>
      <button onclick="groupByDuration()">Group by smownerid & sort groups by value</button>
      <br/>
      <button onclick="groupByDurationOrderByCount(false)">Group by smownerid & sort groups by count</button>
      <br/>
      <button onclick="groupByDurationOrderByCount(true)">Group by smownerid & sort groups by count, aggregate
        collapsed
      </button>
      <br/>
      <br/>
      <button onclick="groupByDurationEffortDriven()">Group by smownerid than createdttime</button>
      <br/>
   
      <br/>
      <br/>
      <button onclick="dataView.collapseAllGroups()">Collapse all groups</button>
      <br/>
      <button onclick="dataView.expandAllGroups()">Expand all groups</button>
      <br/>
    </div> 
  
  </div>	
 <div style="position:relative; margin-left:10%;width:800px">
  

 	<table width="100%">
		<tr>

			<td valign="top" width="50%">
				<div id="myGrid" style="width:800px;height:500px;float:right"></div>
			</td>
			<td valign="top">
				
			</td>
		</tr>
		</table>

     </div>
<script src="modules/evvtApps/SlickGrid-master/lib/firebugx.js"></script>
<script src="modules/evvtApps/SlickGrid-master/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script src="modules/evvtApps/SlickGrid-master/lib/jquery.event.drag-2.2.js"></script>
<script src="modules/evvtApps/SlickGrid-master/plugins/slick.cellrangedecorator.js"></script>
<script src="modules/evvtApps/SlickGrid-master/plugins/slick.cellrangeselector.js"></script>
<script src="modules/evvtApps/SlickGrid-master/plugins/slick.cellselectionmodel.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.core.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.formatters.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.editors.js"></script>
<script src="modules/evvtApps/SlickGrid-master/plugins/slick.rowselectionmodel.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.grid.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.dataview.js"></script>
<script src="modules/evvtApps/SlickGrid-master/controls/slick.pager.js"></script>
<script src="modules/evvtApps/SlickGrid-master/controls/slick.columnpicker.js"></script>
<script src="modules/evvtApps/SlickGrid-master/slick.groupitemmetadataprovider.js"></script>
		<script>
var dataView;
		var grid;
var data=[];
		var columns = [
			{id:"title", name:"Name", field:"title",width: 120, minWidth: 120, cssClass: "cell-title", editor: Slick.Editors.Text, validator: requiredFieldValidator, sortable: true, groupTotalsFormatter: sumTotalsFormatter,formatter: myFormatter},
			{id:"duration", name:"SerialNumber", field:"duration",width: 120, minWidth: 120, cssClass: "cell-title", editor: Slick.Editors.Text, validator: requiredFieldValidator, sortable: true,groupTotalsFormatter: sumTotalsFormatter },
			{id:"%", name:"Smownerid", field:"smownerid" ,width: 120, minWidth: 120, cssClass: "cell-title", editor: Slick.Editors.Text, validator: requiredFieldValidator, sortable: true ,groupTotalsFormatter: sumTotalsFormatter},
			{id:"createdtime", name:"CreatedTime", field:"createdtime",width: 120, minWidth: 120, cssClass: "cell-title", editor: Slick.Editors.Text, validator: requiredFieldValidator, sortable: true,groupTotalsFormatter: sumTotalsFormatter }
			//{id:"finish", name:"Finish", field:"finish"},
			//{id:"effort-driven", name:"Effort Driven", field:"effortDriven"}
		];

		var options = {
 editable: true,
  enableAddRow: true,
  enableCellNavigation: true,
  asyncEditorLoading: true,
  forceFitColumns: false,
  topPanelHeight: 25		
           
		};
var sortcol = "title";
var sortdir = 1;
var smowneridThreshold = 0;
var searchString = "";
var prevPercentCompleteThreshold = 0;

function myFormatter(row, cell, value, columnDef, dataContext) {

  //return "<a href='index.php?module=Project&parenttab=Support&action=DetailView&record=1181'>Click</a>";
return '<a href="index.php?module=Project&parenttab=Support&action=DetailView&record=' + dataContext['pid'] + '">' + value + '</a>';
}
function avgTotalsFormatter(totals, columnDef) {
  var val = totals.avg && totals.avg[columnDef.field];
  if (val != null) {
    return "avg: " + Math.round(val) + "%";
  }
  return "";
}

function sumTotalsFormatter(totals, columnDef) {
  var val = totals.sum && totals.sum[columnDef.field];
  if (val != null) {
    return "total: " + ((Math.round(parseFloat(val)*100)/100));
  }
  return "";
}

function requiredFieldValidator(value) {
  if (value == null || value == undefined || !value.length) {
    return {valid: false, msg: "This is a required field"};
  }
  else {
    return {valid: true, msg: null};
  }
}

function myFilter(item, args) {
  if (item["smownerid"] < args.smowneridThreshold) {
    return false;
  }

  if (args.searchString != "" && item["duration"].indexOf(args.searchString) == -1) {
    return false;
  }

  return true;
}

function smowneridSort(a, b) {
  return a["smownerid"] - b["smownerid"];
}

function comparer(a, b) {
  var x = a[sortcol], y = b[sortcol];
  return (x == y ? 0 : (x > y ? 1 : -1));
}

function toggleFilterRow() {
  grid.setTopPanelVisibility(!grid.getOptions().showTopPanel);
}
function groupByDuration() {
  dataView.setGrouping({
    getter: "smownerid",
    formatter: function (g) {
      return "smownerid:  " + g.value + "  <span style='color:green'>(" + g.count + " items)</span>";
    },
    aggregators: [
      new Slick.Data.Aggregators.Avg("smownerid"),
    //  new Slick.Data.Aggregators.Sum("cost")
    ],
    aggregateCollapsed: true
  });
}
function groupByDurationOrderByCount(aggregateCollapsed) {
  dataView.setGrouping({
    getter: "smownerid",
    formatter: function (g) {
      return "smownerid:  " + g.value + "  <span style='color:green'>(" + g.count + " items)</span>";
    },
    comparer: function (a, b) {
      return a.count - b.count;
    },
    aggregators: [
      new Slick.Data.Aggregators.Avg("smownerid"),
     // new Slick.Data.Aggregators.Sum("cost")
    ],
    aggregateCollapsed: true
  });
}
function groupByDurationEffortDriven() {
  dataView.setGrouping([
    {
      getter: "smownerid",
      formatter :function (g) {
        return "smownerid:  " + g.value + "  <span style='color:green'>(" + g.count + " items)</span>";
      },
      aggregators: [
        new Slick.Data.Aggregators.Sum("smownerid"),
      //  new Slick.Data.Aggregators.Sum("cost")
      ],
      aggregateCollapsed: true
    },
    {
      getter: "createdtime",
      formatter :function (g) {
        return "Create dt time:  " + (g.value ) + "  <span style='color:green'>(" + g.count + " items)</span>";
      },
      aggregators: [
        new Slick.Data.Aggregators.Avg("smownerid"),
        //new Slick.Data.Aggregators.Sum("cost")
      ],
      collapsed: true
    }
  ]);
}
jQuery(".grid-header .ui-icon")
        .addClass("ui-state-default ui-corner-all")
        .mouseover(function (e) {
          jQuery(e.target).addClass("ui-state-hover")
        })
        .mouseout(function (e) {
          jQuery(e.target).removeClass("ui-state-hover")
        });
var _addRowHandler = function() {
     --dummy_id;
    var newRow    = fullColumn,
          newId     = DataView.getLength();
          newRow.id = dummy_id; 
    DataView.insertItem(0 , newRow);
    // Grid.invalidate(); // scenario A without this line. 


}
jQuery(function() {
var groupItemMetadataProvider = new Slick.Data.GroupItemMetadataProvider();
        var data = [];
        <?php echo $data?> //This is where we echo the PHP variable $data which contains our JavaScript array as a string.


	

			dataView = new Slick.Data.DataView({ groupItemMetadataProvider: groupItemMetadataProvider,inlineFilters: true });
  grid = new Slick.Grid("#myGrid", dataView, columns, options);
  grid.setSelectionModel(new Slick.RowSelectionModel());
 // register the group item metadata provider to add expand/collapse group handlers
  grid.registerPlugin(groupItemMetadataProvider);
  grid.setSelectionModel(new Slick.CellSelectionModel());

  var pager = new Slick.Controls.Pager(dataView, grid, jQuery("#pager"));
  var columnpicker = new Slick.Controls.ColumnPicker(columns, grid, options);


  // move the filter panel defined in a hidden div into grid top panel
  jQuery("#inlineFilterPanel")
      .appendTo(grid.getTopPanel())
      .show();

  grid.onCellChange.subscribe(function (e, args) {
    dataView.updateItem(args.item.id, args.item);
  });

  grid.onAddNewRow.subscribe(function (e, args) {
    var item = {"num": data.length, "id": "new_" + (Math.round(Math.random() * 10000)), "title": "New task", "duration": "1 day", "smownerid": 0, "start": "01/01/2009", "finish": "01/01/2009", "effortDriven": false};
    jQuery.extend(item, args.item);
    dataView.addItem(item);
  });

  grid.onKeyDown.subscribe(function (e) {
    // select all rows on ctrl-a
    if (e.which != 65 || !e.ctrlKey) {
      return false;
    }

    var rows = [];
    for (var i = 0; i < dataView.getLength(); i++) {
      rows.push(i);
    }

    grid.setSelectedRows(rows);
    e.preventDefault();
  });

  grid.onSort.subscribe(function (e, args) {
    sortdir = args.sortAsc ? 1 : -1;
    sortcol = args.sortCol.field;

    if (jQuery.browser.msie && jQuery.browser.version <= 8) {
      // using temporary Object.prototype.toString override
      // more limited and does lexicographic sort only by default, but can be much faster

      var smowneridValueFn = function () {
        var val = this["smownerid"];
        if (val < 10) {
          return "00" + val;
        } else if (val < 100) {
          return "0" + val;
        } else {
          return val;
        }
      };

      // use numeric sort of % and lexicographic for everything else
      dataView.fastSort((sortcol == "smownerid") ? smowneridValueFn : sortcol, args.sortAsc);
    } else {
      // using native sort with comparer
      // preferred method but can be very slow in IE with huge datasets
      dataView.sort(comparer, args.sortAsc);
    }
  });

  // wire up model events to drive the grid
  dataView.onRowCountChanged.subscribe(function (e, args) {
    grid.updateRowCount();
    grid.render();
  });

  dataView.onRowsChanged.subscribe(function (e, args) {
    grid.invalidateRows(args.rows);
    grid.render();
  });

  dataView.onPagingInfoChanged.subscribe(function (e, pagingInfo) {
    var isLastPage = pagingInfo.pageNum == pagingInfo.totalPages - 1;
    var enableAddRow = isLastPage || pagingInfo.pageSize == 0;
    var options = grid.getOptions();

    if (options.enableAddRow != enableAddRow) {
      grid.setOptions({enableAddRow: enableAddRow});
    }
  });


  var h_runfilters = null;

  // wire up the slider to apply the filter to the model
  jQuery("#pcSlider,#pcSlider2").slider({
    "range": "min",
    "slide": function (event, ui) {
      Slick.GlobalEditorLock.cancelCurrentEdit();

      if (smowneridThreshold != ui.value) {
        window.clearTimeout(h_runfilters);
        h_runfilters = window.setTimeout(updateFilter, 10);
        smowneridThreshold = ui.value;
      }
    }
  });


  // wire up the search textbox to apply the filter to the model
  jQuery("#txtSearch,#txtSearch2").keyup(function (e) {
    Slick.GlobalEditorLock.cancelCurrentEdit();

    // clear on Esc
    if (e.which === 27) {
      this.value = "";
    }

    searchString = this.value;
    updateFilter();
  });

  function updateFilter() {
    dataView.setFilterArgs({
      smowneridThreshold: smowneridThreshold,
      searchString: searchString
    });
    dataView.refresh();
  }

  jQuery("#btnSelectRows").click(function () {
    if (!Slick.GlobalEditorLock.commitCurrentEdit()) {
      return;
    }

    var rows = [];
    for (var i = 0; i < 10 && i < dataView.getLength(); i++) {
      rows.push(i);
    }

    grid.setSelectedRows(rows);
  });
  function filterAndUpdate() {
    var isNarrowing = smowneridThreshold > prevPercentCompleteThreshold;
    var isExpanding = smowneridThreshold < prevPercentCompleteThreshold;
    var renderedRange = grid.getRenderedRange();

    dataView.setFilterArgs({
      smownerid: smowneridThreshold
    });
    dataView.setRefreshHints({
      ignoreDiffsBefore: renderedRange.top,
      ignoreDiffsAfter: renderedRange.bottom + 1,
      isFilterNarrowing: isNarrowing,
      isFilterExpanding: isExpanding
    });
    dataView.refresh();

    prevPercentCompleteThreshold = smowneridThreshold;
  }

  // initialize the model after all the events have been hooked up
  dataView.beginUpdate();
  dataView.setItems(data);
  dataView.setFilterArgs({
    smowneridThreshold: smowneridThreshold,
    searchString: searchString
  });
  dataView.setFilter(myFilter);
groupByDuration();
  dataView.endUpdate();

  // if you don't want the items that are not visible (due to being filtered out
  // or being on a different page) to stay selected, pass 'false' to the second arg
  dataView.syncGridSelection(grid, true);

  jQuery("#gridContainer").resizable();




		});
	
		</script>

	<?php }} ?>