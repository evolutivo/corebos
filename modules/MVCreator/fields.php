<?php
global $log;
require_once ('include/utils/utils.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');
//require_once('database/DatabaseConnection.php');
require_once ('include/CustomFieldUtil.php');
require_once ('data/Tracker.php');
$rec=$_REQUEST['rec'];
$ai=$adb->query("select * from vtiger_accountinstallation 
                 join vtiger_crmentity on crmid=accountinstallationid 
                 join vtiger_account on accountid=linktoacsd 
                 where accountinstallationid = $rec");

$dbname=$adb->query_result($ai,0,"dbname");
$acno=$adb->query_result($ai,0,"acin_no");

$m=$_POST['mod'];
$res=$adb->query("select * from $acno$dbname.vtiger_tab ");

 for($i=0;$i<$adb->num_rows($res);$i++){
    $name=$adb->query_result($res,$i,"tablabel");
    $id=$adb->query_result($res,$i,"tabid");
    $b.="<option value=\"".$id."\">";
    $b .= $name;
    $b.="</option>";
}

    $query="SELECT * from  $acno$dbname.vtiger_selectcolumn where queryid=$m order by columnindex";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
$a.='<tr><td width="55%" class="lvtCol"><input type=checkbox name=allids1 id=allids1 checked="false" onchange="checkvalues();"> <b>Lista dei Campi</b></td><td  width="20%" class="lvtCol"  align="center"><b>Moduli</b></td></tr>';
        $a.="<tr  height=\"35\" id=\"row\">"
                . "<td idr='$num_rows' namer='$num_rows'>"
                . "<input type=\"hidden\" value='$num_rows' id='field$i' name='val'>"
                . "</td>"
            . "</tr>";
        if($num_rows!=0){


	for($i=1;$i<=$num_rows;$i++)
	{
            if($adb->query_result($result,$i-1,'columnname')!='none'){
                $cn=explode(":",$adb->query_result($result,$i-1,'columnname'));
		$f = getTranslatedString($cn[2]);
                $id =$cn[0].'.'.$cn[1];
                $f1=str_replace("_"," ",utf8_encode(html_entity_decode($f)));
                $n++;
    $typ='<input type="hidden" name="typef'.$i.'" id="typef'.$i.'" value="field">';
                $a.="<tr height=\"35\" id=\"row$i\">"
                        . "<td  align='left'>"
                        . "<input type='checkbox' id='checkf$i' name='checkf$i' value='0' onclick='this.value=1'>"
                        . "<span id='fldname$i'>".$f1."</span>"
                        . "<input type=\"hidden\" value='$f' id='field$i' name='field$i'>$typ<input type=\"hidden\" value='$id' id='colname$i' name='colname$i'>"
                        . "</td>"
                        . "<td><select class=\"small\" id=\"modul$i\" name=\"modul$i\"  >";
                $a.=$b;
                $a.=" </select></td>"
                        . "</tr>";
            }

     }
}

   $query1="SELECT * from  $acno$dbname.vtiger_selectcolumn where queryid=$m and (columnname like '%:D' or columnname like '%:T') order by columnindex";


	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){

      	for($i=1;$i<=$num_rows1;$i++)
	{if($adb->query_result($result1,$i-1,'columnname')!='none'){
            $cn=explode(":",$adb->query_result($result1,$i-1,'columnname'));
		$f = getTranslatedString($cn[2]);
                 $f1=str_replace("_"," ",utf8_encode(html_entity_decode($f)));
                $id =$cn[0].'.'.$cn[1];
 $a1.="<option value='$id'>$f1</option>";

}}

}

echo $a."$$".$num_rows;
?>
