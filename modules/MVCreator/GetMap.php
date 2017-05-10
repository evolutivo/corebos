<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 5/10/2017
 * Time: 9:43 AM
 */

//echo "ne fillim fare ";
if(isset($_REQUEST['Filter']) ){
    //echo "direkty mbas if";
    $SQL=$_POST['Filter'];
   // echo "".$SQL;
    $query="SELECT cb.*,cr.* FROM `vtiger_cbmap` cb JOIN  vtiger_crmentity cr ON cb.cbmapid=cr.crmid WHERE cr.deleted=0 AND  maptype='$SQL'";
    $result = $adb->query($query);
    $num_rows=$adb->num_rows($result);
   // echo "direkt mbas query".$num_rows;
    if($num_rows!=0){
       // echo "nese num row wshte ma e madhe se 1 ";
        for($i=1;$i<=$num_rows;$i++)
        {
            $MapID = $adb->query_result($result,$i-1,'cbmapid');
            $MapName = $adb->query_result($result,$i-1,'mapname');
            $a.='<option value="'.$MapID.'">'.$MapName.'</option>';
        }
    }
    echo $a;

}