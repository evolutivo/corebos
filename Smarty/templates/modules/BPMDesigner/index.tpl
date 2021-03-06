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
 *  Module       : BPMDesigner
 *  Version      : 5.5.0
 *  Author       : OpenCubed.
 *************************************************************************************************/
-->*}
<script type="text/javascript" src="Smarty/templates/modules/BPMDesigner/cytoscape/dist/cytoscape.js"></script>
<script src="https://cdn.rawgit.com/cpettitt/dagre/v0.7.4/dist/dagre.min.js"></script>
<script src="https://cdn.rawgit.com/cytoscape/cytoscape.js-dagre/1.1.2/cytoscape-dagre.js"></script>
<link rel="stylesheet" href="modules/BPMDesigner/BPMDesigner.css" type="text/css" />

<table width=96% align=center border="0" ng-app="demoApp" ng-controller="BPMDesigner" style="padding:10px;">
    <tr><td style="height:2px"><br/><br/></td></tr>
    <tr>
        <td style="padding-left:20px;padding-right:50px" class="moduleName" nowrap colspan="2">
            BPM Designer</td>
     </tr>
     <tr>  
	<td class="showPanelBg" valign="top" style="padding:20px;" width=96%>
            <div class="demo" style="display: flex;flex-direction: row;">
                <div id="cy"></div>
                <div id="params" >
                    <input type="button" value="{'SaveGraphConfig'|@getTranslatedString:'SaveGraphConfig'}" id="add_node" class="crmbutton small create" ng-click="saveGraph();" /> 
                    <div id="blocks">
                        <b>{'ProcTmp'|@getTranslatedString:'ProcTmp'} </b>
                        <select id="processtemplate" ng-options="op.id as op.name for op in procestempList" ng-model="procestemp" ng-click="reloadContent()"></select>
                    </div>
                    <button class="accordion">{'Addstatus'|@getTranslatedString:'Addstatus'}</button>
                    <div class="panel_acc">
                        <p>
                        <input type="text" id="status" ng-model="status" />
                        <input type="button" value="{'Addstatus'|@getTranslatedString:'Addstatus'}" id="add_node" class="crmbutton small create" ng-click="addStatus();"/> 
                        </p>
                    </div>
                    <button class="accordion">{'CrFlow'|@getTranslatedString:'CrFlow'}</button>
                    <div class="panel_acc">
                        <p>
                        {'Start'|@getTranslatedString:'Start'}<select ng-options="op for op in allnodes" ng-model="start_status" ></select><br/>
                        {'End'|@getTranslatedString:'End'}<select ng-options="op for op in allnodes" ng-model="end_status" ></select><br/>
                        <input type="button" value="{'CrFlow'|@getTranslatedString:'CrFlow'}" id="add_node" class="crmbutton small create" ng-click="addRelation();" /> 
                        </p>
                    </div>
                    <button class="accordion">{'Delete'|@getTranslatedString:'Delete'}</button>
                    <div class="panel_acc">
                        <p>
                        <input type="button" value="{'DeleteNode'|@getTranslatedString:'DeleteNode'}" id="add_node" class="crmbutton small create" ng-click="deleteNode();" /> 
                        <input type="button" value="{'DeleteEdge'|@getTranslatedString:'DeleteEdge'}" id="add_node" class="crmbutton small create" ng-click="deleteEdge();" /> 
                        </p>
                    </div>
                </div>                
            </div>
        </td>
     </tr>
</table>
<script>
{literal}
var acc = document.getElementsByClassName("accordion");
var i;
console.log(acc.length);
for (i = 0; i < acc.length; i++) {
  acc[i].onclick = function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
      panel.style['padding-top'] = "0px";
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
      panel.style['padding-top'] = "5px";
    } 
  }
}
{/literal}
</script>
<style>
{literal}
#cy {
    width: 80%;
    height: 700px;
    border: 1px solid #ccc;
}
#params {
    width: 20%;
    height: 700px;
    border: 1px solid #ccc;
}
#blocks {
    border: 1px solid #ccc;
    padding:5px;
    margin-top:15px;
}{/literal}
</style>
<script>
{literal}
//var cytoscape = require('cytoscape');modules/Calendar4You/jquery/jquery-1.9.1.min.js
function test(cy){
    var collection = cy.collection();
    cy.nodes().on("click", function(){
      collection = collection.add(this);
    });
    console.log(collection);
}
angular.module('demoApp')
.controller('BPMDesigner', function($scope, $http) {
    $scope.procestemp='{/literal}{$PROCESSTEMP_ID}{literal}';
    $scope.procestempList={/literal}{$PROCESSTEMP|@json_encode}{literal};   
    $scope.status='';
    $scope.allnodes=[];
    $scope.reloadContent=  function() {
        $http.get('index.php?module=BPMDesigner&action=BPMDesignerAjax&file=index&kaction=reload&processTemp='+$scope.procestemp).
                success(function(data, status) {
                    $scope.allnodes=data.allnodes;
                    var cy=cytoscape({
                        container: document.getElementById('cy'),
                        layout: {
                                name: 'preset'
                        },
                        style: [
                                {
                                    selector: 'node',
                                    style: {
                                            'content': 'data(id)',
                                            'text-opacity': 0.5,
                                            'text-valign': 'center',
                                            'text-halign': 'right',
                                            'background-color': '#11479e'
                                    }
                                },
                                {
                                    selector: 'edge',
                                    style: {
                                            'width': 4,
                                            'target-arrow-shape': 'triangle',
                                            'line-color': '#9dbaea',
                                            'target-arrow-color': '#9dbaea'
                                    }
                                }
                        ],
                        elements: 
                            data.nodes
                        });
                    //cy.reset();
                    cy.zoom(1);
                    cy.pan({
                      x: 50,
                      y: 50 
                    });
                    cy.on('click', 'node', function(evt){
                          console.log(  this.position() );
                    });
                    $scope.addStatus=function(){
                        cy.add([
                            { group: "nodes", data: { id: $scope.status }, position: { x: 0, y: 0 } }
                        ]);
                        $scope.allnodes.push($scope.status);
                    };
                    $scope.deleteNode=function(){
                        if(confirm("{/literal}{'AreYouSure'|@getTranslatedString:'AreYouSure'}{literal}")){
                        var graph=cy.json();
                        var params=encodeURIComponent(JSON.stringify(graph));
                        $http.get('index.php?module=BPMDesigner&action=BPMDesignerAjax&file=operations&kaction=deleteNode&models='+params).
                                success(function(data, status) {
                                    alert("{/literal}{'Done'|@getTranslatedString:'Done'}{literal}");
                                });
                    }
                    };
                    $scope.deleteEdge=function(){
                        if(confirm("{/literal}{'AreYouSure'|@getTranslatedString:'AreYouSure'}{literal}")){
                        var graph=cy.json();
                        var params=encodeURIComponent(JSON.stringify(graph));
                        $http.get('index.php?module=BPMDesigner&action=BPMDesignerAjax&file=operations&kaction=deleteEdge&models='+params).
                                success(function(data, status) {
                                    alert("{/literal}{'Done'|@getTranslatedString:'Done'}{literal}");
                                });
                    }
                    };
                    $scope.saveGraph=function(){
                        if(confirm("{/literal}{'AreYouSure'|@getTranslatedString:'AreYouSure'}{literal}")){
                        var graph=cy.json();
                        var params=encodeURIComponent(JSON.stringify(graph));
                        $http({
                            method: 'POST',
                            url: 'index.php?module=BPMDesigner&action=BPMDesignerAjax&file=operations&kaction=saveGraph',
                            data :graph
                        }).
                            success(function(data, status) {
                                alert("{/literal}{'Done'|@getTranslatedString:'Done'}{literal}");
                            });
                    }
                    };
                    $scope.addRelation=function(){
                        var id=$scope.start_status+'_'+$scope.end_status;
                        cy.add([
                            { group: "edges", data: { id: id, source: $scope.start_status, target: $scope.end_status } }
                        ]);
                        var params='&processTemp='+$scope.procestemp+'&start_status='+$scope.start_status+'&end_status='+$scope.end_status;
                        $http.get('index.php?module=BPMDesigner&action=BPMDesignerAjax&file=operations&kaction=savePF'+params).
                                success(function(data, status) {
                                });
                    }; 
        });
    }; 
    $scope.reloadContent();
});

{/literal}
</script>
