<input type="hidden" name="record" id="record" value="{$record}"/>
<input type="hidden" name="masterModule" id="masterModule" value="{$masterModule}"/>
<input type="hidden" name="module" id="module" value="{$MODULE}"/>
<input type="hidden" name="onOpenView" id="onOpenView" value="{$onOpenView}"/>
<input type="hidden" name="RoleId" id="RoleId" value="{$RoleId}"/>
<input type="hidden" name="Profiles" id="Profiles" value={$Profiles|@json_encode} />
<input type="hidden" name="OutsideData" id="OutsideData" value="{$OutsideData}" />
<input type="hidden" name="ENTITIES" id="ENTITIES" value={$ENTITIES} />
<input type="hidden" name="LoggedUser" id="LoggedUser" value={$LoggedUser} />
<input type="hidden" name="LoggedUserName" id="LoggedUserName" value={$LoggedUserName} />
<link rel="stylesheet" type="text/css" href="modules/{$MODULE}/styles/formDashboard.css" />
<table width=86% align=center border="0" style="padding:10px;" ng-controller="KibiForm">
    <tr>
        <td style="height:2px">
            <div class="modal-header">
                    <div  id="status-buttons" class="text-center" >
                            <a ng-class="active">{$MODULE_LABEL}</a>
                    </div>
            </div>
            <div>
                    <iframe src="{literal}{{iframeurl | trusted}}{/literal}" 
                            height="600" width="1400"></iframe>
            </div>
            <div class="modal-footer" ng-show="false">
                    <a class="btn btn-default" ng-click="handlePrevious()" ng-show="!isFirstStep()">Back</a>
                    <a class="btn btn-primary" ng-click="handleNext()">{literal}{{getNextLabel()}}{/literal}</a>
            </div>
        </td>
     </tr>
</table>            
<script>
{literal}    
angular.module('demoApp')
.controller('KibiForm',  function ($scope,$http) {
    $scope.steps = ['one'];
    $scope.step = 0;
    $scope.name = '';
    $scope.wizard = {};
    $scope.answers = {};
    var record=document.getElementById('record').value;
    var masterModule=$scope.masterModule=document.getElementById('masterModule').value;
    $scope.module=document.getElementById('module').value;
    var urlRoot="index.php?module="+$scope.module+"&action="+$scope.module+"Ajax";
    var urlParams="&masterRecord="+record+"&masterModule="+masterModule;

    $http.get(urlRoot+"&file=operations&kaction=getEntitiesSession&view=create"+urlParams).
            success(function(data, status) {
                var getEntities=data;
                $scope.iframeurl = getEntities['ConfigEntities'].iframeurl;
    });
})
.filter('trusted', ['$sce', function ($sce) {
    return function(url) {
        return $sce.trustAsResourceUrl(url);
    };
}]);
{/literal}
</script>