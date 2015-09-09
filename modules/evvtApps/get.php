<?php
include_once('config.inc.php');
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
//$lq=explode("FROM",$listQuery);
//$query=$adb->query("select * from ".$lq[1]);

//include XML Header (as response will be in xml format)
header("Content-type: text/xml");
//encoding may be different in your case
echo('<?xml version="1.0" encoding="utf-8"?>'); 

//start output of data
echo '<rows id="0">';

//output data from DB as XML
$query=mysql_query("select * from mv_salesorder join vtiger_crmentity on crmid=salesordermasterid where deleted=0 and $where");
		
if($query){
	while($row=mysql_fetch_array($query)){
		//create xml tag for grid's row
               $u=mysql_query("select * from vtiger_users where id=".$row['smownerid']);
if(mysql_num_rows($u)!=0)  $us=getUsername($row['smownerid']);
  else {$us1=getGroupname($row['smownerid']);   
  $us=$us1[0];}
  $ac=$row['accountid'];
$id=$row['salesordermasterid'];

		echo ("<row id='".$id."'>");
		print("<cell><![CDATA[<a href='index.php?module=SalesOrderMaster&action=DetailView&record=$id' target='_blank'>".$row['som_no']."</a>]]></cell>");
		print("<cell><![CDATA[".$row['salesordermastername']."]]></cell>");
		print("<cell><![CDATA[".$row['valoreordine']."]]></cell>");
		print("<cell><![CDATA[".$row['valoreresiduo']."]]></cell>");
		print("<cell><![CDATA[<a href='index.php?module=Accounts&action=DetailView&record=$ac' target='_blank'>".$row['accountname']."</a>]]></cell>");
		print("<cell><![CDATA[".$us."]]></cell>");
		print("</row>");
	}
}else{
//error occurs
	echo mysql_errno().": ".mysql_error()." at ".__LINE__." line in ".__FILE__." file<br>";
}

echo '</rows>';

?>