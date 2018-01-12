  
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
           {foreach name=outer item=popi from=$allitems}  
            var temparray = {};
            {foreach key=key item=item from=$popi}            
                {if $key eq 'rows'}
                  rows=new Array();
                  allfieldsval=[];
                  allfieldstetx=[];
                    {foreach from=$item item=itemi key=keyes}
                      checkifexist={};                    
                      fieldsval=[];
                      fieldstetx=[];
                        {foreach from=$itemi.fields item=items key=key name=name}
                         fieldsval.push('{$items}');
                      {/foreach}
                      {foreach from=$itemi.texts item=items key=key name=name}
                         fieldstetx.push('{$items}');
                      {/foreach}                    
                      {literal}
                        allfieldsval.push(fieldsval);
                        allfieldstetx.push(fieldstetx);
                      {/literal}
                  {/foreach}
                  {literal}
                    temparray["rows"]={fields:allfieldsval,texts:allfieldstetx};
                  {/literal}
                {else}
                 temparray['{$key}']='{$item}';
                {/if}
            {/foreach}
            App.popupJson.push({'{'}temparray{'}'});
            // console.log(temparray);
          {/foreach}
           HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
          App.popupJson.length=0;
      {/foreach}
    
     if (App.SaveHistoryPop.length>0)
    { 
       $('#LoadHistoryPopup div').remove();
        for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
              $('#LoadHistoryPopup').append(showLocalHistory(i,App.SaveHistoryPop[i].PopupJSON,'LoadHistoryPopup','LoadShowPopup'));
        }      
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
    }else{
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
     }

      ShowHistoryData(App.SaveHistoryPop.length-1,'LoadShowPopup');
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
                                             <b>{$MOD.DETAILVIEWBLOCKPORTAL}</b>
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
                                            <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {/if}
                                            <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;width: 80px;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveHIstoryDetailViewBlockPortal" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="SavehistoryCreateViewportal">{$MOD.CreateMap}</button>
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
                                                <label class="slds-form-element__label" for="inputSample3">Choose the Module</label>
                                                <div class="slds-form-element__control">
                                                    <select data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-select-relation-field-id="FieldsForRow" data-module="MapGenerator"  id="FirstModule" name="mod" class="slds-select">
                                                            {$FirstModuleSelected}
                                                     </select>

                                                </div>
                                              </div>
                                            </div>
                                          <div style="width: 100%;margin: 2% 0 0 0;">
                                          <div class="slds-form-element">
                                                <label class="slds-form-element__label" for="inputSample3">{$MOD.writeBlockName}</label>
                                                <div class="slds-form-element__control">
                                                    <input id="BlockName" type="text" minlength="5" style="width: 100%;height: 30px;font-family: verdana;font-size: 12px;color: #333333;text-align: center;margin-bottom: 2%;"  placeholder="{$MOD.writeBlockName}" />
                                                </div>
                                              </div>
                                        </div>
                                            <div id="divForAddRows">
                                                <div style="float: left;width: 60%;">
                                                    <div class="slds-form-element">
                                                    <label class="slds-form-element__label" for="inputSample3">{$MOD.chooseanotherfieldsforthisrow}</label>
                                                    <div class="slds-form-element__control">
                                                        <select  id="FieldsForRow" name="mod" class="slds-select" multiple="multiple">
                                                                {$FirstModuleFields}
                                                         </select>

                                                    </div>
                                                  </div>
                                                </div>
                                          <div style="float: right;width: 40%;margin: 0px;padding: 0px;">
                                            <div class="slds-form-element">
                                                  {* <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectShowFields}</label> *}
                                                  <div class="slds-form-element__control">
                                                      <div class="" style="width: 100%;margin-top:0px;height: 40px">
                                                              <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="float: left;width: 35px;height: 40px;margin-left: 10%;">
                                                                  <button data-add-type="Rows" data-add-relation-id="FieldsForRow"  data-div-show="LoadShowPopup"  onclick="addrows(this)" class="slds-button slds-button_icon" aria-haspopup="true" title="Add more Rows" style="width:2.1rem;">
                                                                      <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                                                                  </button>
                                                              </div>
                                                              <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="float: right;">
                                                                  <button   data-add-type="Block" data-add-relation-id="FirstModule,BlockName"  data-div-show="LoadShowPopup" onclick="showpopupCreateViewPortal(this);resetFieldCreateViewPortal();" class="slds-button slds-button--neutral slds-button--brand" style="float: right;">{$MOD.Addsection}
                                                                  </button>
                                                              </div>
                                                         </div>

                                                  </div>
                                                </div>
                                          </div>
                                            </div>
                                            
                                         </div>
                                </td>
                                <td class="dvtCellInfo" align="left" width="40%">
                                    <div class="">
                                        <div class="flexipageComponent" style="height:500px;">
                                            <article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header" style="margin: 0;height: 500px;">
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
                                                <div id="contenitoreJoin" style="height:440px;">
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