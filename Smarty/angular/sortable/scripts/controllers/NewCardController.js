/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */


'use strict';

angular.module('demoApp').controller('NewCardController', ['$http','$rootScope','$injector','$scope','$timeout', '$modalInstance', 'column' , function ($http,$rootScope,$injector,$scope,$timeout, $modalInstance, column) {
  
  function initScope(scope) {
    scope.columnName = column.name;
    scope.status= '';
    scope.title = '';
    scope.details = '';
    scope.dynamic = 50;
    var nextsubstatus=column.cards[0].ptname;
    var pfid=column.cards[0].pfid;
    
     console.log(column);
     $timeout(function () {
        }, 1000);
       $timeout(function () {
           function rendiconta(){
                $http.post('index.php?module=Utilities&action=UtilitiesAjax&kaction=rendiconta&file=get_substatuses&id='+document.getElementsByName('record').item(0).value+'&pfid='+pfid+'&next_sub='+nextsubstatus).success(function(data) {
                      location.reload();
                });
            };
            rendiconta();
            $modalInstance.close();
    }, 1000);
       
  }

  $scope.addNewCard = function () {
    if (!this.newCardForm.$valid) {
      return false;
    }
    $modalInstance.close({title: this.title, casepf: this.casepf , details: this.details});
  };

  $scope.close = function () {
    $modalInstance.close();
  };

  initScope($scope);

}]);

