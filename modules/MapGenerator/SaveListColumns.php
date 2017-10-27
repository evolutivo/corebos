<?php
// SaveListColumns.php

include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');


global $root_directory, $log; 
$Data = array();

//  var_dump($_REQUEST, true);
// exit();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Master Detail"; // stringa con tutti i campi scelti in selField1
$Data = $_POST['alldata'];
$MapID=explode(',', $_REQUEST['savehistory']); 


if (empty($MapName)) {
	echo "Missing the Name of Map";
}

if (!empty($Data)) {
	
	$jsondecodedata=json_decode($Data);	
    //print_r(save_history(add_aray_for_history($jsondecodedata),$MapID[0],add_content($jsondecodedata)));


	 $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     $focust->column_fields['mapname'] = $jsondecodedata[0]->temparray->FirstModule."_ListColumns";
     $focust->column_fields['content']=add_content($jsondecodedata);
     $focust->column_fields['maptype'] ="ListColumns";
     $focust->column_fields['targetname'] =$jsondecodedata[0]->temparray->FirstModule;
     $focust->column_fields['description']= add_description($jsondecodedata);
     $log->debug(" we inicialize value for insert in database ");
     if (!$focust->saveentity("cbMap"))//
      {
      		
          if (Check_table_if_exist("mapgeneration_queryhistory")>0) {
                 echo save_history(add_aray_for_history($jsondecodedata),$MapID[0],add_content($jsondecodedata)).",".$focust->id;
             } 
             else{
                echo "0,0";
                 $log->debug("Error!! MIssing the history Table");
             }  
                    
      } else 
      {
      	 // echo "Edmondi save in map,hghghghghgh";
        //   exit();
         //echo focus->id;
         echo "Error!! something went wrong";
         $log->debug("Error!! something went wrong");
      }

}



/**
 * @param DataDecode {Array} {This para is a array }
 */
function add_content($DataDecode)
{
     //$DataDecode = json_decode($dat, true);
     $countarray=(count($DataDecode)-1);
     $xml = new DOMDocument("1.0");
     $root = $xml->createElement("map");
     $xml->appendChild($root);
     //$name = $xml->createElement("name");
     $target = $xml->createElement("originmodule");
     $targetid = $xml->createElement("originid");
     $targetidText = $xml->createTextNode("");
     $targetid->appendChild($targetidText);
     $targetname = $xml->createElement("originname");
     $targetnameText = $xml->createTextNode( trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",$DataDecode[0]->temparray->secmodule)[0])))));
     $targetname->appendChild($targetnameText);
     $target->appendChild($targetid);
     $target->appendChild($targetname);
     
     $fields = $xml->createElement("relatedlists");
    
     // $hw=0;
     for($i=0;$i<=$countarray;$i++)
       {       		
     		//     //get target field name
               $relatedlist = $xml->createElement("relatedlist");
                 $module = $xml->createElement("module");
			     $moduletext = $xml->createTextNode($DataDecode[$i]->temparray->FirstModule);
			     $module->appendChild($moduletext);
			     $relatedlist->appendChild($module);

			     $linkfield = $xml->createElement("linkfield");
			     $linkfieldtext = $xml->createTextNode($DataDecode[$i]->temparray->SecondfieldID);
			     $linkfield->appendChild($linkfieldtext);
			     $relatedlist->appendChild($linkfield);

			      $columns = $xml->createElement("columns");
			      
				      	$field = $xml->createElement("field");

				      	$label = $xml->createElement("label");
				      	$labelText=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValue);
				      	$label->appendChild($labelText);
				      	$field->appendChild($label);

				      	$name = $xml->createElement("name");
				      	$nameText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[1]);
				      	$name->appendChild($nameText);
				      	$field->appendChild($name);

				      	$table = $xml->createElement("table");
				      	$tableText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[0]);
				      	$table->appendChild($tableText);
				      	$field->appendChild($table);

				      	$columnname = $xml->createElement("columnname");
				      	$columnnameText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[2]);
				      	$columnname->appendChild($columnnameText);
				      	$field->appendChild($columnname);			      				      	
			       
			         $columns->appendChild($field);
			    $relatedlist->appendChild($columns);
              $fields->appendChild($relatedlist);			       			                     
       }

      	 $popup = $xml->createElement("popup");
      	 $linkfield2 = $xml->createElement("linkfield");
	     $linkfieldtext2 = $xml->createTextNode($DataDecode[0]->temparray->FirstfieldID);
	     $linkfield2->appendChild($linkfieldtext2);
	     $popup->appendChild($linkfield2);
	     $columns2 = $xml->createElement("columns");
		 for($i=0;$i<=$countarray;$i++)
		 {
		 	      if ($i!=0) {
				          	
				          		if (explode(":",$DataDecode[$i]->temparray->Firstfield)[1]!=explode(":",$DataDecode[$i-1]->temparray->Firstfield)[1] )
				          		{
				          			$field2 = $xml->createElement("field");

							      	$label2 = $xml->createElement("label");
							      	$labelText2=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValueFirstModuleField);
							      	$label2->appendChild($labelText2);
							      	$field2->appendChild($label2);

							      	$name2 = $xml->createElement("name");
							      	$nameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[1]);
							      	$name2->appendChild($nameText2);
							      	$field2->appendChild($name2);

							      	$table2 = $xml->createElement("table");
							      	$tableText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[0]);
							      	$table2->appendChild($tableText2);
							      	$field2->appendChild($table2);

							      	$columnname2 = $xml->createElement("columnname");
							      	$columnnameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[2]);
							      	$columnname2->appendChild($columnnameText2);
							      	$field2->appendChild($columnname2);
				          		}
				          }else
				          {
				          	$field2 = $xml->createElement("field");

					      	$label2 = $xml->createElement("label");
					      	$labelText2=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValueFirstModuleField);
					      	$label2->appendChild($labelText2);
					      	$field2->appendChild($label2);

					      	$name2 = $xml->createElement("name");
					      	$nameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[1]);
					      	$name2->appendChild($nameText2);
					      	$field2->appendChild($name2);

					      	$table2 = $xml->createElement("table");
					      	$tableText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[0]);
					      	$table2->appendChild($tableText2);
					      	$field2->appendChild($table2);

					      	$columnname2 = $xml->createElement("columnname");
					      	$columnnameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[2]);
					      	$columnname2->appendChild($columnnameText2);
					      	$field2->appendChild($columnname2);
				          }
					      	
					$columns2->appendChild($field2);
				   $popup->appendChild($columns2); 
		 }
		


          //$root->appendChild($name);
     $root->appendChild($target);
     $root->appendChild($fields);
     $root->appendChild($popup);
     $xml->formatOutput = true;
     return $xml->saveXML();
}

function add_description($DataDecode){
    
    //$DataDecode = json_decode($datades, true);
    $countarray=(count($DataDecode)-1);
     
    $xml=new DOMDocument("1.0");
    $root=$xml->createElement("map");
    $xml->appendChild($root);
    //strt create the first module
    $Fmodule = $xml->createElement("Fmodule");

    $Fmoduleid = $xml->createElement("FmoduleID");
    $FmoduleText = $xml->createTextNode("");
    $Fmoduleid->appendChild($FmoduleText);
    
    $Fmodulename=$xml->createElement("Fmodulename");
    $FmodulenameText=$xml->createTextNode(preg_replace('/\s+/', '',$DataDecode[0]->temparray->FirstModule));
    $Fmodulename->appendChild($FmodulenameText);

    $Fmodule->appendChild($Fmoduleid);
    $Fmodule->appendChild($Fmodulename);

    //second module
    $Secmodule = $xml->createElement("Secmodule");

    $Secmoduleid = $xml->createElement("SecmoduleID");
    $SecmoduleText = $xml->createTextNode(preg_replace('/\s+/','',explode(";",$DataDecode[0]->temparray->Secmodule)[1]));
    $Secmoduleid->appendChild($SecmoduleText);     
    $Secmodulename=$xml->createElement("Secmodulename");     
    $SecmodulenameText=$xml->createTextNode( trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",$DataDecode[0]->temparray->secmodule)[0])))));     
    $Secmodulename->appendChild($SecmodulenameText);    
    $Secmodule->appendChild($Secmoduleid);
    $Secmodule->appendChild($Secmodulename);     
    $fields = $xml->createElement("fields");

    for($i=0;$i<=$countarray;$i++)
       {       		
     		//     //get target field name
	               $field = $xml->createElement("field");

				      	$label = $xml->createElement("label");
				      	$labelText=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValue);
				      	$label->appendChild($labelText);
				      	$field->appendChild($label);

				      	$name = $xml->createElement("name");
				      	$nameText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[1]);
				      	$name->appendChild($nameText);
				      	$field->appendChild($name);

				      	$table = $xml->createElement("table");
				      	$tableText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[0]);
				      	$table->appendChild($tableText);
				      	$field->appendChild($table);

				      	$columnname = $xml->createElement("columnname");
				      	$columnnameText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->SecondField)[2]);
				      	$columnname->appendChild($columnnameText);
				      	$field->appendChild($columnname);			      				      	
			       
			        $fields->appendChild($field);			       			                     
       }
    
    for ($i=0; $i <=$countarray ; $i++) { 


        
         if ($i!=0) {
				          	
	      		if (explode(":",$DataDecode[$i]->temparray->Firstfield)[1]!=explode(":",$DataDecode[$i-1]->temparray->Firstfield)[1] )
	      		{
	      			$field2 = $xml->createElement("field");

			      	$label2 = $xml->createElement("label");
			      	$labelText2=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValueFirstModuleField);
			      	$label2->appendChild($labelText2);
			      	$field2->appendChild($label2);

			      	$name2 = $xml->createElement("name");
			      	$nameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[1]);
			      	$name2->appendChild($nameText2);
			      	$field2->appendChild($name2);

			      	$table2 = $xml->createElement("table");
			      	$tableText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[0]);
			      	$table2->appendChild($tableText2);
			      	$field2->appendChild($table2);

			      	$columnname2 = $xml->createElement("columnname");
			      	$columnnameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[2]);
			      	$columnname2->appendChild($columnnameText2);
			      	$field2->appendChild($columnname2);
	      		}
	      }else
          {
          	$field2 = $xml->createElement("field");

	      	$label2 = $xml->createElement("label");
	      	$labelText2=$xml->createTextNode($DataDecode[$i]->temparray->DefaultValueFirstModuleField);
	      	$label2->appendChild($labelText2);
	      	$field2->appendChild($label2);

	      	$name2 = $xml->createElement("name");
	      	$nameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[1]);
	      	$name2->appendChild($nameText2);
	      	$field2->appendChild($name2);

	      	$table2 = $xml->createElement("table");
	      	$tableText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[0]);
	      	$table2->appendChild($tableText2);
	      	$field2->appendChild($table2);

	      	$columnname2 = $xml->createElement("columnname");
	      	$columnnameText2=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[2]);
	      	$columnname2->appendChild($columnnameText2);
	      	$field2->appendChild($columnname2);
          }
          
          $fields->appendChild($field2);
    }//end for

    //$root->appendChild($name);
     $root->appendChild($Fmodule);
     $root->appendChild($Secmodule);
     $root->appendChild($fields);
     $xml->formatOutput = true;
     return $xml->saveXML();   
}

function add_aray_for_history($decodedata)
 {
    //$countarray=(count($decodedata)-1);
   // $labels="";
     foreach ($decodedata as  $value)
     {
        $labels.= explode(":",$value->temparray->Firstfield)[2].",".explode(":",$value->temparray->Firstfield)[2].",";
     }
     // return $labels;
    return array
     (
        'Labels'=>$labels,
        'FirstModuleval'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstModule),
        'FirstModuletxt'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstModule),
        'SecondModuleval'=>trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",  $decodedata[0]->temparray->secmodule)[0])))),
        'SecondModuletxt'=>trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",  $decodedata[0]->temparray->secmodule)[0])))),
        'firstmodulelabel'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstfieldID),
        'secondmodulelabel'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->SecondfieldID)
     );
 }

// function emptyStr($str) {
//     return is_string($str) && strlen($str) === 0;
// }


function Check_table_if_exist($tableName,$primaryIds="")
{
     global $adb;
    $exist=$adb->query_result($adb->query("SHOW TABLES LIKE '$tableName'"),0,0);
    if (strlen($exist)==0)
     {
         $createTable="
                CREATE TABLE `$tableName` (
                  `id` varchar(250) NOT NULL,
                  `firstmodule` varchar(250) NOT NULL,
                  `firstmoduletext` varchar(250) NOT NULL,
                  `secondmodule` varchar(250) NOT NULL,
                  `secondmoduletext` varchar(250) NOT NULL,
                  `query` text NOT NULL,
                  `sequence` int(11) NOT NULL,
                  `active` varchar(2) NOT NULL,
                  `firstmodulelabel` varchar(250) DEFAULT NULL,
                  `secondmodulelabel` varchar(250) DEFAULT NULL,
                  `labels` text NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
            ";
            if (strlen($primaryIds)>0) {
                $createTable.="
                    ALTER TABLE `$tableName`
                      ADD PRIMARY KEY ($primaryIds);
                    COMMIT;

                ";
            }
         //return $createTable;
         $adb->query("DROP TABLE IF EXISTS `$tableName`");
         $adb->query($createTable);
        

     }else
     {
        return strlen($exist);
     }

 
     if (strlen($adb->query_result($adb->query("SHOW TABLES LIKE '$tableName'"),0,0))>0) 
     {
        return 1;    
     }else
     {
        return 0;
     }

}


function save_history($datas,$queryid,$xmldata){
        global $adb;
        $idquery=$queryid;

        if(strlen($idquery)>0)
        {
              
               $q=$adb->query("select sequence from mapgeneration_queryhistory where id='$idquery' order by sequence DESC");
             //$nr=$adb->num_rows($q);
             // echo "q=".$q;
              $seq=$adb->query_result($q,0,0)+1;
             $adb->query("update mapgeneration_queryhistory set active=0 where id='$idquery'");                            
              //$seqmap=count($data);
             $adb->pquery("insert into mapgeneration_queryhistory values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,$seq,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));            
              //return $idquery;
        }else 
        {

            $idquery=md5(date("Y-m-d H:i:s").uniqid(rand(), true));
            $adb->pquery("insert into mapgeneration_queryhistory values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,$seq,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));
        }
       echo $idquery;    
}