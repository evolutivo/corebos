  <?php
  require_once('include/utils/CommonUtils.php');
  
  global $adb,$log;
  $kaction=$_REQUEST['kaction'];
  $selected=$_REQUEST['selected'];
  $pointing_module=$_REQUEST['pointing_module'];
  $tabid=  getTabid($pointing_module);

  $content=array();
  if($kaction=='retrieve'){
    $qry="Select fieldid,uitype,fieldlabel,fieldname,columnname,block,tabid from vtiger_field ";
    if($pointing_module!='') //only the fields of the pointing module
    {
        $qry.="   where vtiger_field.tabid='$tabid' ";
    }
    $query=$adb->query($qry); 
    $count=$adb->num_rows($query);
   for($i=0;$i<$count;$i++){
      $content[$i]['fieldid']=$adb->query_result($query,$i,'fieldid');
      $content[$i]['uitype']=$adb->query_result($query,$i,'uitype');
      //echo "brisi".$adb->query_result($query,$i,'uitype');
      $content[$i]['fieldlabel']=getTranslatedString($adb->query_result($query,$i,'fieldlabel'),$pointing_module);
      $content[$i]['fieldname']=$adb->query_result($query,$i,'fieldname');
      $content[$i]['columnname']=$adb->query_result($query,$i,'columnname');
      $content[$i]['block']=$adb->query_result($query,$i,'block');
      $content[$i]['tabid']=$adb->query_result($query,$i,'tabid');
      if(strpos($selected,$content[$i]['columnname'])!==false)
      $content[$i]['ticked']=true;
 
      }
    echo json_encode($content);
    }
 ?>
