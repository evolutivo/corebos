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

class AddFormBuilder extends cbupdaterWorker {
	
	function applyChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			$this->sendMsg('Changeset '.get_class($this).' already applied!');
		} else {
			$toinstall = array('FormBuilder');
			foreach ($toinstall as $module) {
				if ($this->isModuleInstalled($module)) {
					vtlib_toggleModuleAccess($module,true);
					$this->sendMsg("$module activated!");
				} else {
					$this->installManifestModule($module);
				}
			}
                        $this->ExecuteQuery("
                               -- phpMyAdmin SQL Dump
-- version 3.5.8.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 15, 2015 at 02:53 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `formbuilder_extensions`
--

-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2015 at 03:01 PM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.6

SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET time_zone = \"+00:00\";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `subito`
--

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_actions`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_actions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `block` int(11) NOT NULL,
  `autoload` tinyint(1) DEFAULT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_blocks`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_blocks` (
  `id` int(11) NOT NULL,
  `block_label` varchar(255) NOT NULL,
  `block_sequence` varchar(50) NOT NULL,
  `block_module` varchar(255) DEFAULT NULL,
  `block_tab` int(11) NOT NULL,
  `block_roles` text NOT NULL,
  `block_users` int(11) NOT NULL,
  `block_action` int(11) DEFAULT NULL,
  `block_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_entities`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_entities` (
  `dsid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `entity` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`dsid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_extensions`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_extensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `parenttab` varchar(255) NOT NULL,
  `current` tinyint(3) NOT NULL,
  `generated_form` tinyint(3) NOT NULL,
  `type` varchar(250) NOT NULL,
  `template` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_fieldparams`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_fieldparams` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `input_name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `default_value` varchar(255) DEFAULT NULL,
  `parameter` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `modulefield` varchar(255) NOT NULL,
  `sequence` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_fields`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_fields` (
  `id` int(11) NOT NULL,
  `fieldname` varchar(255) NOT NULL,
  `fieldlabel` varchar(255) NOT NULL,
  `fieldtype` varchar(255) NOT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  `block` int(11) NOT NULL,
  `module` varchar(255) DEFAULT NULL,
  `modulefield` varchar(255) DEFAULT NULL,
  `operator` varchar(255) DEFAULT NULL,
  `field_sequence` int(11) NOT NULL,
  `listview` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_parameters`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_parameters` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `isgeneral` tinyint(1) NOT NULL,
  `action` int(11) DEFAULT NULL,
  `parent_parameter` int(11) DEFAULT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_results`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_results` (
  `userid` int(11) NOT NULL,
  `crmid` int(11) NOT NULL,
  `entity` char(15) NOT NULL,
  `selected` tinyint(4) NOT NULL,
  `blockid` int(11) NOT NULL,
  KEY `userid` (`userid`,`selected`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_results_config`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_results_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `select_query` text,
  `cond_query` text,
  `where_query` text,
  `userid` int(11) NOT NULL,
  `blockid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=136 ;

-- --------------------------------------------------------

--
-- Table structure for table `dashboardbuilder_tabs`
--

CREATE TABLE IF NOT EXISTS `dashboardbuilder_tabs` (
  `id` int(11) NOT NULL,
  `tab_label` varchar(255) NOT NULL,
  `tab_active` tinyint(1) DEFAULT NULL,
  `tab_sequence` varchar(255) NOT NULL,
  `tab_roles` text NOT NULL,
  `tab_users` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE  `dashboardbuilder_entities` ADD  `entityname` TEXT NULL ,
ADD  `index_type` VARCHAR( 50 ) NULL ,
ADD  `parentname` VARCHAR( 50 ) NULL ,
ADD  `level` VARCHAR( 50 ) NULL,
ADD  `pointing_field` VARCHAR( 50 ) NULL ; 

ALTER TABLE  `dashboardbuilder_entities` ADD  `savedid` INT( 10 ) NULL ;
ALTER TABLE  `dashboardbuilder_entities` ADD  `parameter` VARCHAR( 20 ) NULL ;
ALTER TABLE  `dashboardbuilder_extensions` ADD  `default_form` VARCHAR( 20 ) NULL ;
ALTER TABLE  `dashboardbuilder_extensions` ADD  `last_modifiedtime` VARCHAR( 20 ) NULL ;
ALTER TABLE  `dashboardbuilder_extensions` ADD  `onopen` VARCHAR( 20 ) NULL ;
ALTER TABLE  `dashboardbuilder_blocks` ADD  `doc_widget` VARCHAR( 20 ) NULL ;
ALTER TABLE  `dashboardbuilder_blocks` ADD  `brid` VARCHAR( 50 ) NULL ;

ALTER TABLE  `dashboardbuilder_actions` ADD  `action_module` VARCHAR( 50 ) NULL ;
ALTER TABLE  `dashboardbuilder_actions` ADD  `action_type` VARCHAR( 50 ) NULL ;
ALTER TABLE  `dashboardbuilder_actions` ADD  `mandatory_action` VARCHAR( 50 ) NULL ;

ALTER TABLE  `dashboardbuilder_extensions` ADD  `onsave` VARCHAR( 50 ) NULL ;
ALTER TABLE  `dashboardbuilder_extensions` ADD  `dv_br` VARCHAR( 50 ) NULL ;");
			$this->sendMsg('Changeset '.get_class($this).' applied!');
			$this->markApplied();
		}
		$this->finishExecution();
	}
	
	function undoChange() {
		if ($this->hasError()) $this->sendError();
		if ($this->isApplied()) {
			vtlib_toggleModuleAccess('FormBuilder',false);
			$this->sendMsg('FormBuilder deactivated!');
			$this->markUndone(false);
			$this->sendMsg('Changeset '.get_class($this).' undone!');
		} else {
			$this->sendMsg('Changeset '.get_class($this).' not applied, it cannot be undone!');
		}
		$this->finishExecution();
	}
	
}
?>