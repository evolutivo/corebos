<table class="slds-table slds-no-row-hover slds-table-moz ng-scope" style="border-collapse:separate; border-spacing: 1rem;">
                                            <tbody>
                                                <tr class="blockStyleCss" id="DivObjectID">
                                                    <td class="detailViewContainer" valign="top">
                                                        <div class="forceRelatedListSingleContainer">
                                                            <article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
                                                                <div class="slds-card__header slds-grid">
                                                                    <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                        <div class="slds-media__body">
                                                                            <h2>
                                                                                <span class="slds-text-title--caps slds-truncate slds-m-right--xx-small" title="Organization Information">
                                                                                    <b>{$MOD.NameView}</b>
                                                                                </span>
                                                                            </h2>
                                                                        </div>
                                                                    </header>
                                                                </div>
                                                            </article>
                                                        </div>
                                                        <div class="slds-truncate">
                                                            <table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
                                                                <tr class="slds-line-height--reset">
                                                                    <td class="dvtCellLabel" width="50%">{$MOD.NameView}</td>
                                                                    <td class="dvtCellInfo" align="left" width="50%">
                                                                        <div class="slds-select_container">
                                                                            <select data-load-Map="true" data-type-select="TypeObject" class="slds-select">
                                                                                <option value="">{$MOD.ChooseTypeOfMap}</option>
                                                                                <option value="MaterializedView">{$MOD.MaterializedView}</option>
                                                                                <option value="Script">{$MOD.Script}</option>
                                                                                <option value="Map">{$MOD.Map}</option>
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table id="MapDivID" class="slds-table slds-no-row-hover slds-table-moz ng-scope" style="display: none;border-collapse:separate; border-spacing: 1rem;">
                                            <tbody>
                                                <tr class="blockStyleCss">
                                                    <td class="detailViewContainer" valign="top">
                                                        <div class="forceRelatedListSingleContainer">
                                                            <article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
                                                                <div class="slds-card__header slds-grid">
                                                                    <header class="slds-media slds-media--center slds-has-flexi-truncate">
                                                                        <div class="slds-media__body">
                                                                            <h2>
                                                                                <span class="slds-text-title--caps slds-truncate slds-m-right--xx-small" title="Organization Information">
                                                                                    <b>{$MOD.TypeMapNone}</b>
                                                                                </span>
                                                                            </h2>
                                                                        </div>
                                                                    </header>
                                                                </div>
                                                            </article>
                                                        </div>                                                        
                                                        <div class="slds-truncate">
                                                            <table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
                                                                <tr class="slds-line-height--reset">
                                                                    <td class="dvtCellLabel" width="50%">{$MOD.InsertNameQuery}:</td>
                                                                    <td class="dvtCellInfo" align="left" width="50%">
                                                                        <span id="ShowErorrNameMap" class="error" style="padding: 5px;background-color: red;width: 50%;font;font-size: 12px;border-radius: 9px;color: white;float: none;display: none;"> </span>
                                                                        <div style="margin-top: 5px;">
                                                                            <input type="text" minlength="5" id="nameView" name="nameView" data-controll="true" data-controll-idlabel="ShowErorrNameMap" data-controll-file="MapGenerator,CheckNameOfMap" data-controll-id-relation="TypeMaps" class="slds-input" name='nameView' placeholder="{$MOD.addviewname}" />
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="slds-line-height--reset">
                                                                    <td class="dvtCellLabel" width="50%">{$MOD.TypeMapNone}</td>
                                                                    <td class="dvtCellInfo" align="left" width="50%">
                                                                        <div class="slds-select_container">
                                                                            <select data-load-Map="true" data-type-select="TypeMap" data-type-select-module="MapGenerator,ChoseeObject" id="TypeMaps" class="slds-select" disabled>
                                                                                {$Allmaps}
                                                                            </select>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>