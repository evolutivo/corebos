<?php

//this is for show origin module for Mapping Type 

$module=$_POST['mod'];

if (!empty($module)) {
	
	if (!empty(GetMaps($module))) {
	  echo GetMaps($module);
		// echo "Moduli".$module;
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
function GetMaps($module="")
{
	global $adb, $root_directory, $log;
	if (!empty($module))
	{
		$log->debug("Info!! Value is not ampty");
		// return "is not empty $module";
		$sql="SELECT relmodule FROM `vtiger_fieldmodulerel` WHERE module = '$module' UNION SELECT module FROM `vtiger_fieldmodulerel` WHERE relmodule = '$module' ";
		$result = $adb->query($sql);
	    $num_rows=$adb->num_rows($result);
	    $historymap="";
	    $a="";
	    if($num_rows!=0)
	    {
	        for($i=1;$i<=$num_rows;$i++)
	        {
	            $Modules = $adb->query_result($result,$i-1,'relmodule');
	           
	            $a.='<option value="'.$Modules.'">'.str_replace("'", "", getTranslatedString($Modules)).'</option>';//.str_replace("'", "", getTranslatedString($Modules))
	           
	            
	        }
	       return $a;
	    }else{$log->debug("Info!! The database is empty or something was wrong");}
    }else {
		return "";
	}
	 
}

