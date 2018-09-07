<?php
/*************************************************************************************************
 * Copyright 2014 JPL TSolucio, S.L. -- This file is a part of TSOLUCIO coreBOS Customizations.
* Licensed under the vtiger CRM Public License Version 1.1 (the "License"); you may not use this
* file except in compliance with the License. You can redistribute it and/or modify it
* under the terms of the License. JPL TSolucio, S.L. reserves all rights not expressly
* granted by the License. coreBOS distributed by JPL TSolucio S.L. is distributed in
* the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
* warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Unless required by
* applicable law or agreed to in writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT ANY WARRANTIES OR CONDITIONS OF ANY KIND,
* either express or implied. See the License for the specific language governing
* permissions and limitations under the License. You may obtain a copy of the License
* at <http://corebos.org/documentation/doku.php?id=en:devel:vpl11>
*************************************************************************************************/

class addFilterManagement extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$toinstall = array('FilterManagement');
			foreach ($toinstall as $module) {
				if ($this->isModuleInstalled($module)) {
					vtlib_toggleModuleAccess($module,true);
					$this->sendMsg("$module activated!");
				} else {
					$this->installManifestModule($module);
				}
			}
            global $adb;
				
			//populate fm with filters
			$listQuery="Select name,tabid from vtiger_tab where isentitytype=1 and presence in (0,2) and name not in ('Calendar','Webmails','Emails','Events','ModComments')";
			$query=$adb->pquery($listQuery,array());
			$count=$adb->num_rows($query);
			$modules = array();
			for($i=0;$i<$count;$i++){
				$modules[$adb->query_result($query,$i,'name')]=$adb->query_result($query,$i,'tabid');    
			}

			$content = array();
			$listQuery="Select cv.viewname,cv.cvid,cv.entitytype,cv.userid, cv.setdefault 
						from vtiger_customview cv 
						where 1";
			$query=$adb->pquery($listQuery,array());
			$count=$adb->num_rows($query);
			$content=array();
			for($i=0;$i<$count;$i++){
				$content[$i]['viewid']=$adb->query_result($query,$i,'cvid');
				$content[$i]['entityId']=$modules[$adb->query_result($query,$i,'entitytype')];
				$content[$i]['userid']=$adb->query_result($query,$i,'userid');
				$content[$i]['setdefault']=$adb->query_result($query,$i,'setdefault');

				$cvq=$adb->pquery("select * from vtiger_filtermanagement where viewid=? and roleid=0 and userid=?",array($content[$i]['viewid'],$content[$i]['userid']));
				if($adb->num_rows($cvq)==0 && !empty($content[$i]['entityId']))
				{
					$Query=$adb->pquery("Insert into vtiger_filtermanagement(viewid,editable,viewable,deletable,roleid,userid)
					values(?,?,?,?,?,?)",array($content[$i]['viewid'],'1','1','1',0,$content[$i]['userid']));
					if($content[$i]['setdefault']=='1' || $content[$i]['setdefault']==1){
						$querySelect=$adb->pquery("Select configurationid from vtiger_user_role_filters where roleid=? and userid=? and moduleid=?",array(0,$content[$i]['userid'],$content[$i]['entityId']));
						if($adb->num_rows($querySelect)==0)
						{
							$Query="Insert into vtiger_user_role_filters(roleid,userid,moduleid,second_default_cvid,first_default_cvid,cancreate) values(?,?,?,?,?,?)";
							$queryy=$adb->pquery($Query,array(0,$content[$i]['userid'],$content[$i]['entityId'],0,$content[$i]['viewid'],0));
						}
					}
				}
			}

			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			vtlib_toggleModuleAccess('FilterManagement',false);
			$this->sendMsg('FilterManagement deactivated!');
			$this->markUndone(false);
			$this->sendMsg('Changeset '.get_class($this).' undone!');
		} else {
			$this->sendMsg('Changeset '.get_class($this).' not applied, it cannot be undone!');
		}
		$this->finishExecution();
	}
	
}