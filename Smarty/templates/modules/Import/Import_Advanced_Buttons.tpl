{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
<input type="button" name="cancel" value="{'LBL_CANCEL_BUTTON_LABEL'|@getTranslatedString:$MODULE}" class="slds-button slds-button--small slds-button--destructive" onclick="window.history.back()" />
&nbsp;&nbsp;
<input type="submit" name="import" value="{'LBL_IMPORT_BUTTON_LABEL'|@getTranslatedString:$MODULE}" class="slds-button slds-button--small slds-button_success" onclick="return ImportJs.sanitizeAndSubmit();" />