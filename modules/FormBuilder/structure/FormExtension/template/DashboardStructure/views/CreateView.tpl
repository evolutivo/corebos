<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="edit-profile-block" style="display: flex;flex-direction: column;align-items: center;" ng-model="executingAction.state" ng-show="executingAction.state">
    <br/><br/>
    <p style="text-transform: uppercase;font-size: 18px;">
        Validazione in corso
    </p>
</div>
<div class="edit-profile-block" style="display: flex;flex-direction: column;align-items: center;" ng-model="executingSubmit.state" ng-show="executingSubmit.state">
        <br/><br/>
        <p style="text-transform: uppercase;font-size: 18px;">
            Salvataggio in corso
        </p>
</div>
<div class="modal-header">
        <md-button class="md-raised md-primary" ng-click="backDash(megaText)" ng-if='bachToDashb!==""'> << Back To Dashboard</md-button>
        <div  id="status-buttons" class="text-center" ng-if="steps.length>1">
                <a ng-class="{'active':isCurrentStep($index)}" ng-repeat="current_step in steps"><span>{{$index+1}}</span> {{current_step.block_label}}</a>
        </div>        
        <!--<button type="button" class="close" ng-click="dismiss('No bueno!')" aria-hidden="true">&times;</button>
        <h3>{{name}}</h3>
        ng-click="setCurrentStep($index,current_step.block_label)" 
        -->
</div>
<div layout="row" layout-sm="column" layout-align="space-around"  ng-show="loading">
  <md-progress-circular md-mode="indeterminate"></md-progress-circular>
</div>
<div class="modal-body" style="max-height: 800px;" ng-show="!loading">
        <div>                   
            <div class="slide-frame">
                <div ng-if="getCurrentStep()==step" style="display: flex;flex-direction: row;" class="wave">
                   <div layout="column" layout-fill ng-repeat="blocks in steps[step]['fields']['blocks']" style="border: 1px solid #e5e5e5;height:100%;">
                        <md-toolbar style="background-color: steelblue;border-style: solid;border-spacing: 1px;border-color: white;border-width: 1px;">
                            <div class="md-toolbar-tools">
                              <h3>
                                <span>{{blocks.block_label}}</span>
                              </h3>
                            </div>
                        </md-toolbar>
                        <div layout="" layout-sm="column" layout-padding ng-repeat="wizardRow in blocks['rows']" ng-show="showLogicRow(wizardRow);">
                            <div ng-switch="getFieldType(wizardfield,steps[step]['fields']['info'])" ng-show="showLogic(wizardfield);" flex="" layout="row" ng-repeat="wizardfield in wizardRow" class="sample-show-hide" >
                                    <md-input-container class="md-block" flex-gt-sm>
                                        <label ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])!=56 && getFieldType(wizardfield,steps[step]['fields']['info'])!=5 && getFieldType(wizardfield,steps[step]['fields']['info'])!=6 && getFieldType(wizardfield,steps[step]['fields']['info'])!=70 && getFieldType(wizardfield,steps[step]['fields']['info'])!=23">{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}</label>
                                        <md-fab-speed-dial ng-if="false" md-open="isOpen" md-direction="left" ng-class="selectedMode" style="float:right;" ng-mouseenter="isOpen=true" ng-mouseleave="isOpen=false">
                                            <md-fab-trigger>
                                                <md-icon>build</md-icon>
                                            </md-fab-trigger>
                                            <md-fab-actions>
                                                <i class="material-icons" ng-if="wizardfield=='master_pi'" ng-click="executeAction('329144','html');">
                                                    <md-tooltip md-direction="top" md-visible="true">Search by PIVA</md-tooltip>
                                                    &nbsp;settings&nbsp;
                                                </i>
                                                <i class="material-icons" ng-if="wizardfield=='cod_fisc'" ng-click="executeAction('329161','html');">
                                                    <md-tooltip md-direction="top" md-visible="true">Search by CF</md-tooltip>
                                                    &nbsp;settings&nbsp;
                                                </i>
                                            </md-fab-actions>
                                        </md-fab-speed-dial>
                                        <input type="text" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==1 || getFieldType(wizardfield,steps[step]['fields']['info'])==2 || getFieldType(wizardfield,steps[step]['fields']['info'])==17 || getFieldType(wizardfield,steps[step]['fields']['info'])==106"  ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield);">
                                        
                                        <input type="text" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==4"  ng-model="answers[wizardfield]" ng-disabled="true">
                                        
                                        <input type="email" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==13 || getFieldType(wizardfield,steps[step]['fields']['info'])==104" ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                        
                                        <input type="text" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==11 || getFieldType(wizardfield,steps[step]['fields']['info'])==7" ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                        
                                        <input type="number" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==71" ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                        
                                        <input type="password" ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==99"  ng-model="answers[wizardfield]" id="{{wizardfield}}">
                                                                               
                                        <div ng-switch-when="10" ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-content="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>
                                            <br/><br/>
                                            <a href="" >{{ui10Readable[wizardfield]}}</a>
                                        </div>
                                        <label ng-switch-when="10" class="input__label input__label--editable">
                                          <i class="icon--editable material-icons">edit</i>
                                        </label>
                                        <span ng-switch-when="10" ng-show="disableLogic[wizardfield]"><br/><br/>{{ui10Readable[wizardfield]}}</span>
                                        
                                        <div ng-switch-when="98" ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-content="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>
                                            <br/><br/>
                                            <a href="" >{{ui10Readable[wizardfield]}}</a>
                                        </div>
                                        <label ng-switch-when="98" class="input__label input__label--editable">
                                          <i class="icon--editable material-icons">edit</i>
                                        </label>
                                        <span ng-switch-when="98" ng-show="disableLogic[wizardfield]"><br/><br/>{{ui10Readable[wizardfield]}}</span>
                                        
                                        <md-select ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==15 || getFieldType(wizardfield,steps[step]['fields']['info'])==16 || getFieldType(wizardfield,steps[step]['fields']['info'])==111 || getFieldType(wizardfield,steps[step]['fields']['info'])==115" ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield);">
                                              <md-option ng-repeat="option in getFieldOptions(wizardfield,steps[step]['fields']['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answers" value="{{option[1]}}" ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                                                {{option[0]}}
                                              </md-option>
                                        </md-select>
                                              
                                        <div ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==5 || getFieldType(wizardfield,steps[step]['fields']['info'])==6 ||  getFieldType(wizardfield,steps[step]['fields']['info'])==70 ||  getFieldType(wizardfield,steps[step]['fields']['info'])==23" class="md-block" flex-gt-sm>
                                            <label>{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}</label><br/>
                                            <mdp-date-picker mdp-format="DD-MM-YYYY" ng-model="answers[wizardfield]" ng-change="automatic(wizardfield)" mdp-disabled="disableLogic[wizardfield]"></mdp-date-picker>
                                            <mdp-time-picker ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==23" ng-model="answers[wizardfield]" ng-change="automatic(wizardfield)"></mdp-time-picker>
                                        </div>
                                                                                 
                                        <md-select ng-switch-when="53" ng-model="answers[wizardfield+'_'+steps[step]['entityname']]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                            <md-optgroup label="Users">
                                                <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,steps[step]['fields']['info'])[1]" value="{{key}}">
                                                  <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                                                </md-option>
                                            </md-optgroup> 
                                            <md-optgroup label="Groups">
                                                <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,steps[step]['fields']['info'])[2]" value="{{key}}">
                                                  <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                                                </md-option>
                                            </md-optgroup> 
                                        </md-select>
                                    
                                    <div ng-if="getFieldType(wizardfield,steps[step]['fields']['info'])==19 || getFieldType(wizardfield,steps[step]['fields']['info'])==21 || getFieldType(wizardfield,steps[step]['fields']['info'])==24">
                                        <textarea  ng-if="wizardfield!='description'"  ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)"> 
                                        </textarea>
                                        <textarea  ng-if="wizardfield=='description'"  ng-model="answers[wizardfield+'_'+steps[step]['entityname']]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield+'_'+steps[step]['entityname'])"> 
                                        </textarea>
                                    </div>
                                         
                                    <div ng-switch-when="56" >    
                                        <md-checkbox  ng-model="answers[wizardfield]" class="md-block" flex-gt-xs  ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                            <span style="color:steelblue;">{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}</span>
                                        </md-checkbox> 
                                    </div>
                                        
                                    <div ng-switch-when="1025" >    
                                        <div ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-content="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupEvoMultiRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>  
                                            <a href="">Click to Choose</a>
                                        </div>
                                        <label class="input__label input__label--editable">
                                            <i class="icon--editable material-icons">edit</i>
                                        </label>
                                            <md-chips  ng-model="uiEvoReadable[wizardfield]" readonly="false" md-on-remove="refEvoRemoved($chip, $index,wizardfield);">
                                                <md-chip-template>
                                                    <span>
                                                      <strong>[{{$chip.count}}] {{$chip.crmname}}</strong>
                                                    </span>
                                                </md-chip-template>
                                            </md-chips>
                                    </div>
                                        
                                    <div ng-switch-when="33" >
                                                <md-select class="form-control" multiple="multiple"  ng-model="answers[wizardfield]" ng-disabled="disableLogic[wizardfield]" ng-change="automatic(wizardfield)">
                                                    <md-option ng-repeat="option in getFieldOptions(wizardfield,steps[step]['fields']['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answers" value="{{option[1]}}" ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                                                        {{option[0]}}
                                                    </md-option>
                                                </md-select>
                                    </div>
                                    
                                    <div ng-switch-when="1025" >    
                                        <div ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-content="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupEvoMultiRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>  
                                            <a href="">Click to Choose</a>
                                        </div>
                                        <label class="input__label input__label--editable">
                                            <i class="icon--editable material-icons">edit</i>
                                        </label>
                                        <md-chips  placeholder="" secondary-placeholder="" ng-model="uiEvoReadable[columns]" readonly="false"></md-chips>
                                    </div>
                                    <div ng-switch-when="1026">
                                    <md-autocomplete 
                                          ng-disabled="disableLogic[wizardfield]"
                                          md-no-cache="false"
                                          md-selected-item="selectedItem[wizardfield]"
                                          md-search-text-change="searchValueChange(searchValue[wizardfield],wizardfield)"
                                          md-search-text="searchValue[wizardfield]"
                                          md-selected-item-change="selectedItemChange(item,wizardfield)"
                                          md-items="item in querySearch(searchValue,wizardfield)"
                                          md-item-text="item[wizardfield]"
                                          md-min-length="1"
                                          md-input-maxlength="100" 
                                          md-floating-label="{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}" 
                                          placeholder="Cerca...">
                                   <md-item-template>
                                   <span md-highlight-text="ctrl.searchValue[wizardfield]" md-highlight-flags="^i">{{item[wizardfield]}}</span>
                                   </md-item-template>
                                   <md-not-found>
                                       Nessuna {{getFieldLabel(wizardfield,steps[step]['fields']['info'])}}  corrispondenti "{{searchValue[wizardfield]}}".
                                   </md-not-found>
                                   </md-autocomplete>
                                    <p ng-show="isNotValidAutocomplete[wizardfield]" style="color:red;">{{getFieldLabel(wizardfield,steps[step]['fields']['info'])}} non e valido</p>
                                   </div>
                                        
                                    <p style="color:red;">{{getMandatoryText(wizardfield,steps[step]['fields']['info'])}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield+'_'+steps[step]['entityname']]" style="color:red;">{{currentErrorMessage[wizardfield+'_'+steps[step]['entityname']]}}</p>
                                    </md-input-container>
                                
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer">
    <md-fab-speed-dial ng-if="extActions.length>0 || (steps[step]['doc_widget'])" md-open="isOpen" md-direction="{{selectedDirection}}" ng-class="selectedMode" ng-mouseenter="isOpen=true" ng-mouseleave="isOpen=false">
        <md-fab-trigger>
            <md-button aria-label="menu" class="md-fab" style="background-color: steelblue;">
              {{label}}
            </md-button>
        </md-fab-trigger>
        <md-fab-actions>
          <md-button ng-if="(steps[step]['doc_widget'])" class="md-fab md-raised md-mini" ng-click="openBottomSheet();" style="background-color: steelblue;">
            <md-tooltip md-direction="top" md-visible="false">Allega Documenti</md-tooltip>
            <i class="material-icons">attach_file</i>
          </md-button>
          <md-button ng-repeat="act in extActions" class="md-fab md-raised md-mini">
            <md-tooltip md-direction="top" md-visible="false">{{act.label}}</md-tooltip>
            <img src="{{act.iconpath}}" style="width:30px;height:30px;" ng-click="executeAction(act.actionid,act.output_type);" />
          </md-button>
        </md-fab-actions>
    </md-fab-speed-dial>
    <a class="btn btn-default" ng-click="handlePrevious()" ng-show="!isFirstStep()">Back</a>
    <a class="btn btn-default" ng-click="save_repeat(answers,getCurrentStep())" ng-show="false" ng-disabled="true">Save&Repeat</a>
    <a class="btn btn-primary" ng-click="handleNext()" ng-disabled="processing">{{getNextLabel()}}</a>

</div>
<script type="text/ng-template" id="PrimoSguardo">
    <md-dialog class="md-sidenav-right md-whiteframe-4dp" md-component-id="right" style="width:500px;height:680px;">
      <md-toolbar class="md-theme-light">
        <h1 class="md-toolbar-tools">Dati della risposta</h1>
      </md-toolbar>
      <md-dialog-content layout-padding>
          <br/><br/>
          <table width='100%' >
              <tr ng-repeat="(key,column) in outputActionInfo.columns" ng-init="sec_col=key+1 ">
                  <td ng-if="$index%2==0">
                      <br/>
                      <md-input-container>
                        <label>{{outputActionInfo.columnslabels[key]}}</label>
                        <input type="text" value="{{outputActionInfo.values[key]}}" ng-disabled="true">
                      </md-input-container>
                  </td>
                  <td ng-if="$index%2==0">
                      <br/>
                      <md-input-container>
                        <label>{{outputActionInfo.columnslabels[sec_col]}}</label>
                        <input type="text" value="{{outputActionInfo.values[sec_col]}}" ng-disabled="true">
                      </md-input-container>
                  </td>
              </tr>
          </table>
        <md-dialog-actions layout="row">
            <md-button ng-click="close()" class="md-primary">
              Non Accettare
            </md-button>
            <md-button ng-click="accept()" class="md-primary">
              Accettare
            </md-button>
        </md-dialog-actions>
      </md-dialog-content>
    </md-dialog>
</script>