<?php

include ('All_functions.php');
include('modfields.php');

$modules=$_POST['mod'];

if (!empty($modules)) {

	 echo "moduli eshte mod=".$modules;

	 echo GetModulRel($modules);
	
}


