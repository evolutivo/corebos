<?php
    global $adb,$current_language,$current_user;
    $tit="(Categoria per Numero N.)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
    $type="";
      $nu="a";
$reportid="96";
$focus1=new ReportRun($reportid);
	$currencyfieldres = $adb->pquery("SELECT tabid, fieldlabel, uitype from vtiger_field WHERE uitype in (71,72,10)", array());
		if($currencyfieldres) {
			foreach($currencyfieldres as $currencyfieldrow) {
				$modprefixedlabel = getTabModuleName($currencyfieldrow['tabid'])." ".$currencyfieldrow['fieldlabel'];
				$modprefixedlabel = str_replace(' ','_',$modprefixedlabel);

				if($currencyfieldrow['uitype']!=10){
					if(!in_array($modprefixedlabel, $focus1->convert_currency) && !in_array($modprefixedlabel, $focus1->append_currency_symbol_to_value)) {
						$focus1->convert_currency[] = $modprefixedlabel;
					}
				} else {

					if(!in_array($modprefixedlabel, $focus1->ui10_fields)) {
						$focus1->ui10_fields[] = $modprefixedlabel;
					}
				}
			}
		}
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
		$rspotsmax=$adb->query("SELECT vtiger_troubletickets.sorgentett as gr, REPLACE(CONCAT('\"',vtiger_troubletickets.sorgentett,'\"'),\"'\",\"\") as grpc FROM $query and  vtiger_troubletickets.sorgentett<> '' and vtiger_troubletickets.sorgentett is not null group by vtiger_troubletickets.sorgentett");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\") as cols FROM $query GROUP BY vtiger_troubletickets.reportingcategory");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["ticketid"]=$j;
                       $content[$j]["vtigertroubleticketsreportingcategory"]=$ptc;
              unset($ag1);        
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' and vtiger_troubletickets.sorgentett=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero N.");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_crmentityHelpDesk.createdtime,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_crmentityHelpDesk.createdtime,'%Y-%m-%d')<='$dttod' and vtiger_troubletickets.sorgentett=\"$cat[$m]\"");
             
}
  $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Sorgente"));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");

 if(""=="1"){
$agcel[$ii]=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["$mf$cat1$ii"]=$ag[$ii]." <br> ".$agcel[$ii];
}
else  $content[$j]["$mf$cat1$ii"]=$ag[$ii];
$ag1[$ii]+=$adb->query_result($rspots1,0,"$mf");
$ag2[$ii]+=$adb->query_result($rspots1,0,"$mf");
} 
        
             //  if(""=="1")
     //  $content[$j]["a$cat1"]=implode(" - ",$ag)." <br> ".implode(" - ",$agcel);
     //  else $content[$j]["a$cat1"]=implode(" - ",$ag);
         $content[$j]["totale"]=implode(" - ",$ag1);
         
          
    }
     if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_crmentityHelpDesk.createdtime,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_crmentityHelpDesk.createdtime,'%Y-%m-%d')<='$dttod'");
         for($ii1=0;$ii1<sizeof($mainfld1);$ii1++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii1]));
$agy[$ii1]=$adb->query_result($rspots2,0,"$mf");
$content[$j]["ytd$ii1"]=$agy[$ii1];
$agy2[$ii1]+=$adb->query_result($rspots2,0,"$mf");
} 
  }
  if(""!=""){
      $f=explode("#","");
          for($ik=0;$ik<sizeof($f);$ik++){
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' ");
$content[$j]["formula$ik"]=$adb->query_result($rspots3,0,"form");
$frm[$ik]+=$adb->query_result($rspots3,0,"form");}
}
  if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=floatval($f1[$ik]);
      $fcols[$ik]+=$f1[$ik];
  }
}
			$j++;
		}
                $content[$j]["ticketid"]=$j;
          $content[$j]["vtigertroubleticketsreportingcategory"]="Totale";
         $content[$j]["totale"]=implode(" - ",$ag2);  
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and vtiger_troubletickets.sorgentett=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero N.");
           $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Sorgente"));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");
$content[$j]["$mf$cat1$ii"]=$ag[$ii];
if(""=="1") $content[$j]["ytd$ii"]=$agy2[$ii];
} 
          
       //$content[$j]["a$cat1"]=implode(" - ",$ag);

   if(""!=""){    $f=explode("#","");
        for($ik=0;$ik<sizeof($f);$ik++){
       $content[$j]["formula$ik"]=$frm[$ik];}}
       
if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=$fcols[$ik];
    
  }
}
    }
               echo json_encode($content);}
              else echo json_encode("");

?>