<?php
/*************************************************************************************************
 * Copyright 2016 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
 * Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
 * file except in compliance with the License. You can redistribute it and/or modify it
 * under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
 * granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
 * the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
 * applicable law or agreed to in writing, software distributed under the License is
 * distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing
 * permissions and limitations under the License. You may obtain a copy of the License
 * at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
 *************************************************************************************************
 *  Module       : Global Variable Definitions
 *  Version      : 1.0
 *  Author       : JPL TSolucio, S. L.

 * Definition Template *
'variable name' => array(
	'status' => 'Not Implemented' | 'Implemented' | 'Deprecated',
	'valuetype' => 'String' | 'Boolean' | 'Integer' | 'Float' | 'CSV',
	'category' => 'System' | 'User Interface' | 'Performance' | 'Module Functionality' | 'Security' | 'Other',
	'values' => 'list of possible values',
	'definition' => 'explanation and purpose',
),

 *************************************************************************************************/
$GlobalVariableDefinitonsHeader = array(
	'valuetype' => 'Value Type',
	'category' => 'Category',
	'values' => 'Values',
	'definition' => 'Definition',
);
$GlobalVariableDefinitons = array(
'Application_AdminLoginIPs' => array(
	'status' => 'Implemented',
	'valuetype' => 'CSV IPs',
	'category' => 'Security',
	'values' => 'Example: 127.0.0.1,192.168.0.100',
	'definition' => 'Comma separated list of IP addresses from which admin users will be permitted to login',
),
'Debug_Record_Not_Found' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Debug',
	'values' => '0 | 1',
	'definition' => 'When a record cannot be found, the application will show a message indicating the event. If this variable is set to 1 then the application will show additional information about the record being looked for like the module and crmid.',
),
'Debug_Report_Query' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Debug',
	'values' => '0 | 1',
	'definition' => 'If set to 1 the SQL query being launched for a report will be shown on screen.',
),
'Debug_ListView_Query' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Debug',
	'values' => '0 | 1',
	'definition' => 'If set to 1 the SQL query being launched to retrieve records in a List View will be shown on screen.',
),
'Debug_Popup_Query' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Debug',
	'values' => '0 | 1',
	'definition' => 'If set to 1 the SQL query being launched to retrieve records in a Popup View will be shown on screen.',
),
'Accounts_BlockDuplicateName' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Module Functionality',
	'values' => '0 | 1',
	'definition' => 'If set to 1, which is the default value, you will not be able to duplicate account names, if set to 0 that will be permitted.',
),
'ModComments_DefaultBlockStatus' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Module Functionality',
	'values' => '0 | 1',
	'definition' => 'If set to 1, which is the default value, the Comments block will be open, if set to 0 it will be closed.',
),
'ModComments_DefaultCriteria' => array(
	'status' => 'Implemented',
	'valuetype' => 'String',
	'category' => 'Module Functionality',
	'values' => 'All | Last5 | Mine',
	'definition' => '"All" (default value) will show all comments related to the module, "Last5" will show only the last 5 commentas and "Mine" will show all comments of the current user.',
),
'Application_TrackerMaxHistory' => array(
	'status' => 'Implemented',
	'valuetype' => 'Integer',
	'category' => 'Application Functionality',
	'values' => '',
	'definition' => 'Máximum number of elements in the Recent Viewed elements popup. The default value is 10.',
),
'EMail_OpenTrackingEnabled' => array(
	'status' => 'Implemented',
	'valuetype' => 'Boolean',
	'category' => 'Module Functionality',
	'values' => '0 | 1',
	'definition' => 'If emails will have a hidden image to track openings. The default is enabled (1). <a href="http://corebos.org/documentation/doku.php?id=en:email_tracking" target="_blank">Documentation</a>',
),
'ToolTip_MaxFieldValueLength' => array(
	'status' => 'Implemented',
	'valuetype' => 'Integer',
	'category' => 'Module Functionality',
	'values' => '',
	'definition' => 'Maximum number of characters of a value to show in the tooltip. The default value is 35. This variable can be set per module.',
),
'Debug_Send_VtigerCron_Error' => array(
	'status' => 'Implemented',
	'valuetype' => 'CSV EMail',
	'category' => 'Debug',
	'values' => '',
	'definition' => '',
),
'Debug_Send_AdminLoginIPAuth_Error' => array(
	'status' => 'Implemented',
	'valuetype' => 'CSV EMail',
	'category' => 'Debug',
	'values' => '',
	'definition' => '',
),
'Application_Announcement' => array(
	'status' => 'Implemented',
	'valuetype' => 'String',
	'category' => 'Application',
	'values' => 'Text to show',
	'definition' => 'Shows a scrolling header text as a system wide announcement',
),
'Application_Global_Search_SelectedModules' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_Storage_Directory' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_Storage_SaveStrategy' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_Global_Search_Binary' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_OpenRecordInNewXOnRelatedList' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_OpenRecordInNewXOnListView' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_MaxFailedLoginAttempts' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_ExpirePasswordAfterDays' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_ListView_MaxColumns' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_Action_Panel_Open' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Application_Search_Panel_Open' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Calendar_Modules_Panel_Visible' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Calendar_Default_Reminder_Minutes' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Calendar_Slot_Minutes' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Calendar_Show_Inactive_Users' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Calendar_Show_Group_Events' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'calendar_call_default_duration' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'calendar_other_default_duration' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'calendar_sort_users_by' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'CronTasks_cronWatcher_mailto' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'BusinessMapping_SalesOrder2Invoice' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'BusinessMapping_PotentialOnCampaignRelation' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Webservice_showUserAdvancedBlock' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Users_ReplyTo_SecondEmail' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Users_Default_Send_Email_Template' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Campaign_CreatePotentialOnAccountRelation' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Campaign_CreatePotentialOnContactRelation' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'GoogleCalendarSync_BaseUpdateMonths' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'GoogleCalendarSync_BaseCreateMonths' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Import_Full_CSV' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Lead_Convert_TransferToAccount' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Copy_Bundle_OnDuplicate' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Show_Subproducts_Popup' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Permit_Relate_Bundle_Parent' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Permit_Subproduct_Be_Parent' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Maximum_Number_Images' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Workflow_Send_Email_ToCCBCC' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Workflow_GeoDistance_Country_Default' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Report_Send_Scheduled_ifEmpty' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Maximum_Scheduled_Workflows' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Billing_Address_Checked' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Shipping_Address_Checked' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Show_Copy_Adress_Header' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Tax_Type_Default' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'product_service_default' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Product_Default_Units' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Service_Default_Units' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'SalesOrderStatusOnInvoiceSave' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'QuoteStatusOnSalesOrderSave' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'Report.Excel.Export.RowHeight' => array(
	'status' => 'Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'calendar_display' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'world_clock_display' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'calculator_display' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'list_max_entries_per_page' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'limitpage_navigation' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'history_max_viewed' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'default_module' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'default_action' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'default_theme' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'currency_name' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'listview_max_textlength' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'helpdesk_support_email_id' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'helpdesk_support_name' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'helpdesk_support_email_reply_id' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'upload_badext' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'maxWebServiceSessionLifeSpan' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'maxWebServiceSessionIdleTime' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'upload_maxsize' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'allow_exports' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'minimum_cron_frequency' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'default_timezone' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'default_language' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'import_dir' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'upload_dir' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'corebos_app_name' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'corebos_app_url' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'first_day_of_week' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
'preload_jscalendar' => array(
	'status' => 'Not Implemented',
	'valuetype' => '',
	'category' => '',
	'values' => '',
	'definition' => '',
),
);