<?php
    global $adb,$current_language,$current_user;
    $tit="(CAT per Somma TsentbyTek)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
$fp = fopen('Test_acerbis_cubo_custom29e97f5f5e5df3a157804ce79c6203e2.csv', 'a');
global $adb;
    $type="";
      $nu="a";
$reportid="";
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
                if("mv"=="mv")
                    $reportquery="select * from mv_soglia where 1=1 ";
                    else
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
		$rspotsmax=$adb->query("SELECT SOGLIE as gr, REPLACE(CONCAT('\"',SOGLIE,'\"'),\"'\",\"\") as grpc FROM $query   and  SOGLIE<> '' and SOGLIE is not null group by SOGLIE");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(CAT),\"'\",\"\") as cols FROM $query GROUP BY CAT");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["id"]=$j;
                       $content[$j]["cat"]=$ptc;
              unset($ag1);        
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  sum(Tricezioneprodotto)  as Tricezioneprodotto,sum(Tpartrequest)  as Tpartrequest,sum(TsentbyTek)  as TsentbyTek  FROM $query  and  REPLACE(CONCAT(CAT),\"'\",\"\")='$ptc' and SOGLIE=\"$cat[$m]\" ");
       $mainfld1=explode(",","Tricezioneprodotto,Tpartrequest,TsentbyTek"); 
       $fagg1=explode(",","Somma Tricezioneprodotto,Somma Tpartrequest,Somma TsentbyTek");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT  sum(Tricezioneprodotto)  as Tricezioneprodotto,sum(Tpartrequest)  as Tpartrequest,sum(TsentbyTek)  as TsentbyTek  FROM $query  and  REPLACE(CONCAT(CAT),\"'\",\"\")='$ptc' and DATE_FORMAT(Nessuno,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(Nessuno,'%Y-%m-%d')<='$dttod' and SOGLIE=\"$cat[$m]\"");
             
}
  $cat1=str_replace(array(".",",","_"," ","-"),array("","","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("SOGLIE"));
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
       $rspots2=$adb->query("SELECT  sum(Tricezioneprodotto)  as Tricezioneprodotto,sum(Tpartrequest)  as Tpartrequest,sum(TsentbyTek)  as TsentbyTek  FROM $query  and  REPLACE(CONCAT(CAT),\"'\",\"\")='$ptc' and DATE_FORMAT(Nessuno,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(Nessuno,'%Y-%m-%d')<='$dttod'");
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
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(CAT),\"'\",\"\")='$ptc' ");
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
                $content[$j]["id"]=$j;
          $content[$j]["cat"]="Totale";
 
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  sum(Tricezioneprodotto)  as Tricezioneprodotto,sum(Tpartrequest)  as Tpartrequest,sum(TsentbyTek)  as TsentbyTek  FROM $query  and SOGLIE=\"$cat[$m]\" ");
       $mainfld1=explode(",","Tricezioneprodotto,Tpartrequest,TsentbyTek"); 
       $fagg1=explode(",","Somma Tricezioneprodotto,Somma Tpartrequest,Somma TsentbyTek");
           $cat1=str_replace(array(".",",","_"," ","-"),array("","","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("SOGLIE"));
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
