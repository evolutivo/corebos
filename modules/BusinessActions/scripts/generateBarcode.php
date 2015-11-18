<?php

function generateBarcode($param){
//ini_set('display_errors','on');
// Include the main TCPDF library (search for installation path).
include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/fpdf1/fpdf.php');
require_once('include/tcpdf/fpdi.php');
require_once('include/tcpdf/tcpdf.php');
require_once('modules/Actions/scripts/tntIntegration.php');
require_once("modules/Documents/Documents.php");
global $adb,$log,$current_user,$root_directory,$site_URL,$date_var;

$recordid=$param['recordid'];
$mapid='13434';
    
// $importantStuff = explode('&record=', $str);

 $record = $param['recordid'];//$importantStuff[1];
    $pos=strpos($recordid,',');
    if($pos!==false)
    {
       $allrecordsID=explode(',',$recordid);
    }
    else{
       $allrecordsID=array($recordid);
    }
    
    $mapfocus=  CRMEntity::getInstance("Map");
    $mapfocus->retrieve_entity_info($mapid,"Map");
    $sql=$mapfocus->getMapSQL();
    $nr_rec=sizeof($allrecordsID);
    $documents=array();
    $projects=array();
for($c_size=0;$c_size<$nr_rec;$c_size++){
    $record=$allrecordsID[$c_size];
    
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
    $province_rasc=$adb->query_result($result,0,'regione');

    $islockable=$adb->query_result($result,0,'islockable');
    $productdesc=$adb->query_result($result,0,'productdesc');
    $description=$adb->query_result($result,0,'proj_desc');
    $original_date=$adb->query_result($result,0,'dataautorizzazione');
    
    $lockindex=$adb->query_result($result,0,'lockindex');
    $codicearticolo=$adb->query_result($result,0,'codicearticolo');
    $sitovertical=$adb->query_result($result,0,'sitovertical');
    $sitoprovenienza=$adb->query_result($result,0,'sitoprovenienza');
    $purchasecostdetail=$adb->query_result($result,0,'purchasecostdetail');
    $data_reso_pp=$adb->query_result($result,0,'data_reso_pp');

    $brnd=$adb->query_result($result,0,'linktorasc');
    $pesogr=$adb->query_result($result,0,'pesogr');
    $idpratica=$adb->query_result($result,0,'praticaid');
    $idorig=$adb->query_result($result,0,'idorig');
    $tnterror=$adb->query_result($result,0,'tnterror');
    
   
    
//    if($tnterror!='1'){
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
        $pdf->SetFont('Helvetica','',12);
        $pdf->SetTextColor(0,0,0);
        //$pdf->Rotate(90);

        if($sitoprovenienza!='SaldiPrivati'){
            $bar='EPR'.$bar;
        }
        else{
            $bar=$bar;
        }
        $pdf->addTTFfont('include/tcpdf/arial.ttf', 'TrueTypeUnicode', '', 32);
        if($brnd==9767){
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(90, 20);
            $pdf->Write(10, 'Centro assistenza di destinazione:');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 40);
            $pdf->Write(10, 'Teknema SRL');
            $pdf->SetXY(90, 50);
            $pdf->Write(10, 'Via Petrarca, 2');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 60);
            $pdf->Write(10, 'Villasanta MB      20058');
            $pdf->SetXY(90, 70);
            $pdf->Write(10, 'Data Consegna al PP '.$data_reso_pp);
            $pdf->SetXY(90, 80);
            $pdf->Write(10, $bar);
        }
        elseif($brnd==11068734){
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(90, 20);
            $pdf->Write(10, 'Centro assistenza di destinazione:');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 40);
            $pdf->Write(10, 'SaldiPrivati');
            $pdf->SetXY(90, 50);
            $pdf->Write(10, 'Via Buozzi N.5');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 60);
            $pdf->Write(10, 'Caleppio di Settala      20090');
            $pdf->SetXY(90, 70);
            $pdf->Write(10, 'Data Consegna al PP '.$data_reso_pp);
            $pdf->SetXY(90, 80);
            $pdf->Write(10, $bar);
        }else{
            $pdf->SetFont('Arial','B',12);
            $pdf->SetXY(90, 20);
            $pdf->Write(10, 'Centro assistenza di destinazione:');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 40);
            $pdf->Write(10, 'Tecnoassist');
            $pdf->SetXY(90, 50);
            $pdf->Write(10, 'Via Bergamo, 6 ');
            $pdf->SetFont('Arial','',12);
            $pdf->SetXY(90, 60);
            $pdf->Write(10, 'Gallarate      21013');
            $pdf->SetXY(90, 70);
            $pdf->Write(10, 'Data Consegna al PP '.$data_reso_pp);
            $pdf->SetXY(90, 80);
            $pdf->Write(10, $bar);
        }
        $pdf->SetTextColor(0,0,0);
        $pdf->SetFont('Helvetica','',9);
        if($sitovertical=='SaldiPrivati')
        {
            $pdf->Image('/var/www/eprice/themes/images/saldi.png',15,20,40);
            }
        elseif($sitovertical=='Vico42'){
            $pdf->Image('/var/www/eprice/themes/images/vico.jpg',15,20,40);
        }
        elseif($sitovertical=='Mami'){
            $pdf->Image('/var/www/eprice/themes/images/mami.jpg',15,20,40);
        }
        elseif($sitovertical=='BOW'){
            $pdf->Image('/var/www/eprice/themes/images/logo-bow.jpg',15,20,40);
        }
        elseif($sitovertical=='MisterPRICE'){
            $pdf->Image('/var/www/eprice/themes/images/logo-mistereprice.jpg',15,20,40);
        }
        elseif($sitovertical=='ePLAZA'){
            $pdf->Image('/var/www/eprice/themes/images/eplaza.jpg',15,20,40);
        }
        else {
            $sitovertical='ePRICE';
            $pdf->Image('/var/www/eprice/include/tcpdf/images/E_verdhe.png',15,20,40);
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

        if($sitoprovenienza=='SaldiPrivati' || $sitoprovenienza=='ePrice'){
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
        if(($commessa=='RECESSO' || $commessa=='RMA') && strtolower($sitoprovenienza)!='saldiprivati' ){
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
        if(($commessa=='RECESSO' || $commessa=='RMA') && strtolower($sitoprovenienza)=='eprice' ){
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

        elseif($lockindex>50){


        }


        //Close and output PDF document
        $dt=date('Y-m-d');
        $filename=$idpratica."_".$dt."_ModuloReso.pdf";
        $pdf->Output("storage/".$filename, 'F');

        if(!in_array($idpratica, $projects)){
            $att_id = $adb->getUniqueID("vtiger_crmentity");
            $upload_file_path = decideFilePath();
            $file=$filename;
            $dstfile=$root_directory.$upload_file_path.$att_id.'_'.$filename;
            chmod($root_directory.'storage/', 0777);
            $ret=copy('storage/'.$filename, $dstfile);

            $sql1 = "insert into vtiger_crmentity
                (crmid,smcreatorid,smownerid,setype,description,createdtime,modifiedtime)
                values(?, ?, ?, ?, ?, ?, ?)";
            $params1 = array($att_id, 1, 1, " Attachment", '', $adb->formatDate($date_var, true), $adb->formatDate($date_var, true));
            $adb->pquery($sql1, $params1);
            $sql2="insert into vtiger_attachments(attachmentsid, name, description, type,path) values(?, ?, ?, ?,?)";
            $params2 = array($att_id, $file, '', 'application/pdf',$upload_file_path);
            $result=$adb->pquery($sql2, $params2);

            $document=new Documents();
            $document->column_fields["notes_title"]=$filename;
            $document->column_fields["notecontent"] ="Description";
            $document->column_fields["filename"] =$file;
            $document->column_fields["filetype"] ="application/pdf";
            $document->column_fields["filelocationtype"]="I";
            $document->column_fields["fileversion"] = 1;
            $document->column_fields["filestatus"] = 1;
            $document->column_fields["filesize"]=169758;
            //$document->column_fields["date"]=$date;
            //$document->column_fields["folderid"]=$folder;
            $document->column_fields["assigned_user_id"] = 1;
            $document->save("Documents");

            $adb->pquery("Insert into vtiger_seattachmentsrel
                    values (?,?)",array($document->id,$att_id));
            $adb->pquery("Insert into vtiger_senotesrel
                    values (?,?)",array($record,$document->id));
            $documents[$idpratica]=$document->id;
        }
        else{
            $adb->pquery("Insert into vtiger_senotesrel
                    values (?,?)",array($record,$documents[$idpratica]));
        }
        $result_idorig = $adb->pquery('Select *  '
            . ' from vtiger_project'
            . ' join vtiger_crmentity c1 on c1.crmid=projectid'
            . ' where idorig=? and c1.deleted=0 and projectid <> ? and idorig <> "" '
            . ' ',array($idorig,$record));
        $nr_pd=$adb->num_rows($result_idorig);
        shell_exec("cd $root_directory");
        for($c_p=0;$c_p<$nr_pd;$c_p++){
            $idPrat_projectid=$adb->query_result($result_idorig,$c_p,'praticaid');
            shell_exec("cp /var/www/eprice/storage/$filename /var/www/eprice/storage/".$idPrat_projectid."_".$dt."_ModuloReso.pdf");
        }
        //$pdf->Output('example_032.pdf', 'I');
        $links[$record]=$site_URL."/storage/".$filename;
        $projects[$record]=$idpratica;
    }
    
    if($nr_rec==1){
        $resp['pdfURL']=$site_URL."/storage/".$filename;
        $resp['pdfURL_sequencer']=$site_URL."/storage/".$filename;
    }
    else{
        $projects=array_unique($projects);
        $zipname="storage/ModuloDiReso.zip";
        shell_exec("rm -rf $zipname");
        $zip = new ZipArchive;
        $zip->open($zipname,ZipArchive::CREATE);
//        for($c=0;$c<sizeof($links);$c++){
        $seq_link=array();
        foreach($projects as $key=>$value){
           $seq_link[]=$site_URL."/storage/".$value."_".$dt."_ModuloReso.pdf";
           $file_name_arr=explode("/",$links[$key]);
           $file_name=$file_name_arr[sizeof($file_name_arr)-1];
           $zip->addFile("storage/$file_name","$file_name");
        }
        $zip->close();
        $resp['pdfURL']=$site_URL."/".$zipname;
        $resp['pdfURL_sequencer']=implode(',',$seq_link);
    }

    
return $resp;
} 
