<?php 
    include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
if("report"=="none" || "report"=="report"){
    $type="";
      $nu="a";
$reportid="62";
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
        if("1"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",1,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

 $group=array();
  $k=0;
  $fld=explode(",","Project_Progetto_collegato,Project_Project_Name,Project_Project_No,Project_Substatus,Project_RMA,Project_ID_progetto,Project_Created_Time,ProjectMilestone_Project_Milestone_Name,ProjectMilestone_Project_Milestone_No,ProjectMilestone_Data_chiamata_cliente,ProjectMilestone_data_ricezione_prodotto,ProjectMilestone_data_ricezione_laboratorio,ProjectMilestone_data_richiesta_parti,ProjectMilestone_data_ricezine_parti,ProjectMilestone_data_riparazione,ProjectMilestone_data_disponibile_per_restituzione,ProjectMilestone_data_closed,ProjectMilestone_data_inserimento_codice,ProjectMilestone_data_escalation_codice,ProjectMilestone_data_attivazione_corriere,ProjectMilestone_data_appuntamento,ProjectMilestone_Data_cancellazione,Project_Giorni_SLA,Project_Apertura_SLA,Project_Chiusura_SLA,Project_Verificato_SLa,Project_Verifica_SLa,Project_Giorni_apertura,Project_Log_SLA,Project_Presenza_Parti,Project_Data_sospensione,Project_Date_Shipped_,Project_Data_Richiesta_Parti_,Project_Actual_End_Date");

for($j=0;$j<sizeof($suba);$j++){
 $in=key(preg_grep("/\b$fld[$j]\b/i", $f));


 $ff1=explode('AS',$f[$in]);
 $ff2=explode(' as ',$ff1[0]);
 $ff=$ff2[0];

if($grp[$j]==1) {$group[$k]=$ff;
$k++;}
    if($suba[$j]!='')
        $fields.=$suba[$j].'('.$ff.') as '.$fld[$j].',';
    else $fields.=$ff.' as '.$fld[$j].',';
}


$group2=implode(",",$group);
                $qr=strtolower("select $fields vtiger_project.projectid as primid FROM ".$rq[1]." group by $group2");
}
        else{
        $qr=strtolower($rq[0]." ,vtiger_project.projectid as primid FROM ".$rq[1]);}
            }
        
          else {
              $query1="show columns from ";


	$result1 = $adb->query($query1);
	$num_rows1=$adb->num_rows($result1);
        if($num_rows1!=0){
$j=0;

	for($i1=1;$i1<=$num_rows1;$i1++)
	{
         $f=$adb->query_result($result1,$i1-1);
       if(substr($f,-2)=="id" && $j==0 ) {$fname=$f;
       $fname1=str_replace("id","",$fname);
       $tab=$adb->query("select * from vtiger_tab where name like \"$fname1\"");
       if($adb->num_rows($tab)!=0)
       $md1=$adb->query_result($tab,0,"name");
}
}}

          $qr="select $fname as primid,.* from  ";
            }
            if(""!="") $lim="Limit 0,";
        else $lim="";
              if($_REQUEST["condit"]!="") $co=$_REQUEST["condit"];
else $co="";
        $query=$adb->query($qr."  ".$co." ".$lim);
          
        $count=$adb->num_rows($query);
$fld=explode(",","Project_Progetto_collegato,Project_Project_Name,Project_Project_No,Project_Substatus,Project_RMA,Project_ID_progetto,Project_Created_Time,ProjectMilestone_Project_Milestone_Name,ProjectMilestone_Project_Milestone_No,ProjectMilestone_Data_chiamata_cliente,ProjectMilestone_data_ricezione_prodotto,ProjectMilestone_data_ricezione_laboratorio,ProjectMilestone_data_richiesta_parti,ProjectMilestone_data_ricezine_parti,ProjectMilestone_data_riparazione,ProjectMilestone_data_disponibile_per_restituzione,ProjectMilestone_data_closed,ProjectMilestone_data_inserimento_codice,ProjectMilestone_data_escalation_codice,ProjectMilestone_data_attivazione_corriere,ProjectMilestone_data_appuntamento,ProjectMilestone_Data_cancellazione,Project_Giorni_SLA,Project_Apertura_SLA,Project_Chiusura_SLA,Project_Verificato_SLa,Project_Verifica_SLa,Project_Giorni_apertura,Project_Log_SLA,Project_Presenza_Parti,Project_Data_sospensione,Project_Date_Shipped_,Project_Data_Richiesta_Parti_,Project_Actual_End_Date");
    $ch=explode(",","");
    if($count!=0){
for($i=0;$i<$count;$i++){
   $content[$i]["projectid"]=$adb->query_result($query,$i,"primid");
              for($k=0;$k<sizeof($fld);$k++){
$name=strtolower($fld[$k]);
$namec=$fld[$k];
$j=$ch[$k];
              $name1=str_replace(".","",str_replace("_","",strtolower($fld[$k])));
           if(strstr($name,"Assigned_To")){ $u=$adb->pquery("select * from vtiger_users where id=?",array($adb->query_result($query,$i,"$name")));
   if($adb->num_rows($u)!=0)
$us=getUsername($adb->query_result($query,$i,"$name"));
  else {$us1=getGroupname($adb->query_result($query,$i,"$name"));
          $us=$us1[0];
  }

   $content[$i]["$name1"]=$us;}
      else if (in_array($namec,$focus1->ui10_fields)) {


								$type = getSalesEntityType($adb->query_result($query,$i,"$name"));
								$tmp =getEntityName($type,$adb->query_result($query,$i,"$name"));
								if (is_array($tmp)){
									foreach($tmp as $key=>$val){
                                                                       if($val==null) $val="";
										  $content[$i]["$name1"] = $val;

										break;
									}
								}}
   else{
         if($adb->query_result($query,$i,"$name")==null) $s="";
          else $s=$adb->query_result($query,$i,"$name");
           $content[$i]["$name1"]=$s;}
       }
       if("Project"!="")
       $md1="Project";
         
       $content[$i]["url"]="index.php?module=$md1&action=DetailView&record=". $content[$i]["projectid"];
       }


echo json_encode($content);}
else echo json_encode("");

?>