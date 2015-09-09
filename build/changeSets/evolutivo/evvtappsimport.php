<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class evvtappsimport extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
                    global $adb;
                    $query1=$adb->query("CREATE TABLE IF NOT EXISTS `vtiger_evvtapps` (
  `evvtappsid` int(11) NOT NULL AUTO_INCREMENT,
  `appname` varchar(64) NOT NULL,
  `installdate` datetime NOT NULL,
  `showhomepagepopup` varchar(250) NOT NULL,
  `vtappquery` text NOT NULL,
  `istemplate` int(1) NOT NULL,
  `moduleid` int(19) NOT NULL,
  `deleted` int(1) NOT NULL,
  `letterformat` varchar(250) NOT NULL,
  `orientation` varchar(250) NOT NULL,
  `isactive` int(1) NOT NULL,
  `isdefault` int(1) NOT NULL,
  PRIMARY KEY (`evvtappsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;");
                    $query2=$adb->query("CREATE TABLE IF NOT EXISTS `vtiger_evvtappsuser` (
  `evvtappsuserid` int(11) NOT NULL AUTO_INCREMENT,
  `appid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `sortorder` int(11) NOT NULL,
  `wtop` int(11) NOT NULL,
  `wleft` int(11) NOT NULL,
  `wwidth` int(11) NOT NULL,
  `wheight` int(11) NOT NULL,
  `wvisible` tinyint(1) NOT NULL,
  `wenabled` tinyint(1) NOT NULL,
  `canwrite` tinyint(1) NOT NULL,
  `candelete` tinyint(1) NOT NULL,
  `canhide` tinyint(1) NOT NULL,
  `canshow` tinyint(1) NOT NULL,
  `visits` int(15) DEFAULT NULL,
  `widget` int(11) NOT NULL,
  PRIMARY KEY (`evvtappsuserid`),
  KEY `appid` (`appid`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;");
                
			$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'evvtApps';
$module->version = '1.0';
$module->save();

// Initialize all the tables required
// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Tools');
$menu->addModule($module);

	}
          $this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		$this->finishExecution();
        }
        function undoChange() {
		if ($this->hasError()) $this->sendError();
		$this->sendMsg('Changeset '.get_class($this).' is a system update, it cannot be undone!');
		$this->finishExecution();
	}
        }
?>

