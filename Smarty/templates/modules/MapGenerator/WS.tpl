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
										<section class="ws-accordion-item ws-active">
											<div class="ws-accordion-header">
												<div class="ws-accordion-toggle" id="ws-configuration">
													<i class="fa fa-arrow-right" id="ws-hide" style="display: none;"></i>
													<i class="fa fa-arrow-down"  id="ws-show" ></i>
												</div>
												<h4 class="ws-accordion-title">{$MOD.WSConfirguration}</h4>
											</div>
											<div class="ws-accordion-item-content" style="display: block;">
												<div class="ws-configuration-container">
													<!-- WS URL and method container -->
													<div class="ws-url-container">
														<!-- URL input-->
														<div class="ws-url-input">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="url-input">URL</label>
																<div class="slds-form-element__control slds-input-has-icon_left">
																	<span class="slds-icon slds-input__icon slds-input__icon_left slds-icon-text-default" id="fixed-text-addon-pre">https://</span>
																	<input id="url-input" class="slds-input" placeholder="Enter URL" type="text" aria-describedby="fixed-text-addon-pre fixed-text-addon-post" />
																</div>
															</div>
														</div>
														<!-- URL Method -->
														<div class="ws-url-method">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="urlMethod">Method</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="urlMethod" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				{$FirstModuleSelected}
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
																<label class="slds-form-element__label" for="ws-response-time">Response Time (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-response-time" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-user">
																<label class="slds-form-element__label" for="ws-user">User (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-user" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-password">
																<label class="slds-form-element__label" for="ws-password">Password (Optional)</label>
																<div class="slds-form-element__control">
																	<input id="ws-password" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
														</div>
														<div class="ws-host-port-tag">
															<div class="slds-form-element slds-text-align--left ws-host">
																<label class="slds-form-element__label" for="ws-proxy-host">Proxy Host</label>
																<div class="slds-form-element__control">
																	<input id="ws-password" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-port">
																<label class="slds-form-element__label" for="ws-proxy-port">Proxy Port</label>
																<div class="slds-form-element__control">
																	<input id="ws-proxy-port" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
															<div class="slds-form-element slds-text-align--left ws-tag">
																<label class="slds-form-element__label" for="ws-start-tag">Start Tag</label>
																<div class="slds-form-element__control">
																	<input id="ws-start-tag" class="slds-input" placeholder="Placeholder Text" type="text" />
																</div>
															</div>
														</div>
													</div>
													<!-- WS Input/Output type container -->
													<div class="ws-input-output">
														<div class="ws-input-type">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-input-type">Input Type</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="ws-input-type" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				{$FirstModuleSelected}
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="ws-output-type">
															<div class="slds-form-element slds-text-align--left">
																<label class="slds-form-element__label" for="ws-output-type">Output Type</label>
																<div class="slds-form-element__control">
																	<div class="slds-select_container">
																		<select id="ws-output-type" data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-second-module-id="PickListFields"  data-select-relation-field-id="Firstfield,Firstfield2" data-module="MapGenerator"   data-second-module-file="getPickList" name="mod" class="slds-select">
																				{$FirstModuleSelected}
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
															<button class="slds-button slds-button--small slds-button--brand" data-modal-saveas-open="true" data-modal-id="ws-configuration-headers-modal" data-modal-backdrop-id="ws-configuration-headers-backdrop">Add Headers</button>
														</div>
														<!-- Add button -->
														<div class="slds-form-element slds-text-align--right ws-add-button">
															<button class="slds-button slds-button--small slds-button--brand">Add</button>
														</div>
													</div>
												</div>
											</div>
										</section>
										<!-- WS Input Panel -->
										<section class="ws-accordion-item ws-active">
											<div class="ws-accordion-header">
												<div class="ws-accordion-toggle">
													<i class="fa fa-arrow-right" ></i>
													<i class="fa fa-arrow-down" style="display: none;"></i>
												</div>
												<h4 class="ws-accordion-title">{$MOD.WSInputFields}</h4>
											</div>
											<div class="ws-accordion-item-content" style="display: block;">
												<div class="ws-input-name">
													<div class="slds-form-element slds-text-align--left">
														<label class="slds-form-element__label" for="ws-input-name">Name</label>
														<div class="slds-form-element__control">
															<input id="ws-input-name" class="slds-input" type="text" />
														</div>
													</div>
												</div>
												<div class="ws-input-value">
													<div class="slds-form-element slds-text-align--left">
														<label class="slds-form-element__label" for="ws-input-value">Value</label>
														<div class="slds-form-element__control">
															<input id="ws-input-value" class="slds-input" type="text" />
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
												<div class="ws-buttons">
													<!-- Add button -->
													<div class="slds-form-element slds-text-align--right ws-add-button">
														<button class="slds-button slds-button--small slds-button--brand">Add</button>
													</div>
												</div>
											</div>
										</section>
										<!-- WS Output Panel -->
										<section class="ws-accordion-item">
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
										<section class="ws-accordion-item">
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
					<h2 class="slds-text-heading--medium"> Add headers </h2>
				</div>
				<div class="slds-modal__content slds-p-around--medium ws-modal-container">
					<!-- Key Name -->
					<div class="slds-form-element">
						<label class="slds-form-element__label" for="ws-key-name">Key Name</label>
						<div class="slds-form-element__control">
							<input id="ws-key-name" name="mod" class="slds-input"/>
						</div>
					</div>
					<!-- Key Value -->
					<div class="slds-form-element">
						<label class="slds-form-element__label" for="ws-key-value">Key Value</label>
						<div class="slds-form-element__control">
							<input id="ws-key-value" name="mod" class="slds-input"/>
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