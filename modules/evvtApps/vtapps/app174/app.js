'use strict';
// Declare app level module which depends on filters, and services


angular.module('myApp', ['ngTable','ui.bootstrap']).
        controller('mainCtrl', function ($scope, $http, $filter, ngTableParams) {
            
            var kURL = "module=evvtApps&action=evvtAppsAjax&file=get_products";

             $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 10,          // count per page
                filter: {
                    productname: ''    // initial filter
                       
                }
            }, {
        //total: data.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            $http.get('index.php?'+kURL+'&kaction=retrieve').
                    success(function(data, status) {
                        
                      var orderedData = params.filter() ?
                   $filter('filter')(data, params.filter()) :
                   data;
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
         $scope.statesWithFlags = [];
         $http.get('index.php?'+kURL+'&kaction=autocomplete').
                    success(function(data, status) {
                        
                     $scope.statesWithFlags=data;
                     
                      });
         
        } );
  