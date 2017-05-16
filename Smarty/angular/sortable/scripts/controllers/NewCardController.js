                                                  /*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */


'use strict';

angular.module('demoApp').controller('NewCardController', ['$http','$rootScope','$injector','$scope','$timeout', '$modalInstance', 'column' ,'files','executingAction', function ($http,$rootScope,$injector,$scope,$timeout, $modalInstance, column,files,executingAction) {


  function initScope(scope) {
    scope.columnName = column.name;
    scope.status= '';
    scope.title = '';
    scope.details = '';
    scope.dynamic = 50;
    var nextsubstatus=column.cards[0].ptname;
    var pfid=column.cards[0].pfid;
    var questions=column.cards[0].details;

    $timeout(function () {
        }, 1000);
       $timeout(function () {
           function rendiconta(){

               $http({
                    method: 'POST',
                    url: 'index.php?module=Utilities&action=UtilitiesAjax&kaction=rendiconta&file=get_substatuses&id='
                                +document.getElementsByName('record').item(0).value+'&pfid='+pfid+'&next_sub='+nextsubstatus,
                    headers: { 'Content-Type': undefined },
                    transformRequest: function (data) {
                        var formData = new FormData();
                        for (var i = 0; i < data.files.length; i++) {
                            formData.append("filename" , data.files[i]);
                        }
                        formData.append("questions" ,JSON.stringify(questions));
                        return formData;
                    },
                    data: { files: files}
                })
                        .success(function(data) {
                              location.reload();
                });
            };
            var proceed=true;
            var hasUploaded=(files.length>0);
            var hasDocQuestion=false;

            angular.forEach(questions, function(value, key) {
                var answer=value['answers'];
                var questiontype=value['questiontype'];
                var d_obblig=value['d_obblig'];
                if( (answer===undefined || answer==='') && questiontype!=='documento' && d_obblig==="1"){
                    proceed=false;
                }
                if(questiontype==='documento'){
                    hasDocQuestion=true;
                }
            });
            if(proceed && (hasUploaded || !hasDocQuestion) ){
                rendiconta();
            }
            else{
                alert('Indica tutti i campi obbligatori');
                executingAction.state=false;
            }
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

