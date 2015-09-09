<?php
require 'build/cbHeader.inc';
require_once("include/HTTP_Session/Session.php");
require_once 'include/Webservices/Utils.php';
require_once("modules/Users/Users.php");
require_once("include/Webservices/State.php");
require_once("include/Webservices/OperationManager.php");
require_once("include/Webservices/SessionManager.php");
require_once("include/Zend/Json.php");
require_once 'include/Webservices/WebserviceField.php';
require_once 'include/Webservices/EntityMeta.php';
require_once 'include/Webservices/VtigerWebserviceObject.php';
require_once("include/Webservices/VtigerCRMObject.php");
require_once("include/Webservices/VtigerCRMObjectMeta.php");
require_once("include/Webservices/DataTransform.php");
require_once("include/Webservices/WebServiceError.php");
require_once 'include/utils/UserInfoUtil.php';
require_once 'include/Webservices/ModuleTypes.php';
require_once 'include/utils/VtlibUtils.php';
require_once('include/logging.php');
require_once 'include/Webservices/WebserviceEntityOperation.php';
require_once "include/language/$default_language.lang.php";
require_once 'include/Webservices/Retrieve.php';
require_once('include/Webservices/Update.php');
require_once('modules/Emails/mail.php');
require_once('include/events/SqlResultIterator.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowManager.inc');
require_once('modules/com_vtiger_workflow/VTSimpleTemplate.inc');
require_once 'modules/com_vtiger_workflow/VTEntityCache.inc';
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');
require_once 'modules/com_vtiger_workflow/include.inc';
require_once('modules/com_vtiger_workflow/WorkFlowScheduler.php');
/////////////////////////////////////////////////////
// PARAMETERS TO SET
 $workflowid_to_evaluate = $_REQUEST['workflowid'];
 $crm_record_to_evaluate = $_REQUEST['crmid'];
/////////////////////////////////////////////////////
if (empty($workflowid_to_evaluate) or empty($crm_record_to_evaluate)) {
	echo "<h2>Parameters required:</h2>";
	echo "<b>workflowid</b>: ID of the workflow to evaluate. For example: 19<br>";
	echo "<b>crmid</b>: webservice enhanced ID of the record to evaluate the workflow against. For example: 12x57<br>";
	echo "?workflowid=19&crmid=12x57";
	die();
}

global $currentModule, $adb;

	function evalwfEmailTask($entityid,$task){
		global $adb, $current_user, $entityCache;
		$result = $adb->query("select user_name, email1, email2 from vtiger_users where id=1");
		$from_email = $adb->query_result($result,0,'email1');
		$from_name  = $adb->query_result($result,0,'user_name');

		if(!empty($task->fromname)){
			$fnt = new VTEmailRecipientsTemplate($task->fromname);
			$from_name  = $fnt->render($entityCache,$entityid);
		}
		if(!empty($task->fromemail)){
			$fet = new VTEmailRecipientsTemplate($task->fromemail);
			$from_email = $fet->render($entityCache,$entityid);
		}

		$et = new VTEmailRecipientsTemplate($task->recepient);
		$to_email = $et->render($entityCache, $entityid);
		$ecct = new VTEmailRecipientsTemplate($task->emailcc);
		$cc = $ecct->render($entityCache, $entityid);
		$ebcct = new VTEmailRecipientsTemplate($task->emailbcc);
		$bcc = $ebcct->render($entityCache, $entityid);
		$to_email = preg_replace('/,,+/', ',', $to_email);
		$cc = preg_replace('/,,+/', ',', $cc);
		$bcc = preg_replace('/,,+/', ',', $bcc);
		$st = new VTSimpleTemplate($task->subject);
		$subject = $st->render($entityCache, $entityid);
		$ct = new VTSimpleTemplate($task->content);
		$content = $ct->render($entityCache, $entityid);
		return array(
			'to_email' => $to_email,
			'cc' => $cc,
			'bcc' => $bcc,
			'subject' => $subject,
			'content' => $content,
		);
	}


list($wsmod,$crmid) = explode('x', $crm_record_to_evaluate);
$wsrs = $adb->pquery('select name FROM vtiger_ws_entity where id=?',array($wsmod));
if (!$wsrs or $adb->num_rows($wsrs)==0) {
	echo "<h2>Incorrect crmid:</h2>";
	echo "<b>crmid</b> could not be evaluated as a valid webservice enhanced ID<br>";
	die();
}
$currentModule = $adb->query_result($wsrs, 0, 0);
$semod = getSalesEntityType($crmid);
if ($semod != $currentModule and ($semod!='Calendar' and $currentModule!='Events')) {
	echo "<h2>Incorrect crmid:</h2>";
	echo "<b>crmid</b> could not be evaluated as a valid record ID<br>";
	die();
}
$util = new VTWorkflowUtils();
$adminUser = $util->adminUser();
$entityCache = new VTEntityCache($adminUser);
$wfs = new VTWorkflowManager($adb);
$result = $adb->pquery('select workflow_id, module_name, summary, test, execution_condition, type
			from com_vtiger_workflows where workflow_id=?',array($workflowid_to_evaluate));
if (!$result or $adb->num_rows($result)==0) {
	echo "<h2>Incorrect workflowid:</h2>";
	echo "<b>workflowid</b> could not be found as a valid workflow<br>";
	die();
}
$workflows = $wfs->getWorkflowsForResult($result);
$workflow = $workflows[$workflowid_to_evaluate];
$entityData = $entityCache->forId($crm_record_to_evaluate);

if ($workflows[$workflowid_to_evaluate]->executionCondition==VTWorkflowManager::$ON_SCHEDULE) {
	echo "<h2>Scheduled: SQL for affected records:</h2>";
	$workflowScheduler = new WorkFlowScheduler($adb);
	$query = $workflowScheduler->getWorkflowQuery($workflow);
	echo "<span style='font-size: large;'>$query</span>";
} else {
	echo "<h2>Launch Conditions:</h2>";
	$eval = $workflow->evaluate($entityCache, $crm_record_to_evaluate);
	echo "<span style='font-size: large;'>";
	var_dump($eval);
	echo '</span>';
	$tm = new VTTaskManager($adb);
	$taskQueue = new VTTaskQueue($adb);
	$tasks = $tm->getTasksForWorkflow($workflow->id);
	foreach($tasks as $task){
		if(is_object($task) and $task->active and get_class($task) == 'VTEmailTask') {
			$email = evalwfEmailTask($crm_record_to_evaluate,$task);
			foreach ($email as $key => $value) {
				echo "<h2>$key</h2>$value <br><hr>";
			}
		}
	}
}
?>
</table>
</body>
</html>
