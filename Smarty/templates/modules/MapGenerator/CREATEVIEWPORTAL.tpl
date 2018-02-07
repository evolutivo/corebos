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
								{if $key eq 'rows'}
									rows=new Array();
									allfieldsval=[];
									allfieldstetx=[];
										{foreach from=$item item=itemi key=keyes}
											checkifexist={};                    
											fieldsval=[];
											fieldstetx=[];
												{foreach from=$itemi.fields item=items key=key name=name}
												 fieldsval.push('{$items}');
											{/foreach}
											{foreach from=$itemi.texts item=items key=key name=name}
												 fieldstetx.push('{$items}');
											{/foreach}                    
											{literal}
												allfieldsval.push(fieldsval);
												allfieldstetx.push(fieldstetx);
											{/literal}
									{/foreach}
									{literal}
										temparray["rows"]={fields:allfieldsval,texts:allfieldstetx};
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
		
		 if (App.SaveHistoryPop.length>0)
		{ 
			 $('#LoadHistoryPopup div').remove();
				for (var i = 0; i <=App.SaveHistoryPop.length - 1; i++) {           
							$('#LoadHistoryPopup').append(showLocalHistory(i,App.SaveHistoryPop[i].PopupJSON,'LoadHistoryPopup','LoadShowPopup'));
				}      
			 App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryCorrect);
		}else{
			 App.utils.ShowNotification("snackbar",4000,mv_arr.LoadHIstoryError);
		 }
		 //var historydata=App.SaveHistoryPop[parseInt(App.SaveHistoryPop.length-1)];
			ShowHistoryData(App.SaveHistoryPop.length-1,'LoadShowPopup');
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

{literal}
<style type="text/css">
	label{
		float: left;
	}
</style>
{/literal}

<table class="slds-table slds-no-row-hover slds-table-moz ng-scope" style="border-collapse:separate; border-spacing: 1rem;">
	<tbody>
		<tr class="blockStyleCss" id="DivObjectID">
			<td class="detailViewContainer" valign="top">
				<div class="forceRelatedListSingleContainer">
					<article class="slds-card forceRelatedListCardDesktop" aria-describedby="header">
						<div class="slds-card__header slds-grid">
							<header class="slds-media--center slds-has-flexi-truncate">
								<h1 class="slds-page-header__title slds-m-right--small slds-truncate">
									{if $NameOFMap neq ''} {$NameOFMap} {/if}
								</h1>
								<p class="slds-text-heading--label slds-line-height--reset">{$MOD.CREATEVIEWPORTAL}</p>
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
										&nbsp;
										<button class="slds-button slds-button--small slds-button--brand" data-send-data-id="ListData,MapName"   data-send="true"  data-send-url="MapGenerator,saveCreateViewPortal" data-send-saveas="true" data-send-saveas-id-butoni="SaveAsButton" data-send-savehistory="true" data-save-history="true" data-save-history-show-id="LoadHistoryPopup" data-save-history-show-id-relation="LoadShowPopup" data-send-savehistory-functionname="SavehistoryCreateViewportal">{$MOD.CreateMap}</button
									</div>
								</div>
							</div>
						</div>
					</article>
				</div>
				<div class="slds-truncate">
					<table class="slds-table slds-table--cell-buffer slds-no-row-hover slds-table--bordered slds-table--fixed-layout small detailview_table">
						<tr class="slds-line-height--reset">
							<td class="dvtCellLabel" width="70%" valign="top">
								<!-- THE MODULE Zone -->
								<div class="create-view-portal-container">
									<div class="view-portal-choose-module">
										<!-- Choose Module -->
										<div class="slds-form-element">
											<div class="slds-form-element__control">
												<label class="slds-form-element__label" for="FirstModule">Choose the Module</label>
												<select data-select-load="true" data-reset-all="true" data-reset-id-popup="LoadShowPopup" data-select-relation-field-id="FieldsForRow" data-module="MapGenerator" id="FirstModule" name="mod" class="slds-select">
													{$FirstModuleSelected}
												</select>
											</div>
										</div>
									</div>

									<div class="view-portal-write-block-name">
										<!-- Write block Name -->
										<div class="slds-form-element">
											<div class="slds-form-element__control">
												<label class="slds-form-element__label" for="BlockName">{$MOD.writeBlockName}</label>
												<input id="BlockName" class="slds-input" type="text" minlength="5" placeholder="{$MOD.writeBlockName}" />
											</div>
										</div>
									</div>

									<div id="divForAddRows">
										<div class="view-portal-select-container">
											<div class="slds-form-element">
												<div class="slds-form-element__control">
													<label class="slds-form-element__label slds-text-align--left" for="FieldsForRow">{$MOD.chooseanotherfieldsforthisrow}</label>
													<select  id="FieldsForRow" name="mod" class="slds-select" multiple="multiple">
													{$FirstModuleFields}
													</select>
												</div>
											</div>
										</div>

										<div class="view-portal-buttons-container">
											<div class="slds-form-element">
												{* <label class="slds-form-element__label" for="inputSample3">{$MOD.SelectShowFields}</label> *}
												<div class="slds-form-element__control">
													<div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click">
														<button data-add-type="Rows" data-add-relation-id="FieldsForRow" data-div-show="LoadShowPopup"  onclick="addrows(this)" class="slds-button slds-button_icon" aria-haspopup="true" title="Add more Rows">
															<img src="themes/images/btnL3Add.gif">
														</button>
													</div>
													<div class="slds-listbox_object-switcher slds-dropdown-trigger slds-dropdown-trigger_click">
														<button data-add-type="Block" data-add-relation-id="FirstModule,BlockName"  data-div-show="LoadShowPopup" onclick="showpopupCreateViewPortal(this);resetFieldCreateViewPortal();" class="slds-button slds-button--small slds-button--brand">{$MOD.Addsection}</button>
													</div>
												</div>
											</div>
										</div>
									</div>

								</div>
							</td>
							<td class="dvtCellInfo" align="left" width="40%" valign="top">
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
<div>
	<input type="hidden" name="MapID" value="{$MapID}" id="MapID">
	<input type="hidden" name="queryid" value="{$queryid}" id="queryid">
	<input type="hidden" name="querysequence" id="querysequence" value="">
	<input type="hidden" name="MapName" id="MapName" value="{$MapName}">
</div>

<div id="waitingIddiv"></div>
<div id="contentJoinButtons" style="width: 70%;height: 100%;float: left;"></div>
<div id="generatedquery">
	<div id="results" style="margin-top: 1%;"></div>
</div>
<div id="null"></div>
<div id="queryfrommap"></div>