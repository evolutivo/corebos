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
require_once('modules/com_vtiger_workflow/VTEntityCache.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');
require_once('modules/com_vtiger_workflow/VTSimpleTemplate.inc');

class VTSequencersTask extends VTTask {
	public $executeImmediately = true; 

	public function getFieldNames(){
		return array('listofseqs','addrel');
	}

	public function doTask($entity) {
		global $adb,$default_charset,$log;
		if ($this->addrel==2) { // del all
			$this->listofseqs = '';
			$rsseq = $adb->query('select sequencersid from vtiger_sequencers inner join vtiger_crmentity on crmid=sequencersid where deleted=0');
			while ($seq = $adb->fetch_array($rsseq)) {
				$this->listofseqs = $seq['sequencersid'].',';
			}
			trim($this->listofseqs,',');
			$this->addrel = 0; // delete relation
		}
		list($cto,$cto_id) = explode('x',$entity->getId());
		$setype = getSalesEntityType($cto_id);
		if (!empty($cto_id) and !empty($this->listofseqs)) {
			require_once('modules/Sequencers/Sequencers.php');
			$sequencers = new Sequencers();
			$seqs = explode(',', $this->listofseqs);
			foreach ($seqs as $seqid) {
				if ($this->addrel) {
					$sequencers->save_related_module('Sequencers', $seqid, $setype, $cto_id);
				} else {
					$sequencers->delete_related_module('Sequencers', $seqid, $setype, $cto_id);
				}
			}
		}
	}
}
?>
