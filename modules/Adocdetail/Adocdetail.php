<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class Adocdetail extends CRMEntity {
var $db, $log; // Used in class functions of CRMEntity

var $table_name = 'vtiger_adocdetail'; 
var $table_index= 'adocdetailid'; 
var $column_fields = Array(); 

var $IsCustomModule = true;
var $HasDirectImageField = false;
var $customFieldTable = Array('vtiger_adocdetailcf', 'adocdetailid');
var $tab_name = Array('vtiger_crmentity', 'vtiger_adocdetail', 'vtiger_adocdetailcf');

var $tab_name_index = Array( 
'vtiger_crmentity' => 'crmid', 
'vtiger_adocdetail'   => 'adocdetailid', 
'vtiger_adocdetailcf' => 'adocdetailid');

var $list_fields = Array (
'AdocdetailNo'=> Array('adocdetail'=> 'adocdetailno'),
'Line Nr'=> Array('adocdetail'=> 'nrline'),
'adoc_product'=> Array('adocdetail'=> 'adoc_product'),
'Quantity'=> Array('adocdetail'=> 'adoc_quantity'),
'Price'=> Array('adocdetail'=> 'adoc_price'),
'Assigned To' => Array('crmentity'=> 'smownerid'));

var $list_fields_name = Array(
'AdocdetailNo'=>'adocdetailno',
'Line Nr'=> 'nrline',
'adoc_product'=> 'adoc_product',
'Quantity'=> 'adoc_quantity',
'Price'=> 'adoc_price',
'Assigned To' => 'assigned_user_id');

var $list_link_field = 'adocdetailno';
var $search_fields = Array( 
'AdocdetailNo'=> Array('adocdetail'=> 'adocdetailno'),
'Line Nr'=> Array('adocdetail'=> 'nrline'),
'adoc_product'=> Array('adocdetail'=> 'adoc_product'),
'Quantity'=> Array('adocdetail'=> 'adoc_quantity'),
'Price'=> Array('adocdetail'=> 'adoc_price'),
 );
var $search_fields_name = Array( 
'AdocdetailNo'=>'adocdetailno',
'Line Nr'=> 'nrline',
'adoc_product'=> 'adoc_product',
'Quantity'=> 'adoc_quantity',
'Price'=> 'adoc_price',
); 

var $popup_fields = Array('adocdetailno');
var $sortby_fields = Array();
var $def_basicsearch_col = 'adocdetailno'; 
var $def_detailview_recname = 'adocdetailno';
var $required_fields = Array();
var $special_functions = Array('set_import_assigned_user'); 
var $default_order_by = 'adocdetailno'; 
var $default_sort_order='ASC'; 
var $mandatory_fields = Array('createdtime', 'modifiedtime'); 


	function save_module($module) {
            if ($this->HasDirectImageField) {
                    $this->insertIntoAttachment($this->id,$module);
            }
            require_once("modules/Adocmaster/calculateTariffPrice.php");
            $res = $this->db->pquery("Select adoctomaster,adoc_product,adoc_quantity FROM vtiger_adocdetail WHERE adocdetailid=?", array($this->id));
            $adocmasterid=$this->db->query_result($res, 0,'adoctomaster');
            $productid=$this->db->query_result($res,0,'adoc_product');
            $quantity=$this->db->query_result($res,0,'adoc_quantity');
            $foundRes2=calculatePrice('Adocdetail', $productid, $adocmasterid, $quantity);
            $foundRes3=explode("::",$foundRes2);
            $new_price=$foundRes3[7];
            $new_tax=$foundRes3[8];
            $queryString=$this->db->pquery("Select taxamount,totalamount,amount FROM vtiger_adocmaster where adocmasterid=?",array($adocmasterid));
            $actualtotal=$this->db->query_result($queryString,0,'totalamount');
            $actualtax=$this->db->query_result($queryString,0,'taxamount');
            $actualtotal2=$this->db->query_result($queryString,0,'amount');
            $addthis=$quantity*$new_price;
            $newtotal=$actualtotal+$addthis;
            $totaltaxed=$new_price*$quantity*$new_tax;
            $newtax=$actualtax+$totaltaxed;
            $total2=$actualtotal2+$new_price*$quantity*$new_tax+$quantity*$new_price;
            $this->db->pquery("UPDATE vtiger_adocdetail set adoc_price=?,adocdtax=?,adocdtotalamount=?,adocdtotal=? WHERE adocdetailid=?",array($new_price,$new_tax,$addthis,$totaltaxed,$this->id));
            $this->db->pquery("UPDATE vtiger_adocmaster set totalamount=?,taxamount=?,amount=? WHERE adocmasterid=?",array($newtotal,$newtax,$total2,$adocmasterid));
     
	}

	/**
	 * Apply security restriction (sharing privilege) query part for List view.
	 */
	function getListViewSecurityParameter($module) {
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');

		$sec_query = '';
		$tabid = getTabid($module);

		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1
			&& $defaultOrgSharingPermission[$tabid] == 3) {

				$sec_query .= " AND (vtiger_crmentity.smownerid in($current_user->id) OR vtiger_crmentity.smownerid IN
					(
						SELECT vtiger_user2role.userid FROM vtiger_user2role
						INNER JOIN vtiger_users ON vtiger_users.id=vtiger_user2role.userid
						INNER JOIN vtiger_role ON vtiger_role.roleid=vtiger_user2role.roleid
						WHERE vtiger_role.parentrole LIKE '".$current_user_parent_role_seq."::%'
					)
					OR vtiger_crmentity.smownerid IN
					(
						SELECT shareduserid FROM vtiger_tmp_read_user_sharing_per
						WHERE userid=".$current_user->id." AND tabid=".$tabid."
					)
					OR (";

					// Build the query based on the group association of current user.
					if(sizeof($current_user_groups) > 0) {
						$sec_query .= " vtiger_groups.groupid IN (". implode(",", $current_user_groups) .") OR ";
					}
					$sec_query .= " vtiger_groups.groupid IN
						(
							SELECT vtiger_tmp_read_group_sharing_per.sharedgroupid
							FROM vtiger_tmp_read_group_sharing_per
							WHERE userid=".$current_user->id." and tabid=".$tabid."
						)";
				$sec_query .= ")
				)";
		}
		return $sec_query;
	}

	/**
	 * Invoked when special actions are performed on the module.
	 * @param String Module name
	 * @param String Event Type (module.postinstall, module.disabled, module.enabled, module.preuninstall)
	 */
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {
			// TODO Handle post installation actions
			$this->setModuleSeqNumber('configure', $modulename, $modulename.'-', '0000001');
		} else if($event_type == 'module.disabled') {
			// TODO Handle actions when this module is disabled.
		} else if($event_type == 'module.enabled') {
			// TODO Handle actions when this module is enabled.
		} else if($event_type == 'module.preuninstall') {
			// TODO Handle actions when this module is about to be deleted.
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
		}
	}

	/**
	 * Handle saving related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	// function save_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle deleting related module information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function delete_related_module($module, $crmid, $with_module, $with_crmid) { }

	/**
	 * Handle getting related list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_related_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }

	/**
	 * Handle getting dependents list information.
	 * NOTE: This function has been added to CRMEntity (base class).
	 * You can override the behavior by re-defining it here.
	 */
	//function get_dependents_list($id, $cur_tab_id, $rel_tab_id, $actions=false) { }
}
?>
