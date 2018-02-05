

 <div id="LoadingImage" style="display: none">
    <img src=""/>
  </div>
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

            App.ModulLabel='Module';
			App.FieldLabel='Value';
			App.DefaultValue='Value';
            ShowLocalHistoryRecordSetMapping('LoadHistoryPopup','LoadShowPopup')
            ClickToshowSelectedFiledsRecordSetMapping(parseInt(App.SaveHistoryPop.length-1),'LoadShowPopup');
         App.countsaveMap=2;
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
		<tr class="blockStyleCss">
			<td class="detailViewContainer" valign="top"> 
				<div class="forceRelatedListSingleContainer">
					<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
						<div class="slds-card__header slds-grid">
							<header class="slds-media--center slds-has-flexi-truncate">
								<h1 class="slds-page-header__title slds-m-right--small slds-truncate">
									 {if $NameOFMap neq ''} {$NameOFMap} {/if}
								</h1>
								<p class="slds-text-heading--label slds-line-height--reset"> {$MOD.RecordSetMapping} </p>
							</header>
							<div class="slds-no-flex">
								<div class="actionsContainer mapButton">
									<div class="slds-section-title--divider">
										{if $HistoryMap neq ''}
                                        {* saveFieldDependency *}
                                        <button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton">{$MOD.SaveAsMap}</button>
                                      {else}
                                        {* saveFieldDependency *}
                                        <button class="slds-button slds-button--small slds-button--neutral" data-modal-saveas-open="true" id="SaveAsButton" disabled>{$MOD.SaveAsMap}</button>
                                      {/if}
										<button class="slds-button slds-button--small slds-button--brand" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveRecordSetMapping" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-send-savehistory-functionname="ShowLocalHistoryRecordSetMapping" data-save-history-show-id-relation="LoadShowPopup" >{$MOD.CreateMap}</button>

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
								<label class="slds-form-element__label" for="record-set-mapping-insert-value" style="width100%;margin-top: 5px;text-align: left;">
									{$MOD.RecordSetMappingLabelPutID}
								</label>
								<div class="inset-value-container">
									
									<!-- Enable/Disable below fields -->
									<div class="slds-form-element toggle-field-below" style="width:20%;">
										<label class="slds-checkbox--toggle slds-grid toggle-readonly" for="checkbox-4">
											<input id="checkbox-4" name="checkbox" checked="checked" type="checkbox" onchange="showHide(this);" aria-describedby="toggle-desc" />
											<span id="checkbox-check" class="slds-checkbox--faux_container" aria-live="assertive">
												 <span class="slds-checkbox--faux"></span>
                                                  <span class="slds-checkbox--on">{$MOD.YES}</span>
                                                  <span class="slds-checkbox--off">{$MOD.NO}</span>
											</span>
										</label>
									</div>
									<!-- Input value -->
									<div id="DivId" class="slds-form-element" style="width:  100%;margin-top: 20px;">
										<!--<label class="slds-form-element__label" for="record-set-mapping-insert-value">Input Label</label>-->
										<div class="slds-form-element__control input-value-with-icon">
											<input id="inputforId" class="slds-input" placeholder="ID" type="text" />
										</div>
									</div>

								</div>

								<!-- Choose the value -->
								<div id="DivModule" style="display:none;" class="slds-form-element">
									<label class="slds-form-element__label" for="record-set-mapping-choose-module">
									       {$MOD.Module}
									</label>
									<div class="slds-form-element__control">
										<div class="slds-select_container">
											<select class="slds-select" id="FirstModule">
												{$FirstModuleSelected}
											</select>
										</div>
									</div>
								</div>

								<!-- Entity value -->
								<div id="DivEntity" style="display:none;" class="slds-form-element">
									<label class="slds-form-element__label" for="record-set-mapping-entity-value">
									    {$MOD.EndtityLabel}
									</label>
									<div class="slds-form-element__control">
										<input id="EntityValueId" class="slds-input" placeholder="Entity Value" type="text" />
									</div>
								</div>

								<!-- Action and addButton-->
								<div class="action-add-button-container">
									<!-- Action content -->
									<div class="slds-form-element record-set-mapping-action-select">
										<label class="slds-form-element__label" for="record-set-mapping-action">Action</label>
										<div class="slds-form-element__control">
											<div class="slds-select_container">
												<select class="slds-select" id="ActionId">
													<option value="">Please select</option>
													<option>Include</option>
													<option selected>Exclude</option>
													<option>Group</option>
												</select>
											</div>
										</div>
									</div>
									<!-- Add button content -->
									<div class="add-button-content slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click">
										<button class="slds-button slds-button_icon" id="addPopupButton" data-add-button-popup="true" 
										         data-add-type="ID" 
												 data-add-relation-id="inputforId,ActionId" 
												 data-show-id="inputforId" 
												 data-show-modul-id="" 
												 data-add-button-validate="inputforId" 
												 onclick="RestoreData(this)" 
												 data-div-show="LoadShowPopup" class="slds-button slds-button_icon" aria-haspopup="true" title="{$MOD.ClickAdd}">
											<img src="themes/images/btnL3Add.gif" width="18">
										</button>
									</div>
								</div>
								 <input type="hidden" name="MapID" value="{$MapID}" id="MapID">
								<input type="hidden" name="queryid" value="{$queryid}" id="queryid">
								<input type="hidden" name="querysequence" id="querysequence" value="">
								<input type="hidden" name="MapName" id="MapName" value="{$MapName}">

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