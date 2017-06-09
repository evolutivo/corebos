<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
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
*************************************************************************************************/
include 'XmlContent.php';
// $rec = $_REQUEST['installationID'];
// $ai=$adb->query("select * from vtiger_accountinstallation join vtiger_crmentity on crmid=accountinstallationid
//                 join vtiger_account on accountid=linktoacsd where accountinstallationid=$rec");
// $dbname=$adb->query_result($ai,0,"dbname");
//$acno=$adb->query_result($ai,0,"acin_no");
$FirstmoduleXML ="";
if (isset( $_REQUEST['MapId'])) {
    $mapid =  $_REQUEST['MapId'];
    $FirstmoduleXML = takeSecondMOduleFromXMLMap($mapid);
}


//echo $FirstmoduleXML;
//exit();
//

global $log, $mod_strings;
$m = $_REQUEST['mod'];
//require_once('include/utils/utils.php');
//require_once('Smarty_setup.php');
//require_once('include/database/PearDatabase.php');
//require_once('database/DatabaseConnection.php');
//require_once ('include/CustomFieldUtil.php');
//require_once ('data/Tracker.php');
//require_once('include/utils/utils.php');
//require_once('Smarty_setup.php');
//require_once('include/database/PearDatabase.php');
//require_once('database/DatabaseConnection.php');
//require_once('include/CustomFieldUtil.php');
//require_once('data/Tracker.php');
//echo "";
//exit();

$rec = $_REQUEST['installationID'];

$j = 0;
$result = $adb->pquery("SELECT relmodule,columnname,fieldlabel 
        from vtiger_fieldmodulerel join vtiger_field 
        on vtiger_field.fieldid=vtiger_fieldmodulerel.fieldid 
        where module= ? and relmodule<>'Faq' and relmodule<>'Emails' and relmodule<>'Events'
        and relmodule<>'Webmails' and relmodule<>'SMSNotifier'
        and relmodule<>'PBXManager' and relmodule<>'Modcomments' and relmodule<>'Calendar' 
        and relmodule in (select name from vtiger_tab where presence=0)", array($m));

$num_rows = $adb->num_rows($result);
if ($num_rows != 0) {
    for ($i = 1; $i <= $num_rows; $i++) {

        $modul1 = $adb->query_result($result, $i - 1, 'relmodule');
        $log->debug("Fillim$i" . $modul1);
        $column = $adb->query_result($result, $i - 1, 'columnname');
        $fl = $adb->query_result($result, $i - 1, 'fieldlabel');
        if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
            $a .= '<option selected value="' . $modul1 . ';' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
        } else {
            $a .= '<option value="' . $modul1 . ';' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
        }
       // echo $modul1;
    }
}
$query1 = "SELECT  module, columnname, fieldlabel from  vtiger_fieldmodulerel 
         join  vtiger_field on  vtiger_field.fieldid= vtiger_fieldmodulerel.fieldid
         where relmodule='$m' and module<>'Faq' and module<>'Emails' and module<>'Events' and module<>'Webmails' and module<>'SMSNotifier'
         and module<>'PBXManager' and module<>'Modcomments' and module<>'Calendar' 
         and relmodule in (select name from  vtiger_tab where presence=0) 
         and module in (select name from  vtiger_tab where presence=0)";


$result1 = $adb->query($query1);
$num_rows1 = $adb->num_rows($result1);
if ($num_rows1 != 0) {
    for ($i = 1; $i <= $num_rows1; $i++) {
        $modul1 = $adb->query_result($result1, $i - 1, 'module');
        $column = $adb->query_result($result1, $i - 1, 'columnname');
        $fl = $adb->query_result($result1, $i - 1, 'fieldlabel');
        if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
            $a .= '<option selected value="' . $modul1 . '(many);' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
        } else {
            $a .= '<option value="' . $modul1 . '(many);' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
        }
    }
}
$query2 = "SELECT uitype, columnname, fieldlabel from  vtiger_field 
         join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid 
         where (uitype=76 or uitype=50 or uitype=51 or uitype=57 or uitype=58 or uitype=59 or uitype=73 or uitype=75 or  uitype=78
         or  uitype=80 or uitype=81 or uitype=68) and name='$m' and  vtiger_tab.presence=0";

$result2 = $adb->query($query2);
$num_rows2 = $adb->num_rows($result2);
if ($num_rows2 != 0) {
    for ($i = 1; $i <= $num_rows2; $i++) {
        $ui = $adb->query_result($result2, $i - 1, 'uitype');
        $column = $adb->query_result($result2, $i - 1, 'columnname');
        $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');

        if ($ui == 51 || $ui == 50 || $ui == 73 || $ui == 68) {
            $modul1 = "Accounts";
            if ($ui == 68) $modul2 = 'Contacts';
        } else if ($ui == 57) {
            $modul1 = "Contacts";
            $modul2 = '';
        } else if ($ui == 59) {
            $modul1 = "Products";
            $modul2 = '';

        } else if ($ui == 58) {
            $modul1 = "Campaigns";
            $modul2 = '';

        } else if ($ui == 76) {
            $modul1 = "Potentials";
            $modul2 = '';

        } else if ($ui == 75 || $ui = 81) {
            $modul1 = "Vendors";
            $modul2 = '';
        } else if ($ui == 78) {
            $modul1 = "Quotes";
            $modul2 = '';
        } else if ($ui == 80) {
            $modul1 = "SalesOrder";
            $modul2 = '';
        }
        $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");
        if ($modul2 != '') {
            $mo2 = $adb->query("select * from  vtiger_tab where name='$modul2' and presence=0");
        }
        if ($adb->num_rows($mo) != 0) {
            if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                $a .= '<option selected value="' . $modul1 . '; ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
            } else {
                $a .= '<option value="' . $modul1 . '; ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
            }
        }
        if ($modul2 != '' && $adb->num_rows($mo2) != 0)
            if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul2) {
                $a .= '<option selected value="' . $modul2 . '; ' . $column . '">' . str_replace("'", "", getTranslatedString($modul2)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul2)) . '</option>';
            }
            else {
                $a .= '<option value="' . $modul2 . '; ' . $column . '">' . str_replace("'", "", getTranslatedString($modul2)) . ' ' . str_replace("'", "", getTranslatedString($fl, $modul2)) . '</option>';
            }
        }

}

if ($m == "Accounts") {
    $query2 = "SELECT name, columnname, fieldlabel from  vtiger_field
            join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where (uitype=73 or uitype=50
            or uitype=51 or uitype=68) and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events'
            and name<>'Webmails' and name<>'SMSNotifier' and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' 
            and  vtiger_tab.presence=0";

    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");
            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
            }
    }
}
if ($m == "Contacts") {
    $query2 = "SELECT name, columnname, fieldlabel from  vtiger_field 
              join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid 
              where (uitype=57 or uitype=68) and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events' 
              and name<>'Webmails' and name<>'SMSNotifier'and name<>'PBXManager' and name<>'Modcomments' 
              and name<>'Calendar' and  vtiger_tab.presence=0";

    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");
            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
            }
    }
}
if ($m == "Produts") {
    $query2 = "SELECT columnname,name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where  uitype=59 and name<>'$m'
    and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

";


    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select *  from vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ')' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }

        }
    }
}
if ($m == "Campaigns") {
    $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where  uitype=58 and name<>'$m'
    and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0
";


    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
        }
    }
}
if ($m == "Potentials") {
    $query2 = "SELECT columnname, name ,fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=76 and name<>'$m'
    and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and   vtiger_tab.presence=0

";


    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
        }
    }
}
if ($m == "Quotes") {
    $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=78
    and name<>'$m' and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

";

    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0)
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else {
                    $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }

        }
    }
}
if ($m == "SalesOrder") {
    $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=81 and uitype=75 and name<>'$m'
    and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

";


    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $$fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0)
                $a .= '<option value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . ') ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';


        }
    }
}
if ($m == "Vendors") {
    $query2 = "SELECT columnname, name, fieldlabel from  vtiger_field join  vtiger_tab on  vtiger_tab.tabid= vtiger_field.tabid where uitype=80 and name<>'$m'
    and name<>'Faq' and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
    and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and  vtiger_tab.presence=0

";


    $result2 = $adb->query($query2);
    $num_rows2 = $adb->num_rows($result2);
    if ($num_rows2 != 0) {
        for ($i = 1; $i <= $num_rows2; $i++) {
            $modul1 = $adb->query_result($result2, $i - 1, 'name');
            $column = $adb->query_result($result2, $i - 1, 'columnname');
            $fl = $adb->query_result($result2, $i - 1, 'fieldlabel');
            $mo = $adb->query("select * from  vtiger_tab where name='$modul1' and presence=0");

            if ($adb->num_rows($mo) != 0) {
                if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
                    $a .= '<option selected="selected" value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . '); ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
                else{
                    $a .= '<option  value="' . $modul1 . '(many); ' . $column . '">' . str_replace("'", "", getTranslatedString($modul1)) . '(' . $mod_strings['many'] . '); ' . str_replace("'", "", getTranslatedString($fl, $modul1)) . '</option>';
                }
            }
        }
    }
}
echo $a;
?>