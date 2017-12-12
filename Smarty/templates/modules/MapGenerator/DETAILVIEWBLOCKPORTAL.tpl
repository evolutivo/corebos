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

<div style="width: 55%;height: 100%;float: left;">
  	 <div class="slds-section-title--divider">
     	 	{if $HistoryMap neq ''}
     	 		<button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
     	 	{else}
     	 		<button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
     	 	{/if}

     		<button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveHIstoryDetailViewBlockPortal" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="SavehistoryCreateViewportal">{$MOD.CreateMap}</button>
      	<h3 style="margin-left: 25%;" class="slds-section-title--divider">{$MOD.DETAILVIEWBLOCKPORTAL}</h3>
  	 </div>
	 <div>
	 	<div style="width: 100%;">
	 		<div class="slds-form-element">
            <label class="slds-form-element__label" for="inputSample3">Choose the Module</label>
            <div class="slds-form-element__control">
              	<select data-select-load="true" data-select-relation-field-id="FieldsForRow" data-module="MapGenerator"  id="FirstModule" name="mod" class="slds-select">
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
	</div>
<div style="float: right;width: 44%;margin-left: 0px;">
	<div id="LoadShowPopup"></div>
</div>
<div id="LoadHistoryPopup"  style="/* position: absolute; */margin-top: 6%;float: left;width: 50%;">
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