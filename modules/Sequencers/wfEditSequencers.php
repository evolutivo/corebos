<?php
/*************************************************************************************************
 * Copyright 2011-2013 TSolucio  --  This file is a part of vtMktDashboard.
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
*  Module       : Sequencers
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
global $currentModule;

$respuesta=array();
$qemp="select sequencersid,reference
 from vtiger_sequencers
 inner join vtiger_crmentity on crmid=sequencersid
 where deleted=0 and sequencers_status='Active'";
if (isset($_REQUEST['sort'])) {
	$qemp.=' order by ';
	$ob='';
	foreach ($_REQUEST['sort'] as $sf) {
		if ($sf['field']=='apellidos') {
			$ob.='lastname1 '.$sf['dir'].',lastname2 '.$sf['dir'].',';
		} else {
			$ob.=$sf['field'].' '.$sf['dir'].',';
		}
	}
	$qemp.=trim($ob,',');
}
if (isset($_REQUEST['page']) and isset($_REQUEST['pageSize'])) {
	$qemp.=' limit '.(($_REQUEST['page']-1)*$_REQUEST['pageSize']).', '.$_REQUEST['pageSize'];
}
$rsemp=$adb->query($qemp);
while ($emp=$adb->fetch_array($rsemp)) {
	$respuesta[]=array(
			'sequencersid'=>$emp['sequencersid'],
			'reference'=>$emp['reference'],
			);
}

echo json_encode($respuesta);
