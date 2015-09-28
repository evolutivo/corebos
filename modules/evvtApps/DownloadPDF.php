<?php
/*************************************************************************************************
 * Copyright 2012-2013 OpenCubed  --  
 * You can copy, adapt and distribute the work under the "Attribution-NonCommercial-ShareAlike"
 * Vizsage Public License (the "License"). You may not use this file except in compliance with the
 * License. Roughly speaking, non-commercial users may share and modify this code, but must give credit
 * and share improvements. However, for proper details please read the full License, available at
 * http://vizsage.com/license/Vizsage-License-BY-NC-SA.html and the handy reference for understanding
 * the full license at http://vizsage.com/license/Vizsage-Deed-BY-NC-SA.html. Unless required by
 * applicable law or agreed to in writing, any software distributed under the License is distributed
 * on an  "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the
 * License terms of Creative Commons Attribution-NonCommercial-ShareAlike 3.0 (the License).
 *************************************************************************************************
 *  Module       : evvtApps
 *  Version      : 1.8
 *  Author       : OpenCubed
 *************************************************************************************************/
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