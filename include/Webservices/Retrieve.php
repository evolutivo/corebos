<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

	function vtws_retrieve($id, $user){
		global $log,$adb;

		$webserviceObject = VtigerWebserviceObject::fromId($adb,$id);
		$handlerPath = $webserviceObject->getHandlerPath();
		$handlerClass = $webserviceObject->getHandlerClass();

		require_once $handlerPath;

		$handler = new $handlerClass($webserviceObject,$user,$adb,$log);
		$meta = $handler->getMeta();
		$entityName = $meta->getObjectEntityName($id);
		$types = vtws_listtypes(null, $user);
		if(!in_array($entityName,$types['types'])){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		if($meta->hasReadAccess()!==true){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}

		if($entityName !== $webserviceObject->getEntityName()){
			throw new WebServiceException(WebServiceErrorCode::$INVALIDID,"Id specified is incorrect");
		}
		
		if(!$meta->hasPermission(EntityMeta::$RETRIEVE,$id)){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to read given object is denied");
		}
		
		$idComponents = vtws_getIdComponents($id);
		if(!$meta->exists($idComponents[1])){
			throw new WebServiceException(WebServiceErrorCode::$RECORDNOTFOUND,"Record you are trying to access is not found");
		}

		$entity = $handler->retrieve($id);
		//return product lines
		if($entityName == 'Quotes' || $entityName == 'PurchaseOrder' || $entityName == 'SalesOrder' || $entityName == 'Invoice') {
			list($wsid,$recordid) = explode('x',$id);
			$result = $adb->pquery('select * from vtiger_inventoryproductrel where id=?',array($recordid));
			while ($row=$adb->getNextRow($result, false)) {
				if($row['discount_amount'] == NULL && $row['discount_percent'] == NULL) {
					$discount = 0;$discount_type = 0;
				} else
					$discount = 1;

				if($row['discount_amount'] == NULL)
					$discount_amount = 0;
				else {
					$discount_amount = $row['discount_amount'];
					$discount_type = 'amount';
				}
				if($row['discount_percent'] == NULL)
					$discount_percent = 0;
				else {
					$discount_percent = $row['discount_percent'];
					$discount_type = 'percentage';
				}

				$onlyPrd = Array(
					"productid"=>$row['productid'],
					"comment"=>$row['comment'],
					"qty"=>$row['quantity'],
					"listprice"=>$row['listprice'],
					'discount'=>$discount,  // 0 no discount, 1 discount
					"discount_type"=>$discount_type,  //  amount/percentage
					"discount_percentage"=>$discount_percent,
					"discount_amount"=>$discount_amount,
				);
				$entity['pdoInformation'][] = $onlyPrd;
			}
		}
		VTWS_PreserveGlobal::flush();
		return $entity;
	}
        
        function vtws_getrelatedblocksPortal($id, $user){
                require_once('modules/cbMap/cbMap.php');
                global $log,$adb,$default_language;
                $idComponents = vtws_getIdComponents($id);
                $mid=$idComponents[0];
                $sql1 = 'SELECT name'
                        . ' from vtiger_ws_entity'
                        . ' where  id=? OR name =?';
                $result=$adb->pquery($sql1,array($mid,$id));
                $name=$adb->query_result($result,0,'name');

                $isCreating=strpos($id,'x');
                if($isCreating!==false){
                    $entity=vtws_retrieve($id, $user);
                    $type_ma='DETAILVIEWBLOCKPORTAL';
                }
                else{
                    $type_ma='CREATEVIEWPORTAL';
                }

                $sql = 'SELECT * '
                        . ' FROM vtiger_businessrules'
                        . ' INNER JOIN vtiger_crmentity ce ON ce.crmid=vtiger_businessrules.businessrulesid'
                        . ' INNER JOIN vtiger_cbmap  ON vtiger_businessrules.linktomap=vtiger_cbmap.cbmapid'
                        . ' where ce.deleted=0  '
                        . ' and maptype =? and module_rules=? ';
                $result=$adb->pquery($sql,array($type_ma,$name));
                $count=$adb->num_rows($result);
                $block=array();
                if($count>0){
                    for($i=0;$i<$count;$i++){
                        $map=$adb->query_result($result,$i,'linktomap');
                        if(!empty($map)){
                            $mapfocus=new cbMap();
                            $mapfocus->retrieve_entity_info($map, 'cbMap');
                            $rows=$mapfocus->getMapPortalDvBlocks();
                            $rows1=$rows['rows'];
                            $block=$rows['blocks'];
                        }
                        $blocks[$i]=array('reference'=>$adb->query_result($result,$i,'businessrules_name'),
                            'description'=>$adb->query_result($result,$i,'description'),
                            'mapstructure'=>$rows1,'blocks'=>$block);
                    }            
                }
                $blocks['info']=$entity;
                return $blocks;
        }


	function vtws_retrieve_deleted($id, $user) {
		global $log,$adb;

		// First we look if it has been totally eliminated
		$parts = explode('x', $id);
		$result = $adb->pquery("SELECT count(*) as cnt FROM vtiger_crmentity WHERE crmid=?", array($parts[1]));
		if($adb->query_result($result,0,"cnt") == 1) {  // If not we can "almost" continue normally
			$webserviceObject = VtigerWebserviceObject::fromId($adb,$id);
			$handlerPath = $webserviceObject->getHandlerPath();
			$handlerClass = $webserviceObject->getHandlerClass();
			require_once $handlerPath;
			$handler = new $handlerClass($webserviceObject,$user,$adb,$log);
			$meta = $handler->getMeta();
			$entityName = $meta->getObjectEntityNameDeleted($id);
			$entity = $handler->retrieve($id,true);
			VTWS_PreserveGlobal::flush();
		} else {  // if it has been eliminated we have to mock up object and return with nothing
			// here we should return a mock object with empty values.
			$entity = null;  // I am being lazy
		}
		return $entity;
	}

?>
