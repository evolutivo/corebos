<?php
/*************************************************************************************************
* Copyright 2012-2013 OpenCubed  --  This file is a part of vtMktDashboard.
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
*  Module       : BusinessActions
*  Version      : 1.8
*  Author       : OpenCubed
*************************************************************************************************/
require_once('include/utils/CommonUtils.php');
global $default_charset;

if(isset($_REQUEST['action_id']) && $_REQUEST['action_id'] !='') {
  $query = "select * from vtiger_businessactions where businessactionsid={$_REQUEST['action_id']}";
  $res = $adb->query($query);
  $subject = addslashes($adb->query_result($res, 0, 'subject'));
  $body = addcslashes(decode_html($adb->query_result($res, 0, 'template')), "'\r\n");
}
?>
<script type="text/javascript">
//my changes
if(typeof window.opener.document.getElementById('subject') != 'undefined' &&
	window.opener.document.getElementById('subject') != null){
	window.opener.document.getElementById('subject').value = '<?php echo $subject?>';
	window.opener.document.getElementById('description').value = '<?php echo $body?>';
	window.opener.oCKeditor.setData('<?php echo $body?>');
}
window.close();
</script>