<div>
    <div id="LoadingImage" style="display: none">
        <img src="" />
    </div>
    {if $HistoryMap neq ''}
    <script type="text/javascript">
    App.savehistoryar = '{$HistoryMap}';
    </script>
    {/if} 

    {if $PopupJS neq ''} 
 <script type="text/javascript"> 

      {foreach from=$PopupJS item=allitem key=key name=name}
          {foreach key=profile_name item=$popjs  from=$allitem }
                var temparray = {};
                temparray['DefaultText'] = '';
                temparray['JsonType'] = '{$popjs.JsonType}';
                temparray['FirstfieldoptionGroup'] = '{$popjs.FirstfieldoptionGroup}';
                temparray['FirstModule'] ='{$popjs.FirstModule}';
                temparray['FirstModuleoptionGroup'] = '{$popjs.FirstModuleoptionGroup}';
                temparray['FirstfieldID'] = '{$popjs.FirstfieldID}';
                temparray['FirstfieldIDoptionGroup'] ='{$popjs.FirstfieldIDoptionGroup}';
                temparray['Firstfield'] = '{$popjs.Firstfield}';
                temparray['Firstfield_Text'] ='{$popjs.Firstfield_Text}';
                temparray['secmodule'] = '{$popjs.secmodule}';
                temparray['secmoduleoptionGroup'] ='{$popjs.secmoduleoptionGroup}';
                temparray['SecondfieldID'] ='{$popjs.SecondfieldID}';
                temparray['sortt6ablechk'] = '{$popjs.sortt6ablechk}';
                temparray['sortt6ablechkoptionGroup'] = '{$popjs.sortt6ablechkoptionGroup}';
                temparray['editablechk'] = '{$popjs.editablechk}';
                temparray['editablechkoptionGroup'] = '{$popjs.editablechkoptionGroup}';
                temparray['mandatorychk'] = '{$popjs.mandatorychk}';
                temparray['hiddenchkoptionGroup'] = '{$popjs.hiddenchkoptionGroup}';
                temparray['hiddenchk'] = '{$popjs.hiddenchk}';
                temparray['hiddenchkoptionGroup'] = '{$popjs.hiddenchkoptionGroup}';
              App.popupJson.push({'{'}temparray{'}'});
          {/foreach}
           HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
           App.popupJson.length=0;
      {/foreach}
    
        if (App.SaveHistoryPop.length>0)
        { 
            App.utils.AddtoHistory('LoadHistoryPopup','LoadShowPopup','showmodalformasterdetail');
           App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
        }else{
           App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
         }

      var historydata=App.SaveHistoryPop[parseInt(App.SaveHistoryPop.length-1)];
      App.popupJson.length=0;
      for (var i=0;i<=historydata.PopupJSON.length-1;i++){
      App.popupJson.push(historydata.PopupJSON[i]);
      }
      // App.utils.ReturnDataSaveHistory('LoadShowPopup');
      showmodalformasterdetail();

    </script>
   
{/if}

{if $Modali neq ''}
  <div>
    {$Modali}
  </div>
{/if}
    <div id="contentJoinButtons" style="width: 100%;height: 100%;float: left;">
        <!-- <div class="slds-grid slds-grid--vertical slds-navigation-list--vertical"
         style="float:left; overflow: hidden;width:20%" id="buttons">

        <ul id="LDSstyle">
        <li>
          <button class="slds-button slds-button--brand"  data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,SaveMasterDetail" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" style="width:98%;margin:5px;">{$MOD.CreateMap}</button>
        </li>
         <li>
           {if $HistoryMap neq ''}
          <button data-modal-saveas-open="true" id="SaveAsButton" class="slds-button slds-button--brand" style="width:98%;margin:5px;">{$MOD.SaveAsMap}</button>
          {else}
          <button data-modal-saveas-open="true" id="SaveAsButton" class="slds-button slds-button--brand" disabled style="width:98%;margin:5px;">{$MOD.SaveAsMap}</button>
           {/if}
        </li>

        <li>
          <button class="slds-button slds-button--brand" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,SaveMasterDetail"   data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" class="slds-button slds-button--brand" style="width:98%;margin:5px;">{$MOD.SaveAsMap}
          </button>
        </li>


         {*
            <li><a href="javascript:void(0);" id="addJoin" name="radio" onclick="showform(this);"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.AddJoin}</a></li>
            <li><a href="javascript:void(0);" id="deleteLast" name="radio" onclick="openalertsJoin();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.DeleteLastJoin}</a></li>
            <li><a href="javascript:void(0);" id="create" name="radio" onclick="creaVista();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateMaterializedView}</a></li>
            <li><a href="javascript:void(0);" id="createscript" name="radio" onclick="generateScript();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateScript}</a></li>
            <li><a href="javascript:void(0);" id="createmap" name="radio" onclick="SaveMap();"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.CreateMap}</a></li>
            <li><a href="javascript:void(0);" id="saveasmap" name="radio"
                   class="slds-navigation-list--vertical__action slds-text-link--reset"
                   aria-describedby="entity-header">{$MOD.SaveAsMap}</a></li>
         *}


        </ul>

    </div> -->
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
                                             <b>{$MOD.MasterDetail}</b>
                                          </span>
                                        </h2>
                                    {if $NameOFMap neq ''}
                                     <h2 style="width: 50%;float: left;">
                                            <span class="slds-text-title--caps slds-truncate slds-m-right--xx-small" title="">
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
                                                <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {else}
                                                <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {/if}
                                                <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;width: 80px;" data-send-data-id="ListData,MapName" data-send="true" data-send-url="MapGenerator,SaveMasterDetail" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="showmodalformasterdetail2">{$MOD.CreateMap}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <div class="slds-truncate">
                            <table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
                                <tr class="slds-line-height--reset">
                                    <td class="dvtCellLabel" width="100%" style="vertical-align: top;">
                                        <!-- THE MODULE Zone -->
                                        <div id="selJoin" style="float:left; overflow: hidden;width:100%; height: 100%;">
                                            <div style="float:left; overflow: hidden;width:45%;margin-right: 50px;" id="sel1">
                                                <div class="slds-form-element">
                                                    <div class="slds-form-element__control">
                                                        <center>
                                                            <label class="slds-form-element__label" for="input-id-01">{$MOD.TargetModule}</label>
                                                        </center>
                                                        <div class="slds-select_container">
                                                            <select data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="secmodule" data-select-fieldid="FirstfieldID" data-module="MapGenerator" data-second-module-file="SecondModuleMapping" data-select-relation-field-id="Firstfield" id="FirstModule" name="mod" class="slds-select">
                                                                {$FirstModuleSelected}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label style="font-size: larger;vertical-align:bottom;margin: 0; padding-left: 7px;">ID:</label>
                                                        <input type="button" class="slds-button slds-button--neutral sel" id="FirstfieldID" value="{$FmoduleID}" name="FirstfieldID" style="padding: 0px;margin-top: 10px;width: 90%;">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="slds-form-element">
                                                    <div class="slds-form-element__control">
                                                      <center>
                                                          <label class="slds-form-element__label" for="input-id-01">{$MOD.SelectFields}</label>
                                                      </center>
                                                        <div class="slds-select_container">
                                                            <select id="Firstfield" name="mod" class="slds-select">
                                                                {$FirstModuleFields}
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {* <div style="float:left; overflow: hidden;width:10%;" id="centerJoin">
                                                <span class="slds-form-element__label" style="margin-top: 20px;font-size: 35px;margin-left: 10px;">=</span> =</div> *}
                                            <div style="float:left; overflow: hidden;width:45%" id="sel2">
                                                <div class="slds-form-element">
                                                    <div class="slds-form-element__control">
                                                        <center>
                                                            <label class="slds-form-element__label" for="input-id-01">{$MOD.OriginModule}</label>
                                                        </center>
                                                        <div class="slds-select_container">
                                                            <select id="secmodule" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-select-load="true" data-module="MapGenerator" data-second-select-relation-id="SecondField" data-select-fieldid="SecondfieldID" data-second-firstmodule-id="FirstModule" name="secmodule" class="slds-select">
                                                                {$SecondModulerelation}
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label style="font-size: larger;vertical-align:bottom;margin: 0; padding-left: 7px;">ID:</label>
                                                        <input type="button" class="slds-button slds-button--neutral sel" id="SecondfieldID" value="{$SmoduleID}" name="SecondfieldID" style="padding: 0px;margin-top: 10px;width: 90%;">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="slds-form-element">
                                                    <div class="slds-form-element__control">
                                                        <div class="" id="SecondDiv" style="float: left;width: 105%;">
                                                            <div class="slds-form-element" style="display: inline-block;">
                                                                <label class="slds-checkbox--toggle slds-grid">
                                                                    <input id="sortt6ablechk" name="checkbox" type="checkbox" checked="checked" aria-describedby="toggle-desc" />
                                                                    <span id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
                            <span class="slds-checkbox--faux"></span>
                                                                    <span class="slds-checkbox--on">{$MOD.Sort}-{$MOD.YES}</span>
                                                                    <span class="slds-checkbox--off">{$MOD.Sort}-{$MOD.NO}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="slds-form-element" style="display: inline-block;">
                                                                <label class="slds-checkbox--toggle slds-grid">
                                                                    <input onchange="RemovecheckedMasterDetail(this)" data-all-id="mandatorychk,editablechk" id="hiddenchk" name="checkbox" type="checkbox" aria-describedby="toggle-desc" />
                                                                    <span id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
                            <span class="slds-checkbox--faux"></span>
                                                                    <span class="slds-checkbox--on">{$MOD.Hidden}-{$MOD.YES}</span>
                                                                    <span class="slds-checkbox--off">{$MOD.Hidden}-{$MOD.NO}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <!--SLDS Checkbox Toggle Element Start-->
                                                            <div class="slds-form-element" style="display: inline-block;">
                                                                <label class="slds-checkbox--toggle slds-grid">
                                                                    <input id="editablechk" name="checkbox" checked="checked" type="checkbox" aria-describedby="toggle-desc" />
                                                                    <span id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
                            <span class="slds-checkbox--faux"></span>
                                                                    <span class="slds-checkbox--on">{$MOD.Edit}-{$MOD.YES}</span>
                                                                    <span class="slds-checkbox--off">{$MOD.Edit}-{$MOD.NO}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="slds-form-element" style="display: inline-block; margin-right: -5px;">
                                                                <label class="slds-checkbox--toggle slds-grid">
                                                                    <input id="mandatorychk" name="checkbox" checked="checked" type="checkbox" aria-describedby="toggle-desc" />
                                                                    <span id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
                            <span class="slds-checkbox--faux"></span>
                                                                    <span class="slds-checkbox--on">{$MOD.Mandatory}-{$MOD.YES}</span>
                                                                    <span class="slds-checkbox--off">{$MOD.Mandatory}-{$MOD.NO}</span>
                                                                    </span>
                                                                </label>
                                                            </div>
                                                            <div class="slds-form-element" style="display: inline-block;float: right;margin-right: 14px;">
                                                                <label class="slds-checkbox--toggle slds-grid">
                                                                    <button onclick="GenearteMasterDetail()" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add " style="width:2.1rem;">
                                                                        <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                                                                    </button>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
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
        <div class="mailClient mailClientBg" style="position: absolute; width: 350px; height:110px;z-index: 90000; display: none;" id="userorgroup" name="userorgroup">
            <center><b>{$MOD.addjoin}</b>:
                <select name="usergroup" id="usergroup" style="width:30%">
                    <option value="none">None</option>
                    <option value="user">User</option>
                    <option value="group">Group</option>
                </select>
                <br>
                <br><b>{$MOD.addCF}</b>:
                <select name="CFtables" id="cf" style="width:30%">
                    <option value="none">None</option>
                    <option value="cf">CF</option>
                </select>
                <br>
                <br>
                <br>
                <input class="crmbutton small edit" type="button" name="okbutton" id="okbutton" value="OK" onclick="generateJoin();hidediv('userorgroup');openalertsJoin();">
            </center>
        </div>
        <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
        <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
        <input type="hidden" name="querysequence" id="querysequence" value="">
        <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
       
        <br>
        <br>
        <div id="contenitoreJoin">
            <div id="sectionField" style="width: 100%;">
                <div>
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
        </div>
    </div>
    {literal}
    <script>
    </script>
    <style>
    .alerts {
        padding: 10px;
        background-color: #808080;
        margin: 4px;
        color: white;
        display: inline-block;
    }

    .closebtns {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 20px;
        line-height: 15px;
        cursor: pointer;
        transition: 0.3s;
    }

    .closebtns:hover {
        color: black;
    }

    #LDSstyle {
        border: 1px solid black;
        margin-right: 0px;
        margin-top: 0px;
        width: 100%;
        height: 100%;
    }
    /*@media(width:1024px){*/
    /*#LDSstyle {*/
    /*font-size: 10*/
    /*}*/
    /*}*/

    #LDSstyle li {
        margin: 0px;
        padding: 0px;
    }

    #LDSstyle a:hover {
        background: #c3cede;
        /*margin-right: 2px;*/
    }

    .ajax_loader {
        background: url("modules/MapGenerator/image/spinner_squares_circle.gif") no-repeat center center transparent;
        width: 100%;
        height: 100%;
    }

    .blue-loader .ajax_loader {
        background: url("modules/MapGenerator/image/ajax-loader_blue.gif") no-repeat center center transparent;
    }

    #feedback {
        font-size: 1.4em;
    }

    #selectable .ui-selecting {
        background: #FECA40;
    }

    #selectable .ui-selected {
        background: #F39814;
        color: white;
    }

    #selectable {
        list-style-type: none;
        margin: 0;
        padding: 0;
        width: 60%;
    }

    #selectable li {
        margin: 3px;
        padding: 0.4em;
        font-size: 1.4em;
        height: 18px;
    }
    /*
         * The buttonset container needs a width so we can stack them vertically.
         *
         */

    #radio {
        width: 85%;
    }
    /*
         * Make each label stack on top of one another.
         *
         */

    .ui-buttonset-vertical label {
        display: block;
    }
    /*
         * Handle colliding borders. Here, we"re making the bottom border
         * of every label transparent, except for labels with the
         * ui-state-active or ui-state-hover class, or if it"s the last label.
         *
         */

    .ui-buttonset-vertical label:not(:last-of-type):not(.ui-state-hover):not(.ui-state-active) {
        border-bottom: transparent;
    }
    /*
         * For lables in the active state, we need to make the top border of the next
         * label transparent.
         *
         */

    .ui-buttonset-vertical label.ui-state-active+input+label {
        border-top: transparent;
    }
    /*
         * Oddly enough, the above style approach doesn"t work for the
         * hover state. So we define this class that"s used by our JavaScript
         * hack.
         *
         */

    .ui-buttonset-vertical label.ui-transparent-border-top {
        border-top: transparent;
    }

    select {
        width: 300px;
    }

    .overflow {
        height: 200px;
    }


    .tooltip {
        position: relative;
        display: inline-block;
        border-bottom: 1px dotted black;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 120px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        top: -5px;
        right: 105%;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }


    #toggle-desc .slds-checkbox {
        font-size: 11px !important;
        margin: 0 5px !important;
    }
    /*#SecondDiv .slds-form-element{*/
    /*margin-right: 6px !important; */
    /*}*/

    #toggle-desc .slds-checkbox--faux {
        margin-right: 12px !important;
        text-align: center;
    }
    </style>
    {/literal}
</div>