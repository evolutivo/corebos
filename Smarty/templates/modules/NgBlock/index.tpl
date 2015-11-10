<link rel="stylesheet" href="Smarty/angular/material/angular-material.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=RobotoDraft:300,400,500,700,400italic">
<script src="Smarty/angular/material/angular.min.js"></script>
<script src="Smarty/angular/material/angular-animate.min.js"></script>
<script src="Smarty/angular/material/angular-aria.min.js"></script>
<script src="Smarty/angular/material/angular-material.min.js"></script>

<script src="Smarty/angular/angular.min.js"></script>
<script  src="Smarty/angular/ng-table.js"></script>
<link rel="stylesheet" href="Smarty/angular/ng-table.css" />
<script src="Smarty/angular/ui-bootstrap-tpls-0.6.0.js"></script>
<link rel="stylesheet" type="text/css" href="Smarty/angular/bootstrap.min.css"/>
<script src="Smarty/angular/angular-multi-select.js"></script>  
<link rel="stylesheet" href="Smarty/angular/angular-multi-select.css">

<table width=96% align=center border="0" ng-app="demoApp" style="padding:10px;">
    <tr><td style="height:2px"><br/><br/></td></tr>
    <tr>
        <td style="padding-left:20px;padding-right:50px" class="moduleName" nowrap colspan="2">
            Generic AngularJS component providing widgets functionality</td>
     </tr>
     <tr>  
	<td class="showPanelBg" valign="top" style="padding:10px;" width=96%>
              <div  layout="column" class="demo" >
                <md-content class="md-padding">
                    <md-tabs md-dynamic-height md-border-bottom>
                      <md-tab label="NgBLocks">
                        <md-content class="md-padding">
                          <h5 >Manage NgBlocks</h5>
                          <table ng-controller="ng_Block" ng-table="tableParams" class="table  table-bordered table-responsive" width=90% >
                            <tr >
                                <td colspan="21">
                                    <button class="btn btn-primary" ng-click="open(new_user,'add')">Add new NgBlock</button>
                                </td>
                        </tr>
                        <tr style="background-color:#c9dff0">
                                <th style="align:center">
                                </th> <th style="text-align: center">
                                    Name
                                </th><th style="text-align: center">
                                    Module Name
                                </th><th style="text-align: center">
                                    Pointing Block
                                </th><th style="text-align: center">
                                    Pointing Module
                                </th><th style="text-align: center">
                                    Pointing Field
                                </th><th style="text-align: center">
                                    Columns
                                </th><th style="text-align: center">
                                    Condition
                                </th><th style="text-align: center">
                                    Paginate
                                </th><th style="text-align: center">
                                    Nr Page
                                </th><th style="text-align: center">
                                    Add Record
                                </th><th style="text-align: center">
                                    Sort
                                </th><th style="text-align: center">
                                    Edit Record
                                </th><th style="text-align: center">
                                    Delete Record
                                </th><th style="text-align: center">
                                    Sequence
                                </th>
                                <th style="text-align: center">
                                    Destination
                                </th>
                                <th style="text-align: center">
                                    Type
                                </th>
                                <th style="text-align: center">
                                    Tab
                                </th>
                                <th style="text-align: center">
                                    Custom Widget Path
                                </th>
                                <th style="text-align: center">
                                    BR
                                </th>
                                <th style="text-align: center">
                                    Respective Action
                                </th>
                            </tr>    
                            <tr ng-repeat="user in $data" >
                              <td  >                
                                  <table>
                                      <tr>
                                          <th>
                                              <img ng-if="!user.$edit" width="20" height="20" ng-click="open(user,'edit')" src="themes/images/editfield.gif" /> 
                                              <a ng-click="open(user,'edit')">Edit</a> 
                                          </th>
                                          <th>
                                              <img ng-if="!user.$edit" width="20" height="20" ng-click="delete_record(user)" src="themes/images/delete.gif" />
                                              <a ng-click="delete_record(user)">Delete</a>
                                          </th>   
                                      </tr>             
                                  </table>
                              </td>
                              <td > 
                                  {literal}  {{user.name}}{/literal}                                                                   
                              </td> 
                              <td  > 
                                  {literal}  {{user.module_name_trans}}{/literal}
                              </td> 
                              <td  > 
                                  {literal}  {{user.pointing_block_name_trans}}{/literal}
                              </td> 
                              <td > 
                                  {literal}  {{user.pointing_module_name_trans}}{/literal}
                              </td> 
                              <td  > 
                                  {literal}  {{user.pointing_field_name_trans}}{/literal}
                              </td> 
                              <td  > 
                                  {literal}  {{user.columns}}{/literal}
                              </td> 
                              <td  > 
                                  {literal}  {{user.cond}}{/literal}
                              </td> 
                              <td > 
                                  <img ng-if="user.paginate==1" width="20" height="20" src="themes/images/yes.gif" />
                                  <img ng-if="user.paginate!=1" width="20" height="20" src="themes/images/no.gif" />                                  
                              </td> 
                              <td  data-title="'Nr Page'"> 
                                  {literal}  {{user.nr_page}}{/literal}
                              </td> 
                              <td  > 
                                  <img ng-if="user.add_record==1" width="20" height="20" src="themes/images/yes.gif" />
                                  <img ng-if="user.add_record!=1" width="20" height="20" src="themes/images/no.gif" />  
                              </td> 
                              <td > 
                                  {literal}  {{user.sort}}{/literal}
                              </td> 
                              <td  > 
                                  <img ng-if="user.edit_record==1" width="20" height="20" src="themes/images/yes.gif" />
                                  <img ng-if="user.edit_record!=1" width="20" height="20" src="themes/images/no.gif" />
                              </td> 
                              <td  > 
                                  <img ng-if="user.delete_record==1" width="20" height="20" src="themes/images/yes.gif" />
                                  <img ng-if="user.delete_record!=1" width="20" height="20" src="themes/images/no.gif" />
                              </td> 
                              <td  data-title="'Sequence'"> 
                                  {literal}  {{user.sequence_ngblock}}{/literal}
                              </td> 
                              <td  data-title="'Destination'"> 
                                  {literal}  {{user.destination}}{/literal}
                              </td> 
                              <td  data-title="'Type'"> 
                                  {literal}  {{user.type}}{/literal}
                              </td> 
                              <td  data-title="'Tab'"> 
                                  {literal}  {{user.related_tab_name}}{/literal}
                              </td> 
                              <td  data-title="'Custom Widget Path'" > 
                                  {literal}  {{user.custom_widget_path}}{/literal}
                              </td> 
                              <td  data-title="'BR'" > 
                                  {literal}  {{user.br_id}}{/literal}
                              </td> 
                              <td  data-title="'Action'" > 
                                  <a href="index.php?module=BusinessActions&action=DetailView&record={literal}{{user.respective_act}}{/literal}">Action</a>
                              </td> 
                              
                            </tr>
                        </table>
                        </md-content>
                      </md-tab>
                      <md-tab label="RL Tabs">
                        <md-content class="md-padding">
                          <h5 class="md-display-5">Manage Tabs of Related Lists</h5>
                            <table ng-controller="ng_Block" ng-table="tableParamsTabs" class="table  table-bordered table-responsive" width=100% >
                                <tr >
                                    <td colspan="4">
                                         <button class="btn btn-primary" ng-click="open2(new_user,'add')">Add New Tab RL</button>
                                    </td>
                            </tr>
                            <tr style="background-color:#c9dff0">
                                <th style="align:center">
                                </th> <th style="text-align: center">
                                    Name
                                </th>
                                <th style="text-align: center">
                                    Module
                                </th>
                                <th style="text-align: center">
                                    Sequence
                                </th>
                            </tr> 
                            <tr ng-repeat="user in $data" >
                                  <td>                
                                      <table>
                                      <tr>
                                          <th>
                                              <img ng-if="!user.$edit" width="20" height="20" ng-click="open2(user,'edit')" src="themes/images/editfield.gif" /> 
                                              <a ng-click="open2(user,'edit')">Edit</a> 
                                          </th>
                                          <th>
                                              <img ng-if="!user.$edit" width="20" height="20" ng-click="delete_record2(user)" src="themes/images/delete.gif" />
                                              <a ng-click="delete_record2(user)">Delete</a>
                                          </th>   
                                      </tr>             
                                      </table>
                                  </td>
                                  <td > 
                                      {literal}  {{user.name}}{/literal}                                                                   
                                  </td> 
                                  <td  > 
                                      {literal}  {{user.modulename}}{/literal}
                                  </td>
                                  <td  > 
                                      {literal}  {{user.sequence}}{/literal}
                                  </td>
                            </tr>
                            </table>
                        </md-content>
                      </md-tab>
                      <md-tab label="Manage UiType Evo">
                        <md-content class="md-padding">
                          <h5 class="md-display-5">Manage UiType Evo Fields</h5>
                          {include file="modules/NgBlock/uiEvo.tpl"}
                        </md-content>
                      </md-tab>
                      <md-tab label="NgForm Creator">
                        <md-content class="md-padding">
                          <h5 class="md-display-5">Form Creator Parameter Setting</h5>
                          {include file="modules/NgBlock/ngFormCreator.tpl"}
                        </md-content>
                      </md-tab>            
                    </md-tabs>
                  </md-content>
                </div>
             </td>
            </tr>
</table>
<script>
{literal}
angular.module('demoApp',['ngTable','ui.bootstrap','multi-select','ngMaterial']) 
.controller('ng_Block', function($scope, $http, $modal, ngTableParams) {

    $scope.new_user={"id":"","id_hidden":"","name":"","module_name":"",
        "module_name_trans":"","pointing_block_name":"",
        "pointing_block_name_trans":"","pointing_module_name":"",
        "pointing_module_name_trans":"","pointing_field_name":"",
        "pointing_field_name_trans":"","columns":"","cond":"",
        "paginate":"","nr_page":"","add_record":"",
        "sort":" ","edit_record":"","delete_record":""
        ,"br_id":""};

    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 5  // count per page

    }, {
       counts: [5,15], 
        getData: function($defer, params) {
        $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=retrieve').
            success(function(data, status) {
              var orderedData = data;
              params.total(data.length);
              $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
        })
            }
    });
    $scope.tableParamsTabs = new ngTableParams({
        page: 1,            // show first page
        count: 5  // count per page

    }, {
       counts: [5,15], 
        getData: function($defer, params) {
        $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=get_tab').
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
         var data_send =JSON.stringify(user);
         $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=delete&models='+data_send
        )
        .success(function(data, status) {
              $scope.tableParams.reload();

         });
        }
    };
        // delete selected record
    $scope.delete_record2 =  function(user) {
     if(confirm('Are you sure you want to delete?'))
     {
         var data_send =JSON.stringify(user);
         $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=deletetab&models='+data_send
        )
        .success(function(data, status) {
              $scope.tableParamsTabs.reload();

         });
        }
    }

      $scope.open = function (us,type) {
          
        var modalInstance = $modal.open({
          templateUrl: 'Smarty/templates/modules/NgBlock/edit_modal.html',
          controller: 'ModalInstanceCtrl',
          resolve: {
            user :function () {
              return us;
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

    $scope.open2 = function (us,type) {
        var modalInstance = $modal.open({
          templateUrl: 'Smarty/templates/modules/NgBlock/add_ng_tab.html',
          controller: 'TabInstanceCtrl',
          resolve: {
              user :function () {
              return us;
            },
            type :function () {
              return type;
            },
            tbl :function () {
              return $scope.tableParamsTabs;
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
.controller('ModalInstanceCtrl',function ($scope,$http,$modalInstance,user,type,tbl) {

      $scope.user = (type === 'add' ? {} : user);
      $scope.selected = {
        item: 0
      };
      $scope.Action = (type === 'add' ? 'Create' : 'Edit');

      $scope.module_sel=[];
      $scope.blocks=[];
      $scope.pointing_field=[];
      $scope.tab_rl_opt=[];
      $scope.mod_sel={'tablabel':$scope.user.module_name,'tabid':$scope.user.module_id};
      $scope.pointing_module_name_sel={'tablabel':$scope.user.pointing_module_name};
      $scope.pointing_block_name_sel={'label':$scope.user.pointing_block_name};
      $scope.pointing_field_name_sel={'columnname':$scope.user.pointing_field_name};
      $scope.related_tab_sel={'id':$scope.user.related_tab};
      
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=relation&kaction=retrieve').
                    success(function(data, status) {
                      $scope.modules = data;
      });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=block&kaction=retrieve').
                    success(function(data, status) {
                      $scope.blocks = data;
      });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=pointing_field_name&kaction=retrieve').
                    success(function(data, status) {
                      $scope.pointing_field = data;
      });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=get_tab').
                    success(function(data, status) {
                      $scope.tab_rl_opt = data;
      });
      
      $scope.destination_opt=['DETAILVIEWWIDGET','RELATEDVIEWWIDGET','PORTALDV','PORTALSV','PORTALLV','DETAILVIEWWIDGET_PORTAL','CUSTOMERPORTAL'];
      $scope.type_opt=['Table','Graph','Elastic','Text','Custom'];  
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction=get_elastic_indexes').
                    success(function(data, status) {
                      $scope.get_elastic_indexes = data;
      });
      $scope.myFilter = function(value) {
       return ($scope.filterValues.indexOf(value.id) !== -1);
      };
      // edit selected record
      $scope.setEditId =  function(user) {
            user =JSON.stringify(user);
            $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction='+type+'&models='+user
                )
                .success(function(data, status) {
                      tbl.reload();  
                      $modalInstance.close($scope.selected.item);
                 });
            };
            
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns=data;
                  });
       
       $scope.functionClick = function( data ) {
           //alert($scope.user.columns);
           if($scope.user.columns!=undefined)
               var arr = $scope.user.columns.split(',');
           else
               var arr = new Array();
           var index =arr.indexOf(data.columnname);
           if(index!==-1)
           {
               arr.splice(index,1);
           }
           else
           {
               arr.push(data.columnname);
           }
           $scope.user.columns=arr.join(',');
       };
       
        $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve_br&selected='+$scope.user.br_id+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.br_id=data;
                  });
       
       $scope.functionClick_br = function( data ) {          
           if ($scope.user.br_id===data.businessrulesid){
               $scope.user.br_id='';
           }
           else{
               $scope.user.br_id=data.businessrulesid;
           }
           
       };
       $scope.refresh_columns = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns=data;
                  });
       };
       
       $scope.refresh_br = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve_br&selected='+$scope.user.br_id+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.br_id=data;
                  });
       };
      
      $scope.fill_condition = function (a,b) {
        $scope.user.cond+=' '+a+' '+b+' ';
      };
      $scope.fill_sort = function (a,b) {
        $scope.user.sort=' '+a+','+b;
      };
      $scope.getTab = function (a) {
        return $scope.mod_sel.tabid===a;
      };
      
      $scope.ok = function () {
        $modalInstance.close($scope.selected.item);
      };

      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
});
angular.module('demoApp')
    .filter('filter_blocks', function() {
          return function(blocks,user) {
            var filterEvent = [];
            for (var i = 0;i < blocks.length; i++){
                if(blocks[i]['tablabel']==user.module_name){
                    filterEvent.push(blocks[i]);
                }
            }
            return filterEvent;
        }
    }
    );
angular.module('demoApp')
.filter('filter_pointing_fields', function() {
      return function(pointing_field,user) {
        var filterEvent = [];
        for (var i = 0;i < pointing_field.length; i++){
            if(pointing_field[i]['tablabel']==user.pointing_module_name){
                filterEvent.push(pointing_field[i]);
            }
        }
        return filterEvent;
    }
});
angular.module('demoApp')
    .filter('filter_tab_rl', function() {
          return function(tab_rl_opt,user) {
            var filterEvent = [];
            for (var i = 0;i < tab_rl_opt.length; i++){
                if(tab_rl_opt[i]['moduleid']==user.module_name){
                    filterEvent.push(tab_rl_opt[i]);
                }
            }
            return filterEvent;
        }
    }
    );
angular.module('demoApp')
.filter('filter_source_fields', function() {
      return function(pointing_field,user) {
        var filterEvent = [];
        for (var i = 0;i < pointing_field.length; i++){
            if(pointing_field[i]['tablabel']==user.module_name){
                filterEvent.push(pointing_field[i]);
            }
        }
        return filterEvent;
    }
});
angular.module('demoApp')
.controller('TabInstanceCtrl',function ($scope,$http,$modalInstance,user,type,tbl) {

      $scope.user = (type === 'add' ? {} : user);
      $scope.selected = {
        item: 0
      };
      $scope.Action = (type === 'add' ? 'Create' : 'Edit');
      $scope.act = (type === 'add' ? 'addtab' : 'edittab');

      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=relation&kaction=retrieve').
                success(function(data, status) {
                  $scope.modules = data;
      });
      $scope.module_sel=[];
      $scope.mod_sel={'tablabel':$scope.user.moduleid,'tabid':$scope.user.moduleid};
      
      $scope.setEditId =  function(user) {
            user =JSON.stringify(user);
            $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=index&kaction='+$scope.act+'&models='+user
                )
                .success(function(data, status) {
                      tbl.reload();  
                      $modalInstance.close($scope.selected.item);
                 });
            };
      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
});
angular.module('demoApp') 
.controller('ng_EvoFields', function($scope, $http, $modal, ngTableParams) {

    $scope.new_user={};

    $scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 5  // count per page

    }, {
       counts: [5,15], 
        getData: function($defer, params) {
        $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=ng_fields&kaction=retrieve').
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
         var data_send =JSON.stringify(user);
         $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=ng_fields&kaction=delete&models='+data_send
        )
        .success(function(data, status) {
              $scope.tableParams.reload();

         });
        }
    };
     
      $scope.open2 = function (us,type) {
          
        var modalInstance = $modal.open({
          templateUrl: 'Smarty/templates/modules/NgBlock/edit_evo_fields.html',
          controller: 'ng_Evo_Edit',
          resolve: {
            user :function () {
              return us;
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
});
angular.module('demoApp')
.controller('ng_Evo_Edit',function ($scope,$http,$modalInstance,user,type,tbl) {

      $scope.user = (type === 'add' ? {"fieldid":"","fieldname":"","module_name":"","pointing_block_name":"",
        "columns_search":"","columns_shown":"",
        "br_id":"","type":""
        ,"existing":false} : user);
      $scope.selected = {
        item: 0
      };
      $scope.Action = (type === 'add' ? 'Create' : 'Edit');
      $scope.act = (type === 'add' ? 'add_ng_field' : 'edit_ng_field');
      $scope.blocks=[];

      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=relation&kaction=retrieve').
                success(function(data, status) {
                  $scope.modules = data;
      });
      $scope.module_sel=[];
      $scope.mod_sel={'tablabel':$scope.user.module_name,'tabid':$scope.user.module_id};
      $scope.pointing_module_name_sel={'tablabel':$scope.user.pointing_module_name};
      $scope.pointing_block_name_sel={'label':$scope.user.pointing_block_name};
      $scope.pointing_field_name_sel={'columnname':$scope.user.fieldname};
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve').
                    success(function(data, status) {
                      $scope.pointing_field = data;
      });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=block&kaction=retrieve').
                    success(function(data, status) {
                      $scope.blocks = data;
      });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns_search+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns_search=data;
                  });
      $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns_shown+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns_shown=data;
                  });            
      $scope.functionClick_shown = function( data ) {
           //alert($scope.user.columns_shown);
           if($scope.user.columns_shown!='')
               var arr = $scope.user.columns_shown.split(',');
           else
               var arr = new Array();
           var index =arr.indexOf(data.columnname);
           if(index!==-1)
           {
               arr.splice(index,1);
           }
           else
           {
               arr.push(data.columnname);
           }
           $scope.user.columns_shown=arr.join(',');
       };
       $scope.functionClick_search = function( data ) {
           //alert($scope.user.columns_search);
           if($scope.user.columns_search!='')
               var arr = $scope.user.columns_search.split(',');
           else
               var arr = new Array();
           var index =arr.indexOf(data.columnname);
           if(index!==-1)
           {
               arr.splice(index,1);
           }
           else
           {
               arr.push(data.columnname);
           }
           $scope.user.columns_search=arr.join(',');
       };
       
        $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve_br&selected='+$scope.user.br_id+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.br_id=data;
                  });
       
       $scope.functionClick_br = function( data ) {          
           if ($scope.user.br_id===data.businessrulesid){
               $scope.user.br_id='';
           }
           else{
               $scope.user.br_id=data.businessrulesid;
           }
           
       };
      $scope.refresh_columns_shown = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns_shown+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns_shown=data;
                  });
       };
       $scope.refresh_columns_search = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns_search+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns_search=data;
                  });
       };
       
      $scope.refresh_br = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve_br&selected='+$scope.user.br_id+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.br_id=data;
                  });
      };
      $scope.type_opt=[
          {name:'Reference10 autocomplete(1021)',id:'1021'},
          {name:'Picklist autocomplete(1022)',id:'1022'},
          {name:'MultiPicklist autocomplete(1023)',id:'1023'},
          {name:'MultiPicklist Role(1024)',id:'1024'},
          {name:'Reference10 MultiSelect(1025)',id:'1025'},
          {name:'Reference long description(1026)',id:'1026'}];  
      $scope.type={'id':$scope.user.type};
      $scope.setEditId =  function(user) {
          var br=user.br_id;
            user =JSON.stringify(user);
            if(br!==''){
                $http.post('index.php?module=NgBlock&action=NgBlockAjax&file=ng_fields&kaction='+$scope.act+'&models='+user
                    )
                    .success(function(data, status) {
                          tbl.reload();  
                          $modalInstance.close($scope.selected.item);
                     });
            }
            else{
                alert('Business Rule is mandatory');
            }
        };
      $scope.cancel = function () {
        $modalInstance.dismiss('cancel');
      };
});
{/literal}
</script>
