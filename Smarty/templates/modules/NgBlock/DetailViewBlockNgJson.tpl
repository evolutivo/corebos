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
 *  Module       : NgBlock
 *  Version      : 5.5.0
 *  Author       : OpenCubed.
 *************************************************************************************************/
-->*}
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
<tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td align=right>
                {if $NG_BLOCK_NAME eq $MOD.LBL_ADDRESS_INFORMATION && ($MODULE eq 'Accounts') }
                        {if $MODULE eq 'Leads'}
                                <input name="mapbutton" value="{$APP.LBL_LOCATE_MAP}" class="crmbutton small create" type="button" onClick="searchMapLocation( 'Main' )" title="{$APP.LBL_LOCATE_MAP}">
                        {/if}
                {/if}
        </td>
</tr>
<tr>{strip}
    <td colspan=4 class="dvInnerHeader">

            <div style="float:left;font-weight:bold;"><div style="float:left;"><a href="javascript:showHideStatus('tbl{$NG_BLOCK_NAME|replace:' ':''}','aid{$NG_BLOCK_NAME|replace:' ':''}','{$IMAGE_PATH}');">
                                    <img id="aid{$NG_BLOCK_NAME|replace:' ':''}" src="{'inactivate.gif'|@vtiger_imageurl:$THEME}" style="border: 0px solid #000000;" alt="Display" title="Display"/>
                            </a></div><b>&nbsp;
                            {$NG_BLOCK_NAME}
                    </b></div>
    </td>{/strip}
</tr>
</table>

<div style="width:auto;display:none;" id="tbl{$NG_BLOCK_NAME|replace:' ':''}" >
        <table ng-controller="block_{$NG_BLOCK_ID}"  ng-table="tableParams" class="table  table-bordered table-responsive">
            {if $ADD_RECORD eq 1 }
            <tr class="dvtCellLabel">
                {math equation="x+1" x=$FIELD_LABEL|@count assign="nr_col"} 
                <td colspan="{$nr_col}">
                    <img width="20" height="20" ng-click="open(user,'create')" src="themes/softed/images/btnL3Add.gif" />
                    {if $MODULE_NAME eq 'Project' && $POINTING_MODULE eq 'Messages'}
                        <a ng-click="open(user,'create')">Crea Nota</a> 
                    {else}
                        <a ng-click="open(user,'create')">Add New {$NG_BLOCK_NAME}</a> &nbsp;&nbsp;&nbsp;
                    {/if}
                </td> 
            </tr>
            {/if}
            <tr class="dvtCellLabel">
                <td ng-repeat="header in headers" > 
                    <b>{literal}{{header}}{/literal}</b> </td> 
                <td> </td> 
            </tr>
            <tr ng-repeat="user in $data"  class="dvtCellInfo">
                <td > 
                      {literal}{{user.line}}{/literal}
                </td>
                <td > 
                      {literal}{{user.date}}{/literal}
                </td>
                <td > 
                      {literal}{{user.user}}{/literal}
                </td>
                <td  width="80" >
                <table>
                      <tr>
                          {if $EDIT_RECORD eq 1}
                          <td>
                              <img ng-if="!user.$edit" width="20" height="20" ng-click="open(user,'edit')" src="themes/images/editfield.gif" /> 
                          </td>
                          {/if}
                          {if $DELETE_RECORD eq 1}
                          <td>
                              <img ng-if="!user.$edit" width="20" height="20" ng-click="delete_record(user)" src="themes/images/delete.gif" />
                          </td> 
                          {/if}
                      </tr>             
                 </table>   
                </td>
            </tr>
        </table>
</div>

<style>
{literal}
.app-modal-window .modal-dialog {
    width: 500px;
    margin-left:-190px;
    }
{/literal}
</style>

<script>
{literal}
angular.module('demoApp')
.controller('block_{/literal}{$NG_BLOCK_ID}{literal}',function($scope, $http, $modal,ngTableParams) {
           
     $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 5  // count per page

    }, {
       counts: [5,15], 
        getData: function($defer, params) {
        $http.get('index.php?{/literal}{$blockURL}{literal}&kaction=retrieve_json').
            success(function(data, status) {
              var orderedData = data.values;
              $scope.headers=data.headers;
              params.total(orderedData.length);
              $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
        })
            }
    });
        
});
{/literal}
</script>
