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
require_once('Smarty_setup.php');

$idlist = $_REQUEST['idlist'];
$entityId = $_REQUEST['entity_id'];

if (empty($entityId)) {
  $smarty = new vtigerCRM_Smarty;
  $smarty->assign('IDLIST', $idlist);
  $smarty->assign('maindata',
    array(
      array( 10 ),
      array(
        array(
          'displaylabel' => getTranslatedString('Assign Contacts To','Sequencers'),
          'options' => array( 'Sequencers', 'PlannedActions' ),
          ),
        ),
      array( 'entity_id' ),
      array( '', '', '' ),
      'V~M',
      array( 1 ),
      )
    );
  $smarty->display("modules/Sequencers/AssignContacts.tpl");
  return;
}

$contactList = explode(';', trim($idlist, ';'));

$entityName = getSalesEntityType($entityId);
$entity = CRMEntity::getInstance($entityName);
$entity->id = $entityId;
$entity->retrieve_entity_info($entityId, $entityName);
foreach($contactList as $contactId) {
  $entity->save_related_module($entityName, $entityId, getSalesEntityType($contactId), $contactId);
}

?>
<script type="text/javascript">
location.href='<?php echo "index.php?action=DetailView&module=$entityName&record=$entityId&parenttab=Marketing"?>';
</script>
