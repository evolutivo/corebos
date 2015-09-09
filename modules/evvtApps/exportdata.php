<?php
global $adb,$log;
// load library
require 'modules/EntityConverter/php-excel.class.php';
$allcontacts= $_REQUEST['contacts'];
$data[] = array('Rapporto visita Fonte Campagna','Rapporto visita Funzionario','Rapporto visita BU interesse 1','Aziende Nome Azienda','Contatti Formula di saluto','Contatti Nome','Contatti Cognome','Contatti Posizione','Contatti Email');
$sql = $adb->query("
Select * FROM ( 
        Select po.potentialid,po.contacts as contacts,cp.campaignname as campaign,po.buinteresse1 as interesse1,ce.smownerid as user, 
        acc.accountname as accountname,cd.salutation as salutation,cd .firstname as firstname,cd.lastname as lastname, cd .posizione_ as posizione, cd.email as email   
        FROM vtiger_potential po
        INNER JOIN vtiger_crmentity ce ON ce.crmid=po.potentialid
        LEFT JOIN vtiger_contactdetails cd ON cd.contactid=po.contacts
        LEFT JOIN vtiger_account acc ON acc.accountid=cd.accountid
        LEFT JOIN vtiger_campaign cp ON cp.campaignid=po.campaignid
        where po.contacts IN ($allcontacts)  AND ce.deleted=0  Order by potentialid DESC) 
tmp GROUP BY contacts") ;
while($row = $adb->fetch_array($sql)){
$data[] = array($row['campaign'],$row['user'],$row['interesse1'],$row['accountname'],$row['salutation'],$row['firstname'],$row['lastname'],$row['posizione'],$row['email']);
}
$log->debug('All results to export');
$log->debug($data);
$xls = new Excel_XML('UTF-8', false, 'Workflow Management');
$xls->addArray($data);
$log->debug('kthehet');
$xls->generateXML('Contacts-export'.date('Y-m-d'));


?>