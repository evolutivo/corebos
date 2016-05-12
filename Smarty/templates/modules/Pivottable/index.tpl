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
 *  Module       : ESClient
 *  Version      : 5.5.0
 *  Author       : OpenCubed.
 *************************************************************************************************/
-->*}
<script  src="Smarty/angular/ng-table.js"></script>
<script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.6.0.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
<script src="Smarty/angular/angular-multi-select.js"></script>  
<link rel="stylesheet" href="Smarty/angular/angular-multi-select.css">

<link rel="stylesheet" type="text/css" href="modules/Pivottable/pivottable-master2/dist/pivot.css">
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/examples/ext/d3.v3.min.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/examples/ext/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var j2=jQuery.noConflict();
</script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/examples/ext/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/examples/ext/jquery.csv-0.71.min.js"></script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/dist/pivot.js"></script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/dist/gchart_renderers.js"></script>
<script type="text/javascript" src="modules/Pivottable/pivottable-master2/dist/d3_renderers.js"></script>
<script src="modules/Pivottable/pivottable-master2/dist/nrecopivot.js"></script>		
<script type="text/javascript">
    {literal}
    var tableToExcel = (function() {            
      var uri = 'data:application/vnd.ms-excel;base64,'
        , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">\n\
<head><!--[if gte mso 9]>\n\
<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>\n\
<x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/>\n\
</x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook>\n\
</xml><![endif]-->\n\
<meta http-equiv="content-type" content="text/plain; charset=UTF-8"/>\n\
</head><body><table>{table}</table></body></html>'
        , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
        , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
      return function(table, name) {
        if (!table.nodeType) table = document.getElementById(table)
        alert(table.innerHTML);
        var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        window.location.href = uri + base64(format(template, ctx))
      }
    })()
    
    function download(strData, strFileName, strMimeType) {
    var D = document,
        A = arguments,
        a = D.createElement("a"),
        d = A[0],
        n = A[1],
        t = A[2] || "text/plain";

    //build download link:
    a.href = "data:" + strMimeType + "," + escape(strData);


    if (window.MSBlobBuilder) {
        var bb = new MSBlobBuilder();
        bb.append(strData);
        return navigator.msSaveBlob(bb, strFileName);
    } /* end if(window.MSBlobBuilder) */



    if ('download' in a) {
        a.setAttribute("download", n);
        a.innerHTML = "downloading...";
        D.body.appendChild(a);
        setTimeout(function() {
            var e = D.createEvent("MouseEvents");
            e.initMouseEvent("click", true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            a.dispatchEvent(e);
            D.body.removeChild(a);
        }, 66);
        return true;
    } /* end if('download' in a) */
    ; //end if a[download]?

    //do iframe dataURL download:
    var f = D.createElement("iframe");
    D.body.appendChild(f);
    f.src = "data:" + (A[2] ? A[2] : "application/octet-stream") + (window.btoa ? ";base64" : "") + "," + (window.btoa ? window.btoa : escape)(strData);
    setTimeout(function() {
        D.body.removeChild(f);
    }, 333);
    return true;
} /* end download library function () */

function tableToExcel2 (table) {
   if (!table.nodeType) table = document.getElementById(table);
   download(table.outerHTML, "table.xls", "application/vnd.ms-excel");
 }
    {/literal}
</script>
<style>
{literal}
.widget {
    margin: 0 1em 0 0;
    background-color: white;
    border: 2px solid #444;
    border-radius: 5px;
    position: relative;
    height: 100%;
    width: 150px;
    text-align:center;
}
.widget-header {
    

}
</style>
{/literal}
<table  border=0 cellspacing=0 cellpadding=0 width=100% class=small >
    <tr><td style="height:7px"></td></tr>
    <tr><td style="height:7px"></td></tr>
    <tr>
        <td style="padding-left:10px;padding-right:50px" class="moduleName" nowrap>
            <a class="hdrLink" href="index.php?action=index&module=Pivottable">Pivot Dashboard</a>
        </td>
    </tr>
</table>

<table border=0 cellspacing=0 cellpadding=0 width=98% align=center ng-controller="Pivottable" height=5000>
     <tr>
        <td valign=top><img src="{'showPanelTopLeft.gif'|@vtiger_imageurl:$THEME}"></td>
    
        <td class="showPanelBg" valign="top" width=100% style="padding:10px;">
             <table   width=100% align=center border="0" >
                <tr><td style="height:2px"><br/><br/>
                    <img src="themes/softed/images/btnL3Add.gif" alt="Add new Pivot Config" 
                        ng-if="isAdmin=='on'"  ng-click="open_addnew(reports,'create','');"/>
                    <button class="btn btn-warning" ng-click="cancel()" ng-show="show_inline">List All Reports</button>
                    <span style="padding:400px;text-align:center;" ng-show="show_inline"><b>{literal}{{name}}{/literal}</b></span>
                    <div id="inline_cbApps" ng-show="show_inline">
                        <div class="modal-header">
                             <button class="btn btn-warning" ng-show="pivot_type=='report'" ng-click="put_inline(cbAppid,repid,name,pivot_type,'true')">Recalculate</button>
                             <button class="btn btn-warning" ng-click="export(cbAppid,repid,name,pivot_type)">Export csv</button>
                             <button class="btn btn-warning"  onclick="tableToExcel2('testTable')">Export Pivot to Excel</button>
                             <button class="btn btn-warning" ng-if="isAdmin=='on'" ng-click="save_config(cbAppid)" >Save Configuration</button>
                             <button class="btn btn-warning" ng-if="isAdmin=='on'" ng-click="save_config_as(cbAppid,repid,reports)" >Save As</button>
                        </div>
                             <div style="height:1100px" id="dyn_content">  
                               </div>
                    </div>
                    </td>
                </tr>
             </table>
             <table   width=68% align=center border="0" ng-show="show_list">
                <tr><td style="height:2px"><br/><br/></td>
                </tr>
                <tr>               
                     <td style="width:30px;" ng-repeat="report in reports">
                         <div class="widget" >
                             <div class="widget-header">
                                 <p>
                                     <b>{literal}{{report.reportname}}{/literal}</b>
                                     
                                     <!--<b><span style="text-align:right;"  ng-click="open(report.cbAppsid,report.reportid,report.reportname,report.pivot_type)">Popup</span><b/>-->
                                 </p>
                             </div>
                                 <br/><img src="modules/Pivottable/report_cbApp1.jpg " ng-click="put_inline(report.cbAppsid,report.reportid,report.reportname,report.pivot_type,'false');" style="width:100px;height:80px;" />
                             <br/>{literal}{{report.desc_pivot}}{/literal}
                             <br/><b><span style="text-align:left;color:red" ng-click="delete(report.cbAppsid)">Delete</span><b/>
                                 | <b><span style="text-align:left;" ng-click="open_addnew(reports,'edit',report.cbAppsid)">Edit</span><b/>
                        </div>
                     </td>                
                 </tr>
            </table>
            
         </td>
     </tr>
</table>
<script type="text/ng-template" id="open_addnew.html">

<div class="modal-header">
    <h4 class="modal-title">{literal}{{action}}{/literal} Pivot Configuration</h4>
</div>
<div class="modal-body">    
    <table   width=68% align=center border="0" >
        <tr ng-if="edit_type=='create'">
            <td align="center">
                <input type="radio" name="type_pivot.name" value="report" ng-model="type_pivot.name">Report<br>
                <select id="report_opt" ng-disabled="type_pivot.name!='report'">
                    {$options}
                </select>
            </td>
            <td align="center">
                <input type="radio" name="type_pivot.name" value="mv" ng-model="type_pivot.name" >MV<br>
                <select id="mv_opt" ng-disabled="type_pivot.name!='mv'">
                    {$options_mv}
                </select>
            </td>
            <td align="center">
                <input type="radio" name="type_pivot.name" value="elastic" ng-model="type_pivot.name" >Elastic index<br>
                <select id="elastic_opt" ng-disabled="type_pivot.name!='elastic'" ng-model="elastic_opt.id">
                    {$options_elastic}
                </select>
            </td>
        </tr>
        <tr>
            <td align="center">
                <h5>Nome </h5>
            </td>
            <td align="left" colspan="2">
                <input type="text" name="name_pivot" ng-model="name_pivot" >
            </td>
        </tr>
        <tr>
            <td align="center">
                <h5>Descrizione </h5>
            </td>
            <td align="left" colspan="2">
                <input type="text" name="desc_pivot" ng-model="desc_pivot" >
            </td>
        </tr>
        <tr ng-if="type_pivot.name=='elastic'">
            <td align="center">
                <h5>Type Elastic</h5>
            </td>
            <td align="left" colspan="2">
                <select id="elastic_types" ng-model="elastic_type.name" ng-options="opt.typename for opt  in elastictypes | filter_elastic_type:elastic_opt track by opt.typename">
                </select>
            </td>
        </tr>

    </table>
</div>
<div class="modal-footer">
   <button class="btn btn-primary" ng-click="new_config(type_pivot.name,name_pivot,desc_pivot,elastic_type)" ng-if="edit_type=='create'" >Add New Config</button> 
   <button class="btn btn-primary" ng-click="edit_config(name_pivot,desc_pivot,elastic_type)" ng-if="edit_type=='edit'" >Edit Config</button>
   <button class="btn btn-primary" ng-click="edit_config(name_pivot,desc_pivot)" ng-if="edit_type=='saveas'" >Save As</button>
   <button class="btn btn-warning" ng-click="cancel()">Close</button>
</div>
</script>
<style>
{literal}
   .modal{
    width:1300px;
    margin-left:-650px;
}
{/literal}
</style>
<script>
{literal}
       
google.load("visualization", "1", {packages:["corechart", "charteditor"]});
    
angular.module('demoApp',['ngTable','ui.bootstrap','multi-select']) 
.filter('filter_elastic_type', function() {
      return function(elastictypes,elastic_opt) {
        var filterEvent = [];
        for (var i = 0;i < elastictypes.length; i++){
            var curr=elastictypes[i];
            for (var j = 0;j < curr.length; j++){
                if(curr[j]['id']===elastic_opt.id){
                    filterEvent.push(curr[j]);
                }
            }
        }
        return filterEvent;
    }
}
)
.controller('Pivottable', function($scope, $http, $modal) {

            $scope.show_inline=false;
            $scope.show_list=true;
            $scope.reports={/literal}{$reports}{literal};
            $scope.isAdmin='{/literal}{$isAdmin}{literal}';
            
            $scope.put_inline =  function(cbAppid,repid,name,pivot_type,recalculate) {
                $scope.show_inline=true;
                $scope.show_list=false;
                $scope.repid=repid;
                $scope.cbAppid=cbAppid;
                $scope.id = repid;
                $scope.name = name;
                $scope.pivot_type = pivot_type;
                document.getElementById('dyn_content').innerHTML=
                        '<div id="output'+$scope.cbAppid+'" style="margin: 30px;"></div>';
                
                var url='';
                var recalc='&recalc='+recalculate;
                if($scope.pivot_type=='report'){
                    url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAppsid='+cbAppid+'&reportid='+repid+'&cbAction=retrieveReport';
                }
                else if($scope.pivot_type=='mv'){
                    url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAppsid='+cbAppid+'&reportid='+repid+'&cbAction=retrieveMV';
                }
                else if($scope.pivot_type=='elastic'){
                    url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAppsid='+cbAppid+'&reportid='+repid+'&cbAction=retrieveElastic';
                }
                    

                $http.get(url+recalc).
                    success(function(data, status) {
                        var resp_arr=data;
                        var derivers = j2.pivotUtilities.derivers;
                        j2.getJSON("report"+$scope.cbAppid+".json", function(mps) {
                        var nrecoPivotExt = new NRecoPivotTableExtensions({
			drillDownHandler: function (dataFilter) {
				console.log(dataFilter);
				
				var filterParts = [];
				for (var k in dataFilter) {
					filterParts.push(k+"="+dataFilter[k]);
				}
				alert( filterParts.join(", "));	
				
			}
		});
		//,"Line Chart",Bar Chart,Stacked Bar Chart,Area Chart,Scatter Chart
		var stdRendererNames = ["Table","Table Barchart","Heatmap","Row Heatmap","Col Heatmap"];
		var wrappedRenderers = j2.extend( {}, j2.pivotUtilities.renderers);
		j2.each(stdRendererNames, function() {
			var rName = this;
			wrappedRenderers[rName] = nrecoPivotExt.wrapTableRenderer(wrappedRenderers[rName]);
		});
                        j2("#output"+$scope.cbAppid).pivotUI(mps, {
                                renderers: wrappedRenderers,
                                rows: resp_arr.selectedColumnsX,
                                cols: resp_arr.selectedColumnsY,
                                rendererName: resp_arr.type,//aggregatorKeys
                                aggregatorName: resp_arr.aggregatorName,
                                vals: resp_arr.vals
                            });
                        });
                    });

            };
                        
            $scope.export =  function(cbAppid,repid,name,pivot_type) {
                $http.get('index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=exportReport&cbAppsid='+cbAppid).
                    success(function(data, status) {
                    alert('Successfully Exported');
                    var resp_arr=data;
                    window.open(resp_arr,'_blank');
                  });
            };
            
            $scope.delete =  function(cbAppid) {
                if(confirm('Are you sure you want to delete?'))
                {
                    $http.get('index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=deleteReport&cbAppsid='+cbAppid).
                    success(function(data, status) {
                    for(var i = $scope.reports.length - 1; i >= 0; i--){
                        if($scope.reports[i].cbAppsid == cbAppid){
                            $scope.reports.splice(i,1);
                        }
                    }
                    alert('Successfully Deleted');
                  });
                }
            };
            $scope.cancel = function () {
                $scope.show_inline=false;
                $scope.show_list=true;
            };
              
            $scope.save_config = function (cbAppid) {
                var horiz=Array();
                var hz=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtCols ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<hz.length;j++){
                    var pos=hz[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=hz[j].substr(0,pos);
                        horiz[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var vert=Array();
                var v=document.getElementsByClassName('pvtAxisContainer pvtRows ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<v.length;j++){
                    var pos=v[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=v[j].substr(0,pos);
                        vert[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var vals=Array();
                var a=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtVals ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<a.length;j++){
                    var pos=a[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=a[j].substr(0,pos);
                        vals[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var aggrcount=Array();
                var a=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtVals ui-sortable').item(0).innerHTML.split('<select class="');
                var i=0;console.log(a);
                for(j=1;j<a.length;j++){
                    var pos=a[j].indexOf('"');
                    var selectname=a[j].substr(0,pos);//alert(pos);alert(selectname);
                    var t=document.getElementsByClassName(selectname).item(0).options[document.getElementsByClassName(selectname).item(0).selectedIndex].value;
                    //alert(t);
                    aggrcount[i]=t;
                    i++;
                }
                var selectedY =horiz.join(",");
                var selectedX =vert.join(",");  
                var typebar=document.getElementById('typechart').value;
                var aggrdrop=vals.join(",");
                var aggr=aggrcount.join(",");
                $http.get('index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=updateReport&cbAppsid='+cbAppid+'&selectedX='+selectedX+'&selectedY='+selectedY+'&type='+typebar+'&aggr='+aggr+'&aggrdrop='+aggrdrop).
                    success(function(data, status) {
                        alert('Successfully saved');
              });
            };
            
            $scope.save_config_as = function (cbAppid,repid,reports) {
                var horiz=Array();
                var hz=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtCols ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<hz.length;j++){
                    var pos=hz[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=hz[j].substr(0,pos);
                        horiz[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var vert=Array();
                var v=document.getElementsByClassName('pvtAxisContainer pvtRows ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<v.length;j++){
                    var pos=v[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=v[j].substr(0,pos);
                        vert[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var vals=Array();
                var a=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtVals ui-sortable').item(0).innerHTML.split("<nobr>");
                var i=0;
                for(j=0;j<a.length;j++){
                    var pos=a[j].indexOf('</nobr>');
                    if(pos !==-1){
                        var str=a[j].substr(0,pos);
                        vals[i]=str;//.replace('pvtAttr','').replace('"">','');
                        i++;
                    }                     
                }
                var aggrcount=Array();
                var a=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtVals ui-sortable').item(0).innerHTML.split('<select class="');
                var i=0;console.log(a);
                for(j=1;j<a.length;j++){
                    var pos=a[j].indexOf('"');
                    var selectname=a[j].substr(0,pos);//alert(pos);alert(selectname);
                    var t=document.getElementsByClassName(selectname).item(0).options[document.getElementsByClassName(selectname).item(0).selectedIndex].value;
                    //alert(t);
                    aggrcount[i]=t;
                    i++;
                }
                var selectedY =horiz.join(",");
                var selectedX =vert.join(",");  
                var typebar=document.getElementById('typechart').value;
                var aggrdrop=vals.join(",");
                var aggr=aggrcount.join(",");
                $scope.open_saveas(cbAppid,reports,repid,selectedX,selectedY,typebar,aggr,aggrdrop);
            };
            
              $scope.open = function (cbAppid,repid,name,pivot_type) {
                var modalInstance = $modal.open({
                  templateUrl: 'Smarty/templates/modules/Pivottable/cbAppTemplate.html',
                  controller: 'cbApp',
                  resolve: {
                    name :function () {
                      return name;
                    },
                    repid :function () {
                      return repid;
                    },
                    cbAppid :function () {
                      return cbAppid;
                    },
                    pivot_type :function () {
                      return pivot_type;
                    }
                  }
                });

                modalInstance.result.then(function (selectedItem) {
                  $scope.selected = selectedItem;
                }, function () {
                  //$log.info('Modal dismissed at: ' + new Date());
                });
              };
              
              $scope.open_addnew = function (reports,edit_type,cbAppsid) {
                var modalInstance = $modal.open({
                  templateUrl: 'open_addnew.html',
                  controller: 'addnewReport',
                  resolve: {
                    reports :function () {
                      return reports;
                    },
                    edit_type :function () {
                      return edit_type;
                    },
                    cbAppsid :function () {
                      return cbAppsid;
                    }
                  }
                });

                modalInstance.result.then(function (selectedItem) {
                  $scope.selected = selectedItem;
                }, function () {
                  //$log.info('Modal dismissed at: ' + new Date());
                });
              };
              
              $scope.open_saveas = function (cbAppid,reports,repid,selectedX,selectedY,typebar,aggr,aggrdrop) {
                var modalInstance = $modal.open({
                  templateUrl: 'open_addnew.html',
                  controller: 'saveasReport',
                  resolve: {
                    cbAppid :function () {
                      return cbAppid;
                    },
                    reports :function () {
                      return reports;
                    },
                    repid :function () {
                      return repid;
                    },
                    selectedX :function () {
                      return selectedX;
                    },
                    selectedY :function () {
                      return selectedY;
                    },
                    typebar :function () {
                      return typebar;
                    },
                    aggr :function () {
                      return aggr;
                    },
                    aggrdrop :function () {
                      return aggrdrop;
                    }
                  }
                });

                modalInstance.result.then(function (selectedItem) {
                  $scope.selected = selectedItem;
                }, function () {
                  //$log.info('Modal dismissed at: ' + new Date());
                });
              };
        
});
angular.module('demoApp')
.controller('cbApp',function ($scope,$http,$modalInstance,cbAppid,repid,name,pivot_typ) {

    var url='';
    if(pivot_typ=='report'){
        url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAppsid='+cbAppid+'&reportid='+repid+'&cbAction=retrieveReport';
    }
    else{
        url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAppsid='+cbAppid+'&reportid='+repid+'&cbAction=retrieveMV';
    }
        $http.get(url).
        success(function(data, status) {
            var resp_arr=data;
            var derivers = j2.pivotUtilities.derivers;
            j2.getJSON("report"+repid+".json", function(mps) {
            var nrecoPivotExt = new NRecoPivotTableExtensions({
			drillDownHandler: function (dataFilter) {
				console.log(dataFilter);
				
				var filterParts = [];
				for (var k in dataFilter) {
					filterParts.push(k+"="+dataFilter[k]);
				}
				alert( filterParts.join(", "));	
				
			}
		});
		
		var stdRendererNames = ["Table","Table Barchart","Heatmap","Row Heatmap","Col Heatmap"];
		var wrappedRenderers = $.extend( {}, $.pivotUtilities.renderers);
		j2.each(stdRendererNames, function() {
			var rName = this;
			wrappedRenderers[rName] = nrecoPivotExt.wrapTableRenderer(wrappedRenderers[rName]);
		});
            j2("#output").pivotUI(mps, {
                renderers: wrappedRenderers,
                rows: resp_arr.selectedColumnsX,
                cols: resp_arr.selectedColumnsY,
                rendererName: resp_arr.type
            });
        });
      });

      $scope.id = repid;
      $scope.name = name;
      // edit selected record
      $scope.setEditId =  function(user) {
           
            };

      $scope.ok = function () {
        $modalInstance.close($scope.selected.item);
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
})
.controller('addnewReport',function ($scope,$http,$modalInstance,reports,edit_type,cbAppsid) {

    $scope.name_pivot='';
    $scope.desc_pivot='';
    $scope.elastic_type={name:''};
    $scope.elastic_opt={id:''};
    $scope.pivot_type='';
    $scope.type_pivot={name:'report'};
    $scope.edit_type=edit_type;
    $scope.cbAppsid=cbAppsid;
    $scope.action=(edit_type=='edit' ? 'Edit' : 'Add');
    $scope.elastictypes={};
    
    for(var i = reports.length - 1; i >= 0; i--){
        if(reports[i].cbAppsid == $scope.cbAppsid){
            $scope.name_pivot=reports[i]['reportname'];
            $scope.desc_pivot=reports[i]['desc_pivot'];
            $scope.pivot_type=reports[i]['pivot_type'];
            if($scope.pivot_type=='elastic'){
                $scope.type_pivot={name:'elastic'};
                $scope.elastic_type.name={typename:reports[i]['elastic_type'],id:reports[i]['reportid']};
                $scope.elastic_opt={id:reports[i]['reportid']};
            }
        }
    }
    
    var url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=index_types';
    $http.get(url).
        success(function(data, status) {
            $scope.elastictypes=data;
    });
      
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
    $scope.new_config = function (type_piv,name_pivot,desc_pivot,elastic_type) {
       var repid=document.getElementById('report_opt').options[document.getElementById('report_opt').selectedIndex].value;
       var repname=document.getElementById('report_opt').options[document.getElementById('report_opt').selectedIndex].text;
       if(document.getElementById('mv_opt').selectedIndex!=-1){
           var mvid=document.getElementById('mv_opt').options[document.getElementById('mv_opt').selectedIndex].value;
           var mvname=document.getElementById('mv_opt').options[document.getElementById('mv_opt').selectedIndex].text;
       }
       if(document.getElementById('elastic_opt').selectedIndex!=-1){
           var elasticid=document.getElementById('elastic_opt').options[document.getElementById('elastic_opt').selectedIndex].value;
           var elasticname=document.getElementById('elastic_opt').options[document.getElementById('elastic_opt').selectedIndex].text;
       }
       
       var url='';var typ='';var elastic_params='';
       if(type_piv==='report')
           typ='newReport';
       else if(type_piv==='mv'){
           typ='newMV';
           repid=mvid;
           repname=mvname;
       }
       else if (type_piv==='elastic'){
           typ='newElastic';
           repid=elasticid;
           repname=elasticname;
           var elastic_p=elastic_type.name.typename;//document.getElementById('elastic_types').options[document.getElementById('elastic_types').selectedIndex].value;
           elastic_params='&elastic_type='+elastic_p;
       }
       if(name_pivot!='')repname=name_pivot;
       url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction='+typ+'&reportid='+repid+'&reportname='+name_pivot+'&reportdesc='+desc_pivot+elastic_params;
       $http.get(url).
            success(function(data, status) {
                var lastid=data;
                reports.push({'cbAppsid':lastid,'reportid':repid,'reportname':repname,'pivot_type':type_piv,'desc_pivot':desc_pivot,'elastic_type':elastic_p});
                $modalInstance.close();

      });
    }
    
    $scope.edit_config = function (name_pivot,desc_pivot,elastic_type) {
       var url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=updateReportName&cbAppsid='+$scope.cbAppsid+'&reportname='+name_pivot+'&reportdesc='+desc_pivot+'&elastic_type='+elastic_type.name.typename;
       $http.get(url).
            success(function(data, status) {
                var lastid=data;
                for(var i = reports.length - 1; i >= 0; i--){
                    if(reports[i].cbAppsid == $scope.cbAppsid){
                        reports[i]['reportname']=name_pivot;
                        reports[i]['desc_pivot']=desc_pivot;
                        reports[i]['elastic_type']=elastic_type.name.typename;
                    }
                }
                $modalInstance.close();

      });
    }
})
.controller('saveasReport',function ($scope,$http,$modalInstance,cbAppid,reports,repid,selectedX,selectedY,typebar,aggr,aggrdrop) {

    $scope.name_pivot='';
    $scope.desc_pivot='';
    $scope.type_pivot='report';
    $scope.reports=reports;
    $scope.repid=repid;
    $scope.selectedX=selectedX;
    $scope.selectedY=selectedY;
    $scope.typebar=typebar;
    $scope.aggr=aggr;
    $scope.aggrdrop=aggrdrop;
    $scope.action='Save As';
    $scope.edit_type='saveas';
    
    for(var i = reports.length - 1; i >= 0; i--){
        if(reports[i].cbAppsid == cbAppid){
            $scope.name_pivot=reports[i]['reportname'];
            $scope.desc_pivot=reports[i]['desc_pivot'];
        }
    }
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
    
    $scope.edit_config = function (name_pivot,desc_pivot) {
       var url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=saveasReport&cbAppsid='+cbAppid+'&reportid='+$scope.repid+'&reportname='+name_pivot+'&reportdesc='+desc_pivot+'&selectedX='+selectedX+'&selectedY='+selectedY+'&type='+typebar+'&aggr='+aggr+'&aggrdrop='+aggrdrop;
       $http.get(url).
            success(function(data, status) {
                var dt=data.split('@@');
                var lastid=dt[0];
                var typ=dt[1];
                reports.push({'cbAppsid':lastid,'reportid':repid,'reportname':name_pivot,'pivot_type':typ,'desc_pivot':desc_pivot});
                $modalInstance.close();

      });
    }
});
{/literal}
</script>
