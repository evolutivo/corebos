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
                                <input style="width: 400px; " type="text" id="SaveasMapTextImput" required=""
                                       class="slds-input"  placeholder="dfdfd">
                                <div class="slds-form-element__control">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slds-modal__footer">
                        <button class="slds-button slds-button--neutral" onclick="closeModalwithoutcheck();">{$MOD.cancel}
                        </button>
                        <button onclick="closeModal();" class="slds-button slds-button--neutral slds-button--brand">
                            {$MOD.save}
                        </button>
                    </div>
                </div>
            </div>
            <div class="slds-backdrop" id="backdrop"></div>

            <!-- Button To Open Modal -->
            {*<button class="slds-button slds-button--brand" id="toggleBtn">Open Modal</button>*}
        </div>