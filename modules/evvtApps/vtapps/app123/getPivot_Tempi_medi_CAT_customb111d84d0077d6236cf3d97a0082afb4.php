<?php
    global $adb,$current_language,$current_user;
    $tit="(Committente / CAT per Media Totale)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
$fp = fopen('Tempi_medi_CAT_customb111d84d0077d6236cf3d97a0082afb4.csv', 'a');
global $adb;
    $type="";
      $nu="a";
$reportid="153";
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
                if("none"=="mv")
                    $reportquery="select * from  where 1=1 ";
                    else
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
		$rspotsmax=$adb->query("SELECT  as gr, REPLACE(CONCAT('\"',,'\"'),\"'\",\"\") as grpc FROM $query   and  <> '' and  is not null group by ");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_project.linktobuyer),\"'\",\"\") as cols FROM $query GROUP BY vtiger_project.linktobuyer");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["projectid"]=$j;
                       $content[$j]["vtigerprojectlinktobuyer"]=$ptc;
              unset($ag1);        
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid,avg(vtiger_projectmilestone.tempoattesaparti)  as vtigerprojectmilestonetempoattesaparti,avg(vtiger_projectmilestone.tempoengagement)  as vtigerprojectmilestonetempoengagement,avg(vtiger_projectmilestone.tricezprod)  as vtigerprojectmilestonetricezprod,avg(vtiger_projectmilestone.tpartreq)  as vtigerprojectmilestonetpartreq,avg(vtiger_projectmilestone.sentbytek)  as vtigerprojectmilestonesentbytek,avg(vtiger_projectmilestone.tconfreceived)  as vtigerprojectmilestonetconfreceived,avg(vtiger_projectmilestone.trepairing)  as vtigerprojectmilestonetrepairing,avg(vtiger_projectmilestone.tready)  as vtigerprojectmilestonetready,avg(vtiger_projectmilestone.tempototale)  as vtigerprojectmilestonetempototale  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer),\"'\",\"\")='$ptc' and =\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid,vtiger_projectmilestone.tempoattesaparti,vtiger_projectmilestone.tempoengagement,vtiger_projectmilestone.tricezprod,vtiger_projectmilestone.tpartreq,vtiger_projectmilestone.sentbytek,vtiger_projectmilestone.tconfreceived,vtiger_projectmilestone.trepairing,vtiger_projectmilestone.tready,vtiger_projectmilestone.tempototale"); 
       $fagg1=explode(",","Numero N. Chiamate,Media attesa_parti,Media engage,Media ricezione prod.,Media part request,Media sent Tek,Media conf receiv.,Media repairing,Media ready deliv.,Media Totale");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid,avg(vtiger_projectmilestone.tempoattesaparti)  as vtigerprojectmilestonetempoattesaparti,avg(vtiger_projectmilestone.tempoengagement)  as vtigerprojectmilestonetempoengagement,avg(vtiger_projectmilestone.tricezprod)  as vtigerprojectmilestonetricezprod,avg(vtiger_projectmilestone.tpartreq)  as vtigerprojectmilestonetpartreq,avg(vtiger_projectmilestone.sentbytek)  as vtigerprojectmilestonesentbytek,avg(vtiger_projectmilestone.tconfreceived)  as vtigerprojectmilestonetconfreceived,avg(vtiger_projectmilestone.trepairing)  as vtigerprojectmilestonetrepairing,avg(vtiger_projectmilestone.tready)  as vtigerprojectmilestonetready,avg(vtiger_projectmilestone.tempototale)  as vtigerprojectmilestonetempototale  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer),\"'\",\"\")='$ptc' and DATE_FORMAT(,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(,'%Y-%m-%d')<='$dttod' and =\"$cat[$m]\"");
             
}
  $cat1=str_replace(array(".",",","_"," ","-"),array("","","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower(""));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");

 if(""=="1"){
$agcel[$ii]=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",",".")." <br> ".number_format($agcel[$ii],2,",",".");
}
else { $content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",","."); $formula[$j]["a$cat1$ii"]=$ag[$ii];}
$ag1[$ii]+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
$ag2[$ii]+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
} 
        
             //  if(""=="1")
     //  $content[$j]["a$cat1"]=implode(" - ",$ag)." <br> ".implode(" - ",$agcel);
     //  else $content[$j]["a$cat1"]=implode(" - ",$ag);
 
         
             }
     if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid,avg(vtiger_projectmilestone.tempoattesaparti)  as vtigerprojectmilestonetempoattesaparti,avg(vtiger_projectmilestone.tempoengagement)  as vtigerprojectmilestonetempoengagement,avg(vtiger_projectmilestone.tricezprod)  as vtigerprojectmilestonetricezprod,avg(vtiger_projectmilestone.tpartreq)  as vtigerprojectmilestonetpartreq,avg(vtiger_projectmilestone.sentbytek)  as vtigerprojectmilestonesentbytek,avg(vtiger_projectmilestone.tconfreceived)  as vtigerprojectmilestonetconfreceived,avg(vtiger_projectmilestone.trepairing)  as vtigerprojectmilestonetrepairing,avg(vtiger_projectmilestone.tready)  as vtigerprojectmilestonetready,avg(vtiger_projectmilestone.tempototale)  as vtigerprojectmilestonetempototale  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer),\"'\",\"\")='$ptc' and DATE_FORMAT(,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(,'%Y-%m-%d')<='$dttod'");
         for($ii1=0;$ii1<sizeof($mainfld1);$ii1++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii1]));
$agy[$ii1]=$adb->query_result($rspots2,0,"$mf");
$content[$j]["ytd$ii1"]=number_format($agy[$ii1],2,",",".");
$agy2[$ii1]+=$adb->query_result($rspots2,0,"$mf");
} 
  }
  if(""!=""){
      $f=explode("#","");
          for($ik=0;$ik<sizeof($f);$ik++){
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer),\"'\",\"\")='$ptc' ");
$content[$j]["formula$ik"]=number_format($adb->query_result($rspots3,0,"form"),2,",",".");
$frm[$ik]+=$adb->query_result($rspots3,0,"form");}
}
  if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format(floatval($f1[$ik]),2,",",".");
      $fcols[$ik]+=$f1[$ik];
  }
}
    if(""==1)
         $content[$j]["totale"]=implode(" - ",$ag1);
  fputcsv($fp, $content[$j],";");
			$j++;
		}
                $content[$j]["projectid"]=$j;
          $content[$j]["vtigerprojectlinktobuyer"]="Totale";
 
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid,avg(vtiger_projectmilestone.tempoattesaparti)  as vtigerprojectmilestonetempoattesaparti,avg(vtiger_projectmilestone.tempoengagement)  as vtigerprojectmilestonetempoengagement,avg(vtiger_projectmilestone.tricezprod)  as vtigerprojectmilestonetricezprod,avg(vtiger_projectmilestone.tpartreq)  as vtigerprojectmilestonetpartreq,avg(vtiger_projectmilestone.sentbytek)  as vtigerprojectmilestonesentbytek,avg(vtiger_projectmilestone.tconfreceived)  as vtigerprojectmilestonetconfreceived,avg(vtiger_projectmilestone.trepairing)  as vtigerprojectmilestonetrepairing,avg(vtiger_projectmilestone.tready)  as vtigerprojectmilestonetready,avg(vtiger_projectmilestone.tempototale)  as vtigerprojectmilestonetempototale  FROM $query  and =\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid,vtiger_projectmilestone.tempoattesaparti,vtiger_projectmilestone.tempoengagement,vtiger_projectmilestone.tricezprod,vtiger_projectmilestone.tpartreq,vtiger_projectmilestone.sentbytek,vtiger_projectmilestone.tconfreceived,vtiger_projectmilestone.trepairing,vtiger_projectmilestone.tready,vtiger_projectmilestone.tempototale"); 
       $fagg1=explode(",","Numero N. Chiamate,Media attesa_parti,Media engage,Media ricezione prod.,Media part request,Media sent Tek,Media conf receiv.,Media repairing,Media ready deliv.,Media Totale");
           $cat1=str_replace(array(".",",","_"," ","-"),array("","","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower(""));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");
$content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",",".");
$formula[$j]["a$cat1$ii"]=$ag[$ii];
} 
if($m==sizeof($cat)-1){
for($ii=0;$ii<sizeof($mainfld1);$ii++){
      if(""=="1") $content[$j]["ytd$ii"]=number_format($agy2[$ii],2,",","."); }   
       //$content[$j]["a$cat1"]=implode(" - ",$ag);

   if(""!=""){    $f=explode("#","");
        for($ik=0;$ik<sizeof($f);$ik++){
       $content[$j]["formula$ik"]=number_format($frm[$ik],2,",",".");}}
       
if(""!=""){
      $f1=explode("#",$a);
          for($ik=0;$ik<sizeof($f1);$ik++){
      
      $content[$j]["formulacol$ik"]=number_format($fcols[$ik],2,",",".");
    
  }
}  
if(""==1)         
$content[$j]["totale"]=implode(" - ",$ag2); }
    }
    fputcsv($fp, $content[$j],";");
    fclose($fp);
               echo json_encode($content);}
              else echo json_encode("");

?>
