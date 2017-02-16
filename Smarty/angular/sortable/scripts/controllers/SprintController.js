/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */

'use strict';

angular.module('demoApp').controller('SprintController', ['$scope','$http', 'ngTableParams', function ($scope,$http,ngTableParams) {

  $scope.tableParams = new ngTableParams({
                page: 1,            // show first page
                count: 3  // count per page
                
            }, {
               counts: [5 ,15], 
                //total: 30, // length of data
                getData: function($defer, params) {
                   // $defer.resolve(data.slice((params.page() - 1) * params.count(), params.page() * params.count()));
                $http.get('index.php?module=Project&action=ProjectAjax&file=get_questions&proj_id='+document.getElementsByName('record').item(0).value).
                    success(function(data, status) {
                      var orderedData = data;
                      params.total(data.length);
                      $defer.resolve(orderedData.slice((params.page() - 1) * params.count(),params.page() * params.count()));
                })
                    }
                        });

          
}
]);
