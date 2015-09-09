<?php
    global $adb,$current_language,$current_user;
    $tit="(Substatus per Numero Project_Project_No)";
		
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
    $type="";
      $nu="a";
$reportid="38";
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
		$rspotsmax=$adb->query("SELECT vtiger_project.progetto as gr, REPLACE(CONCAT('\"',vtiger_project.progetto,'\"'),\"'\",\"\") as grpc FROM $query and  vtiger_project.progetto<> '' and vtiger_project.progetto is not null group by vtiger_project.progetto");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                          
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_project.substatusproj),\"'\",\"\") as cols FROM $query GROUP BY vtiger_project.substatusproj");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
        $ptc=$pt['cols'];
                       $content[$j]["projectid"]=$j;
                       $content[$j]["vtigerprojectsubstatusproj"]=$ptc;
                      
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_project.project_no)  as vtigerprojectprojectno  FROM $query  and  REPLACE(CONCAT(vtiger_project.substatusproj),\"'\",\"\")='$ptc' and vtiger_project.progetto=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_project.project_no"); 
       $fagg1=explode(",","Numero Project_Project_No");
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));
if($j==0)
$ag[$ii]=$fagg1[$ii].": ".$adb->query_result($rspots1,0,"$mf");
else $ag[$ii]=$adb->query_result($rspots1,0,"$mf");
} 
          $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower(Commessa));
       $content[$j]["$xf$cat1"]=implode(" - ",$ag);

    }
			$j++;
		}
                $content[$j]["projectid"]=$j;
          $content[$j]["vtigerprojectsubstatusproj"]="Totale";
                      
        for($m=0;$m<sizeof($cat);$m++){
       $rspots1=$adb->query("SELECT  count(vtiger_project.project_no)  as vtigerprojectprojectno  FROM $query  and vtiger_project.progetto=\"$cat[$m]\" ");
       $mainfld1=explode(",","vtiger_project.project_no"); 
       $fagg1=explode(",","Numero Project_Project_No");
               for($ii=0;$ii<sizeof($mainfld1);$ii++){
$mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));
if($j==0)
$ag[$ii]=$fagg1[$ii].": ".$adb->query_result($rspots1,0,"$mf");
else $ag[$ii]=$adb->query_result($rspots1,0,"$mf");
} 
          $cat1=str_replace(array(".",",","_"," "),array("","","",""),strtolower($cat[$m]));
          $xf=str_replace(array(".",",","_"," "),array("","","",""),strtolower(Commessa));
       $content[$j]["$xf$cat1"]=implode(" - ",$ag);

    }
               echo json_encode($content);}
              else echo json_encode("");

?>
