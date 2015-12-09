{*<!--
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *************************************************************************************************
 *  Author       : JPL TSolucio, S. L.
 *************************************************************************************************//
-->*}

<script src="modules/com_vtiger_workflow/resources/vtigerwebservices.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="Smarty/angular/angular.js"></script>
<link rel="stylesheet" href="Smarty/angular/ng-tags-input.min.css" />
<script src="Smarty/angular/ng-tags-input.min.js"></script>
<script type="text/javascript" charset="utf-8">
var moduleName = '{$entityName}';
</script>
<div ng-app="demoApp" ng-controller="mainCtrl_businessactionsid">
<h2>Select {'LBL_ACTION'|@getTranslatedString:'com_vtiger_workflow'}</h2>
<input name="businessactionsid" id="businessactionsid" value="{$task->businessactionsid}" type="hidden"  >  
<tags-input ng-model="businessactionsid" 
            display-property="name" 
            on-tag-added="functionClick($tag)"
            on-tag-removed="functionClick($tag)"
            placeholder="Select " >
  <auto-complete source="loadTags($query)"
                 min-length="2"
                 max-results-to-show="20"
                 ></auto-complete>
</tags-input>
</div>
<script>
    {literal}
angular.module('demoApp',['ngTagsInput'])
.controller('mainCtrl_businessactionsid', function ($scope, $http) {
    $scope.businessactionsid=[];
      $scope.loadTags = function(query) {
            return $http.get('index.php?module=com_vtiger_workflow&action=com_vtiger_workflowAjax&file=get_businessactionsid&moduleName='+moduleName+"&query="+query).
        success(function(data, status) {
          });
      };
      $http.get('index.php?module=com_vtiger_workflow&action=com_vtiger_workflowAjax&file=get_businessactionsid&moduleName='+moduleName+'&sel_values={/literal}{$task->businessactionsid}{literal}').
            success(function(data, status) {
                $scope.businessactionsid=data;
      });                            
      $scope.functionClick= function(tag) {

           var arr = new Array();
         for(var i=0;i<$scope.businessactionsid.length;i++)
           arr[i]=$scope.businessactionsid[i]['id'];

         document.getElementsByName('businessactionsid').item(0).value=arr.join(',');

       };

      });

    {/literal}
</script>