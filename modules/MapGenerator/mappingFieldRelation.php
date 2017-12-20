<?php

/**
 * @Author: edmondi kacaj
 * @Date:   2017-12-20 10:43:55
 * @Last Modified by:   edmondi kacaj
 * @Last Modified time: 2017-12-20 16:16:07
 */
include 'All_functions.php';
require_once("modfields.php");


$module=$_POST['mod'];

if (!empty($module)) {
	if (!empty(MappingRelationFields($module))) {
	  	echo MappingRelationFields($module);
		// echo "Moduli".$module;
	} else {
	  echo "<option value=''>None</option>";
	}
	

} else {
	 echo "<option value=''>None</option>";
}
