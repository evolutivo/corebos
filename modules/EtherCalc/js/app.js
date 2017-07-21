'use strict';
var app = angular.module('ngTableTutorial', ['ngResource', 'ngRoute','ngTable']);
        app.controller('tableController', function ($scope, $filter,$http,$sce,$window,ngTableParams) {
            $scope.frameName="";
            $scope.frameUrl="";
            $scope.frameName1="";
            $scope.ethid="";
            //$scope.frameUrl1="http://localhost:8000/";
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
              console.log(data);
              $scope.frameUrl=data.spspreadsheet;
              $scope.ethid=item.id;
              $scope.newurl=data.siteurl;
    });
    
}
$scope.updatedata=function(ethid){
 //   alert(ethid);
    $http.get('modules/EtherCalc/updatecrm.php?id='+ethid).success(function(data,status){
         console.log("ha finito");
          console.log(data);
              $scope.frameUrl="http://"+data.ethlink;
              $scope.ethid=ethid;
              $scope.newurl=data.ethurl;
   
         //$window.location.reload();
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
     

