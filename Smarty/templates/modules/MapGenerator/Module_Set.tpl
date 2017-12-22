
<div>
  
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
              temparray['HistoryValueToShow'] = '{$popjs.HistoryValueToShow}';
              temparray['HistoryValueToShowoptionGroup'] = '{$popjs.HistoryValueToShowoptionGroup}';
              temparray['JsonType'] ='{$popjs.JsonType}';
              temparray['firstModule'] = '{$popjs.firstModule}';
              temparray['firstModuleoptionGroup'] = '{$popjs.firstModuleoptionGroup}';
              App.popupJson.push({'{'}temparray{'}'});
        {/foreach}
        HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
          App.popupJson.length=0;
      {/foreach}
      App.utils.AddtoHistory('LoadHistoryPopup','LoadShowPopup');


       var historydata=App.SaveHistoryPop[parseInt(App.SaveHistoryPop.length-1)];
      App.popupJson.length=0;
    for (var i=0;i<=historydata.PopupJSON.length-1;i++){
    App.popupJson.push(historydata.PopupJSON[i]);
    }
      App.utils.ReturnDataSaveHistory('LoadShowPopup');

    </script>
   
{/if}

{if $Modali neq ''}
      <div>
        {$Modali}
      </div>
{/if}

<div id="contentJoinButtons" style="width: 70%;height: 100%;float: left;">
   

       <div class="slds-section-title--divider">
        {if $HistoryMap neq ''}
          <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
        {else}
          <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
        {/if}

        <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveModuleSet" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
        <center>
          <h3 style="margin-left: 20%;" class="slds-section-title--divider">{$MOD.module_set}</h3>
          <center>
     </div>

   <div class="mailClient mailClientBg" style="position: absolute; width: 350px; height:110px;z-index: 90000; display: none;" id="userorgroup" name="userorgroup">
   <center><b>{$MOD.addjoin}</b>: <select name="usergroup" id="usergroup" style="width:30%"><option value="none">None</option><option value="user">User</option><option value="group">Group</option>
   </select><br><br><b>{$MOD.addCF}</b>: <select name="CFtables" id="cf" style="width:30%"><option value="none">None</option><option value="cf">CF</option></select>
   <br><br><br><input class="crmbutton small edit" type="button" name="okbutton" id="okbutton" value="OK" onclick="generateJoin();hidediv('userorgroup');openalertsJoin();"></center></div>

  <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
    <div data-div-load-automatic="true" id="ModalShow">
    <input type="hidden" name="MapName" id="HistoryValueToShow" value=" ">
    </div>

    {if $Modali neq ''}
      <div>
        {$Modali}
      </div>
    {/if}
    <div id="selJoin" style="float:left; overflow: hidden;width:100%;height: 100%">
        <div style="float:left; overflow: hidden;width:60%" id="sel1">
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                <center>
                    <label class="slds-form-element__label" for="input-id-01">{$MOD.TargetModule}</label>
                </center>  
                    <div class="slds-select_container">
                       <select data-select-load="true" id="FirstModule" name="mod" class="slds-select">
                        {$FirstModuleSelected}
                        </select>
                       </div>
                </div>
            </div>
        </div>
        <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin:22px 0px 10px 5px;padding: 0px;width: 47px;height: 39px;">
            <button data-add-button-popup="true" data-add-type="Modul" data-add-relation-id="HistoryValueToShow,FirstModule,FirstModule" data-show-id="" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add " style="width:2.1rem;">
                <img src="themes/images/btnL3Add.gif" style="width: 100%;">
            </button>
        </div>
       <!-- <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
       <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
       <input type="hidden" name="querysequence" id="querysequence" value="">
       <input type="hidden" name="MapName" id="MapName" value="{$MapName}"> -->
           <div id="contenitoreJoin">      
           <div class="testoDiv">
                    <b>{$MOD.SelectField}</b>
                </div> 
    </div>{*End div contenitorejoin*}
    <div id="LoadShowPopup" >        
      <!-- <div id="LoadShowPopup" style="margin-top: 10px;display: block; width: 70%;float: left;"></div> -->
      <!-- <div id="LoadHistoryPopup" style="margin-top: 10px;display: block; width: 30%;float: right;"> -->
        
      </div>
    </div>{*End div LoadShowPopup*}
    <div id="LoadHistoryPopup" style="width: 24%;height: 100%;text-align: left;border: 1px;overflow-y: auto;overflow-x: hidden;">
    </div>
</div>

<div id="generatedquery">
    
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
  

</div>