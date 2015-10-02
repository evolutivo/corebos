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
<script src="Smarty/angular/angular.min.js"></script>
<script  src="Smarty/angular/ng-table.js"></script>
<link data-require="ng-table@*" data-semver="0.3.0" rel="stylesheet" href="http://bazalt-cms.com/assets/ng-table/0.3.0/ng-table.css" />
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
                        ng-if="isAdmin=='on'"  ng-click="open_addnew(reports);"/>
                    <button class="btn btn-warning" ng-click="cancel()" ng-show="show_inline">List All Reports</button>
                    <span style="padding:400px;text-align:center;" ng-show="show_inline"><b>{literal}{{name}}{/literal}</b></span>
                    <div id="inline_cbApps" ng-show="show_inline">
                        <div class="modal-header">
                             <button class="btn btn-warning" ng-show="pivot_type=='report'" ng-click="put_inline(cbAppid,repid,name,pivot_type,'true')">Recalculate</button>
                             <button class="btn btn-warning" ng-click="export(cbAppid,repid,name,pivot_type)">Export csv</button>
                             
                             <button class="btn btn-warning" ng-if="isAdmin=='on'" ng-click="save_config(cbAppid)" >Save Configuration</button>
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
                                 | <b><span style="text-align:left;" ng-click="edit_cbapp(report.cbAppsid)">Edit</span><b/>
                        </div>
                     </td>                
                 </tr>
            </table>
            
         </td>
     </tr>
</table>
                        <script type="text/ng-template" id="open_addnew.html">

<div class="modal-header">
    <h4 class="modal-title">Add new Pivot Configuration</h4>
</div>
<div class="modal-body">    
    <table   width=68% align=center border="0" >
        <tr ng-if="edit_type=='edit'">
            <td align="center">
                <input type="radio" name="type_pivot" value="report" ng-model="type_pivot">Report<br>
                <select id="report_opt" ng-disabled="type_pivot!='report'">
                    {$options}
                </select>
            </td>
            <td align="center">
                <input type="radio" name="type_pivot" value="mv" ng-model="type_pivot" >MV<br>
                <select id="mv_opt" ng-disabled="type_pivot!='mv'">
                    {$options_mv}
                </select>
            </td>
            <td align="center">
                <input type="radio" name="type_pivot" value="elastic" ng-model="type_pivot" >Elastic index<br>
                <select id="elastic_opt" ng-disabled="type_pivot!='elastic'">
                    {$options_elastic}
                </select>
            </td>
        </tr>
        <tr>
            <td align="center">
                <h5>Nome </h5>
            </td>
            <td align="center" colspan="2">
                <input type="text" name="name_pivot" ng-model="name_pivot" >
            </td>
        </tr>
        <tr>
            <td align="center">
                <h5>Descrizione </h5>
            </td>
            <td align="center" colspan="2">
                <input type="text" name="desc_pivot" ng-model="desc_pivot" >
            </td>
        </tr>

    </table>
</div>
<div class="modal-footer">
   <button class="btn btn-primary" ng-click="new_config(type_pivot,name_pivot,desc_pivot)"  >Add New Config</button> 
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

                        j2("#output"+$scope.cbAppid).pivotUI(mps, {
                                   renderers: j2.extend(
                                    j2.pivotUtilities.renderers, 
                                    j2.pivotUtilities.gchart_renderers, 
                                    j2.pivotUtilities.d3_renderers
                                    ),
                                rows: resp_arr.selectedColumnsX,
                                cols: resp_arr.selectedColumnsY,
                                rendererName: resp_arr.type,
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
                $http.get('index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=deleteReport&cbAppsid='+cbAppid).
                    success(function(data, status) {
                    for(var i = $scope.reports.length - 1; i >= 0; i--){
                        if($scope.reports[i].cbAppsid == cbAppid){
                            $scope.reports.splice(i,1);
                        }
                    }
                    alert('Successfully Deleted');
                  });
            };
            $scope.cancel = function () {
                $scope.show_inline=false;
                $scope.show_list=true;
            };
              
            $scope.save_config = function (cbAppid) {
                var j=0;
                var horiz=Array();
                var hz=document.getElementsByClassName('pvtAxisContainer pvtHorizList pvtCols ui-sortable').item(0).innerHTML.split("<span class=");
                var i=1;
                while(hz[i]!=undefined){
                     //alert(hz[i]);
                     horiz[j]=hz[i].replace('pvtAttr','').replace('"">','');
                     j++;
                     i=i+2;
                }
                var j1=0;
                var vert=Array();
                var v=document.getElementsByClassName('pvtAxisContainer pvtRows ui-sortable').item(0).innerHTML.split("<span class=");
                var i1=1;
                while(v[i1]!=undefined){
                     //alert(hz[i]);
                     vert[j1]=v[i1].replace('pvtAttr','').replace('"">','');
                     j1++;
                     i1=i1+2;
                }
                                
                var selectedY =horiz.join(",");
                var selectedX =vert.join(",");  
                var typebar=document.getElementById('typechart').value;
                var aggr=document.getElementById('aggr').value;
                var aggrdrop='';
                var j1=0;
                var agg_arr=Array();
                if (document.getElementById('aggrdrop')!=undefined)
                {
                    aggrdrop=document.getElementById('aggrdrop').value;
                }
                $http.get('index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction=updateReport&cbAppsid='+cbAppid+'&selectedX='+selectedX+'&selectedY='+selectedY+'&type='+typebar+'&aggr='+aggr+'&aggrdrop='+aggrdrop).
                    success(function(data, status) {
                        alert('Successfully saved');
              });
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
              
              $scope.open_addnew = function (reports) {
                var modalInstance = $modal.open({
                  templateUrl: 'open_addnew.html',
                  controller: 'addnewReport',
                  resolve: {
                    reports :function () {
                      return reports;
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

            j2("#output").pivotUI(mps, {
                   renderers: j2.extend(
                    j2.pivotUtilities.renderers, 
                    j2.pivotUtilities.gchart_renderers, 
                    j2.pivotUtilities.d3_renderers
                    ),
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
.controller('addnewReport',function ($scope,$http,$modalInstance,reports) {

    $scope.type_pivot='report';
    $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
    $scope.new_config = function (type_piv,name_pivot,desc_pivot) {
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
       
       var url='';var typ='';
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
       }
       url='index.php?module=Pivottable&action=PivottableAjax&file=index&cbAction='+typ+'&reportid='+repid+'&reportname='+name_pivot+'&reportdesc='+desc_pivot;
       $http.get(url).
            success(function(data, status) {
                var lastid=data;
                reports.push({'cbAppsid':lastid,'reportid':repid,'reportname':repname,'pivot_type':type_piv});
                $modalInstance.close();

      });
    }
});
{/literal}
</script>
