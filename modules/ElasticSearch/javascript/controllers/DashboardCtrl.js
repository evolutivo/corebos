/* Controllers */
function DashboardCtrl($scope, elastic,$modal,indexService) {
    $scope.health = {};
    $scope.nodes = [];
    $scope.plugins = [];
    $scope.serverUrl = "";

    $scope.removeIndex = function (index) {
        elastic.removeIndex(index, function () {
            indexDetails();
        });
    };

    $scope.openIndex = function (index) {
        elastic.openIndex(index, function () {
            indexDetails();
        });
    };

    $scope.closeIndex = function (index) {
        elastic.closeIndex(index, function () {
            indexDetails();
        });
    };

    $scope.openChangeReplicas = function (index) {
        indexService.name = index.name;
        if (!isNaN(parseInt(index.numReplicas)) && isFinite(index.numReplicas)) {
            indexService.numReplicas = parseInt(index.numReplicas);
        }

        var opts = {
            backdrop: true,
            keyboard: true,
            backdropClick: true,
            templateUrl: 'modules/ElasticSearch/template/dialog/numreplicas.html',
            controller: 'ChangeNumReplicasCtrl',
            resolve: {fields: function () {
                return angular.copy(indexService);
            }}
        };
        var modalInstance = $modal.open(opts);
        modalInstance.result.then(function (result) {
            if (result) {
                elastic.changeReplicas(result.name,result.numReplicas, function() {
                    indexDetails();
                });
            }
        }, function () {
            // Nothing to do here
        });
    };

    function indexDetails() {
        elastic.indexesDetails(function (data) {
            $scope.indices = data;
        });
    }

    function refreshData() {
        $scope.serverUrl = elastic.obtainServerAddress();

        elastic.clusterHealth(function (data) {
            $scope.health = data;
        });

        elastic.clusterNodes(function (data) {
            $scope.nodes = data;
        });
    }

    $scope.$on('$viewContentLoaded', function () {
        indexDetails();
        refreshData();
    });
}
DashboardCtrl.$inject = ['$scope', 'elastic', '$modal', 'indexService'];
