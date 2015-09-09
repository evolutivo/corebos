'use strict';
// Declare app level module which depends on filters, and services


angular.module('myApp', ['ngTable','ui.bootstrap']).
        controller('mainCtrl', function ($scope, $http, $filter, ngTableParams) {
            
            var kURL = "module=evvtApps&action=evvtAppsAjax&file=get_products";


 
         $scope.statesWithFlags = [];
         $http.get('index.php?'+kURL+'&kaction=autocomplete_project').
                    success(function(data, status) {
                        
                     $scope.statesWithFlags=data;
                     
                      });
                      
                      $scope.products_kit = [];
         $http.get('index.php?'+kURL+'&kaction=products_kit').
                    success(function(data, status) {
                        
                     $scope.products_kit=data;
                     
                      });
                      
                      $scope.fine_rip = [];
         $http.get('index.php?'+kURL+'&kaction=fine_rip').
                    success(function(data, status) {
                        
                     $scope.fine_rip=data;
                     
                      });
                      $scope.tecnico = [];
         $http.get('index.php?'+kURL+'&kaction=tecnico').
                    success(function(data, status) {
                        
                     $scope.tecnico=data;
                     
                      });
                      
             $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10,          // count per page
                filter: {
                    pcdescriptionname: ''    // initial filter
                       
                }
            }, {
        //total: data.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            $http.get('index.php?'+kURL+'&kaction=retrieve_pcdetails&projectid='+document.getElementById('project_selected_id').value).
                    success(function(data, status) {                        
                      var orderedData = params.filter() ?  $filter('filter')(data, params.filter()) : data;
                      params.total(data.length);
                      $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
    });
           
        }
    });
    //filter:{name:$viewValue}  getLocation($viewValue)
    $scope.getLocation = function(val) {
        
    return $http.get('index.php?'+kURL+'&kaction=autocomplete', {
      params: {
        entered_val: val,
        sensor: true
      }
    })      
  };
  
  $scope.technico = ['same','test'];
 
                      
  $scope.onSelect = function ($item, $model, $label) {
      //alert($item.projectid);
    $scope.$item = $item;
    $scope.$model = $model;
    $scope.$label = $label;
    document.getElementById('project_selected_id').value=$item.projectid;
    $scope.tableParams.reload();


};

$scope.setEditId =  function(pid,fine) {
             $http.post('index.php?'+kURL+'&kaction=update&pcdetailsid='+pid+'&fine='+fine
                )
                .success(function(data, status) {
                    alert('Fine Riparazione modificata');
                      $scope.tableParams.reload();
                     
                 });
        }
       
$scope.add_parti =  function() {
     var prodid=document.getElementById('productid_selected_id').value;
var fine_rip=document.getElementById('fine_rip_selected_id').value;

var parameters='&prodid='+prodid+'&fine_rip='+fine_rip+'&project_id='+document.getElementById('project_selected_id').value;

$http.post('index.php?module=evvtApps&action=evvtAppsAjax&file=get_products&ajax=true&kaction=add_parti'+parameters
                )
                .success(function(data, status) {
                   alert('Parte aggiunta');//{{customSelected.projectid }}
                      $scope.tableParams.reload();
                     
                 });
                
}
            
        } );
  