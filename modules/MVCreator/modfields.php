<?php

function getModFields($module, $dbname)
{
    global $log;
    $log->debug("Entering getAdvSearchfields(".$module.") method ...");
    global $adb;
    global $current_user;
    global $mod_strings,$app_strings;
    $OPTION_SET.= '<optgroup label="'.$module.'">';
    $tabid = getTabid($module,$dbname);
    if($tabid==9)
        $tabid="9,16";


    $sql = "select * from  vtiger_field ";
    $sql.= " where vtiger_field.tabid in(?) and";
    $sql.= " vtiger_field.displaytype in (1,2,3) and vtiger_field.presence in (0,2)";
    if($tabid == 13 || $tabid == 15)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Add Comment'";
    }
    if($tabid == 14)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Product Image'";
    }
    if($tabid == 9 || $tabid==16)
    {
        $sql.= " and vtiger_field.fieldname not in('notime','duration_minutes','duration_hours')";
    }
    if($tabid == 4)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Contact Image'";
    }
    if($tabid == 13 || $tabid == 10)
    {
        $sql.= " and vtiger_field.fieldlabel != 'Attachment'";
    }
    $sql.= " group by vtiger_field.fieldlabel order by block,sequence";

    $params = array($tabid);


    $result = $adb->pquery($sql, $params);
    $noofrows = $adb->num_rows($result);
    $block = '';
    $select_flag = '';
    //echo "edmondi 2";
    for($i=0; $i<$noofrows; $i++)
    {
        $fieldtablename = $adb->query_result($result,$i,"tablename");
        $fieldcolname = $adb->query_result($result,$i,"columnname");
        $fieldname = $adb->query_result($result,$i,"fieldname");
        $block = $adb->query_result($result,$i,"block");
        $fieldtype = $adb->query_result($result,$i,"typeofdata");
        $fieldtype = explode("~",$fieldtype);
        $fieldtypeofdata = $fieldtype[0];
        if($fieldcolname == 'account_id' || $fieldcolname == 'accountid' || $fieldcolname == 'product_id' || $fieldcolname == 'vendor_id' || $fieldcolname == 'contact_id' || $fieldcolname == 'contactid' || $fieldcolname == 'vendorid' || $fieldcolname == 'potentialid' || $fieldcolname == 'salesorderid' || $fieldcolname == 'quoteid' || $fieldcolname == 'parentid' || $fieldcolname == "recurringtype" || $fieldcolname == "campaignid" || $fieldcolname == "inventorymanager" ||  $fieldcolname == "currency_id")
            $fieldtypeofdata = "V";
        if($fieldcolname == "discontinued" || $fieldcolname == "active")
            $fieldtypeofdata = "C";
        $fieldlabel = $mod_strings[$adb->query_result($result,$i,"fieldlabel")];

        // Added to display customfield label in search options
        if($fieldlabel == "")
            $fieldlabel = $adb->query_result($result,$i,"fieldlabel");

        if($fieldlabel == "Related To")
        {
            $fieldlabel = "Related to";
        }
        if($fieldlabel == "Start Date & Time")
        {
            $fieldlabel = "Start Date";
            if($module == 'Activities' && $block == 19)
                $module_columnlist['vtiger_activity:time_start::Activities_Start Time:I'] = 'Start Time';

        }
        //$fieldlabel1 = str_replace(" ","_",$fieldlabel); // Is not used anywhere
        //Check added to search the lists by Inventory manager
        if($fieldtablename == 'vtiger_quotes' && $fieldcolname == 'inventorymanager')
        {
            $fieldtablename = 'vtiger_usersQuotes';
            $fieldcolname = 'user_name';
        }
        if($fieldtablename == 'vtiger_contactdetails' && $fieldcolname == 'reportsto')
        {
            $fieldtablename = 'vtiger_contactdetails2';
            $fieldcolname = 'lastname';
        }
        if($fieldtablename == 'vtiger_notes' && $fieldcolname == 'folderid'){
            $fieldtablename = 'vtiger_attachmentsfolder';
            $fieldcolname = 'foldername';
        }
        if($fieldlabel != 'Related to')
        {
            if ($i==0)
                $select_flag = "";

            $mod_fieldlabel = $mod_strings[$fieldlabel];
            if($mod_fieldlabel =="") $mod_fieldlabel = $fieldlabel;

            if($fieldlabel == "Product Code") {

                $OPTION_SET .= "<option value='" . $fieldtablename . ":" . $fieldcolname . ":" . $fieldname . "::" . $fieldtypeofdata . "'" . $select_flag . ">" . $mod_fieldlabel . "</option>";


            }
            if($fieldlabel == "Reports To"){

                $OPTION_SET .= "<option value='".$fieldtablename.":".$fieldcolname.":".$fieldname."::".$fieldtypeofdata."'".$select_flag.">".$mod_fieldlabel." - ".$mod_strings['LBL_LIST_LAST_NAME']."</option>";

            }

            elseif($fieldcolname == "contactid" || $fieldcolname == "contact_id")
            {

                $OPTION_SET .= "<option value='vtiger_contactdetails:lastname:".$fieldname."::".$fieldtypeofdata."' ".$select_flag.">".$app_strings['LBL_CONTACT_LAST_NAME']."</option>";
                $OPTION_SET .= "<option value='vtiger_contactdetails:firstname:".$fieldname."::".$fieldtypeofdata."'>".$app_strings['LBL_CONTACT_FIRST_NAME']."</option>";



            }
            elseif($fieldcolname == "campaignid")
                $OPTION_SET .= "<option value='vtiger_campaign:campaignname:".$fieldname."::".$fieldtypeofdata."' ".$select_flag.">".$mod_fieldlabel."</option>";
            else
                $OPTION_SET .= "<option value='".$fieldtablename.":".$fieldcolname.":".$fieldname."::".$fieldtypeofdata."' ".$select_flag.">".str_replace("'","`",$fieldlabel)."</option>";
        }
    }
    //Added to include Ticket ID in HelpDesk advance search
    if($module == 'HelpDesk')
    {
        $mod_fieldlabel = $mod_strings['Ticket ID'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Ticket ID';

        $OPTION_SET .= "<option value=\'vtiger_crmentity:crmid:".$fieldname."::".$fieldtypeofdata."\'>".$mod_fieldlabel."</option>";
    }
    //Added to include activity type in activity advance search
    if($module == 'Activities')
    {
        $mod_fieldlabel = $mod_strings['Activity Type'];
        if($mod_fieldlabel =="") $mod_fieldlabel = 'Activity Type';

        $OPTION_SET .= "<option value=\'vtiger_activity.activitytype:".$fieldname."::".$fieldtypeofdata."\'>".$mod_fieldlabel."</option>";
    }
    $log->debug("Exiting getAdvSearchfields method ...");
    $OPTION_SET.="</optgroup>";
    return $OPTION_SET;
}
