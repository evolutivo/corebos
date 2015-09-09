<?php
    global $adb,$current_language,$current_user;
    $tit="(Project_ID,Substatus,Commessa per Numero Project_ID)";
		
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

		$rspotsmax=$adb->query("SELECT vtiger_project.progetto as gr, REPLACE(CONCAT('\"',vtiger_project.progetto,'\"'),\"'\",\"\") as grpc FROM $query group by vtiger_project.progetto");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                   $j1=0;           
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_crmentity.crmid,' ',vtiger_project.substatusproj,' ',vtiger_project.progetto),\"'\",\"\") as cols FROM $query GROUP BY vtiger_crmentity.crmid,vtiger_project.substatusproj,vtiger_project.progetto");
		$data=Array();
                $j=0;
                        if($adb->num_rows($rspots)!=0){
		while ($pt=$adb->fetch_array($rspots)) {
                for($m=0;$m<sizeof($cat);$m++){$ptc=$pt['cols'];
                 if(""!="") $lim="Limit 0,";
        else $lim="";
               $rspots1=$adb->query("SELECT  count(vtiger_crmentity.crmid)  as vtigercrmentitycrmid  FROM $query  and  REPLACE(CONCAT(vtiger_crmentity.crmid,' ',vtiger_project.substatusproj,' ',vtiger_project.progetto),\"'\",\"\")='$ptc' and vtiger_project.progetto=\"$cat[$m]\" ");
       //     if($adb->num_rows($rspots1)==0 || $adb->query_result($rspots1,0,"cnt")=='')
             $content[$j1]["projectid"]=$j1;
                     $content[$j1]["vtigerprojectprogetto"]=$cat[$m];
                     $content[$j1]["vtigercrmentitycrmidvtigerprojectsubstatusprojvtigerprojectprogetto"]=$ptc;
                  $mainaggr1=explode(",","count");  
                   $mainfld1=explode(",","vtiger_crmentity.crmid"); 
                      for($ii=0;$ii<sizeof($mainaggr1);$ii++){
                      $mf=str_replace(array(".",",","_"),array("","",""),strtolower($mainfld1[$ii]));                 
$content[$j1]["$mf"]=$adb->query_result($rspots1,0,"$mf");        
}
$j1++;
    }
               
			$j++;
		}
               
               echo json_encode($content);}
              else echo json_encode("");

?>
