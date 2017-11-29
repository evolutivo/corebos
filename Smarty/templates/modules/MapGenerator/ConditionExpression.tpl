<div class="wrapper">
    
  <div class="half" >
    <div class="tab">
      <input id="tab-one" type="radio" name="tabs">
      <label for="tab-one">{$MOD.expression}</label>
      <div class="tab-content">
            <div class="slds-modal__container" style="width: 100%;">
                    <div class="slds-modal__content slds-p-around--medium" style="height: 100%;">
                       
                       <div style="width: 100%;height: 100%;">
                          <div>
                            <h3 style="margin-left: 0%;" class="slds-section-title--divider">{$MOD.SectionOriginFileds}</h3>
                            <br/>
                            <div>
                              <div style="float: left;width: 40%;">
                                <div class="slds-form-element">
                                      <label class="slds-form-element__label" for="inputSample3">Choose the Module</label>
                                      <div class="slds-form-element__control">
                                          <select  data-select-load="true" id="FirstModule"  data-select-relation-field-id="Firstfield" data-module="MapGenerator" name="mod" class="slds-select">
                                                  {$FirstModule}
                                          </select>

                                      </div>
                                    </div>
                              </div>
                              <div id="ShowmoreInput" style="float: right; width: 40%; padding: 0px;">
                                <div class="slds-form-element">
                                      <label class="slds-form-element__label" for="inputSample3">Choose the field</label>
                                      <div class="slds-form-element__control">
                                          <select  id="Firstfield" name="mod" class="slds-select">
                                                  {$Picklistdropdown}
                                          </select>

                                      </div>
                                    </div>
                              </div>
                            </div>
                           </div>
                           <div style="float: left;width: 100%;">
                                <div class="slds-form-element">
                                  <label class="slds-form-element__label" for="text-input-id-1">{$MOD.writetheexpresion}</label>
                                  <div class="slds-form-element__control">
                                   <textarea id="expresion" class="slds-textarea" placeholder="{$MOD.writetheexpresion}"></textarea>
                                  </div>
                                </div>
                          </div>
                    </div>



                    </div>
                    <div class="slds-modal__footer">
                        <button id="AddToArray" data-add-button-popup="true" data-add-type="Picklist" data-add-relation-id="PickListFields,DefaultValueFirstModuleField_1" data-show-id="PickListFields" data-div-show="LoadShowPopup"  class="slds-button slds-button--neutral slds-button--brand">
                            {$MOD.Add}
                        </button>  <!-- data-send-savehistory="{$savehistory}" -->
                    </div>
                </div>
      </div>
    </div>
    <div class="tab">
      <input id="tab-two" type="radio" name="tabs">
      <label for="tab-two">{$MOD.function}</label>
      <div class="tab-content">
           <div class="slds-modal__container" style="width: 100%;">
                    <div class="slds-modal__content slds-p-around--medium" style="height: 100%;">
                       
                       <div style="width: 100%;height: 100%;">
                      <div >
                        <div>
                          <div style="float: left;width: 100%;">
                                <div class="slds-form-element">
                                  <label class="slds-form-element__label" for="text-input-id-1">{$MOD.writethefunctionname}</label>
                                  <div class="slds-form-element__control">
                                   <input type="text"  id="FunctionName" class="slds-input" placeholder="{$MOD.writethefunctionname}">
                                  </div>
                                </div>
                          </div>
                          <div style="float: left;width: 40%;">
                            <div class="slds-form-element">
                                  <label class="slds-form-element__label" for="inputSample3">Choose the field</label>
                                  <div class="slds-form-element__control">
                                      <select  id="PickListFields" name="mod" class="slds-select">
                                              {$Picklistdropdown}
                                      </select>

                                  </div>
                                </div>
                          </div>
                          <div id="ShowmoreInput" style="float: right;/* width: 40%; */margin-top:15px;padding: 0px;">
                            <div class="slds-combobox_container slds-has-object-switcher" style="width: 100%;margin-top:0px;height: 40px">
                                               <div  id="SecondInput" class="slds-combobox slds-dropdown-trigger slds-dropdown-trigger_click"  aria-expanded="false" aria-haspopup="listbox" role="combobox">
                                                <div class="slds-combobox__form-element">
                                                    <input type="text" id="DefaultValueFirstModuleField_1" placeholder="{$MOD.AddAValues}" id="defaultvalue" style="width:250px;height: 38px;padding: 0px;margin: 0px;font-size: 15px;font-family: monospace;" class="slds-input slds-combobox__input">
                                                </div>
                                                </div>
                                               <div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click" style="margin: 0px;padding: 0px;width: 35px;height: 40px;">
                                                <button class="slds-button slds-button_icon" onclick="Addmorevalues(this)" aria-haspopup="true" title="Add more Values" style="width:2.1rem;">
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
                    <div class="slds-modal__footer">
                        {* <label id="ErrorLabelModal" style="margin-right: 100px;background-color: red;font-size: 14px;border-radius: 5px;padding: 6px;"></label> *}
                        <button class="slds-button slds-button--neutral" data-modal-saveas-close="true" data-modal-close-backdrop-id="Picklistbackdrop" data-modal-close-id="Picklist" >{$MOD.cancel}
                        </button>
                        <button id="AddToArray" data-add-button-popup="true" data-add-type="Picklist" data-add-relation-id="PickListFields,DefaultValueFirstModuleField_1" data-show-id="PickListFields" data-div-show="LoadShowPopup"  class="slds-button slds-button--neutral slds-button--brand">
                            {$MOD.Add}
                        </button>  <!-- data-send-savehistory="{$savehistory}" -->
                    </div>
                </div>
      </div>
    </div>
  </div>  
</div>
<div style="width: 75%">
      {if $HistoryMap neq ''}
        <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
      {else}
        <button class="slds-button slds-button--neutral" style="float: left;" data-modal-saveas-open="true" id="SaveAsButton" disabled >{$MOD.SaveAsMap}</button>  {* saveFieldDependency *}
      {/if}

      <button class="slds-button slds-button--neutral slds-button--brand" style="float: right;" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveGlobalSearchAutocomplete" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup">{$MOD.CreateMap}</button>
      {* <h3 style="margin-left: 20%;" class="slds-section-title--divider">{$MOD.GlobalSearchAutocompleteMapping}</h3> *}
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