<?php
global $log,$adb;

$reportID=$_POST['reportID'];
$log->debug("ketu".$reportID);


    $query="SELECT * from  vtiger_selectcolumn where queryid =$reportID order by columnindex";
	$result = $adb->query($query);
	$num_rows=$adb->num_rows($result);
        if($num_rows!=0){
	for($i=1;$i<=$num_rows;$i++)
	{
            if($adb->query_result($result,$i-1,'columnname')!='none'){
                $cn=explode(":",$adb->query_result($result,$i-1,'columnname'));
//                var_dump($adb->query_result($result,$i-1,'columnname'));
                $fieldnamearr = explode("_", $cn[2]);
                $fieldname = $fieldnamearr[1]." ".$fieldnamearr[2];
                $f = getTranslatedString($cn[2]);
                $id =$cn[0].'.'.$cn[1];
                $f1=str_replace("_"," ",utf8_encode(html_entity_decode($f)));
                $fldlabelarr = explode("_",$f);
                $fldLabel="";
                for($k=1;$k<count($fldlabelarr);$k++)
                $fldLabel .= $fldlabelarr[$k]." ";
                $n++;
    $typ='<input type="hidden" name="typef'.$i.'" id="typef'.$i.'" value="field">';
    if($i%2 == 1)
                $a.="<tr height=\"35\" id=\"row$i\" class=\"d0\">";
    else        $a.="<tr height=\"35\" id=\"row$i\" class=\"d1\">";
           $a.= "<td  align='left'>"
          . "<input type='checkbox' class='k-checkbox' id='checkf$i' name='checkf$i'>"
          ."<label class='k-checkbox-label' for='checkf$i' id='fldname$i' >".$f1."</label>"
                        . "<input type=\"hidden\" value='$f' id='field$i' name='field$i'>$typ<input type=\"hidden\" value='$id' id='colname$i' name='colname$i'>"
                        . "</td>"
               . "<td><input type=\"text\" id=\"modulfieldlabel$i\" name=\"modulfieldlabel$i\"  value=\"$fldLabel\">";
                $a.="</td>";
               $a.= "<td><input type='checkbox'  id='checkanalyzed$i' name='checkanalyzed$i'  class='k-checkbox'>";
                   $a.="<label class='k-checkbox-label' for='checkanalyzed$i' id='checkanalyzedname$i' >Analyzed</label></td>"   ;     
                   $a.= "</tr>";
            }

     }
}

   $query1="SELECT * from vtiger_selectcolumn where queryid=$reportID and (columnname like '%:D' or columnname like '%:T') order by columnindex";


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
