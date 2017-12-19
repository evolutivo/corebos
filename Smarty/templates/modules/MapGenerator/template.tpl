<link type="text/css" href="Smarty/templates/modules/MapGenerator/jquery-ui.css" rel="stylesheet" />
<script type="text/javascript" src="Smarty/templates/modules/MapGenerator/jquery-1.10.2.js"></script>
<script type="text/javascript" src="Smarty/templates/modules/MapGenerator/jquery-ui.js"></script>
<link type="text/css" href="modules/MapGenerator/css/style.css" rel="stylesheet" />
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.css" rel="stylesheet" />
<link type="text/css" href="include/LD/assets/styles/salesforce-lightning-design-system.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="modules/MapGenerator/css/popupNotification.css" rel="stylesheet">
<script type="text/javascript" src="modules/MapGenerator/language/{$currlang}.lang.js"></script>
<script type="text/javascript" src="modules/MapGenerator/js/functions.js"></script>
<script type="text/javascript" src="modules/MapGenerator/jquery/script.js"></script>
<script type="text/javascript" src="modules/MapGenerator/js/MapGenerator.js"></script>
<script src="https://use.fontawesome.com/c74e66ed00.js"></script>
<div class="small">
    <table class="slds-table slds-no-row-hover slds-table--cell-buffer slds-table-moz" style="background-color: #f7f9fb;">
        <tr class="slds-text-title--caps">
            <td style="padding: 0;">
                {assign var="USE_ID_VALUE" value=$MOD_SEQ_ID} {if $USE_ID_VALUE eq ''} {assign var="USE_ID_VALUE" value=$ID} {/if}
                <div class="slds-page-header s1FixedFullWidth s1FixedTop forceHighlightsStencilDesktop" style="height: 60px; margin-top: 15px;">
                    <div class="slds-grid primaryFieldRow" style="transform: translate3d(0, -8.65823px, 0);">
                        <div class="slds-grid slds-col slds-has-flexi-truncate slds-media--center">
                            <div class="slds-media__body">
                                <h1 class="slds-page-header__title slds-m-right--small slds-truncate slds-align-middle">
								<span class="uiOutputText">{$MOD.MVCreator}</span>
								</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <br/>
    <table border=0 cellspacing=0 cellpadding=0 width=90% align=center>
        <tr>
            <td>
                <div class="slds-truncate">
                    <table class="slds-table slds-no-row-hover dvtContentSpace" style="width: 90%;" align="center">
                        <tr>
                            <td valign="top" style="padding: 0;">
                                <div class="slds-tabs--scoped">
                                    <ul class="slds-tabs--scoped__nav" role="tablist" style="margin-bottom: 0;">
                                        <li id="firstTab" class="slds-tabs--scoped__item active" title="" role="presentation">
                                            <a onclick="selectTab(true);" class="slds-tabs--scoped__link" data-autoload-maps="true" data-autoload-Filename="MapGenerator,createMaps" aria-selected="true" data-autoload-id-put="CreateMaps" data-autoload-id-relation="LoadMAps" href="" role="tab" tabindex="0" aria-selected="true" aria-controls="tab--scoped-1" id="tab--scoped--1__item">{$MOD.CreateView}</a>
                                        </li>
                                        <li id="secondTab" class="slds-tabs--scoped__item slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open" title="" role="presentation">
                                            <a onclick="selectTab(false);" class="slds-tabs--scoped__link" data-autoload-maps="true" data-autoload-Filename="MapGenerator,LoadAllMaps" data-autoload-id-put="LoadMAps" data-autoload-id-relation="CreateMaps" href="" role="tab" tabindex="-1" aria-selected="false" aria-controls="tab--scoped-2" id="tab--scoped-2__item">{$MOD.LoadMap}</a>
                                        </li>
                                    </ul>
                                    {* for notification *}
                                    <div id="snackbar"></div>
                                    <!-- CREATE MAP TAB -->
                                    <div id="CreateMaps" role="tabpanel" aria-labelledby="tab--scoped-1__item" class="slds-tabs--scoped__content slds-truncate">
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
                                                                        <div class="slds-select_container">
                                                                            <input type="text" minlength="5" id="nameView" name="nameView" data-controll="true" data-controll-idlabel="ShowErorrNameMap" data-controll-file="MapGenerator,CheckNameOfMap" data-controll-id-relation="TypeMaps" class="slds-input" name='nameView' placeholder="{$MOD.addviewname}" />
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr class="slds-line-height--reset">
                                                                    <td class="dvtCellLabel" width="50%">{$MOD.TypeMapNone}</td>
                                                                    <td class="dvtCellInfo" align="left" width="50%">
                                                                        <div class="slds-select_container">
                                                                            <select data-load-Map="true" data-type-select="TypeMap" data-type-select-module="MapGenerator,ChoseeObject" id="TypeMaps" class="slds-select" disabled>
                                                                                <option value="">{$MOD.TypeMapNone}</option>
                                                                                <option value="SQL">{$MOD.ConditionQuery}</option>
                                                                                <option value="Mapping">{$MOD.TypeMapMapping}</option>
                                                                                <option value="IOMap">{$MOD.TypeMapIOMap}</option>
                                                                                <option value="FieldDependency">{$MOD.TypeMapFieldDependency}</option>
                                                                                <option value="FieldDependencyPortal">{$MOD.FieldDependencyPortal}</option>
                                                                                <option value="WS">{$MOD.TypeMapWS}</option>
                                                                                <option value="MasterDetail">{$MOD.MasterDetail}</option>
                                                                                <option value="ListColumns">{$MOD.ListColumns}</option>
                                                                                <option value="Module_Set">{$MOD.module_set}</option>
                                                                                <option value="GlobalSearchAutocomplete">{$MOD.GlobalSearchAutocompleteMapping}</option>
                                                                                <option value="CREATEVIEWPORTAL">{$MOD.CREATEVIEWPORTAL}</option>
                                                                                <option value="ConditionExpression">{$MOD.ConditionExpression}</option>
                                                                                <option value="DETAILVIEWBLOCKPORTAL">{$MOD.DETAILVIEWBLOCKPORTAL}</option>
                                                                                <option value="MENUSTRUCTURE">{$MOD.MENUSTRUCTURE}</option>
                                                                                <option value="RecordAccessControl">{$MOD.RecordAccessControl}</option>
                                                                                <option value="DuplicateRecords">{$MOD.DuplicateRecords}</option>
                                                                                {*
                                                                                <!-- <option value="ConditionQuery">{$MOD.ConditionQuery}</option> -->*}
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
                                    </div>
                                    <div id="LoadMAps" style="display: none;" role="tabpanel" aria-labelledby="tab--scoped-1__item" class="slds-tabs--scoped__content slds-truncate">
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>