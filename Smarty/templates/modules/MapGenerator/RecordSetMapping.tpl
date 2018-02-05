
<table class="slds-table slds-no-row-hover slds-table-moz record-set-map">
	<tbody>
		<tr class="blockStyleCss">
			<td class="detailViewContainer" valign="top">
				<div class="forceRelatedListSingleContainer">
					<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
						<div class="slds-card__header slds-grid">
							<header class="slds-media--center slds-has-flexi-truncate">
								<h1 class="slds-page-header__title slds-m-right--small slds-truncate">
									Name of map
								</h1>
								<p class="slds-text-heading--label slds-line-height--reset"> Maptype </p>
							</header>
							<div class="slds-no-flex">
								<div class="actionsContainer mapButton">
									<div class="slds-section-title--divider">

										<button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button>

										<button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button>

										<button class="slds-button slds-button--small slds-button--brand">{$MOD.CreateMap}</button>

									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
				<div class="slds-truncate">
					<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
						<tr class="slds-line-height--reset">
							<td class="dvtCellLabel record-set-mapping-container" width="70%" valign="top">
								<div class="inset-value-container">
									<!-- Input value -->
									<div class="slds-form-element">
										<label class="slds-form-element__label" for="record-set-mapping-insert-value">Input Label</label>
										<div class="slds-form-element__control input-value-with-icon">
											<input id="record-set-mapping-insert-value" class="slds-input" placeholder="Placeholder Text" type="text" />
										</div>
									</div>
									<!-- Enable/Disable below fields -->
									<div class="slds-form-element toggle-field-below">
										<label class="slds-checkbox--toggle slds-grid toggle-readonly" for="checkbox-4">
											<input id="checkbox-4" name="checkbox" checked="checked" type="checkbox" aria-describedby="toggle-desc" />
											<span id="checkbox-check" class="slds-checkbox--faux_container" aria-live="assertive">
												<span class="slds-checkbox--faux"></span>
											</span>
											<span class="slds-form-element__label">Checkbox</span>
										</label>
									</div>

								</div>

								<!-- Choose the value -->
								<div class="slds-form-element">
									<label class="slds-form-element__label" for="record-set-mapping-choose-module">Choose the module</label>
									<div class="slds-form-element__control">
										<div class="slds-select_container">
											<select class="slds-select" id="record-set-mapping-choose-module">
												<option value="">Please select</option>
												<option>Option One</option>
												<option>Option Two</option>
												<option>Option Three</option>
											</select>
										</div>
									</div>
								</div>

								<!-- Entity value -->
								<div class="slds-form-element">
									<label class="slds-form-element__label" for="record-set-mapping-entity-value">Entity Value</label>
									<div class="slds-form-element__control">
										<input id="record-set-mapping-entity-value" class="slds-input" placeholder="Placeholder Text" type="text" />
									</div>
								</div>

								<!-- Action and addButton-->
								<div class="action-add-button-container">
									<!-- Action content -->
									<div class="slds-form-element record-set-mapping-action-select">
										<label class="slds-form-element__label" for="record-set-mapping-action">Action</label>
										<div class="slds-form-element__control">
											<div class="slds-select_container">
												<select class="slds-select" id="record-set-mapping-action">
													<option value="">Please select</option>
													<option>Include</option>
													<option>Exclude</option>
													<option>Group</option>
												</select>
											</div>
										</div>
									</div>
									<!-- Add button content -->
									<div class="add-button-content slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click">
										<button class="slds-button slds-button_icon" aria-haspopup="true" title="Click to add">
											<img src="themes/images/btnL3Add.gif" width="18">
										</button>
									</div>
								</div>


							</td>
							<td class="dvtCellInfo" align="left" width="30%">
								<div class="flexipageComponent">
									<article class="slds-card container MEDIUM forceBaseCard runtime_sales_mergeMergeCandidatesPreviewCard" aria-describedby="header">
										<div class="slds-card__header slds-grid">
											<header class="slds-media slds-media--center slds-has-flexi-truncate">
												<div class="slds-media__body">
													<h2 class="header-title-container"> 
														<span class="slds-text-heading--small slds-truncate actionLabel">
															<b>PopUp Zone</b>
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
															<b>History Zone</b>
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