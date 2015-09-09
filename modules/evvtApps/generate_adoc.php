<?php
require_once('include/database/PearDatabase.php');
require_once('modules/Adocmaster/Adocmaster.php');
require_once('modules/Adocdetail/Adocdetail.php');
global $adb,$log,$current_user;
$inoutmaster=$_REQUEST['record_id'];
$tipo = $_REQUEST['type'];

    
    $sql1 = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        LEFT JOIN vtiger_account ON vtiger_account.accountid=vtiger_project.linktoaccountscontacts
                        LEFT JOIN vtiger_sites ON vtiger_account.accountid=vtiger_sites.accountid
                        WHERE deleted=0
			AND project=$inoutmaster

        ";
    $result1 = $adb->query($sql1);
    $sql = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_products ON vtiger_products.productid=vtiger_pcdetails.linktoproduct
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        WHERE deleted=0
			AND project=$inoutmaster
        
        ";
    $result = $adb->query($sql);
    $sql2 = "SELECT *
FROM vtiger_project
LEFT JOIN vtiger_products ON vtiger_products.productid = vtiger_project.products
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_project.linktoaccountscontacts
WHERE projectid =$inoutmaster";
    $result2 = $adb->query($sql2);
      //echo $adb->query_result($result2,0,'accountname');exit;
    $nr_doc=crp_series(); 
    $log->debug('juli'.$nr_doc);
    $focus = CRMEntity::getInstance("Adocmaster");
    $focus->id='';

    $focus->column_fields['adocmastername']='DDT PDF GENERATED';
    
    $prog =$adb->query_result($result1,0,'progetto');
    $sql3="SELECT projectname FROM vtiger_project WHERE projectid = $prog";
    $result3 = $adb->query($sql3);
    $projname= $adb->query_result($result3,0,'projectname');
    
    if(strpos($adb->query_result($result1,0,'projectname'),"CRP")===false)
    $focus->column_fields['adoc_account']=$adb->query_result($result1,0,'linktobuyer');//commitente
    else
    $focus->column_fields['adoc_account']=$adb->query_result($result1,0,'linktoaccountscontacts'); 
    

         $focus->column_fields['accounttname']=$adb->query_result($result2,0,'dealer');
   
    if(strpos($adb->query_result($result2,0,'projectname'),"PUR")!=false)
    $focus->column_fields['adoc_account']=$adb->query_result($result2,0,'accountid');   
    $focus->column_fields['docdate_from']=$adb->query_result($result1,0,'startdate');   
    $focus->column_fields['project']=$inoutmaster; 
    if(strpos($projname,"CAT")!=false)
     $focus->column_fields['causaleadm']='CONTO SOSTITUZIONE';    
    else if(strpos($adb->query_result($result1,0,'projectname'),"CRP")!=false)
            $focus->column_fields['causaleadm']='FORNITURA MAT. IN GARANZIA';
            else
      $focus->column_fields['causaleadm']='RESO DA RIPARAZIONE';   
    if($adb->query_result($result1,0,'progetto')==162799)
    $focus->column_fields['doctype']='Restituzione prodotto al cliente';   
    elseif($tipo!='prodotto')
    $focus->column_fields['doctype']='OUTGOING DDT PARTE';
    else
    $focus->column_fields['doctype']='OUTGOING DDT PRODOTTO';
    $focus->column_fields['nrdoc']=$nr_doc; 
    $focus->column_fields['cap']=$adb->query_result($result1,0,'ship_codepr');
    $focus->column_fields['telefonocliente']=$adb->query_result($result1,0,'mphone');
    $focus->column_fields['assigned_user_id']=$current_user->id;
    $focus->save("Adocmaster"); 
  if(strpos($adb->query_result($result2,0,'projectname'),"PUR")!=false)
  {
     
      $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';
     $focus_det->column_fields['adocdetailname']=$adb->query_result($result2,0,'projectname');
      $focus_det->column_fields['adoc_product']=$adb->query_result($result2,0,'productid');
      $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail"); 
      
      
  }else if(strpos($projname,"CAT")!=false && strpos($adb->query_result($result2,0,'projectname'),"CIN")!=false)
  {
      for($i=0;$i<$adb->num_rows($result);$i++)
    {
    $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';

    $focus_det->column_fields['adocdetailname']=$adb->query_result($result,$i,'pcdescriptionname');
    //$focus_det->column_fields['nrline']=$adb->query_result($result,$i,'linktoaccountscontacts');
  
    $focus_det->column_fields['adoc_product']=$adb->query_result($result,$i,'productid');
    $focus_det->column_fields['adoc_quantity']= $adb->query_result($result,$i,'quantity');
    $focus_det->column_fields['adoc_price']= $adb->query_result($result,$i,'teknemaunitprice'); 
//    $focus_det->column_fields['inout_docnr']= $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['poteknema']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['posupplier']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['riferimento']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['adoc_stock']= $adb->query_result($result,$i,'linktoaccountscontacts');
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail"); 
      
    } 
      
      
      
  } else
  { for($i=0;$i<$adb->num_rows($result);$i++)
    {
    $focus_det = CRMEntity::getInstance("Adocdetail");
    $focus_det->id='';

    $focus_det->column_fields['adocdetailname']=$adb->query_result($result,$i,'pcdescriptionname');
    //$focus_det->column_fields['nrline']=$adb->query_result($result,$i,'linktoaccountscontacts');
    if($adb->query_result($result1,0,'progetto')==162799)
    $focus_det->column_fields['adoc_product']=$adb->query_result($result2,$i,'productid');
    else
    $focus_det->column_fields['adoc_product']=$adb->query_result($result,$i,'productid');
    $focus_det->column_fields['adoc_quantity']= $adb->query_result($result,$i,'quantity');
    $focus_det->column_fields['adocdetail_project']= $inoutmaster;
    $focus_det->column_fields['adoc_price']= $adb->query_result($result,$i,'teknemaunitprice'); 
//    $focus_det->column_fields['inout_docnr']= $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['poteknema']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['posupplier']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['riferimento']=  $adb->query_result($result,$i,'linktoaccountscontacts');
//    $focus_det->column_fields['adoc_stock']= $adb->query_result($result,$i,'linktoaccountscontacts');
    $focus_det->column_fields['adoctomaster']= $focus->id;
    $focus_det->column_fields['assigned_user_id']= $current_user->id;
    $focus_det->save("Adocdetail"); 
      
    }}
 // crp_series();       
 
function crp_series(){
	global $adb,$log;
	$proj_id=$_REQUEST['record'];
 $progressive_final='';
        $settingquery="SELECT * FROM vtiger_setting 
                     WHERE tipo_documento='OUTGOING DDT' AND brand='TPV'  ";
        
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
                     WHERE tipo_documento='OUTGOING DDT'   ";
        
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

function getPcDetails(){
	global $adb,$log;
	$inoutmaster=$_REQUEST['record_id'];


        $sql1 = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
                        LEFT JOIN vtiger_account ON vtiger_account.accountid=vtiger_project.linktoaccountscontacts
                        LEFT JOIN vtiger_sites ON vtiger_account.accountid=vtiger_sites.accountid
                        WHERE deleted=0
			AND adocmasterid=$inoutmaster

        ";
	$result1 = $adb->query($sql1);

	$sql = "SELECT *
		FROM vtiger_pcdetails
			LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_pcdetails.pcdetailsid
                        LEFT JOIN vtiger_products ON vtiger_products.productid=vtiger_pcdetails.linktoproduct
                        LEFT JOIN vtiger_project ON vtiger_project.projectid=vtiger_pcdetails.project
                        LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
                        WHERE deleted=0
			AND adocmasterid=$inoutmaster
        
        ";
	$result = $adb->query($sql);
        $acc=$adb->query_result($result1,0,'linktoaccountscontacts');
        $acc_name=$adb->query_result($result1,0,'accountname');

        $bill_city=$adb->query_result($result1,0,'bill_city');
        $bill_state=$adb->query_result($result1,0,'bill_state');
        $bill_country=$adb->query_result($result1,0,'bill_country');

        $query=$adb->pquery("Select cityname from vtiger_cities
        where vtiger_cities.citiesid=?",array($bill_city));
        $cityname=$adb->query_result($query,0,'cityname');
        $query=$adb->pquery("Select countyname from vtiger_counties
        where vtiger_counties.countiesid=?",array(bill_state));
        $countyname=$adb->query_result($query,0,'countyname');
        $query=$adb->pquery("Select countriesname from vtiger_country
        where vtiger_country.countryid=?",array($bill_country));
        $countriesname=$adb->query_result($query,0,'countriesname');
        
        $return .= '<table border="0" cellpadding="1" cellspacing="1" style="border-collapse:collapse;width:1400px;">';
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;"><tr><td width="50%" height="100" style="vertical-align:top;padding-left:10px;border-left:black;border-bottom:black;"><font style="font-size:70%;font-family:Trebuchet MS;">LUOGO DI DESTINAZIONE<br/><br/>'.$acc_name.'<br/>'.$cityname.'<br/> '.$countyname.'<br/>'.$countriesname.'</font></td><td  width="50%" style="vertical-align:top;padding-left:10px;border-right:black;border-bottom:black;" ><font style="font-size:70%;font-family:Trebuchet MS;">DESTINATARIO: Residenza o domicilio<br/><br/>'.$acc_name.'<br/>'.$cityname.'<br/> '.$countyname.'<br/>'.$countriesname.'</font></td></tr></table></td></tr>';
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;"><tr><td ><table border="0" ><tr><td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:70%;font-family:Trebuchet MS;"> N. ORDINE</font></td>
            <td style="vertical-align:top;padding-left:30px;text-align:right;width:200px;" align="right" ><input type="checkbox">in conto<br/><input type="checkbox">a saldo</td><tr></table></td>
            <td style="vertical-align:top;padding-left:10px;"  width="20%"><font style="font-size:70%;font-family:Trebuchet MS;">DATA ORDINE</font></td>
            <td style="vertical-align:top;padding-left:10px;" width="50%"><font style="font-size:70%;font-family:Trebuchet MS;">Vs. P.I.V.A. o C.F.</font></td></tr>';
	$return .= '<tr>
            <td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:70%;font-family:Trebuchet MS;">CAUSALE DEL TRASPORTO<br/>CONTO SOSTITUZIONE </font></td>
            <td style="vertical-align:top;padding-left:10px;" width="20%"><font style="font-size:70%;font-family:Trebuchet MS;">PORTO FRANCO</font> &nbsp;<input type="checkbox"><br/><font style="font-size:70%;font-family:Trebuchet MS;">PORTO ASSEGNATO &nbsp;<input type="checkbox"></font></td>
            <td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:70%;font-family:Trebuchet MS;">TRASPORTO A CURA DEL </font> <br/>
            <input type="checkbox"><font style="font-size:70%;font-family:Trebuchet MS;">MITTENTE</font> &nbsp;&nbsp;<input type="checkbox"><font style="font-size:70%;font-family:Trebuchet MS;">DESTINATARIO</font>&nbsp;&nbsp;<input type="checkbox"> <font style="font-size:70%;font-family:Trebuchet MS;">VETTORE </font></td></tr>
            </table></td></tr>';//<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;">//</table></td></tr>
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;">
            <tr><td width="10%"  style="padding-left:10px;">QUANT </td>
            <td width="15%" style="padding-left:10px;">SPARE-PART </td>
            <td width="30%" style="padding-left:10px;">DESCRIZIONE </td>
            <td width="20%" style="padding-left:10px;">CHIAMATA </td>
            <td width="15%" style="padding-left:10px;">ORDINE</td></tr>
            ';

        while($row = $adb->fetchByAssoc($result)){
            if($row['pcdetailsstatus']=='SHIPPED' && ($row['qtyshipped'] !='' || $row['qtyshipped'] !='0'))
            {
            $warranty=$row['warranty'];
            if($warranty=='1')
                $proj=$row['project_no'];
            else
                $proj=$row['reforder'];
		$return .= '<tr>';
		$return .= '<td width="10%" style="padding-left:10px;" height="30">'.$row['quantity'].'</td>';
		$return .= '<td width="15%" style="padding-left:10px;">'.$row['productname'].'</td>';
		$return .= '<td width="30%" style="padding-left:10px;">'.$row['productsheet'].'</td>';
		$return .= '<td width="20%" style="padding-left:10px;">'.$proj.'</td>';
		$return .= '<td width="15%" style="padding-left:10px;">'.$row['pcdescriptionname'].'</td>';
		$return .= '</tr> ';
            }
	}
	$return .= '</table></td></tr></table>';
	return $return;
}

function distributor_name(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['record_id'];

        $query=$adb->pquery("Select distributorname from vtiger_distributor
            join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
            LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_project.projectid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
        $distributorname=$adb->query_result($query,0,'distributorname');
        else
            $distributorname='';
        return $distributorname;
}

function pi_vettore(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['record_id'];
        $query=$adb->pquery("Select pivettore from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
        where vtiger_project.projectid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $pivettore=$adb->query_result($query,0,'pivettore');
        else
            $pivettore='';
        return $pivettore;
}

function reg_auto(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['record_id'];
        $query=$adb->pquery("Select regauto from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
        where vtiger_project.projectid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
                $regauto=$adb->query_result($query,0,'regauto');
        else
            $regauto='';
        return $regauto;
}

function sede1(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['record_id'];
        $query=$adb->pquery("Select sede1 from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
        where vtiger_project.projectid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $sede1=$adb->query_result($query,0,'sede1');
        else
            $sede1='';
        return $sede1;
}

function sede2(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['record_id'];
        $query=$adb->pquery("Select sede2 from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
        where vtiger_project.projectid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $sede2=$adb->query_result($query,0,'sede2');
        else 
            $sede2='';
        return $sede2;
}

?>
