<?php

//saveFieldDependency.php

include_once ("modules/cbMap/cbMap.php");
require_once ('data/CRMEntity.php');
require_once ('include/utils/utils.php');
require_once('All_functions.php');
require_once('Staticc.php');


global $root_directory, $log; 
$Data = array();

$MapName = $_POST['MapName']; // stringa con tutti i campi scelti in selField1
$MapType = "Condition Expression"; // stringa con tutti i campi scelti in selField1
$SaveasMapText = $_POST['SaveasMapText'];
// $Data = $_POST['ListData'];
$MapID=explode(',', $_REQUEST['savehistory']); 
$mapname=(!empty($SaveasMapText)? $SaveasMapText:$MapName);
$idquery2=!empty($MapID[0])?$MapID[0]:md5(date("Y-m-d H:i:s").uniqid(rand(), true));
$typecondition=!empty($_POST['TypeFunction'])?$_POST['TypeFunction']:$_POST['TypeExpresion'];

if (empty($SaveasMapText)) {
     if (empty($MapName)) {
            echo "Missing the name of map Can't save";
            return;
       }
}
if (empty($MapType))
 {
    $MapType = "Condition Expression";
}

if (!empty($typecondition)) {
	

   $myobject=new stdClass();
    foreach($_POST as $key => $value)
    {
        $myobject->{$key}=$value;       
    }
   
  if(strlen($MapID[1]==0)){

	 $focust = new cbMap();
     $focust->column_fields['assigned_user_id'] = 1;
     // $focust->column_fields['mapname'] = $jsondecodedata[0]->temparray->FirstModule."_ListColumns";
     $focust->column_fields['mapname']=$mapname;
     $focust->column_fields['content']=add_content($myobject);
     $focust->column_fields['maptype'] =$MapType;
     $focust->column_fields['targetname'] =(!empty($myobject->FirstModule))?$myobject->FirstModule:$myobject->FirstModule2;
     $focust->column_fields['description']= add_content($myobject);
     $focust->column_fields['mvqueryid']=$idquery2;
     $log->debug(" we inicialize value for insert in database ");
     if (!$focust->saveentity("cbMap"))//
      {
      		
          if (Check_table_if_exist("mapgeneration_queryhistory")>0) {
                 echo save_history(add_aray_for_history($myobject),$idquery2,add_content($myobject)).",".$focust->id;
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
     $focust->column_fields['content']=add_content($myobject);
     $focust->column_fields['maptype'] =$MapType;
     $focust->column_fields['mvqueryid']=$idquery2;
     $focust->column_fields['targetname'] =(!empty($myobject->FirstModule))?$myobject->FirstModule:$myobject->FirstModule2;
     $focust->column_fields['description']= add_content($myobject);
     $focust->mode = "edit";
     $focust->save("cbMap");

          if (Check_table_if_exist("mapgeneration_queryhistory")>0) {
                 echo save_history(add_aray_for_history($myobject),$idquery2,add_content($myobject)).",".$MapID[1];
             } 
             else{
                echo "0,0";
                 $log->debug("Error!! MIssing the history Table");
             }
  }

}


/**
 * @param DataDecode {Array} {This para is a array }
 */
function add_content($DataDecode)
{
     $xml = new DOMDocument("1.0");
     $root = $xml->createElement("map");
     $xml->appendChild($root);
    
     if (!empty($DataDecode->TypeFunction)) {
        
        $function = $xml->createElement("function");
        $name = $xml->createElement("name");
        $nameText = $xml->createTextNode($DataDecode->FunctionName);
        $name->appendChild($nameText);
        $function->appendChild($name);
        $parameters=$xml->createElement("parameters");
        
        if (!empty($DataDecode->ListData)) {
            $data=json_decode($DataDecode->ListData);
            foreach ($data as$value) {
                $parameter = $xml->createElement("parameter");
                $parameterText = $xml->createTextNode(explode(":", $value->temparray->Firstfield2)[2]);
                $parameter->appendChild($parameterText);
                $parameters->appendChild($parameter);
            }
        }else
        {
            foreach ($DataDecode as $key => $value) {
                if(strpos($key, 'DefaultValueFirstModuleField') === 0)
                {
                    if($value !== '')
                    {
                        $parameter = $xml->createElement("parameter");
                        $parameterText = $xml->createTextNode($value);
                        $parameter->appendChild($parameterText);
                        $parameters->appendChild($parameter);
                    }
                }
            }
        }
       
        $function->appendChild($parameters);
        $root->appendChild($function);
     }else
     {
       $expresion=$xml->createElement("expression");
       if (!empty($DataDecode->Firstfield)) {
           $expresionText = $xml->createTextNode(explode(":",$DataDecode->Firstfield)[2]);
       }else
       {
        $expresionText= $xml->createTextNode($DataDecode->expresion);
       }
       $expresion->appendChild($expresionText);
       $root->appendChild($expresion);
     }     
            
     $xml->formatOutput = true;
     return $xml->saveXML();
}



// function getModuleID($module,$moduleName="entityidfield")
// {
// 	global $adb,$root_directory, $log;
// 	try {

// 		$result = $adb->pquery("Select * from  vtiger_entityname where modulename = ?",array($module));
// 		$num_rows = $adb->num_rows($result);
// 		if ($num_rows>0) {
// 			$Resulti = $adb->query_result($result,0,$moduleName);

// 			if (!empty($Resulti)) {
// 				return $Resulti;
// 			} else {
// 				throw new Exception(TypeOFErrors::ErrorLG." Something was wrong RESULT IS EMPTY", 1);
// 			}
// 		} else {
// 			throw new Exception(TypeOFErrors::ErrorLG."Not exist Map with this ID=".$Queryid,1);
// 		}
// 	} catch (Exception $ex) {
// 		 $log->debug(TypeOFErrors::ErrorLG." Something was wrong check the Exception ".$ex);
// 		 return "";
// 	}
// }



function add_aray_for_history($DataDecode)
{
   if (!empty($DataDecode->TypeFunction)) {
       $label="";
        if (!empty($DataDecode->ListData)) {
            $data=json_decode($DataDecode->ListData);
            foreach ($data as $key => $value) {
               $label.=",".explode(":", $value->temparray->Firstfield2)[2];
            }
        }else{
             foreach ($DataDecode as $key => $value) {
                if(strpos($key, 'DefaultValueFirstModuleField') === 0)
                {
                    if($value !== '')
                    {
                        $label.=",".$value;
                    }
                }
            }
            
        }
        return array
         (
            'Labels'=>substr($label,1),
            'FirstModuleval'=>$DataDecode->Firstmodule2,
            'FirstModuletxt'=>$DataDecode->Firstmodule2,
            'SecondModuleval'=>"",
            'SecondModuletxt'=>"",
            'firstmodulelabel'=>"",
            'secondmodulelabel'=>""
         );
        
    } else
    {
            $labels="";              
            if (!empty($DataDecode->Firstfield)) {
                $labels.=explode(":", $DataDecode->Firstfield)[2];
            }else
            {
                $labels.=$DataDecode->expresion;
            }
            return array
             (
                'Labels'=>$labels,
                'FirstModuleval'=>$DataDecode->FirstModule,
                'FirstModuletxt'=>$DataDecode->FirstModule,
                'SecondModuleval'=>"",
                'SecondModuletxt'=>"",
                'firstmodulelabel'=>"",
                'secondmodulelabel'=>""
             );
    }     
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
        $q=$adb->query("select sequence from mapgeneration_queryhistory where id='$idquery2' order by sequence DESC");
             //$nr=$adb->num_rows($q);
             // echo "q=".$q;
             
        $seq=$adb->query_result($q,0,0);
      
        if(!empty($seq))
        {
            $seq=$seq+1;
             $adb->query("update mapgeneration_queryhistory set active=0 where id='$idquery2'");                            
              //$seqmap=count($data);
             $adb->pquery("insert into mapgeneration_queryhistory values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery2,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,$seq,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));
              //return $idquery;
        }else 
        {

            $adb->pquery("insert into mapgeneration_queryhistory values (?,?,?,?,?,?,?,?,?,?,?)",array($idquery2,$datas["FirstModuleval"],$datas["FirstModuletxt"],$datas["SecondModuletxt"],$datas["SecondModuleval"],$xmldata,1,1,$datas["firstmodulelabel"],$datas["secondmodulelabel"],$datas["Labels"]));
        }
        echo $idquery2;
}




?>