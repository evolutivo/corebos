<?php

include ('All_functions.php');
include('modfields.php');

$modules=$_POST['mod'];
 $datareturn="";

if (!empty($modules)) {

	 $dataarray=GetAllRelationMOdul($modules);
	// echo getModFields($modules, $acno.$dbname);

	if (!empty($dataarray))
	{
		foreach ( $dataarray as $key) 
		{
	 		// echo "value=".$key."<br>";
	 		$datareturn.=getModFields(explode(";", $key)[0], $acno.$dbname);
	 	}
	}else
	{
		echo getModFields($modules);
	}

	echo $datareturn;
}


