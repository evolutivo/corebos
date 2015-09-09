<?php
    global $adb,$current_language,$current_user;
    $tit="(Committente,Commessa per Numero Project_ID)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
    $type="";
      $nu="a";
$reportid="116";
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
		$rspotsmax=$adb->query("SELECT vtiger_project.substatusproj as gr, REPLACE(CONCAT('\"',vtiger_project.substatusproj,'\"'),\"'\",\"\") as grpc FROM $query and  vtiger_project.substatusproj<> '' and vtiger_project.substatusproj is not null group by vtiger_project.substatusproj");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_project.linktobuyer,' ',vtiger_project.progetto),\"'\",\"\") as cols FROM $query GROUP BY vtiger_project.linktobuyer,vtiger_project.progetto");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["projectid"]=$j;
                       $content[$j]["vtigerprojectlinktobuyervtigerprojectprogetto"]=$ptc;
              unset($ag1);        
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer,' ',vtiger_project.progetto),\"'\",\"\")='$ptc' and vtiger_project.substatusproj=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero Project_ID");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer,' ',vtiger_project.progetto),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_project.customer_purchase_data,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_project.customer_purchase_data,'%Y-%m-%d')<='$dttod' and vtiger_project.substatusproj=\"$cat[$m]\"");
             
}
  $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("ubstatus"));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");

 if(""=="1"){
$agcel[$ii]=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",",".")." <br> ".number_format($agcel[$ii],2,",",".");
}
else  $content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",",".");
$ag1[$ii]+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
$ag2[$ii]+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
} 
        
             //  if(""=="1")
     //  $content[$j]["a$cat1"]=implode(" - ",$ag)." <br> ".implode(" - ",$agcel);
     //  else $content[$j]["a$cat1"]=implode(" - ",$ag);
         $content[$j]["totale"]=implode(" - ",$ag1);
         
          
    }
     if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer,' ',vtiger_project.progetto),\"'\",\"\")='$ptc' and DATE_FORMAT(vtiger_project.customer_purchase_data,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(vtiger_project.customer_purchase_data,'%Y-%m-%d')<='$dttod'");
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
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(vtiger_project.linktobuyer,' ',vtiger_project.progetto),\"'\",\"\")='$ptc' ");
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
			$j++;
		}
                $content[$j]["projectid"]=$j;
          $content[$j]["vtigerprojectlinktobuyervtigerprojectprogetto"]="Totale";
         $content[$j]["totale"]=implode(" - ",$ag2);  
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and vtiger_project.substatusproj=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_crmentity.crmid"); 
       $fagg1=explode(",","Numero Project_ID");
           $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("ubstatus"));
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));

$ag[$ii]=$adb->query_result($rspots1,0,"$mf");
$content[$j]["a$cat1$ii"]=number_format($ag[$ii],2,",",".");
if(""=="1") $content[$j]["ytd$ii"]=number_format($agy2[$ii],2,",",".");
} 
          
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
    }
               echo json_encode($content);}
              else echo json_encode("");

?>
