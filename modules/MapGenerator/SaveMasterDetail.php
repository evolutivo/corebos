<?php

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

	
    // print_r(add_aray_for_history($jsondecodedata));

	 $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     $focust->column_fields['mapname'] = $MapName;
     $focust->column_fields['content']=add_content($jsondecodedata);
     $focust->column_fields['maptype'] ="MasterDetailLayout";
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
          exit();
         //echo focus->id;
         echo "Error!! something went wrong";
         $log->debug("Error!! something went wrong");
      }
	
}



function add_content($DataDecode)
{
     //$DataDecode = json_decode($dat, true);
     $countarray=(count($DataDecode)-1);
     $xml = new DOMDocument("1.0");
     $root = $xml->createElement("map");
     $xml->appendChild($root);

     $originmodule = $xml->createElement("originmodule");
     $originmoduletxt = $xml->createTextNode(explode(";",$DataDecode[0]->temparray->secmodule)[0]);
     $originmodule->appendChild($originmoduletxt);
     $root->appendChild($originmodule);

     $target = $xml->createElement("targetmodule");
     $targettxt = $xml->createTextNode($DataDecode[0]->temparray->FirstModule);
     $target->appendChild($targettxt);
     $root->appendChild($target);

     $linkfields= $xml->createElement("linkfields");
                     
     $OrgRelfieldName = $xml->createElement("originfield");
     $OrgRelfieldNameText= $xml->createTextNode($DataDecode[0]->temparray->SecondfieldID);
     $OrgRelfieldName->appendChild($OrgRelfieldNameText);
     $linkfields->appendChild($OrgRelfieldName);
     // $root->appendChild($linkfields);

     $targetfield = $xml->createElement("targetfield");
     $targetfieldText= $xml->createTextNode($DataDecode[0]->temparray->FirstfieldID);
     $targetfield->appendChild($targetfieldText);
     $linkfields->appendChild($targetfield);

     $root->appendChild($linkfields);

     foreach ($DataDecode as $value) {
     	if ($value->temparray->sortt6ablechk==="1") {

     		 $sortfield = $xml->createElement("sortfield");
		     $sortfieldtxt = $xml->createTextNode(explode(":",$value->temparray->Firstfield)[2]);
		     $sortfield->appendChild($sortfieldtxt);
		     $root->appendChild($sortfield);
     		
     	}     	
     }

     $detailview=$xml->createElement("detailview");

      $fields=$xml->createElement("fields");

      foreach ($DataDecode as  $value) {

      	 $field = $xml->createElement("field");
      	 $fieldtype = $xml->createElement("fieldtype");
  	     $fieldtypeText= $xml->createTextNode("corebos");
  	     $fieldtype->appendChild($fieldtypeText);
  	     $field->appendChild($fieldtype);

  	     $fieldname = $xml->createElement("fieldname");
  	     $fieldnameText= $xml->createTextNode( explode(":",$value->temparray->Firstfield)[2]);
  	     $fieldname->appendChild($fieldnameText);
  	     $field->appendChild($fieldname);

  	     $editable = $xml->createElement("editable");
  	     $editableText= $xml->createTextNode( $value->temparray->editablechk);
  	     $editable->appendChild($editableText);
  	     $field->appendChild($editable);


  	     $mandatory = $xml->createElement("mandatory");
  	     $mandatoryText= $xml->createTextNode( $value->temparray->mandatorychk);
  	     $mandatory->appendChild($mandatoryText);
  	     $field->appendChild($mandatory);

  	     $hidden = $xml->createElement("hidden");
  	     $hiddenText= $xml->createTextNode( $value->temparray->mandatorychk);
  	     $hidden->appendChild($hiddenText);
  	     $field->appendChild($hidden);

  	     $fields->appendChild($field);

      }
     $detailview->appendChild($fields);
     $root->appendChild($detailview);   
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

                $label = $xml->createElement("fieldID");
                $labelText=$xml->createTextNode(explode(":",$DataDecode[$i]->temparray->Firstfield)[1]);
                $label->appendChild($labelText);
                $field->appendChild($label);

                $name = $xml->createElement("fieldname");
                $nameText=$xml->createTextNode($DataDecode[$i]->temparray->DefaultText);
                $name->appendChild($nameText);
                $field->appendChild($name);
              $fields->appendChild($field);                                      
       }
    
    

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
        $labels.= explode(":",$value->temparray->Firstfield)[2].",";
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