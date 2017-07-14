'use strict';
var app = angular.module('ngTableTutorial', ['ngResource', 'ngRoute','ngTable']);
        app.controller('tableController', function ($scope, $filter,$http,$sce,ngTableParams) {
            $scope.frameName="";
            $scope.frameUrl="";
            $scope.frameName1="";
            $scope.ethid="";
            //$scope.frameUrl1="http://localhost:8000/";
            $scope.users = [{"id":1,"first_name":"Philip","last_name":"Kim","email":"pkim0@mediafire.com","country":"Indonesia","ip_address":"29.107.35.8"},
                            {"id":2,"first_name":"Judith","last_name":"Austin","email":"jaustin1@mapquest.com","country":"China","ip_address":"173.65.94.30"},
                            {"id":3,"first_name":"Julie","last_name":"Wells","email":"jwells2@illinois.edu","country":"Finland","ip_address":"9.100.80.145"},
                            {"id":4,"first_name":"Philip","last_name":"Kim","email":"pkim0@mediafire.com","country":"Indonesia","ip_address":"29.107.35.8"},
                            {"id":5,"first_name":"Judith","last_name":"Austin","email":"jaustin1@mapquest.com","country":"China","ip_address":"173.65.94.30"},
                            {"id":6,"first_name":"Julie","last_name":"Wells","email":"jwells2@illinois.edu","country":"Finland","ip_address":"9.100.80.145"},
                            {"id":7,"first_name":"Philip","last_name":"Kim","email":"pkim0@mediafire.com","country":"Indonesia","ip_address":"29.107.35.8"},
                            {"id":8,"first_name":"Judith","last_name":"Austin","email":"jaustin1@mapquest.com","country":"China","ip_address":"173.65.94.30"},
                            {"id":9,"first_name":"Julie","last_name":"Wells","email":"jwells2@illinois.edu","country":"Finland","ip_address":"9.100.80.145"},
                            {"id":10,"first_name":"Philip","last_name":"Kim","email":"pkim0@mediafire.com","country":"Indonesia","ip_address":"29.107.35.8"},
                            {"id":11,"first_name":"Judith","last_name":"Austin","email":"jaustin1@mapquest.com","country":"China","ip_address":"173.65.94.30"},
                            {"id":12,"first_name":"Julie","last_name":"Wells","email":"jwells2@illinois.edu","country":"Finland","ip_address":"9.100.80.145"},
                            {"id":13,"first_name":"Philip","last_name":"Kim","email":"pkim0@mediafire.com","country":"Indonesia","ip_address":"29.107.35.8"},
                            {"id":14,"first_name":"Judith","last_name":"Austin","email":"jaustin1@mapquest.com","country":"China","ip_address":"173.65.94.30"},
                            {"id":15,"first_name":"Julie","last_name":"Wells","email":"jwells2@illinois.edu","country":"Finland","ip_address":"9.100.80.145"}];
 $scope.alldata=[];
$scope.trustSrcurl = function(data) 
{
    return $sce.trustAsResourceUrl(data);
}
$scope.showlist=function(data){
    $scope.frameName="";
    $scope.frameName1="";
    $scope.data=data;
     for(var i = 0; i < $scope.data.length; i++){
        $scope.data[i].isRowSelected =false;
        
    }

}
$scope.showlist1=function(){
    $scope.frameName1="notempty";
}
$http.get('modules/EtherCalc/listspreadsheets.php').success(function(data,status){
          $scope.users=data;
              $scope.usersTable = new ngTableParams({
                page: 1,
                count: 10
            }, {
                total: $scope.users.length, 
                getData: function ($defer, params) {
                    $scope.data = $scope.users.slice((params.page() - 1) * params.count(), params.page() * params.count());
                    $defer.resolve($scope.data);
                }
            });

});

$scope.createspreadsheet=function(){
    //alert("createspreadsheet");
    $http.get('modules/EtherCalc/creaspreadsheet.php').success(function(data,status){
          //alert("pasredirektit");
          $scope.frameUrl1=data;
    });
}
$scope.toggleSelection = function(item){
    console.log("inhere");
    console.log(item.id);
    item.isRowSelected = !item.isRowSelected;
    $scope.frameName="test";
    $scope.frameName1="notEmpty";
    $http.get('modules/EtherCalc/specificspreadsheet.php?id='+item.id).success(function(data,status){
              console.log("ketuketu");
              //alert("ketuketu");
              //alert(data);
              $scope.frameUrl=data;
              $scope.ethid=item.id;
    });
    
}
$scope.updatedata=function(ethid){
 //   alert(ethid);
    $http.get('modules/EtherCalc/updatecrm.php?id='+ethid).success(function(data,status){
         console.log("ha finito");
    });
}


$scope.isAnythingSelected = function () {
    for(var i = 0; i < $scope.data.length; i++){
        if($scope.data[i].isRowSelected === true){
            return true;
        }
    }

    return false;
};
        
            });
     

