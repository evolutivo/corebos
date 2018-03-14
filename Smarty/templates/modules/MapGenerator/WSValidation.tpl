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


{if $PopupJS neq ''}
	<script type="text/javascript">
			{foreach from=$PopupJS item=allitems key=key name=name}
					 {foreach name=outer item=popi from=$allitems}
						var temparray = {};
						{foreach key=key item=item from=$popi}
								{if $key eq 'Anotherdata'}
									rows=new Array();
									allfieldsval=[];
									allfieldstetx=[];
										{foreach from=$item item=itemi key=keyes}
											checkifexist={};
											fieldsval=[];
											fieldstetx=[];
											{foreach from=$itemi item=items key=key name=name}
												checkifexist['DataValues']=`{$itemi.DataValues}`;
												checkifexist['DataText']=`{$itemi.DataText}`;
											{/foreach}
											{literal}
												allfieldsval.push(checkifexist);
											{/literal}
									{/foreach}
									{literal}
										temparray["Anotherdata"]=allfieldsval;
									{/literal}
								{else}
								 temparray['{$key}']='{$item}';
								{/if}
						{/foreach}
						App.popupJson.push({'{'}temparray{'}'});
						// console.log(temparray);
					{/foreach}
					 HistoryPopup.addtoarray(App.popupJson,"PopupJSON");
					App.popupJson.length=0;
			{/foreach}
			ShowLocalHistoryWS('LoadHistoryPopup','LoadShowPopup');
			ClickToshowSelectedWS(parseInt(App.SaveHistoryPop.length-1),'LoadShowPopup');
			App.countsaveMap=2;
			App.utils.UpdateMapNAme();
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
											<button class="slds-button slds-button--small slds-button--brand"  data-loading="true" data-loading-divid="LoadingDivId"  data-send-data-id="ListData,MapName" data-send="true" data-send-url="MapGenerator,saveWebServiceMap" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="ShowLocalHistoryWS" >{$MOD.CreateMap}</button> 
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

									<div class="ws-val-container">
										<div class="ws-val-modules">
											<!-- Origin Module-->
											<div class="ws-val-origin">
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-origin">{$MOD.wsModule}</label>
													<div class="slds-form-element__control">
														<div class="slds-select_container">
															<select id="ws-val-origin" required data-second-select-load="true" data-second-firstmodule-id="FirstModule" data-module="MapGenerator" data-second-select-relation-id="ws-select-multiple,ws-output-select-multiple" data-second-select-file="mappingFieldRelation"  name="mod" class="slds-select">
																{$FirstModuleSelected}
															</select>
														</div>
													</div>
												</div>
											</div>
											<!-- Target Module-->
											<div class="ws-val-target">
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-target">{$MOD.wsModule}</label>
													<div class="slds-form-element__control">
														<div class="slds-select_container">
															<select id="ws-val-target" required data-second-select-load="true" data-second-firstmodule-id="FirstModule" data-module="MapGenerator" data-second-select-relation-id="ws-select-multiple,ws-output-select-multiple" data-second-select-file="mappingFieldRelation"  name="mod" class="slds-select">
																{$FirstModuleSelected}
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="ws-val-fields">
											<h5 class="ws-val-fields-header slds-text-align--center">Fields</h5>
											<div class="ws-val-name-input">
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-name">Name</label>
													<div class="slds-form-element__control">
														<input id="ws-val-name" class="slds-input" placeholder="Enter {$MOD.wsURL}" type="text" required aria-describedby="fixed-text-addon-pre fixed-text-addon-post" />
													</div>
												</div>
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-value">Value</label>
													<div class="slds-form-element__control">
														<input id="ws-val-value" class="slds-input" placeholder="Enter {$MOD.wsURL}" type="text" required aria-describedby="fixed-text-addon-pre fixed-text-addon-post" />
													</div>
												</div>
											</div>
											<div class="ws-val-validation-origin">
												<!-- WS Validation Validation Input -->
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-validation">Validation</label>
													<div class="slds-form-element__control">
														<input id="ws-val-validation" class="slds-input" placeholder="Enter {$MOD.wsURL}" type="text" required aria-describedby="fixed-text-addon-pre fixed-text-addon-post" />
													</div>
												</div>
												<!-- WS Validation Origin Select -->
												<div class="slds-form-element slds-text-align--left">
													<label class="slds-form-element__label" for="ws-val-origin-select">Origin</label>
													<div class="slds-form-element__control">
														<div class="slds-select_container">
															<select id="ws-val-origin-select" required data-second-select-load="true" data-second-firstmodule-id="FirstModule" data-module="MapGenerator" data-second-select-relation-id="ws-select-multiple,ws-output-select-multiple" data-second-select-file="mappingFieldRelation"  name="mod" class="slds-select">
																{$FirstModuleSelected}
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="ws-val-button">
											<button class="slds-button slds-button--small slds-button--brand" > Add </button>
										</div>
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
												<div id="contenitoreJoin" style="height: 300px;">
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
							<input id="ws-key-name" name="mod" class="slds-input" required placeholder="insert {$MOD.wsKeyName}"/>
						</div>
					</div>
					<!-- Key Value -->
					<div class="slds-form-element">
						<label class="slds-form-element__label" for="ws-key-value">{$MOD.wsKeyValue}</label>
						<div class="slds-form-element__control">
							<input id="ws-key-value" name="mod" required class="slds-input" placeholder="insert {$MOD.wsKeyValue}"/>
						</div>
					</div>
				</div>
				<div class="slds-modal__footer">
					<button class="slds-button slds-button--small slds-button--brand" onclick="AddPopupForHeaders(this);RestoreDataEXFIM(this)" data-add-button-popup="false" data-add-type="Header" data-add-button-validate="ws-key-name"  data-add-relation-id="ws-key-name,ws-key-value" data-show-id="" data-div-show="LoadShowPopup">
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