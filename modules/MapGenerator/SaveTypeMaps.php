<?php
 
include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');

global $root_directory, $log; 
$Data = array();

//  var_dump($_REQUEST, true);
// exit();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Mapping"; // stringa con tutti i campi scelti in selField1
$Data = $_POST['ListData']; // nome della vista
if (empty($MapName)) {
    echo "Missing the name of map Can't save";
    return;
}
if (empty($MapType)) {
    $MapType = "Mapping";
}
 
 if (!empty($Data)) {
     
     
     include_once('modules/cbMap/cbMap.php');
     $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     $focust->column_fields['mapname'] = $MapName;
     $focust->column_fields['content']=add_content($Data);
     $focust->column_fields['maptype'] =$MapType;
     $focust->column_fields['description']= add_description($Data);
     $log->debug(" we inicialize value for insert in database ");
     if (!$focust->saveentity("cbMap"))
      {
         //echo $focust->id;
         echo "succes!! the map is created ";
         $log->debug("succes!! the map is created ");
      } else 
      {
         //echo focus->id;
         echo "Error!! something went wrong";
         $log->debug("Error!! something went wrong");
      }
     
    //     $ddd=$xml->saveXML();
    //     $fp = fopen('C:\Users\Edmondi\Desktop\prova.xml', 'w');
    //     fwrite($fp, $ddd);   
    //     fwrite($fp, "I=".$hw); 
    //     fclose($fp);
   
   
}//end of if (! empty($Data)) 

function add_content($dat)
{
     $DataDecode = json_decode($dat, true);
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
     $targetnameText = $xml->createTextNode( trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",  $DataDecode[0]['SecondModuleval'])[0])))));
     $targetname->appendChild($targetnameText);
     $target->appendChild($targetid);
     $target->appendChild($targetname);
     
     $origin = $xml->createElement("targetmodule");
     $originid = $xml->createElement("targetid");
     $originText = $xml->createTextNode("");
     $originid->appendChild($originText);
     $originname = $xml->createElement("targetname");
     $originnameText = $xml->createTextNode(preg_replace('/\s+/', '',$DataDecode[0]['FirstModuleval']));
     $originname->appendChild($originnameText);
     $origin->appendChild($originid);
     $origin->appendChild($originname);
     $fields = $xml->createElement("fields");
    // $hw=0;
     for($i=0;$i<=$countarray;$i++){
         //get target field name
       
                 $field = $xml->createElement("field");
                 $fieldname = $xml->createElement("fieldname");
                 if (preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[1])==="smownerid") {
                   $fieldnameText = $xml->createTextNode(preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[2]));    
                 }else{
                  $fieldnameText = $xml->createTextNode( preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[1]));   
                 }
                 
                 $fieldname->appendChild($fieldnameText);
                 $field->appendChild($fieldname);
                 
                 $fieldID = $xml->createElement("fieldID");
                 $fieldideText = $xml->createTextNode("");
                 $fieldID->appendChild($fieldideText);         
                 $field->appendChild($fieldID);
                // echo $i;
                 if ($DataDecode[$i]['SecondFieldtext']=="Default-Value")
                 {
                     $value = $xml->createElement("value");
                     $valueText = $xml->createTextNode($DataDecode[$i]['SecondFieldval']);
                     $value->appendChild($valueText);
                     $field->appendChild($value);
                 }         
                 //target module fields
                 $Orgfields = $xml->createElement("Orgfields");
                 $field->appendChild($Orgfields);
                
                 if ($DataDecode[$i]['SecondFieldtext']=="Default-Value")
                 {
                     $OrgRelfield= $xml->createElement("Orgfield");
                     
                     $OrgRelfieldName = $xml->createElement("OrgfieldName");
                     $OrgRelfieldNameText= $xml->createTextNode("");
                     $OrgRelfieldName->appendChild($OrgRelfieldNameText);
                     $OrgRelfield->appendChild($OrgRelfieldName); 
                    
                     $OrgfieldID = $xml->createElement("OrgfieldID");
                     $OrgfieldIDText= $xml->createTextNode("");
                     $OrgfieldID->appendChild($OrgfieldIDText);
                     $OrgRelfield->appendChild($OrgfieldID); 
                     
                     $Orgfields->appendChild($OrgRelfield);
                    
                 }else
                 {
                     $OrgRelfield= $xml->createElement("Orgfield");
                     
                     $OrgRelfieldName = $xml->createElement("OrgfieldName");
                     $OrgRelfieldNameText= $xml->createTextNode(preg_replace('/\s+/','', explode(":",$DataDecode[$i]['SecondFieldval'])[2]));
                     $OrgRelfieldName->appendChild($OrgRelfieldNameText);
                     $OrgRelfield->appendChild($OrgRelfieldName);
                     
                     $OrgfieldID = $xml->createElement("OrgfieldID");
                     $OrgfieldIDText= $xml->createTextNode("");
                     $OrgfieldID->appendChild($OrgfieldIDText);
                     $OrgRelfield->appendChild($OrgfieldID);
                     
                     $Orgfields->appendChild($OrgRelfield);
                 }
                 
                 
                 $del = $xml->createElement("delimiter");
                 $delText= $xml->createTextNode("");
                 $del->appendChild($delText);
                 $Orgfields->appendChild($del);
                 $fields->appendChild($field);
         }
        
       
        
     
     //$root->appendChild($name);
     $root->appendChild($target);
     $root->appendChild($origin);
     $root->appendChild($fields);
     $xml->formatOutput = true;
    return $xml->saveXML();

}




function add_description($datades){
    
    $DataDecode = json_decode($datades, true);
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
    $FmodulenameText=$xml->createTextNode(preg_replace('/\s+/', '',$DataDecode[0]['FirstModuleval']));
    $Fmodulename->appendChild($FmodulenameText);

    $Fmodule->appendChild($Fmoduleid);
    $Fmodule->appendChild($Fmodulename);

    //second module
    $Secmodule = $xml->createElement("Secmodule");

    $Secmoduleid = $xml->createElement("SecmoduleID");
    $SecmoduleText = $xml->createTextNode(preg_replace('/\s+/','',explode(";",$DataDecode[0]['SecondModuleval'])[1]));
    $Secmoduleid->appendChild($SecmoduleText);     
    $Secmodulename=$xml->createElement("Secmodulename");     
    $SecmodulenameText=$xml->createTextNode( trim(preg_replace('/\s*\([^)]*\)/', '',preg_replace("(many)",'', preg_replace('/\s+/', '', explode(";",  $DataDecode[0]['SecondModuleval'])[0])))));     
    $Secmodulename->appendChild($SecmodulenameText);    
    $Secmodule->appendChild($Secmoduleid);
    $Secmodule->appendChild($Secmodulename);     
    $fields = $xml->createElement("fields");
    
    for ($i=0; $i <=$countarray ; $i++) { 
        
         $field = $xml->createElement("field");
         $fieldname = $xml->createElement("fieldID");
         if (preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[1])==="smownerid")
          {
            $fieldnameText = $xml->createTextNode(preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[2]));    
          }else
          {
              $fieldnameText = $xml->createTextNode( preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['FirstFieldval'])[1]));
          }

          $fieldname->appendChild($fieldnameText);
          $field->appendChild($fieldname);
          $fieldID = $xml->createElement("fieldname");
          $fieldideText = $xml->createTextNode($DataDecode[$i]['FirstFieldtxt']);
          $fieldID->appendChild($fieldideText);         
          $field->appendChild($fieldID);
          
          $field2 = $xml->createElement("field");
          if ($DataDecode[$i]['SecondFieldtext']=="Default-Value")
          {
             $Dfieldname = $xml->createElement("Value");
             $DfieldnameText = $xml->createTextNode($DataDecode[$i]['SecondFieldval']);
             $Dfieldname->appendChild($DfieldnameText);
             $field2->appendChild($Dfieldname);
             $DfieldID = $xml->createElement("fieldname");
             $DfieldideText = $xml->createTextNode($DataDecode[$i]['SecondFieldtext']);
             $DfieldID->appendChild($DfieldideText);         
             $field2->appendChild($DfieldID);
              
          }else
          {
                $Sfieldname = $xml->createElement("fieldID");
                if (preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['SecondFieldval'])[1])==="smownerid")
                {
                $SfieldnameText = $xml->createTextNode( preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['SecondFieldval'])[2]));    
                }else
                {
                  $SfieldnameText = $xml->createTextNode( preg_replace('/\s+/', '',explode(":",$DataDecode[$i]['SecondFieldval'])[1]));
                }

              $Sfieldname->appendChild($SfieldnameText);
              $field2->appendChild($Sfieldname);
              $SfieldID = $xml->createElement("fieldname");
              $SfieldideText = $xml->createTextNode($DataDecode[$i]['SecondFieldtext']);
              $SfieldID->appendChild($SfieldideText);         
              $field2->appendChild($SfieldID);
          }
          $fields->appendChild($field);
          $fields->appendChild($field2);

    }//end for

    //$root->appendChild($name);
     $root->appendChild($Fmodule);
     $root->appendChild($Secmodule);
     $root->appendChild($fields);
     $xml->formatOutput = true;
     return $xml->saveXML();   
}