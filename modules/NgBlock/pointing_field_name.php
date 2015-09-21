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
 *  Module       : NgBlock
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/
 
  global $adb,$log;
  $kaction=$_REQUEST['kaction'];
  $pointing_module=$_REQUEST['pointing_module'];
  
  $qu2='';
  $content=array();
  if($kaction=='retrieve'){
      
    $qu1="Select fieldid,columnname,fieldlabel,fieldname,vtiger_field.tabid,tablabel 
         from vtiger_field
         join vtiger_tab on vtiger_field.tabid = vtiger_tab.tabid
            ";
    $tabid=  getTabid($pointing_module);
    $qu1=$qu1." where uitype in (10,51,50,73,68,57,59,58,76,75,81,78,80)
        ";   
    $query=$adb->query($qu1);
    $count=$adb->num_rows($query);
   for($i=0;$i<$count;$i++){
      $content[$i]['fieldid']=$adb->query_result($query,$i,'fieldid');
      $content[$i]['columnname']=$adb->query_result($query,$i,'columnname');
      $content[$i]['fieldlabel']=  getTranslatedString($adb->query_result($query,$i,'fieldlabel'),$pointing_module);
      $content[$i]['fieldname']=$adb->query_result($query,$i,'fieldname');
      $content[$i]['tabid']=$adb->query_result($query,$i,'tabid');
      $content[$i]['tablabel']=$adb->query_result($query,$i,'tablabel');
      }
    echo json_encode($content);
    
    }
 ?>
