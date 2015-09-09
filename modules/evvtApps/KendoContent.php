<?php
header("Content-type: application/json");
		$kaction=$_REQUEST['kaction'];

global $adb,$current_user;

function getAccountNamekendo($account_id)
{
	global $log;
	$log->debug("Entering getAccountName(".$account_id.") method ...");
	$log->info("in getAccountName ".$account_id);

	global $adb;
	if($account_id != ''){
		$sql = "select accountname from vtiger_account where accountid=?";
        $result = $adb->pquery($sql, array($account_id));
		$accountname = $adb->query_result($result,0,"accountname");
	}
	$log->debug("Exiting getAccountName method ...");
	return 'Accounts&'.$accountname;
}
require_once("modules/SalesOrderMaster/SalesOrderMaster.php");
require_once('include/ListView/ListView.php');
require_once('modules/CustomView/CustomView.php');
require_once('include/DatabaseUtil.php');

$queryGenerator = new QueryGenerator("SalesOrderMaster", $current_user);

	$queryGenerator->initForCustomViewById(147);
$list_query = $queryGenerator->getQuery();
$where = str_replace("vtiger_account","mv_salesorder",str_replace("vtiger_salesordermaster","mv_salesorder",$queryGenerator->getConditionalWhere()));

//$listQuery=$_SESSION['SalesOrderMaster_listquery'];
//
//if(empty($listQuery))
//$listQuery="Select * FROM vtiger_salesordermaster tt join vtiger_crmentity ce on tt.salesordermasterid=ce.crmid where ce.deleted=0";
if($kaction=='retrieve'){
//$lq=explode("FROM",$listQuery);
//$query=$adb->query("select * from ".$lq[1]);
$query=$adb->query("select * from mv_salesorder join vtiger_crmentity on crmid=salesordermasterid where deleted=0 and $where");

$count=$adb->num_rows($query);

//$fieldcolumns=getKendoHelpDeskFields();
for($i=0;$i<$count;$i++){
    
//   foreach($fieldcolumns as $index=>$fieldobj){
//   $fieldname=$fieldobj['field'];
//   if($fieldname=='smownerid')
//   $content[$i][$fieldname]=getUsername($adb->query_result($query,$i,$fieldname));
//   else
//   $content[$i][$fieldname]=$adb->query_result($query,$i,$fieldname);
//   }
   $content[$i]['salesordermasterid']=$adb->query_result($query,$i,'salesordermasterid');
   $content[$i]['salesordermastername']=$adb->query_result($query,$i,'salesordermastername');
   $content[$i]['som_no']=$adb->query_result($query,$i,'som_no');
    $content[$i]['ordine']=$adb->query_result($query,$i,'valoreordine');
   $content[$i]['residuo']=$adb->query_result($query,$i,'valoreresiduo');
   $content[$i]['url']='index.php?module=SalesOrderMaster&action=DetailView&record='.$content[$i]['salesordermasterid'];
   //$content[$i]['total']=$adb->query_result($query,$i,'subtotal');

  // $content[$i]['category']=$adb->query_result($query,$i,'category');

   $parent_id=$adb->query_result($query,$i,'linktoaccount');
   
   $pid=($parent_id>0&&$parent_id!=null)?getAccountNamekendo($parent_id):"";
   if($pid!="") list($setype,$pid)=explode('&',$pid);
   $content[$i]['linktoaccount']=$pid;
   if($parent_id>0 && $parent_id!=null)
   $content[$i]['purl']='index.php?module='.$setype.'&action=DetailView&record='.$parent_id;
   else
   $content[$i]['purl']="--";
   //$content[$i]['severity']=$adb->query_result($query,$i,'severity');
  // $content[$i]['status']=$adb->query_result($query,$i,'st');
  // $content[$i]['priority']=$adb->query_result($query,$i,'priority');
//   global $log;
//   $log->debug("loro ".$adb->query_result($query,$i,'smownerid').' '.$adb->query_result($query,$i,'som_no'));
   $u=$adb->pquery("select * from vtiger_users where id=?",array($adb->query_result($query,$i,'smownerid')));
   if($adb->num_rows($u)!=0)
$us=getUsername($adb->query_result($query,$i,'smownerid'));
  else {$us1=getGroupname($adb->query_result($query,$i,'smownerid'));
          $us=$us1[0];
  };
   $content[$i]['smownerid']=$us;
}
echo json_encode($content);

	}

?>
