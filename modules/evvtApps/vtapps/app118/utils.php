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
            <td style="vertical-align:top;padding-left:10px;" width="20%"><font style="font-size:70%;font-family:Trebuchet MS;">PORTO FRANCO</font>  <input type="checkbox"><br/><font style="font-size:70%;font-family:Trebuchet MS;">PORTO ASSEGNATO  <input type="checkbox"></font></td>
            <td style="vertical-align:top;padding-left:10px;" width="40%"><font style="font-size:70%;font-family:Trebuchet MS;">TRASPORTO A CURA DEL </font> <br/>
            <input type="checkbox"><font style="font-size:70%;font-family:Trebuchet MS;">MITTENTE</font>   <input type="checkbox"><font style="font-size:70%;font-family:Trebuchet MS;">DESTINATARIO</font>  <input type="checkbox"> <font style="font-size:70%;font-family:Trebuchet MS;">VETTORE </font></td></tr>
            </table></td></tr>';//<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;">//</table></td></tr>
	$return .= '<tr><td colspan ="5"><table border="1" style="border-collapse:collapse;width:1400px;">
            <tr><td width="10%"  style="padding-left:10px;">QUANT </td>
            <td width="15%" style="padding-left:10px;">SPARE-PART </td>
            <td width="30%" style="padding-left:10px;">DESCRIZIONE </td>
            <td width="20%" style="padding-left:10px;">CHIAMATA </td>
            <td width="15%" style="padding-left:10px;">ORDINE</td></tr>
            ';

        while($row = $adb->fetchByAssoc($result)){
            if($row['qtyshipped'] !='' || $row['qtyshipped'] !='0')
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
?>