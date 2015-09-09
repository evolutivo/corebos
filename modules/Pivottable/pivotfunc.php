<?php
 function sqltojson($indextype,$rep) {
            global $adb;
            $ip=GlobalVariable::getVariable('ip_elastic_server', '');
$endpointUrl = "http://$ip:9200/$indextype/norm/_search?pretty&size=12"; 
$channel1 = curl_init();
curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel1, CURLOPT_POST, false);
//curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
//curl_setopt($channel1, CURLOPT_POSTFIELDS, json_encode($fields1));
curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
$response1 = json_decode(curl_exec($channel1));

//var_dump($response1);
            //    make the error a js comment so that a javascript error will NOT be invoked
    $json_str = ""; //Init the JSON string.

    if($total = $response1->hits->total) { //See if there is anything in the query
        $json_str .= "[\n";
        $records_all=array();
        $arr=array();
        $arr1=array();
//echo $total;
//echo ' '.$query;
        $row_count = 0;  
        $max=0;
         foreach ($response1->hits->hits as $row) {
            if($response1->hits->total > 1) 
            {
                $json_str .= "{\n";
            }
            $count = 0;
            $record=array();
            $data=$row->_source;
 

            foreach($data as $key => $value) {

	
                if($key!='urlrecord'){
		 $arr[$count]=$key;
                $count++;

                $record[$key]=$value;
               
         }}
         if ($count>$max) { $max=$count;
         $arr1=$arr;
         }
      
        }
       
        
         foreach ($response1->hits->hits as $row) {
            if($response1->hits->total > 1) 
            {
                $json_str .= "{\n";
            }
            $count = 0;
            $record=array();
            $data=$row->_source;


            for($j=0;$j<count($arr1);$j++) {

            //$value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',$value);
	    $value = str_replace("\n", "", $data->$arr1[$j]);
            $value = str_replace("\r", "",$data->$arr1[$j]);
            $key=$arr1[$j];
                if($key!='urlrecord'){
		 if($response1->hits->total > 1) $json_str .= "\"$key\":\"$value\"";
                else $json_str .= "\"$value\"";

                //Make sure that the last item don't have a ',' (comma)
                $count++;

                $record[$key]=$value;
         }}
            $row_count++;
            if($response1->hits->total > 1) 
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
