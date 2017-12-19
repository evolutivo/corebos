{*ListColumns.tpl*}

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
    {foreach from=$PopupJS item=allitems key=key name=name}
      {foreach name=outer item=popi from=$allitems}  
          var temparray = {};
          {foreach key=key item=item from=$popi}
          temparray['{$key}']='{$item}';
      {/foreach}
       App.popupJson.push({'{'}temparray{'}'});
            // console.log(temparray);
            {/foreach}
            HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
            App.popupJson.length=0;
    {/foreach}

            
           ShowLocalHistoryMenuStructure('LoadHistoryPopup','LoadShowPopup')
         ClickToshowSelectedFileds(parseInt(App.SaveHistoryPop.length-1),'LoadShowPopup');
         </script>

         {/if}

{if $Modali neq ''}
      <div>
        {$Modali}
      </div>
{/if}


  <div id="contentJoinButtons" style="width: 75%">

   <div class="slds-section-title--divider">
    {if $HistoryMap neq ''}
    <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
    {else}
    <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
    {/if}

    <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveDuplicateRecords" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-send-savehistory-functionname="ShowLocalHistoryMenuStructure" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
    <center>
      <h3 style="margin-left: 20%;" class="slds-section-title--divider">{$MOD.DuplicateRecords}</h3>
      <center>

      </div>
    </div>
    <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
    <div id="selJoin" style="overflow: hidden;width:100%;height: 100%;">
      <div style="float:left; overflow: hidden;width:75%" id="sel1">
        <div class="slds-form-element">
          <div class="slds-form-element__control">
            <center>
              <label class="slds-form-element__label" for="input-id-01">{$MOD.TargetModule}</label>
            </center>
            <div class="slds-select_container">
             <select  data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-select-load="true"  data-second-module-id="relatedModule"  data-second-module-file="RelatedModuleDuplicate"  data-module="MapGenerator"   id="FirstModule" name="mod" class="slds-select">
              {$FirstModuleSelected}
            </select>
          </div>
        </div>

      </div>
      <br><br>
  <div class="column" style="background-color:#F4F6F9;">
        <div style="width: 70%;margin-left: 10%;">
          <label style="font-size:  17px;color: slateblue;font-family: sans-serif;">{$MOD.DuplicateDirectRelations}</label>
          <div class="slds-form-element" style="display: inline-block;float: right;">
            <label class="slds-checkbox--toggle slds-grid">
             <input id="DuplicateDirectRelationscheck" name="checkbox"  type="checkbox" aria-describedby="toggle-desc" />
             <span  id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
              <span class="slds-checkbox--faux"></span>
              <span class="slds-checkbox--on">{$MOD.TRUEE}</span>
              <span class="slds-checkbox--off">{$MOD.FALSEE}</span>
            </span>
          </label>
        </div>
      </div>
  </div>

<div id="contenitoreJoins">

  <div id="sectionField" style="width:100%; float: left; margin-top: 10px">

   <div class="testoDiv">
    <center><b>{$MOD.RelatedList}</b></center>
  </div>
  <div class="slds-form-element">
    <div class="slds-form-element__control">
      <div id="AlertsAddDiv" style="margin-top: 10px;width: 50%;">                  

      </div>
    </div>
  </div>
</div>
</div>

<div style="float:left; overflow: hidden;width:100%; margin-top: 10px;" id="sel1">
  <div style="width: 100%;margin-bottom: 20%;">
    
    <div style="width: 35%;float: left;">
      <div class="slds-form-element" style="width: 100%">
          <div class="slds-form-element__control">
            <center>
              <label class="slds-form-element__label" for="input-id-01">{$MOD.RelatedModule}</label>
            </center>
            <div class="slds-select_container">
             <select  name="relatedModule" id="relatedModule" class="slds-select">
                {$AllModulerelated}
            </select>
          </div>
        </div>

      </div>
    </div>

      <div style="width: 65%;float:  right;margin-top:  20px;">
          
          <div class="" id="SecondDiv" style="float: left;width: 100%;">
                      <div class="slds-form-element" style="display: inline-block;margin-left:  20px;">
                        <label class="slds-checkbox--toggle slds-grid">
                        <button  data-add-button-popup="true" data-add-type="Related" data-add-relation-id="FirstModule,DuplicateDirectRelationscheck,relatedModule" data-show-id="relatedModule" data-show-modul-id="FirstModule" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="{$MOD.ClickAdd}" style="width:2.1rem;">
                                  <img src="themes/images/btnL3Add.gif" style="width: 100%;vertical-align:bottom;">
                              </button>
                        </label>
                      </div>
                  </div>

      </div>

  </div>

</div>

<div style="float:left; overflow: hidden;width:45%" id="sel2">
</div>

<div id="contenitoreJoins">

  <div id="sectionField" style="width:100%; float: left; margin-top: -15px">
    <div class="testoDiv">
    </div>
  </div>
</div>


</div> 

<div id="contenitoreJoin" style="width: 100%;display: inline-flex;">        
  <div id="LoadShowPopup" style="margin-top: 10px;display: block; float: left;"></div>
  <div id="LoadHistoryPopup" style="margin-top: 10px;display: block; float: right;">

  </div>
</div>{*End div contenitorejoin*}
<!--     <div id="selJoin" style="float:left;overflow: hidden;width: 100%;height: 100%;">
    
       <label style="margin-top: initial;font-size: 14px;font-family: unset;font-style: oblique;color: indigo;font-style: oblique;">{$MOD.SelectedField}</label>
      <div class="slds-grid slds-grid--vertical slds-navigation-list--vertical"
         style="float:left;overflow: hidden;width: 73%;height: 100px;" id="buttons">
         <div id="LoadShowPopup" style="width: 100%;height: 100%;overflow: auto;background-color: moccasin;" >        
      <div id="LoadShowPopup" style="margin:auto;display: block; width: 20%;">
      </div>
    </div>{*End div LoadShowPopup*}
     <div id="LoadHistoryPopup">
     </div>
    </div>   
  </div> -->


</div>
<div id="null"></div>
<div>
  <div id="queryfrommap"></div>
</div>
{literal}
<script>

</script>
<style>

* {
  box-sizing: border-box;
}

body {
  margin: 0;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 100%;
  padding: 10px;
  height: 50px;/* Should be removed. Only for demonstration */
}



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