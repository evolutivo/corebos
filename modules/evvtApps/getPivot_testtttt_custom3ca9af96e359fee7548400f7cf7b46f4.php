<?php
    global $adb,$current_language,$current_user;
    $tit="(CAT,da 0 a 2 gg Numero soglieid,da 03 a 5 gg Numero soglieid,da 06 a 9 gg Numero soglieid,da 10 a 15 gg Numero soglieid,da 16 a 20 gg Numero soglieid,over 21 gg Numero soglieid per Numero *)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
$fp = fopen('testtttt_custom3ca9af96e359fee7548400f7cf7b46f4.csv', 'a');
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
                    $reportquery="select * from mv_soglia_hr where 1=1 ";
                    else
$reportquery=$focus1->sGetSQLforReport($reportid,$nu);

        $rq=explode("from",$reportquery);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
 $fld=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR,ada0a2gg0,ada03a5gg0,ada06a9gg0,ada10a15gg0,ada16a20gg0,aover21gg0");
      $fldaggreg=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR,da 0 a 2 gg#count#soglieid,da 03 a 5 gg#count#soglieid,da 06 a 9 gg#count#soglieid,da 10 a 15 gg#count#soglieid,da 16 a 20 gg#count#soglieid,over 21 gg#count#soglieid");
                                  $fldn=explode(",","CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR,da 0 a 2 gg Numero soglieid,da 03 a 5 gg Numero soglieid,da 06 a 9 gg Numero soglieid,da 10 a 15 gg Numero soglieid,da 16 a 20 gg Numero soglieid,over 21 gg Numero soglieid");
                                          $fldnex=explode(","," ,CAT,SOGLIE,soglieid,TengagementclienteHR,TpartrequestHR,TsentbyTekHR,TconfirmreceivHR,TrepairingHR,TripsenzapartiHR,TcloseHR,TclosewithoutpartHR,da 0 a 2 gg Numero soglieid,da 03 a 5 gg Numero soglieid,da 06 a 9 gg Numero soglieid,da 10 a 15 gg Numero soglieid,da 16 a 20 gg Numero soglieid,over 21 gg Numero soglieid");
                                 $typef=explode(","," ,,,,,,,,,,,,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr,fieldaggr");
$checkf=explode(",","1,,,,,,,,,,,1,1,1,1,1,1");
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(),\"'\",\"\") as cols FROM $query GROUP BY ");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["id"]=$j;
                       $content[$j][""]=$ptc;
              unset($ag1);        
        for($m=0;$m<sizeof($fld);$m++){
          $aggreg=explode("#",$fldaggreg[$m]);
        $mf=str_replace(array(".",",","_"),array("","",""),strtolower($aggreg[2]));
        if($typef[$m]=="fieldaggr" && $checkf[$m]==1){
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and SOGLIE=\"$aggreg[0]\" ");
       //$mainfld1=explode(",",""); 
       $fagg1=explode(",","");
           if(""=="1"){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots22=$adb->query("SELECT $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and DATE_FORMAT(Nessuno,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(Nessuno,'%Y-%m-%d')<='$dttod' and SOGLIE=\"$cat[$m]\"");
             
}
  
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("SOGLIE"));     


$ag=$adb->query_result($rspots1,0,"$mf");

 if(""=="1"){
$agcel=$adb->query_result($rspots22,0,"$mf");
 $content[$j]["$fld[$m]"]=number_format($ag,2,",",".")." <br> ".number_format($agcel,2,",",".");
}
else { $content[$j]["$fld[$m]"]=number_format($ag,2,",","."); $formula[$j]["$fld[$m]"]=$ag;}
$ag1+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");
$ag2+=number_format($adb->query_result($rspots1,0,"$mf"),2,",",".");

                   
             }
               if($typef[$m]=="ytd" && $checkf[$m]==1){
            $dttod=date("Y-m-d",strtotime("-1 years",strtotime(date("Y-m-d"))));
             $dttod2=date("Y-m-d",strtotime("-2 years",strtotime(date("Y-m-d"))));
       $rspots2=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' and DATE_FORMAT(Nessuno,'%Y-%m-%d')>'$dttod2' and DATE_FORMAT(Nessuno,'%Y-%m-%d')<='$dttod'");
   
$agy=$adb->query_result($rspots2,0,"$mf");
$content[$j]["$fld[$m]"]=number_format($agy,2,",",".");
$agy2+=$adb->query_result($rspots2,0,"$mf");       }
}
  
 
  if(""!=""){
      $f=explode("#","");
          for($ik=0;$ik<sizeof($f);$ik++){
  $rspots3=$adb->query("SELECT  sum($f[$ik]) as form  FROM $query  and  REPLACE(CONCAT(),\"'\",\"\")='$ptc' ");
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
          $content[$j][""]="Totale";
 
        for($m=0;$m<sizeof($fld);$m++){
        $aggreg=explode("#",$fldaggreg[$m]);
        $mf=str_replace(array(".",",","_"),array("","",""),strtolower($aggreg[2]));
        if($typef[$m]=="fieldaggr" && $checkf[$m]==1){
      
       $rspots1=$adb->query("SELECT  $aggreg[1]($aggreg[2]) as $mf FROM $query  and SOGLIE=\"$aggreg[0]\" ");

       $fagg1=explode(",","");
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower("SOGLIE"));
 

$ag=$adb->query_result($rspots1,0,"$mf");
$content[$j]["$fld[$m]"]=number_format($ag,2,",",".");
$formula[$j]["$fld[$m]"]=$ag;
} 
 if($typef[$m]=="ytd" && $checkf[$m]==1){
      //if(""=="1") 
          $content[$j]["$fld[$m]"]=number_format($agy2,2,",","."); }
     
if($m==sizeof($fld)-1){


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
