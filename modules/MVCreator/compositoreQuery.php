<?php
global  $adb;
$selField1 = $_POST['selField1'];//stringa con tutte i campi scelti in selField1
$selField2 = $_POST['selField2'];//stringa con tutte i campi scelti in selField1
$nameView=$_POST['nameView'];//nome della vista
$campiSelezionati = $_POST['campiSelezionati'];

$optionsCombo = $_POST['html'];
$optionValue = array();
$optgroup = array();
for($j=0; $j<count($campiSelezionati);$j++){
    $expdies = explode(":",$campiSelezionati[$j]);
    array_push($optionValue,$expdies[0].".".$expdies[1]);
}

$firstmodule = $_POST['fmodule'];
$secmodule = $_POST['smodule'];
//$selField1 = explode(',',$stringaselField1);
//$selField2 = explode(',',$stringaselField2);
$stringaFields = implode(",", $optionValue);
$selTab1 = $_POST['selTab1'];
$selTab2 = $_POST['selTab2'];

$query = $adb->query("select entityidfield,tablename from vtiger_entityname where modulename='$firstmodule'");
$entityidfield = $adb->query_result($query, 0, "entityidfield");
$tablename = $adb->query_result($query, 0, "tablename");
//echo "edmondi". $entityidfield.$tablename;
$entityidfields=$tablename.".".$entityidfield;
$generatetQuery = showJoinArray($selField1, $selField2, $nameView,$stringaFields,$selTab1,$selTab2,$entityidfields);

//echo "optionValue <br>";
//print_r($optionValue);
//echo "<br>";
//echo "stringfileds <br>";
//print_r($stringaFields);
//echo "<br>";
//echo "selftab <br>";
//print_r($selTab1);
//echo "<br>";
//echo "seltab2 <br>";
//print_r($selTab2);
//echo "<br>";
//echo "selfield1 <br>";
//print_r($selField2);
//echo "<br>";
//echo "selfields2 <br>";
//print_r($selField2);
//echo "<br>";
//echo $nameView;
//echo "<br>";
//echo "campiselezionati <br>";
//print_r($campiSelezionati);
//echo "<br>";
/*
 * Stampa a video nel <div> con id="results" la query per la creazione della vista materializzata
 */
function showJoinArray( $selField1, $selField2, $nameView, $stringaFields,$selTab1,$selTab2,$primarySelectID){
   $acc = 0;
   $strQuery = '' ;
   global $log;
    for($i=0;$i<count($selTab1);$i++){
    if($selTab1[$i] == "Potentials") {$selTab1[$i] = "vtiger_potential";}
    else if($selTab1[$i]== "Accounts") {$selTab1[$i] = "vtiger_account";}
    else if($selTab1[$i]== "Contacts"){ $selTab1[$i] = "vtiger_contactdetails";}
    else { $selTab1[$i] = "vtiger_".strtolower($selTab1[$i]);}
     if($selTab2[$i] == "Potentials") $selTab2[$i] = "vtiger_potential";
    else if($selTab2[$i]== "Accounts") $selTab2[$i] = "vtiger_account";
    else if($selTab2[$i] == "Contacts") $selTab2[$i] = "vtiger_contactdetails";
    else $selTab2[$i] = "vtiger_".strtolower($selTab2[$i]);
        if($i==0){
			/* <b> CREATE TABLE </b>'.$nameView.'<b>  */
         $strQuery.= '<b> SELECT </b>'.$primarySelectID.",".$stringaFields.'<b> FROM </b>'.strtolower($selTab1[$i]).'<b> INNER JOIN </b>'.strtolower($selTab2[$i]).'<b> ON </b>'.strtolower($selTab1[$i]).'.'.$selField1[$i].'<b> = </b>'.strtolower($selTab2[$i]).'.'.$selField2[$i];
            if($selTab1[$i] == "vtiger_account" && $acc == 0){
            $strQuery.=' <b> inner join </b> vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid';
             $strQuery.='<b>  inner join </b> vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid';
            $acc =1;
           }
       }
            else{
                $strQuery.='<b> INNER JOIN </b>'.$selTab2[$i].'<b> ON </b>'.strtolower($selTab1[$i]).'.'.$selField1[$i].'<b> = </b>'.strtolower($selTab2[$i]).'.'.$selField2[$i];
                if($selTab2[$i] == "vtiger_account" && $acc == 0){ 
                $strQuery.='<b> inner </b> join vtiger_accountbillads on vtiger_account.accountid=vtiger_accountbillads.accountaddressid';
                 $strQuery.=' <b> inner join </b> vtiger_accountshipads on vtiger_account.accountid=vtiger_accountshipads.accountaddressid';
                $acc =1;
                }
        }
            
    }
     return $strQuery;
}


/*
 * Ricevendo il nome di una tabella, fornisce il un array contenente tutti
 * i nomi dei campi in essa contenuta.
 */

//function getCampi($table){
//        global $db;
//        $fields = mysql_list_fields($db, $table);
//        $numColumn= mysql_num_fields($fields);
//        for ($i = 0; $i < $numColumn; $i++){
//            $fieldList[$i]=mysql_field_name($fields,$i);
//        }
//        return $fieldList;
//}

/*
 * Riceve in ingresso un array e un intero, e restituisce un sub array 
 */
//function prelevaArray($array, $indice){
//    for($i=0; $i<$indice;$i++){
//        $subArray[$i]=$array[$i];
//    }
//    return $subArray;
//}


/*
 * Riceve in ingresso un array, e concatena ogni elemento in un'unica stringa
 */
//function concatenaAllField($allFields)
//{
//      for($i=0;$i<count($allFields);$i++){
//         if($i==0){
//             $stringa=$allFields[$i];
//         }
//         else{
//             $stringa=$stringa.', '.$allFields[$i];
//         }
//       }
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

//function getAllFields($tableList1, $tableList2){
//    $allFields = array();
//    $num=0;
//    $tableList2[count($tableList2)]=$tableList1[0];
//
//    for($i=0;$i<count($tableList2);$i++){
//        if(!(in_array($tableList2[$i],prelevaArray($tableList2,$i) ) || ((in_array($tableList2[$i],prelevaArray($tableList1,$i)))&& $tableList2[$i]!=$tableList1[0]))){
//            $fields=getCampi($tableList2[$i]);
//                for($j=0;$j<(count($tableList2));$j++){
//                    if($tableList2[$i]!=$tableList2[$j]){
//                        for($k=0;$k<count($fields);$k++){
//                            $fieldsTabList2=getCampi($tableList2[$j]);
//                                 if(in_array($fields[$k], $fieldsTabList2)){
//                                    $stringa=$tableList2[$i].'.'.$fields[$k].' <b>AS</b> '.$tableList2[$i].'_'.$fields[$k];
//                                    for($u=0;$u<count($fieldsTabList2);$u++){
//                                        if($fieldsTabList2[$u]==$fields[$k]){
//                                            $fieldsList2[$u]=$tableList2[$j].'.'.$fieldsList2[$k].' <b>AS</b> '.$tableList2[$j].'_'.$fieldsList2[$k];
//                                        }
//                                    }
//                                    $fields[$k]=$stringa;
//                                 }
//                        }
//                     }
//                }
//                for($s=0;$s<count($fields);$s++){
//                    $allFields[$num]=$fields[$s];
//                    $num++;
//                }
//       }
//    }
//    return $allFields;
//}
require_once('Smarty_setup.php');
global $app_strings, $mod_strings, $current_language, $currentModule, $theme,$adb,$root_directory,$current_user;
$theme_path="themes/".$theme."/";
$image_path= $theme_path."images/";
$smarty = new vtigerCRM_Smarty();
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("DATEFORMAT",$current_user->date_format);
$smarty->assign("QUERY",$generatetQuery);

//$optString = "";
//foreach ($optgroup as  $key => $v1) {
//    $optString .= "<optgroup label='".$key."'>";
//    foreach ($v1 as $k2 => $v2) {
//        $optString.='<option value='.$k2.">".$v2."</option>";
//    }
//    $optString.='</optgroup>';
//}
$smarty->assign("FIELDS",$optionsCombo);
$smarty->assign("FIELDLABELS",$campiSelezionatiLabels);
$smarty->assign("JS_DATEFORMAT",parse_calendardate($app_strings['NTC_DATE_FORMAT']));
$smarty->display("modules/MVCreator/WhereCondition.tpl");
?>


