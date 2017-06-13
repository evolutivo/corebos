<div layout="column" layout-fill ng-repeat="blocks in FatherDetailViewBlocks['blocks']" style="border: 1px solid #e5e5e5;height:100%;">
    <md-toolbar style="background-color: steelblue;border-style: solid;border-spacing: 1px;border-color: white;border-width: 1px;">
        <div class="md-toolbar-tools">
          <h3>
            <span>{{blocks.block_label}}</span>
          </h3>
        </div>
    </md-toolbar>
    <div layout="" layout-sm="column" layout-padding ng-repeat="wizardRow in blocks['rows']">
        <div ng-switch="getFieldType(wizardfield,FatherDetailViewBlocks['info'])" ng-show="showLogic(wizardfield);" flex="" layout="row" ng-repeat="wizardfield in wizardRow">
            <md-input-container  ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==1 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==2 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==17 " class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <input type="text" ng-model="answersFather[wizardfield]" ng-blur="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container> 
            <md-input-container  ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])== 4" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <input type="text" ng-model="answersFather[wizardfield]" ng-disabled="true">
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container>
            <md-input-container ng-switch-when="13" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <input type="email" ng-model="answersFather[wizardfield]" ng-blur="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container> 
            <md-input-container ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==11 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==7"  class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <input type="text" ng-model="answersFather[wizardfield]" ng-blur="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]"> 
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container> 
            <md-input-container ng-switch-when="10" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <div ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}" data-content="{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>
                    <br/><br/>
                    <a href="" ng-disabled="disableLogic[wizardfield]">{{ui10Readable[wizardfield]}}</a>
                </div>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                <span ng-show="disableLogic[wizardfield]"><br/><br/>{{ui10Readable[wizardfield]}}</span>
            </md-input-container> 
            <md-input-container ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==15 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==111 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==16 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==111" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <md-select ng-model="answersFather[wizardfield]" ng-change="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                      <md-option ng-repeat="option in getFieldOptions(wizardfield,FatherDetailViewBlocks['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answersFather" value="{{option[1]}}" ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                        {{option[0]}}
                      </md-option>
                </md-select>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container> 
            <md-input-container ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==5 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==6 ||  getFieldType(wizardfield,FatherDetailViewBlocks['info'])==70 ||  getFieldType(wizardfield,FatherDetailViewBlocks['info'])==23" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label><br/>
                <mdp-date-picker mdp-format="DD-MM-YYYY" ng-model="answersFather[wizardfield]" ng-change="updateFather(wizardfield)" style="width:10px;" mdp-disabled="disableLogic[wizardfield]"></mdp-date-picker>
                <mdp-time-picker ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==70 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==23" ng-model="answersFather[wizardfield]" ng-change="updateFather(wizardfield)" mdp-disabled="disableLogic[wizardfield]"></mdp-time-picker>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container>

            <md-input-container ng-switch-when="53" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <md-select ng-model="answersFather[wizardfield]" ng-change="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                    <md-optgroup label="Users">
                        <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,FatherDetailViewBlocks['info'])[1]" value="{{key}}">
                          <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                        </md-option>
                    </md-optgroup> 
                    <md-optgroup label="Groups">
                        <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,FatherDetailViewBlocks['info'])[2]" value="{{key}}">
                          <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                        </md-option>
                    </md-optgroup> 
                </md-select>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container> 
            <md-input-container ng-if="getFieldType(wizardfield,FatherDetailViewBlocks['info'])==19 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==21 || getFieldType(wizardfield,FatherDetailViewBlocks['info'])==24" class="md-block" flex-gt-sm>
                <label>{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}</label>
                <textarea ng-model="answersFather[wizardfield]" ng-blur="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]"> 
                </textarea>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </md-input-container>
            <div ng-switch-when="56" >
                <md-checkbox  ng-model="answersFather[wizardfield]" class="md-block" flex-gt-xs  ng-change="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                    {{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}
                </md-checkbox> 
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
            </div> 

        <div ng-switch-when="33" ng-show="showLogic(wizardfield)" class="form-group">
                <label class="control-label" >{{getFieldLabel(wizardfield)}}</label>
                <div>
                    <md-select class="form-control" multiple="multiple"  ng-model="answersFather[wizardfield]" ng-change="updateFather(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                        <md-option ng-repeat="option in getFieldOptions(wizardfield,FatherDetailViewBlocks['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answersFather" value="{{option[1]}}">
                            {{option[0]}}
                        </md-option>
                    </md-select>
                </div>
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
        </div>
        <div ng-switch-when="1026">
                <md-autocomplete 
                      ng-disabled="disableLogic[wizardfield]"
                      md-no-cache="false"
                      md-selected-item="selectedItemFather[wizardfield]"
                      md-search-text-change="searchValueChange(searchValueFather[wizardfield],wizardfield,'father')"
                      md-search-text="searchValueFather[wizardfield]"
                      md-selected-item-change="selectedItemChange(itemFather,wizardfield,'father')"
                      md-items="itemFather in querySearch(searchValueFather,wizardfield,'father')"
                      md-item-text="itemFather[wizardfield]"
                      md-min-length="1"
                      md-input-maxlength="100" 
                      md-floating-label="{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}}" 
                      placeholder="Cerca...">
               <md-item-template>
               <span md-highlight-text="ctrl.searchValueFather[wizardfield]" md-highlight-flags="^i">{{itemFather[wizardfield]}}</span>
               </md-item-template>
               <md-not-found>
                   Nessuna {{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}} corrispondenti "{{searchValueFather[wizardfield]}}".
               </md-not-found>
               </md-autocomplete>
               
                <p style="color:red;">{{getMandatoryText(wizardfield,FatherDetailViewBlocks['info'],answersFather,'father')}}</p>
                <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                <p ng-show="isNotValidAutocomplete[wizardfield]" style="color:red;">{{getFieldLabel(wizardfield,FatherDetailViewBlocks['info'])}} non e valido</p>
            </div>
        </div>
    </div>    
</div>