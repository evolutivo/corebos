<?php
function wfRendicontaStato($entity)
{
 global $log, $adb;
 $orig_pot = $entity->getId();
 $log->debug("orig_pot value is: ".$orig_pot);
 $orig_id = explode('x',$orig_pot); //Il formato e' del tipo 5x111
 $projectid = $orig_id[1];
 require_once('modules/Project/RendicontaAction.php');

 $query=$adb->pquery("select statopraticacorriere 
      from vtiger_project 
      where projectid=? ",array($projectid));
 $statopraticacorriere=$adb->query_result($query,0,'statopraticacorriere');
 $data_to_be_passed=array('recordid'=>$projectid,'statopraticacorriere'=>$statopraticacorriere);
 RendicontaAction($data_to_be_passed);
}