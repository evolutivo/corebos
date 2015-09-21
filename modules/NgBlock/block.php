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
    $module_val= $_REQUEST['mod_value'];

    if($kaction=='retrieve'){
        $q="
          SELECT DISTINCT blockid,blocklabel,vtiger_blocks.sequence as seq  ,tablabel ,vtiger_tab.tabid as t_id
          FROM vtiger_field
          join vtiger_blocks on vtiger_blocks.blockid = vtiger_field.block
          join vtiger_tab on vtiger_blocks.tabid = vtiger_tab.tabid
          ";
      if($module_val!='')
      $q.=" where vtiger_tab.tablabel= '$module_val'"; 
      $log->debug('testing2 '.$q);
      $query=$adb->query($q);
      $count=$adb->num_rows($query);
    
      for($i=0;$i<$count;$i++){
      $content[$i]['id']=$adb->query_result($query,$i,'blockid');
      $content[$i]['label2']=getTranslatedString($adb->query_result($query,$i,'blocklabel'),$adb->query_result($query,$i,'tablabel'));
      $content[$i]['label']=$adb->query_result($query,$i,'blocklabel');
      $content[$i]['label3']=getTranslatedString($adb->query_result($query,$i,'blocklabel'),$adb->query_result($query,$i,'tablabel'));
      $content[$i]['sequence']=$adb->query_result($query,$i,'seq');
      $content[$i]['tabid']=$adb->query_result($query,$i,'t_id');
      $content[$i]['tablabel']=$adb->query_result($query,$i,'tablabel');
      }
    echo json_encode($content);
     
    }
 ?>

