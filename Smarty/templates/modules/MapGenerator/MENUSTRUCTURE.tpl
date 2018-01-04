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
     
      {foreach item=historys from=$PopupJS }
         var temparray = {};
        {foreach key=profile_name item=popjs  from=$historys }
               var temparray = {};
              temparray['DefaultText'] ='{$popjs.DefaultText}' ;
              temparray['FirstModule'] = '{$popjs.FirstModule}';
              temparray['FirstModuleoptionGroup'] = '{$popjs.FirstModuleoptionGroup}';
              temparray['JsonType'] ='{$popjs.JsonType}';
              temparray['LabelName'] = '{$popjs.LabelName}';
              temparray['LabelNameoptionGroup'] = '{$popjs.LabelNameoptionGroup}';
              temparray['Moduli'] = '{$popjs.Moduli}';
              App.popupJson.push({'{'}temparray{'}'});
        {/foreach}
        HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
          App.popupJson.length=0;
      {/foreach}
     ShowLocalHistoryMenuStructure('LoadHistoryPopup','LoadShowPopup');
     ShowHistoryDataLocal(parseInt(App.SaveHistoryPop.length-1),'LoadShowPopup');
    </script>
   
{/if}

{if $Modali neq ''}
      <div>
        {$Modali}
      </div>
{/if}



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
                                             <b>{$MOD.MENUSTRUCTURE}</b>
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
                                            <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {else}
                                            <button class="slds-button slds-button--neutral" style="float: left;width: 80px;" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button> {* saveFieldDependency *} {/if}
                                            <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;width: 80px;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveMenuStructure" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="ShowLocalHistoryMenuStructure">{$MOD.CreateMap}</button>
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
                                        <div id="selJoin" style="float:left; overflow: hidden;width:100%;height: 100%">
                                          <div style="float:left; overflow: hidden;width:80%" id="sel1">
                                            
                                            <div class="slds-form-element">
                                            <label style="margin-right:75%;" class="slds-form-element__label" for="text-input-id-1">{$MOD.labelName}</label>
                                            <div class="slds-form-element__control slds-input-has-icon slds-input-has-icon_left-right">
                                              <input style="width:100%;" type="text" id="LabelName" class="slds-input" placeholder="{$MOD.labelName}" />
                                              <button data-message-show="true" data-message-show-id="help" class="slds-input__icon slds-input__icon_right slds-button slds-button_icon">
                                                <svg class="slds-button__icon slds-icon-text-light" aria-hidden="true">
                                                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#info" />
                                                </svg>
                                                <span class="slds-assistive-text">Clear</span>
                                              </button>
                                            </div>
                                            <div class="slds-popover slds-popover_tooltip slds-nubbin_bottom-left" id="help" role="tooltip" style="top: 0px;width: 20rem;margin-left: 38%;display: none;">
                                                <div class="slds-popover__body slds-text-longform">
                                                  <p>{$MOD.writethelabelName}</p>
                                                </div>
                                              </div>
                                          </div>
                                             
                                              <div class="slds-form-element">
                                                  <div class="slds-form-element__control">
                                                  <center>
                                                      <label style="margin-right:75%;" class="slds-form-element__label" for="input-id-01">{$MOD.MenustructureModule}</label>
                                                  </center>  
                                                      <div class="slds-select_container">
                                                         <select data-select-load="true" id="FirstModule" name="mod" class="slds-select">
                                                          {$FirstModuleSelected}
                                                          </select>
                                                         </div>
                                                  </div>
                                              </div>
                                             
                                          </div>
                                          <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin-top: 85px;padding: 0px;width: 47px;height: 39px;">
                                              <button data-add-button-popup="true" data-add-type="Module" data-add-relation-id="LabelName,FirstModule" data-show-id="LabelName" data-div-show="LoadShowPopup" data-show-modul-id="FirstModule" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add " style="width:2.1rem;">
                                                  <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                                              </button>
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
    <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
    <div data-div-load-automatic="true" id="ModalShow">
    <input type="hidden" name="MapName" id="HistoryValueToShow" value=" ">
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
        .ui-buttonset-vertical label.ui-state-active + input + label {
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

    </style>
 
{/literal}
