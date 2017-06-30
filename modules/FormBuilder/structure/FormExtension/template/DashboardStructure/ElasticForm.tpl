<link rel="stylesheet" type="text/css" href="modules/{$MODULE}/styles/formDashboard.css" />
<table width=86% align=center border="0" style="padding:10px;" ng-controller="ElasticForm">
    <tr>
        <td style="height:2px">
            <div class="modal-header">
                    <div  id="status-buttons" class="text-center" >
                            <a ng-class="{literal}{'active':isCurrentStep(0)}{/literal}" ng-click="setCurrentStep(0)"><span>1</span> General Info</a>
                    </div>
                    <!--<button type="button" class="close" ng-click="dismiss('No bueno!')" aria-hidden="true">&times;</button>
                    <h3>{literal}{{name}}{/literal}</h3>-->
            </div>
            <div class="modal-body">
                    <div>                            
                        <div ng-switch="getCurrentStep()" class="slide-frame">
                            <div ng-switch-when="one" class="wave" ng-repeat="wizardblock in wizard">
                                <fieldset ng-switch="wizardblock.type">
                                    <div ng-switch-when="string" class="form-group" ng-switch-when="string">
                                            <label class="control-label" >{literal}{{wizardblock.name}}{/literal}</label>
                                            <div>
                                                    <input class="form-control" type="text" class="input-xlarge" ng-model="answers[wizardblock.name]">
                                            </div>
                                    </div>
                                    <div ng-switch-when="multiselect" class="form-group" ng-switch-when="string">
                                            <label class="control-label" >{literal}{{wizardblock.name}}{/literal}</label>
                                            <div>
                                                <select class="form-control" multiple="multiple"  ng-model="answers[wizardblock.name]">
                                                    <option>Cheese</option>
                                                    <option>Guacamole</option>
                                                    <option>Mild Salsa</option>
                                                    <option>Hot Salsa</option>
                                                    <option>Cilantro</option>
                                                </select>
                                            </div>
                                    </div>
                                    <div ng-switch-when="checkbox" class="form-group" ng-switch-when="string">
                                        <label>
                                            <input type="checkbox" id="optionsCheckbox" ng-model="answers[wizardblock.name]">
                                            {literal}{{wizardblock.name}}{/literal}
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <a class="btn btn-default" ng-click="handlePrevious()" ng-show="!isFirstStep()">Back</a>
                    <a class="btn btn-primary" ng-click="handleNext()">{literal}{{getNextLabel()}}{/literal}</a>
            </div>
        </td>
     </tr>
</table>            
<script>
{literal}    
angular.module('demoApp')
.controller('ElasticForm',  function ($scope,$http) {
    $scope.steps = ['one'];
    $scope.step = 0;
    $scope.name = '';
    $scope.wizard = {};
    $scope.answers = {};

    $http.get("index.php?module={/literal}{$MODULE}&action={$MODULE}{literal}Ajax&file=operations&kaction=getEntities").
            success(function(data, status) {
              $scope.wizard = data[0].columns;
              $scope.name=data[0].name;
    });
    $scope.isFirstStep = function () {
        return $scope.step === 0;
    };

    $scope.isLastStep = function () {
        return $scope.step === ($scope.steps.length - 1);
    };

    $scope.isCurrentStep = function (step) {
        return $scope.step === step;
    };

    $scope.setCurrentStep = function (step) {
        $scope.step = step;
    };

    $scope.getCurrentStep = function () {
        return $scope.steps[$scope.step];
    };

    $scope.getNextLabel = function () {
        return ($scope.isLastStep()) ? 'Submit' : 'Next';
    };

    $scope.handlePrevious = function () {
        $scope.step -= ($scope.isFirstStep()) ? 0 : 1;
    };

    $scope.handleNext = function () {
        if ($scope.isLastStep()) {
            var answers =$scope.answers;
            $http.post("index.php?module={/literal}{$MODULE}&action={$MODULE}{literal}Ajax&file=operations&kaction=saveAnswers"
                ,{answers: answers})
                    .success(function(data, status) {
                      alert(data);
            });
            //$modalInstance.close(modal.wizard);
        } else {
            $scope.step += 1;
        }
    };
});
{/literal}
</script>