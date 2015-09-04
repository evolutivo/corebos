<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $adb, $log, $current_user;
require_once ('Smarty_setup.php');
require_once ('data/Tracker.php');
require_once ('modules/Quotes/Quotes.php');
require_once ('modules/SalesOrder/SalesOrder.php');
require_once ('modules/Potentials/Potentials.php');
require_once ('include/CustomFieldUtil.php');
require_once ('include/utils/utils.php');

$focus = CRMEntity::getInstance($currentModule);
$smarty = new vtigerCRM_Smarty();

$category = getParentTab($currentModule);
$record = vtlib_purify($_REQUEST['record']);
$isduplicate = vtlib_purify($_REQUEST['isDuplicate']);

//added to fix the issue4600
$searchurl = getBasic_Advance_SearchURL();
$smarty->assign("SEARCH", $searchurl);
//4600 ends

$currencyid = fetchCurrency($current_user->id);
$rate_symbol = getCurrencySymbolandCRate($currencyid);
$rate = $rate_symbol['rate'];
if (isset ($_REQUEST['record']) && $_REQUEST['record'] != '') {
	if (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'quotetoinvoice') {
		$quoteid = $record;
		$quote_focus = new Quotes();
		$quote_focus->id = $quoteid;
		$quote_focus->retrieve_entity_info($quoteid, 'Quotes');
		$focus = getConvertQuoteToInvoice($focus, $quote_focus, $quoteid);

		// Reset the value w.r.t Quote Selected
		$currencyid = $quote_focus->column_fields['currency_id'];
		$rate = $quote_focus->column_fields['conversion_rate'];

		//Added to display the Quote's associated vtiger_products -- when we create vtiger_invoice from Quotes DetailView
		$associated_prod = getAssociatedProducts("Quotes", $quote_focus);
		$txtTax = (($quote_focus->column_fields['txtTax'] != '') ? $quote_focus->column_fields['txtTax'] : '0.000');
		$txtAdj = (($quote_focus->column_fields['txtAdjustment'] != '') ? $quote_focus->column_fields['txtAdjustment'] : '0.000');

		$smarty->assign("CONVERT_MODE", vtlib_purify($_REQUEST['convertmode']));
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("MODE", $quote_focus->mode);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	}
	elseif (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'sotoinvoice') {
		$soid = $_REQUEST['record'];
		$so_focus = new SalesOrder();
		$so_focus->id = $soid;
		$so_focus->retrieve_entity_info($soid, "SalesOrder");
		$focus = getConvertSoToInvoice($focus, $so_focus, $soid);

		// Reset the value w.r.t SalesOrder Selected
		$currencyid = $so_focus->column_fields['currency_id'];
		$rate = $so_focus->column_fields['conversion_rate'];

		//added to set the PO number and terms and conditions
		$focus->column_fields['vtiger_purchaseorder'] = $so_focus->column_fields['vtiger_purchaseorder'];
		$focus->column_fields['terms_conditions'] = $so_focus->column_fields['terms_conditions'];

		//Added to display the SalesOrder's associated vtiger_products -- when we create vtiger_invoice from SO DetailView
		$associated_prod = getAssociatedProducts("SalesOrder", $so_focus);
		$txtTax = (($so_focus->column_fields['txtTax'] != '') ? $so_focus->column_fields['txtTax'] : '0.000');
		$txtAdj = (($so_focus->column_fields['txtAdjustment'] != '') ? $so_focus->column_fields['txtAdjustment'] : '0.000');

		$smarty->assign("CONVERT_MODE", vtlib_purify($_REQUEST['convertmode']));
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("MODE", $so_focus->mode);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');

	}
	elseif (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'update_so_val') {
		//Updating the Selected SO Value in Edit Mode
		foreach ($focus->column_fields as $fieldname => $val) {
			if (isset ($_REQUEST[$fieldname])) {
				$value = $_REQUEST[$fieldname];
				$focus->column_fields[$fieldname] = $value;
			}

		}
		//Handling for dateformat in invoicedate vtiger_field
		if ($focus->column_fields['invoicedate'] != '') {
			$curr_due_date = $focus->column_fields['invoicedate'];
			$focus->column_fields['invoicedate'] = DateTimeField::convertToDBFormat($curr_due_date);
		}

		$soid = $focus->column_fields['salesorder_id'];
		$so_focus = new SalesOrder();
		$so_focus->id = $soid;
		$so_focus->retrieve_entity_info($soid, "SalesOrder");
		$focus = getConvertSoToInvoice($focus, $so_focus, $soid);
		$focus->id = $_REQUEST['record'];
		$focus->mode = 'edit';
		$focus->name = $focus->column_fields['subject'];

		// Reset the value w.r.t SalesOrder Selected
		$currencyid = $so_focus->column_fields['currency_id'];
		$rate = $so_focus->column_fields['conversion_rate'];
	} else {
		$focus->id = $record;
		$focus->mode = 'edit';
		$focus->retrieve_entity_info($record, 'Invoice');
		$focus->name = $focus->column_fields['subject'];
	}
} else {
	if (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'update_so_val') {
		//Updating the Selected SO Value in Create Mode
		foreach ($focus->column_fields as $fieldname => $val) {
			if (isset ($_REQUEST[$fieldname])) {
				$value = $_REQUEST[$fieldname];
				$focus->column_fields[$fieldname] = $value;
			}

		}
		//Handling for dateformat in invoicedate vtiger_field
		if ($focus->column_fields['invoicedate'] != '') {
			$curr_due_date = $focus->column_fields['invoicedate'];
			$focus->column_fields['invoicedate'] = DateTimeField::convertToDBFormat($curr_due_date);
		}

		$soid = $focus->column_fields['salesorder_id'];
		$so_focus = new SalesOrder();
		$so_focus->id = $soid;
		$so_focus->retrieve_entity_info($soid, "SalesOrder");
		$focus = getConvertSoToInvoice($focus, $so_focus, $soid);

		// Reset the value w.r.t SalesOrder Selected
		$currencyid = $so_focus->column_fields['currency_id'];
		$rate = $so_focus->column_fields['conversion_rate'];

		//Added to display the SO's associated products -- when we select SO in New Invoice page
		if (isset ($_REQUEST['salesorder_id']) && $_REQUEST['salesorder_id'] != '') {
			$associated_prod = getAssociatedProducts("SalesOrder", $so_focus, $focus->column_fields['salesorder_id']);
		}

		$smarty->assign("SALESORDER_ID", $focus->column_fields['salesorder_id']);
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("MODE", $so_focus->mode);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');

	}
	elseif (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'potentoinvoice') {
		$focus->mode = '';
		$_REQUEST['opportunity_id'] = $_REQUEST['return_id'];
		$relpot = $adb->query_result($adb->pquery('select related_to from vtiger_potential where potentialid=?',array($_REQUEST['return_id'])),0,0);
		$reltype = getSalesEntityType($relpot);
		if ($reltype=='Accounts') {
			$_REQUEST['account_id'] = $relpot;
		} else {
			$_REQUEST['contact_id'] = $relpot;
		}
	}
}
if($isduplicate == 'true') {
	$smarty->assign('DUPLICATE_FROM', $focus->id);
	$INVOICE_associated_prod = getAssociatedProducts($currentModule, $focus);
	$inventory_cur_info = getInventoryCurrencyInfo($currentModule, $focus->id);
	$currencyid = $inventory_cur_info['currency_id'];
	$focus->id = '';
	$focus->mode = '';
}
$focus->preEditCheck($_REQUEST,$smarty);
if (!empty($_REQUEST['save_error']) and $_REQUEST['save_error'] == "true") {
	if (!empty($_REQUEST['encode_val'])) {
		global $current_user;
		$encode_val = vtlib_purify($_REQUEST['encode_val']);
		$decode_val = base64_decode($encode_val);
		$explode_decode_val = explode('&', trim($decode_val,'&'));
		$tabid = getTabid($currentModule);
		foreach ($explode_decode_val as $fieldvalue) {
			$value = explode("=", $fieldvalue);
			$field_name_val = $value[0];
			$field_value =urldecode($value[1]);
			$finfo = VTCacheUtils::lookupFieldInfo($tabid, $field_name_val);
			if ($finfo !== false) {
				if ($finfo['uitype']=='56') {
					$field_value = $field_value=='on' ? '1' : '0';
				}
				if ($finfo['uitype']=='71' or $finfo['uitype']=='72') {
					$currencyField = new CurrencyField($field_value);
					$field_value = CurrencyField::convertToDBFormat($field_value,$current_user);
				}
				if ($finfo['uitype']=='33' or $finfo['uitype']=='3313') {
					if (is_array($field_value)) {
						$field_value = implode(' |##| ', $field_value);
					}
				}
			}
			$focus->column_fields[$field_name_val] = $field_value;
		}
	}
	$errormessageclass = isset($_REQUEST['error_msgclass']) ? vtlib_purify($_REQUEST['error_msgclass']) : '';
	$errormessage = isset($_REQUEST['error_msg']) ? vtlib_purify($_REQUEST['error_msg']) : '';
	$smarty->assign('ERROR_MESSAGE_CLASS', $errormessageclass);
	$smarty->assign('ERROR_MESSAGE', $errormessage);
} elseif(empty($_REQUEST['record']) && $focus->mode != 'edit'){
	setObjectValuesFromRequest($focus);
}
if (isset ($_REQUEST['opportunity_id']) && $_REQUEST['opportunity_id'] != '') {
	$potfocus = new Potentials();
	$potfocus->column_fields['potential_id'] = $_REQUEST['opportunity_id'];
	$associated_prod = getAssociatedProducts("Potentials", $potfocus, $potfocus->column_fields['potential_id']);
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", count($associated_prod)>0 ? 'true' : 'false');
	$smarty->assign("MODE", $focus->mode);
}
if (isset ($_REQUEST['product_id']) && $_REQUEST['product_id'] != '') {
	$focus->column_fields['product_id'] = $_REQUEST['product_id'];
	$log->debug("Invoice EditView: Product Id from the request is " . $_REQUEST['product_id']);
	$associated_prod = getAssociatedProducts("Products", $focus, $focus->column_fields['product_id']);
	for ($i=1; $i<=count($associated_prod);$i++) {
		$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
		$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Products');
		$associated_prod[$i]['listPrice'.$i] = $associated_prod_prices[$associated_prod_id];
	}
	$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
	$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	$smarty->assign("MODE", $focus->mode);
}
if (!empty ($_REQUEST['parent_id']) && !empty ($_REQUEST['return_module'])) {
	if ($_REQUEST['return_module'] == 'Services') {
		$focus->column_fields['product_id'] = vtlib_purify($_REQUEST['parent_id']);
		$log->debug("Service Id from the request is " . vtlib_purify($_REQUEST['parent_id']));
		$associated_prod = getAssociatedProducts("Services", $focus, $focus->column_fields['product_id']);
		for ($i=1; $i<=count($associated_prod);$i++) {
			$associated_prod_id = $associated_prod[$i]['hdnProductId'.$i];
			$associated_prod_prices = getPricesForProducts($currencyid,array($associated_prod_id),'Services');
			$associated_prod[$i]['listPrice'.$i] = $associated_prod_prices[$associated_prod_id];
		}
		$smarty->assign("ASSOCIATEDPRODUCTS", $associated_prod);
		$smarty->assign("AVAILABLE_PRODUCTS", 'true');
	}
}

if (isset ($_REQUEST['account_id']) && $_REQUEST['account_id'] != '' && ($record == '' || $_REQUEST['convertmode'] == "potentoinvoice") && ($_REQUEST['convertmode'] != 'update_so_val')) {
	require_once ('modules/Accounts/Accounts.php');
	$acct_focus = new Accounts();
	$acct_focus->retrieve_entity_info($_REQUEST['account_id'], "Accounts");
	$focus->column_fields['bill_city'] = $acct_focus->column_fields['bill_city'];
	$focus->column_fields['ship_city'] = $acct_focus->column_fields['ship_city'];
	$focus->column_fields['bill_street'] = $acct_focus->column_fields['bill_street'];
	$focus->column_fields['ship_street'] = $acct_focus->column_fields['ship_street'];
	$focus->column_fields['bill_state'] = $acct_focus->column_fields['bill_state'];
	$focus->column_fields['ship_state'] = $acct_focus->column_fields['ship_state'];
	$focus->column_fields['bill_code'] = $acct_focus->column_fields['bill_code'];
	$focus->column_fields['ship_code'] = $acct_focus->column_fields['ship_code'];
	$focus->column_fields['bill_country'] = $acct_focus->column_fields['bill_country'];
	$focus->column_fields['ship_country'] = $acct_focus->column_fields['ship_country'];
	$focus->column_fields['bill_pobox'] = $acct_focus->column_fields['bill_pobox'];
	$focus->column_fields['ship_pobox'] = $acct_focus->column_fields['ship_pobox'];
}
if (isset ($_REQUEST['contact_id']) && $_REQUEST['contact_id'] != '' && ($_REQUEST['record'] == '' || $_REQUEST['convertmode'] == "potentoinvoice")) {
	require_once ('modules/Contacts/Contacts.php');
	$cto_focus = new Contacts();
	$cto_focus->retrieve_entity_info($_REQUEST['contact_id'], "Contacts");
	$focus->column_fields['bill_city'] = $cto_focus->column_fields['mailingcity'];
	$focus->column_fields['ship_city'] = $cto_focus->column_fields['othercity'];
	$focus->column_fields['bill_street'] = $cto_focus->column_fields['mailingstreet'];
	$focus->column_fields['ship_street'] = $cto_focus->column_fields['otherstreet'];
	$focus->column_fields['bill_state'] = $cto_focus->column_fields['mailingstate'];
	$focus->column_fields['ship_state'] = $cto_focus->column_fields['otherstate'];
	$focus->column_fields['bill_code'] = $cto_focus->column_fields['mailingcode'];
	$focus->column_fields['ship_code'] = $cto_focus->column_fields['othercode'];
	$focus->column_fields['bill_country'] = $cto_focus->column_fields['mailingcountry'];
	$focus->column_fields['ship_country'] = $cto_focus->column_fields['othercountry'];
	$focus->column_fields['bill_pobox'] = $cto_focus->column_fields['mailingpobox'];
	$focus->column_fields['ship_pobox'] = $cto_focus->column_fields['otherpobox'];
}

$disp_view = getView($focus->mode);
$smarty->assign('BLOCKS', getBlocks($currentModule, $disp_view, $focus->mode, $focus->column_fields));
$smarty->assign('BASBLOCKS', getBlocks($currentModule, $disp_view, $focus->mode, $focus->column_fields, 'BAS'));
$smarty->assign('ADVBLOCKS',getBlocks($currentModule,$disp_view,$focus->mode,$focus->column_fields,'ADV'));
$smarty->assign('OP_MODE',$disp_view);
$smarty->assign('APP', $app_strings);
$smarty->assign('MOD', $mod_strings);
$smarty->assign('MODULE', $currentModule);
// TODO: Update Single Module Instance name here.
$smarty->assign('SINGLE_MOD', 'SINGLE_'.$currentModule);
$smarty->assign('CATEGORY', $category);
$smarty->assign("THEME", $theme);
$smarty->assign('IMAGE_PATH', "themes/$theme/images/");
$smarty->assign('ID', $focus->id);
$smarty->assign('MODE', $focus->mode);
$smarty->assign('CREATEMODE', isset($_REQUEST['createmode']) ? vtlib_purify($_REQUEST['createmode']) : '');

$smarty->assign('CHECK', Button_Check($currentModule));
$smarty->assign('DUPLICATE', $isduplicate);

if($focus->mode == 'edit' || $isduplicate) {
	$recordName = array_values(getEntityName($currentModule, $record));
	$recordName = $recordName[0];
	$smarty->assign('NAME', $recordName);
	$smarty->assign('UPDATEINFO',updateInfo($record));
}

if (isset ($_REQUEST['convertmode']) && $_REQUEST['convertmode'] == 'quotetoinvoice') {
	$smarty->assign("MODE", $quote_focus->mode);
	$se_array = getProductDetailsBlockInfo($quote_focus->mode, "Quotes", $quote_focus);
}
elseif (isset ($_REQUEST['convertmode']) && ($_REQUEST['convertmode'] == 'sotoinvoice' || $_REQUEST['convertmode'] == 'update_so_val')) {
	$smarty->assign('MODE', $focus->mode);
	$se_array = getProductDetailsBlockInfo($focus->mode, "SalesOrder", $so_focus);

	$txtTax = (($so_focus->column_fields['txtTax'] != '') ? $so_focus->column_fields['txtTax'] : '0.000');
	$txtAdj = (($so_focus->column_fields['txtAdjustment'] != '') ? $so_focus->column_fields['txtAdjustment'] : '0.000');

	$associated_prod = getAssociatedProducts('SalesOrder', $so_focus);
	$smarty->assign('ASSOCIATEDPRODUCTS', $associated_prod);
	$smarty->assign('MODE', $focus->mode);
}
elseif ($focus->mode == 'edit') {
	$smarty->assign('UPDATEINFO', updateInfo($focus->id));
	$associated_prod = getAssociatedProducts('Invoice', $focus);
	$smarty->assign('ASSOCIATEDPRODUCTS', $associated_prod);
	$smarty->assign('MODE', $focus->mode);
}
elseif (isset ($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$associated_prod = $INVOICE_associated_prod;
	$smarty->assign('AVAILABLE_PRODUCTS', 'true');
	$smarty->assign('MODE', $focus->mode);
}

$smarty->assign('ASSOCIATEDPRODUCTS', $associated_prod);

if (isset ($_REQUEST['return_module']))
	$smarty->assign("RETURN_MODULE", vtlib_purify($_REQUEST['return_module']));
else
	$smarty->assign("RETURN_MODULE", "Invoice");
if (isset ($_REQUEST['return_action']))
	$smarty->assign("RETURN_ACTION", vtlib_purify($_REQUEST['return_action']));
else
	$smarty->assign("RETURN_ACTION", "index");
if (isset ($_REQUEST['return_id']))
	$smarty->assign("RETURN_ID", vtlib_purify($_REQUEST['return_id']));
if (isset ($_REQUEST['return_viewname']))
	$smarty->assign("RETURN_VIEWNAME", vtlib_purify($_REQUEST['return_viewname']));
$smarty->assign("UPLOADSIZE", $upload_maxsize/1000000); //Convert to MB
$smarty->assign("UPLOAD_MAXSIZE",$upload_maxsize);

// Field Validation Information
$tabid = getTabid($currentModule);
$validationData = getDBValidationData($focus->tab_name,$tabid);
$validationArray = split_validationdataArray($validationData);

$smarty->assign("VALIDATION_DATA_FIELDNAME",$validationArray['fieldname']);
$smarty->assign("VALIDATION_DATA_FIELDDATATYPE",$validationArray['datatype']);
$smarty->assign("VALIDATION_DATA_FIELDLABEL",$validationArray['fieldlabel']);

// In case you have a date field
$smarty->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$smarty->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

// Module Sequence Numbering
$mod_seq_field = getModuleSequenceField($currentModule);
if($focus->mode != 'edit' && $mod_seq_field != null) {
	$autostr = getTranslatedString('MSG_AUTO_GEN_ON_SAVE');
	$mod_seq_string = $adb->pquery("SELECT prefix, cur_id from vtiger_modentity_num where semodule = ? and active=1",array($currentModule));
	$mod_seq_prefix = $adb->query_result($mod_seq_string,0,'prefix');
	$mod_seq_no = $adb->query_result($mod_seq_string,0,'cur_id');
	if ($adb->num_rows($mod_seq_string) == 0 || $focus->checkModuleSeqNumber($focus->table_name, $mod_seq_field['column'], $mod_seq_prefix.$mod_seq_no)) {
		echo '<br><font color="#FF0000"><b>'. getTranslatedString('LBL_DUPLICATE'). ' '. getTranslatedString($mod_seq_field['label'])
			.' - '. getTranslatedString('LBL_CLICK') .' <a href="index.php?module=Settings&action=CustomModEntityNo&parenttab=Settings&selmodule='.$currentModule.'">'.getTranslatedString('LBL_HERE').'</a> '
			. getTranslatedString('LBL_TO_CONFIGURE'). ' '. getTranslatedString($mod_seq_field['label']) .'</b></font>';
	} else {
		$smarty->assign("MOD_SEQ_ID",$autostr);
	}
} else {
	$smarty->assign("MOD_SEQ_ID", $focus->column_fields[$mod_seq_field['name']]);
}

//if create Invoice, get all available product taxes and shipping & Handling taxes
if ($focus->mode != 'edit') {
	$tax_details = getAllTaxes('available');
	$sh_tax_details = getAllTaxes('available', 'sh');
} else {
	$tax_details = getAllTaxes('available', '', $focus->mode, $focus->id);
	$sh_tax_details = getAllTaxes('available', 'sh', 'edit', $focus->id);
}
$smarty->assign('GROUP_TAXES', $tax_details);
$smarty->assign('SH_TAXES', $sh_tax_details);

$smarty->assign("CURRENCIES_LIST", getAllCurrencies());
if ($focus->mode == 'edit') {
	$inventory_cur_info = getInventoryCurrencyInfo('Invoice', $focus->id);
	$smarty->assign("INV_CURRENCY_ID", $inventory_cur_info['currency_id']);
} else {
	$smarty->assign("INV_CURRENCY_ID", $currencyid);
}

$smarty->assign('CREATEMODE', vtlib_purify($_REQUEST['createmode']));

// Gather the help information associated with fields
$smarty->assign('FIELDHELPINFO', vtlib_getFieldHelpInfo($currentModule));

$picklistDependencyDatasource = Vtiger_DependencyPicklist::getPicklistDependencyDatasource($currentModule);
$smarty->assign("PICKIST_DEPENDENCY_DATASOURCE", Zend_Json::encode($picklistDependencyDatasource));

//Get Service or Product by default when create
$smarty->assign('PRODUCT_OR_SERVICE', GlobalVariable::getVariable('product_service_default', 'Products', $currentModule, $current_user->id));
//Set taxt type group or individual by default when create
$smarty->assign('TAX_TYPE', GlobalVariable::getVariable('Tax_Type_Default', 'individual', $currentModule, $current_user->id));
//Show or not the Header to copy address to left or right
$smarty->assign('SHOW_COPY_ADDRESS', GlobalVariable::getVariable('Show_Copy_Adress_Header', 'yes', $currentModule, $current_user->id));

if ($focus->mode == 'edit')
	$smarty->display('Inventory/InventoryEditView.tpl');
else
	$smarty->display('Inventory/InventoryCreateView.tpl');
?>