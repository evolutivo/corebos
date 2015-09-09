<?php
    global $adb,$current_language,$current_user;
    $tit="(Categoria per Numero HelpDesk_ID)";
		
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
		$rspotsmax=$adb->query("SELECT vtiger_troubletickets.status as gr, REPLACE(CONCAT('\"',vtiger_troubletickets.status,'\"'),\"'\",\"\") as grpc FROM $query and  vtiger_troubletickets.status<> '' and vtiger_troubletickets.status is not null group by vtiger_troubletickets.status");
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
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' and vtiger_troubletickets.status=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero HelpDesk_ID");
           
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));
if($j==0)
$ag[$ii]=$fagg1[$ii].": ".$adb->query_result($rspots1,0,"$mf");
else $ag[$ii]=$adb->query_result($rspots1,0,"$mf");
$ag1[$ii]+=$adb->query_result($rspots1,0,"$mf");
$ag2[$ii]+=$adb->query_result($rspots1,0,"$mf");
} 
          $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Status"));
       $content[$j]["$xf$cat1"]=implode(" - ",$ag);
         $content[$j]["totale"]=implode(" - ",$ag1);
          
    }
     if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' and ='$dttod'");
         for($ii1=0;$ii1<sizeof($mainfld1);$ii1++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii1]));
$agy[$ii1]=$adb->query_result($rspots2,0,"$mf");
$agy2[$ii1]+=$adb->query_result($rspots2,0,"$mf");
} 
  $content[$j]["ytd"]=implode(" - ",$agy);}
  if(""!=""){
  $rspots3=$adb->query("SELECT  sum() as form  FROM $query  and  REPLACE(CONCAT(vtiger_troubletickets.reportingcategory),\"'\",\"\")='$ptc' ");
$content[$j]["formula"]=$adb->query_result($rspots3,0,"form");
$frm+=$adb->query_result($rspots3,0,"form");
}
			$j++;
		}
                $content[$j]["ticketid"]=$j;
          $content[$j]["vtigertroubleticketsreportingcategory"]="Totale";
         $content[$j]["totale"]=implode(" - ",$ag2);  
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and vtiger_troubletickets.status=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero HelpDesk_ID");
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));
if($j==0)
$ag[$ii]=$fagg1[$ii].": ".$adb->query_result($rspots1,0,"$mf");
else $ag[$ii]=$adb->query_result($rspots1,0,"$mf");
} 
          $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("Status"));
       $content[$j]["$xf$cat1"]=implode(" - ",$ag);
if(""=="1") $content[$j]["ytd"]=implode(" - ",$agy2);
   if(""!="") $content[$j]["formula"]=$frm;
    }
               echo json_encode($content);}
              else echo json_encode("");

?>
