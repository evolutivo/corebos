'use strict';

// Declare app level module which depends on other modules
angular.module('demoApp',['Form.controllers','Form.services','ngMaterial','ngTable','ngRoute',
    'mgcrea.ngStrap','mdPickers']).
  config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/', {
        templateUrl: 'Smarty/templates/modules/'+getModule()+'/views/CreateView.tpl',
        controller: 'InitialController'
    });
    $routeProvider.when('/detail', {
        templateUrl: 'Smarty/templates/modules/'+getModule()+'/views/DetailView.tpl',
        controller: 'DetailController'
    });
    $routeProvider.when('/create', {
        templateUrl: 'Smarty/templates/modules/'+getModule()+'/views/CreateView.tpl',
        controller: 'CreateController'
    });
    function getModule(){
        return document.getElementById('module').value;
    }
    $routeProvider.otherwise({redirectTo: '/'});
  }]);

