<?php
//require_once('/var/www/eprice/include/fpdf/fpdf.php');
//tntIntegration('12827','Via Ciovasso 6','20121Santamaria Giorgio','','Milano','MI','Via Bergamo', '6','21013','TECNOASSIST','','Gallarate','','9530.00');
function tntIntegration($id,$add_acc,$postcode_acc,$name_acc,$country_acc,$town_acc,$province_acc
        ,$add_rasc,$postcode_rasc,$name_rasc,$country_rasc,$town_rasc,$province_rasc,
        $weight,$idpratica,$action,$sitoprovenienza,$purchasecostdetail){
    global $adb,$log;
$log->debug('test4');   
if($sitoprovenienza=='SaldiPrivati'){
    $senderAccId='06355872';
    $customer='S08284';
    $user='REVERSE';
    $password='EXPLBL';
}
else{
    $senderAccId='01643779';
    $customer='E02768';
    $user='EPRICETEST';
    $password='EPRICETEST';
}
$weight=number_format($weight, 0); 
$weight=str_pad($weight.'000',8,"0",STR_PAD_LEFT);
if($purchasecostdetail>=150)
{
    $purchasecostdetail="'".str_pad($purchasecostdetail,13,"0",STR_PAD_LEFT)."'";
}
else{
    $purchasecostdetail="'".str_pad('',13,"0",STR_PAD_LEFT)."'";
    //str_replace(',','',str_replace('.','',$purchasecostdetail))
}
$bar=str_pad($idpratica,12,"0",STR_PAD_LEFT);
if($sitoprovenienza!='SaldiPrivati'){
    $bar='EPR'.$bar;
}
else{
    $bar=$bar;
}
$xml = 
'<?xml version="1.0" encoding="utf-8" ?> 
<shipment xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
xsi:noNamespaceSchemaLocation="W:\ExpressLabel\Internazionale\routinglabel.xsd"> 
  <software>
    <application>MYRTL</application>
    <version>1.0</version>
  </software>
  <security>
    <customer>'.$customer.'</customer>
    <user>'.$user.'</user>
    <password>'.$password.'</password>
    <langid>IT</langid> 
  </security>
  <consignment action="'.$action.'" international="N" insurance="N" hazardous="N" cashondelivery="N" operationaloption="0" highvalue="N" specialgoods="N"> 
    <laroseDepot></laroseDepot>  
    <senderAccId>'.$senderAccId.'</senderAccId>
    <consignmentno>'.$idpratica.'111122</consignmentno>
    <consignmenttype>C</consignmenttype>
    <actualweight>'.$weight.'</actualweight> 
    <actualvolume></actualvolume> 
    <totalpackages>00001</totalpackages> 
    <packagetype>C</packagetype > 
    <division>D</division> 
    <product>NC</product> 
    <vehicle></vehicle>
    <insurancevalue>0000000000000</insurancevalue> 
    <insurancecurrency>EUR</insurancecurrency>
    <packingdesc>BOX</packingdesc> 
    <reference>'.$bar.'</reference> 
    <collectiondate>26062012</collectiondate> 
    <collectiontime></collectiontime> 
    <invoicevalue></invoicevalue> 
    <invoicecurrency></invoicecurrency> 
    <specialinstructions>Attenzione consegnare sempre dopo le 12:00</specialinstructions> 
    <options> 
    <option></option> 
    <option></option> 
    </options> 
    <termsofpayment>S</termsofpayment>
    <systemcode>RL</systemcode>
    <systemversion>1.0</systemversion>
    <codfvalue></codfvalue> 
    <codfcurrency>EUR</codfcurrency>
    <goodsdesc>ABBIGLIAMENTO</goodsdesc> 
    <eomenclosure></eomenclosure> 
    <eomofferno>0000025</eomofferno> 
    <eomdivision></eomdivision> 
    <eomunification></eomunification> 
    <dropoffpoint></dropoffpoint> 
    <addresses>
       <address>
        <addressType>R</addressType>
        <addrline1>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$add_rasc).'</addrline1> 
        <postcode>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$postcode_rasc).'</postcode> 
        <phone1></phone1> 
        <phone2></phone2> 
        <name>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$name_rasc).'</name> 
        <country>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$country_rasc).'</country>
        <town>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$town_rasc).'</town> 
        <contactname></contactname> 
        <province>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$province_rasc).'</province> 
        <custcountry></custcountry> 
      </address>
       <address>
        <addressType>S</addressType>
        <addrline1>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$add_acc).'</addrline1> 
        <postcode>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$postcode_acc).'</postcode> 
        <phone1></phone1> 
        <phone2></phone2> 
        <name>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$name_acc).'</name> 
        <country>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$country_acc).'</country>
        <town>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$town_acc).'</town> 
        <contactname></contactname> 
        <province>'.preg_replace('/[^A-Z \'"a-z0-9\-]/', '',$province_acc).'</province> 
        <custcountry></custcountry> 
      </address>
    </addresses>
<dimensions> 
<itemaction>'.$action.'</itemaction>
<itemsequenceno>1</itemsequenceno> 
<itemtype >C</itemtype > 
<itemreference>'.$bar.'</itemreference> 
<volume></volume> 
<weight>'.$weight.'</weight> 
<length></length> 
<height></height> 
<width></width> 
<quantity></quantity> 
    </dimensions>
<articles> 
<tariff></tariff> 
<origcountry>IT</origcountry> 
</articles> 
  </consignment>
</shipment>';
//<Item action="'.$action.'">
$fb = fopen('test_tnt.log', "a") or die("can't open file");
fwrite($fb,$xml);
//$data = strstr($xml, '<?');
//$xml_parser = xml_parser_create();
//xml_parse_into_struct($xml_parser, $data, $vals, $index);
//xml_parser_free($xml_parser);
//var_dump($vals);

$endpointUrl = "https://www.mytnt.it/XMLServices";
$fields1 =array("xmlin"=>$xml);
$channel1 = curl_init();
curl_setopt($channel1, CURLOPT_URL, $endpointUrl);
curl_setopt($channel1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($channel1, CURLOPT_POST, true);
curl_setopt($channel1, CURLOPT_HEADER, 1);
//curl_setopt($channel1, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($channel1, CURLOPT_POSTFIELDS, "xmlin=$xml");
curl_setopt($channel1, CURLOPT_CONNECTTIMEOUT, 100);
//curl_setopt($channel1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($channel1, CURLOPT_TIMEOUT, 1000);
$host  = getHostName();
$pos=  strpos($host, 'madunina');
if($pos===false){ // production
    curl_setopt($channel1, CURLOPT_HTTPPROXYTUNNEL, 0); 
    //curl_setopt($channel1, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($channel1, CURLOPT_PROXY, 'srv-proxy.eprice.lan:80');
    curl_setopt($channel1, CURLOPT_PROXYPORT, 80); 
}
$response1 = curl_exec($channel1);
//var_dump($response1);
//var_dump( htmlentities($response1) );
//var_dump( curl_error($channel1) );

$data = strstr($response1, '<?');
$xml_parser = xml_parser_create();
xml_parse_into_struct($xml_parser, $data, $vals, $index);
xml_parser_free($xml_parser);
$i=0;
//var_dump($xml);
//var_dump(json_encode($vals));

foreach ($vals as $val)
{
   if($val['tag']=='ORIGINDEPOTID' )
       $tag['ORIGINDEPOTID']=$val['value'];
   if($val['tag']=='ITEMNO' )
       $tag['ITEMNO']=$val['value'];
   if($val['tag']=='WEIGHT' )
       $tag['WEIGHT']=$val['value'];
   if($val['tag']=='SENDERNAME' )
       $tag['SENDERNAME']=$val['value'];
   if($val['tag']=='NAME' )
       $tag['NAME']=$val['value'];
   if($val['tag']=='ADDRESS' )
       $tag['ADDRESS']=$val['value'];
   if($val['tag']=='TOWN' )
       $tag['TOWN']=$val['value'];
   if($val['tag']=='TNTCONNO' )
       $tag['TNTCONNO']=$val['value'];
   if($val['tag']=='DATE' )
       $tag['DATE']=$val['value'];
   if($val['tag']=='DEPOTID' )
       $tag['DEPOTID']=$val['value'];
   if($val['tag']=='DEPOTNAME' )
       $tag['DEPOTNAME']=$val['value'];
   if($val['tag']=='SERVICE' )
       $tag['SERVICE']=$val['value'];
   if($val['tag']=='DESTINATIONHUB' )
       $tag['DESTINATIONHUB']=$val['value'];
   if($val['tag']=='SPECIALGOODS' )
       $tag['SPECIALGOODS']=$val['value'];
   if($val['tag']=='MICROZONE' )
       $tag['MICROZONE']=$val['value'];
   if($val['tag']=='OPERATIONALOPTION' )
       $tag['OPERATIONALOPTION']=$val['value'];
   if($val['tag']=='ITEMINCRNO' )
       $tag['ITEMINCRNO']=$val['value'];
   if($val['tag']=='BARCODE' )
       $tag['BARCODE']=$val['value'];
   if($val['tag']=='ITEMSEQUENCENO' )
       $tag['ITEMSEQUENCENO']=$val['value'];
   if($val['tag']=='CODE' )
       $tag['CODE']=$val['value'];
   if($val['tag']=='MESSAGE' )
       $tag['MESSAGE']=$val['value'];
   if($val['tag']=='CUSTOMERREF' )
       $tag['CUSTOMERREF']=$val['value'];

}
$idorig_res=$adb->pquery("Select idorig "
        . " from  vtiger_project"
        . " where vtiger_project.projectid=?",array($id));
$idorig=$adb->query_result($idorig_res,0,'idorig');
$adb->pquery("Update vtiger_project"
                    . " set ldvreso=?,"
                    . " codicereso=?"
                    . " where vtiger_project.idorig=?",array($tag['TNTCONNO'],$tag['BARCODE'],$idorig));       
return  $tag;
//try { 
//     $gsearch = new 
//     SoapClient('https://www.mytnt.it/ResiService/ResiServiceImpl.wsdl'); 
//     $result = $gsearch->__soapCall('getPDFLabel', array(array('inputXml'=>$xml))); 
//     $result2 = $result->getPDFLabelReturn; 
//     if ($result2->documentCorrect == 1 && strlen($result2->binaryDocument)>0){ 
//          file_put_contents('/var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf', $result2->binaryDocument);
////         header('Content-Description: File Transfer'); 
////         header('Content-Type: application/pdf'); 
////         header('Content-Disposition: attachment; filename="storage/label'.$id.'.pdf"'); 
////         header('Expires: 0'); 
////         echo $result2->binaryDocument; 
//     } else { 
//        exec('rm -rf /var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf');
//        $pdf=new FPDF();
//        $pdf->AddPage();
//        $pdf->SetFont('Arial','B',16);
//        $pdf->Cell(40,10,$result2->outputString);
//        $pdf->Output("/var/www/eprice/storage/ModuloDiReso_$id.pdf", "F");
////         header('Content-Type: text/xml'); 
////         header('Expires: 0'); 
////         echo $result2->outputString; 
//     } 
//     unset($gsearch); 
//} 
//catch (SoapFault $e) { 
////     header('Content-Type: text/html'); 
////     header('Expires: 0');
//        exec('rm -rf /var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf');
//        $pdf=new FPDF();
//        $pdf->AddPage();
//        $pdf->SetFont('Arial','B',16);
//        $pdf->Cell(40,10,$e->faultcode." ". $e->faultstring);
//        $pdf->Output("/var/www/eprice/storage/ModuloDiReso_'.$id.'.pdf", "F");
////         echo $e->faultcode." ". $e->faultstring;
//         print_r($e); 
//} 


// checkAddress 016443779

}
