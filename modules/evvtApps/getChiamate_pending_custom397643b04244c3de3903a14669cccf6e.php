<?php 
    include_once("modules/Reports/Reports.php");
include("modules/Reports/ReportRun.php");
global $adb;
if("report"=="none" || "report"=="report"){
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
        if("0"=="1"){

           $f=explode(",",str_replace('select','',$rq[0]));
    $grp=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");
    $suba=explode(",",",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,");

 $group=array();
  $k=0;
  $fld=explode(",","Project_Project_Name,Project_ID,Project_RMA,Project_Serial_Number,Project_Model_Number,Project_Substatus,Project_Account,Project_LBLGROUPBUYER,Project_Progetto_collegato,Project_Assigned_To,Project_Brand,Project_Id_product,Project_total_paid,Project_total_received,Project_total_time,Project_difference_time,Project_Estimate_external_Time,Project_Estimate_internal_Time,Project_Days_on,Project_Lavorato,Project_Invio_Box,Project_Created_Time,Project_Status,Project_Refurbishing,Project_Swap_con_prodotto_nuovo,Project_Spedizione_prodotto_di_cortesia,Project_On_Site,Project_Current_level,Project_Warranty,Project_Accettazione_Modello_Sostitutivo,Project_Tipo_Avviso,Project_Difetti_esterni,Project_Password_BIOS,Project_Difetto_dichiarato,Project_Project_No,Project_ID_progetto,Project_Apertura_SLA,Project_Chiusura_SLA,Project_Giorni_SLA,Project_Verifica_SLa,Project_Verificato_SLa,Project_Log_SLA,Project_Giorni_apertura,Project_Actual_End_Date,Project_Modified_Time");

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

        $query=$adb->query($qr." AND  vtiger_project.substatusproj NOT LIKE '%losed%' ".$co." ".$lim);
//        echo $qr." AND  vtiger_project.substatusproj NOT LIKE '%losed%' ".$co." ".$lim;  
        $count=$adb->num_rows($query);
$fld=explode(",","Project_Project_Name,Project_ID,Project_RMA,Project_Serial_Number,Project_Model_Number,Project_Substatus,Project_Account,Project_LBLGROUPBUYER,Project_Progetto_collegato,Project_Assigned_To,Project_Brand,Project_Id_product,Project_total_paid,Project_total_received,Project_total_time,Project_difference_time,Project_Estimate_external_Time,Project_Estimate_internal_Time,Project_Days_on,Project_Lavorato,Project_Invio_Box,Project_Created_Time,Project_Status,Project_Refurbishing,Project_Swap_con_prodotto_nuovo,Project_Spedizione_prodotto_di_cortesia,Project_On_Site,Project_Current_level,Project_Warranty,Project_Accettazione_Modello_Sostitutivo,Project_Tipo_Avviso,Project_Difetti_esterni,Project_Password_BIOS,Project_Difetto_dichiarato,Project_Project_No,Project_ID_progetto,Project_Apertura_SLA,Project_Chiusura_SLA,Project_Giorni_SLA,Project_Verifica_SLa,Project_Verificato_SLa,Project_Log_SLA,Project_Giorni_apertura,Project_Actual_End_Date,Project_Modified_Time");
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
