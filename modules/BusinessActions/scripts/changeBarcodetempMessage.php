<?php

include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/fpdf1/fpdf.php');
require_once('include/tcpdf/fpdi.php');
require_once('include/tcpdf/tcpdf.php');
require_once('modules/Actions/scripts/tntIntegration.php');
function generate_barcode($recordid,$arr_tnt){
ini_set('display_errors','on');
// Include the main TCPDF library (search for installation path).

global $adb,$log,$current_user;

$mapid='13434';
$record = $recordid;//$importantStuff[1];
 
    $mapfocus=  CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($mapid,"Map");
    $sql=$mapfocus->getMapSQL();
    $result = $adb->pquery($sql,array($record));
    $nr=$adb->num_rows($result);
    $name1= $adb->query_result($result,0,'accountname');
    $projectno= $adb->query_result($result,0,'praticaid');
    $projectno=str_pad($projectno,12,"0",STR_PAD_LEFT);
    $commessa =$adb->query_result($result,0,'processtemplatename');
    //$bar=$commessa.'   '.$projectno;
    $bar=$projectno;
    $street=$adb->query_result($result,0,'ship_street');
    $code=$adb->query_result($result,0,'ship_code');
    $city=$adb->query_result($result,0,'ship_city');
    $state=$adb->query_result($result,0,'ship_state');
    $ship_country=$adb->query_result($result,0,'ship_country');

    $add_rasc=$adb->query_result($result,0,'indirizzo');
    $postcode_rasc=$adb->query_result($result,0,'cap');
    $name_rasc=$adb->query_result($result,0,'regionalascname');
    $country_rasc=$adb->query_result($result,0,'stato');
    $town_rasc=$adb->query_result($result,0,'citta');
    $province_rasc=$adb->query_result($result,0,'provincia');

    $islockable=$adb->query_result($result,0,'islockable');
    $productdesc=$adb->query_result($result,0,'productdesc');
    $description=$adb->query_result($result,0,'proj_desc');
    $original_date=$adb->query_result($result,0,'dataautorizzazione');
    
    $lockindex=$adb->query_result($result,0,'lockindex');
    $codicearticolo=$adb->query_result($result,0,'codicearticolo');
    $sitovertical=$adb->query_result($result,0,'sitovertical');
    $sitoprovenienza=$adb->query_result($result,0,'sitoprovenienza');
    $purchasecostdetail=$adb->query_result($result,0,'purchasecostdetail');

    $brnd=$adb->query_result($result,0,'linktorasc');
    $pesogr=$adb->query_result($result,0,'pesogr');
    $idpratica=$adb->query_result($result,0,'praticaid');
    $idorig=$adb->query_result($result,0,'idorig');

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
$pdf->AddPage();
// set auto page breaks
//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->SetFont('Helvetica','',8);
$pdf->SetTextColor(0,0,0);
//$pdf->Rotate(90);
if($sitoprovenienza!='SaldiPrivati'){
    $bar='EPR'.$bar;
}
else{
    $bar=$bar;
}
$pdf->SetXY(90, 10);
$pdf->SetFont('Helvetica','B',14);
$pdf->Write(10, $arr_tnt['ORIGINDEPOTID']);
$pdf->SetXY(90, 15);
$pdf->SetFont('Helvetica','',9);
$pdf->Write(10, 'COLLI: '.$arr_tnt['ITEMNO']);
$pdf->SetXY(90, 19);
$pdf->Write(10, 'PESO: '.$arr_tnt['WEIGHT']);
$pdf->Image('/var/www/eprice/themes/images/tnt_logo.jpg',160,10,25);
$pdf->SetXY(90, 30);
$pdf->Write(10, 'DA: '.$arr_tnt['SENDERNAME']);
$pdf->SetXY(90, 34);
$pdf->Write(10, 'A: '.$arr_tnt['NAME']);
$pdf->SetXY(90, 38);
$pdf->Write(10, $arr_tnt['ADDRESS']);
$pdf->SetFont('Helvetica','',12);
$pdf->SetXY(90, 48);
$pdf->Write(10, $arr_tnt['TOWN']);
$pdf->SetFont('Helvetica','B',28);
$pdf->SetXY(90, 59);
$pdf->Write(10, $arr_tnt['ITEMINCRNO'].'  '.$arr_tnt['DEPOTID']);
$pdf->SetFont('Helvetica','',17);
$pdf->SetXY(90, 75);
$pdf->Write(10, $arr_tnt['DEPOTNAME']);
$pdf->SetFont('Helvetica','',9);
$pdf->SetXY(160, 30);
$y=  substr($arr_tnt['DATE'], 0, 4);
$m=  substr($arr_tnt['DATE'], 4, 2);
$d=  substr($arr_tnt['DATE'], 6);
$pdf->Write(10, 'DATA: '.$y.'-'.$m.'-'.$d);
$pdf->SetXY(160, 34);
$pdf->Write(10, 'LDV: '.$arr_tnt['TNTCONNO']);
$pdf->Image('/var/www/eprice/themes/images/blackbox.jpeg',160,45,25);
$pdf->SetFont('Helvetica','B',28);
$pdf->SetTextColor(255,255,255);
$pdf->SetXY(160, 57);
$pdf->Write(20, $arr_tnt['DESTINATIONHUB']);
$pdf->SetFont('Helvetica','B',22);
$pdf->SetTextColor(0,0,0);
$pdf->SetXY(160, 64);
$pdf->Write(20, $arr_tnt['MICROZONE']);
$pdf->SetFont('Helvetica','',9);
$pdf->SetXY(90, 78);
$pdf->Write(20, 'R.C.:'.$bar);
$pdf->SetFont('Helvetica','B',9);
$style = array(
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 9,
    'stretchtext'  => 2
);
$pdf->write1DBarcode($arr_tnt['BARCODE'], 'C128',110, 95, 85, 35, 0.4, $style, 'N');

$pdf->SetTextColor(0,0,0);
$pdf->SetFont('Helvetica','',9);
if($lockindex<=4){
            $x=15;
            $y=13;
            $pdf->SetFont('Helvetica','B',9);
            $style = array(
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 9,
                'stretchtext'  => 2
            );
            $pdf->SetXY(10, 30);
            $pdf->Write(10, "CODICE A BARRE PER LOCKER");
            $pdf->write1DBarcode($bar, 'C128',10,40, 75, 25, 0.4, $style, 'N');            
        }
        else{
            $x=15;
            $y=20;
        }
        if($sitovertical=='SaldiPrivati')
        {
            $pdf->Image('/var/www/eprice/themes/images/saldi.png',$x,$y,30);
            }
        elseif($sitovertical=='Vico42'){
            $pdf->Image('/var/www/eprice/themes/images/vico.jpg',$x,$y,30);
        }
        elseif($sitovertical=='Mami'){
            $pdf->Image('/var/www/eprice/themes/images/mami.jpg',$x,$y,30);
        }
        elseif($sitovertical=='BOW'){
            $pdf->Image('/var/www/eprice/themes/images/logo-bow.jpg',$x,$y,30);
        }
        elseif($sitovertical=='MisterPRICE'){
            $pdf->Image('/var/www/eprice/themes/images/logo-mistereprice.jpg',$x,$y,30);
        }
        elseif($sitovertical=='ePLAZA'){
            $pdf->Image('/var/www/eprice/themes/images/eplaza.jpg',$x,$y,30);
        }
        else {
            $sitovertical='ePRICE';
            $pdf->Image('/var/www/eprice/include/tcpdf/images/E_verdhe.png',$x,$y,30);
        }

$pdf->SetXY(10, 75);
$pdf->Write(10, "$sitovertical fa parte del gruppo Banzai");
$pdf->Image('/var/www/eprice/themes/images/all_logos.jpg',10,85,25);
$pdf->Image('/var/www/eprice/themes/images/gershera.jpg',20,138,160,13);
$pdf->SetXY(40, 170);
$pdf->SetFont('Helvetica','B',12);
$pdf->Write(10, $commessa);
$pdf->SetFont('Helvetica','B',9);
$style = array(
    'text' => true,
    'font' => 'helvetica',
    'fontsize' => 9,
    'stretchtext'  => 2
);

$pdf->write1DBarcode($bar, 'C128',20,180, 75, 25, 0.4, $style, 'N');

$pdf->SetFont('Helvetica','B',11);
$pdf->SetXY(20, 210);
$pdf->Write(10, 'ARTICOLI RESI');
$pdf->SetFont('Helvetica','',8);
    
if($sitoprovenienza=='SaldiPrivati'){
    $result_idorig = $adb->pquery('Select distinct(codicearticolo),productdesc  '
        . ' from vtiger_project'
        . ' join vtiger_crmentity c1 on c1.crmid=projectid'
        . ' where idorig=? and c1.deleted=0'
        . ' group by codicearticolo',array($idorig));
    $nr_pd=$adb->num_rows($result_idorig);
    $p1='';$p2='';$p3='';$p4='';
    for($c_p=0;$c_p<$nr_pd;$c_p++){
        $codicearticolo=$adb->query_result($result_idorig,$c_p,'codicearticolo');
        $productdesc=$adb->query_result($result_idorig,$c_p,'productdesc');
        $p1=  $codicearticolo.' - '.$productdesc;
        $p2=  wordwrap($p1,45,'
');
        $p4.="
$p2";
    }
    $p3=  wordwrap(substr($description,0,200),45,'
');
    $p4.="


$p3";
    $pdf->SetXY(20, 218);
    $pdf->Multicell(0,3.5,"$p4",0,'L');
}
else{
    $pdf->SetXY(20, 218);
    $p1=  $codicearticolo.' - '.$productdesc;
    $p2=  wordwrap($p1,45,'
');
    $p3=  wordwrap(substr($description,0,200),45,'
');
    $pdf->Multicell(0,3.5,"$p2


$p3",0,'L');
}

$pdf->Image('/var/www/eprice/themes/images/line_logo.jpg',100,180,5,90);

$pdf->SetFont('Helvetica','B',10);
$pdf->SetXY(105, 180);
if($sitovertical=='Vico42'){
$dataaut=(!empty($original_date) ? date('d-m-Y', strtotime('+30 days', strtotime($original_date))) : '');
}
else{
$dataaut=(!empty($original_date) ? date('d-m-Y', strtotime('+30 days', strtotime($original_date))) : ''); 
}
$pdf->Write(5, 'EFFETTUA IL RESO ENTRO IL '.$dataaut);
$pdf->SetFont('Helvetica','',9);
$pdf->SetXY(105, 183);
$pdf->Write(5, 'scegliendo tra le opzioni disponibili:');

                $pdf->Image('/var/www/eprice/themes/images/checkbox.png',105,192,5);
        $pdf->SetFont('Helvetica','B',8);
        $pdf->SetXY(110, 192);
        $pdf->Write(5, 'PRENOTA IL RITIRO A CASA TUA');
        $pdf->SetFont('Helvetica','',8);
        $pdf->SetXY(110, 195);
        $pdf->Write(5, 'Chiama il nostro trasportatore TNT al numero 199.803.868');
        if(($commessa=='RECESSO' || $commessa=='RMA') && strtolower($sitoprovenienza)=='eprice' ){
        }
        else{
            $pdf->SetXY(110, 198);
            $pdf->Write(5, 'comunicando il ritiro A CARICO DEL DESTINATARIO.');
        }
        $pdf->SetXY(110, 201);
        $pdf->Write(5, 'Puoi anche collegarti al sito www.tnt.it alla sezione');
        $pdf->SetXY(110, 204);
        $pdf->Write(5, '“Strumenti on-line > Prenota ritiro in Italia” indicando tutti');
        $pdf->SetXY(110, 207);
        $pdf->Write(5, 'i dati necessari e selezionando le spese di spedizione');
        if(($commessa=='RECESSO' || $commessa=='RMA') && strtolower($sitoprovenienza)!='saldiprivati' ){
        }
        else{
            $pdf->SetXY(110, 210);
            $pdf->Write(5, 'a CARICO DEL DESTINATARIO.');
        }
        //$pdf->SetXY(110, 218);
        //$pdf->Write(5, '');
  if($lockindex<=9){

    $pdf->Image('/var/www/eprice/themes/images/checkbox.png',105,217,5);
    $pdf->SetFont('Helvetica','B',8);
    $pdf->SetXY(110, 217);
    $pdf->Write(5, 'CONSEGNA AL PICK&PAY®');
    $pdf->SetFont('Helvetica','',8);
    $pdf->SetXY(110, 220);
    $pdf->Write(5, 'Puoi rendere il prodotto in uno qualsiasi dei nostri Pick&Pay.');
    $pdf->SetXY(110, 223);
    $pdf->Write(5, 'Trovi la lista completa dei Pick&Pay sul nostro sito.');
    $pdf->SetXY(110, 226);
    $pdf->Write(5, 'Solo scegliendo questa modalità di restituzione potrai consegnare');
    $pdf->SetXY(110, 229);
    $pdf->Write(5, 'all’incaricato il prodotto privo di imballo esterno.');
    $pdf->SetXY(110, 232);
    $pdf->Write(5, 'Saranno come sempre necessari etichette e confezione originale');
    $pdf->SetXY(110, 235);
    $pdf->Write(5, 'del prodotto. Ti suggeriamo di verificare sul sito gli orari ');
    $pdf->SetXY(110, 238);
    $pdf->Write(5, 'di apertura del Pick&Pay prima di recarti per il reso.');
   // $pdf->SetXY(110, 240);
  // $pdf->Write(5, '');
  //  $pdf->SetXY(110, 243);
   // $pdf->Write(5, '');
}
 if($lockindex<=4){
            $pdf->Image('/var/www/eprice/themes/images/checkbox.png',105,246,5);
            $pdf->SetFont('Helvetica','B',8);
            $pdf->SetXY(110, 246);
            $pdf->Write(5, 'CONSEGNA AL LOCKER');
            $pdf->SetFont('Helvetica','',8);
            $pdf->SetXY(110, 249);
            $pdf->Write(5, 'Scegli il Locker più comodo per te dal nostro sito.');
            $pdf->SetXY(110, 252);
            $pdf->Write(5, 'I Locker sono punti di ritiro automatici,funzionanti 24 ore');
            $pdf->SetXY(110, 255);
            $pdf->Write(5, 'su 24, 7 giorni su 7.La scatola non dovrà superare le');
            $pdf->SetXY(110, 258);
            $pdf->Write(5, 'dimensioni massime di 38 cm x 38 cm x 64 cm.,');
            $pdf->SetXY(110, 261);
            $pdf->Write(5, 'altrimenti non sarà possibile depositare il tuo reso nel');
            $pdf->SetXY(110, 264);
            $pdf->Write(5, 'Locker.');

        }
if($lockindex>50){
    
    
}




//Close and output PDF document
$pdf->Output('storage/Etichette_Reso_'.$recordid.'.pdf', 'F');
}
