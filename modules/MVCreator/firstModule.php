<?php

//echo "fillim fare ";
include 'XmlContent.php';

$FirstmoduleXML = "";//"edmondi" . $_POST['MapID'];
if (isset($_REQUEST['MapID'])) {
    $mapid = $_REQUEST['MapID'];
    $FirstmoduleXML = takeFirstMOduleFromXMLMap($mapid);
  // echo "brenda kushtit mapID ".$mapid;
}
//echo "Jashtekushtit mapID ";
//echo $FirstmoduleXML;
////echo '<option  selected>'.$FirstmoduleXML.' </option>';
//exit();
if (isset($_REQUEST['secModule']) && isset($_REQUEST['firstModule'])) {
    $secModule = implode(',', array_keys(array_flip(explode(',', $_REQUEST['secModule']))));
    $modulesAllowed = '"' . $_REQUEST['firstModule'] . '","' . str_replace(',', '","', $secModule) . '"';
    $query = "SELECT * from vtiger_tab where isentitytype=1 and name<>'Faq' 
        and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and presence=0
        and name in ($modulesAllowed)";
   // echo "brenda ifit seltab etj ";
} else {
    $query = "SELECT * from vtiger_tab where isentitytype=1 and name<>'Faq' 
        and name<>'Emails' and name<>'Events' and name<>'Webmails' and name<>'SMSNotifier'
        and name<>'PBXManager' and name<>'Modcomments' and name<>'Calendar' and presence=0";
    //echo "brenda elsit nese nuk plotesohet if ";
}
$result = $adb->query($query);
$num_rows = $adb->num_rows($result);
//echo "para ciklit fore  ";
if ($num_rows != 0) {
    //echo "if num rows eshte e madhe se 0 ";
    for ($i = 1; $i <= $num_rows; $i++) {
        //echo "brenda ciklit for ".$i;
        $modul1 = $adb->query_result($result, $i - 1, 'name');

        if (strlen($FirstmoduleXML) != 0 && $FirstmoduleXML == $modul1) {
            $a .= '<option selected value="' . $modul1 . '">' . str_replace("'", "", getTranslatedString($modul1)) . '</option>';
           // echo "nese plotesohet kushti firstmodulexml";
        } else {
            $a .= '<option  value="' . $modul1 . '">' . str_replace("'", "", getTranslatedString($modul1)) . '</option>';
            ///echo "nese nuk  plotesohet kushti firstmodulexml";
        }
    }
}
echo $a;
?>
