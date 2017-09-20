<tr>
                                    <td>
                                      <table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
                                          <tr>
                                            <td class="dvtTabCacheBottom" style="padding: 1px 0;">
                                              <div class="slds-tabs--default">
                                                  <ul class="slds-tabs--default__nav " role="tablist" style="margin-bottom:0; border-bottom:none;">
                                                      <li class="tabMenuBottom slds-tabs--default__item-b slds-active" role="presentation"
                                                          title="{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}">
                                                          <a class="slds-tabs--default__link slds-tabs--default__link_mod" href="javascript:void(0);"
                                                             role="tab" tabindex="0" aria-selected="true" aria-haspopup="true" aria-controls="tab-default-1">
                                                              <span class="slds-truncate">{$SINGLE_MOD|@getTranslatedString:$MODULE} {$APP.LBL_INFORMATION}</span>
                                                          </a>
                                                      </li>
                                                      {if $SinglePane_View eq 'false' && $IS_REL_LIST neq false && $IS_REL_LIST|@count > 0}
                                                      <li class="tabMenuBottom slds-dropdown-trigger slds-dropdown-trigger_click slds-is-open slds-tabs--default__item-b slds-tabs__item_overflow"
                                                          role="presentation" title="{$APP.LBL_MORE} {$APP.LBL_INFORMATION}">
                                                          <a class="slds-tabs--default__link slds-tabs--default__link_mod" role="tab" tabindex="-1" aria-selected="false" aria-haspopup="true" aria-controls="tab-default-2"
                                                             href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">
                                                              <span class="slds-truncate">{$APP.LBL_MORE} {$APP.LBL_INFORMATION}</span>
                                                              <svg class="slds-button__icon slds-button__icon_x-small" aria-hidden="true">
                                                                  <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#chevronup">
                                                                      <svg viewBox="0 0 24 24" id="chevronup" width="100%" height="100%"><path d="M22 8.2l-9.5 9.6c-.3.2-.7.2-1 0L2 8.2c-.2-.3-.2-.7 0-1l1-1c.3-.3.8-.3 1.1 0l7.4 7.5c.3.3.7.3 1 0l7.4-7.5c.3-.2.8-.2 1.1 0l1 1c.2.3.2.7 0 1z"></path></svg>
                                                                  </use>
                                                              </svg>
                                                          </a>
                                                          <div class="slds-dropdown-b slds-dropdown--left">
                                                              <ul class="slds-dropdown__list slds-dropdown--length-5" role="menu">
                                                              {foreach key=_RELATION_ID item=_RELATED_MODULE from=$IS_REL_LIST}
                                                                  <li class="slds-dropdown__item" role="presentation">
                                                                      <a role="menuitem" tabindex="-1" class="drop_down"
                                                                         href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}&selected_header={$_RELATED_MODULE}&relation_id={$_RELATION_ID}#tbl_{$MODULE}_{$_RELATED_MODULE}">
                                                                         {$_RELATED_MODULE|@getTranslatedString:$_RELATED_MODULE}</a>
                                                                  </li>
                                                              {/foreach}
                                                              </ul>
                                                          </div>
                                                      </li>
                                                      {/if}
                                                  </ul>
                                              </div>
                                            </td>
                                            <td class="dvtTabCacheBottom">

                                                <div class="slds-col slds-no-flex slds-grid slds-align-middle actionsContainer pull-right"
                                                     id="detailview_utils_thirdfiller">
                                                    <div class="slds-grid forceActionsContainer">
                                                        {if $EDIT_PERMISSION eq 'yes'}
                                                            <input class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                    {*class="slds-button slds-button--small slds-button_success assideBtn"*}
                                                                   aria-live="assertive" type="button" name="Edit"
                                                                   title="{$APP.LBL_EDIT_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_EDIT_BUTTON_KEY}"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.return_id.value='{$ID}';DetailView.module.value='{$MODULE}';submitFormForAction('DetailView','EditView');"
                                                                   value="&nbsp;{$APP.LBL_EDIT_BUTTON_LABEL}&nbsp;"/>&nbsp;
                                                        {/if}

                                                        {if ((isset($CREATE_PERMISSION) && $CREATE_PERMISSION eq 'permitted') || (isset($EDIT_PERMISSION) && $EDIT_PERMISSION eq 'yes')) && $MODULE neq 'Documents'}
                                                            <input title="{$APP.LBL_DUPLICATE_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_DUPLICATE_BUTTON_KEY}"
                                                                    {*class="slds-button slds-button--small slds-button--brand assideBtn"*}
                                                                   class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='DetailView'; DetailView.isDuplicate.value='true';DetailView.module.value='{$MODULE}'; submitFormForAction('DetailView','EditView');"
                                                                   type="button" name="Duplicate"
                                                                   value="{$APP.LBL_DUPLICATE_BUTTON_LABEL}"/>&nbsp;
                                                        {/if}

                                                        {if $DELETE eq 'permitted'}
                                                            <input title="{$APP.LBL_DELETE_BUTTON_TITLE}"
                                                                   accessKey="{$APP.LBL_DELETE_BUTTON_KEY}"
                                                                    {*class="slds-button slds-button--small slds-button--destructive assideBtn"*}
                                                                   class="slds-button slds-button--neutral not-selected slds-not-selected uiButton"
                                                                   onclick="DetailView.return_module.value='{$MODULE}'; DetailView.return_action.value='index'; {if $MODULE eq 'Accounts'} var confirmMsg = '{$APP.NTC_ACCOUNT_DELETE_CONFIRMATION}' {else} var confirmMsg = '{$APP.NTC_DELETE_CONFIRMATION}' {/if}; submitFormForActionWithConfirmation('DetailView', 'Delete', confirmMsg);"
                                                                   type="button" name="Delete"
                                                                   value="{$APP.LBL_DELETE_BUTTON_LABEL}"/>&nbsp;
                                                        {/if}
                                                    </div> {*/forceActionsContainer*}
                                                </div> {*/detailview_utils_thirdfiller*}
                                            </td>
                                          </tr>


<tr>
                              <td></td>
                              <td class="pull-right">
                                            {if $privrecord neq ''}
                                                <span class="detailview_utils_prev"
                                                      onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$privrecord}&parenttab={$CATEGORY}&start={$privrecordstart}'"
                                                      title="{$APP.LNK_LIST_PREVIOUS}">
                                                    <img align="absmiddle"
                                                         accessKey="{$APP.LNK_LIST_PREVIOUS}"
                                                         name="privrecord"
                                                         value="{$APP.LNK_LIST_PREVIOUS}"
                                                         src="{'rec_prev.gif'|@vtiger_imageurl:$THEME}"
                                                         style="padding-top: 6px;"/>
                                              </span>&nbsp;
                                            {else}
                                                <span class="detailview_utils_prev"
                                                      title="{$APP.LNK_LIST_PREVIOUS}">
                                                    <img align="absmiddle" width="23"
                                                         style="padding-top: 6px;"
                                                         src="{'rec_prev_disabled.gif'|@vtiger_imageurl:$THEME}">
                                              </span>&nbsp;
                                            {/if}

                                            {if $privrecord neq '' || $nextrecord neq ''}
                                                        <span class="detailview_utils_jumpto" id="jumpBtnIdTop"
                                                              onclick="
                                                                      var obj = this;
                                                                      var lhref = getListOfRecords(obj, '{$MODULE}',{$ID},'{$CATEGORY}');"
                                                              title="{$APP.LBL_JUMP_BTN}">
                                                        <img align="absmiddle" title="{$APP.LBL_JUMP_BTN}"
                                                             accessKey="{$APP.LBL_JUMP_BTN}" name="jumpBtnIdTop"
                                                             src="{'rec_jump.gif'|@vtiger_imageurl:$THEME}"
                                                             id="jumpBtnIdTop"/>
                                                    </span>&nbsp;
                                                    {/if}
                                                    
                                              {if $nextrecord neq ''}
                                                  <span class="detailview_utils_next"
                                                        onclick="location.href='index.php?module={$MODULE}&viewtype={if isset($VIEWTYPE)}{$VIEWTYPE}{/if}&action=DetailView&record={$nextrecord}&parenttab={$CATEGORY}&start={$nextrecordstart}'"
                                                        title="{$APP.LNK_LIST_NEXT}">
                                                  <img align="absmiddle"
                                                       accessKey="{$APP.LNK_LIST_NEXT}"
                                                       name="nextrecord"
                                                       src="{'rec_next.gif'|@vtiger_imageurl:$THEME}"
                                                       style="padding-top: 6px;">
                                               </span>&nbsp;
                                              {else}
                                                  <span class="detailview_utils_next" title="{$APP.LNK_LIST_NEXT}">
                                                  <img align="absmiddle" title="{$APP.LNK_LIST_NEXT}"
                                                       width="23" style="padding-top: 6px;"
                                                       src="{'rec_next_disabled.gif'|@vtiger_imageurl:$THEME}"/>
                                                </span>&nbsp;
                                              {/if}</td>
                            </tr>