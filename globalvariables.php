<?php

$patch_version = '';
$modified_database = '';
// Create connection
$con=mysql_connect("localhost","root","root") or die("Unable to connect to MySQL");
$selected = mysql_select_db("corebos550",$con) 
  or die("Could not select examples");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$globalvar=mysql_query("select * from vtiger_globalsettings");
mysql_num_rows($globalvar);
while($row=mysql_fetch_array($globalvar))
{
$vtiger_current_version = $row['vtiger_current_version'];
$_SESSION['vtiger_version'] = $vtiger_current_version;
$coreBOS_app_version = $row['corebos_app_version'];
$coreBOS_app_name = $row['corebos_app_name'];
$coreBOS_app_url = $row['corebos_app_url'];
$CALENDAR_DISPLAY = $row['calendar_display'];
$WORLD_CLOCK_DISPLAY =  $row['world_clock_display'];
$CALCULATOR_DISPLAY = $row['calculator_display'];
$HELPDESK_SUPPORT_EMAIL_ID = $row['helpdesk_support_email_id'];
$HELPDESK_SUPPORT_NAME = $row['helpdesk_support_name'];
$HELPDESK_SUPPORT_EMAIL_REPLY_ID = $HELPDESK_SUPPORT_EMAIL_ID;
$root_directory = $row['root_directory'];
$site_URL = $row['site_url'];
$cache_dir = $row['cache_dir'];
$tmp_dir = $row['tmp_dir'];
$import_dir = $row['import_dir'];
$upload_dir = $row['upload_dir'];
$upload_maxsize = $row['upload_maxsize'];
$allow_exports = $row['allow_exports'];
$upload_badext = $row['upload_badext'];
$list_max_entries_per_page = $row['list_max_entries_per_page'];
$limitpage_navigation = $row['limitpage_navigation'];
$history_max_viewed = $row['history_max_viewed'];
$default_module = $row['default_module'];
$default_action = $row['default_action'];
$default_theme = $row['default_theme'];
$currency_name = $row['currency_name'];
$default_charset = $row['default_charset'];
$default_charset = strtoupper($default_charset);
$listview_max_textlength = $row['listview_max_textlength'];
$php_max_execution_time = $row['php_max_execution_time'];
$default_timezone = $row['default_timezone'];
if(isset($default_timezone) && function_exists('date_default_timezone_set')) {
	@date_default_timezone_set($default_timezone);
}
$MINIMUM_CRON_FREQUENCY = $row['minimum_cron_frequency'];
$default_language = $row['default_language'];
$maxWebServiceSessionIdleTime = $row['maxwebservicesessionidletime'];
$maxWebServiceSessionLifeSpan = $row['maxwebservicesessionlifespan'];

}
?>
