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
<link rel="stylesheet" type="text/css" href="Smarty/angular/bootstrap.min.css"/>
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
<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
    <tr>
        <td>
            <table ng-controller="block_{$NG_BLOCK_ID}"  ng-table="tableParams" class="table  table-bordered table-responsive">
            {if $ADD_RECORD eq 1 }
            <tr class="dvtCellLabel">
                {math equation="x" x=$FIELD_LABEL|@count assign="nr_col"} 
                <td>
                    <img width="20" height="20" ng-click="open(user,'create')" src="themes/softed/images/btnL3Add.gif" />
                    {if $MODULE_NAME eq 'Project' && $POINTING_MODULE eq 'Messages'}
                        <a ng-click="open(user,'create')">Crea Nota</a> 
                    {else}
                        <a ng-click="open(user,'create')">Add New {$NG_BLOCK_NAME}</a> &nbsp;&nbsp;&nbsp;
                    {/if}
                </td> 
                <td colspan="{$nr_col}">
                    <a ng-click="open(user,'choose')">Choose {$NG_BLOCK_NAME}</a> &nbsp;&nbsp;&nbsp;
                </td>
            </tr>
            {/if}
            <tr class="dvtCellLabel">
                {foreach key=index item=fieldlabel from=$FIELD_LABEL} 
                   {* {if $COLUMN_NAME.$index neq 'messagio'}*}
                    <td> <b>{$fieldlabel}</b> </td> 
                   {* {/if} *}
                {/foreach} 
                <td> </td> 
            </tr>
            <tr ng-repeat="user in $data"  class="dvtCellInfo">
                {foreach key=index item=fieldname from=$COLUMN_NAME} 
                 {*  {if $fieldname neq 'messagio'}  *}
                      {if $index eq 0}
                          <td >
                             <a href="{literal}{{user.href}}{/literal}">{literal}{{user.{/literal}{$fieldname}{literal}}}{/literal}</a>
                          </td> 
                      {else}
                          <td > 
                              {if in_array($FIELD_UITYPE.$index,array(10,51,50,73,68,57,59,58,76,75,81,78,80) )}
                                  <div ng-bind-html="user.{$fieldname}_display | sanitize"></div> 
                              {elseif in_array($FIELD_UITYPE.$index,array(5,6,23) )}
                                  <div ng-bind-html="user.{$fieldname}_display"></div> 
                              {elseif in_array($FIELD_UITYPE.$index,array(53) )}
                                  <div ng-bind-html="user.{$fieldname}_display"></div> 
                              {elseif in_array($FIELD_UITYPE.$index,array(69,105,28) )}
                                  <a ng-click="downloadfile(user.preview)"><b>{literal}{{{/literal}user.{$fieldname}{literal}}}{/literal}</b></a>
                              {else}
                                  <div ng-bind-html="user.{$fieldname} | sanitize"></div> 
                              {/if}
                          </td>
                      {/if}
                 {*  {/if} *}
                {/foreach} 
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
        </td>
    </tr>
</table>
</div>

<script type="text/ng-template" id="DetailViewBlockNgEdit{$NG_BLOCK_ID}.html">

<div class="modal-header">
    <h4 class="modal-title">{literal}{{Action}}{/literal} {$POINTING_MODULE} {literal}{{user.name}}{/literal}</h4>
</div>
<div class="modal-body">    
    <table  >
     <tr ng-if="type=='choose'">
        <td style="height:300px;vertical-align:top">
                <input ng-model="choosen_entity" type="hidden"  >  
                <multi-select   
                    input-model="choosen_entity1" 
                    output-model="selected_values_choosen_entity"
                    button-label="name"
                    item-label="name"
                    tick-property="ticked" 
                    on-item-click="functionClick_en( data )">
                </multi-select>
        </td>
    </tr>
    {foreach key=index item=fieldname from=$COLUMN_NAME} 
        {if  $FIELD_UITYPE.$index neq '70'}
          <tr ng-class-odd="'emphasis'" ng-class-even="'odd'" ng-if="type!='choose'">
              <td style="text-align:right;"> 
                  {$FIELD_LABEL.$index}
              </td>
              <td style="text-align:left;"> 
              {if $FIELD_UITYPE.$index eq '15'}
                  <select class="form-control" ng-model="user.{$fieldname}"  ng-options="op for op  in opt.{$fieldname}"></select>
              {elseif in_array($FIELD_UITYPE.$index,array(10,51,50,73,68,57,59,58,76,75,81,78,80) )  }
              <input class="form-control" style="width:250px;" type="hidden" id="{$fieldname}" ng-model="user.{$fieldname}" value="user.{$fieldname}"/>
              <input class="form-control" style="width:250px;" type="text" id="{$fieldname}_display" ng-model="user.{$fieldname}_display" value="user.{$fieldname}_display" onchange="alert(this.value);"/>
              <img src="{'select.gif'|@vtiger_imageurl:$THEME}"
                alt="Select" title="Select" LANGUAGE=javascript  onclick='return window.open("index.php?module={$REL_MODULE.$index}&action=Popup&html=Popup_picker&form=vtlibPopupView&forfield={$fieldname}&srcmodule={$POINTING_MODULE}&responseTo=ngBlockPopup","test","width=640,height=602,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>
              {elseif in_array($FIELD_UITYPE.$index,array(5,6,23) )}
                  <input type="hidden" ng-model="user.{$fieldname}"/>
                  <input type="date" class="form-control" ng-model="user.{$fieldname}_display2" placeholder="yyyy-MM-dd" ng-change="put_date('{$fieldname}');"/>
              {elseif $FIELD_UITYPE.$index eq '33'}
                  <select multiple class="form-control" ng-model="user.{$fieldname}"  
                          ng-options="op for op  in opt[{$index}]"></select>
              {elseif $FIELD_UITYPE.$index eq '19'}
                      <div text-angular="text-angular" name="user.{$fieldname}" ng-model="user.{$fieldname}"></div>
              {elseif $FIELD_UITYPE.$index eq '53'}
                  <input type="hidden" ng-model="user.{$fieldname}"/>
                  <select class="form-control" ng-model="user.{$fieldname}_display2" ng-change="put_ass('{$fieldname}');"
                          ng-options="op as op.crmname group by op.crmtype for op  in opt.{$fieldname} track by op.crmid"></select>
              {elseif in_array($FIELD_UITYPE.$index,array(69,105,28) )}
                  <input type="file" file-upload />
                        {literal}{{{/literal}user.{$fieldname}{literal}}}{/literal}
              {else}
                  <input class="form-control" style="width:350px;" type="text" ng-model="user.{$fieldname}"/>
              {/if}
               </td>
          </tr>
      {/if}
    {/foreach}
    </table>
</div>
<div class="modal-footer">
    {if $POINTING_MODULE neq 'Messages'}
        <button class="btn btn-primary" ng-click="setEditId(user)" ng-if="type!='choose'">Save</button>
    {else}
        <button class="btn btn-primary" ng-click="setEditId(user)" ng-if="type!='choose'">Send</button>
    {/if}
    <button class="btn btn-primary" ng-click="setRelation(choosen_entity)" ng-if="type=='choose'">Set Relation</button>
    <button class="btn btn-warning" ng-click="cancel()">Cancel</button>
</div>
</script>
<style>
{literal}
.app-modal-window .modal-dialog {
    width: 500px;
    margin-left:-190px;
    }
.ta-editor {
    min-height: 50px;
    height: auto;
    overflow: auto;
    font-family: inherit;
    font-size: 100%;
    margin:20px 0;
}
{/literal}
</style>
<script>
{literal}
angular.module('demoApp')
.filter("sanitize", ['$sce', function($sce) {
  return function(htmlCode){
    return $sce.trustAsHtml(htmlCode);
  }
}])
.controller('block_{/literal}{$NG_BLOCK_ID}{literal}',function($scope,$window,$http, $modal, ngTableParams) {
    $scope.user={};
            
    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 5  // count per page

    }, {
       counts: [5,15], 
        getData: function($defer, params) {
        $http.get('index.php?{/literal}{$blockURL}{literal}&kaction=retrieve').
            success(function(data, status) {
              var orderedData = data;
              params.total(data.length);
              $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
        })
            }
    });

    // delete selected record
    $scope.delete_record =  function(user) {
     if(confirm('Are you sure you want to delete?'))
     {
         user.href='';
         var data_send =JSON.stringify(user);
         $http.post('index.php?{/literal}{$blockURL}{literal}&kaction=delete&models='+data_send
        )
        .success(function(data, status) {
              $scope.tableParams.reload();

         });
        }
    };
    
    $scope.downloadfile = function(path) {
        $window.open(path, '_blank');
    };

      $scope.open = function (user,type) {
        var modalInstance = $modal.open({
          templateUrl: 'DetailViewBlockNgEdit{/literal}{$NG_BLOCK_ID}{literal}.html',
          controller: 'ModalInstanceCtrl{/literal}{$NG_BLOCK_ID}{literal}',
          windowClass: 'app-modal-window',
          //backdrop: "static",
          resolve: {
            user :function () {
              return user;
            },
            type :function () {
              return type;
            },
            tbl :function () {
              return $scope.tableParams;
            }
          }
        });

        modalInstance.result.then(function (selectedItem) {
          $scope.selected = selectedItem;
        }, function () {
          //$log.info('Modal dismissed at: ' + new Date());
        });
      };

})

.controller('ModalInstanceCtrl{/literal}{$NG_BLOCK_ID}{literal}',function ($scope,$http,$filter,$modalInstance,user,type,tbl) {

      $scope.user = (type === 'create' ? {} : user);
      $scope.user1 = {};
      $scope.choosen_entity='';
      $scope.selected = {
        item: 0
      };
      $scope.type=type;
      $scope.Action = (type === 'create' ? 'Create' : 'Edit');
      $scope.opt={/literal}{$OPTIONS}{literal}; console.log($scope.opt);
      $scope.col_json={/literal}{$COLUMN_NAME_JSON}{literal};
      $scope.ui_json={/literal}{$FIELD_UITYPE_JSON}{literal};
      for(var i=0;i<$scope.col_json.length;i++){
          if($scope.ui_json[i]=='5'){
              $scope.user[$scope.col_json[i]+'_display2']=new Date($scope.user[$scope.col_json[i]]);
          }
          else if($scope.ui_json[i]=='53'){
              $scope.user[$scope.col_json[i]+'_display2']={'crmid':$scope.user[$scope.col_json[i]]};
          }
      }
      // edit selected record
      $scope.put_ass =  function(fld) {
              var name=fld+'_display2';
              $scope.user[fld]=$scope.user[name]['crmid'];
      };
      $scope.put_date =  function(fld) {
              var name=fld+'_display2';
              $scope.user[fld]=$filter('date')($scope.user[name], 'yyyy-MM-dd');
      };

            $http.get('index.php?{/literal}{$blockURL}{literal}&kaction=select_entity').
                success(function(data, status) {
                    $scope.choosen_entity1=data;
                });
       
        $scope.functionClick_en = function( data ) {          
           if($scope.choosen_entity!=undefined)
               var arr = $scope.choosen_entity.split(',');
           else
               var arr = new Array();
           var index =arr.indexOf(data.id);
           if(index!==-1)
           {
               arr.splice(index,1);
           }
           else
           {
               arr.push(data.id);
           }
           $scope.choosen_entity=arr.join(',');
       };
       
      $scope.setRelation =  function(choosen) {
            user.href='';
            user =JSON.stringify(user);
            $http.post('index.php?{/literal}{$blockURL}{literal}&kaction=setRelation&relid='+choosen
                )
                .success(function(data, status) {
                      tbl.reload();  
                      $modalInstance.close($scope.selected.item);
                 });
      };
    //an array of files selected
    $scope.files = [];
    //listen for the file selected event
    $scope.$on("fileSelected", function (event, args) {
        $scope.$apply(function () {            
            //add the file object to the scope's files collection
            $scope.files.push(args.file);
        });
    });
    
      $scope.setEditId =  function(user) {
            {/literal}
            {foreach key=index item=fieldname from=$COLUMN_NAME} 
              {if in_array($FIELD_UITYPE.$index,array(10,51,50,73,8,57,59,58,76,75,81,78,80))}
                  if(document.getElementById("{$fieldname}").value!='user.{$fieldname}')
                      user.{$fieldname}=document.getElementById("{$fieldname}").value;
              {elseif in_array($FIELD_UITYPE.$index,array(69,105,28))}
                  if($scope.files.length>0){ldelim}
                      user.{$fieldname}=$scope.files[0].name;
                    {rdelim}
              {/if}
            {/foreach}
            {literal}
            user.href='';
            user =JSON.stringify(user);
            $http({
                method: 'POST',
                url: 'index.php?{/literal}{$blockURL}{literal}&kaction='+type+'&models='+encodeURIComponent(user),
                headers: { 'Content-Type': undefined },
                transformRequest: function (data) {
                    var formData = new FormData();
                    for (var i = 0; i < data.files.length; i++) {
                        formData.append("filename" , data.files[i]);
                    }
                    return formData;
                },
                data: { files: $scope.files}
            })
                .success(function(data, status, headers, config) {
                      tbl.reload();  
                      $modalInstance.close($scope.selected.item);
                 });
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
})
.directive('fileUpload', function () {
    return {
        scope: true,        //create a new scope
        link: function (scope, el, attrs) {
            el.bind('change', function (event) {
                var files = event.target.files;
                //iterate files since 'multiple' may be specified on the element
                for (var i = 0;i<files.length;i++) {
                    //emit event upward
                    scope.$emit("fileSelected", { file: files[i] });
                }                                       
            });
        }
    };
});
{/literal}
</script>
