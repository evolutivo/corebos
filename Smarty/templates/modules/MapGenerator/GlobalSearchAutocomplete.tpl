{*ListColumns.tpl*}

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
            	var Fieldsarray=[];
                {if $key eq 'Firstfield' or $key eq 'Firstfield2' }
                	{foreach from=$item item=itemi}
                		Fieldsarray.push('{$itemi}');
                	{/foreach}
                	temparray['{$key}']=Fieldsarray;

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
        App.utils.AddtoHistory('LoadHistoryPopup','LoadShowPopup');
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
    }else{
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
     }
     
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

  <div>
  	<input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
  </div>

<div style="width: 70%;height: 100%;float: left;">
  	 <div class="slds-section-title--divider">
     	 	{if $HistoryMap neq ''}
     	 		<button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
     	 	{else}
     	 		<button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
     	 	{/if}

     		<button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveGlobalSearchAutocomplete" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
      	<center>
          <h3 style="margin-left: 20%;" class="slds-section-title--divider">{$MOD.GlobalSearchAutocompleteMapping}</h3>
          <center>
            
  	 </div>
	 <div>
	 	<div style="width: 100%;">
	 		<div class="slds-form-element">
            <label class="slds-form-element__label" for="inputSample3">Choose the Module</label>
            <div class="slds-form-element__control">
              	<select data-select-load="true"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"  id="FirstModule" name="mod" class="slds-select">
                        {$FirstModuleSelected}
                 </select>

            </div>
          </div>
	 	</div>
	 	<div>
	 		<div style="float: left;width: 40%;">
		 		<div class="slds-form-element">
	            <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectsearchFields}</label>
	            <div class="slds-form-element__control">
	              	<select  id="Firstfield" name="mod" class="slds-select" multiple="multiple">
	                        {$FirstModuleFields}
	                 </select>

	            </div>
	          </div>
	 		</div>
	 		<div style="float: left;width: 13px;font-size: 30px;margin-left: 10;padding: 15px 0px 0px 32px;">
	 			<div class="slds-form-element" style="display: inline-block;">
                <label class="slds-checkbox--toggle slds-grid">
                 <input id="startwithchck" name="checkbox"  type="checkbox" aria-describedby="toggle-desc" />
                 <span  id="toggle-desc" class="slds-checkbox--faux_container" aria-live="assertive">
                    <span class="slds-checkbox--faux"></span>
                    <span class="slds-checkbox--on" style="font-size: initial;margin-right: 0px">{$MOD.Startwith}</span>
                    <span class="slds-checkbox--off" style="font-size: initial;margin-right: 0px">{$MOD.contains}</span>
                 </span>
                </label>
              </div>
	 		</div>
	 		<div style="float: right;width: 40%;margin: 0px;padding: 0px;">
		 		<div class="slds-form-element">
	            <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectShowFields}</label>
	            <div class="slds-form-element__control">
	              	<div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">
                             <div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">
                              <div class="slds-combobox__form-element">
                                 <select  id="Firstfield2" name="mod" class="slds-select" multiple="multiple">
	                        		{$FirstModuleFields}
	                			 </select>
                              </div>
                              </div>
                          <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">
                              <button data-add-button-popup="true" data-add-type="Module" data-add-relation-id="FirstModule,Firstfield,Firstfield2,startwithchck" onclick="resetFieldGlobalSearchAuto();" data-show-id="FirstModule" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add" style="width:2.1rem;">
                                  <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                              </button>
                          </div>
                     </div>

	            </div>
	          </div>
	 		</div>
	 	</div>
	 	
	 </div>
	
</div>
<div id="GlobalSearchAutocompletels" style="float: right;width: 25%;margin-left: 20px;">
	<div id="LoadShowPopup"></div>
</div>
<div id="LoadHistoryPopup"  style="/* position: absolute; */margin-top: 6%;float: left;width: 71%;">
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