<?php
global $log,$adb;
$type = $_POST['type'];
$cbmapid = $_POST['cbmapid'];
$SQLforMap = $adb->pquery("Select content,mapname,selected_fields from vtiger_cbmap where cbmapid = ?",array($cbmapid));
$mapSql = str_replace('"','',html_entity_decode($adb->query_result($SQLforMap,0,"selected_fields"),ENT_QUOTES));
//get fields from map SQL
$sqlQueryfields = explode(",",$mapSql);
$nr = count($sqlQueryfields);
//DEFINE COLUMNS ALIAS
 $queryFromMap = str_replace('"','',$adb->query_result($SQLforMap,0,'content'));
 $explodeFrom = explode("FROM",$queryFromMap);
 $getSqlFields = explode(",",str_replace("SELECT","",$explodeFrom[0]));
  for($k=0; $k<count($sqlQueryfields);$k++)
  {
     $i = $k+1;
     $fselect=explode(" AS ",$getSqlFields[$k]);
     $fselect2 =explode(" as ",$fselect[0]);
     $fldtblnamearr = explode(".",$sqlQueryfields[$k]);
     $fldLabel = getColumnLabel($fldtblnamearr[1],$fldtblnamearr[0]);
     $fldLabel = getTranslatedString($fldLabel);
     if($fldLabel == "") $fldLabel = $fldtblnamearr[1];
     $sqlFields[$k] = trim($fselect2[0]).' AS '.trim($fldtblnamearr[0]).'_'.$fldtblnamearr[1];
     $alias = trim($fldtblnamearr[0]).'_'.$fldtblnamearr[1];
if($type == "index"){
$typ='<input type="hidden" name="maptypef'.$i.'" id="maptypef'.$i.'" value="field">';
             $typ='<input type="hidden" name="typef'.$i.'" id="typef'.$i.'" value="field">';
    if($i%2 == 1)
                $a.="<tr height=\"35\" id=\"row$i\" class=\"d0\">";
    else        $a.="<tr height=\"35\" id=\"row$i\" class=\"d1\">";
                    $a.= "<td  align='left'>";
                         $a.= "<input type='checkbox'  id='checkf$i' name='checkf$i'  class='k-checkbox'>";
                   $a.="<label class='k-checkbox-label' for='checkf$i' id='mapfldname$i' >".$fldLabel."</label>"
                    . "<input type=\"hidden\" value='$sqlFields[$k]' id='mapfield$i' name='mapfield$i'>
                            $typ
                      <input type=\"hidden\" value='$sqlQueryfields[$k]' id='colname$i' name='colname$i'>
                             <input type=\"hidden\" value='$alias' id='colaliasname$i' name='colaliasname$i'>"
                    . "</td>"
           . "<td><input type=\"text\" id=\"modulfieldlabel$i\" name=\"modulfieldlabel$i\"  value=\"$fldLabel\">";
            $a.="</td>";
             $a.= "<td><input type='checkbox'  id='checkanalyzed$i' name='checkanalyzed$i'  class='k-checkbox'>";
                   $a.="<label class='k-checkbox-label' for='checkanalyzed$i' id='checkanalyzedname$i' >Analyzed</label></td>"   ;     
                   $a.= "</tr>";
}
else {
  $typ='<input type="hidden" name="maptypef'.$i.'" id="maptypef'.$i.'" value="field">';
             $typ='<input type="hidden" name="typef'.$i.'" id="typef'.$i.'" value="field">';
    if($i%2 == 1)
                $a.="<tr height=\"35\" id=\"row$i\" class=\"d0\">";
    else        $a.="<tr height=\"35\" id=\"row$i\" class=\"d1\">";
                    $a.= "<td  align='left'>";
                         $a.= "<input type='checkbox'  id='checkflogg$i' name='checkflogg$i'  class='k-checkbox'>";
                   $a.="<label class='k-checkbox-label' for='checkflogg$i' id='mapfldname$i' >".$fldLabel."</label>"
                    . "<input type=\"hidden\" value='$sqlFields[$k]' id='mapfield$i' name='mapfield$i'>
                            $typ
                      <input type=\"hidden\" value='$sqlQueryfields[$k]' id='colname$i' name='colname$i'>
                             <input type=\"hidden\" value='$alias' id='colaliasname$i' name='colaliasname$i'>"
                    . "</td>"
           . "<td><input type=\"text\" id=\"modulfieldlabel$i\" name=\"modulfieldlabel$i\"  value=\"$fldLabel\">";
            $a.="</td>";
             $a.= "<td><input type='checkbox'  id='checkanalyzedlogg$i' name='checkanalyzedlogg$i'  class='k-checkbox'>";
                   $a.="<label class='k-checkbox-label' for='checkanalyzedlogg$i' id='checkanalyzedname$i' >Analyzed</label></td>"   ;     
                   $a.= "</tr>";  
}
 }
 
 function getColumnLabel($fldname,$fldtable){
  global $adb;
       $fldname = trim($fldname);
       $fldtable = trim($fldtable);
       $sql = $adb->pquery("select fieldlabel from vtiger_field where fieldname LIKE ? and tablename LIKE ?",array($fldname,$fldtable));
       $fieldLabel = $adb->query_result($sql,0,'fieldlabel');
    return $fieldLabel;
 }
 echo $a.'$$'.$nr;
?>
