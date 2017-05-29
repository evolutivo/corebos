<?php
global $adb;
$selField1 = $_POST['selField1'];//stringa con tutte i campi scelti in selField1
$selField2 = $_POST['selField2'];//stringa con tutte i campi scelti in selField1
$nameView = $_POST['nameView'];//nome della vista
$campiSelezionati = $_POST['campiSelezionati'];
$OptVAl = $_REQUEST['JoinOV'];
$sendarray = array();
$valuecombo = $_REQUEST['Valueli'];

for ($j = 0; $j < count($valuecombo); $j++) {
    $expdies = explode("!", $valuecombo[$j]);
    $sendarray[] = array(
        'Values' => $expdies[0],
        'Texti' => $expdies[1],
    );
}

$optionValue = array();
$optgroup = array();
for ($j = 0; $j < count($campiSelezionati); $j++) {
    $expdies = explode(":", $campiSelezionati[$j]);
    array_push($optionValue, $expdies[0] . "." . $expdies[1]);
}

$firstmodule = $_POST['fmodule'];
$secmodule = $_POST['smodule'];
$Moduls = array();
array_push($Moduls, $firstmodule);
array_push($Moduls, $secmodule);
//echo inerJoionwithCrmentity($Moduls);
//$selField1 = explode(',',$stringaselField1);
//$selField2 = explode(',',$stringaselField2);
//$stringaFields = implode(",", selectValueswithoutjoincrmentity($OptVAl, $Moduls)); substr($stringaFields2, 0, -2)
//$stringaFields2 = implode(",", selectValueswithjoincrmentity($OptVAl, $Moduls));
$selTab1 = $_POST['selTab1'];
$selTab2 = $_POST['selTab2'];
$selTab3=array_unique(array_merge($selTab1,$selTab2));
$primfield=$selTab1[0];
$query = $adb->query("select entityidfield,tablename from vtiger_entityname where modulename='$primfield'");
$entityidfield = $adb->query_result($query, 0, "entityidfield");
$tablename = $adb->query_result($query, 0, "tablename");
$entityidfields = $tablename . "." . $entityidfield;
$generatetQuery = showJoinArray($selField1, $selField2, $nameView,$OptVAl, $selTab1, $selTab2, $entityidfields, $selTab3);
/*
 * Stampa a video nel <div> con id="results" la query per la creazione della vista materializzata
 */
function showJoinArray($selField1, $selField2, $nameView, $stringaFields, $selTab1, $selTab2, $primarySelectID, $Moduls)
{
    $acc = 0;
    $index=0;
    $strQuery = '';
    global $log;
    $prim=explode(".",$primarySelectID);
    $primarySelectID=$prim[1];
    for ($i = 0; $i < count($selTab1); $i++) {
        if ($selTab1[$i] == "Potentials") {
            $selTab1[$i] = "vtiger_potential";
        } else if ($selTab1[$i] == "Accounts") {
            $selTab1[$i] = "vtiger_account";
        } else if ($selTab1[$i] == "Contacts") {
            $selTab1[$i] = "vtiger_contactdetails";
        }
         else if ($selTab1[$i] == "Leads") {
            $selTab1[$i] = "vtiger_leaddetails";
        } else {
            $selTab1[$i] = "vtiger_" . strtolower($selTab1[$i]);
        }
        if ($selTab2[$i] == "Potentials") $selTab2[$i] = "vtiger_potential";
        else if ($selTab2[$i] == "Accounts") $selTab2[$i] = "vtiger_account";
        else if ($selTab2[$i] == "Contacts") $selTab2[$i] = "vtiger_contactdetails";
        else if ($selTab2[$i] == "Leads") $selTab2[$i] = "vtiger_leaddetails";
        else $selTab2[$i] = "vtiger_" . strtolower($selTab2[$i]);
        if($index==0) $index=1;
        $firsttblid=getentityidfield(strtolower($selTab1[$i]));
        $secondtblid=getentityidfield(strtolower($selTab2[$i]));
        if ($i == 0) {
            /* <b> CREATE TABLE </b>'.$nameView.'<b>  */
            $arr["$selTab1[$i]"]=strtolower($selTab1[$i]).'_0';
            $arr["$selTab2[$i]"]=strtolower($selTab2[$i]).'_'.$index;
            $maintab=strtolower($selTab1[$i]);
            $selTabmain=$selTab1[$i];
            $stringaFields2 = implode(",", selectValueswithjoincrmentity($stringaFields, $Moduls,$index,$arr));
            $selectquery='<b> SELECT </b>'.strtolower($selTab1[$i]).'_0.'. $primarySelectID . "," . substr($stringaFields2, 0, -2);
            $strQuery .= '<b> FROM </b>' . strtolower($selTab1[$i]) .' as '.strtolower($selTab1[$i]).'_0 join vtiger_crmentity CRM_'.strtolower($selTab1[$i]).'_0 on CRM_'.strtolower($selTab1[$i]).'_0.crmid='.strtolower($selTab1[$i]).'_0.'.$firsttblid.' <b>INNER JOIN </b>'.$selTab2[$i].' <b> as </b> ' . strtolower($selTab2[$i]).'_'.$index. '<b> ON </b>' . strtolower($selTab1[$i]).'_0.'. $selField1[$i] . '<b> = </b>' . strtolower($selTab2[$i]).'_'.$index. '.'. $selField2[$i].' join vtiger_crmentity CRM_'.strtolower($selTab2[$i]).'_'.$index.' on CRM_'.strtolower($selTab2[$i]).'_'.$index.'.crmid='.strtolower($selTab2[$i]).'_'.$index.'.'.$secondtblid;
         //  if(count($selTab1)==1)
           // $strQuery .= inerJoionwithCrmentity($Moduls,$stringaFields,$index,strtolower($selTab1[$i]));
            if ($selTab1[$i] == "vtiger_account" && $acc == 0) {
                $strQuery .= ' <b> INNER JOIN </b> vtiger_accountbillads <b> ON </b>  vtiger_account_'.$index.'.accountid=vtiger_accountbillads.accountaddressid';
                $strQuery .= '<b>  INNER JOIN </b> vtiger_accountshipads <b> ON </b>  vtiger_account_'.$index.'.accountid=vtiger_accountshipads.accountaddressid';
                $acc =$acc+ 1;
            }
            $index2=$index;
            $index++;
        } else {  
            if($selTab1[$i]==$selTabmain)
            {
            $index2--;
            }
            $arr["$selTab1[$i]"]=strtolower($selTab1[$i]).'_'.$index2;
            $arr["$selTab2[$i]"]=strtolower($selTab2[$i]).'_'.$index;
            $stringaFields2 = implode(",", selectValueswithjoincrmentity($stringaFields, $Moduls,$index,$arr));
            $selectquery='<b> SELECT </b>'.strtolower($maintab).'_0.'. $primarySelectID . "," . substr($stringaFields2, 0, -2);
            $strQuery .= '<b> INNER JOIN </b>'.$selTab2[$i].' <b> as </b> ' . $selTab2[$i].'_'.$index . '<b> ON </b>' . strtolower($selTab1[$i]).'_'.($index2).'.' . $selField1[$i] . '<b> = </b>' . strtolower($selTab2[$i]).'_'.$index. '.'. $selField2[$i].' join vtiger_crmentity as CRM_'.$selTab2[$i].'_'.$index.' on CRM_'.$selTab2[$i].'_'.$index.'.crmid='.$selTab2[$i].'_'.$index.'.'.$secondtblid;
            //$strQuery1 .= '<b> INNER JOIN </b>' . $selTab2[$i] . '<b> ON </b>' ;//. strtolower($selTab1[$i]) . '.' . $selField1[$i] . '<b> = </b>' . strtolower($selTab2[$i]) . '.' . $selField2[$i];
            if ($selTab2[$i] == "vtiger_account" && $acc == 0) {
                $strQuery .= '<b> INNER </b> join vtiger_accountbillads <b> ON </b> vtiger_account_'.$index.'.accountid=vtiger_accountbillads.accountaddressid';
                $strQuery .= ' <b> INNER join </b> vtiger_accountshipads <b> ON </b>  vtiger_account_'.$index.'.accountid=vtiger_accountshipads.accountaddressid';
                $acc =$acc+ 1;
            }
           //  $strQuery .= inerJoionwithCrmentity($Moduls,$stringaFields,$index,strtolower($selTab1[$i]));
            $index++;
        }

    }
    return str_replace(",_"," ",$selectquery.' '.$strQuery);
}
function selectValueswithjoincrmentity($params, $Moduls,$nr,$arr)
{
    global $adb;
    $query = $adb->query("select tablename from vtiger_entityname where modulename='$Moduls[1]'");
    $tablename = $adb->query_result($query, 0, "tablename");

    $Querysplit = array();
    if (!empty($params)) {
        $index = 0;

        for ($i = 0; $i <= count($params); $i++) {
            foreach ($Moduls as $modul) {
                $splitvalues = explode(":", $params[$i]);
                if ($splitvalues[1] == "vtiger_crmentity") {
                     $query2 = $adb->query("select tablename from vtiger_entityname where modulename='$modul'");
                     $tablename2 = $adb->query_result($query2, 0, "tablename");
                     $tab2=$arr["$tablename2"];
                     array_push($Querysplit, "CRM_" .$tab2. "." . $splitvalues[2]);

                }
                elseif ($splitvalues[1]==$tablename){
                    array_push($Querysplit,  $splitvalues[1]."_".$nr . "." . $splitvalues[2]);
                }
                else {
                    array_push($Querysplit,  $splitvalues[1] . "_0." . $splitvalues[2]);//($nr > 0 ? $splitvalues[1]."_".$nr . "." . $splitvalues[2] : $splitvalues[1] . "." . $splitvalues[2]);
                }


            }
            $index++;
//            return $Querysplit;
        }

        return array_unique($Querysplit);
    }
}

//function selectValueswithoutjoincrmentity($params, $Moduls)
//{
//    $Querysplit = array();
//    if (!empty($params)) {
//        $nr2 = 1;
//
//        for ($i = 0; $i <= count($params); $i++) {
//            foreach ($Moduls as $modul) {
//
//                $splitvalues = explode(":", $params[$i]);
//                if ($splitvalues[1] != "vtiger_crmentity") {
//                    array_push($Querysplit, $splitvalues[1] . "." . $splitvalues[2]);
//                }
////                } else {
////                    array_push($Querysplit, $splitvalues[1] . "." . $splitvalues[2]);
////                }
//
//
//            }
//            $nr2++;
////            return $Querysplit;
//        }
//
//        return $Querysplit;
//    }
//}

function inerJoionwithCrmentity($Moduls, $OptVAl,$nr,$tab)
{
    global $adb;
    $joinCrmentity = '';
    //$nr = 1;
    $prova = array();
    $index=0;
    foreach ($Moduls as $modul) {

//        $prova= selectValues($OptVAl, $modul, $nr);
        $joinCrmentity .= '  <b>JOIN</b>   ';
        $query = $adb->query("select entityidfield,tablename from vtiger_entityname where modulename='$modul'");
        $Module_entityidfield = $adb->query_result($query, 0, "entityidfield");
        $Module_tablename = $adb->query_result($query, 0, "tablename");
        $JoinCondition = $Module_tablename . "." . $Module_entityidfield;
        if (!empty($JoinCondition)) {
            $joinCrmentity .= 'vtiger_crmentity <b>as</b> ' . 'CRM_' . strtolower($modul);
            $joinCrmentity .= '  <b>ON</b>  ';
            $joinCrmentity .= ' CRM_' . strtolower($modul) . '.crmid = ' . ($tab!= $Module_tablename ? $Module_tablename.'_'.$nr.'.'.$Module_entityidfield  : $Module_tablename.'_0.'.$Module_entityidfield);
            $joinCrmentity .= ' <b>AND</b> CRM_' . strtolower($modul) . '.deleted = 0   ';

        }
        //$nr++;
        $index++;
    }

    return $joinCrmentity;


//    $querysecondmodule = $adb->query("select entityidfield,tablename from vtiger_entityname where modulename='$modul2'");
//    $SecModule_entityidfield = $adb->query_result($querysecondmodule, 0, "entityidfield");
//    $SecModule_tablename = $adb->query_result($querysecondmodule, 0, "tablename");


//    $joinCrmentity = '  <b>JOIN</b>   ';
//    if (!empty($FirstCondition) && !empty($SecondCondition)) {
//
//        $joinCrmentity .= 'vtiger_crmentity <b>ON</b>';
//        $joinCrmentity .= ' vtiger_crmentity.crmid = ' . $FirstCondition;
//        $joinCrmentity .= '  <b>JOIN</b>  ';
//        $joinCrmentity .= 'vtiger_crmentity <b>ON</b>';
//        $joinCrmentity .= ' vtiger_crmentity.crmid = ' . $SecondCondition;
//        return $joinCrmentity;
//    } else {
//        return "";
//    }
}
function getentityidfield($table)
{global $adb;
        $query = $adb->query("select entityidfield,tablename from vtiger_entityname where tablename='$table'");
        $Module_entityidfield = $adb->query_result($query, 0, "entityidfield");
        return $Module_entityidfield;
}
/*
 * Ricevendo il nome di una tabella, fornisce il un array contenente tutti
 * i nomi dei campi in essa contenuta.
 */

function getCampi($table)
{
    global $db;
    $fields = mysql_list_fields($db, $table);
    $numColumn = mysql_num_fields($fields);
    for ($i = 0; $i < $numColumn; $i++) {
        $fieldList[$i] = mysql_field_name($fields, $i);
    }
    return $fieldList;
}

/*
 * Riceve in ingresso un array e un intero, e restituisce un sub array
 */
function prelevaArray($array, $indice)
{
    for ($i = 0; $i < $indice; $i++) {
        $subArray[$i] = $array[$i];
    }
    return $subArray;
}


/*
 * Riceve in ingresso un array, e concatena ogni elemento in un'unica stringa
 */
//function concatenaAllField($allFields)
//{
//    for ($i = 0; $i < count($allFields); $i++) {
//        if ($i == 0) {
//            $stringa = $allFields[$i];
//        } else {
//            $stringa = $stringa . ', ' . $allFields[$i];
//        }
//    }
//    return $stringa;
//}
/* Prende in ingresso due liste di tabelle.
 * $tableList1 corrisponde alle tabelle inserite nel selField1
 * $tableList2 corrisponde alle tabelle inserite nel selField2
 *
 * La funzione, prende il primo elemento della lista di $tableList1, perchè
 * gli altri elemnti sono già presenti, e lo inserisce in $tableList2,
 * in questo modo si ha un unico array.
 * Successivamente si scorre tutto l'array con un for, e con un'altro si scorre
 * nuovamente il tutto.
 * In questo modo, per ogni tabella, si prende ogni campo, e si controlla se esistono
 * tabelle con campi con lo stesso nome, e se si, si rinominano.
 * Successivamente, ogni campo modificato, e non modificato, viene inserito nell'array
 * $allFields, che poi è il valore di ritorno della funzione.
 *
 */
//echo $generatetQuery;
//exit();
//function getAllFields($tableList1, $tableList2)
//{
//    $allFields = array();
//    $num = 0;
//    $tableList2[count($tableList2)] = $tableList1[0];
//
//    for ($i = 0; $i < count($tableList2); $i++) {
//        if (!(in_array($tableList2[$i], prelevaArray($tableList2, $i)) || ((in_array($tableList2[$i], prelevaArray($tableList1, $i))) && $tableList2[$i] != $tableList1[0]))) {
//            $fields = getCampi($tableList2[$i]);
//            for ($j = 0; $j < (count($tableList2)); $j++) {
//                if ($tableList2[$i] != $tableList2[$j]) {
//                    for ($k = 0; $k < count($fields); $k++) {
//                        $fieldsTabList2 = getCampi($tableList2[$j]);
//                        if (in_array($fields[$k], $fieldsTabList2)) {
//                            $stringa = $tableList2[$i] . '.' . $fields[$k] . ' <b>AS</b> ' . $tableList2[$i] . '_' . $fields[$k];
//                            for ($u = 0; $u < count($fieldsTabList2); $u++) {
//                                if ($fieldsTabList2[$u] == $fields[$k]) {
//                                    $fieldsList2[$u] = $tableList2[$j] . '.' . $fieldsList2[$k] . ' <b>AS</b> ' . $tableList2[$j] . '_' . $fieldsList2[$k];
//                                }
//                            }
//                            $fields[$k] = $stringa;
//                        }
//                    }
//                }
//            }
//            for ($s = 0; $s < count($fields); $s++) {
//                $allFields[$num] = $fields[$s];
//                $num++;
//            }
//        }
//    }
//    return $allFields;
//}
require_once('Smarty_setup.php');
global $app_strings, $mod_strings, $current_language, $currentModule, $theme, $adb, $root_directory, $current_user;
$theme_path = "themes/" . $theme . "/";
$image_path = $theme_path . "images/";
$smarty = new vtigerCRM_Smarty();
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE", $currentModule);
$smarty->assign("IMAGE_PATH", $image_path);
$smarty->assign("DATEFORMAT", $current_user->date_format);
$smarty->assign("QUERY", $generatetQuery);

//$optString = "";
//foreach ($optgroup as  $key => $v1) {
//    $optString .= "<optgroup label='".$key."'>";
//    foreach ($v1 as $k2 => $v2) {
//        $optString.='<option value='.$k2.">".$v2."</option>";
//    }
//    $optString.='</optgroup>';
//}

//$smarty->assign("FIELDS", $PRovatjeter);
$smarty->assign("valueli", $sendarray);
//$smarty->assign("texticombo", $texticombo);
//$smarty->assign("FOPTION", '');
$smarty->assign("FIELDLABELS", $campiSelezionatiLabels);
$smarty->assign("JS_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
$smarty->display("modules/MVCreator/WhereCondition.tpl");
?>


