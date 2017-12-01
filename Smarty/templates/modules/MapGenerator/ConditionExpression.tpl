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
    
     if (App.SaveHistoryPop.length>0)
    { 
        App.utils.AddtoHistory('LoadHistoryPopup','LoadShowPopup');
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
    }else{
       App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
     }
  </script>


{/if}

<div>
    <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
    <input type="hidden" name="queryid" value="{$queryid}" id="queryid">
    <input type="hidden" name="querysequence" id="querysequence" value="">
    <input type="hidden" name="MapName" id="MapName" value="{$MapName}">
  </div>
  <div style="width: 75%">
      {if $HistoryMap neq ''}
        <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button> 
      {else}
        <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  
      {/if}

      <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveConditionExpresion" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButtonExpresion" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" >{$MOD.CreateMap}</button>
      
   </div>
<div class="wrapper">
    
  <div class="half" >
    <div class="tab">
      <input id="tab-one" type="radio" name="tabs">
      <label for="tab-one">{$MOD.expression}</label>
      <div class="tab-content">
            <div class="slds-modal__container" style="width: 100%;">
              <input type="hidden" id="TypeExpresion" value="Expression" name="">
                    <div class="slds-modal__content slds-p-around--medium" style="height: 100%;">
                       
                       <div style="width: 100%;height: 100%;">
                          <div>
                            <div>
                              <div style="float: left;width: 40%;">
                                <div class="slds-form-element">
                                      <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectModule}</label>
                                      <div class="slds-form-element__control">
                                          <select  data-select-load="true" id="FirstModule"  data-select-relation-field-id="Firstfield" data-module="MapGenerator" name="mod" class="slds-select">
                                                  {$FirstModuleSelected}
                                          </select>

                                      </div>
                                    </div>
                              </div>
                              <div id="OickList" style="float: right; width: 40%; padding: 0px;">
                                <div class="slds-form-element">
                                      <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectField}</label>
                                      <div class="slds-form-element__control">
                                          <select  id="Firstfield" name="mod" class="slds-select" data-load-element="true" data-load-element-idget="Firstfield" data-load-element-idset="expresion">
                                                  {$FirstModuleFields}
                                          </select>

                                      </div>
                                    </div>
                              </div>
                            </div>
                           </div>
                           <br>
                           <div style="float: left;width: 100%;margin-top: 10px;">
                                <div class="slds-form-element">
                                  <label class="slds-form-element__label" for="text-input-id-1">{$MOD.writetheexpresion}</label>
                                  <div class="slds-form-element__control">
                                   <textarea id="expresion" class="slds-textarea" onfocus="removeselect('Firstfield')"  placeholder="{$MOD.writetheexpresion}"></textarea>
                                  </div>
                                </div>
                          </div>
                    </div>



                    </div>
                    <div class="slds-modal__footer">
                       {* {if $HistoryMap neq ''}
                           <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButtonExpresion" >{$MOD.SaveAsMap}</button> 
                        {else}
                          <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButtonExpresion" disabled >{$MOD.SaveAsMap}</button>
                        {/if} *}                        
                        <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;"  data-add-button-popup="true" data-add-type="Expression" data-add-relation-id="FirstModule,Firstfield,expresion" data-add-replace="true" data-show-id="expresion" data-div-show="LoadShowPopup" onclick="removearrayselected('Function','Parameter')">{$MOD.Add}</button>
                    </div>
                </div>
      </div>
    </div>
    <div class="tab">
      <input id="tab-two" type="radio" name="tabs">
      <label for="tab-two">{$MOD.function}</label>
      <div class="tab-content">
           <div class="slds-modal__container" style="width: 100%;">
            <input type="hidden" id="TypeFunction" value="Function" name="">
                    <div class="slds-modal__content slds-p-around--medium" style="height: 100%;">
                       
                       <div style="width: 100%;height: 100%;">
                      <div >
                        <div>
                          <div style="float: left;width: 100%;">
                                <div class="slds-form-element">
                                  <label style="width:100%;" class="slds-form-element__label" for="text-input-id-1">{$MOD.writethefunctionname}</label>
                                  <div id="divfunctionname" class="slds-form-element__control">
                                   <input style="width:100%;" type="text"  id="FunctionName" onblur="checkfunctionname(this)" class="slds-input" placeholder="{$MOD.writethefunctionname}">
                                  </div>
                                </div>
                          </div>
                          <br>
                          <div style="float: left;width: 100%;margin-top: 10px;">
                                <div class="slds-form-element">
                                  <label style="width:100%;" class="slds-form-element__label" for="text-input-id-1">{$MOD.SelectModule}</label>
                                  <div class="slds-form-element__control">
                                  <select class="slds-select" id="Firstmodule2" disabled="disabled" data-select-load="true" data-select-relation-field-id="Firstfield2" data-module="MapGenerator" >
                                    {$FirstModuleSelected}
                                    <option>Select One</option>

                                  </select>
                                  </div>
                                </div>
                          </div>
                          <br>>
                          <div style="float: left;width: 40%;margin-top: 10px;">
                            <div class="slds-form-element">
                                  <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectFieldOrwritetheparameters}</label>
                                  <div class="slds-form-element__control">
                                      <select  id="Firstfield2" name="mod" class="slds-select" data-add-button-popup="true" data-add-type="Function" disabled="disabled" data-add-relation-id="Firstfield2,Firstmodule2,FunctionName" data-show-id="Firstfield2" data-div-show="LoadShowPopup" onclick="removearrayselected('Parameter','Expression')">
                                              {$FirstModuleFields}
                                      </select>

                                  </div>
                                </div>
                          </div>
                          <div id="ShowmoreInput" style="float: right;/* width: 40%; */margin-top:15px;padding: 0px;">
                            <label class="slds-form-element__label" for="inputSample3">{$MOD.putParameter}</label>
                            <div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">
                                               <div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">
                                                <div class="slds-combobox__form-element">
                                                    <input type="text" disabled="disabled" id="DefaultValueFirstModuleField_1" placeholder="{$MOD.AddAValues}" id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input"  onfocus="removearrayselected('Function','Expression')">
                                                </div>
                                                </div>
                                               <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">
                                                <button class="slds-button slds-button_icon" aria-haspopup="true" title="Add more Values" style="width:2.1rem;" data-add-button-popup="true" data-add-type="Parameter" data-add-relation-id="DefaultValueFirstModuleField_1,Firstmodule2,FunctionName" data-show-id="DefaultValueFirstModuleField_1" data-div-show="LoadShowPopup">
                                                    <img src="themes/images/btnL3Add.gif" style="width: 100%;">
                                                </button>
                                              
                                            </div>
                                      </div>
                          </div>
                        </div>
                        <div id="showpopupmodal" style="width: 100%;height: 100%;">
                          
                        </div>
                       </div>
                    </div>



                    </div>
                  {*   <div class="slds-modal__footer">
                        div class="slds-modal__footer">
                        {if $HistoryMap neq ''}
                          <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButtonFunction" >{$MOD.SaveAsMap}</button> 
                        {else}
                          <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButtonFunction" disabled >{$MOD.SaveAsMap}</button>
                        {/if}                        
                        <button id="AddToArray" class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,FunctionName,Firstmodule2,MapName,Firstfield2,DefaultValueFirstModuleField_1,TypeFunction"   data-send="true"  data-send-url="MapGenerator,saveConditionExpresion" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButtonFunction" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
                    </div> *}
                    </div>
                </div>
      </div>
    </div>
  </div>  
  <div style="float: right;width: 20%;margin-left: 20px;">
  <div id="LoadShowPopup"></div>
</div>
<div id="LoadHistoryPopup"  style="/* position: absolute; */margin-top: 6%;float: left;width: 71%;">
</div>
</div>
<style type="text/css">

h1 {
  text-align: center;
}
.half {
  float: left;
  width: 75%;
  padding: 0 1em;
}
/* Acordeon styles */
.tab {
  position: relative;
  margin-bottom: 1px;
  width: 100%;
  color: #fff;
  overflow: hidden;
}
input[type=radio] {
  position: absolute;
  opacity: 0;
  z-index: -1;
}
label {
  position: relative;
    display: block;
    padding: 0 0 0 1em;
    color: #54698d;
    font-weight: bold;
    line-height: 3;
    cursor: pointer;
    background-color: #f4f6f9;
    font-size: 1.0rem;
}
.blue label {
  background: #2980b9;
}
.tab-content {
  max-height: 0;
  overflow: hidden;
  background: #f7f9fb;
  -webkit-transition: max-height .35s;
  -o-transition: max-height .35s;
  transition: max-height .05s;
}
.blue .tab-content {
  background: #3498db;
}
.tab-content p {
  margin: 1em;
}
/* :checked */
input:checked ~ .tab-content {
  max-height: 100%;
}
/* Icon */
label::after {
  position: absolute;
  right: 0;
  top: 0;
  display: block;
  width: 3em;
  height: 3em;
  line-height: 3;
  text-align: center;
  -webkit-transition: all .35s;
  -o-transition: all .35s;
  transition: all .35s;
}
input[type=checkbox] + label::after {
  content: ">";
  font-size: 20px;
}
input[type=radio] + label::after {
  content: "\25BC";
}
input[type=checkbox]:checked + label::after {
  transform: rotate(90deg);
}
input[type=radio]:checked + label::after {
  transform: rotateX(180deg);
}


</style>