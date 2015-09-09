<?php
function getFieldValue($fieldName,$module){
    global $adb;
    
    $ids=$_REQUEST["record"];
    if(empty($ids)) $ids=str_replace(";",",",$_REQUEST["recordvalpdf"]);
    $moduledata=$adb->pquery("Select entityidfield,tablename from vtiger_entityname where modulename=?",array($module));
    $entityidfield=$adb->query_result($moduledata,0,0);
    $tablename=$adb->query_result($moduledata,0,1);
    
    $query=$adb->pquery("Select $fieldName from $tablename
                         join vtiger_crmentity c on c.crmid=$entityidfield
                         where $entityidfield=? and c.deleted=0",array($ids));
    $value=$adb->query_result($query,0,0);
     if(in_array($fieldName,array("smownerid","smcreatorid","modifiedby"))) $value=  getUserFullName($value);
    return $value;
}

function getPcDetails(){
	global $adb,$log;
	$inoutmaster=$_REQUEST['record'];
        
if(empty($inoutmaster)) $inoutmaster=str_replace(";",",",$_REQUEST["recordvalpdf"]);

        $sql1 = "SELECT partitaiva, projectname, adoc_account, accountname, vtiger_accountbillads.bill_city, vtiger_accountbillads.bill_state, vtiger_accountbillads.bill_country, vtiger_accountbillads.bill_street
FROM vtiger_pcdetails
LEFT JOIN vtiger_crmentity ON vtiger_crmentity.crmid = vtiger_pcdetails.pcdetailsid
LEFT JOIN vtiger_project ON vtiger_project.projectid = vtiger_pcdetails.project
LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid = vtiger_adocmaster.project
LEFT JOIN vtiger_account ON vtiger_account.accountid = vtiger_adocmaster.adoc_account
LEFT JOIN vtiger_accountbillads ON vtiger_account.accountid = vtiger_accountbillads.accountaddressid
LEFT JOIN vtiger_sites ON vtiger_account.accountid = vtiger_sites.accountid
WHERE deleted =0
AND adocmasterid =$inoutmaster

        ";
       $log->debug('test5 ' .$sql1);
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
        
        $piva = $adb->query_result($result1,0,0);
        $pprojectname = $adb->query_result($result1,0,1);
        
        $acc=$adb->query_result($result1,0,2);
        $acc_name=$adb->query_result($result1,0,3);
        
        $bill_city=$adb->query_result($result1,0,4);
        $log->debug('test5 ' .$bill_city);
        $bill_state=$adb->query_result($result1,0,5);
        $bill_country=$adb->query_result($result1,0,6);
        $bill_street = $adb->query_result($result1,0,7);
        $query=$adb->pquery("Select cityname from vtiger_cities
        where vtiger_cities.citiesid=?",array($bill_city));
        $cityname=$adb->query_result($query,0,'cityname');$log->debug('test5 ' .$cityname);
        $query=$adb->pquery("Select countyname from vtiger_counties
        where vtiger_counties.countiesid=?",array($bill_state));
        $countyname=$adb->query_result($query,0,'countyname');$log->debug('test5 ' .$countyname);
        $query=$adb->pquery("Select countriesname from vtiger_country
        where vtiger_country.countryid=?",array($bill_country));
        $countriesname=$adb->query_result($query,0,'countriesname');
        
        $return .= '</br></br>';
        $return .= '<table border="0" cellpadding="1" cellspacing="1" style="border-collapse:collapse;width:1250px;">';
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1250px;"><tr><td width="50%" height="100" style="vertical-align:top;padding-left:10px;border-left:black;border-bottom:black;"><font style="font-size:12px;font-family:Trebuchet MS;">LUOGO DI DESTINAZIONE<br/><br/><br/>'.$acc_name.'<br/>'.$bill_street.'<br/>'.$cityname.'<br/> '.$countyname.'<br/>'.$countriesname.'</font></td><td  width="50%" style="vertical-align:top;padding-left:10px;border-right:black;border-bottom:black;" ><font style="font-size:12px;font-family:Trebuchet MS;">DESTINATARIO: Residenza o domicilio<br/><br/>'.$acc_name.'<br/>'.$bill_street.'<br/>'.$cityname.'<br/> '.$countyname.'<br/>'.$countriesname.'</font></td></tr></table></td></tr>';
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1250px;"><tr><td ><table border="0" ><tr>
            <tr></table></td>
            
            <td style="vertical-align:top;padding-left:10px;" width="50%"><font style="font-size:12px;font-family:Trebuchet MS;">Vs. P.I.V.A. o C.F.<br/><br/>'.$piva.'</font></td></tr>';
	$return .= '<tr>
            <td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:12px;font-family:Trebuchet MS;">CAUSALE DEL TRASPORTO<br/>CONTO SOSTITUZIONE </font></td>
            <td style="vertical-align:top;padding-left:10px;" width="20%"><font style="font-size:12px;font-family:Trebuchet MS;">PORTO FRANCO</font>  <input type="checkbox" checked><br/><font style="font-size:12px;font-family:Trebuchet MS;">PORTO ASSEGNATO  <input type="checkbox"></font></td>
            <td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:12px;font-family:Trebuchet MS;">TRASPORTO A CURA DEL </font> <br/>
            <input type="checkbox"><font style="font-size:12px;font-family:Trebuchet MS;">MITTENTE</font>   <input type="checkbox" checked><font style="font-size:12px;font-family:Trebuchet MS;">DESTINATARIO</font>  <input type="checkbox"> <font style="font-size:12px;font-family:Trebuchet MS;">VETTORE </font></td></tr>
            </table></br></br></td></tr>';//<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;">//</table></td></tr>
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1250px;">
            <tr><td width="10%"  style="padding-left:10px;">QUANT </td>
            <td width="15%" style="padding-left:10px;">SPARE-PART </td>
            <td width="30%" style="padding-left:10px;">DESCRIZIONE </td>
            <td width="20%" style="padding-left:10px;">CHIAMATA </td>
            <td width="15%" style="padding-left:10px;">ORDINE</td></tr>
            ';

        while($row = $adb->fetchByAssoc($result)){
            if($row['pcdetailsstatus']=='SHIPPED' && ($row['qtyshipped'] !='' || $row['qtyshipped'] !='0'))
            {
//            $warranty=$row['warranty'];
//            if($warranty=='1')
//                $proj=$row['project_no'];
//            else
//                $proj=$row['reforder'];
                $proj=$row['projectname'];
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

function crp_series(){
	global $adb,$log;
	$proj_id=&$_REQUEST['record'];
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
      
     // $adb->query("UPDATE vtiger_setting set ultimo_no=$progressive, ultima_data='".date('Y-m-d')."' WHERE settingid=".$settingid);
      return $suffisso."-".$progressive_final;
}


function distributor_name(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['recordvalpdf'];

        $query=$adb->pquery("Select distributorname from vtiger_distributor
            join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
         LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_adocmaster.adocmasterid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
        $distributorname=$adb->query_result($query,0,'distributorname');
        else
            $distributorname='';
        return $distributorname;
}

function pi_vettore(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['recordvalpdf'];
        $query=$adb->pquery("Select pivettore from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
      LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_adocmaster.adocmasterid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $pivettore=$adb->query_result($query,0,'pivettore');
        else
            $pivettore='';
        return $pivettore;
}

function reg_auto(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['recordvalpdf'];
        $query=$adb->pquery("Select regauto from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
      LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_adocmaster.adocmasterid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
                $regauto=$adb->query_result($query,0,'regauto');
        else
            $regauto='';
        return $regauto;
}

function sede1(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['recordvalpdf'];
        $query=$adb->pquery("Select sede1 from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
      LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_adocmaster.adocmasterid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $sede1=$adb->query_result($query,0,'sede1');
        else
            $sede1='';
        return $sede1;
}

function sede2(){

    global $adb,$log;
	$inoutmaster=&$_REQUEST['recordvalpdf'];
        $query=$adb->pquery("Select sede2 from vtiger_distributor
        join vtiger_project on vtiger_project.distributor=vtiger_distributor.distributorid
        LEFT JOIN vtiger_adocmaster ON vtiger_project.projectid=vtiger_adocmaster.project
        where vtiger_adocmaster.adocmasterid=?",array($inoutmaster));
        if($adb->num_rows($query)>0)
            $sede2=$adb->query_result($query,0,'sede2');
        else 
            $sede2='';
        return $sede2;
}
?>