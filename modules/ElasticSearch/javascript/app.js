//'use strict';

// Declare app level module which depends on filters, and services
var demoApp = angular.module('demoApp', ['ngRoute','demoApp.filters', 'demoApp.services', 'demoApp.directives', 'ui.bootstrap','elasticsearch','gridshore.c3js.chart']).
        config(['$routeProvider', function ($routeProvider) {
        console.log("ketu");
            $routeProvider.when('/dashboard', {templateUrl: 'modules/ElasticSearch/partials/dashboard.html', controller: DashboardCtrl});
            $routeProvider.when('/node/:nodeId', {templateUrl: 'modules/ElasticSearch/partials/node.html', controller: NodeInfoCtrl});
            $routeProvider.when('/search', {templateUrl: 'modules/ElasticSearch/partials/search.html', controller: SearchCtrl});
            $routeProvider.when('/query', {templateUrl: 'modules/ElasticSearch/partials/query.html', controller: QueryCtrl});
            $routeProvider.when('/graph', {templateUrl: 'modules/ElasticSearch/partials/graph.html', controller: GraphCtrl});
            $routeProvider.when('/tools/suggestions', {templateUrl: 'modules/ElasticSearch/partials/suggestions.html', controller: SuggestionsCtrl});
            $routeProvider.when('/tools/whereareshards', {templateUrl: 'modules/ElasticSearch/partials/whereareshards.html', controller: WhereShardsCtrl});
            $routeProvider.when('/tools/snapshots', {templateUrl: 'modules/ElasticSearch/partials/snapshots.html', controller: SnapshotsCtrl});
            $routeProvider.when('/about', {templateUrl: 'modules/ElasticSearch/partials/about.html'});
            $routeProvider.otherwise({redirectTo: '/index.php?module=ElasticSearch&action=index&partial=dashboard'});
        }]);

demoApp.value('localStorage', window.localStorage);

demoApp.factory('$exceptionHandler',['$injector', function($injector) {
    return function(exception, cause) {
        console.log(exception);
        var errorHandling = $injector.get('errorHandling');
        errorHandling.add(exception.message);
        throw exception;
    };
}]);

var serviceModule = angular.module('demoApp.services', []);
serviceModule.value('version', '1.2.1');