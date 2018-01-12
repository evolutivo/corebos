  <div style="width: 100%;height: 100%">
  
  <div id="LoadingImage" style="display: none">
    <img src=""/>
</div>
{if $HistoryMap neq ''}
  <script type="text/javascript">
    App.savehistoryar='{$HistoryMap}';
  </script>
{/if}

{if $PopupJS neq ''}
  <script type="text/javascript">
      {foreach from=$PopupJS item=allitems key=key name=name}
           var temparray = {};
            {foreach key=key item=item from=$allitems}
                temparray['{$key}']='{$item}';
            {/foreach}
            App.popupJson.push({'{'}temparray{'}'});          
           HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
          App.popupJson.length=0;
      {/foreach}
    
     if (App.SaveHistoryPop.length>0)
    { 
       ShowLocalHistoryRendiConfig('LoadHistoryPopup','LoadShowPopup');
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
    }else{
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
     }

      ShowRendicontConfig(parseInt(App.SaveHistoryPop.length-1),'LoadShowPopup');
      App.countsaveMap=2;
  </script>


{/if}

<div id="ModalDiv">
    {if $Modali neq ''}
        <div>
         {$Modali}
        </div>
    {/if}
</div>
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
                                             <b>{$MOD.RendicontaConfig}</b>
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
                                            <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button> 
                                             {else}
                                            <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button>
                                            {/if}
                                            <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;width: 80px;" data-send-data-id="ListData,MapName" data-send="true" data-loading="true" data-loading-divid="waitingIddiv" data-send-url="MapGenerator,saveRendicontaConfig" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-send-savehistory-functionname="ShowLocalHistoryRendiConfig" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
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

                                        <div>
                                          <div style="width: 100%;">
                                            <div class="slds-form-element">
                                                  <label class="slds-form-element__label" for="inputSample3">{$MOD.ChosseMOdule}</label>
                                                  <div class="slds-form-element__control">
                                                      <select data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="statusfield,processtemp,causalefield" data-module="MapGenerator"  id="FirstModule"  name="mod" class="slds-select">
                                                              {$FirstModuleSelected}
                                                       </select>

                                                  </div>
                                                </div>
                                          </div>

                                          <div>
                                            <div style="float: left;width: 80%;">
                                              <div class="slds-form-element">
                                                    <label class="slds-form-element__label" for="inputSample3">{$MOD.statusfield}</label>
                                                    <div class="slds-form-element__control">
                                                        <select  id="statusfield" data-reset="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup"  name="mod" class="slds-select">
                                                                {$allfields}
                                                         </select>

                                                    </div>
                                                  </div>
                                              </div>
                                           </div>
                                           <div>
                                            <div style="float: left;width: 80%;">
                                              <div class="slds-form-element">
                                                    <label class="slds-form-element__label" for="inputSample3">{$MOD.processtemp}</label>
                                                    <div class="slds-form-element__control">
                                                        <select  id="processtemp" data-reset="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" name="mod" class="slds-select">
                                                                {$allfields}
                                                         </select>

                                                    </div>
                                                  </div>
                                              </div>
                                           </div>
                                           <div>
                                            <div style="float: left;width: 80%;">
                                              <div class="slds-form-element">
                                                    <label class="slds-form-element__label" for="inputSample3">{$MOD.causalefield}</label>
                                                    <div class="slds-form-element__control">
                                                        <select  id="causalefield" data-reset="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" name="mod" class="slds-select">
                                                                {$allfields}
                                                         </select>

                                                    </div>
                                                  </div>
                                              </div>
                                           </div>
                                           <div>
                                            <div style="float: left;width: 100%;">
                                                 <div class="slds-section-title--divider">
                                                  <button data-add-button-popup="false" data-add-type="RendicontaConfig" data-add-relation-id="FirstModule,statusfield,processtemp,causalefield" data-div-show="LoadShowPopup" onclick="AdDPOpupRendicontaConfig(this);" class="slds-button slds-button--neutral slds-button--brand" style="float: right;">{$MOD.Add}</button>
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
                                                                <b>{$MOD.PopupZone}</b>
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
                                                                <b>{$MOD.HistoryZone}</b>
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
    <div>
        <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
        <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
        <input type="hidden" name="querysequence" id="querysequence" value="">
        <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
    </div>
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