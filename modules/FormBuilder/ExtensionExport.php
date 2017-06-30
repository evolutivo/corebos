<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Vtiger/Module.php');
include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Event.php');
include_once('vtlib/Vtiger/Zip.php');
include_once('vtlib/Vtiger/Cron.php');
/**
 * Provides API to package vtiger CRM module and associated files.
 * @package vtlib
 */
class Vtiger_ExtensionExport {
	var $_export_tmpdir = 'test/vtlib';
	var $_export_modulexml_filename = null;
	var $_export_modulexml_file = null;

	/**
	 * Constructor
	 */
	function Vtiger_ExtensionExport() {
		if(is_dir($this->_export_tmpdir) === FALSE) {
			mkdir($this->_export_tmpdir);
		}
	}

	/** Output Handlers */

	/** @access private */
	function openNode($node,$delimiter="\n") {
		$this->__write("<$node>$delimiter");
	}
	/** @access private */
	function closeNode($node,$delimiter="\n") {
		$this->__write("</$node>$delimiter");
	}
	/** @access private */
	function outputNode($value, $node='') {
		if($node != '') $this->openNode($node,'');
		$this->__write($value);
		if($node != '') $this->closeNode($node);
	}
	/** @access private */
	function __write($value) {
		fwrite($this->_export_modulexml_file, $value);
	}

	/**
	 * Set the module.xml file path for this export and 
	 * return its temporary path.
	 * @access private
	 */
	function __getManifestFilePath() {
		if(empty($this->_export_modulexml_filename)) {
			// Set the module xml filename to be written for exporting.
			$this->_export_modulexml_filename = "manifest-".time().".xml";
		}
		return "$this->_export_tmpdir/$this->_export_modulexml_filename";
	}

	/**
	 * Initialize Export
	 * @access private
	 */
	function __initExport($module) {
		$this->_export_modulexml_file = fopen($this->__getManifestFilePath(), 'w');
		$this->__write("<?xml version='1.0'?>\n");
	}

	/**
	 * Post export work.
	 * @access private
	 */
	function __finishExport() {
		if(!empty($this->_export_modulexml_file)) {
			fclose($this->_export_modulexml_file);
			$this->_export_modulexml_file = null;
		}
	}

    /**
	 * Clean up the temporary files created.
	 * @access private
     */
	function __cleanupExport() {
		if(!empty($this->_export_modulexml_filename)) {
			unlink($this->__getManifestFilePath());
		}
	}

	/**
	 * Export Module as a zip file.
	 * @param Vtiger_Module Instance of module
	 * @param Path Output directory path
	 * @param String Zipfilename to use
	 * @param Boolean True for sending the output as download
	 */
	function export($moduleInstance, $parentab,$todir='', $zipfilename='', $directDownload=false) {

		$module = $moduleInstance;

		$this->__initExport($module);

		// Call module export function
		$this->export_Module($module,$parentab);

		$this->__finishExport();		

		// Export as Zip
		if($zipfilename == '') $zipfilename = "$module-" . date('YmdHis') . ".zip";
		$zipfilename = "$this->_export_tmpdir/$zipfilename";

		$zip = new Vtiger_Zip($zipfilename);
		// Add manifest file
		$zip->addFile($this->__getManifestFilePath(), "manifest.xml");		
		// Copy module directory
		$zip->copyDirectoryFromDisk("modules/$module");
		// Copy templates directory of the module (if any)
		if(is_dir("Smarty/templates/modules/$module"))
			$zip->copyDirectoryFromDisk("Smarty/templates/modules/$module", "templates");
		// Copy cron files of the module (if any)
		if(is_dir("cron/modules/$module"))
			$zip->copyDirectoryFromDisk("cron/modules/$module", "cron");

		$zip->save();

		if($directDownload) {
			$zip->forceDownload($zipfilename);
			unlink($zipfilename);
		}
		$this->__cleanupExport();
	}

	/**
	 * Export Module Handler
	 * @access private
	 */
	function export_Module($moduleInstance,$parenttab) {
		global $adb;
		$this->openNode('module');		
		$this->outputNode(date('Y-m-d H:i:s'),'exporttime');
		$this->outputNode($moduleInstance, 'name');
		$this->outputNode($parenttab, 'label');
		$this->outputNode($parent_name, 'parent');
		$this->outputNode('extension', 'type');
		// Export module tables
		//$this->export_Tables($moduleInstance);

        $this->closeNode('module');
	}

	/**
	 * Export module base and related tables
	 * @access private
	 */
	function export_Tables($moduleInstance) {

		$_exportedTables = Array();

		$modulename = $moduleInstance->name;

		$this->openNode('tables');

		//if($moduleInstance->isentitytype) {
		//	$focus = CRMEntity::getInstance($modulename);

			// Setup required module variables which is need for vtlib API's
			//vtlib_setup_modulevars($modulename, $focus);

			$tables = Array ("formbuilder_extensions","formbuilder_entities");

			foreach($tables as $table) {
				$this->openNode('table');
				$this->outputNode($table, 'name');
				$this->outputNode('<![CDATA['.Vtiger_Utils::CreateTableSql($table).']]>', 'sql');
				$this->closeNode('table');

				$_exportedTables[] = $table;
			}
			
	//	}
		$this->closeNode('tables');
	}

}
?>