/*jshint undef: false, unused: false, indent: 2*/
/*global angular: false */


'use strict';

// Declare app level module which depends on other modules
angular.module('demoApp').
  config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/', {templateUrl: 'Smarty/angular/sortable/views/kanban.tpl'});
    $routeProvider.when('/kanban', {templateUrl: 'Smarty/angular/sortable/views/kanban.tpl', controller: 'KanbanController'});
    $routeProvider.when('/sprint', {templateUrl: 'Smarty/angular/sortable/views/sprint.html', controller: 'SprintController'});
    //$routeProvider.otherwise({redirectTo: '/'});
  }]).
  controller('demoController', ['$scope', '$location', function ($scope, $location) {
    $scope.isActive = function (viewLocation) {
      var active = false;
      if ($location.path().indexOf(viewLocation) !== -1) {
        active = true;
      }
      return active;
    };

  }]);


