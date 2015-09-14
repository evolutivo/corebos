<?php
 function sqltojson($query,$rep) {
            global $adb;
    $data_sql = $adb->query($query) ;// If an error has occurred, 
            //    make the error a js comment so that a javascript error will NOT be invoked
    $json_str = ""; //Init the JSON string.

    if($total = $adb->num_rows($data_sql)) { //See if there is anything in the query
        $json_str .= "[\n";
        $records_all=array();

        $row_count = 0;  
       // var_dump($adb->fetchByAssoc($data_sql));
        while($data=$adb->fetchByAssoc($data_sql)) {
            if(count($data) > 1) 
            {
                $json_str .= "{\n";
            }
            $count = 0;
            $record=array();
            foreach($data as $key => $value) {
                //If it is an associative array we want it in the format of "key":"value"
              
            $q1=$adb->query("select * from vtiger_selectcolumn where queryid=$rep and columnname not like 'vtiger_crmentity:crm%' and columnname like '%$key%'");
            $fl=explode(":",$adb->query_result($q1,0,'columnname'));
            $key1=explode(" ",str_replace(array("_"),array(" "),$fl[2]),2);
            $mod=$key1[0];
            include("modules/$mod/language/it_it.lang.php");
            $key2=$mod_strings["$key1[1]"];
            if($key2==''){
            $key22=str_replace(" ","_",$key1[1]);
            $key2=$mod_strings["$key22"];
            }
            if($key2=='')
            { $key2=$key1[1];
            }

            //$value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',$value);
	    $value = str_replace("\n", "", $value);
            $value = str_replace("\r", "", $value);
		 if(count($data) > 1) $json_str .= "\"$key2\":\"$value\"";
                else $json_str .= "\"$value\"";

                //Make sure that the last item don't have a ',' (comma)
                $count++;
                if($count < count($data)) $json_str .= ",\n";
                $record[$key2]=$value;
            }
            $row_count++;
            if(count($data) > 1) 
            {
                $json_str .= "}\n";
            }

            //Make sure that the last item don't have a ',' (comma)
            if($row_count < $total) 
            {
                $json_str .= ",\n";
            }
            $records_all[]=$record;
        }

        $json_str .= "]\n";
    }

    //Replace the '\n's - make it faster - but at the price of bad redability.
    // $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
    // echo $json_str;
    //Finally, output the data
    return json_encode($records_all);//$json_str;
}

function sqltojson_mv($query,$rep) {
            global $adb;
    $data_sql = $adb->query($query) ;// If an error has occurred, 
            //    make the error a js comment so that a javascript error will NOT be invoked
    $json_str = ""; //Init the JSON string.

    if($total = $adb->num_rows($data_sql)) { //See if there is anything in the query
        $json_str .= "[\n";
        $records_all=array();

        $row_count = 0;  
       // var_dump($adb->fetchByAssoc($data_sql));
        while($data=$adb->fetchByAssoc($data_sql)) {
            if(count($data) > 1) 
            {
                $json_str .= "{\n";
            }
            $count = 0;
            $record=array();
            foreach($data as $key => $value) {
                //If it is an associative array we want it in the format of "key":"value"
              
            
            $key2=$key;

            //$value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',$value);
	    $value = str_replace("\n", "", $value);
            $value = str_replace("\r", "", $value);
		 if(count($data) > 1) $json_str .= "\"$key2\":\"$value\"";
                else $json_str .= "\"$value\"";

                //Make sure that the last item don't have a ',' (comma)
                $count++;
                if($count < count($data)) $json_str .= ",\n";
                $record[$key2]=$value;
            }
            $row_count++;
            if(count($data) > 1) 
            {
                $json_str .= "}\n";
            }

            //Make sure that the last item don't have a ',' (comma)
            if($row_count < $total) 
            {
                $json_str .= ",\n";
            }
            $records_all[]=$record;
        }

        $json_str .= "]\n";
    }

    //Replace the '\n's - make it faster - but at the price of bad redability.
    // $json_str = str_replace("\n","",$json_str); //Comment this out when you are debugging the script
    // echo $json_str;
    //Finally, output the data
    return json_encode($records_all);//$json_str;
}

function createjson($sql,$id){
$fp = fopen('report'.$id.'.json', 'w');
fwrite($fp, $sql);
fclose($fp);
}
?>
