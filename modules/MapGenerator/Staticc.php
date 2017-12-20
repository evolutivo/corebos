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
	/**
	 * @param      <String>  constant for tabele of history
	 */
	const Tabele_name="mapgeneration_queryhistory";

	//this is for all maps, key is the name of map ,values is used for translate 
	const AllMaps = array(
	'SQL'=>'ConditionQuery',
	'Mapping'=>'TypeMapMapping',
	'IOMap'=>'TypeMapIOMap',
	'FieldDependency'=>'TypeMapFieldDependency',
	'FieldDependencyPortal'=>'FieldDependencyPortal',
	'WS'=>'TypeMapWS',
	'MasterDetail'=>'MasterDetail',
	'ListColumns'=>'ListColumns',
	'Module_Set'=>'module_set',
	'GlobalSearchAutocomplete'=>'GlobalSearchAutocompleteMapping',
	'CREATEVIEWPORTAL'=>'CREATEVIEWPORTAL',
	'ConditionExpression'=>'ConditionExpression',
	'DETAILVIEWBLOCKPORTAL'=>'DETAILVIEWBLOCKPORTAL',
	'MENUSTRUCTURE'=>'MENUSTRUCTURE',
	'RecordAccessControl'=>'RecordAccessControl',
	'DuplicateRecords'=>'DuplicateRecords',
	);
	
	

}