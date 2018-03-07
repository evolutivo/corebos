
<?php
/*
 * @Author: Edmond Kacaj 
 * @Date: 2018-03-07 15:13:39 
 * @Last Modified by: programim95@gmail.com
 * @Last Modified time: 2018-03-07 17:21:45
 */
//saveWebServiceMap.php


include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');
require_once ('All_functions.php');
require_once ('Staticc.php');


global $root_directory, $log; 
$Data = array();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "WS"; // stringa con tutti i campi scelti in selField1
$SaveasMapText = $_POST['SaveasMapText'];
$Data = $_POST['ListData'];
$MapID=explode(',', $_REQUEST['savehistory']); 
$mapname=(!empty($SaveasMapText)? $SaveasMapText:$MapName);
$idquery2=!empty($MapID[0])?$MapID[0]:md5(date("Y-m-d H:i:s").uniqid(rand(), true));


if (empty($SaveasMapText)) {
     if (empty($MapName)) {
            echo "Missing the name of map Can't save";
            return;
       }
}
if (empty($MapType))
 {
    $MapType = "WS";
}

if (!empty($Data)) {
	
    $jsondecodedata=json_decode($Data);	
    // print_r($jsondecodedata);
    // print_r(add_content($jsondecodedata));
    // exit();
    // $myDetails=array();
	if(strlen($MapID[1]==0)){
	   $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     // $focust->column_fields['mapname'] = $jsondecodedata[0]->temparray->FirstModule."_ListColumns";
     $focust->column_fields['mapname']=$mapname;
     $focust->column_fields['content']=add_content($jsondecodedata);
     $focust->column_fields['maptype'] =$MapType;
     $focust->column_fields['targetname'] =$jsondecodedata[0]->temparray->FirstModule;
     $focust->column_fields['description']= add_content($jsondecodedata);
     $focust->column_fields['mvqueryid']=$idquery2;
     $log->debug(" we inicialize value for insert in database ");
     if (!$focust->saveentity("cbMap"))//
      {
      		
          if (Check_table_if_exist(TypeOFErrors::Tabele_name)>0) {
                 echo save_history(add_aray_for_history($jsondecodedata),$idquery2,add_content($jsondecodedata)).",".$focust->id;
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

    }else{

     include_once ("modules/cbMap/cbMap.php");
     $focust = new cbMap();
     $focust->id = $MapID[1];
     $focust->retrieve_entity_info($MapID[1],"cbMap");
     $focust->column_fields['assigned_user_id'] = 1;
     // $focust->column_fields['mapname'] = $MapName;
     $focust->column_fields['content']=add_content($jsondecodedata);
     $focust->column_fields['maptype'] =$MapType;
     $focust->column_fields['mvqueryid']=$idquery2;
     $focust->column_fields['targetname'] =$jsondecodedata[0]->temparray->FirstModule;
     $focust->column_fields['description']= add_content($jsondecodedata);
     $focust->mode = "edit";
     $focust->save("cbMap");

          if (Check_table_if_exist(TypeOFErrors::Tabele_name)>0) {
                 echo save_history(add_aray_for_history($jsondecodedata),$idquery2,add_content($jsondecodedata)).",".$MapID[1];
             } 
             else{
                echo "0,0";
                 $log->debug("Error!! MIssing the history Table");
             }
    }

}


/**
 * function to convert to xml the array come from post 
 *
 * @param      <type>  $DataDecode  The data decode
 * @param      DataDecode  {Array}  {This para is a array }
 *
 * @return     <type>  ( description_of_the_return_value )
 */
function add_content($DataDecode)
{
    $countarray=(count($DataDecode)-1);
    $configuration=false;
    $Header=false;
    $Input=false;
    $Output=false;
    $ValueMap=false;
    $Errorhandler=false;
    $xml = new DOMDocument("1.0");
    $root = $xml->createElement("map");
    $xml->appendChild($root);   
    
    // for configuration
    $wsconfigtag=$xml->createElement("wsconfig");
    foreach ($DataDecode as $value) {

        if($value->temparray->JsonType == "Configuration")
        {
            
            $wsurl = $xml->createElement("wsurl");
            $wsurltext = $xml->createTextNode( $value->temparray->{'fixed-text-addon-pre'}.$value->temparray->{'url-input'});
            $wsurl->appendChild($wsurltext);
            $wsconfigtag->appendChild($wsurl);

            $wshttpmethod = $xml->createElement("wshttpmethod");
            $wshttpmethodtext = $xml->createTextNode($value->temparray->urlMethod);
            $wshttpmethod->appendChild($wshttpmethodtext);
            $wsconfigtag->appendChild($wshttpmethod);

            $wsresponsetime = $xml->createElement("wsresponsetime");
            $wsresponsetimeText = $xml->createTextNode($value->temparray->{'ws-response-time'});
            $wsresponsetime->appendChild($wsresponsetimeText);
            $wsconfigtag->appendChild($wsresponsetime);

            $wsuser = $xml->createElement("wsuser");
            $wsuserText = $xml->createTextNode($value->temparray->{'ws-user'});
            $wsuser->appendChild($wsuserText);
            $wsconfigtag->appendChild($wsuser);
            

            $wspass = $xml->createElement("wspass");
            $wspassText = $xml->createTextNode($value->temparray->{'ws-password'});
            $wspass->appendChild($wspassText);
            $wsconfigtag->appendChild($wspass);

            $wsproxyhost = $xml->createElement("wsproxyhost");
            $wsproxyhostText = $xml->createTextNode($value->temparray->{'ws-proxy-host'});
            $wsproxyhost->appendChild($wsproxyhostText);
            $wsconfigtag->appendChild($wsproxyhost);

            $wsproxyport = $xml->createElement("wsproxyport");
            $wsproxyportText = $xml->createTextNode($value->temparray->{'ws-proxy-port'});
            $wsproxyport->appendChild($wsproxyportText);
            $wsconfigtag->appendChild($wsproxyport);

            $wsstarttag = $xml->createElement("wsstarttag");
            $wsstarttagText = $xml->createTextNode($value->temparray->{'ws-start-tag'});
            $wsstarttag->appendChild($wsstarttagText);
            $wsconfigtag->appendChild($wsstarttag);

            $wstype = $xml->createElement("wstype");
            $wstypeText = $xml->createTextNode("");
            $wstype->appendChild($wstypeText);
            $wsconfigtag->appendChild($wstype);

            $inputtype = $xml->createElement("inputtype");
            $inputtypeText = $xml->createTextNode($value->temparray->{'ws-input-type'});
            $inputtype->appendChild($inputtypeText);
            $wsconfigtag->appendChild($inputtype);

            $outputtype = $xml->createElement("outputtype");
            $outputtypeText = $xml->createTextNode($value->temparray->{'ws-output-type'});
            $outputtype->appendChild($outputtypeText);
            $wsconfigtag->appendChild($outputtype);

            $configuration=true;
        }
       }
     //for header
     $wsheadertag=$xml->createElement("wsheader");
     foreach ($DataDecode as $value) {

        if($value->temparray->JsonType == "Header")
        {
            $headertag=$xml->createElement("header");

            $keyname = $xml->createElement("keyname");
            $keynameText = $xml->createTextNode($value->temparray->{'ws-key-name'});
            $keyname->appendChild($keynameText);
            $headertag->appendChild($keyname);

            $keyvalue = $xml->createElement("keyvalue");
            $keyvalueText = $xml->createTextNode($value->temparray->{'ws-key-value'});
            $keyvalue->appendChild($keyvalueText);
            $headertag->appendChild($keyvalue);

            $wsheadertag->appendChild($headertag);

            $Header=true;
        }        
    }
    if($Header==true){$wsconfigtag->appendChild($wsheadertag); }
    

    // for input 
    $inputtag=$xml->createElement("input");
    $fieldstag=$xml->createElement("fields");
    foreach ($DataDecode as $value) {

        if($value->temparray->JsonType == "Input")
        {
            $fieldtag=$xml->createElement("field");

            $fieldname = $xml->createElement("fieldname");
            if(!empty($value->temparray->{'InputFieldsName'}))
            {
                $fieldnameText = $xml->createTextNode(explode(":",$value->temparray->{'InputFieldsName'})[2]);
            }else
            {
                $fieldnameText = $xml->createTextNode($value->temparray->{'input-name-input'});
            }
            $fieldname->appendChild($fieldnameText);
            $fieldtag->appendChild($fieldname);

            $fieldvalue = $xml->createElement("fieldvalue");
            if(!empty($value->temparray->{'InputFieldsValue'}))
            {
                $fieldvalueText = $xml->createTextNode(explode(":",$value->temparray->{'InputFieldsValue'})[2]);
            }else
            {
                $fieldvalueText = $xml->createTextNode($value->temparray->{'input-value-value'});
            }
            $fieldvalue->appendChild($fieldvalueText);
            $fieldtag->appendChild($fieldvalue);

            $attribute = $xml->createElement("attribute");
            $attributeText = $xml->createTextNode($value->temparray->{'ws-input-attribute'});
            $attribute->appendChild($attributeText);
            $fieldtag->appendChild($attribute);

            $origin = $xml->createElement("origin");
            $originText = $xml->createTextNode($value->temparray->{'ws-input-Origin'});
            $origin->appendChild($originText);
            $fieldtag->appendChild($origin);

            $format = $xml->createElement("format");
            $formatText = $xml->createTextNode($value->temparray->{'ws-input-format'});
            $format->appendChild($formatText);
            $fieldtag->appendChild($format);

            $default = $xml->createElement("default");
            $defaultText = $xml->createTextNode($value->temparray->{'ws-input-default'});
            $default->appendChild($defaultText);
            $fieldtag->appendChild($default);

            
            $fieldstag->appendChild($fieldtag);

            $Input=true;
        }        
    }
    $inputtag->appendChild($fieldstag);

    /// output tag 

    $Outputtag=$xml->createElement("Output");
    $fieldstag=$xml->createElement("fields");
    foreach ($DataDecode as $value) {

        if($value->temparray->JsonType == "Output")
        {
            $fieldtag=$xml->createElement("field");

            $fieldlabel = $xml->createElement("fieldlabel");
            $fieldlabelText = $xml->createTextNode($value->temparray->{'ws-label'});
            $fieldlabel->appendChild($fieldlabelText);
            $fieldtag->appendChild($fieldlabel);


            $fieldname = $xml->createElement("fieldname");
            if(!empty($value->temparray->{'OutputFieldsName'}))
            {
                $fieldnameText = $xml->createTextNode(explode(":",$value->temparray->{'OutputFieldsName'})[2]);
            }else
            {
                $fieldnameText = $xml->createTextNode($value->temparray->{'output-name-input'});
            }
            $fieldname->appendChild($fieldnameText);
            $fieldtag->appendChild($fieldname);

            $fieldvalue = $xml->createElement("fieldvalue");
            if(!empty($value->temparray->{'OutputFieldsValue'}))
            {
                $fieldvalueText = $xml->createTextNode(explode(":",$value->temparray->{'OutputFieldsValue'})[2]);
            }else
            {
                $fieldvalueText = $xml->createTextNode($value->temparray->{'output-value-value'});
            }
            $fieldvalue->appendChild($fieldvalueText);
            $fieldtag->appendChild($fieldvalue);           

            $attribute = $xml->createElement("attribute");
            $attributeText = $xml->createTextNode($value->temparray->{'ws-output-attribute'});
            $attribute->appendChild($attributeText);
            $fieldtag->appendChild($attribute);
            
            $fieldstag->appendChild($fieldtag);

            $Output=true;
        }        
    }
    $Outputtag->appendChild($fieldstag);


     /// Value Map tag 

     $valuemaptag=$xml->createElement("valuemap");
     $fieldstag=$xml->createElement("fields");
     foreach ($DataDecode as $value) {
 
         if($value->temparray->JsonType == "Value Map")
         {
             $fieldtag=$xml->createElement("field");
 
             $fieldname = $xml->createElement("fieldname");
             $fieldnameText = $xml->createTextNode($value->temparray->{'ws-value-map-name'});
             $fieldname->appendChild($fieldnameText);
             $fieldtag->appendChild($fieldname);
 
             $fieldsrc = $xml->createElement("fieldsrc");
             $fieldsrcText = $xml->createTextNode($value->temparray->{'ws-value-map-source-input'});
             $fieldsrc->appendChild($fieldsrcText);
             $fieldtag->appendChild($fieldsrc);           
 
             $fielddest = $xml->createElement("fielddest");
             $fielddestText = $xml->createTextNode($value->temparray->{'ws-value-map-destinamtion'});
             $fielddest->appendChild($fielddestText);
             $fieldtag->appendChild($fielddest);
             
             $fieldstag->appendChild($fieldtag);

             $ValueMap=true;
         }        
     }
     $valuemaptag->appendChild($fieldstag);
    
     /// Value Map tag 

     $errorhandlertag=$xml->createElement("errorhandler");
     foreach ($DataDecode as $value) {
 
         if($value->temparray->JsonType == "Error Handler")
         {
             $fieldtag=$xml->createElement("field");
 
             $fieldname = $xml->createElement("fieldname");
             $fieldnameText = $xml->createTextNode($value->temparray->{'ws-error-name'});
             $fieldname->appendChild($fieldnameText);
             $fieldtag->appendChild($fieldname);
 
             $value = $xml->createElement("value");
             $valueText = $xml->createTextNode($value->temparray->{'ws-error-value'});
             $value->appendChild($valueText);
             $fieldtag->appendChild($value);           
 
             $errormessage = $xml->createElement("errormessage");
             $errormessageText = $xml->createTextNode($value->temparray->{'ws-error-message'});
             $errormessage->appendChild($errormessageText);
             $fieldtag->appendChild($errormessage);
             
             $errorhandlertag->appendChild($fieldtag);

             $Errorhandler=true;
             break;
         }  
               
     }
     $valuemaptag->appendChild($fieldstag);
    
    $root->appendChild($wsconfigtag);
    if($Input==true){$root->appendChild($inputtag); }
    if($Output==true){$root->appendChild($Outputtag);}
    if($ValueMap==true){ $root->appendChild($valuemaptag);}
    if($Errorhandler==true){$root->appendChild($errorhandlertag);}
    $xml->formatOutput = true;
    return $xml->saveXML();
}







function add_aray_for_history($decodedata)
{
    return array
     (
        'Labels'=>"",
        'FirstModuleval'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstModule),
        'FirstModuletxt'=>preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstModuleText),
        'SecondModuleval'=>"",
        'SecondModuletxt'=>"",
        'firstmodulelabel'=>getModuleID(preg_replace('/\s+/', '',$decodedata[0]->temparray->FirstModule)),
        'secondmodulelabel'=>""
     );
}

/**
 * save history is a function which save in db the history of map 
 * @param  [array] $datas   array 
 * @param  [type] $queryid the id of qquery
 * @param  [type] $xmldata the xml data 
 * @return [type]          boolean true or false 
 */
function save_history($datas,$queryid,$xmldata){
        global $adb;
        $idquery2=$queryid;
        $q=$adb->query("select sequence from ".TypeOFErrors::Tabele_name." where id='$idquery2' order by sequence DESC");
             //$nr=$adb->num_rows($q);
             // echo "q=".$q;
             
        $seq=$adb->query_result($q,0,0);
      
        if(!empty($seq))
        {
            $seq=$seq+1;
             $adb->query("update ".TypeOFErrors::Tabele_name." set active=0 where id='$idquery2'");                            
              //$seqmap=count($data);
             $adb->pquery("insert into ".TypeOFErrors::Tabele_name." values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery2,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,$seq,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));
              //return $idquery;
        }else 
        {

            $adb->pquery("insert into ".TypeOFErrors::Tabele_name." values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery2,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,1,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));
        }
        echo $idquery2;
}






