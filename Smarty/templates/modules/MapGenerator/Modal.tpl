<div class="slds">
            <div class="slds-modal" aria-hidden="false" role="dialog" id="modal">
                <div class="slds-modal__container">
                    <div class="slds-modal__header">
                        <button class="slds-button slds-button--icon-inverse slds-modal__close" onclick="closeModal()">
                            <svg aria-hidden="true" class="slds-button__icon slds-button__icon--large">
                                <use xlink:href="/assets/icons/action-sprite/svg/symbols.svg#close"></use>
                            </svg>
                            <span class="slds-assistive-text">{$MOD.close}</span>
                        </button>
                        <h2 class="slds-text-heading--medium">{$MOD.mapname}</h2>
                    </div>
                    <div class="slds-modal__content slds-p-around--medium">
                        <div>
                            <div class="slds-form-element">
                                <label class="slds-form-element__label" for="input-unique-id">
                                    <abbr id="ErrorVAlues" class="slds-required" title="{$MOD.requiredstring}">*</abbr>{$MOD.required}</label>
                                <input style="width: 400px; " type="text" id="SaveasMapText" name="nameView" required=""
                                       class="slds-input" placeholder="{$MOD.mapname}" data-controll="true" data-controll-idlabel="ErrorLabelModal" data-controll-file="MapGenerator,CheckNameOfMap" data-controll-id-relation="SendDataButton" >
                                <div class="slds-form-element__control">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slds-modal__footer">
                        <label id="ErrorLabelModal" style="margin-right: 100px;background-color: red;font-size: 14px;border-radius: 5px;padding: 6px;"></label>
                        <button class="slds-button slds-button--neutral" data-modal-saveas-close="true" >{$MOD.cancel}
                        </button>
                        <button data-send="true" data-send-url="{$Datas}" data-send-data-id="{$dataid},SaveasMapText" id="SendDataButton" disabled class="slds-button slds-button--neutral slds-button--brand">
                            {$MOD.save}
                        </button>  <!-- data-send-savehistory="{$savehistory}" -->
                    </div>
                </div>
            </div>
            <div class="slds-backdrop" id="backdrop"></div>

            <!-- Button To Open Modal -->
            {*<button class="slds-button slds-button--brand" id="toggleBtn">Open Modal</button>*}
        </div>