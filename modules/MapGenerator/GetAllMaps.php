<?php

$TypeMaps=$_POST['ALL'];

if (!empty($TypeMaps)) {
	
	if (!empty(GetMaps($TypeMaps))) {
	  echo GetMaps($TypeMaps);
	} else {
	  echo "<option value=''>None</option>";
	}
	

} else {
	 echo "<option value=''>None</option>";
}




/**
 * This function is to get all maps from database a
 * @param string $value  this param is if you want to filter by map type
 * @return  a list of maps 
 */
function GetMaps($value="")
{
	global $adb, $root_directory, $log;
	if (!empty($value))
	{
		$log->debug("Info!! Value is not ampty");
		if ($value=="ALL") {
			$sql="SELECT cb.*,cr.* FROM vtiger_cbmap cb JOIN  vtiger_crmentity cr ON cb.cbmapid=cr.crmid WHERE cr.deleted=0 ORDER BY cb.maptype ";
		} else {
			$sql="SELECT cb.*,cr.* FROM vtiger_cbmap cb JOIN  vtiger_crmentity cr ON cb.cbmapid=cr.crmid WHERE cr.deleted=0 AND  maptype='$value' ORDER BY cb.maptype ";
		}
		$result = $adb->query($sql);
	    $num_rows=$adb->num_rows($result);
	    $historymap="";
	    $a="";
	    if($num_rows!=0)
	    {
	        for($i=1;$i<=$num_rows;$i++)
	        {
	            $MapID = $adb->query_result($result,$i-1,'cbmapid');
	            $queryid = $adb->query_result($result,$i-1,'mvqueryid');
	            $MapName = $adb->query_result($result,$i-1,'mapname');
	            $MapType = $adb->query_result($result,$i-1,'maptype');
	            if ($MapType!=$historymap) {
	            	$historymap=$MapType;
	            	$a .= '<optgroup label="'.$MapType.'">';
	            } else {
	            	$a.='<option value="'.$MapType.'#'.$MapID.'##'.$queryid.'">'.$MapName.'</option>';
	            }
	            
	        }
    }else{$log->debug("Info!! The database ios empty or something was wrong");}
    return $a;

	} else {
		return "";
	}
	
}