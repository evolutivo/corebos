
<table class="slds-table slds-no-row-hover slds-table-moz ng-scope" style="border-collapse:separate; border-spacing: 1rem;">
        <tbody>
            <tr class="blockStyleCss" id="DivObjectID">
                <td class="detailViewContainer" valign="top">
                    <div class="forceRelatedListSingleContainer">
                        <article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
                            <div class="slds-card__header slds-grid">
                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                    <div class="slds-media__body">
                                       <h2 style="width: 50%;float: left;">
                                          <span class="slds-text-title--caps slds-truncate slds-m-right--xx-small">
                                             <b>{$MOD.ImportBusinessMapping}</b>
                                          </span>
                                        </h2>
                                      {if $NameOFMap neq ''}
                                       <h2 style="width: 50%;float: left;">
                                              <span style="text-transform: capitalize;" class="slds-text-title--caps slds-truncate slds-m-right--xx-small" title="">
                                              <b>{$NameOFMap}</b>
                                               </span>
                                       </h2>
                                      {/if}
                                    </div>
                                </header>
                                <div class="slds-no-flex" data-aura-rendered-by="1224:0">
                                    <div class="actionsContainer mapButton">
                                        <div class="slds-section-title--divider">
                                            {if $HistoryMap neq ''}
                                            <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {else}
                                            <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {/if}
                                            <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName,UpdateId" data-send="true" data-loading="true" data-loading-divid="waitingIddiv" data-send-url="MapGenerator,saveImportBussinesMapping" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="ShowLocalHistoryImportBussiness">{$MOD.CreateMap}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                    <div class="slds-truncate">
                        <table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
                            <tr class="slds-line-height--reset">
                                <td class="dvtCellLabel" width="70%" style="vertical-align: top;">
                                        <!-- THE MODULE Zone -->
                                        <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
                                    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
                                    <input type="hidden" name="querysequence" id="querysequence" value="">
                                    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
                                    <div data-div-load-automatic="true" id="ModalShow">
                                    </div>
                                    {if $Modali neq ''}
                                    <div>
                                        {$Modali}
                                    </div>
                                    {/if}
                                    <div id="selJoin" style="float:left; overflow: hidden;width:100%; height: 100%;">
                                        <div style="float:left; overflow: hidden;width:100%;margin-right: 64px;" id="sel1">
                                            <div class="slds-form-element">
                                                <div class="slds-form-element__control">
                                                    <center>
                                                        <label style="margin-right: 75%;" class="slds-form-element__label" for="input-id-01">{$MOD.TargetModule}</label>
                                                    </center>
                                                    <div class="slds-select_container">
                                                        <select data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-file="SecondModuleMapping" data-second-module-id="secmodule" data-module="MapGenerator" data-select-relation-field-id="Firstfield,SecondField" id="FirstModule" name="mod" class="slds-select">
                                                            {$FirstModuleSelected}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>                                            
                                        </div>
                                        {* <div style="float:left; overflow: hidden;width:10%;" id="centerJoin"> =</div> *}
                                        <div style="float:left; overflow: hidden;width:100%" id="sel2">
                                        	<div class="slds-form-element" style="width: 40%;float: left;">
                                                <div class="slds-form-element__control">
                                                     <center>
                                                        <label style="margin-right: 70%;" class="slds-form-element__label" for="input-id-01">{$MOD.SelectFields}</label>
                                                    </center>
                                                    <div class="slds-select_container">
                                                        <select id="Firstfield" name="mod" class="slds-select">
                                                            {$FirstModuleFields}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div style="float:left;overflow: hidden;width: 15%;margin-left: 0px;" id="sel1">    <label style="margin-top: 30%;text-align:  center;margin-left:  30%;font-size: 15px;font-weight: bold;" class="slds-form-element__label" for="input-id-01">{$MOD.Maches}</label>
                                             </div>
                                            <div class="slds-form-element" style="width: 40%;float: right;">
                                                <div class="slds-form-element__control">
                                                    <center>
                                                        <label style="margin-right: 70%;" class="slds-form-element__label" for="input-id-01">{$MOD.SelectFields}</label>
                                                    </center>
                                                    <div class="" id="SecondDiv" style="float: left;width: 100%; height: 33px;">
                                                        <select id="SecondField" name="secmodule" data-add-button-popup="false" data-add-type="Related" data-add-relation-id="FirstModule,Firstfield,SecondField" data-show-id="Firstfield" data-show-modul-id="FirstModule" data-div-show="LoadShowPopup" class="slds-select" onchange="AQddImportBussinessMapping(this)">
                                                            {$SecondModuleFields}
                                                        </select>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div id="contenitoreJoin" style="display: inline-flex;">
                                            <div id="sectionField" style="width:100%;">
                                                <div>
                                                    <div class="testoDiv">
                                                        {* <center><b>{$MOD.SelectField}</b></center> *}
                                                    </div>
                                                    <div class="slds-form-element">
                                                        <div class="slds-form-element__control">
                                                            <div id="AlertsAddDiv" style="margin-top: 10px;width: 50%;">
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
                                                        <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
                                                        <input type="hidden" name="querysequence" id="querysequence" value="">
                                                        <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                         <div id="sectionField" style="width:100%;">
                                            <div class="testoDiv" style="margin: 50px 0px  5px 0px;">
                                                <center><b>{$MOD.UpdateInportBussines}</b></center>
                                            </div>
                                            <hr style="display: block;margin-top: 0.5em;margin-bottom: 0.5em;margin-left: auto;margin-right: auto;border-style: inset;border-width: 1px;">
                                              <div style="float:left; overflow: hidden;width:45%;margin-right: 64px;" id="sel1">
                                            <div class="slds-form-element">
                                                <div class="slds-form-element__control">
                                                    <center>
                                                        <label style="margin-right: 100%;" class="slds-form-element__label" for="input-id-01">{$MOD.UpdateInportBussines}</label>
                                                    </center>
                                                    <div class="slds-select_container">
                                                        <select class="slds-select" id="UpdateId">
                                                            <option value="FIRST">{$MOD.FIRST}</option>
                                                            <option value="LAST">{$MOD.LAST}</option>
                                                            <option value="ALL">{$MOD.ALL}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="dvtCellInfo" align="left" width="40%">
                                    <div class="">
                                        <div class="flexipageComponent">
                                            <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header" style="margin: 0;">
                                                <div class="slds-card__header slds-grid">
                                                    <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                        <div class="slds-media__body">
                                                            <h2 class="header-title-container"> 
                                                              <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                <b>PopUp Zone</b>
                                                              </span> 
                                                            </h2>
                                                        </div>
                                                    </header>
                                                </div>
                                                <div id="contenitoreJoin">
                                                    <div id="LoadShowPopup" style="height: 125px;display: grid;"></div>
                                                </div>
                                                {*End div contenitorejoin*}
                                        </div>
                                        </article>
                                        <br/>
                                        <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header" style="margin: 0;">
                                            <div class="slds-card__header slds-grid">
                                                <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                    <div class="slds-media__body">
                                                        <h2 class="header-title-container"> 
                                                              <span class="slds-text-heading--small slds-truncate actionLabel">
                                                                <b>History Zone</b>
                                                              </span> 
                                                            </h2>
                                                    </div>
                                                </header>
                                            </div>
                                            <div class="slds-card__body slds-card__body--inner">
                                                <div id="contenitoreJoin">
                                                    <div id="LoadHistoryPopup">
                                                    </div>
                                                </div>{*End div contenitorejoin*}
                                            </div>
                                        </article>
                                    </div>
                        </table>
        </tbody>
    </table>
    <div id="waitingIddiv"></div>
    <div id="contentJoinButtons" style="width: 70%;height: 100%;float: left;">
    </div>
    <div id="generatedquery">
        <div id="results" style="margin-top: 1%;"></div>
    </div>
    <div id="null"></div>
    <div>
        <div id="queryfrommap"></div>
    </div>