<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('modules/PCDetails/PCDetails.php');

global $adb,$current_user;
$kaction=$_REQUEST['kaction'];

$content=array();
if($kaction=='retrieve'){
global $log;

$query=$adb->pquery("Select * from vtiger_products
                    join vtiger_crmentity on vtiger_crmentity.crmid=productid 
                    join vtiger_location on vtiger_products.linktolocation=vtiger_location.locationid
                    where tipolocation=?",array('Car KIT'));
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['productid']=$adb->query_result($query,$i,'productid');
       $content[$i]['productname']=$adb->query_result($query,$i,'productname');
       $content[$i]['productsheet']=$adb->query_result($query,$i,'productsheet');
       }
echo json_encode($content);

}

elseif($kaction=='autocomplete'){
    
$entered_val=$_REQUEST['entered_val'];
$query=$adb->pquery("Select * from vtiger_products
                    join vtiger_crmentity on vtiger_crmentity.crmid=productid 
                    join vtiger_location on vtiger_products.linktolocation=vtiger_location.locationid
                    where tipolocation=? ",array('Locale'));
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['productid']=$adb->query_result($query,$i,'productid');
       $content[$i]['productname']=$adb->query_result($query,$i,'productname');
       $content[$i]['productsheet']=$adb->query_result($query,$i,'productsheet');
       }
echo json_encode($content);

}
elseif($kaction=='autocomplete_project'){
    
$entered_val=$_REQUEST['entered_val'];
$query=$adb->pquery("Select * from vtiger_project
                    join vtiger_crmentity on vtiger_crmentity.crmid=projectid 
                    where deleted=0 and ( substatusproj not like '%Closed%' OR substatusproj is null )
                    and ( statuslavor not like '%Closed%'  OR  statuslavor is null )
                    and smownerid=? and brand='Merkur' ",array($current_user->id));
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['projectid']=$adb->query_result($query,$i,'projectid');
       $content[$i]['project_no']=$adb->query_result($query,$i,'project_no');
       $content[$i]['projectname']=$adb->query_result($query,$i,'projectname');
       }
echo json_encode($content);

}
elseif($kaction=='products_kit'){
    
$query=$adb->pquery("Select * from vtiger_products
                    join vtiger_crmentity on vtiger_crmentity.crmid=productid 
                    join vtiger_location on vtiger_products.linktolocation=vtiger_location.locationid
                    where tipolocation=?",array('Car KIT'));
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['productid']=$adb->query_result($query,$i,'productid');
       $content[$i]['productname']=$adb->query_result($query,$i,'productname');
       $content[$i]['productsheet']=$adb->query_result($query,$i,'productsheet');
       }
echo json_encode($content);

}
elseif($kaction=='fine_rip'){
    
$query=$adb->pquery("Select * from vtiger_fineriparazione
                    ",array());
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['fineriparazione']=$adb->query_result($query,$i,'fineriparazione');
       }
echo json_encode($content);

}
elseif($kaction=='tecnico'){
    
$query=$adb->pquery("Select * from vtiger_tecnicirf
                    ",array());
$count=$adb->num_rows($query);
for($i=0;$i<$count;$i++){
       $content[$i]['tecnicirf']=$adb->query_result($query,$i,'tecnicirf');
       }
echo json_encode($content);

}
elseif($kaction=='retrieve_pcdetails'){
global $log;
$projectid=$_REQUEST['projectid'];
if( $projectid !='') {
    $query=$adb->pquery("Select * from vtiger_pcdetails
                        join vtiger_crmentity on vtiger_crmentity.crmid=pcdetailsid 
                        join vtiger_project on vtiger_project.projectid=vtiger_pcdetails.project
                        where deleted =0 And projectid=? 
                        and fineriparazione LIKE  '%nessuno%' ",array($projectid));
    $count=$adb->num_rows($query);
    for($i=0;$i<$count;$i++){
           $content[$i]['pcdetailsid']=$adb->query_result($query,$i,'pcdetailsid');
           $content[$i]['pcdescriptionname']=$adb->query_result($query,$i,'pcdescriptionname');
           $content[$i]['fineriparazione']=$adb->query_result($query,$i,'fineriparazione');
           $content[$i]['quantity']=$adb->query_result($query,$i,'quantity');
       }
}
echo json_encode($content);

}
elseif($kaction=='add_parti'){
  
require_once('modules/PCDetails/PCDetails.php');
$prodid=$_REQUEST['prodid'];
$id=$_REQUEST['project_id'];
$fine_rip=$_REQUEST['fine_rip'];

$focus = CRMEntity::getInstance("PCDetails");
    $focus->column_fields['fineriparazione']=$fine_rip; 
    $focus->column_fields['linktoproduct']=$prodid; 
    $focus->column_fields['project']=$id;
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("PCDetails"); 
}
elseif($kaction=='update'){
  
$id=$_REQUEST['pcdetailsid'];
$fine_rip=$_REQUEST['fine'];

$sql1 = "Update vtiger_pcdetails"
        . "  set  fineriparazione=?"
            . " where pcdetailsid=?";
$res=$adb->pquery($sql1,array($fine_rip,$id));
}
elseif($kaction=='upload' )
{
   
    global $log,$adb;
    
    $current_id = $adb->getUniqueID("vtiger_crmentity");
    $current_id=$current_id+1;

    $filename = ltrim(basename(" ".$_FILES['allega_doc']['name'])); //allowed filename like UTF-8 characters 
    $filetype= $_FILES['allega_doc']['type'];
    $filesize = $_FILES['allega_doc']['size'];
    $filetmp_name = $_FILES['allega_doc']['tmp_name'];

    $_SESSION['file_upload_kendo_chiudi']=$filename;
    $_SESSION['file_upload_kendo_chiudi_currid']=$current_id;
    $log->debug('filename '.$_SESSION['file_upload_kendo_chiudi'].$current_id);
    //get the file path inwhich folder we want to upload the file
    $upload_file_path = decideFilePath();
    $upload_status = move_uploaded_file($filetmp_name,$upload_file_path.$current_id."_".$filename);
                    
    echo true;

}
