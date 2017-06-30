<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<div class="edit-profile-block" style="display: flex;flex-direction: column;align-items: center;" ng-model="executingAction.state" ng-show="executingAction.state">
    <br/><br/>
    <p style="text-transform: uppercase;font-size: 18px;">
        Validazione in corso
    </p>
</div>
<style>
    .clickableRow {
    cursor: pointer;
}
</style>
    <div id="pageWrapper">
    <md-button class="md-raised md-primary" ng-click="backDash(megaText)" ng-if='bachToDashb!==""'> << Back To Dashboard</md-button>
    <div class="pageContent profile-view" style="background-color: rgb(245,245,245)">
        <md-progress-linear md-mode="indeterminate" ng-show="gettingEntities"></md-progress-linear>
        <div ng-include="'Smarty/templates/modules/'+module+'/views/Actions.html'" ></div>
        <table ng-show="answers.denied=='denied'" border='0' cellpadding='5' cellspacing='0' width='98%'>
                <tbody><tr>
                        <td rowspan='2' width='11%'><img src='themes/images/denied.gif' ></td>
                        <td style='border-bottom: 1px solid rgb(204, 204, 204);' nowrap='nowrap' width='70%'>
                                <span class='genHeaderSmall'>Non ti è permesso eseguire questa operazione</span>
                        </td>
                </tr>
                </tbody>
        </table>
        <div id="profileContent">
            <div>
                <!--Left Side-->
                <div id="profileDetails" style="background-color: white">
                    <div id="profileHeader">
                        <!--<h2 class="md-title" style="margin-top:-7px;">Info</h2>-->
                        <div layout="column" layout-fill ng-repeat="blocks in DetailViewBlocks['blocks']" style="border: 1px solid #e5e5e5;height:100%;">
                        <md-toolbar style="background-color: steelblue;border-style: solid;border-spacing: 1px;border-color: white;border-width: 1px;">
                            <div class="md-toolbar-tools">
                              <h3>
                                <span>{{blocks.block_label}}</span>
                              </h3>
                              <md-fab-actions style="display: flex;flex-direction: row;" ng-if="$index==0">
                                  <md-button ng-repeat="act in relatedmodules.DETAILVIEWBASICFORM2" class="md-fab md-raised md-mini">
                                    <md-tooltip md-direction="top" md-visible="false">{{act.linklabel}}</md-tooltip>
                                    <img src="{{act.linkicon}}" style="width:30px;height:30px;" ng-click="executeAction(act.linkid,act.output_type);" />
                                  </md-button>
                              </md-fab-actions>
                            </div>
                        </md-toolbar>
                        <div layout="" layout-sm="column" layout-padding ng-repeat="wizardRow in blocks['rows']">
                            <div ng-switch="getFieldType(wizardfield,DetailViewBlocks['info'])" ng-show="showLogic(wizardfield);" flex="" layout="row" ng-repeat="wizardfield in wizardRow">
                                <md-input-container  ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==1 || getFieldType(wizardfield,DetailViewBlocks['info'])==2 || getFieldType(wizardfield,DetailViewBlocks['info'])==17 || getFieldType(wizardfield,DetailViewBlocks['info'])==106 " class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <input type="text" ng-model="answers[wizardfield]" ng-blur="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container> 
                                <md-input-container  ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==4" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <input type="text" ng-model="answers[wizardfield]" ng-disabled="true">
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container>
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==13 || getFieldType(wizardfield,DetailViewBlocks['info'])==104" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <input type="email" ng-model="answers[wizardfield]" ng-blur="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container> 
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==11 || getFieldType(wizardfield,DetailViewBlocks['info'])==7"  class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <input type="text" ng-model="answers[wizardfield]" ng-blur="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container> 
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==71"  class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <input type="number" ng-model="answers[wizardfield]" ng-blur="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container>
                                <md-input-container ng-switch-when="10" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <div style="display: flex;flex-direction: row;">
                                        <div ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}" data-content="{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>
                                            <br/><br/>
                                            <a href="" ng-disabled="disableLogic[wizardfield]"><i class="material-icons" style="{ldelim} font-size:8px;{rdelim}">edit</i> {{ui10Readable[wizardfield]}}</a>
                                        </div>
                                        <div ng-show="disableLogic[wizardfield]" style="width:100%">
                                            <span>
                                                <br/><br/>{{ui10Readable[wizardfield]}}
                                            </span>
                                        </div>
                                        <a ng-click='goToui10("index.php?module="+formRef[wizardfield].ui10FormName+"&action="+formRef[wizardfield].ui10ActionName+"&record="+answers[wizardfield]+"&onOpenView=detail");'><i class="material-icons">launch</i></a>
                                    </div>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container> 
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==15 || getFieldType(wizardfield,DetailViewBlocks['info'])==16 || getFieldType(wizardfield,DetailViewBlocks['info'])==111" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <md-select ng-model="answers[wizardfield]" ng-change="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                          <md-option ng-repeat="option in getFieldOptions(wizardfield,DetailViewBlocks['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answers" value="{{option[1]}}"  ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                                            {{option[0]}}
                                          </md-option>
                                    </md-select>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container> 
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==115" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <md-select ng-model="answers['user_status']" ng-change="updateEntity('user_status')" ng-disabled="disableLogic['user_status']">
                                          <md-option ng-repeat="option in getFieldOptions(wizardfield,DetailViewBlocks['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answers" value="{{option[1]}}" ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                                            {{option[0]}}
                                          </md-option>
                                    </md-select>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container>
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==5 || getFieldType(wizardfield,DetailViewBlocks['info'])==6 ||  getFieldType(wizardfield,DetailViewBlocks['info'])==70 ||  getFieldType(wizardfield,DetailViewBlocks['info'])==23" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label><br/>
                                    <mdp-date-picker mdp-format="DD-MM-YYYY" ng-model="answers[wizardfield]" mdp-disabled="disableLogic[wizardfield]" ng-change="updateEntity(wizardfield)" style="width:10px;"></mdp-date-picker>
                                    <mdp-time-picker ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==70 || getFieldType(wizardfield,DetailViewBlocks['info'])==23" ng-model="answers[wizardfield]" ng-change="updateEntity(wizardfield)" mdp-disabled="disableLogic[wizardfield]"></mdp-time-picker>
                                </md-input-container>
                                    
                                <md-input-container ng-switch-when="53" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <md-select ng-model="answers[wizardfield]" ng-change="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                        <md-optgroup label="Users">
                                            <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,DetailViewBlocks['info'])[1]" value="{{key}}">
                                              <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                                            </md-option>
                                        </md-optgroup> 
                                        <md-optgroup label="Groups">
                                            <md-option ng-repeat="(key,value) in getFieldOptions(wizardfield,DetailViewBlocks['info'])[2]" value="{{key}}">
                                              <span ng-repeat="(key1,value1) in value">{{key1}}</span>
                                            </md-option>
                                        </md-optgroup> 
                                    </md-select>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p> 
                                </md-input-container> 
                                <md-input-container ng-if="getFieldType(wizardfield,DetailViewBlocks['info'])==19 || getFieldType(wizardfield,DetailViewBlocks['info'])==21 || getFieldType(wizardfield,DetailViewBlocks['info'])==24" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <textarea ng-model="answers[wizardfield]" ng-blur="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]"> 
                                    </textarea>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </md-input-container>
                                <div ng-switch-when="56" >
                                    <md-checkbox  ng-model="answers[wizardfield]" class="md-block" flex-gt-xs  ng-change="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                        {{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}
                                    </md-checkbox>    
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </div> 
                                <div ng-switch-when="1025" class="md-block" flex-gt-sm>
                                    <label>{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}</label>
                                    <div ng-show="!disableLogic[wizardfield]" style="width:100%" class="input__field input__field--editable" title="{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}" data-content="{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}" data-placement="bottom-bottom" data-template-url="Smarty/templates/modules/{{module}}/views/popupEvoMultiRef.tpl.html" data-animation="am-flip-x" data-auto-close="1" bs-popover>
                                        <a href="">Click to Choose</a>
                                    </div>
                                        <label class="input__label input__label--editable">
                                            <i class="icon--editable material-icons">edit</i>
                                        </label> 
                                        <md-chips  ng-model="uiEvoReadable[wizardfield]" md-on-remove="refEvoRemoved($chip, $index,wizardfield);">
                                            <md-chip-template>
                                                <span>
                                                  <strong>[{{$chip.count}}] {{$chip.crmname}}</strong>
                                                </span>
                                            </md-chip-template>
                                        </md-chips>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                </div>
                                
                            <div ng-switch-when="33" ng-show="showLogic(wizardfield)" class="form-group">
                                    <label class="control-label" >{{getFieldLabel(wizardfield)}}</label>
                                    <div>
                                        <md-select class="form-control" multiple="multiple"  ng-model="answers[wizardfield]" ng-change="updateEntity(wizardfield)" ng-disabled="disableLogic[wizardfield]">
                                            <md-option ng-repeat="option in getFieldOptions(wizardfield,DetailViewBlocks['info']) | getPickListDep:map_field_dep:wizardfield:MAP_PCKLIST_TARGET:answers" value="{{option[1]}}" ng-disabled="disableOptionLogic[wizardfield+'_'+option[1]]">
                                                {{option[0]}}
                                            </md-option>
                                        </md-select>
                                    </div>
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p> 
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
                                          md-floating-label="{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}}" 
                                          placeholder="Cerca...">
                                   <md-item-template>
                                   <span md-highlight-text="ctrl.searchValue[wizardfield]" md-highlight-flags="^i">{{item[wizardfield]}}</span>
                                   </md-item-template>
                                   <md-not-found>
                                       Nessuna {{getFieldLabel(wizardfield,DetailViewBlocks['info'])}} corrispondenti "{{searchValue[wizardfield]}}".
                                   </md-not-found>
                                   </md-autocomplete>
                                   
                                    <p style="color:red;">{{getMandatoryText(wizardfield,DetailViewBlocks['info'],answers,'master')}}</p>
                                    <p ng-if="currentErrorStatus[wizardfield]" style="color:red;">{{currentErrorMessage[wizardfield]}}</p>
                                    <p ng-show="isNotValidAutocomplete[wizardfield]" style="color:red;">{{getFieldLabel(wizardfield,DetailViewBlocks['info'])}} non e valido</p>
                              </div>
                            </div>
                        </div>    
                    </div>
                    </div>
            </div>
            <div style="background-color: white" id="opportunities" class="pageRows">
                <div ng-include="'Smarty/templates/modules/'+module+'/views/RelatedLists.html'" ></div>
                <!--Right Side
                <div id="opportunities" class="pageRows" ng-if="ngblockType!='FATHERDV'" >
                    <div id="pagerowsHeader">
                    <h2 style="color:white" class="label">Interactive Timeline</h2>
                    <div style="align:right;float:right;">
                       <!-- <md-button class="md-fab md-mini" style="margin-right:2px;margin-top:-10px;" ng-click="openDialog($event)">
                            +
                        </md-button>
                    </div>
                    </div>
                    <div ng-if="ngblockType=='Table'">
                        <table ng-table-dynamic="tableParams with fields" show-filter="true" class="table table-bordered table-striped">
                             <tbody ng-repeat="(fIndex,row) in $data "  >
                                <tr>
                                    <td ng-repeat="col in $columns" >
                                        <span ng-if="col.field=='expand'" style="cursor: pointer;" id="row.id" data-ng-click="selectTableRow(fIndex,row.id)">
                                            ⬍
                                        </span>
                                        <span ng-if="col.field!='expand' && $index==1"><a href="index.php?module={{form_name}}&action=index&record={{row.id}}&onOpenView=detail">{{row[col.field+'_display']}}</a></span>
                                        <span ng-if="col.field!='expand' && $index!=1">{{row[col.field+'_display']}}</span>
                                    </td>
                                </tr>
                                <tr ng-if="dayDataCollapse[$index] && row.SubDetails.records.length>0">
                                    <td style="padding-bottom:0px;">&nbsp;</td>
                                    <td colspan="7" style="padding-bottom:0px;">
                                        <div class="span12 pull-right" style="margin-left:0px;width:100%;">
                                            <table class="table table-bordered table-striped" style="margin-left:0px;width:100%;">
                                                <thead>
                                                    <tr>
                                                        <td ng-repeat="col in row.SubDetails.fieldsLabels"><b>{{col}}</b></td>
                                                        <td></td>
                                                    </tr>
                                                </thead>
                                                 <tbody ng-repeat="record in row.SubDetails.records "  >
                                                    <tr>
                                                        <td ng-repeat="col in row.SubDetails.fields" >{{record[col]}}</td>
                                                        <td></td>
                                                    </tr>
                                                 </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                             </tbody>
                        </table>
                    </div>              
                    <div ng-if="ngblockType=='Graph'" class="pageRows"></div>
                    <div ng-if="ngblockType=='Custom'" class="pageRows">
                        <div ng-include="'Smarty/templates/modules/'+module+'/views/Rendiconta.tpl'" ></div>
                    </div>
                </div>
                                                        
                <div ng-if="ngblockType=='FATHERDV'" class="pageRows">
                    <div ng-include="'Smarty/templates/modules/'+module+'/views/FatherDV.tpl'" ></div>
                </div>
                   --> 
            </div>
        </div>
    </div>
</div>
                                    
<script type="text/ng-template" id="dialog.html">
    <md-dialog>
      <md-toolbar>
        <div class="md-toolbar-tools">Action Dialog</div>
      </md-toolbar>
      <md-dialog-content layout-padding>
          <table>
              <tr>
                  <td></td>
              </tr>
          </table>
      </md-dialog-content>
      <div class="md-actions">
        <md-button aria-label="Close dialog" ng-click="dialog.close()" class="md-primary">
          Close Greeting
        </md-button>
        <md-button aria-label="Submit dialog" ng-click="dialog.submit()" class="md-primary">
          Submit
        </md-button>
      </div>
    </md-dialog>
</script>

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
<!--<table width="100%"  border="0" ng-table="tableParamsDetail" style="table-layout:fixed;width:100%;padding-left: 0px;" >
        <tr>
            <td header-class="'text-left'" style="text-align: left;padding-top:15px;width:3%;padding-right: 0px;margin-right: 0px;">
                Name
            </td>
        </tr>
        <tr ng-repeat="records in $data " id="records.id" data-ng-click="selectTableRow($index,records.id)">
            <td  header-class="'text-left'" style="text-align: left;padding-top:15px;width:3%;padding-right: 0px;margin-right: 0px;">
                <span class="name">
                    <div class="cssOneOrderHeader">
                        <div class="cssOrderID">{{records.name}}</div>
                    </div>
                    <div class="cssOneProductRecord" ng-repeat='SubDetail in records.subSet ' ng-class-odd="'cssProductOdd'" ng-class-even="'cssProductEven'">
                        <div class="cssOneProductQty">{{SubDetail.name}}</div>
                    </div>
                </span>
            </td>
        </tr>
    </table>
-->

                