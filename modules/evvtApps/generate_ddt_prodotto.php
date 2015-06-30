<?php
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
$inoutmaster=$_REQUEST['record_id'];
 

    
    $sql1 = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        LEFT JOIN vtiger_account ON vtiger_account.accountid=vtiger_project.linktoaccountscontacts
                        LEFT JOIN vtiger_sites ON vtiger_account.accountid=vtiger_sites.accountid
                        LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
                        WHERE deleted=0
			AND project=$inoutmaster

        ";
    $result1 = $adb->query($sql1);
    $sql = "SELECT *
		FROM vtiger_project
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktobuyer
			WHERE projectid=$inoutmaster
        
        ";
    $result = $adb->query($sql);
    $sql2 = "SELECT * 
FROM vtiger_project
LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_project.products
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
LEFT JOIN vtiger_sites ON vtiger_account.accountid = vtiger_sites.accountid
LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
WHERE projectid =$inoutmaster";
   
    $result2 = $adb->query($sql2);
    $sql5 = "SELECT vtiger_account.accountid
FROM vtiger_project
LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_project.products
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
LEFT JOIN vtiger_sites ON vtiger_account.accountid = vtiger_sites.accountid
LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
WHERE projectid =$inoutmaster";
    $result5 = $adb->query($sql5);
      //echo $adb->query_result($result2,0,'accountname');exit;
    //$nr_doc=crp_series(); 
    //$log->debug('juli'.$nr_doc);
    $focus = CRMEntity::getInstance("Adocmaster");
    $focus->id='';

    $focus->column_fields['adocmastername']='OUTGOING DDT PRODOTTO';
    
    $prog =$adb->query_result($result1,0,'progetto');
    $sql3="SELECT projectname FROM vtiger_project WHERE projectid = $inoutmaster";
    $result3 = $adb->query($sql3);
    $projname= $adb->query_result($result3,0,'projectname');
    
    $account = $adb->query_result($result2,0,'linktobuyer');
     $sql4="SELECT accountname,codcat FROM vtiger_account WHERE accountid = $account";
    $result4 = $adb->query($sql4);
    $bill_city=$adb->query_result($result2,0,'bill_city');
$cat = $adb->query_result($result4,0,'codcat');
//$nr_doc = $nr_doc;
    $accountname= $adb->query_result($result4,0,'accountname');
   $query=$adb->pquery("Select cityname from vtiger_cities
        where vtiger_cities.citiesid=?",array($bill_city));
        $cityname=$adb->query_result($query,0,'cityname');

        
    $focus->column_fields['adoc_account']=$adb->query_result($result5,0,0);   
    $focus->column_fields['docdate_from']=$adb->query_result($result1,0,'startdate');   
    $focus->column_fields['project']=$inoutmaster; 
     $focus->column_fields['causaleadm']='OUTGOING DDT PRODOTTO';    
     $focus->column_fields['desriparazione']=$adb->query_result($result1,0,'descrip');
     $focus->column_fields['descdiffesterni']=$adb->query_result($result1,0,'external_defect_description');
     $focus->column_fields['produttore']=$adb->query_result($result1,0,'brand');
     $focus->column_fields['serialnr']=$adb->query_result($result1,0,'serial_number');
     $focus->column_fields['modelnumber']=$adb->query_result($result1,0,'model_number');
     $focus->column_fields['cittacat']=$cityname;
     $focus->column_fields['commitente']=$account;
     
     
     
     
     
      $focus->column_fields['inc_doc_data']=$adb->query_result($result2,0,'incoming_document_data');
      $focus->column_fields['inc_doc_number']=$adb->query_result($result2,0,'incoming_document_number');
     
      $focus->column_fields['projectrma']=$adb->query_result($result2,0,'rma');
       $focus->column_fields['projectcommessa']=$projname;
       $focus->column_fields['accountcommittente']=$accountname;
      
        $focus->column_fields['clientaddress']=$adb->query_result($result2,0,'bill_street');
        
        $ac = $adb->query_result($result5,0,0);
       
    
         // $focus->column_fields['accounttname']=  $adb->query_result($result6,0,'accountname');; 
        $focus->column_fields['telefonocliente']=$adb->query_result($result2,0,'phone');
          $focus->column_fields['cellularecliente']=$adb->query_result($result2,0,'cellulare');
        $focus->column_fields['indirizzocommittente']=$adb->query_result($result2,0,'phone');
     

       // $focus->column_fields['vettore']=$adb->query_result($result2,0,'distributor');
         $focus->column_fields['porto']="Porto Franco";
         $focus->column_fields['trasporto_vettore']="Vettore";
         $focus->column_fields['aspetto_esteriore_dei_beni']="Scatola";
          $focus->column_fields['nrcolli']="1";
        $focus->column_fields['causale']="Reso da riparazione";
         $focus->column_fields['causaleadm']="RESO DA RIPARAZIONE";
        
    $focus->column_fields['doctype']='OUTGOING DDT PRODOTTO'; 
    //$focus->column_fields['nrdoc']=$nr_doc; 
    $focus->column_fields['cap']=$adb->query_result($result1,0,'ship_codepr');
    $focus->column_fields['telefonocliente']=$adb->query_result($result1,0,'mphone');
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("Adocmaster"); 
 

    
  
        $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';

    $focus_det->column_fields['adocdetailname']=$adb->query_result($result2,0,'project_no');
    //$focus_det->column_fields['nrline']=$adb->query_result($result,$i,'linktoaccountscontacts');
    $focus_det->column_fields['adocdetail_project']=$inoutmaster;
    $focus_det->column_fields['adocdetailrma']=$adb->query_result($result2,0,'rma');
    $focus_det->column_fields['modprod']=$adb->query_result($result2,0,'model_number');
    $focus_det->column_fields['adoc_quantity']="1";
    $focus_det->column_fields['description']= $adb->query_result($result,0,'ragione_sociale');
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail"); 
      
    

 // crp_series();       
 
function crp_series(){
	global $adb,$log;
	$proj_id=$_REQUEST['record'];
 $progressive_final='';
        $settingquery="SELECT * FROM vtiger_setting 
                     WHERE tipo_documento='OUTGOING DDT PRODOTTO'   ";
        
      $setting=$adb->query($settingquery);
      $nrsetting=$adb->num_rows($setting);$log->debug('nr_test'.$nrsetting);
if($nrsetting>0){
      $settingid=$adb->query_result($setting,0,'settingid');
      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
      $suffisso=$adb->query_result($setting,0,'suffisso');
      }
else
{
$settingquery="SELECT * FROM vtiger_setting 
                     WHERE tipo_documento='OUTGOING DDT PRODOTTO'   ";
        
      $setting=$adb->query($settingquery);
      $nrsetting=$adb->num_rows($setting);
if($nrsetting>0){
      $settingid=$adb->query_result($setting,0,'settingid');
      $progressive=$adb->query_result($setting,0,'ultimo_no')+1;
      $suffisso=$adb->query_result($setting,0,'suffisso');
}
}
if($progressive/10 <1)
    $progressive_final='000'.$progressive;
    else if($progressive/100 <1)
        $progressive_final='00'.$progressive;
    else if($progressive/1000 <1)
        $progressive_final='0'.$progressive;
        else
          $progressive_final=$progressive;  
      
      $adb->query("UPDATE vtiger_setting set ultimo_no=$progressive, ultima_data='".date('Y-m-d')."' WHERE settingid=".$settingid);
      return $suffisso."-".$progressive_final;
}








?>