/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */

'use strict';
var demoapp=angular.module('demoApp');
      demoapp.run(['$http', '$rootScope','$location',
   function($http, $rootScope) {
           $http.get('index.php?module=Utilities&action=UtilitiesAjax&file=get_substatuses&kaction=retrieveProcessFlow&id='+document.getElementsByName('record').item(0).value).success(function(data) {
                  $rootScope.resource = data;
                //console.log($rootScope.resource.columns[0].cards[0].pfid);   
               });  
    }
  ])
  
angular.module('demoApp').controller('KanbanController', ['$scope','$rootScope','$http', 'BoardService','BoardManipulator', function ($scope,$rootScope,$http, BoardService,BoardManipulator) {

    $scope.fill_sortable=function () {
        $http.get('index.php?module=Utilities&action=UtilitiesAjax&kaction=retrieveProcessFlow&file=get_substatuses&id='+document.getElementsByName('record').item(0).value).success(function(data) {
          $scope.arr=data;
          $scope.kanbanBoard = BoardService.kanbanBoard($scope.arr);  
             
        });
    };
    $scope.processflowselected=function(pfid,cpfid){ 
        document.getElementById('selectedprocessflow').setAttribute('value',pfid);
        document.getElementById('currentprocessflow').setAttribute('value',cpfid);
    }
    $scope.fill_sortable();  
    //an array of files selected
    $scope.files = [];
    $scope.executingAction={state:false};
    //listen for the file selected event
    $scope.$on("fileSelected", function (event, args) {
        $scope.$apply(function () {            
            //add the file object to the scope's files collection
            $scope.files.push(args.file);
            console.log($scope.files);
        });
    });
    
    $scope.$on('handleBroadcast', function() {
        $http.get('index.php?module=Utilities&action=UtilitiesAjax&kaction=retrieveProcessFlow&file=get_substatuses&id='+document.getElementsByName('record').item(0).value).success(function(data) {
            $scope.arr=data;
            $scope.kanbanBoard = BoardService.kanbanBoard($scope.arr);  
        });
    });  
    
    $scope.isCollapsed = true;
    $scope.kanbanSortOptions = {
        //restrict move across columns. move only within column.
        /*accept: function (sourceItemHandleScope, destSortableScope) {
         return sourceItemHandleScope.itemScope.sortableScope.$id !== destSortableScope.$id;
         },*/
        itemMoved: function (event) {
          //event.source.itemScope.modelValue.status = event.dest.sortableScope.$parent.column.name;
        },
        orderChanged: function (event) {
        },
        containment: '#board'
    };

    $scope.removeCard = function (column, card) {
        BoardService.removeCard($scope.kanbanBoard, column, card);
    };

    $scope.addNewCard = function (column,files) {
        $scope.executingAction.state=true;
        BoardService.addNewCard($scope.kanbanBoard, column,files,$scope.executingAction);
    };
}])
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
  });;
