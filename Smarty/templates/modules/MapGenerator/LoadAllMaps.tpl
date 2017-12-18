    <div id="divloadingid" ></div>
<div id="LoadfromMapFirstStep">
    <div class="slds-form-element"  style="margin-right:20%; ">

        <label class="slds-form-element__label" for="select-01">{$MOD.ChoseMapTXT}</label>

        <div class="slds-form-element__control">
            <div class="slds-select_container" style="width: 65%;">
                <select id="GetALLMaps"  class="slds-select">
                    {$AllMaps}
                </select>
            </div>
        </div>
    </div>
    <a id="set" style="margin-top: 30px;" data-select-map-load="true" data-loading="true" data-loading-divid="divloadingid"  data-select-map-load-id-relation="GetALLMaps" data-select-map-load-id-to-show="LoadfromMapSecondStep" data-select-map-load-url="MapGenerator,GetMapGeneration" data-showhide-load="true" data-tools-id="LoadfromMapFirstStep,LoadfromMapSecondStep"
    class="slds-button slds-button--neutral">Next
</a>
</div>
<div id="LoadfromMapSecondStep" style="display: none;">
</div>