<?php

include_once('data/CRMEntity.php');
include_once('modules/Map/Map.php');
require_once('include/utils/utils.php');
require_once('include/database/PearDatabase.php');
require_once('include/fpdf1/fpdf.php');
require_once('include/tcpdf/fpdi.php');
require_once('include/tcpdf/tcpdf.php');
require_once('modules/Actions/scripts/tntIntegration.php');
require_once("modules/Documents/Documents.php");

function generatePDFMessage($recordid,$substatus,$content){
    ini_set('display_errors','on');
    global $adb,$log,$current_user,$root_directory,$date_var;

    $mapid='13434';
    $record = $recordid;

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
    $pdf->SetMargins(0, 15, 15);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    //$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->AddPage();
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->SetFont('Helvetica','',8);
    $pdf->SetTextColor(0,0,0);
//    $pdf->SetXY(90, 10);
    $content=  str_replace("'", '"',$content);
    $content=  str_replace("transparent", 'white',$content);
    $html = <<<EOD
$content
EOD;
$html=$html;
//$pdf->writeHTMLCell(9000, 6000, 10, 20, $html, 0, 0, 0, true, 'L', true);
  $pdf->writeHTML(utf8_encode($html), true, 0, true, 0);
    $log->debug('alb1 '.$substatus);      
    //Close and output PDF document
    $pdf->Output('storage/Mail_di_apertura.pdf', 'F');
    $filename='Mail_di_apertura.pdf';
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
    $document->column_fields['docmanagtype'] = 'Mail di apertura';
    //$document->column_fields["date"]=$date;
    //$document->column_fields["folderid"]=$folder;
    $document->column_fields["assigned_user_id"] = 1;
    $document->save("Documents");

    $adb->pquery("Insert into vtiger_seattachmentsrel
            values (?,?)",array($document->id,$att_id));
    $adb->pquery("Insert into vtiger_senotesrel
            values (?,?)",array($record,$document->id));
        
}
