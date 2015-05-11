<script src="Smarty/angular/angular.min.js"></script>
<script  src="Smarty/angular/ng-table.js"></script>
<link data-require="ng-table@*" data-semver="0.3.0" rel="stylesheet" href="http://bazalt-cms.com/assets/ng-table/0.3.0/ng-table.css" />
<script src="http://angular-ui.github.io/bootstrap/ui-bootstrap-tpls-0.6.0.js"></script>
<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
<script src="Smarty/angular/angular-multi-select.js"></script>  
<link rel="stylesheet" href="Smarty/angular/angular-multi-select.css">

<table  width="100%" width=98% align=center border="0" ng-app="cbApp">
    <tr><td style="height:2px"><br/><br/></td></tr>
    <tr>
        <td style="padding-left:20px;padding-right:50px" class="moduleName" nowrap colspan="2">
            NgBlocks Creator</td>
     </tr>
     <tr>         
         <td class="showPanelBg" style="margin-left:20px;">
             <br/><br/>
             <table width=100% style="padding-left:10px;margin-left:20px" border="0"><tr>
                     <td   ng-controller="ng_Block">   
                        <table  ng-table="tableParams" class="table  table-bordered table-responsive" width=100% >
                            <tr >
                                <td colspan="14">
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
                              <td  data-title="'Nr Page'" width="40%"> 
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
                              
                            </tr>
                        </table>                               
            </td>
          </tr>
      </table>
 </td></tr>
</table>

<script>
{literal}
angular.module('cbApp',['ngTable','ui.bootstrap','multi-select']) 
.controller('ng_Block', function($scope, $http, $modal, ngTableParams) {

            $scope.new_user={"id":"","id_hidden":"","name":"","module_name":"",
                "module_name_trans":"","pointing_block_name":"",
                "pointing_block_name_trans":"","pointing_module_name":"",
                "pointing_module_name_trans":"","pointing_field_name":"",
                "pointing_field_name_trans":"","columns":"","cond":"",
                "paginate":"","nr_page":"","add_record":"",
                "sort":" ","edit_record":"","delete_record":""};
            
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
            }
            
              $scope.open = function (us,type) {
                  if(type=='add'){
                     us= {"id":"","id_hidden":"","name":"","module_name":"",
                            "module_name_trans":"","pointing_block_name":"",
                            "pointing_block_name_trans":"","pointing_module_name":"",
                            "pointing_module_name_trans":"","pointing_field_name":"",
                            "pointing_field_name_trans":"","columns":"","cond":"",
                            "paginate":"","nr_page":"","add_record":"",
                            "sort":" ","edit_record":"","delete_record":""};
                  }
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
        
        });
        
        
angular.module('cbApp')
.controller('ModalInstanceCtrl',function ($scope,$http,$modalInstance,user,type,tbl) {

      $scope.user = user;
      $scope.selected = {
        item: 0
      };
      $scope.Action = (type === 'add' ? 'Create' : 'Edit');
      
      $scope.module_sel=[];
      $scope.blocks=[];
      $scope.pointing_field=[];
      $scope.mod_sel={'tablabel':$scope.user.module_name,'tabid':$scope.user.module_id};
      $scope.pointing_module_name_sel={'tablabel':$scope.user.pointing_module_name};
      $scope.pointing_block_name_sel={'label':$scope.user.pointing_block_name};
      $scope.pointing_field_name_sel={'columnname':$scope.user.pointing_field_name};
      
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
           //alert(data.columnname);
           if($scope.user.columns!='')
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
       
       $scope.refresh_columns = function(  ) {
           $http.get('index.php?module=NgBlock&action=NgBlockAjax&file=field&kaction=retrieve&selected='+$scope.user.columns+'&pointing_module='+$scope.user.pointing_module_name).
                success(function(data, status) {
                 $scope.columns=data;
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
angular.module('cbApp')
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
angular.module('cbApp')
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
}
);
{/literal}
</script>
