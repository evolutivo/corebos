                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    		<?php 
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $adb;
global $current_user; 
$userid = $current_user->id ;
$query1="SELECT * FROM `vtiger_users2group` WHERE `userid` = $userid";
$result1 = $adb->query($query1);
$group = $adb->query_result($result1,1,'groupid');

$query="SELECT * 
FROM vtiger_notes
 JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid
JOIN vtiger_attachmentsfolder ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid
JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_crmentity.crmid
WHERE smownerid = $group AND deleted =0
ORDER BY data_pubb_notifica DESC";

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
$query2 = "SELECT * FROM  notifiche_confirm WHERE  account_id = $userid";
$result2 = $adb->query($query2);
$num_rows2=$adb->num_rows($result2);
$diff = $num_rows - $num_rows2;
echo '<script> alert("   sono '. $diff .' messaggi da leggere    "); </script>';

                                                             ?>                                                                                                                                                                                                                                                           
        <script>
(function(H){H.className=H.className.replace(/\bgoogle\b/,'google-js')})(document.documentElement)


</script>
<link href="include/hlightbox/home/css/default.css" rel="stylesheet" />
<link href="include/hlightbox/home/css/maia.css" rel="stylesheet" />
<link href="include/hlightbox/home/css/gweb/lightbox.css" rel="stylesheet" />
		
<div id="maia-main" role="main">
	<div class="maia-cols">
		<div class="maia-col-6" style="height:200px;width:200px;float:left;">
			<div class="impact-award">
				<div class="lightbox-intro">
					<img height ="80"  src="http://vtiger1.teknema.it/teknema/themes/images/assistenzatecnica.jpg" />
					
			
<?php 

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $adb;
global $current_user; 
$userid = $current_user->id ;
$query1="SELECT * FROM `vtiger_users2group` WHERE `userid` = $userid";
$result1 = $adb->query($query1);
$group = $adb->query_result($result1,1,'groupid');

$query="SELECT * 
FROM vtiger_notes
 JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid
JOIN vtiger_attachmentsfolder ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid
JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_crmentity.crmid
WHERE deleted =0
AND smownerid = $group
ORDER BY data_pubb_notifica DESC";

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
$query2 = "SELECT * FROM  notifiche_confirm WHERE  account_id = $userid";
$result2 = $adb->query($query2);
$num_rows2=$adb->num_rows($result2);
$count = 0;
$count2 =0;
for($i=1;$i<=$num_rows;$i++){

if($adb->query_result($result,$i-1,'folderid')==6){
$count2++;
 for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') == $adb->query_result($result2,$j-1,'notesid'))
           $count++;



}

}
}

$count1 = $count2-$count;


echo '
<strong style="color:red;"><h2> '. $count1 .'      </h2> </strong><h2>
						Supporto Tecnico</h2>
					<a class="learn-more lb" data-lightbox-height="600" data-lightbox-width="600" href="#charity_water">Ulteriori informazioni</a></div>
				<div class="lightbox-content" id="charity_water">
					</br></br></br></br></br></br></br></br></br></br></br></br>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example" width="100%">
    <thead>
        <tr>
            <th width="60%">Nome</th>
           <th width="20%">Data Pubblicazione</th>
            <th width="20%" aligh><center>  Stato</center></th>
           
        </tr>
    </thead>

';
echo '<div id="doc"></div>';
for($i=1;$i<=$num_rows;$i++)
	{if($adb->query_result($result,$i-1,'foldername')=="Technical Support"){

if($num_rows2 != 0){
$t = 0;
       for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') != $adb->query_result($result2,$j-1,'notesid') && $t ==0 )
           $var = 'NON  LETTA'; 
else
{$var ='LETTA';$t=1;}

}}else{ $var ='NON LETTA';}
$d =  strtotime($adb->query_result($result,$i-1,'data_pubb_notifica'));
$dt = date('d-m-Y',$d);
if($adb->query_result($result,$i-1,'notecontent') != "")
{
$v = $adb->query_result($result,$i-1,'notecontent');
$vart=$adb->query_result($result,$i-1,'notesid');
 ?>
<script type="text/javascript">

function PopupCentrata()
{ 
var myWindow = window.open("open.php?var=<?php echo $vart; ?>","","width=600,height=600");

}

</script>
<?php
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" PopupCentrata()">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'. $dt.'</td><td>'. $var .'</td>';
}else {
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" ">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'.$dt.'</td><td>'. $var .'</td>';}
}}


                                                             ?>
</table>
                                                
                                                                                             
				</div>
			</div>
		</div>
            
		<div class="maia-col-6" style="height:200px;width:200px;float:left;">
			<div class="impact-award">
				<div class="lightbox-intro">
					<img height ="80"  src="http://vtiger1.teknema.it/teknema/themes/images/marketing.jpg" />
					
					
					
<?php 
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $adb;
global $current_user; 
$userid = $current_user->id ;
$query1="SELECT * FROM `vtiger_users2group` WHERE `userid` = $userid";
$result1 = $adb->query($query1);
$group = $adb->query_result($result1,1,'groupid');

$query="SELECT * 
FROM vtiger_notes
 JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid
JOIN vtiger_attachmentsfolder ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid
JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_crmentity.crmid
WHERE smownerid = $group AND deleted =0
ORDER BY data_pubb_notifica DESC";

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
$query2 = "SELECT * FROM  notifiche_confirm WHERE  account_id = $userid";
$result2 = $adb->query($query2);
$num_rows2=$adb->num_rows($result2);
$count = 0;
$count2 =0;
for($i=1;$i<=$num_rows;$i++){

if($adb->query_result($result,$i-1,'foldername')=="PERFORMANCE"){
$count2++;
 for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') == $adb->query_result($result2,$j-1,'notesid'))
           $count++;



}

}
}

$count1 = $count2-$count;


echo '
<strong style="color:red;"><h2> '. $count1 .'      </h2> </strong><h2>
						Performance</h2>
					<a class="learn-more lb" data-lightbox-height="600" data-lightbox-width="600" href="#consortium">Ulteriori informazioni</a></div>
				<div class="lightbox-content" id="consortium">
</br></br></br></br></br></br></br></br></br></br></br></br>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example1" width="100%">
    <thead>
        <tr>
            <th width="60%">Nome</th>
           <th width="20%">Data Pubblicazione</th>
            <th width="20%" aligh><center>  Stato</center></th>
           
        </tr>
    </thead>

';

for($i=1;$i<=$num_rows;$i++)
	{if($adb->query_result($result,$i-1,'foldername')=="PERFORMANCE"){
if($num_rows2 != 0){
$t = 0;
       for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') != $adb->query_result($result2,$j-1,'notesid') && $t ==0 )
           $var = 'NON  LETTA'; 
else
{$var ='LETTA';$t=1;}

}}else{ $var ='NON LETTA';}
$d =  strtotime($adb->query_result($result,$i-1,'data_pubb_notifica'));
$dt = date('d-m-Y',$d);
if($adb->query_result($result,$i-1,'notecontent') != "")
{echo '<script>
function PopupCentrata1()
{
 var myWindow = window.open("open.php?var='.$adb->query_result($result,$i-1,'notesid').'","","width=600,height=600");
}

</script>
';
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" PopupCentrata1()">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'. $dt.'</td><td>'. $var .'</td>';
}else {
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" ">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'.$dt.'</td><td>'. $var .'</td>';}

}}


                                                             ?>
</table>
                                       
		
				</div>
			</div>
		</div>
            <div class="maia-col-6" style="height:200px;width:240px;float:left;">
			<div class="impact-award">
				<div class="lightbox-intro">
					<img height ="80" src="http://vtiger1.teknema.it/teknema/themes/images/informaziooperative.jpg" />
					
					
				
<?php 
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $adb;
global $current_user; 
$userid = $current_user->id ;
$query1="SELECT * FROM `vtiger_users2group` WHERE `userid` = $userid";
$result1 = $adb->query($query1);
$group = $adb->query_result($result1,1,'groupid');

$query="SELECT * 
FROM vtiger_notes
 JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid
JOIN vtiger_attachmentsfolder ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid
JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_crmentity.crmid
WHERE smownerid = $group AND deleted =0
ORDER BY data_pubb_notifica DESC";

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);

$query2 = "SELECT * FROM  notifiche_confirm WHERE  account_id = $userid";
$result2 = $adb->query($query2);
$num_rows2=$adb->num_rows($result2);
$count = 0;
$count2 =0;
for($i=1;$i<=$num_rows;$i++){

if($adb->query_result($result,$i-1,'folderid')==8){
$count2++;
 for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') == $adb->query_result($result2,$j-1,'notesid'))
           $count++;



}

}
}

$count1 = $count2-$count;


echo '
<strong style="color:red;"><h2> '. $count1 .'      </h2> </strong><h2>
						Informazioni Operative</h2>
					<a class="learn-more lb" data-lightbox-height="600" data-lightbox-width="600" href="#charity_water1">Ulteriori informazioni</a></div>
				<div class="lightbox-content" id="charity_water1">
					</br></br></br></br></br></br></br></br></br></br></br></br>
				<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example2" width="100%">
    <thead>
        <tr>
           <th width="60%">Nome</th>
           <th width="20%">Data Pubblicazione</th>
            <th width="20%" aligh><center>  Stato</center></th>
           
        </tr>
    </thead>

';

for($i=1;$i<=$num_rows;$i++)
	{if($adb->query_result($result,$i-1,'folderid')==8){
if($num_rows2 != 0){
$t = 0;
       for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') != $adb->query_result($result2,$j-1,'notesid') && $t ==0 )
           $var = 'NON  LETTA'; 
else
{$var ='LETTA';$t=1;}

}}else{ $var ='NON LETTA';}
$d =  strtotime($adb->query_result($result,$i-1,'data_pubb_notifica'));
$dt = date('d-m-Y',$d);
if($adb->query_result($result,$i-1,'notecontent') != "")
{echo '<script>
function PopupCentrata2()
{
 var myWindow = window.open("open.php?var='.$adb->query_result($result,$i-1,'notesid').'","","width=600,height=600");
}

</script>
';
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" PopupCentrata2()">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'. $dt.'</td><td>'. $var .'</td>';
}else {
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" ">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'.$dt.'</td><td>'. $var .'</td>';}
}}


                                                             ?>
                                          </table>     
                                                                                             
				</div>
			</div>
		</div>
               <div class="maia-col-6" style="height:220px;width:240px;float:left;">
			<div class="impact-award">
				<div class="lightbox-intro">
					<img height ="80" src="http://vtiger1.teknema.it/teknema/themes/images/img12.jpg" />
					
					
					
<?php 
require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $adb;
global $current_user; 
$userid = $current_user->id ;
$query1="SELECT * FROM `vtiger_users2group` WHERE `userid` = $userid";
$result1 = $adb->query($query1);
$group = $adb->query_result($result1,1,'groupid');
$query="SELECT * 
FROM vtiger_notes
 JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_notes.notesid
JOIN vtiger_attachmentsfolder ON vtiger_attachmentsfolder.folderid = vtiger_notes.folderid
JOIN vtiger_seattachmentsrel ON vtiger_seattachmentsrel.crmid = vtiger_crmentity.crmid
WHERE smownerid =$group  AND deleted =0
ORDER BY data_pubb_notifica DESC";
$result = $adb->query($query);
$num_rows=$adb->num_rows($result);


$query2 = "SELECT * FROM  notifiche_confirm WHERE  account_id = $userid";
$result2 = $adb->query($query2);
$num_rows2=$adb->num_rows($result2);
$count = 0;
$count2 =0;
for($i=1;$i<=$num_rows;$i++){

if($adb->query_result($result,$i-1,'folderid')==9){
$count2++;
 for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') == $adb->query_result($result2,$j-1,'notesid'))
           $count++;



}

}
}

$count1 = $count2-$count;


echo '
<strong style="color:red;"><h2> '. $count1 .'      </h2> </strong><h2>
						Info. Comm.li e Amm.ve </h2>
					<a class="learn-more lb" data-lightbox-height="600" data-lightbox-width="600" href="#charity_water2">Ulteriori informazioni</a></div>
				<div class="lightbox-content" id="charity_water2">
					</br></br></br></br></br></br></br></br></br></br></br></br>
					<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example3" width="100%">
    <thead>
        <tr>
            <th width="60%">Nome</th>
           <th width="20%">Data Pubblicazione</th>
            <th width="20%" aligh><center>  Stato</center></th>
           
        </tr>
    </thead>

';

for($i=1;$i<=$num_rows;$i++)
	{if($adb->query_result($result,$i-1,'folderid')==9){
if($num_rows2 != 0){
$t = 0;
       for($j=1;$j<= $num_rows2;$j++)
    {  if ($adb->query_result($result,$i-1,'notesid') != $adb->query_result($result2,$j-1,'notesid') && $t ==0 )
           $var = 'NON  LETTA'; 
else
{$var ='LETTA';$t=1;}

}}else{ $var ='NON LETTA';}
$d =  strtotime($adb->query_result($result,$i-1,'data_pubb_notifica'));
$dt = date('d-m-Y',$d);
if($adb->query_result($result,$i-1,'notecontent') != "")
{echo '<script>
function PopupCentrata3()
{
var myWindow = window.open("open.php?var='.$adb->query_result($result,$i-1,'notesid').'","","width=600,height=600");
}

</script>
';
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" PopupCentrata3()">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'. $dt.'</td><td>'. $var .'</td>';
}else {
echo '<tr><td align="left" ><a href="index.php?module=uploads&action=downloadfile&entityid='.$adb->query_result($result,$i-1,'notesid').'&fileid='.$adb->query_result($result,$i-1,'attachmentsid').'&fil=1&acc='.$userid.' " title="Download file" onclick=" ">'.$adb->query_result($result,$i-1,'title').'</a></td><td>'.$dt.'</td><td>'. $var .'</td>';}

}}


                                                             ?>
</table>
                                               
                                                                                             
				</div>
			</div>
		</div>
	</div>
	
	
<script src="include/hlightbox/home/js/giving.min.js"></script>
<script src="include/hlightbox/home/js/google.js"></script>
<script src="include/hlightbox/home/js/maia.js"></script>
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    		
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        