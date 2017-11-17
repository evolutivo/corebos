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
    {foreach name=outer item=popi from=$PopupJS}  
      var temparray = {};
      {foreach key=key item=item from=$popi}
          temparray['{$key}']='{$item}';
      {/foreach}
      App.popupJson.push({'{'}temparray{'}'});
      // console.log(temparray);
    {/foreach}
    
     if (App.popupJson.length>0)
    { 
       for (var i = 0; i <= App.popupJson.length-1; i++) {
         var module=App.popupJson[i].temparray[`DefaultText`];
         var typeofppopup=App.popupJson[i].temparray['JsonType'];
         var divinsert= App.utils.DivPopup(i,module,"LoadShowPopup",typeofppopup);
         $('#LoadShowPopup').append(divinsert);
       } 
    }else{
      alert(mv_arr.MappingFiledValid);
     }
  </script>


{/if}

<div class="subTitleDiv" id="subTitleDivJoin" style="margin-top: 1%">
    <left style="margin-left: 45%"><b>{$MOD.TargetModule}</b></left>
    <right style="margin-left: 10%"><b> {$MOD.OriginModule}</b></right>
</div>
<div id="contentJoinButtons">
  <label style="margin-top: initial;font-size: 14px;font-family: unset;font-style: oblique;color: indigo;font-style: oblique;">{$MOD.SelectedField}<label>
      <div class="slds-grid slds-grid--vertical slds-navigation-list--vertical"
         style="float:left;overflow: hidden;width: 25%;height: 400px;" id="buttons">
         <div id="LoadShowPopup" style="width: 95%;height: 100%;overflow: auto;background-color: moccasin;" >        
      <div id="LoadShowPopup" style="margin:auto;display: block; width: 20%;">
      </div>
    </div>{*End div LoadShowPopup*}

         
        </ul>

    </div>
   <div class="mailClient mailClientBg" style="position: absolute; width: 350px; height:110px;z-index: 90000; display: none;" id="userorgroup" name="userorgroup">
   <center><b>{$MOD.addjoin}</b>: <select name="usergroup" id="usergroup" style="width:30%"><option value="none">None</option><option value="user">User</option><option value="group">Group</option>
   </select><br><br><b>{$MOD.addCF}</b>: <select name="CFtables" id="cf" style="width:30%"><option value="none">None</option><option value="cf">CF</option></select>
   <br><br><br><input class="crmbutton small edit" type="button" name="okbutton" id="okbutton" value="OK" onclick="generateJoin();hidediv('userorgroup');openalertsJoin();"></center></div>

  <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
   <div id="selJoin" style="float:left; overflow: hidden;width:75%;height: 100%;">
        <div style="float:left; overflow: hidden;width:45%" id="sel1">
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                    <div class="slds-select_container">
                       <select data-select-load="true" data-second-module-id="secmodule" data-select-relation-field-id="Firstfield" data-select-fieldid="FirstfieldID" data-module="MapGenerator"  data-second-module-file="ListColumnsRelationData" id="FirstModule" name="mod" class="slds-select">
                        {$FirstModuleSelected}
                        </select>
                       </div>
                </div>

                 <div>
                  <label style="font-size: larger;vertical-align:bottom;margin: 0;">ID:</label>
                   <input type="button" value="{$FmoduleID}" class="slds-button slds-button--neutral sel" id="FirstfieldID" name="FirstfieldID"
                   style="padding: 0px;margin-top: 10px;width: 90%;">
                 </div>
            </div>
            <br>
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                	<label style="font-size: initial;color: grey;">{$MOD.SelectField}</label>
                    <div class="slds-select_container">
                       <select  id="SecondField" data-label-change-load="true" data-module="MapGenerator" data-select-filename="GetLabelName" data-set-value-to="DefaultValue" name="mod" class="slds-select">
                        {if $SecondModuleFields neq ''}
                            {$SecondModuleFields}
                        {else}
                            <option value="">Choose from origin</option>
                        {/if}
                        </select>
                       </div>
                </div>
            </div>
           </div>
       <div style="float:left; overflow: hidden;width:3%; margin-left: 2%; margin-right: 2%;" id="centerJoin"> =</div>
        <div style="float:left; overflow: hidden;width:45%" id="sel2">
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                    <div class="slds-select_container">
                        <select id="secmodule" data-second-select-load="true" data-module="MapGenerator" data-second-select-relation-id="SecondField" data-select-fieldid="SecondfieldID"  name="secmodule" class="slds-select">
                          {if $SecondModulerelation neq ''}
                            {$SecondModulerelation}
                        {else}
                            <option value="">Choose form Target</option>
                        {/if}
                        
                        </select>
                     </div>
                </div> 
                <div>
                  <label style="font-size: larger;vertical-align:bottom;margin: 0;">ID:</label>
                   <input type="button" class="slds-button slds-button--neutral sel" value="{$SmoduleID}" id="SecondfieldID" name="SecondfieldID"
                   style="padding: 0px;margin-top: 10px;width: 90%;">
                 </div>
               </div>
            <br>
              <div class="slds-form-element">
                <div class="slds-form-element__control">
                
                      <div class="" id="SecondDiv" style="float: left;width: 100%;">
                       
                          <div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">
                             <div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">
                              <div class="slds-combobox__form-element">
                                  <input type="text" id="DefaultValue" placeholder="Change label if you want and after click button" id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input">
                              </div>
                              </div>
                          <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">
                              <button data-add-button-popup="true" data-add-type="Related" data-add-relation-id="FirstModule,secmodule,SecondField,FirstfieldID,SecondfieldID,DefaultValue" data-show-id="SecondField" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add " style="width:2.1rem;">
                                  <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                              </button>
                          </div>
                     </div>
                         
                        </div>
                        
                </div>
                
                
            </div>
            
          </div>
            
       <br><br>
       <div id="contenitoreJoins">

        <div id="sectionField">

            <div>
                <div class="testoDiv">
                    <center><b>{$MOD.popupPlace}</b></center>
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
    </div> 
    <div id="selJoin" style="float:left;overflow: hidden;width: 75%;height: 100%;">
        <div style="float:left;/* overflow: hidden; */width: 39%;margin-top: 20px;margin-right: 20px;/* height: 391px; */" id="sel1">
           <div class="slds-form-element">
                <div class="slds-form-element__control">
                    <div class="slds-select_container">
                       <select style="height: 35px;"  id="Firstfield" data-label-change-load="true" data-module="MapGenerator" data-select-filename="GetLabelName" data-set-value-to="DefaultValueFirstModuleField" name="mod" class="slds-select">
                        {if $FirstModuleFields neq ''}
                           {$FirstModuleFields}
                        {else}
                            <option value="">Choose from Target</option>
                        {/if}
                        </select>
                       </div>
                </div>
            </div>
            <br>
            
           </div>
       
        <div style="float:left; overflow: hidden;width:45%" id="sel2">            
            <br>
            <div class="slds-form-element">
                <div class="slds-form-element__control">
                
                      <div class="" id="SecondDiv" style="float: left;width: 100%;">
                       
                          <div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">
                             <div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">
                              <div class="slds-combobox__form-element">
                                  <input type="text" id="DefaultValueFirstModuleField" placeholder="Change label if you want and after click button" id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input">
                              </div>
                              </div>
                          <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">
                              <button data-add-button-popup="true" data-add-type="Popup" data-add-relation-id="FirstModule,FirstfieldID,Firstfield,DefaultValueFirstModuleField" data-show-id="SecondField" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add " style="width:2.1rem;">
                                  <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                              </button>
                            
                          </div>
                     </div>
                         
                        </div>
                        
                </div>
                
                
            </div>
            
          </div>
            
       <br><br>
       <button id="set" style="margin-left: 59%;margin-top: 64px;position: absolute;"  data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,SaveListColumns" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup"  class="slds-button slds-button--brand">
        {$MOD.CreateMap}
        </button>
        {if $HistoryMap neq ''}
          <button data-modal-saveas-open="true" id="SaveAsButton" class="slds-button slds-button--brand" style="margin-left: 60%;margin-top: 28px;position: relative;">{$MOD.SaveAsMap}</button>
        {else}
           <button data-modal-saveas-open="true" id="SaveAsButton" class="slds-button slds-button--brand" disabled style="margin-left: 60%;margin-top: 28px;position: relative;">{$MOD.SaveAsMap}</button>
        {/if}
     <div id="LoadHistoryPopup">
     </div>
    </div>   
</div>


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