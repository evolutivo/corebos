<?php

/* * *******************************************************************************

 * * The contents of this file are subject to the Evolutivo BPM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ****************************************************************************** */
 require_once("modules/evvtApps/pdfcreator/dompdf_config.inc.php");
  $content='
<link href="include/kendoui/styles/kendo.default.css" rel="stylesheet" />';
 $content.= html_entity_decode($_POST['contenthtml'], ENT_COMPAT, 'UTF-8');

 $filename=$_POST['filename'];
 $paper=$_REQUEST['paper'];
 $orientation=$_REQUEST['orientation'];
 
 
 $dompdf = new DOMPDF();
 $dompdf->load_html($content);
 $dompdf->set_paper("$paper","$orientation");
 $dompdf->render();

 $dompdf->stream("$filename.pdf", array("Attachment" => false));
 exit(0);
?>