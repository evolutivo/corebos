<?php
//============================================================+
// File name   : example_032.php
// Begin       : 2008-06-09
// Last Update : 2013-06-19
//
// Description : Example 032 for TCPDF class
//               EPS/AI image
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: EPS/AI image
 * @author Nicola Asuni
 * @since 2008-06-09
 */

// Include the main TCPDF library (search for installation path).
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
global $adb,$log,$current_user;

$recordid=$_REQUEST['recordid'];
$mapid=$_REQUEST['map'];
$str= $_SERVER['HTTP_REFERER'];

 $importantStuff = explode('&record=', $str);

 $record = $recordid;//$importantStuff[1];
 
$mapfocus=  CRMEntity::getInstance("Map");
$mapfocus->retrieve_entity_info($mapid,"Map");
$sql=$mapfocus->getMapSQL();
$result = $adb->pquery($sql,array($record));
$nr=$adb->num_rows($result);
$name1= $adb->query_result($result,0,'accountname');
$projectno= $adb->query_result($result,0,'praticaid');
$projectno=str_pad($projectno,12,"0",STR_PAD_LEFT);
$projectno='EPR'.$projectno;
$commessa =$adb->query_result($result,0,'processtemplatename');
//$bar=$commessa.'   '.$projectno;
$bar=$projectno;
$street=$adb->query_result($result,0,'ship_street');
$code=$adb->query_result($result,0,'ship_code');
$city=$adb->query_result($result,0,'ship_city');
$state=$adb->query_result($result,0,'ship_state');
$islockable=$adb->query_result($result,0,'islockable');
$productdesc=$adb->query_result($result,0,'productdesc');
$difetto=$adb->query_result($result,0,'description');
$nr1=$nr-1;
$original_date=$adb->query_result($result,$nr1,'startdate');
$dataaut=date('d-m-Y', strtotime('+15 days', strtotime($original_date)));

$brnd=$adb->query_result($result,0,'linktorasc');
require('include/tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 032');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 032', 'juljan dimo bingo');
$pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);





// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

$pdf->AddPage();
if($brnd==9767){
$html = <<<EOD
       
        <br />
DESTINATARIO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MITTENTE
 <br />
ePRICE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <br />   
        Teknema SRL
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$name1;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
      <br /> 
        Via Petrarca, 2
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$street;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
      <br /> 
        20058 Villasanta MB
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$code.' '.$city.' ('.$state.')';
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
}else{
    $html = <<<EOD

   <br />
DESTINATARIO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MITTENTE
 <br />
ePRICE &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <br />   
        c/o Tecnoassist  
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$name1;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
      <br /> 
        Via Bergamo, 6 
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$street;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
      <br /> 
        21013 Gallarate (VA)  
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
EOD;
$html=$html.$code.' '.$city.' ('.$state.')';
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
}
$html = <<<EOD
      <br /><br /> 
        <hr />
EOD;
$html=$html.$commessa;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
        <br />

EOD;

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
require('include/tcpdf/tcpdf_barcodes_1d.php');

// set the barcode content and type
$barcodeobj = "http://www.tcpdf.org";

// output the barcode as HTML object


$pdf->write1DBarcode($bar, 'C128', '', '', '', 15, 0.4, $style, 'N');


$html = <<<EOD
       
        <br /><br />
        
EOD;
$html=$html.$projectno;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->Image('include/tcpdf/images/E_verdhe.png', 154, 15, 17,17, '','','',true,300,'R');
$pdf->Image('include/tcpdf/images/gershera.png', 200, 95, 180,15, '','','',true,300,'R');
$html = <<<EOD
                 <hr />
        <br /><br /><br /><br /><br />DETTAGLIO RESO
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

EOD;
$html=$html.$commessa;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD

        <br />
<br /><br /> 
       
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$style = array(
    'position' => 'R',
    'align' => 'C',
    'stretch' => false,
    'fitwidth' => true,
    'cellfitalign' => '',
    'border' => false,
    'hpadding' => 'auto',
    'vpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255),
    'text' => false,
    'font' => 'helvetica',
    'fontsize' => 8,
    'stretchtext' => 4
);
$pdf->write1DBarcode($bar, 'C128', '', '', '', 15, 0.4, $style, 'N');

$html = <<<EOD

        <br />
<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       

       
EOD;
$html=$html.$projectno;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$html = <<<EOD
        <br /> <br />ARTICOLI<br />

<br />
EOD;
      
  $html=$html.$productdesc;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);  
   
$html = <<<EOD
        <br />
<br />MOTIVO<br />
<br />

EOD;
        
  $html=$html.$difetto;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);  
            
        
$html = <<<EOD
<br /><br /><br /><br /><br /><br />IMPORTANTE<br />
<br />Il reso potrà essere effettuato entro il 

EOD;
     
     
     $html=$html.$dataaut;   
          



       

       


$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
if($islockable=='S'){
$html = <<<EOD


<br /><br />Se vuoi rendere tramite i nostri Locker ricorda che il pacco non dovrà superare le dimensioni<br />
massime di 38 cm x 38 cm x 64 cm.<br />

       
EOD;

$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);}


// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_032.pdf', 'I');
$pdf->Output('storage/ModuloDiReso_'.$recordid.'.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+
//ini_set('display_errors','on');
//// Include the main TCPDF library (search for installation path).
//include_once('data/CRMEntity.php');
//include_once('modules/Map/Map.php');
//require_once('include/utils/utils.php');
//require_once('include/database/PearDatabase.php');
//require_once('include/fpdf1/fpdf.php');
//require_once('include/tcpdf/fpdi.php');
//require_once('include/tcpdf/tcpdf.php');
//require_once('modules/Actions/scripts/tntIntegration.php');
//global $adb,$log,$current_user;
//
//$recordid=$_REQUEST['recordid'];
//$mapid=$_REQUEST['map'];
//$str= $_SERVER['HTTP_REFERER'];
//
// $importantStuff = explode('&record=', $str);
//
// $record = $recordid;//$importantStuff[1];
// 
//$mapfocus=  CRMEntity::getInstance("Map");
//$mapfocus->retrieve_entity_info($mapid,"Map");
//$sql=$mapfocus->getMapSQL();
//$result = $adb->pquery($sql,array($record));
//$nr=$adb->num_rows($result);
//$name1= $adb->query_result($result,0,'accountname');
//$projectno= $adb->query_result($result,0,'praticaid');
//$projectno=str_pad($projectno,12,"0",STR_PAD_LEFT);
//$projectno='EPR'.$projectno;
//$commessa =$adb->query_result($result,0,'processtemplatename');
////$bar=$commessa.'   '.$projectno;
//$bar=$projectno;
//$street=$adb->query_result($result,0,'ship_street');
//$code=$adb->query_result($result,0,'ship_code');
//$city=$adb->query_result($result,0,'ship_city');
//$state=$adb->query_result($result,0,'ship_state');
//$ship_country=$adb->query_result($result,0,'ship_country');
//
//$add_rasc=$adb->query_result($result,0,'indirizzo');
//$postcode_rasc=$adb->query_result($result,0,'cap');
//$name_rasc=$adb->query_result($result,0,'regionalascname');
//$country_rasc=$adb->query_result($result,0,'stato');
//$town_rasc=$adb->query_result($result,0,'citta');
//$province_rasc=$adb->query_result($result,0,'regione');
//
//$islockable=$adb->query_result($result,0,'islockable');
//$productdesc=$adb->query_result($result,0,'productdesc');
//$description=$adb->query_result($result,0,'proj_desc');
//$nr1=$nr-1;
//$original_date=$adb->query_result($result,$nr1,'startdate');
//$dataaut=date('d-m-Y', strtotime('+15 days', strtotime($original_date)));
//$lockindex=$adb->query_result($result,0,'lockindex');
//$codicearticolo=$adb->query_result($result,0,'codicearticolo');
//$sitovertical=$adb->query_result($result,0,'sitovertical');
//
//$brnd=$adb->query_result($result,0,'linktorasc');
//$pesogr=$adb->query_result($result,0,'pesogr');
//$idpratica=$adb->query_result($result,0,'pesogr');
//$action='I';
//$arr_tnt=tntIntegration($recordid,$street,$code,$name1,$ship_country,$city,$state
//        ,$add_rasc,$postcode_rasc,$name_rasc,$country_rasc,$town_rasc,$province_rasc,
//        $pesogr,$idpratica,$action);
//if($arr_tnt['CODE']!=''){
//    $action='R';
//    $arr_tnt=tntIntegration($recordid,$street,$code,$name1,$ship_country,$city,$state
//        ,$add_rasc,$postcode_rasc,$name_rasc,$country_rasc,$town_rasc,$province_rasc,
//        $pesogr,$idpratica,$action);
//}
//
//// create new PDF document
//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//
//// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
//$pdf->SetTitle('TCPDF Example 032');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
//
//// set default header data
////$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 032', 'juljan dimo bingo');
//$pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
//// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
////$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
//
//// set default monospaced font
////$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//
//// set margins
//$pdf->SetMargins(15, 15, 15);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
////$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->AddPage();
//// set auto page breaks
////$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//$pdf->SetFont('Helvetica','',8);
//$pdf->SetTextColor(0,0,0);
////$pdf->Rotate(90);
//$pdf->SetXY(90, 10);
//$pdf->SetFont('Helvetica','B',14);
//$pdf->Write(10, $arr_tnt['ORIGINDEPOTID']);
//$pdf->SetXY(90, 15);
//$pdf->SetFont('Helvetica','',9);
//$pdf->Write(10, 'COLLI: '.$arr_tnt['ITEMNO']);
//$pdf->SetXY(90, 19);
//$pdf->Write(10, 'PESO: '.$arr_tnt['WEIGHT']);
//$pdf->Image('/var/www/eprice/themes/images/tnt_logo.jpg',160,10,25);
//$pdf->SetXY(90, 30);
//$pdf->Write(10, 'DA: '.$arr_tnt['SENDERNAME']);
//$pdf->SetXY(90, 34);
//$pdf->Write(10, 'A: '.$arr_tnt['NAME']);
//$pdf->SetXY(90, 38);
//$pdf->Write(10, $arr_tnt['ADDRESS']);
//$pdf->SetFont('Helvetica','',12);
//$pdf->SetXY(90, 48);
//$pdf->Write(10, $arr_tnt['TOWN']);
//$pdf->SetFont('Helvetica','B',28);
//$pdf->SetXY(90, 59);
//$pdf->Write(10, $arr_tnt['ITEMINCRNO'].'  '.$arr_tnt['DEPOTID']);
//$pdf->SetFont('Helvetica','',17);
//$pdf->SetXY(90, 75);
//$pdf->Write(10, $arr_tnt['DEPOTNAME']);
//$pdf->SetFont('Helvetica','',9);
//$pdf->SetXY(160, 30);
//$y=  substr($arr_tnt['DATE'], 0, 4);
//$m=  substr($arr_tnt['DATE'], 4, 2);
//$d=  substr($arr_tnt['DATE'], 6);
//$pdf->Write(10, 'DATA: '.$y.'-'.$m.'-'.$d);
//$pdf->SetXY(160, 34);
//$pdf->Write(10, 'LDV: '.$arr_tnt['TNTCONNO']);
//$pdf->Image('/var/www/eprice/themes/images/blackbox.jpeg',160,45,25);
//$pdf->SetFont('Helvetica','B',28);
//$pdf->SetTextColor(255,255,255);
//$pdf->SetXY(160, 57);
//$pdf->Write(20, $arr_tnt['DESTINATIONHUB']);
//$pdf->SetFont('Helvetica','B',22);
//$pdf->SetTextColor(0,0,0);
//$pdf->SetXY(160, 64);
//$pdf->Write(20, $arr_tnt['MICROZONE']);
//$pdf->SetFont('Helvetica','',9);
//$pdf->SetXY(90, 78);
//$pdf->Write(20, 'R.C.:'.$arr_tnt['CUSTOMERREF']);
//$pdf->SetFont('Helvetica','B',9);
//$style = array(
//    'text' => true,
//    'font' => 'helvetica',
//    'fontsize' => 9,
//    'stretchtext'  => 2
//);
//$pdf->write1DBarcode($arr_tnt['BARCODE'], 'C128',110, 95, 85, 35, 0.4, $style, 'N');
//
//$pdf->SetTextColor(0,0,0);
//$pdf->SetFont('Helvetica','',9);
//if($sitovertical=='SaldiPrivati')
//{
//    $pdf->Image('/var/www/eprice/themes/images/saldi.png',15,20,40);
//}
//else {
//    $pdf->Image('/var/www/eprice/include/tcpdf/images/E_verdhe.png',15,20,40);
//}
//
//$pdf->SetXY(10, 85);
//$pdf->Write(10, "ePRICE fa parte del gruppo Banzai");
//$pdf->Image('/var/www/eprice/themes/images/all_logo.jpg',10,95,25);
//$pdf->Image('/var/www/eprice/themes/images/gershera.jpg',10,138,190);
//$pdf->SetXY(40, 170);
//$pdf->SetFont('Helvetica','B',12);
//$pdf->Write(10, $commessa);
//$pdf->SetFont('Helvetica','B',9);
//$style = array(
//    'text' => true,
//    'font' => 'helvetica',
//    'fontsize' => 9,
//    'stretchtext'  => 2
//);
//$pdf->write1DBarcode($bar, 'C128',20,180, 75, 25, 0.4, $style, 'N');
//
//
//$pdf->SetFont('Helvetica','B',11);
//$pdf->SetXY(20, 210);
//$pdf->Write(10, 'ARTICOLI RESI');
//$pdf->SetFont('Helvetica','',9);
//$pdf->SetXY(20, 218);
//$p1=  $codicearticolo.' - '.$productdesc;
//$p2=  wordwrap($p1,45,'
//');
//$p3=  wordwrap(substr($description,0,300),45,'
//');
//$pdf->Multicell(0,3.5,"$p2
//    
//$p3");
//
//$pdf->Image('/var/www/eprice/themes/images/line_logo.jpg',100,180,10);
//
//$pdf->SetFont('Helvetica','B',10);
//$pdf->SetXY(110, 180);
//$pdf->Write(5, 'EFFETTUA IL RESO ENTRO IL '.$dataaut);
//$pdf->SetFont('Helvetica','',9);
//$pdf->SetXY(110, 183);
//$pdf->Write(5, 'scegliendo tra le opzioni disponibili:');
//
//$pdf->Image('/var/www/eprice/themes/images/checkbox.png',110,195,8);
//$pdf->SetFont('Helvetica','B',8);
//$pdf->SetXY(120, 195);
//$pdf->Write(5, 'PRENOTA IL RITIRO A CASA TUA');
//$pdf->SetFont('Helvetica','',8);
//$pdf->SetXY(120, 200);
//$pdf->Write(5, 'fissa la data e l\'ora del ritiro con TNT chiamando');
//$pdf->SetXY(120, 203);
//$pdf->Write(5, 'il numero 800 900 800 oppure sul il sito www.tnt.it.');
//$pdf->SetXY(120, 206);
//$pdf->Write(5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.');
//$pdf->SetXY(120, 209);
//$pdf->Write(5, 'Praesent elit justo, rutrum consequat quam eu, aliquam');
//$pdf->SetXY(120, 212);
//$pdf->Write(5, 'pharetra neque. Aenean porttitor accumsan turpis ac');
//$pdf->SetXY(120, 215);
//$pdf->Write(5, 'placerat. Nullam in fringilla purus.');
//
//if($lockindex<=5){
//    $pdf->Image('/var/www/eprice/themes/images/checkbox.png',110,225,8);
//    $pdf->SetFont('Helvetica','B',8);
//    $pdf->SetXY(120, 225);
//    $pdf->Write(5, 'CONSEGNA AL LOCKER');
//    $pdf->SetFont('Helvetica','',8);
//    $pdf->SetXY(120, 230);
//    $pdf->Write(5, 'il Locker piu vicino a te è:');
//    $pdf->SetXY(120, 233);
//    $pdf->Write(5, 'Locker Carrefour Gallarate');
//    $pdf->SetXY(120, 236);
//    $pdf->Write(5, 'Via Carlo Noe, 21013 Gallarate (VA)');
//    $pdf->SetXY(120, 239);
//    $pdf->Write(5, 'aperto 24/7');
//    $pdf->SetXY(120, 242);
//    $pdf->Write(5, 'oppure scegli quello più comodo per te sul nostro sito.');
//    $pdf->SetXY(120, 245);
//    $pdf->Write(5, 'In questo caso ricorda che il pacco non dovra superare le');
//    $pdf->SetXY(120, 248);
//    $pdf->Write(5, 'dimensioni massime di 38 cm x 38 cm x 64 cm.');
//}else
//{
//    $pdf->Image('/var/www/eprice/themes/images/checkbox.png',110,225,8);
//    $pdf->SetFont('Helvetica','B',8);
//    $pdf->SetXY(120, 225);
//    $pdf->Write(5, 'CONSEGNA AL PICK&PAY');
//    $pdf->SetFont('Helvetica','',8);
//    $pdf->SetXY(120, 230);
//    $pdf->Write(5, 'il Pick&Pay più vicino a te è:');
//    $pdf->SetXY(120, 233);
//    $pdf->Write(5, 'Pick&Pay Gallarate (Loc. Cascinetta)');
//    $pdf->SetXY(120, 236);
//    $pdf->Write(5, 'Via Pegoraro 19/A, 21013 Gallarate (VA)');
//    $pdf->SetXY(120, 239);
//    $pdf->Write(5, 'orari apertura: lun-ven 9:00-13:00 14:00-19:00; sab');
//    $pdf->SetXY(120, 242);
//    $pdf->Write(5, '8:30-13:00');
//    $pdf->SetXY(120, 245);
//    $pdf->Write(5, 'oppure scegli quello più  comodo per te sul nostro sito');
//}
//
//
//
////Close and output PDF document
//$pdf->Output('example_032.pdf', 'I');
//$pdf->Output('storage/ModuloDiReso_'.$recordid.'.pdf', 'F');

//============================================================+
// END OF FILE
//============================================================+
