<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : Pivottable
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/

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
                $modid=  getTabid($mod);
                $fldname=$fl[1];
                
                include("modules/$mod/language/it_it.lang.php");
                $key2=$mod_strings["$key1[1]"];
                if($key2==''){
                    $key22=str_replace(" ","_",$key1[1]);
                    $key2=$mod_strings["$key22"];
                }
                if($key2=='')
                { 
                    $key2=$key1[1];
                }
                //$value = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',$value);
                $value = str_replace("\n", "", $value);
                $value = str_replace("\r", "", $value);
                $a=$adb->query("SELECT *
                    from vtiger_field
                    WHERE ( columnname='$fldname' OR fieldname='$fldname' )"
                    . " and tabid = '$modid' ");
                $uitype=$adb->query_result($a,0,'uitype');
                $fieldname=$adb->query_result($a,0,'fieldname');
                $col_fields[$fieldname]=$value;
                $block_info=getDetailViewOutputHtml($uitype,$fieldname,'',$col_fields,'','',$mod);
                $ret_val=$block_info[1];
                if(strpos($ret_val,'href')!==false) //if contains link remmove it because ng can't interpret it
                {
                  $pos1=strpos($ret_val,'>');
                  $first_sub=substr($ret_val,$pos1+1);
                  $pos2=strpos($first_sub,'<');

                  $sec_sub=substr($first_sub,0,$pos2);
                  $ret_val=$sec_sub;
                }
                $value=$ret_val;
                
                $count++;
                $record[$key2]=$value;
            }
            $row_count++;
            $records_all[]=$record;
        }

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

    $transLabel='';
    $query1=$adb->pquery("SELECT fieldlabel from  vtiger_scripts
          where id = ? ",array($rep));
    $fieldlabel=$adb->query_result($query1,0,'fieldlabel');
    if($fieldlabel!='')
    {
        $transLabel=explode(',',$fieldlabel);
    }
    if($total = $adb->num_rows($data_sql)) { //See if there is anything in the query
        $json_str .= "[\n";
        $records_all=array();

        $row_count = 0;  
       // var_dump($adb->fetchByAssoc($data_sql));
        while($data=$adb->fetchByAssoc($data_sql)) {
            $count = 0;
            $record=array();
            foreach($data as $key => $value) {
                $key2=$key;
                if($transLabel!=''){
                    $key2=$transLabel[$count];
                }
                $value = str_replace("\n", "", $value);
                $value = str_replace("\r", "", $value);
                $record[$key2]=$value;
                $count++;
            }
            $records_all[]=$record;
        }
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
