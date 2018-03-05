{*ListColumns.tpl*}
<div>

    {* <style scope>
         @import "modules/MapGenerator/css/WSstyle.css"; 
    </style> *}
    <div id="LoadingImage" style="display: none"><img src=""/></div>

    {if $HistoryMap neq ''}
        <script type="text/javascript">
            App.savehistoryar='{$HistoryMap}';
        </script>
    {/if}



    <div id="ModalDiv">
        {if $Modali neq ''}
            <div>
            {$Modali}
            </div>
        {/if}
    </div>
	<table class="slds-table slds-no-row-hover slds-table-moz map-generator-table">
		<tbody>
			<tr class="blockStyleCss" id="DivObjectID">
				<td class="detailViewContainer" valign="top">
					<div class="forceRelatedListSingleContainer">
						<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
							<div class="slds-card__header slds-grid">
								<header class="slds-media--center slds-has-flexi-truncate">
									<h1 id="mapNameLabel" class="slds-page-header__title slds-m-right--small slds-truncate">
										{if $NameOFMap neq ''} {$NameOFMap} {/if}
									</h1>
									<p class="slds-text-heading--label slds-line-height--reset">{$MOD.FieldDependency}</p>
								</header>
								<div class="slds-no-flex">
									<div class="actionsContainer mapButton">
										<div class="slds-section-title--divider">
											{if $HistoryMap neq ''} {* saveFieldDependency *}
											<button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button>
											{else} {* saveFieldDependency *}
											<button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button>
											{/if} &nbsp;
											<button class="slds-button slds-button--small slds-button--brand"  data-loading="true" data-loading-divid="LoadingDivId"  data-send-data-id="ListData,MapName" data-send="true" data-send-url="MapGenerator,saveFieldDependency" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="ShowLocalHistoryFD" >{$MOD.CreateMap}</button> 
										</div>
									</div>
								</div>
							</div>
						</article>
					</div>
					<div class="slds-truncate">
						<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
							<tr class="slds-line-height--reset map-generator-cell-container">
								<td class="dvtCellLabel" valign="top">
									<!-- THE MODULE Zone -->
									<div class="ws-accordion">
										<!-- WS Configuration Panel -->
										<section  class="ws-accordion-item ws-active">
											<div class="ws-accordion-header">
												<a onclick="showhideblocks(this);" id="ws-section-configuration">
													<span class="ws-accordion-toggle" id="ws-configuration">
														<i class="fa fa-arrow-right" id="ws-hide" style="display: none;"></i>
														<i class="fa fa-arrow-down"  id="ws-show" ></i>
													</span>
													<h4 class="ws-accordion-title">{$MOD.WSConfirguration}</h4>
												</a>
											</div>
											<div class="ws-accordion-item-content" style="display: block;">
												<div class="ws-configuration-container">
													<!-- WS URL and method container -->
													<div class="ws-url-container">
														<!-- URL input-->
														<div class="ws-url-input">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="url-input">{$MOD.wsURL}</label>
																<div class="slds-form-element__control slds-input-has-icon_left">
																	<span class="slds-icon slds-input__icon slds-input__icon_left slds-icon-text-default" id="fixed-text-addon-pre">https://</span>
																	<input id="url-input" class="slds-input" placeholder="Enter {$MOD.wsURL}" type="text" aria-describedby="fixed-text-addon-pre fixed-text-addon-post" />
																</div>
															</div>
														</div>
														<!-- URL Method -->
														<div class="ws-url-method">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="urlMethod">{$MOD.wsMethod}</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="urlMethod" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				<option value="CONNECT">CONNECT</option>
																				<option value="DELETE">DELETE</option>
																				<option selected value="GET">GET</option>
																				<option value="HEAD">HEAD</option>
																				<option value="OPTIONS">OPTIONS</option>
																				<option value="PATCH">PATCH</option>
																				<option value="POST">POST</option>
																				<option value="PUT">PUT</option>
																				<option value="TRACE">TRACE</option>
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- WS Configuration options container-->
													<div class="ws-config-options">
														<div class="ws-response-user-password">
															<div class="slds-form-element slds-text-align--left ws-response-time">
																<label class="slds-form-element__label" for="ws-response-time">{$MOD.wsResponseTime} (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-response-time" class="slds-input" placeholder="{$MOD.wsResponseTime}" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-user">
																<label class="slds-form-element__label" for="ws-user">{$MOD.wsUser} (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-user" class="slds-input" placeholder="{$MOD.wsUser}" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-password">
																<label class="slds-form-element__label" for="ws-password">{$MOD.wsPassword} (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-password" class="slds-input" placeholder="{$MOD.wsPassword}" type="text" />
																</div>
															</div>
														</div>
														<div class="ws-host-port-tag">
															<div class="slds-form-element slds-text-align--left ws-host">
																<label class="slds-form-element__label" for="ws-proxy-host">{$MOD.wsProxyHost}</label>
																<div class="slds-form-element__control">
																	<input id="ws-password" class="slds-input" placeholder="{$MOD.wsProxyHost}" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-port">
																<label class="slds-form-element__label" for="ws-proxy-port">{$MOD.wsProxyPort}</label>
																<div class="slds-form-element__control">
																	<input id="ws-proxy-port" class="slds-input" placeholder="{$MOD.wsProxyPort}" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-tag">
																<label class="slds-form-element__label" for="ws-start-tag">{$MOD.wsStartTag}</label>
																<div class="slds-form-element__control">
																	<input id="ws-start-tag" class="slds-input" placeholder="{$MOD.wsStartTag}" type="text" />
																</div>
															</div>
														</div>
													</div>
													<!-- WS Input/Output type container -->
													<div class="ws-input-output">
														<div class="ws-input-type">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-type">{$MOD.wsInputType}</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="ws-input-type" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				{$listdtat}
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="ws-output-type">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-output-type">{$MOD.wsOutputType}</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="ws-output-type" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				{$listdtat}
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- WS Add headers & Add button container -->
													<div class="ws-config-buttons">
														<!-- Add headers Modal -->
														<div class="slds-form-element slds-text-align--left ws-add-headers">
															<button class="slds-button slds-button--small slds-button--brand" data-modal-saveas-open="true" data-modal-id="ws-configuration-headers-modal" data-modal-backdrop-id="ws-configuration-headers-backdrop">{$MOD.wsAddHeaders}</button>
														</div>
														<!-- Add button -->
														<div class="slds-form-element slds-text-align--right ws-add-button">
															<button class="slds-button slds-button--small slds-button--brand">{$MOD.wsAdd}</button>
														</div>
													</div>
												</div>
											</div>
										</section>
										<!-- WS Input Panel -->
										<section class="ws-accordion-item">
											<div onclick="showhideblocks(this);" id="ws-section-input" >
												<div class="ws-accordion-header">
												<div class="ws-accordion-toggle" id="ws-configuration">
													<i class="fa fa-arrow-right" id="ws-hide" style="display: block;"></i>
													<i class="fa fa-arrow-down"  id="ws-show" style="display: none;" ></i>
												</div>
												<h4 class="ws-accordion-title">{$MOD.WSInputFields}</h4>
											</div>
											<div class="ws-accordion-item-content" style="display: block;">
												<div class="ws-input-container">
													<div class="ws-input-name-container">
														<div class="ws-input-name">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-name">Name</label>
																<div class="slds-form-element__control">
																	<input id="ws-input-name" class="slds-input" type="text" />
																</div>
															</div>
														</div>
													</div>
													<div class="ws-input-value-attribute-container">
														<div class="slds-form-element ws-input-value">
															<label class="slds-form-element__label">Value</label>
															<div class="ws-input-value-switcher">
																<div class="slds-form-element__control slds-input-has-icon slds-input-has-icon_right" id="ws-input-value-input" style="display: block;">
																	<input class="slds-input" placeholder="Placeholder Text" type="text" />
																	<button id="ws-input-value-btn" data-load-show="true" data-load-show-relation="FirstModule,Firstfield,secmodule,DefaultValue" data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add">
																		<img src="themes/images/btnL3Add.gif" width="16">
																	</button>
																</div>
																<div class="slds-form-element__control" id="ws-input-value-select" style="display: none;">
																	<div class="slds-select_container">
																		<select class="slds-select">
																		</select>
																	</div>
																</div>
																<div class="ws-toggle-field">
																	<a href="#" data-showhide-load="true" data-tools-id="ws-input-value-input,ws-input-value-select">
																		<i class="fa fa-refresh fa-2x" aria-hidden="true"></i>
																	</a>
																</div>
															</div>
														</div>
														<div class="ws-input-attribute">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-attribute">Attribute</label>
																<div class="slds-form-element__control">
																	<input id="ws-input-attribute" class="slds-input" type="text" />
																</div>
															</div>
														</div>
													</div>
													<div class="ws-input-organization-default-container">
														<div class="ws-input-organization">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-organization">Organization</label>
																<div class="slds-form-element__control">
																	<input id="ws-input-organization" class="slds-input" type="text" />
																</div>
															</div>
														</div>
														<div class="ws-input-default">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-default">Default</label>
																<div class="slds-form-element__control">
																	<input id="ws-input-default" class="slds-input" type="text" />
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											</div>
										</section>
										<!-- WS Output Panel -->
										<section onclick="showhideblocks(this);" id="ws-section-output" class="ws-accordion-item">
											<div class="ws-accordion-header">
												<div class="ws-accordion-toggle" id="ws-output">
													<i class="fa fa-arrow-right"></i>
													<i class="fa fa-arrow-down" style="display: none;"></i>
												</div>
												<h4 class="ws-accordion-title">{$MOD.WSOutputFields}</h4>
											</div>
											<div class="ws-accordion-item-content">
												
											</div>
										</section>
										<!-- WS Error Handler Panel -->
										<section onclick="showhideblocks(this);" id="ws-section-errorhandler" class="ws-accordion-item">
											<div class="ws-accordion-header">
												<div class="ws-accordion-toggle" id="ws-error">
													<i class="fa fa-arrow-right"></i>
													<i class="fa fa-arrow-down" style="display: none;"></i>
												</div>
												<h4 class="ws-accordion-title">{$MOD.WSErrorHandler}</h4>
											</div>
											<div class="accordion-item-content">
												
											</div>
										</section>
									</div>
								</td>
								<td class="dvtCellInfo" align="left">
									<div class="flexipageComponent">
										<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
											<div class="slds-card__header slds-grid">
												<header class="slds-media slds-media--center slds-has-flexi-truncate">
													<div class="slds-media__body">
														<h2 class="header-title-container"> 
															<span class="slds-text-heading--small slds-truncate actionLabel">
																<b>Popup</b>
															</span> 
														</h2>
													</div>
												</header>
											</div>
											<div class="slds-card__body slds-card__body--inner">
												<div id="contenitoreJoin">
													<div id="LoadShowPopup"></div>
												</div>
											</div>
											{*End div contenitorejoin*}
										</article>
									</div>
									<br/>
									<div class="flexipageComponent">
										<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
											<div class="slds-card__header slds-grid">
												<header class="slds-media slds-media--center slds-has-flexi-truncate">
													<div class="slds-media__body">
														<h2 class="header-title-container"> 
															<span class="slds-text-heading--small slds-truncate actionLabel">
																<b>History</b>
															</span> 
														</h2>
													</div>
												</header>
											</div>
											<div class="slds-card__body slds-card__body--inner">
												<div id="contenitoreJoin">
													<div id="LoadHistoryPopup"></div>
												</div>{*End div contenitorejoin*}
											</div>
										</article>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</tbody>
	</table>

	<div id="waitingIddiv"></div>
	<div id="contentJoinButtons" style="width: 70%;height: 100%;float: left;">
	</div>
	<div id="generatedquery">
		<div id="results" style="margin-top: 1%;"></div>
	</div>
	<div id="null"></div>
	<div>
		<div id="queryfrommap"></div>
	</div>

	<!-- Add Configuration Headers Modal -->
	<div class="ws-configuration-headers">
		<div class="slds-modal" aria-hidden="false" role="dialog" id="ws-configuration-headers-modal">
			<div class="slds-modal__container">
				<div class="slds-modal__header">
					<button class="slds-button slds-button--icon-inverse slds-modal__close" data-modal-saveas-close="true" data-modal-close-id="ws-configuration-headers-modal" data-modal-close-backdrop-id="ws-configuration-headers-backdrop" >
						<svg aria-hidden="true" class="slds-button__icon slds-button__icon--large">
							<use xlink:href="include/LD/assets/icons/action-sprite/svg/symbols.svg#close"></use>
						</svg>
						<span class="slds-assistive-text">{$MOD.close}</span>
					</button>
					<h2 class="slds-text-heading--medium">{$MOD.wsAddHeaders}</h2>
				</div>
				<div class="slds-modal__content slds-p-around--medium ws-modal-container">
					<!-- Key Name -->
					<div class="slds-form-element">
						<label class="slds-form-element__label" for="ws-key-name">{$MOD.wsKeyName}</label>
						<div class="slds-form-element__control">
							<input id="ws-key-name" name="mod" class="slds-input" placeholder="insert the {$MOD.wsKeyName}"/>
						</div>
					</div>
					<!-- Key Value -->
					<div class="slds-form-element">
						<label class="slds-form-element__label" for="ws-key-value">{$MOD.wsKeyValue}</label>
						<div class="slds-form-element__control">
							<input id="ws-key-value" name="mod" class="slds-input" placeholder="insert the {$MOD.wsKeyValue}"/>
						</div>
					</div>
				</div>
				<div class="slds-modal__footer">
					<button class="slds-button slds-button--small slds-button--brand" onclick="" data-add-button-popup="false" data-add-type="Field" data-add-button-validate="Firstfield2"  data-add-relation-id="FirstModule,Firstfield2" data-show-id="Firstfield2" data-div-show="LoadShowPopup">
						{$MOD.Add}
					</button>
					<button class="slds-button slds-button--small slds-button--destructive" data-modal-saveas-close="true" data-modal-close-id="ws-configuration-headers-modal" data-modal-close-backdrop-id="ws-configuration-headers-backdrop" >{$MOD.cancel}</button>
				</div>
			</div>
		</div>
		<div class="slds-backdrop" id="ws-configuration-headers-backdrop"></div>
	</div>

	<div>
		<input type="hidden" name="MapID" value="{$MapID}" id="MapID">
		<input type="hidden" name="queryid" value="{$queryid}" id="queryid">
		<input type="hidden" name="querysequence" id="querysequence" value="">
		<input type="hidden" name="MapName" id="MapName" value="{$MapName}">
	</div>
</div>