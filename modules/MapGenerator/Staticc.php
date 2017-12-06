<?php

 /**
* this is the type of errors or debugs
*/
abstract  class TypeOFErrors 
{
	const ErrorLG="----Edmondi Kacaj--- ERROR !!!";
	const INFOLG="----Edmondi Kacaj----- INFO !!!";
	const WARNINGLG="---Edmondi Kacaj---- WARNING !!!";
	const SuccesLG="----Edmondi Kacaj----- SUCCESS !!!";



	public static function FunctionName($exepsion)
	{
		$updateMovedInToAssigned = fopen(__DIR__."logs/ErrorExepsion.txt", "a");
		$str = "~~~~~~~~~~~~~~~~~~~~~ \n[".date("Y/m/d h:i:s " , mktime())."] \n~~~~~~~~~~~~~~~~~~~~~\n";
		fwrite($updateMovedInToAssigned, "\n".$str."\nError Handler : \n\t\t".$exepsion);
		fclose($updateMovedInToAssigned);
	}

}