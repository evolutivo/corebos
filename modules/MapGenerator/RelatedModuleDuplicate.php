<?php

/**
 * @Author: edmondi kacaj
 * @Date:   2017-12-19 12:28:29
 * @Last Modified by:   edmondi kacaj
 * @Last Modified time: 2017-12-19 14:32:21
 */
include 'All_functions.php';


$module=$_POST['mod'];

if (!empty($module)) {
	
	if (!empty(GetAllrelation($module))) {
	  echo GetAllrelation($module);
		// echo "Moduli".$module;
	} else {
	  echo "<option value=''>None</option>";
	}
	

} else {
	 echo "<option value=''>None</option>";
}



/**
 * Gets the allrelation.
 *
 * @param      string  $module  The module
 *
 * @return     string  The allrelation.
 */
function GetAllrelation($module="")
{
	global $adb, $root_directory, $log;
	if (!empty($module))
	{
		$log->debug("Info!! Value is not ampty");
		$idmodul=getModuleID($module,"tabid");
		$sql="SELECT * from vtiger_relatedlists where tabid='$idmodul'";
		$result = $adb->query($sql);
	    $num_rows=$adb->num_rows($result);
	    $historymap="";
	    $a='<option value="" >(Select a module)</option>';
	    if($num_rows!=0)
	    {
	        for($i=1;$i<=$num_rows;$i++)
	        {
	        	$modulename=(!empty(SearchbyIDModule($adb->query_result($result,$i-1,'related_tabid')))?SearchbyIDModule($adb->query_result($result,$i-1,'related_tabid')):$adb->query_result($result,$i-1,'label'));
	            $Modules = $adb->query_result($result,$i-1,'label');
	            $relatedtypes = $adb->query_result($result,$i-1,'relationtype');
	           
	            $a.='<option value="'.$modulename.'#'.$relatedtypes.'">'.str_replace("'", "", getTranslatedString($modulename)).'</option>';	            
	        }
	       return $a;
	    }else{$log->debug("Info!! The database is empty or something was wrong");}
    }else {
		return "";
	}
	 
}