<?php
    class Graficovtapptest_customc7b48bbe88d040789f1bc5e2f79e8606 extends vtApp {

	var $hasedit = true;
	var $hasrefresh = true;
	var $hassize = true;
	var $candelete = true;
	var $wwidth = 1100;
	var $wheight = 600;
	var $haseditsize = true;
	var $ewidth = 0;
	var $eheight = 0;

public function getContent($lang) {
global $adb,$current_language,$current_user;
		$smarty = new vtigerCRM_Smarty;

                                $wdth=explode(",","200,200,200,200,200,200,200,200,200,200,200,200,150");
                                     $f=Array();
                                     $s=Array();
                                     $a=Array();
                                    $g=Array();
                                    	$smarty->template_dir = $this->apppath;
		$smarty->assign('appId',$this->appid);
		$smarty->assign('appPath',$this->apppath);
                $tit="(Project_Project_Name,Project_Start_Date,Project_Target_End_Date,Project_Actual_End_Date,Project_Status,Project_Related_to,Project_Target_Budget,Project_Progress,Project_Scenario_breve_termine,Project_Scenario_voglio_esserci,Project_Scenario_Medio,Project_Scenario_Obiettivo per Numero *)";
		$smarty->assign("Title",$this->getvtAppTranslatedString('Title', $current_language));
                $smarty->assign("Title2",$tit);
                 include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
    $type="";
      $nu=false;
$reportid="24";
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

        $rq=explode("from",$reportquery,2);
   $quer=explode("group by",$rq[1]);
   $query=$quer[0]."  ";
$i1=0;
		$rspotsmax=$adb->query("SELECT  as gr, REPLACE(CONCAT('\"',,'\"'),\"'\",\"\") as grpc FROM $query group by ");
		for($i=0;$i<$adb->num_rows($rspotsmax);$i++){
                    if($adb->query_result($rspotsmax,$i,"grpc")!=''){
                $potsmax[$i1]=$adb->query_result($rspotsmax,$i,"grpc");
                		$cat[$i1]=$adb->query_result($rspotsmax,$i,"gr");
                                $i1++;
                                }
}
                                $smarty->assign("cat",implode(",",$potsmax));
$dt=Array();
		$rspots=$adb->query("SELECT REPLACE(CONCAT(vtiger_project.projectname,' ',vtiger_project.startdate,' ',vtiger_project.targetenddate,' ',vtiger_project.actualenddate,' ',vtiger_project.projectstatus,' ',vtiger_project.linktoaccountscontacts,' ',vtiger_project.targetbudget,' ',vtiger_project.progress,' ',vtiger_project.scenbreve,' ',vtiger_project.scenwannabe,' ',vtiger_project.scenmedio,' ',vtiger_project.scenob),\"'\",\"\") as cols FROM $query GROUP BY vtiger_project.projectname,vtiger_project.startdate,vtiger_project.targetenddate,vtiger_project.actualenddate,vtiger_project.projectstatus,vtiger_project.linktoaccountscontacts,vtiger_project.targetbudget,vtiger_project.progress,vtiger_project.scenbreve,vtiger_project.scenwannabe,vtiger_project.scenmedio,vtiger_project.scenob");
		$data=Array();
                $j=0;
		while ($pt=$adb->fetch_array($rspots)) {
                for($m=0;$m<sizeof($cat);$m++){$ptc=$pt['cols'];
               $rspots1=$adb->query("SELECT  count(*)  as cnt FROM $query  and  REPLACE(CONCAT(vtiger_project.projectname,' ',vtiger_project.startdate,' ',vtiger_project.targetenddate,' ',vtiger_project.actualenddate,' ',vtiger_project.projectstatus,' ',vtiger_project.linktoaccountscontacts,' ',vtiger_project.targetbudget,' ',vtiger_project.progress,' ',vtiger_project.scenbreve,' ',vtiger_project.scenwannabe,' ',vtiger_project.scenmedio,' ',vtiger_project.scenob),\"'\",\"\")='$ptc' and =\"$cat[$m]\" ");
            if($adb->num_rows($rspots1)==0 || $adb->query_result($rspots1,0,"cnt")=='')
                       $dt[$j]=$dt[$j].'0,';
else
$dt[$j]=$dt[$j].$adb->query_result($rspots1,0,"cnt").',';
    }
                $dt[$j]=substr($dt[$j],0,sizeof($dt[$j])-2);
				$data[$j]="{name:'".$pt['cols']."',data:[$dt[$j]]}";
			$j++;
		}
                $data=implode(",",$data);
		$smarty->assign("PotData",$data);
		return $smarty->fetch("piechartvtapptest_customc7b48bbe88d040789f1bc5e2f79e8606.tpl");
	}

}
?>
